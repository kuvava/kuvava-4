<?php

use Nette\Forms\Controls\TextInput,
	Nette\Application\UI\Form,
	Nette\Utils\Html;



/**
 * AntispamControl
 * add basic antispam feature to Nette forms. 
 *
 * <code>
 * // Register extension
 * AntispamControl::register();
 * 
 * // Add antispam to form
 * $form->addAntispam();
 * </code>
 *
 * @version 0.4
 * @author  Michal Mikoláš <nanuqcz@gmail.com>
 * @license CC BY <http://creativecommons.org/licenses/by/3.0/cz/>
 */
class AntispamControl extends TextInput
{
	/** @var int  minimum delay [sec] to send form */
	public static $minDelay = 5;

	
	/**
	 * Register Antispam to Nette Forms
	 * @return void
	 */
	public static function register()
	{
		Form::extensionMethod('addAntispam', function(Form $form, $name = 'pasticka', $label = 'Toto pole vymažte (kontrola proti nevyžádané reklamě):', $msg = 'Byl detekován pokus o vložení nevyžádané reklamy.'){
			// "All filled" protection
			$form[$name] = new AntispamControl($label, NULL, NULL, $msg);
			
			// "Send delay" protection
			$form->addHidden('form_created', strtr(time(), '0123456789', 'jihgfedcba'))
				->addRule(
					function($item){
						if (AntispamControl::$minDelay <= 0) return TRUE;  // turn off "Send delay protection"

						$value = (int)strtr($item->value, 'jihgfedcba', '0123456789');
						return $value <= (time() - AntispamControl::$minDelay);
					}, 
					$msg
				);
			
			return $form;
		});
	}



	/**
	 * @param string|Html
	 * @param int
	 * @param int
	 * @param string
	 */
	public function __construct($label = '', $cols = NULL, $maxLength = NULL, $msg = '')
	{
		parent::__construct($label, $cols, $maxLength);

		$this->setDefaultValue('http://');
		$this->setOption('class', 'jshidden');
		$this->addRule(Form::BLANK, $msg);
	}



	/**
	 * @return TextInput
	 */
	public function getControl()
	{
		$control = parent::getControl();
		
		$control = $this->addAntispamScript($control);
		return $control;
	}



	/**
	 * @param Html
	 * @return Html
	 */
	protected function addAntispamScript(Html $control)
	{
		$control = Html::el('')->add($control);
		$control->add( Html::el('script', array('type' => 'text/javascript'))->setHtml("
				// Clear input value
				var input = document.getElementById('" . $control[0]->id . "');
				input.value = '';
			") 
		);

		return $control;
	}

}