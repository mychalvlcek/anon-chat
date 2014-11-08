<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}

	public function handlePoll()
	{
		if ($this->isAjax()) {
			// $test = new \Kdyby\Curl\Request("http://jsonplaceholder.typicode.com/todos");
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

	public function handleCreateRoom()
	{
		if ($this->isAjax()) {
			$this->sendResponse(new Nette\Application\Responses\JsonResponse($this->context->httpRequest->getPost()));
		}
	}

	public function handleRoom($id = null)
	{
		if ($this->isAjax()) {
			// get messages from room $id
			$this->sendResponse(new Nette\Application\Responses\JsonResponse(array($id)));
		}
	}

}
