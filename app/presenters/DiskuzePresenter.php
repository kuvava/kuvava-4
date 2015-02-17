<?php

namespace App\Presenters;

use Nette,
	App\Model,
	Nette\Application\UI\Form,
	Nette\Utils\Html;


/**
 * Homepage presenter.
 */
class DiskuzePresenter extends BasePresenter
{

	public function actionPuvodniZneni()
	{
		$this->template->komentar = $this->database->table('koment_tematic')->get($this->params['number2']);
		if (!$this->template->komentar){
			$this->shootError();
		}
		$this->template->robots = 'noindex';
	}

}
