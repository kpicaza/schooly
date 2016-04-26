<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class GradeController
 * @package AppBundle\Controller
 */
class GradeController extends FOSRestController
{
    /**
     * @ApiDoc(
     *   section = "Grades",
     *   description = "Get grades list.",
     *   statusCodes = {
     *     200 = "Show user info.",
     *     401 = "Authentication failure, user does not have permission or API token is invalid or outdated.",
     *     403 = "Authorization failure, user does not have permission to access this area.",
     *   }
     * )
     */
    public function getGradesAction()
    {
        $grades = $this->container->get('app.api_grade_handler')->getList(array());

        $view = $this->view($grades);

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *   section = "Grades",
     *   description = "Get grade by ID.",
     *   statusCodes = {
     *     200 = "Show user info.",
     *     401 = "Authentication failure, user does not have permission or API token is invalid or outdated.",
     *     403 = "Authorization failure, user does not have permission to access this area.",
     *   }
     * )
     *
     * @return array
     */
    public function getGradeAction($id)
    {
        $grade = $this->container->get('app.api_grade_handler')->get($id);

        if (null === $grade) {
            throw new NotFoundHttpException('Grade not found');
        }

        $view = $this->view($grade);

        return $this->handleView($view);
    }

    /**
     * @Security("has_role('ROLE_TEACHER')")
     * @ApiDoc(
     *   section = "Grades",
     *   description = "Create new Grade.",
     *   input = "AppBundle\Form\Model\GradeFormModel",
     *   output = "AppBundle\Model\Grade\GradeInterface",
     *   statusCodes = {
     *     200 = "User data updated.",
     *     401 = "Authentication failure, user does not have permission or API token is invalid or outdated.",
     *   }
     * )
     *
     * @param Request $request
     */
    public function postGradeAction(Request $request)
    {
        $grade = $this->container->get('app.api_grade_handler')->post(
            $request->request->all()
        );

        $view = $this->view($grade);

        return $this->handleView($view);
    }

    /**
     * @Security("has_role('ROLE_TEACHER')")
     * @ApiDoc(
     *   section = "Grades",
     *   description = "Add or update picture to a Grade.",
     *   input = "AppBundle\Form\Model\FileFormModel",
     *   output = "AppBundle\Model\Grade\GradeInterface",
     *   statusCodes = {
     *     200 = "User data updated.",
     *     401 = "Authentication failure, user does not have permission or API token is invalid or outdated.",
     *   }
     * )
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postGradePictureAction(Request $request, $id)
    {
        $grade = $this->container->get('app.api_grade_handler')->postPicture(
            $id,
            $request->files->get('imageFile')
        );

        $view = $this->view($grade);

        return $this->handleView($view);
    }

    /**
     * @Security("has_role('ROLE_TEACHER')")
     * @ApiDoc(
     *   section = "Grades",
     *   description = "Update grade by ID.",
     *   input = "AppBundle\Form\Model\GradeFormModel",
     *   output = "AppBundle\Model\Course\GradeInterface",
     *   statusCodes = {
     *     200 = "Show user info.",
     *     401 = "Authentication failure, user does not have permission or API token is invalid or outdated.",
     *     403 = "Authorization failure, user does not have permission to access this area.",
     *   }
     * )
     *
     * @return array
     */
    public function putGradeAction(Request $request, $id)
    {
        $grade = $this->container->get('app.api_grade_handler')->put(
            $id,
            $request->request->all()
        );
        $view = $this->view($grade);

        return $this->handleView($view);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *   section = "Grades",
     *   description = "Delete grade.",
     *   statusCodes = {
     *     204 = "Do no return nothing.",
     *     401 = "Authentication failure, user does not have permission or API token is invalid or outdated.",
     *   }
     * )
     *
     * @return array
     */
    public function deleteGradeAction($id)
    {
        $this->container->get('app.api_grade_handler')->delete($id);
        $view = $this->view(array(), 204);

        return $this->handleView($view);
    }
}