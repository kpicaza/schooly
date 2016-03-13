<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Model\UserInterface;

/**
 * UserController.
 */
class UserController extends FOSRestController
{

    const URI = '/api/users/%s';

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
     * 
     * @return array
     */
    public function getUserAction(Request $request, $id)
    {

        $user = $this->container->get('app.api_user_handler')->get($id);
        $view = $this->view($user);
        $response = $this->handleView($view);

        if (!$user instanceof UserInterface) {
            return $response;
        }

        $response->setMaxAge(100);
        $response->setEtag(md5($user . $user->getEmail() . $user->getDescription()));
        $response->setPublic(); // make sure the response is public/cacheable
        if ($response->getStatusCode(200) && '"' . $request->headers->get('If-None-Match') . '"' == $response->getEtag()) {
            $response->isNotModified($request);
            $response->setStatusCode(304);
            $response->setContent(null);
            $response->headers->set('Content-Length', 0);
        }

        return $response;
    }

    /**
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
    public function postUserAction(Request $request)
    {
        $user = $this->container->get('app.api_user_handler')->post(
            $request->request->all()
        );

        $view = $this->view($user);
        $response = $this->handleView($view);
        if (200 == $response->getStatusCode()) {
            $response->setStatusCode(201, $response);
            $response->headers->set('Location', sprintf(self::URI, $user->getId()));
        }

        return $response;
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
    public function putUserAction(Request $request, $id)
    {
        $user = $this->container->get('app.api_user_handler')->put(
            $id, $request->request->all()
        );

        $view = $this->view($user);
        $response = $this->handleView($view);

        if (!$user instanceof UserInterface) {
            return $response;
        }

        $response->setMaxAge(100);
        $response->setEtag(md5($user . $user->getEmail() . $user->getDescription()));
        $response->setPublic(); // make sure the response is public/cacheable
        if (200 == $response->getStatusCode() && true === $response->isNotModified($request)) {
            $response->setStatusCode(304);
            $response->setContent(null);
        }

        return $response;
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
    public function deleteUserAction($id)
    {
        $this->container->get('app.api_user_handler')->delete($id);

        $view = $this->view(array(), 204);

        return $this->handleView($view);
    }

    /**
     * @Security("is_granted('edit', user)")
     * @ApiDoc(
     *   description = "Post user picture.",
     *   statusCodes = {
     *     204 = "Do no terurn nothing.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *   }
     * )
     * 
     * @param Request $request
     * @param type $id
     */
    public function postUserPictureAction(Request $request, $id)
    {
        
    }

}
