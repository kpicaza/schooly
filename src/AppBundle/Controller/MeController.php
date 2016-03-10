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
     *     403 = "Authorizationi failure, user doesn’t have permission to access this area.",
     *   }
     * )
     * 
     * @return array
     */
    public function getMeAction()
    {
        $user = $this->get('app.user_repository')->find(
            $this->container->get('security.token_storage')->getToken()->getUser()->getId()
        );
        $view = $this->view($user);

        return $this->handleView($view);
    }

}
