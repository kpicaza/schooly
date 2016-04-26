<?php

namespace AppBundle\Controller;

use AppBundle\Exception\InvalidFormException;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class GradeSessionController.
 */
class GradeSessionController extends FOSRestController
{
    /**
     * @ApiDoc(
     *   section = "Grades",
     *   description = "Get GradeSession list.",
     *   statusCodes = {
     *     200 = "Show grade session info.",
     *     401 = "Authentication failure, user does not have permission or API token is invalid or outdated.",
     *     403 = "Authorization failure, user does not have permission to access this area.",
     *   }
     * )
     * Get list of courses
     */
    public function getGradeSessionsAction($id)
    {
        $grade = $this->gradeExistOr404($id);

        $gradeSessions = $this->container->get('app.api_grade_session_handler')->getList($id, array('grade' => $grade));

        $view = $this->view($gradeSessions);

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *   section = "Grades",
     *   description = "Get one GradeSession.",
     *   statusCodes = {
     *     200 = "Show grade session info.",
     *     401 = "Authentication failure, user does not have permission or API token is invalid or outdated.",
     *     403 = "Authorization failure, user does not have permission to access this area.",
     *   }
     * )
     * Get list of courses
     */
    public function getGradeSessionAction($id, $session_id)
    {
        $grade = $this->gradeExistOr404($id);

        $gradeSession = $this->container->get('app.api_grade_session_handler')->get($id, $session_id);

        if (null === $gradeSession) {
            throw new NotFoundHttpException('Grade session not found');
        }

        $view = $this->view($gradeSession);

        return $this->handleView($view);
    }

    /**
     * @Security("has_role('ROLE_TEACHER')")
     * @ApiDoc(
     *   section = "Grades",
     *   description = "Create new Grade session.",
     *   input = "AppBundle\Form\Model\GradeSessionFormModel",
     *   output = "AppBundle\Model\Grade\GradeSessionInterface",
     *   statusCodes = {
     *     200 = "User data updated.",
     *     401 = "Authentication failure, user does not have permission or API token is invalid or outdated.",
     *   }
     * )
     *
     * @param Request $request
     */
    public function postGradeSessionAction(Request $request, $id)
    {
        $grade = $this->gradeExistOr404($id);

        try {
            $gradeSession = $this->container->get('app.api_grade_session_handler')->post(
                $id, $request->request->all()
            );

            $view = $this->view($gradeSession);
        } catch (InvalidFormException $exception) {
            $view = $this->view($exception->getForm(), Response::HTTP_BAD_REQUEST);
        }

        return $this->handleView($view);
    }

    /**
     * @Security("has_role('ROLE_TEACHER')")
     * @ApiDoc(
     *   section = "Grades",
     *   description = "Existing Grade sessions.",
     *   input = "AppBundle\Form\Model\GradeSessionFormModel",
     *   output = "AppBundle\Model\Grade\GradeSessionInterface",
     *   statusCodes = {
     *     200 = "User data updated.",
     *     401 = "Authentication failure, user does not have permission or API token is invalid or outdated.",
     *   }
     * )
     *
     * @param Request $request
     */
    public function putGradeSessionAction(Request $request, $id, $session_id)
    {
        $grade = $this->gradeExistOr404($id);

        try {
            $gradeSession = $this->container->get('app.api_grade_session_handler')->put(
                $id,
                $session_id,
                $request->request->all()
            );

            $view = $this->view(array(), Response::HTTP_NO_CONTENT);
        } catch (InvalidFormException $exception) {
            $view = $this->view($exception->getForm(), Response::HTTP_BAD_REQUEST);
        }

        return $this->handleView($view);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *   section = "Grades",
     *   description = "Delete a grade session.",
     *   statusCodes = {
     *     204 = "Do no return nothing.",
     *     401 = "Authentication failure, user does not have permission or API token is invalid or outdated.",
     *   }
     * )
     *
     * @return array
     */
    public function deleteGradesSessionsAction($id, $session_id)
    {
        $grade = $this->gradeExistOr404($id);

        $this->container->get('app.api_grade_session_handler')->delete($session_id);
        $view = $this->view(array(), 204);

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *   section = "Grades",
     *   description = "Grade session options.",
     *   statusCodes = {
     *     200 = "User data updated.",
     *     401 = "Authentication failure, user does not have permission or API token is invalid or outdated.",
     *   }
     * )
     *
     * @return Response
     */
    public function optionsGradeSessionsAction($id)
    {
        $options = $this->get('app.api_grade_session_handler')->options();

        $view = $this->view($options);

        $response = $this->handleView($view);

        $response->headers->set('Allow', 'OPTIONS, GET, PUT, POST');

        return $response;
    }

    protected function gradeExistOr404($id)
    {
        $grade = $this->get('app.api_grade_handler')->get($id);
        if (null === $grade) {
            throw new NotFoundHttpException();
        }

        return $grade;
    }
}
