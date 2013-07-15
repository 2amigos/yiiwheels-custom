<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\widgets;

use wheels\helpers\ArrayHelper;

/**
 * wheels\widgets\Ace
 * @ssee https://github.com/ajaxorg/ace
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class Ace extends Input
{
	/**
	 * @var string the theme
	 */
	public $theme = 'clouds';
	/**
	 * @var string the editor mode
	 */
	public $mode = 'html';

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		parent::init();
		if (empty($this->theme)) {
			throw new \CException(\Yii::t(
				'wheels',
				'"{attribute}" cannot be empty.',
				array('{attribute}' => 'theme')
			));
		}

		if (empty($this->mode)) {
			throw new \CException(\Yii::t(
				'zii',
				'"{attribute}" cannot be empty.',
				array('{attribute}' => 'mode')
			));
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
	 * Renders field
	 */
	public function renderField()
	{
		list($name, $id) = $this->resolveNameID();

		$this->options['id'] = ArrayHelper::getValue($this->options, 'id', $id);
		$this->options['name'] = ArrayHelper::getValue($this->options, 'name', $name);

		$tagOptions = $this->options;

		$tagOptions['id'] = 'aceEditor_' . $tagOptions['id'];

		echo \CHtml::tag('div', $tagOptions);

		$this->options['style'] = 'display:none';

		if ($this->hasModel()) {
			echo \CHtml::activeTextArea($this->model, $this->attribute, $this->options);
		} else {
			echo \CHtml::textArea($name, $this->value, $this->options);
		}

		$this->options = $tagOptions;
	}

	/**
	 * Registers required client script for bootstrap ace editor.
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
		$assetsUrl = $this->getAssetsUrl($path);

		/* @var $cs CClientScript */
		$cs = \Yii::app()->getClientScript();

		$cs->registerScriptFile($assetsUrl . '/js/ace.js');
		$id = \CHtml::getOption('id', $this->options, $this->getId());

		/* Global value that will hold the editor */
		$cs->registerScript(uniqid(__CLASS__ . '#' . $id, true), 'var ' . $id . ';', \CClientScript::POS_HEAD);

		/* initialize plugin */
		$js = array();
		$js[] = $id . '= ace.edit("' . $id . '");';
		$js[] = $id . '.setTheme("ace/theme/' . $this->theme . '");';
		$js[] = $id . '.getSession().setMode("ace/mode/' . $this->mode . '");';

		if (!empty($this->clientEvents) && is_array($this->evenclientEventsts)) {
			foreach ($this->clientEvents as $name => $handler) {
				$handler = ($handler instanceof \CJavaScriptExpression)
					? $handler
					: new \CJavaScriptExpression($handler);

				$js[] = $id . ".getSession().on('{$name}', {$handler});";
			}
		}

		$js = implode("\n", $js);

		$cs->registerScript(md5($js), $js);
	}
}