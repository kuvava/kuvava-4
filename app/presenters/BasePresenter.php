<?php

namespace App\Presenters;

use Nette,
	App\Model,
	Nette\Application\UI\Form,
	Nette\Utils\Html;


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
	/** @persistent */
	public $diskuze = 'ne';
	
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
			$this->template->params = $this->params;
			$this->template->stranka = $this->getStranka();
			if (!$this->template->stranka){
				$this->shootError();
			}	
		}
	}
	
	protected function getStranka()
	{
		return $this->database->table('stranka')->where('presenter = ?', $this->name)->where('url1 = ?', $this->params['url1'])->where('url2 = ?', $this->params['url2'])->where('number1 = ?', $this->params['number1'])->limit(1)->fetch();
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
	
	public function createComponentVlozitKomentForm()
	{
		$form = new Nette\Application\UI\Form;
			
		$form->addText('prezdivka', 'Jméno nebo přezdívka:', 30, 30);
		$form->addText('web', 'Vaše webové stránky:', 30, 50)
			->setOption('description','(zobrazí se vedle přezdívky)');
		$form->addTextArea('text', 'Text*:', 50, 10)
			->setAttribute('maxlength', '2000')
			->addRule(Form::FILLED, 'Vyplňte prosím políčko "text".');
		$form->addRadioList('pohlavi', 'Pohlaví:', array('m' => 'muž','h' => 'tajné','f' => 'žena'))
			->getSeparatorPrototype()->addClass('flr')->setName('span');
		$form->addSelect('vek', 'Věk:', array(-1 => 'tajné', '0 až 5' => '0 až 5 let', '5 až 10' => '5 až 10 let', '10 až 15' => '10 až 15 let', '15 až 20' => '15 až 20 let', '20 až 25' => '20 až 25 let', '25 až 30' => '25 až 30 let', '30 až 35' => '30 až 35 let', '35 až 40' => '35 až 40 let', '40 až 50' => '40 až 50 let', '50 až 60' => '50 až 60 let', '60 až 70' => '60 až 70 let', '70 až 80' => '70 až 80 let', '80 nebo více' => '80 nebo více let'))
			->setPrompt('vyberte rozmezí');
		$form->addText('email', 'Email*:', 30, 70)
			->setType('email')
			->addRule(Form::FILLED, 'Zadejte prosím Váš e-mail.')
			->addRule(Form::EMAIL, 'Zadejte prosím platnou e-mailovou adresu.')
			->setDefaultValue('@')
			->setOption('description','(nezobrazí se ostatním)');
		$form->addCheckbox('novinky', 'Smíme Vám zasílat novinky emailem?')
			->setDefaultValue(FALSE);
		$form->addAntispam();
		$form->addSubmit('odeslat', 'Vložit komentář')
			->setAttribute('class','but')
			->setOption('description', Html::el('div class=des')
					->setHtml('<small>(Nesouvisející dotazy pokládejte prosím v našem diskuzním fóru.)<br>(Můžeme si tykat.)<br>(Text nelze formátovat. Odkazy převedeme ručně na klikatelné - zpravidla do 5 dnů.)<br>(Znění dle svého svědomí upravujeme. S autorem o tom komunikujeme emailem. Jde nám o výpovědní hodnotu i pro budoucí čtenáře webu. U pozměněných komentářů je vždy proklik i na původní podobu.)<br>(Údaje s * jsou povinné.)</small>')
					);
		$form->addHidden('stranka_id',isset($this->template->stranka) ? $this->template->stranka : $this->getStranka()->id);
		$this->setFormRenderer($form);
		
		$form->onSuccess[] = array($this, 'vlozitKomentFormSubmitted');
		return $form;
	}
	
	public function vlozitKomentFormSubmitted(Nette\Application\UI\Form $form, $values)
	{
		$novinky = $values->novinky ? 'ano' : 'ne';
		$pohlavi = ($values->pohlavi === NULL) ? 'h' : $values->pohlavi;
		$vek = ($values->vek === NULL) ? -1 : $values->vek;
		$httpRequest = $this->context->getService('httpRequest');
		$now = new Nette\DateTime;
		$ip = inet_pton($httpRequest->getRemoteAddress());

		$data = array (
			'prezdivka' => $values->prezdivka,
			'web' => $values->web,
			'email' => $values->email,
			'novinky' => $novinky,
			'text' => $values->text,
			'puvodni_zneni' => $values->text,
			'pohlavi' => $pohlavi,
			'vek' => $vek,
			'dv' => $now,
			'ip' => $ip,
			'stranka_id' => $values->stranka_id
			);
			
		$this->database->table('koment_tematic')->insert($data);

		$this->flashMessage('Váš komentář byl úspěšně vložen do tematické diskuze k této stránce.');
		$this->redirect('this');
	}
}
