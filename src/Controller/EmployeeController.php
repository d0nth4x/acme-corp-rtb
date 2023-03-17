<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Manager\EmployeeManager;
use App\Model\Filter\EmployeeFilter;
use App\Model\Employee as EmployeeModel;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Requirement\Requirement;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Tag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Tag('Employee')]
class EmployeeController extends AbstractFOSRestController
{
    private const SERIALIZER_GROUPS = [
        'employees_list',
    ];

    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns array of Employees',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Employee::class, groups: self::SERIALIZER_GROUPS))
        )
    )]
    #[Rest\QueryParam(
        name: 'jobTitleId',
        requirements: Requirement::POSITIVE_INT,
        strict: true,
        nullable: true
    )]
    #[Rest\QueryParam(
        name: 'limitFrom',
        requirements: Requirement::POSITIVE_INT,
        strict: true,
        nullable: true
    )]
    #[Rest\QueryParam(
        name: 'limitTo',
        requirements: Requirement::POSITIVE_INT,
        strict: true,
        nullable: true
    )]
    #[Rest\QueryParam(
        name: 'onlyRoot',
        requirements: '0|1',
        nullable: true,
        default: 0,
        description: '0: load all, 1: only root',
        strict: true
    )]
    #[Rest\Get(path: '/api/employees')]
    public function getAction(
        EmployeeManager $employeeManager,
        ParamFetcherInterface $paramFetcher,
        SerializerInterface $serializer
    ): Response {
        /** @var Serializer $serializer */
        $filter = $serializer->fromArray($paramFetcher->all(), EmployeeFilter::class);
        $view = $this->view(
            $employeeManager->filterBy($filter),
        );

        $context = new Context();
        $context->setGroups(self::SERIALIZER_GROUPS);
        $view->setContext($context);

        return $this->handleView($view);
    }

    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns Employee',
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: Employee::class, groups: self::SERIALIZER_GROUPS)
        )
    )]
    #[Rest\Get(
        '/api/employees/{employee}',
        requirements: ['employee' => Requirement::POSITIVE_INT]
    )]
    public function getOneAction(Employee $employee): Response
    {
        $view = $this->view($employee);

        $context = new Context();
        $context->setGroups(self::SERIALIZER_GROUPS);
        $view->setContext($context);

        return $this->handleView($view);
    }

    #[Rest\QueryParam(
        name: 'direct',
        requirements: '0|1',
        nullable: true,
        default: 0,
        description: '0: load all children, 1: only direct',
        strict: true
    )]
    #[Rest\Get(
        '/api/employees/{employee}/subordinates',
        requirements: ['employee' => Requirement::POSITIVE_INT]
    )]
    public function getSubordinatesAction(
        Employee $employee,
        EmployeeManager $employeeManager,
        ParamFetcherInterface $paramFetcher
    ): Response {
        $view = $this->view(
            $employeeManager->findSubordinates($employee, (bool) $paramFetcher->get('direct'))
        );

        $context = new Context();
        $context->setGroups(self::SERIALIZER_GROUPS);
        $view->setContext($context);

        return $this->handleView($view);
    }

    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns Employee',
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: Employee::class)
        )
    )]
    #[OA\Post(
        requestBody: new OA\RequestBody(
            description: 'Employee object',
            content: new OA\JsonContent(ref: new Model(type: EmployeeModel::class))
        )
    )]
    #[ParamConverter('employee', converter: 'fos_rest.request_body')]
    #[Rest\Post(path: '/api/employees')]
    public function postAction(
        EmployeeModel $employee,
        EmployeeManager $employeeManager,
        ConstraintViolationListInterface $validationErrors
    ): Response {
        if ($validationErrors->count() > 0) {
            throw new BadRequestHttpException((string) $validationErrors);
        }

        return $this->handleView(
            $this->view(
                $employeeManager->create($employee)
            )
        );
    }

    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns Employee',
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: Employee::class, groups: self::SERIALIZER_GROUPS)
        )
    )]
    #[OA\Put(
        requestBody: new OA\RequestBody(
            description: 'Employee object',
            content: new OA\JsonContent(ref: new Model(type: EmployeeModel::class))
        )
    )]
    #[ParamConverter('employeeModel', converter: 'fos_rest.request_body')]
    #[Rest\Put('/api/employees/{employee}', requirements: ['employee' => Requirement::POSITIVE_INT])]
    public function putAction(
        Employee $employee,
        EmployeeModel $employeeModel,
        EmployeeManager $employeeManager,
        ConstraintViolationListInterface $validationErrors
    ): Response {
        if ($validationErrors->count() > 0) {
            throw new BadRequestHttpException((string) $validationErrors);
        }

        $view = $this->view(
            $employeeManager->update($employeeModel, $employee)
        );

        $context = new Context();
        $context->setGroups(self::SERIALIZER_GROUPS);
        $view->setContext($context);

        return $this->handleView($view);
    }

    #[Rest\Delete('/api/employees/{employee}', requirements: ['id' => Requirement::POSITIVE_INT])]
    public function deleteAction(Employee $employee, EmployeeManager $employeeManager): Response
    {
        $employeeManager->remove($employee);

        return $this->handleView(
            $this->view(
                ['message' => 'Success']
            )
        );
    }
}
