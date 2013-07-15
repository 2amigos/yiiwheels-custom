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
 * wheels\widgets\Fileupload implements jQuery-File-Upload JS plugin.
 * @see https://github.com/blueimp/jQuery-File-Upload
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class Fileupload extends Input
{
	/**
	 * @var string upload action url
	 */
	public $route;

	/**
	 * Widget's initialization method
	 * @throws \CException
	 */
	public function init()
	{
		parent::init();
		if ($this->route === null) {
			throw new \CException(\Yii::t('wheels', '"route" attribute cannot be blank'));
		}
		$this->options['data-url'] = \CHtml::normalizeUrl($this->route);
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
	 * Renders the input file field
	 */
	public function renderField()
	{
		if ($this->hasModel()) {
			echo \CHtml::activeFileField($this->model, $this->attribute, $this->options);

		} else {
			echo \CHtml::fileField($this->options['name'], $this->value, $this->options);
		}
	}

	/**
	 * Registers client script
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.fileupload');

		/* @var $cs \CClientScript */
		$cs = \Yii::app()->getClientScript();

		AssetManager::registerScriptFile('jquery.ui.widget.js');

		$cs->registerCssFile($assetsUrl . '/css/jquery.fileupload-ui.css')
			->registerScriptFile($assetsUrl . '/js/jquery.iframe-transport.js')
			->registerScriptFile($assetsUrl . '/js/jquery.fileupload.js');

		$this->registerPlugin('fileupload');
	}

}