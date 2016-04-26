<?php

namespace AppBundle\Handler\Course;

use AppBundle\Form\Model\FileFormModel;
use AppBundle\Form\Type\FileFormType;
use AppBundle\Model\Course\CourseRepository;
use AppBundle\Model\Course\CourseInterface;
use AppBundle\Handler\ApiHandlerInterface;
use AppBundle\Form\Model\CourseFormModel;
use AppBundle\Form\Type\CourseFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * ApiCourseHandler.
 */
class ApiCourseHandler implements ApiHandlerInterface
{
    /**
     * @var CourseRepository
     */
    protected $repository;
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * Init Handler.
     *
     * @param CourseRepository     $repository
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(CourseRepository $repository, FormFactoryInterface $formFactory)
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
     * @param int|string $id
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
        $courseModel = new CourseFormModel();
        $form = $this->formFactory->create(CourseFormType::class, $courseModel, array('method' => 'POST'));
        $form->submit($params);

        if ($form->isValid()) {
            $rawCourse = $this->insertFromForm($form->getData());
            $course = $this->repository->insert($rawCourse);

            return $course;
        }

        return $form;
    }

    /**
     * @param int|string $course
     * @param File       $file
     *
     * @return CourseInterface|\Symfony\Component\Form\FormInterface
     */
    public function postPicture($id, File $file = null)
    {
        $fileModel = new FileFormModel();
        $form = $this->formFactory->create(FileFormType::class, $fileModel, array('method' => 'POST'));
        $form->submit(array('imageFile' => $file));

        if ($form->isValid()) {
            $course = $this->repository->find($id);

            return $this->repository->addFile(
                $course,
                $fileModel->getImageFile(),
                $fileModel->getImageName()
            );
        }

        return $form;
    }

    /**
     * Update object from repository.
     *
     * @param int|string $id
     * @param array      $params
     */
    public function put($id, array $params)
    {
        $courseModel = new CourseFormModel();
        $form = $this->formFactory->create(CourseFormType::class, $courseModel, array('method' => 'PUT'));
        $form->submit($params);

        if ($form->isValid()) {
            $rawCourse = $this->updateFromForm($id, $form->getData());
            $this->repository->update();

            return $this->repository->find($rawCourse);
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

    /**
     * @param CourseFormModel $courseModel
     *
     * @return CourseInterface
     */
    protected function insertFromForm(CourseFormModel $courseModel)
    {
        $course = $this->repository->findNew();

        return $this->fromForm($course, $courseModel);
    }

    /**
     * @param int|string      $id
     * @param CourseFormModel $courseModel
     *
     * @return CourseInterface
     */
    protected function updateFromForm($id, CourseFormModel $courseModel)
    {
        $course = $this->repository->find($id);

        return $this->fromForm($course, $courseModel);
    }

    /**
     * @param CourseFormModel $courseModel
     *
     * @return CourseInterface
     */
    protected function fromForm(CourseInterface $course, CourseFormModel $courseModel)
    {
        $course
            ->setName($courseModel->getName());

        return $course;
    }
}
