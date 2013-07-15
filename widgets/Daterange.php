<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\widgets;

use wheels\helpers\ArrayHelper;
use wheels\helpers\AssetManager;

/**
 * wheels\widgets\Daterange
 * @ssee https://github.com/dangrossman/bootstrap-daterangepicker
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class Daterange extends Input
{
	/**
	 * @var string $selector if provided, then no input field will be rendered. It will write the JS code for the
	 * specified selector.
	 */
	public $selector;
	/**
	 * @var string JS Callback for Daterange picker
	 */
	public $callback;


	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		$this->options['id'] = ArrayHelper::getValue($this->options, 'id', $this->getId());
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
	 * Renders the field if no selector has been provided
	 */
	public function renderField()
	{
		if (null === $this->selector) {
			parent::renderField();
			$this->setLocaleSettings();
		}
	}

	/**
	 *
	 * If user did not provided the names of weekdays and months in $this->pluginOptions['locale']
	 *  (which he should not care about anyway)
	 *  then we populate this names from Yii's locales database.
	 *
	 * <strong>Heads up!</strong> This method works with the local properties directly.
	 */
	protected function setLocaleSettings()
	{
		$this->setDaysOfWeekNames();
		$this->setMonthNames();
	}

	/**
	 * Sets days of week names if no locale settings were made to the plugin options.
	 */
	protected function setDaysOfWeekNames()
	{
		if (empty($this->clientOptions['locale']['daysOfWeek'])) {
			$this->clientOptions['locale']['daysOfWeek'] = \Yii::app()->locale->getWeekDayNames('narrow', true);
		}
	}

	/**
	 * Sets month names if no locale settings were made to the plugin options.
	 */
	protected function setMonthNames()
	{
		if (empty($this->clientOptions['locale']['monthNames'])) {
			$this->clientOptions['locale']['monthNames'] = array_values(
				\Yii::app()->locale->getMonthNames('wide', true)
			);
		}
	}

	/**
	 *
	 * Registers required css js files
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.daterangepicker');

		/* @var \CClientScript $cs */
		$cs = \Yii::app()->getClientScript();
		/* register required moment.js */
		AssetManager::registerScriptFile('moment.min.js');
		$cs->registerScriptFile($assetsUrl . '/js/daterangepicker.js', \CClientScript::POS_END);
		$cs->registerCssFile($assetsUrl . '/css/daterangepicker.css');

		$callback = ($this->callback instanceof \CJavaScriptExpression)
			? $this->callback
			: ($this->callback === null ? '' : new \CJavaScriptExpression($this->callback));

		$js = '$("#' . $this->options['id'] . '").daterangepicker(' .
			\CJavaScript::encode($this->clientOptions) .
			($callback ? ', ' . \CJavaScript::encode($callback) : '') .
			');';

		$cs->registerScript(md5($js), $js);
	}
}