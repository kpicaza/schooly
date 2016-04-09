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
        $view = $this->view(array());

        return $this->handleView($view);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
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
        $view = $this->view(array());

        return $this->handleView($view);
    }
}