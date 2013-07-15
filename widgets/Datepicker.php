<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\widgets;

use wheels\helpers\ArrayHelper;

/**
 * wheels\widgets\Datepicker
 * @ssee https://github.com/eternicode/bootstrap-datepicker
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class Datepicker extends Input
{

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		parent::init();
		$this->addCssClass($this->options, 'grd-white');
		$this->initOptions();
	}

	/**
	 * Initializes options
	 */
	public function initOptions()
	{
		$this->options['autocomplete'] = ArrayHelper::getValue($this->options, 'autocomplete', 'off');
		$this->clientOptions['format'] = ArrayHelper::getValue($this->clientOptions, 'format', 'mm/dd/yyyy');
		$this->clientOptions['autoclose'] = ArrayHelper::getValue($this->clientOptions, 'autoclose', true);
	}

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		$this->renderField();
		$this->registerClientScript();
	}

	/**
	 * Registers required client script for bootstrap datepicker.
	 */
	public function registerClientScript()
	{
		/* @var $cs \CClientScript */
		$cs = \Yii::app()->getClientScript();
		$this->registerCss('datepicker.css', 'wheels.widgets.assets.datepicker');

		$cs->registerScriptFile($this->getAssetsUrl() . '/js/bootstrap-datepicker.js');
		if ($language = ArrayHelper::getValue($this->clientOptions, 'language')) {
			$cs->registerScriptFile(
				$this->getAssetsUrl() . '/js/locales/bootstrap-datepicker.' . $language . '.js',
				\CClientScript::POS_END
			);
		}
		$this->registerPlugin('datepicker');
	}
}