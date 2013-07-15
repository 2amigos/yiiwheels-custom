<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\widgets;

use wheels\helpers\ArrayHelper;
use wheels\bootstrap\Modal;

/**
 * wheels\widgets\ModalManager extends bootstrap modal
 * @ssee https://github.com/jschr/bootstrap-modal
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class ModalManager extends Modal
{

	/**
	 * Widget's initialization
	 */
	public function init()
	{
		parent::init();
	}

	/**
	 * Widget's run method
	 */
	public function run()
	{
		parent::run();
		$this->registerPluginFiles();
	}

	/**
	 * Registers required plugins files (js|img|css)
	 */
	public function registerPluginFiles()
	{
		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.modalmanager');

		/* @var $cs CClientScript */
		$cs = \Yii::app()->getClientScript();

		$cs->registerCssFile($assetsUrl . '/css/bootstrap-modal.css');
		$cs->registerScriptFile($assetsUrl . '/js/bootstrap-modal.js', \CClientScript::POS_END);
		$cs->registerScriptFile($assetsUrl . '/js/bootstrap-modalmanager.js', \CClientScript::POS_END);
	}

}