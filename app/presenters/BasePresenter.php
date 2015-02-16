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
	
	protected $params;
	
	public function startup()
	{
		parent::startup();
		if ($this->name !== 'Error'){
			\AntispamControl::register();
			$this->params = $this->getParameters();
		}
	}
	
	public function beforeRender()
	{
		if ($this->name !== 'Error'){
			$this->template->stranka = $this->database->table('stranka')->where('presenter = ?', $this->name)->where('url1 = ?', $this->params['url1'])->where('url2 = ?', $this->params['url2'])->where('number1 = ?', $this->params['number1'])->limit(1)->fetch();
			if (!$this->template->stranka){
				$this->shootError();
			}	
		}
	}

	protected function setFormRenderer(Nette\Application\UI\Form $form)
	{
		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = 'dl';
		$renderer->wrappers['pair']['container'] = 'div';
		$renderer->wrappers['label']['container'] = 'dt';
		$renderer->wrappers['control']['container'] = 'dd';
	}
	
	protected function shootError($message = 'Omlouváme se, ale stránku nelze nalézt.<br>Kontaktujte prosím správce webu: urbanovi&#64;<!-- -->kuvava.cz<br>nebo si vyberte jiný obsah v menu.', $class = 'fr', $errorText = 'Odkazovaný obsah nelze nalézt.')
	{
		$this->flashMessage($message,$class);
		$this->error($errorText);
	}
}
