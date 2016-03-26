<?php
namespace AppBundle\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
/**
 * CourseController.
 */
class CourseController extends FOSRestController
{
    /**
     * @ApiDoc(
     *   description = "Get course list.",
     *   statusCodes = {
     *     200 = "Show user info.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *     403 = "Authorizationi failure, user doesn’t have permission to access this area.",
     *   }
     * )
     * Get list of users
     */
    public function getCoursesAction()
    {
        $courses = $this->container->get('app.api_course_handler')->getList(array());

        $view = $this->view($courses);

        return $this->handleView($view);
    }
    /**
     * @ApiDoc(
     *   description = "Get course by ID.",
     *   statusCodes = {
     *     200 = "Show user info.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *     403 = "Authorizationi failure, user doesn’t have permission to access this area.",
     *   }
     * )
     * 
     * @return array
     */
    public function getCourseAction($id)
    {
        $course = $this->container->get('app.api_course_handler')->get($id);

        if (null === $course) {
            throw new NotFoundHttpException('Course not found');
        }

        $view = $this->view($course);
        
        return $this->handleView($view);
    }
    /**
     * @Security("has_role('ROLE_USER')")
     * @ApiDoc(
     *   description = "Create new Course.",
     *   input = "AppBundle\Form\Model\CourseFormModel",
     *   output = "AppBundle\Model\CourseInterface",
     *   statusCodes = {
     *     200 = "User data updated.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *   }
     * )
     * 
     * @param Request $request
     */
    public function postCourseAction(Request $request)
    {
        $course = $this->container->get('app.api_course_handler')->post(
            $request->request->all()
        );
        $view = $this->view($course);

        return $this->handleView($view);
    }
    /**
     * @Security("has_role('ROLE_TEACHER')")
     * @ApiDoc(
     *   description = "Update course by ID.",
     *   statusCodes = {
     *     200 = "Show user info.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *     403 = "Authorizationi failure, user doesn’t have permission to access this area.",
     *   }
     * )
     * 
     * @return array
     */
    public function putCourseAction(Request $request, $id)
    {
        $course = $this->container->get('app.api_course_handler')->put(
            $id, $request->request->all()
        );
        $view = $this->view($course);

        return $this->handleView($view);
    }
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *   description = "Delete course.",
     *   statusCodes = {
     *     204 = "Do no return nothing.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *   }
     * )
     * 
     * @return array
     */
    public function deleteCourseAction($id)
    {
        $this->container->get('app.api_course_handler')->delete($id);
        $view = $this->view(array());
        
        return $this->handleView($view);
    }
}
