<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace wheels\widgets;

/**
 * wheels\widgets\MarskMoney implements maskMoney jquery plugin
 * @see http://plentz.github.com/jquery-maskmoney/
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class WhMaskMoney extends Input
{

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		$this->renderField();
		$this->registerClientScript();
	}

	/**
	 * Registers required client script for bootstrap multiselect. It is not used through bootstrap->registerPlugin
	 * in order to attach events if any
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.maskmoney');

		/* @var $cs CClientScript */
		$cs = \Yii::app()->getClientScript();

		$cs->registerScriptFile($assetsUrl . '/js/jquery.maskmoney.js');

		$this->registerPlugin('maskMoney');
	}
}