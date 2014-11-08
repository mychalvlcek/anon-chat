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
			$this->sendResponse(new Nette\Application\Responses\JsonResponse(array($id)));
		}
	}

}
