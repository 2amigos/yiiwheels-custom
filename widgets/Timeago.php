<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\widgets;

use wheels\helpers\ArrayHelper;

/**
 * wheels\widgets\Timeago implements timeago JS plugin.
 * @see http://timeago.yarp.com/
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class Timeago extends Widget
{
	/**
	 * @var string the HTML tag type
	 */
	public $tag = 'abbr';
	/**
	 * @var string the language
	 * @see js/locales
	 */
	public $lang = 'en';
	/**
	 * @var string the selector to initialize the widget. Defaults to widget id.
	 */
	public $selector;
	/**
	 * @var string the date to use the timeago against. If null, the widget will not render the tag, assuming that
	 * everything will be handled via the $selector.
	 */
	public $date;

	/**
	 * Widget's initialization method
	 */
	public function init()
	{
		parent::init();
		if (!$this->selector) {
			$this->selector = '#' . ArrayHelper::getValue($this->options, 'id');
		}
	}

	/**
	 * Widget's run method
	 */
	public function run()
	{
		$this->renderContainer();
		$this->registerClientScript();
	}

	/**
	 * Renders plugin container
	 */
	public function renderContainer()
	{
		if ($this->date !== null) {
			$this->options['title'] = $this->date;
			echo \CHtml::tag($this->tag, $this->options, '&nbsp;');
		}
	}

	/**
	 * Registers required client script for sparklines
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.timeago');

		/* @var $cs \CClientScript */
		$cs = \Yii::app()->getClientScript();

		$cs->registerScriptFile($assetsUrl . '/js/jquery.timeago.js');

		if (null !== $this->lang) {
			$cs->registerScriptFile($assetsUrl . '/js/locales/jquery.timeago.' . $this->lang . '.js');
		}

		/* initialize plugin */
		$this->registerPlugin('timeago');
	}

}