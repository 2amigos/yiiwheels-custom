<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\widgets;

use wheels\helpers\ArrayHelper;

/**
 * wheels\widgets\Select2 implements Select2 JS plugin.
 * @see http://ivaynberg.github.io/select2/
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class Select2 extends Input
{

	/**
	 * @var array @param data for generating the list options (value=>display)
	 */
	public $data = array();
	/**
	 * @var bool whether to display a dropdown select box or use it for tagging
	 */
	public $asDropDownList = true;
	/**
	 * @var string locale. Defaults to null. Possible values: "it"
	 */
	public $lang;


	/**
	 * Initializes the widget.
	 * @throws \CException
	 */
	public function init()
	{
		parent::init();
		if (empty($this->data) && $this->asDropDownList === true) {
			throw new \CException(Yii::t('zii', '"data" attribute cannot be blank'));
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
	 * Renders the select2 field
	 */
	public function renderField()
	{
		if ($this->hasModel()) {
			echo $this->asDropDownList ?
				\CHtml::activeDropDownList($this->model, $this->attribute, $this->data, $this->options) :
				\CHtml::activeHiddenField($this->model, $this->attribute);

		} else {
			echo $this->asDropDownList ?
				\CHtml::dropDownList($this->options['name'], $this->value, $this->data, $this->options) :
				\CHtml::hiddenField($this->options['name'], $this->value);
		}
	}

	/**
	 * Registers required client script for bootstrap select2. It is not used through bootstrap->registerPlugin
	 * in order to attach events if any
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.select2');

		/* @var $cs \CClientScript */
		$cs = \Yii::app()->getClientScript();

		$cs->registerCssFile($assetsUrl . '/css/select2.css')
			->registerScriptFile($assetsUrl . '/js/select2.js');

		if ($this->lang) {
			$cs->registerScriptFile(
				$assetsUrl . '/js/locale/select2_locale_' . $this->lang . '.js',
				\CClientScript::POS_END
			);
		}

		$this->registerPlugin('select2');
	}
}