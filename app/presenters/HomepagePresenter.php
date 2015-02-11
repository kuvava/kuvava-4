<?php

namespace App\Presenters;

use Nette,
	App\Model,
	Nette\Application\UI\Form,
	Nette\Utils\Html;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

	/** @var Nette\Mail\IMailer @inject */
	private $mailer;

	public function createComponentNapisteNamForm()
	{
		$form = new Nette\Application\UI\Form;
			
		$form->addText('jmeno', 'Vaše jméno:', 30, 50);
		$form->addText('email', 'Váš email*:', 30, 70)
			->setType('email')
			->addRule(Form::FILLED, 'Zadejte prosím Váš e-mail.')
			->addRule(Form::EMAIL, 'Zadejte prosím platnou e-mailovou adresu.')
			->setDefaultValue('@');
		$form->addCheckbox('novinky', 'Smíme Vám zasílat novinky emailem?')
			->setDefaultValue(FALSE);
		$form->addAntispam();
		$form->addText('telefon', 'Váš telefon:', 30, 30);
		$form->addCheckbox('zavolame_vam', 'Smíme Vám bezplatně zavolat pro domluvu?')
			->setDefaultValue(FALSE);
		$form->addTextArea('text', 'Text*:', 50, 20)
			->setAttribute('maxlength', '2000')
			->addRule(Form::FILLED, 'Vyplňte prosím políčko "text".');
		$form->addCheckbox('kopie', 'Odeslat kopii i na Vámi vyplněný email.')
			->setDefaultValue(FALSE);
		$form->addSubmit('odeslat', 'Důvěrně odeslat')
			->setAttribute('class','but')
			->setOption('description', Html::el('div class=des')
					->setHtml('<small>(Takto obdržené informace nezveřejňujeme.)<br>(Údaje s * jsou povinné.)</small>')
					);
		
		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = 'dl';
		$renderer->wrappers['pair']['container'] = 'div';
		$renderer->wrappers['label']['container'] = 'dt';
		$renderer->wrappers['control']['container'] = 'dd';
		
		$form->onSuccess[] = array($this, 'napisteNamFormSubmitted');
		return $form;
	}
	
	public function napisteNamFormSubmitted(Nette\Application\UI\Form $form, $values)
	{
		$novinky = $values -> novinky ? 'ano' : 'ne';
		$httpRequest = $this->context->getService('httpRequest');
		$now = new Nette\DateTime;
		$ip = inet_pton($httpRequest -> getRemoteAddress());

		$data = array (
			'jmeno' => $values -> jmeno,
			'telefon' => $values -> telefon,
			'email' => $values -> email,
			'novinky' => $novinky,
			'text' => $values -> text,
			'dv' => $now,
			'ov' => $now,
			'ip' => $ip
			);
			
		$this -> zapisovac -> zapis('vzkaz', $data);

		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/../templates/moje-emaily/napiste-nam.latte');
		$template->kopie = FALSE;
		$template->values = $values;
		$template->novinky = $novinky;

		$mail = new Nette\Mail\Message;
		$mail->setFrom('admin@gastrohradec.cz')
			->addTo('gastrohradec@seznam.cz')
			->addTo('urbanovi@kuvava.cz')
			->setHtmlBody($template);

		$mail2 = new Nette\Mail\Message;
		$template->kopie = TRUE;
		$mail2->setFrom('admin@gastrohradec.cz')
			->addTo($values -> email)
			->setHtmlBody($template);

		$mailer = new Nette\Mail\SendmailMailer;
		$mailer->send($mail);
		$mailer->send($mail2);

		$this->flashMessage('Váš vzkaz byl úspěšně odeslán na náš email: gastrohradec@seznam.cz  <br>Pro kontrolu jsme kopii odeslali i na Váš email: '. $values -> email, 'flash-green');
		$this->redirect('this');
	}

}
