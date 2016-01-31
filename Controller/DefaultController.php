<?php

namespace Siciarek\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="default.home")
     * @Template()
     */
    public function homeAction()
    {
        return [];
    }
    
    /**
     * @Route("/sample", name="default.sample")
     * @Template()
     */
    public function sampleAction()
    {
        if(false === $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw $this->createAccessDeniedException();
        }
        
        return [];
    }
}
