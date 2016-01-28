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
use Symfony\Component\HttpFoundation\Request;
use Siciarek\ChatBundle\Entity as E;

/**
 * @Route("/chat")
 */
class ChatController extends Controller
{
    /**
     * @Route("/channel/{channel}/assignees", defaults={"_format":"json"}, name="chat.channel.assignees")
     */
    public function channelAssigneesAction(Request $request, $channel)
    {
        $data = [__FUNCTION__, $channel];
        
        return new Response(json_encode($data, JSON_PRETTY_PRINT));
    }
    
    /**
     * @Route("/channel/{channel}/join", defaults={"_format":"json"}, name="chat.channel.join")
     */
    public function channelJoinAction(Request $request, $channel)
    {
        $data = [__FUNCTION__, $channel];
        
        return new Response(json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * @Route("/channel/{channel}/leave", defaults={"_format":"json"}, name="chat.channel.leave")
     */
    public function channelLeaveAction(Request $request, $channel)
    {
        $data = [__FUNCTION__, $channel];
        
        return new Response(json_encode($data, JSON_PRETTY_PRINT));
    }

# ------------------------------------------------------------------------------

    /**
     * @Route("/channel/list", defaults={"_format":"json"}, name="chat.channel.list")
     */
    public function channelListAction(Request $request)
    {
        $items = $this->get('chat.channel')->getList($this->getUser());
        
        $data = [
            'success' => true,
            'type' => 'data',
            'datetime' => date('Y-m-d H:i:s'),
            'msg' => 'Channels',
            'data' => [
                'totalCount' => count($items),
                'items' => $items,
            ],
        ];
        
        return new Response(json_encode($data, JSON_PRETTY_PRINT));
    }
   
    /**
     * @Route("/channel/{channel}/create", defaults={"_format":"json"}, name="chat.channel.create")
     */
    public function channelCreateAction(Request $request, $channel)
    {
        $channel = $this->get('chat.channel')->create($channel, E\ChatChannel::TYPE_PUBLIC, $this->getUser());
        
        $data = [
            'id' => $channel->getId(),
            'name' => $channel->getName(),
        ];
        
        return new Response(json_encode($data, JSON_PRETTY_PRINT));
    }
    
    /**
     * @Route("/channel/{channel}/close", defaults={"_format":"json"}, name="chat.channel.close")
     */
    public function channelCloseAction(Request $request, $channel)
    {
        $result = $this->get('chat.channel')->close($channel, $this->getUser());
        
        $data = [
            'result' => $result,
        ];
        
        return new Response(json_encode($data, JSON_PRETTY_PRINT));
    }

# ------------------------------------------------------------------------------

    /**
     * @Route("/{channel}/message/list", defaults={"_format":"json"}, name="chat.message.list")
     */
    public function messageListAction(Request $request, $channel)
    {
        $data = [__FUNCTION__, $channel];
        
        return new Response(json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * @Route("/{channel}/message/append", defaults={"_format":"json"}, name="chat.message.append")
     */
    public function messageAppendAction(Request $request, $channel)
    {
        $data = [__FUNCTION__, $channel];
        
        return new Response(json_encode($data, JSON_PRETTY_PRINT));
    }
}
