<?php

namespace App\Controller;

use App\Entity\JobTitle;
use App\Model\Filter\JobTitleFilter;
use App\Model\JobTitle as JobTitleModel;
use App\Manager\JobTitleManager;
use App\Model\Employee;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Tag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Tag('JobTitle')]
#[Security(name: 'Bearer')]
class JobTitleController extends AbstractFOSRestController
{
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Return JobTitle objects',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: JobTitle::class))
        )
    )]
    #[Rest\QueryParam(
        name: 'limitFrom',
        requirements: '\d+',
        description: 'Pagination limit from.',
        strict: true,
        nullable: true
    )]
    #[Rest\QueryParam(
        name: 'limitTo',
        requirements: '\d+',
        description: 'Pagination limit to.',
        strict: true,
        nullable: true
    )]
    #[Rest\QueryParam(
        name: 'enabled',
        requirements: '0|1',
        description: 'enabled',
        strict: true,
        nullable: true
    )]
    #[Rest\Get('/api/job-titles')]
    public function getAction(
        JobTitleManager $jobTitleManager,
        SerializerInterface $serializer,
        ParamFetcherInterface $paramFetcher
    ): Response {
        $filter = $serializer->fromArray($paramFetcher->all(), JobTitleFilter::class);

        return $this->handleView(
            $this->view(
                $jobTitleManager->filterBy($filter)
            )
        );
    }

    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns Employee',
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: Employee::class)
        )
    )]
    #[Rest\Get('/api/job-titles/{jobTitle}', requirements: ['jobTitle' => Requirement::POSITIVE_INT])]
    public function getOneAction(JobTitle $jobTitle): Response
    {
        $view = $this->view($jobTitle);

        return $this->handleView($view);
    }

    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns JobTitle',
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: JobTitle::class)
        )
    )]
    #[OA\Post(
        requestBody: new OA\RequestBody(
            description: 'JobTitle object',
            content: new OA\JsonContent(ref: new Model(type: JobTitleModel::class))
        )
    )]
    #[ParamConverter('jobTitleModel', class: JobTitleModel::class, converter: 'fos_rest.request_body')]
    #[Rest\Post('/api/job-titles')]
    public function postAction(
        JobTitleModel $jobTitleModel,
        JobTitleManager $jobTitleManager,
        ConstraintViolationListInterface $validationErrors
    ): Response {
        if ($validationErrors->count() > 0) {
            throw new BadRequestHttpException((string) $validationErrors);
        }

        return $this->handleView(
            $this->view(
                $jobTitleManager->create($jobTitleModel)
            )
        );
    }

    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns JobTitle',
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: Employee::class)
        )
    )]
    #[OA\Put(
        requestBody: new OA\RequestBody(
            description: 'JobTitle object',
            content: new OA\JsonContent(ref: new Model(type: JobTitleModel::class))
        )
    )]
    #[ParamConverter('jobTitleModel', class: JobTitleModel::class, converter: 'fos_rest.request_body')]
    #[Rest\Put('/api/job-titles/{jobTitle}', requirements: ['jobTitle' => Requirement::POSITIVE_INT])]
    public function putAction(
        JobTitle $jobTitle,
        JobTitleModel $jobTitleModel,
        JobTitleManager $jobTitleManager,
        ConstraintViolationListInterface $validationErrors
    ): Response {
        if ($validationErrors->count() > 0) {
            throw new BadRequestHttpException((string) $validationErrors);
        }

        return $this->handleView(
            $this->view(
                $jobTitleManager->update($jobTitleModel, $jobTitle)
            )
        );
    }

    #[Rest\Delete('/api/job-titles/{jobTitle}', requirements: ['jobTitle' => Requirement::POSITIVE_INT])]
    public function deleteAction(JobTitle $jobTitle, JobTitleManager $jobTitleManager): Response
    {
        $jobTitleManager->remove($jobTitle);

        return $this->handleView(
            $this->view(
                ['message' => 'Success']
            )
        );
    }
}
