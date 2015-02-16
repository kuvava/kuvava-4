<?php

namespace App\Presenters;

use Nette,
	App\Model,
	Nette\Application\UI\Form,
	Nette\Utils\Html;


/**
 * Homepage presenter.
 */
class WwwPresenter extends BasePresenter
{

	/** @var Nette\Mail\IMailer */
	private $mailer;
	public function injectMailer(Nette\Mail\IMailer $mailer)
	{
		$this->mailer = $mailer;
	}

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
		$this->setFormRenderer($form);
		
		$form->onSuccess[] = array($this, 'napisteNamFormSubmitted');
		return $form;
	}
	
	public function napisteNamFormSubmitted(Nette\Application\UI\Form $form, $values)
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
			
		$this->database->table('napiste_nam')->insert($data);

		$latte = new \Latte\Engine;

		$mail = new Nette\Mail\Message;
		$data['komu'] = 'nam';
		$mail->setFrom($data['email'])
			->addTo('urbanovi@kuvava.cz')
			->setHtmlBody($latte->renderToString(__DIR__ . '/../templates/MojeEmaily/NapisteNam.latte', $data));

		if ($data['kopie'] === 'ano'){
			$mail2 = new Nette\Mail\Message;
			$data['komu'] = 'jim';
			$mail2->setFrom('urbanovi@kuvava.cz')
				->addTo($data['email'])
				->setHtmlBody($latte->renderToString(__DIR__ . '/../templates/MojeEmaily/NapisteNam.latte', $data));
		}

		$this->mailer->send($mail);
		if ($data['kopie'] === 'ano') $this->mailer->send($mail2);

		$this->flashMessage('Váš vzkaz byl úspěšně odeslán na náš email: urbanovi@kuvava.cz '. ($data['kopie'] === 'ano' ? ('<br>Pro kontrolu jsme kopii odeslali i na Váš email: '. $data['email']):''));
		$this->redirect('this');
	}

}
