<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\widgets;

use wheels\helpers\ArrayHelper;

/**
 * wheels\widgets\MultiSelect
 * @ssee https://github.com/davidstutz/bootstrap-multiselect
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class MultiSelect extends Input
{
	/**
	 * @var array @param data for generating the list options (value=>display)
	 */
	public $data = array();

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		parent::init();
		if (empty($this->data) && $this->asDropDownList === true) {
			throw new \CException(Yii::t('wheels', '"data" attribute cannot be blank'));
		}
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
	 * Renders the multiselect field
	 */
	public function renderField()
	{
		if ($this->hasModel()) {
			echo \CHtml::activeDropDownList($this->model, $this->attribute, $this->data, $this->options);
		} else {
			echo \CHtml::dropDownList($this->name, $this->value, $this->data, $this->options);
		}
	}

	/**
	 * Registers required client script for bootstrap multiselect. It is not used through bootstrap->registerPlugin
	 * in order to attach events if any
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.multiselect');

		/* @var $cs \CClientScript */
		$cs = \Yii::app()->getClientScript();

		$cs->registerCssFile($assetsUrl . '/css/bootstrap-multiselect.css')
			->registerScriptFile($assetsUrl . '/js/bootstrap-multiselect.js');

		$this->registerPlugin('multiselect');
	}
}