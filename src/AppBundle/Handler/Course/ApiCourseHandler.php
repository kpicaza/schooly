<?php
namespace AppBundle\Handler\Course;
use AppBundle\Model\Course\CourseRepository;
use AppBundle\Model\Course\CourseInterface;
use AppBundle\Handler\ApiHandlerInterface;
use AppBundle\Form\Model\CourseFormModel;
use AppBundle\Form\Type\CourseFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormError;
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
     * @param CourseRepository       $repository
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
     * @param integer $limit
     * @param integer $skip
     */
    public function getList(array $criteria, array $sort = null, $limit = null, $skip = null)
    {
        return $this->repository->findBy($criteria, $sort , $limit , $skip );
    }
    /**
     * Get object from repository.
     * 
     * @param integer $id
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
     * Update object from repository.
     * 
     * @param $id
     * @param array $params
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
     * @param type $id
     */
    public function delete($id)
    {
        $this->repository->remove($id);
    }
    /**
     * @param CourseFormModel $courseModel
     *
     * @return User
     */
    protected function insertFromForm(CourseFormModel $courseModel)
    {
        $course = $this->repository->findNew();
        
        return $this->fromForm($course, $courseModel);
    }
    /**
     * @param type             $id
     * @param CourseFormModel $courseModel
     *
     * @return User
     */
    protected function updateFromForm($id, CourseFormModel $courseModel)
    {
        $course = $this->repository->find($id);

        return $this->fromForm($course, $courseModel);
    }
    /**
     * @param CourseFormModel $courseModel
     * @return CourseInterface
     */
    protected function fromForm(CourseInterface $course, CourseFormModel $courseModel)
    {        
        $course
            ->setName($courseModel->getName())
        ;
        
        return $course;
    }
}