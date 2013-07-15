<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\widgets;

use wheels\helpers\ArrayHelper;

/**
 * wheels\actions\ToggleButton implements old bootstrap-toggle buttons.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class ToggleButton extends Input
{
	/**
	 * @var int the width of the toggle button
	 */
	public $width = 100;
	/**
	 * @var int the height of the toggle button
	 */
	public $height = 25;
	/**
	 * @var bool whether to use animation or not
	 */
	public $animated = true;
	/**
	 * @var mixed the transition speed (toggle movement). Accepted values: float or percent [1, 0.5, '150%']
	 */
	public $transitionSpeed;
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
	public $enabledStyle = 'primary';
	/**
	 * @var string the style of the toggle button disabled style
	 * Accepted values ["primary", "danger", "info", "success", "warning"] or nothing
	 */
	public $disabledStyle = null;
	/**
	 * @var array a custom style for the enabled option. Format
	 * <pre>
	 *  ...
	 *  'customEnabledStyle'=>array(
	 *      'background'=>'#FF00FF',
	 *      'gradient'=>'#D300D3',
	 *      'color'=>'#FFFFFF'
	 *  ),
	 *  ...
	 * </pre>
	 */
	public $customEnabledStyle = array();
	/**
	 * @var array a custom style for the disabled option. Format
	 * <pre>
	 *  ...
	 *  'customDisabledStyle'=>array(
	 *      'background'=>'#FF00FF',
	 *      'gradient'=>'#D300D3',
	 *      'color'=>'#FFFFFF'
	 *  ),
	 *  ...
	 * </pre>
	 */
	public $customDisabledStyle = array();
	/**
	 * @var string the tag name. Defaults to 'div'.
	 */
	public $tagName = 'div';


	/**
	 * Widget's initialization method
	 */
	public function init()
	{
		parent::init();
		$this->buildOptions();
	}

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
		echo \CHtml::openTag($this->tagName, array('id' => 'wrapper-' . $this->options['id']));
		if ($this->hasModel()) {
			echo \CHtml::activeCheckBox($this->model, $this->attribute, $this->options);
		} else {
			echo \CHtml::checkBox($this->options['name'], $this->value, $this->options);
		}
		echo \CHtml::closeTag($this->tagName);
	}

	/**
	 * Registers client scripts
	 */
	protected function registerClientScript()
	{

		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.timepicker');

		/* @var $cs \CClientScript */
		$cs = \Yii::app()->getClientScript();

		$cs->registerCoreScript('jquery')
			->registerCssFile($assetsUrl . '/css/bootstrap-toggle-buttons.css')
			->registerScriptFile($assetsUrl . '/js/jquery.toggle.buttons.js');

		$this->registerPlugin('toggleButtons');
	}

	/**
	 * Builds the [[clientOptions]] of the plugin.
	 */
	protected function buildOptions()
	{
		$onChange = ArrayHelper::remove($this->clientEvents, 'onChange', 'js:$.noop');

		$config = array(
			'onChange' => $onChange,
			'width' => $this->width,
			'height' => $this->height,
			'animated' => $this->animated,
			'transitionSpeed' => $this->transitionSpeed,
			'label' => array(
				'enabled' => $this->onLabel,
				'disabled' => $this->offLabel
			),
			'style' => array()
		);
		if (!empty($this->enabledStyle)) {
			$config['style']['enabled'] = $this->enabledStyle;
		}
		if (!empty($this->disabledStyle)) {
			$config['style']['disabled'] = $this->disabledStyle;
		}
		if (!empty($this->customEnabledStyle)) {
			$config['style']['custom'] = array('enabled' => $this->customEnabledStyle);
		}
		if (!empty($this->customDisabledStyle)) {
			if (isset($config['style']['custom'])) {
				$config['style']['custom']['disabled'] = $this->customDisabledStyle;
			} else {
				$config['style']['custom'] = array('disabled' => $this->customDisabledStyle);
			}
		}
		foreach ($config as $key => $element) {
			if (empty($element)) {
				unset($config[$key]);
			}
		}
		$this->clientOptions = ArrayHelper::merge($this->clientOptions, $config);
	}
}