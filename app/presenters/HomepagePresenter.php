<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{
	const ROOMS_URL = "http://jsonplaceholder.typicode.com/todos";
	const MESSAGE_URL = "http://jsonplaceholder.typicode.com/todos";

	public function renderDefault()
	{
		// get all rooms
		$req = new \Kdyby\Curl\Request(static::ROOMS_URL);
		try {
			$response = $req->get();
			$this->template->rooms = \Nette\Utils\Json::decode($response->getResponse());
		} catch (\Kdyby\Curl\CurlException $e) {
			$this->error($e->getMessage(), Nette\Http\Response::S400_BAD_REQUEST);
		}
	}

	public function handlePoll()
	{
		if ($this->isAjax()) {
			// $test = new \Kdyby\Curl\Request(static::ROOMS_URL);
			// try {
			// 	$response = $test->get();
			// 	$json = \Nette\Utils\Json::decode($response->getResponse());

			// 	// var_dump($response->getHeaders());
			// 	// var_dump($response->getResponse());
			// 	$this->sendResponse(new Nette\Application\Responses\JsonResponse($json));

			// } catch (\Kdyby\Curl\CurlException $e) {
			// 	$this->error($e->getMessage(), Nette\Http\Response::S400_BAD_REQUEST);
			// }

			if (rand(1, 5) < 2) {
				$mine = 'mine';
			} else {
				$mine = '';
			}

			if (rand(1, 5) < 3) {
				$this->sendResponse(new Nette\Application\Responses\JsonResponse(array('mine' => $mine)));
			} else {
				$this->sendResponse(new Nette\Application\Responses\JsonResponse(array()));
			}
		}
	}

	/**
	 * Creates new message
	 */
	public function handleMessage()
	{
		if ($this->isAjax()) {
			$req = new \Kdyby\Curl\Request(static::MESSAGE_URL);
			try {
				$response = $req->post($this->context->httpRequest->getPost());
				$this->sendResponse(new Nette\Application\Responses\JsonResponse(\Nette\Utils\Json::decode($response->getResponse())));
			} catch (\Kdyby\Curl\CurlException $e) {
				$this->error($e->getMessage(), Nette\Http\Response::S400_BAD_REQUEST);
			}
		}
	}

	/**
	 * Creates new room
	 */
	public function handleCreateRoom()
	{
		if ($this->isAjax()) {
			$req = new \Kdyby\Curl\Request(static::MESSAGE_URL);
			try {
				$response = $req->post($this->context->httpRequest->getPost());
				$json = \Nette\Utils\Json::decode($response->getResponse());
				$return = $this->context->httpRequest->getPost();
				$return['url'] = $this->link('room!', $json->id);
				$this->sendResponse(new Nette\Application\Responses\JsonResponse($return));
			} catch (\Kdyby\Curl\CurlException $e) {
				$this->error($e->getMessage(), Nette\Http\Response::S400_BAD_REQUEST);
			}
		}
	}

	/**
	 * Switch the active room
	 * @param  int $id of room
	 */
	public function handleRoom($id = null)
	{
		if ($this->isAjax()) {
			// get messages from room $id
			$this->sendResponse(new Nette\Application\Responses\JsonResponse(array($id)));
		}
	}

}
