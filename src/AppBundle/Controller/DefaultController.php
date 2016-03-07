<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Post;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
              'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
        ));
    }

    /**
     * @ApiDoc(
     *   description = "Generate JWT token.",
     *   statusCodes = {
     *     200 = "Return JWT token and refresh token.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *   }
     * )
     * @Post("/api/login")
     */
    public function apiLoginAction()
    {
        
    }

    /**
     * @ApiDoc(
     *   description = "Refresh JWT token.",
     *   statusCodes = {
     *     200 = "Return JWT token from refresh token.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *   }
     * )
     * @Post("/api/token/refresh")
     */
    public function apiTokenRefreshAction()
    {
        
    }
    
}
