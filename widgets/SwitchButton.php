<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\widgets;

use wheels\helpers\ArrayHelper;

/**
 * wheels\widgets\SwitchButton implements bootstrap switch JS plugin.
 * @see http://www.larentis.eu/switch/
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class SwitchButton extends Input
{
	/**
	 * @var bool whether to use animation or not
	 */
	public $animated = true;
	/**
	 * @var string the label to display on the enabled side
	 */
	public $onLabel = 'ON';
	/**
	 * @var string the label to display on the disabled side
	 */
	public $offLabel = 'OFF';
	/**
	 * @var string the style of the toggle button enable style
	 * Accepted values ["primary", "danger", "info", "success", "warning"] or nothing
	 */
	public $onStyle = 'primary';
	/**
	 * @var string the style of the toggle button disabled style
	 * Accepted values ["primary", "danger", "info", "success", "warning"] or nothing
	 */
	public $offStyle = 'danger';

	/**
	 * Widget's run function
	 */
	public function run()
	{
		$this->renderField();
		$this->registerClientScript();
	}

	/**
	 * Renders the input field
	 */
	public function renderField()
	{
		$options = $this->buildOptions();
		echo \CHtml::openTag('div', $options);
		if ($this->hasModel()) {
			echo \CHtml::activeCheckBox($this->model, $this->attribute, $this->options);
		} else {
			echo \CHtml::checkBox($this->options['name'], $this->value, $this->options);
		}
		echo \CHtml::closeTag('div');
	}

	/**
	 * Builds meta-tag options based on the options and attributes
	 */
	public function buildOptions()
	{
		$options = $this->options;
		ArrayHelper::remove($this->options, 'disabled');
		$this->addCssClass($options, 'switch');
		$options['data-animated'] = $this->animated ? 'true' : 'false';
		$options['data-on-label'] = $this->onLabel;
		$options['data-off-label'] = $this->offLabel;
		$options['data-on'] = $this->onStyle ? $this->offStyle : 'primary';
		$options['data-off'] = $this->offStyle ? $this->offStyle : 'default';
	}

	/**
	 * Registers client scripts
	 */
	protected function registerClientScript()
	{

		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.toggle');

		/* @var $cs \CClientScript */
		$cs = \Yii::app()->getClientScript();

		$cs->registerCoreScript('jquery')
			->registerCssFile($assetsUrl . '/css/bootstrapSwitch.css')
			->registerScriptFile($assetsUrl . '/js/bootstrapSwitch.js');
	}
}
