<?php

namespace AppBundle\Handler\Grade;

use AppBundle\Form\Model\FileFormModel;
use AppBundle\Form\Type\FileFormType;
use AppBundle\Handler\ApiHandlerInterface;
use AppBundle\Model\Grade\GradeInterface;
use AppBundle\Model\Grade\GradeRepository;
use AppBundle\Form\Model\GradeFormModel;
use AppBundle\Form\Type\GradeFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class ApiGradeHandler.
 */
class ApiGradeHandler implements ApiHandlerInterface
{
    /**
     * @var GradeRepository
     */
    protected $repository;
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * Init Handler.
     *
     * @param GradeRepository      $repository
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(GradeRepository $repository, FormFactoryInterface $formFactory)
    {
        $this->repository = $repository;
        $this->formFactory = $formFactory;
    }

    /**
     * Get object list from repository.
     *
     * @param array $criteria
     * @param array $sort
     * @param int   $limit
     * @param int   $skip
     */
    public function getList(array $criteria, array $sort = null, $limit = null, $skip = null)
    {
        return $this->repository->findBy($criteria, $sort, $limit, $skip);
    }

    /**
     * Get object from repository.
     *
     * @param int $id
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Insert object to repository.
     *
     * @param array $params
     */
    public function post(array $params)
    {
        $gradeModel = new GradeFormModel();
        $form = $this->formFactory->create(GradeFormType::class, $gradeModel, array('method' => 'POST'));
        $form->submit($params);

        if ($form->isValid()) {
            $data = $form->getData();

            $rawGrade = $this->repository->findNew($data->getSubject(), $data->getDescription());
            $grade = $this->repository->insert($rawGrade);

            return $grade;
        }

        return $form;
    }

    /**
     * @param int|string $course
     * @param File       $file
     *
     * @return GradeInterface|\Symfony\Component\Form\FormInterface
     */
    public function postPicture($id, File $file = null)
    {
        $fileModel = new FileFormModel();
        $form = $this->formFactory->create(FileFormType::class, $fileModel, array('method' => 'POST'));
        $form->submit(array('imageFile' => $file));

        if ($form->isValid()) {
            $grade = $this->repository->find($id);

            return $this->repository->addFile(
                $grade,
                $fileModel->getImageFile(),
                $fileModel->getImageName()
            );
        }

        return $form;
    }

    /**
     * Update object from repository.
     *
     * @param $id
     * @param array $params
     */
    public function put($id, array $params)
    {
        $gradeModel = new GradeFormModel();
        $form = $this->formFactory->create(GradeFormType::class, $gradeModel, array('method' => 'PUT'));
        $form->submit($params);

        if ($form->isValid()) {
            $data = $form->getData();

            $rawCourse = $this->repository->find($id);
            $rawCourse
                ->setSubject($data->getSubject())
                ->setDescription($data->getDescription());
            $this->repository->update();

            return $rawCourse;
        }

        return $form;
    }

    /**
     * @param int|string $id
     */
    public function delete($id)
    {
        $this->repository->remove($id);
    }
}
