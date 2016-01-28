<?php

namespace Siciarek\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/")
 */
class DefaultController extends Controller
{

    /**
     * @Route("/", name="default.index" )
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/private", name="default.private" )
     * @Template()
     */
    public function privateAction()
    {
        return [];
    }

}
