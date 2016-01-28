<?php

namespace Siciarek\ChatBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * @Route("/chat")
 */
class ChatController extends Controller
{

    /**
     * @Route("/", name="chat.index")
     * @Template()
     */
    public function indexAction($type = 1)
    {
        $room = $this->get('chat.channel');
        $msg = $this->get('chat.message');
        $session = $this->get('session');
        $user = $this->getUser();

        ld($room, $msg, $session, $user);
        
        return [
        ];
    }

}
