<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\RegistrationFormType;
use AppBundle\Form\Model\RegistrationFormModel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * UserController.
 */
class UserController extends FOSRestController
{
    /**
     * @Security("has_role('ROLE_TEACHER')")
     * @ApiDoc(
     *   description = "Get user list.",
     *   statusCodes = {
     *     200 = "Show user info.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *     403 = "Authorizationi failure, user doesn’t have permission to access this area.",
     *   }
     * )
     * Get list of users
     */
    public function getUsersAction()
    {
        $users = $this->container->get('app.api_user_handler')->getList(array());

        $view = $this->view($users);

        return $this->handleView($view);
    }
    /**
     * @Security("is_granted('view', user) or has_role('ROLE_TEACHER')")
     * @ApiDoc(
     *   description = "Get your own user.",
     *   statusCodes = {
     *     200 = "Show user info.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *     403 = "Authorizationi failure, user doesn’t have permission to access this area.",
     *   }
     * )
     * 
     * @return array
     */
    public function getUserAction($id)
    {
        $user = $this->container->get('app.api_user_handler')->get($id);

        if (null === $user) {
            throw new NotFoundHttpException('User not found');
        }

        $view = $this->view($user);

        return $this->handleView($view);
    }
    /**
     * @ApiDoc(
     *   description = "Register new user.",
     *   input = "AppBundle\Form\Model\RegistrationFormModel",
     *   output = "AppBundle\Model\User\UserInterface",
     *   statusCodes = {
     *     200 = "User correctly added.",
     *   }
     * )
     * 
     * @param Request $request
     *
     * @return array
     */
    public function postUserAction(Request $request)
    {
        $user = $this->container->get('app.api_user_handler')->post(
            $request->request->all()
        );
        $view = $this->view($user);
        return $this->handleView($view);
    }
    /**
     * @Security("is_granted('edit', user)")
     * @ApiDoc(
     *   description = "Update own user.",
     *   input = "AppBundle\Form\Model\ProfileFormModel",
     *   output = "AppBundle\Model\User\UserInterface",
     *   statusCodes = {
     *     200 = "User data updated.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *   }
     * )
     * 
     * @param Request $request
     *
     * @return array
     */
    public function putUserAction(Request $request, $id)
    {
        $user = $this->container->get('app.api_user_handler')->put(
            $id, $request->request->all()
        );

        $view = $this->view($user);
        return $this->handleView($view);
    }
    /**
     * @Security("is_granted('edit', user)")
     * @ApiDoc(
     *   description = "Delete own user.",
     *   statusCodes = {
     *     204 = "Do no return nothing.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *   }
     * )
     * 
     * @return array
     */
    public function deleteUserAction($id)
    {
        $this->container->get('app.api_user_handler')->delete($id);
        $view = $this->view(array());

        return $this->handleView($view);
    }

}
