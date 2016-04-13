<?php

namespace AppBundle\Handler\Grade;

use AppBundle\Handler\ApiRelationHandlerInterface;
use AppBundle\Model\Grade\GradeSessionInterface;
use AppBundle\Model\Grade\GradeSessionRepository;
use AppBundle\Form\Model\GradeSessionFormModel;
use AppBundle\Form\Type\GradeSessionFormType;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class ApiGradeSessionHandler
 * @package AppBundle\Handler\Grade
 */
class ApiGradeSessionHandler implements ApiRelationHandlerInterface
{
    /**
     * @var GradeSessionRepository
     */
    protected $repository;
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * Init Handler.
     *
     * @param GradeSessionRepository $repository
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(GradeSessionRepository $repository, FormFactoryInterface $formFactory)
    {
        $this->repository = $repository;
        $this->formFactory = $formFactory;
    }

    /**
     * Insert object to repository.
     *
     * @param array $params
     */
    public function post($id, array $params)
    {
        $gradeSessionModel = new GradeSessionFormModel();
        $form = $this->formFactory->create(GradeSessionFormType::class, $gradeSessionModel, array('method' => 'POST'));

        $form->submit($params);

        if ($form->isValid()) {
            $data = $form->getData();

            $rawGradeSession = $this->repository->findNew($id, $data->getStartDate(), $data->getEndDate());
            $grade = $this->repository->insert($rawGradeSession);

            return $grade;
        }

        return $form;
    }

    public function getList($id, array $criteria, array $sort = null, $limit = null, $skip = null)
    {
        // TODO: Implement getList() method.
    }

    public function get($id, $session_id)
    {
        // TODO: Implement get() method.
    }

    public function put($id, $session_id, array $params)
    {
        // TODO: Implement put() method.
    }
}