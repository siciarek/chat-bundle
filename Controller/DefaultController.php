<?php

namespace Siciarek\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="chat.default.home")
     * @Template()
     */
    public function homeAction()
    {
        return [];
    }

    /**
     * @Route("/sample", name="chat.default.sample")
     * @Template()
     */
    public function sampleAction()
    {
        if(false === $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw $this->createAccessDeniedException();
        }
        
        $refreshAfter = 200; //$this->container->getParameter('siciarek_chat.refresh');

        return [
            'refreshAfter' => $refreshAfter,
        ];
    }
}
