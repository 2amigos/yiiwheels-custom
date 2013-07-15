<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\widgets;

use wheels\helpers\ArrayHelper;

/**
 * wheels\widgets\Redactor
 * @ssee http://imperavi.com/redactor/
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class Redactor extends Input
{

	/**
	 * Widget's init function
	 */
	public function init()
	{
		parent::init();

		$this->options['style'] = ArrayHelper::getValue($this->options, 'style', '');

		$width = ArrayHelper::getValue($this->options, 'width', '100%');
		$height = ArrayHelper::remove($this->options, 'height', '450px');
		$this->options['style'] = "width:{$width};height:{$height};" . $this->options['style'];
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

		if ($this->hasModel()) {
			echo \CHtml::activeTextArea($this->model, $this->attribute, $this->options);
		} else {
			echo \CHtml::textArea($this->options['name'], $this->value, $this->options);
		}

	}

	/**
	 * Registers required client script for bootstrap select2. It is not used through bootstrap->registerPlugin
	 * in order to attach events if any
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.redactor');

		/* @var $cs CClientScript */
		$cs = \Yii::app()->getClientScript();

		$script = YII_DEBUG ? 'redactor.js' : 'redactor.min.js';

		$cs->registerCssFile($assetsUrl . '/css/redactor.css')
			->registerScriptFile($assetsUrl . '/js/' . $script);

		/* register language */
		$language = ArrayHelper::getValue($this->clientOptions, 'lang');
		if (!empty($language) && $language != 'en') {
			$cs->registerScriptFile($assetsUrl . '/js/langs/' . $language . '.js', \CClientScript::POS_END);
		}

		/* register plugins (if any) */
		$this->registerPlugins($assetsUrl);

		$this->registerPlugin('redactor');
	}

	/**
	 * @param $assetsUrl
	 */
	protected function registerPlugins($assetsUrl)
	{
		if (isset($this->clientOptions['plugins'])) {
			$ds = DIRECTORY_SEPARATOR;
			$pluginsPath = \Yii::getPathOfAlias('wheels.widgets.assets.redactor.plugins');
			$pluginsUrl = $assetsUrl . '/js/plugins/';
			$scriptTypes = array('css', 'js');

			foreach ($this->clientOptions['plugins'] as $pluginName) {
				foreach ($scriptTypes as $type) {
					if (@file_exists("{$pluginsPath}$ds{$pluginName}$ds{$pluginName}$ds{$type}")) {
						\Yii::app()->clientScript->registerScriptFile(
							"{$pluginsUrl}/{$pluginName}/{$pluginName}/{$type}"
						);
					}
				}
			}
		}
	}
}