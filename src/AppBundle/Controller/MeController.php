<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * MeController.
 */
class MeController extends FOSRestController
{
    /**
     * @Security("is_granted('view', user)")
     * @ApiDoc(
     *   description = "Get your own user.",
     *   statusCodes = {
     *     200 = "Show user info.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *   }
     * )
     * 
     * @return array
     */
    public function getMeAction()
    {
        $user = $this->container->get('app.api_user_handler')->get(
            $this->container->get('security.token_storage')->getToken()->getUser()
        );

        $view = $this->view($user);

        return $this->handleView($view);
    }

    /**
     * @Route("/api/register/me.{_format}", methods="POST")
     * @ApiDoc(
     *   description = "Register new user.",
     *   input = "AppBundle\Form\Model\RegistrationFormModel",
     *   output = "AppBundle\Entity\User",
     *   statusCodes = {
     *     200 = "User correctly added.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *   },
     *   requirements={
     *      {
     *          "name"="_format",
     *          "dataType"="string",
     *          "requirement"="json|xml|html",
     *      }
     *   }
     * )
     * 
     * @param Request $request
     *
     * @return array
     */
    public function MeAction(Request $request)
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
     *   output = "AppBundle\Entity\User",
     *   statusCodes = {
     *     200 = "User data updated.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *   },
     *   requirements={
     *      {
     *          "name"="_format",
     *          "dataType"="string",
     *          "requirement"="json|xml|html",
     *      }
     *   }
     * )
     * 
     * @param Request $request
     *
     * @return array
     */
    public function putMeAction(Request $request)
    {
        $me = $this->container->get('security.token_storage')->getToken()->getUser();

        $user = $this->container->get('app.api_user_handler')->put(
            $me->getId(), $request->request->all()
        );

        $view = $this->view($user);

        return $this->handleView($view);
    }

    /**
     * @Security("is_granted('edit', user)")
     * @ApiDoc(
     *   description = "Delete own user.",
     *   statusCodes = {
     *     204 = "Do no terurn nothing.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *   }
     * )
     * 
     * @return array
     */
    public function deleteMeAction()
    {
        $this->container->get('app.api_user_handler')->delete(
            $this->container->get('security.token_storage')->getToken()->getUser()
        );

        $view = $this->view(array());

        return $this->handleView($view);
    }
}
