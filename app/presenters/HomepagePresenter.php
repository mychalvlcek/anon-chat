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
	const MESSAGES_URL = "http://jsonplaceholder.typicode.com/todos";

	/**
     * @inject
     * @var \App\Model\ApiModel
     */
    public $apiModel;

	public function renderDefault()
	{
		$this->init();
	}

	public function renderRoom($id = null)
	{
		$this->init();
		try {
			// get messages of room
			$response = $this->apiModel->getRoomMessages($id);
			$this->template->messages = \Nette\Utils\Json::decode($response->getResponse());
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
			try {
				$response = $this->apiModel->createMessage($this->context->httpRequest->getPost(), null);
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
			try {
				$response = $this->apiModel->createRoom($this->context->httpRequest->getPost());
				$json = \Nette\Utils\Json::decode($response->getResponse());
				$return = $this->context->httpRequest->getPost();
				$return['url'] = $this->link('room', $json->id);
				$this->sendResponse(new Nette\Application\Responses\JsonResponse($return));
			} catch (\Kdyby\Curl\CurlException $e) {
				$this->error($e->getMessage(), Nette\Http\Response::S400_BAD_REQUEST);
			}
		}
	}

	private function init()
	{
		// get user ?
		
		// get all rooms
		try {
			$response = $this->apiModel->getRooms();
			$this->template->rooms = \Nette\Utils\Json::decode($response->getResponse());
		} catch (\Kdyby\Curl\CurlException $e) {
			$this->error($e->getMessage(), Nette\Http\Response::S400_BAD_REQUEST);
		}
	}

}
