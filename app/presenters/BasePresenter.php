<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	/** @var Nette\Database\Context */
	protected $database;
	public function injectDatabase(Nette\Database\Context $database)
	{
		$this->database = $database;
	}
	
	public function startup()
	{
		parent::startup();
		\AntispamControl::register();
	}
	
	public function beforeRender()
	{
		$this->template->stranka = $this->database->table('stranka')->where('presenter = ?', $this->name)->where('view = ?', $this->view)->limit(1)->fetch();
	}

	protected function setFormRenderer(Nette\Application\UI\Form $form)
	{
		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = 'dl';
		$renderer->wrappers['pair']['container'] = 'div';
		$renderer->wrappers['label']['container'] = 'dt';
		$renderer->wrappers['control']['container'] = 'dd';
	}
}
