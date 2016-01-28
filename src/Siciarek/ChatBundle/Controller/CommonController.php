<?php

namespace Siciarek\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

abstract class CommonController extends Controller
{

    public static $customExceptions = [
        'Siciarek\ChatBundle\Model\ChatChannelException',
    ];

    protected function getFrame()
    {
        return new \Siciarek\ChatBundle\Model\LaafFrame();
    }

    /**
     * Zwraca dane JSON wysłane postem jako tablicę lub obiekt
     *
     * @param boolean $array czy zwracać jako tablicę
     * @return array|mixed
     */
    protected function getJsonRequest($array = true)
    {

        $request = $this->get('request');
        $input = $request->get('json');

        if ($input === null) {
            $input = file_get_contents('php://input');
        }

        $data = json_decode($input, $array);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $data = $this->getFrame()->getErrorFrame();
            $data['msg'] = json_last_error_msg();
            $data['data'] = [];
            $data['data']['code'] = 500;
            $data['data']['input'] = $input;

            $data = json_decode(json_encode($data), $array);
        }

        return $data;
    }

    /**
     * Handles json response
     *
     * @param type $run callable
     */
    protected function handleJsonAction($run)
    {
        try {
            $frame = $run();
        } catch (\Exception $e) {

            $frame = $this->getFrame()->getErrorFrame('Unexpected Exception.');

            if (in_array(get_class($e), self::$customExceptions)) {
                $frame = $this->getFrame()->getWarningFrame($e->getMessage());
            }

            $frame['data'] = [
                'code' => $e->getCode(),
            ];

            if ($this->get('kernel')->getEnvironment() != 'prod') {
                $frame['msg'] = $e->getMessage();
                $frame['data'] = array_merge($frame['data'], [
                    'class' => get_class($e),
                    'trace' => $e->getTrace(),
                ]);
            }
        }

        return $this->getJsonResponse($frame);
    }

    /**
     * Returns json response.
     */
    protected function getJsonResponse($data)
    {
        $request = $this->get('request');

        $json = json_encode($data, JSON_PRETTY_PRINT);

        $contentType = 'application/json';
        $content = $json;

        // <jsonp>
        $callback = $request->get('callback');

        if ($callback !== null) {
            $contentType = 'application/javascript';
            $content = sprintf('%s(%s);', $callback, $json);
        }
        // </jsonp>

        $response = new Response($content, 200, [ 'Content-Type' => $contentType]);

        return $response;
    }

}
