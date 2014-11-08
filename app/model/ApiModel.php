<?php

namespace App\Model;

use Nette\Database\Context;

class ApiModel extends \Nette\Object
{

    const ROOMS_URL = "http://jsonplaceholder.typicode.com/todos";

    const MESSAGE_URL = "http://jsonplaceholder.typicode.com/todos";
    const MESSAGES_URL = "http://jsonplaceholder.typicode.com/posts?userId=1";

    /**
     * Get all chat rooms
     * @return \Kdyby\Curl\Response $response
     */
    public function getRooms()
    {
        $req = new \Kdyby\Curl\Request(static::ROOMS_URL);
        return $req->get();
    }

    /**
     * Gets messages from given room
     * @param  int $roomId id of room
     * @return \Kdyby\Curl\Response $response
     */
    public function getRoomMessages($roomId = null)
    {
        $req = new \Kdyby\Curl\Request(static::MESSAGES_URL);
        return $req->get();
    }

    /**
     * Create new message
     * @return \Kdyby\Curl\Response $response
     */
    public function createMessage(array $data, $roomId)
    {
        $req = new \Kdyby\Curl\Request(static::MESSAGE_URL);
        return $req->post($data);
    }

    /**
     * Create new chat room
     * @return \Kdyby\Curl\Response $response
     */
    public function createRoom(array $data)
    {
        $req = new \Kdyby\Curl\Request(static::MESSAGE_URL);
        return $req->post($data);
    }


}

?>
