<?php

namespace Siciarek\ChatBundle\Model;

class ChatMessageException extends \Exception
{

    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, 4561237, $previous);
    }

}
