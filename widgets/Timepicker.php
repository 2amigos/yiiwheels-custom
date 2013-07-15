<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\widgets;

/**
 * wheels\widgets\Timepicker implements bootstrap timepicker JS plugin.
 * @see http://jdewit.github.io/bootstrap-timepicker/
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class Timepicker extends Input
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
	 * Renders the field
	 */
	public function renderField()
	{
		echo \CHtml::openTag('span', array('class' => 'bootstrap-timepicker'));
		parent::renderField();
		echo \CHtml::closeTag('span');
	}

	/**
	 * Registers required javascript files
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.timepicker');

		/* @var $cs \CClientScript */
		$cs = \Yii::app()->getClientScript();

		$cs->registerCssFile($assetsUrl . '/css/bootstrap-timepicker.min.css')
			->registerScriptFile($assetsUrl . '/js/bootstrap-timepicker.min.js');

		$this->registerPlugin('timepicker');
	}
}