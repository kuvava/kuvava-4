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
	
	public function createComponentVlozitKomentForm()
	{
		$form = new Nette\Application\UI\Form;
			
		$form->addText('prezdivka', 'Přezdívka:', 30, 30);
		$form->addText('web', 'Vaše webové stránky:', 30, 50)
			->setOption('description','(zobrazí se vedle přezdívky)');
		$form->addTextArea('text', 'Text*:', 50, 10)
			->setAttribute('maxlength', '2000')
			->addRule(Form::FILLED, 'Vyplňte prosím políčko "text".');
		$form->addRadioList('pohlavi', 'Pohlaví:', array('m' => 'muž','h' => 'tajné','f' => 'žena'))
			->getSeparatorPrototype()->addClass('flr')->setName('span');
		$form->addSelect('vek', 'Věk:', array(-1 => 'tajné', 0 => '0 až 5 let', 5 => '5 až 10 let', 10 => '10 až 15 let', 15 => '15 až 20 let', 20 => '20 až 25 let', 25 => '25 až 30 let', 30 => '30 až 35 let', 35 => '35 až 40 let', 40 => '40 až 50 let', 50 => '50 až 60 let', 60 => '60 až 70 let', 70 => '70 až 80 let', 80 => '80 nebo více let'))
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
					->setHtml('<small>(Nesouvisející dotazy pokládejte prosím v našem diskuzním fóru.)<br>(Text nelze formátovat. Odkazy převedeme ručně na klikatelné - zpravidla do 5 dnů.)<br>(Znění dle svého svědomí upravujeme. S autorem o tom komunikujeme emailem. Jde nám o výpovědní hodnotu i pro budoucí čtenáře webu. U pozměněných komentářů je vždy proklik i na původní podobu.)<br>(Údaje s * jsou povinné.)</small>')
					);
		$this->setFormRenderer($form);
		
		$form->onSuccess[] = array($this, 'napisteNamFormSubmitted');
		return $form;
	}
	
	public function vlozitKomentFormSubmitted(Nette\Application\UI\Form $form, $values)
	{
		$novinky = $values->novinky ? 'ano' : 'ne';
		$zavolame_vam = $values->zavolame_vam ? 'ano' : 'ne';
		$kopie = $values->kopie ? 'ano' : 'ne';
		$httpRequest = $this->context->getService('httpRequest');
		$now = new Nette\DateTime;
		$ip = inet_pton($httpRequest->getRemoteAddress());

		$data = array (
			'jmeno' => $values->jmeno,
			'telefon' => $values->telefon,
			'zavolame_vam' => $zavolame_vam,
			'email' => $values->email,
			'novinky' => $novinky,
			'text' => $values->text,
			'kopie' => $kopie,
			'dv' => $now,
			'ov' => $now,
			'ip' => $ip
			);
			
		$this->database->table('koment')->insert($data);

		$this->flashMessage('Váš vzkaz byl úspěšně odeslán na náš email: urbanovi&#64;<!-- -->kuvava.cz '. ($data['kopie'] === 'ano' ? ('<br>Pro kontrolu jsme kopii odeslali i na Váš email: '. $data['email']):''));
		$this->redirect('this');
	}
}
