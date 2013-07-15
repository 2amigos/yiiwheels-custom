<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\widgets;

/**
 * wheels\widgets\Sparklines implements Sparklines JS plugin.
 * @see http://omnipotent.net/jquery.sparkline/#s-about
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class Sparklines extends Widget
{
	/**
	 * @var string the tag name to render the sparkline to
	 * NOTE: span type of tag may have issues.
	 */
	public $tag = 'div';
	/**
	 * @var array the data to show on the chart
	 * @see http://omnipotent.net/jquery.sparkline/#s-about
	 */
	public $data = array();


	/**
	 * Widget's initialization method
	 */
	public function init()
	{
		parent::init();
		if (empty($this->data)) {
			throw new CException(Yii::t('zii', '"data" attribute cannot be blank'));
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
	 * Renders sparklines container
	 */
	public function renderContainer()
	{
		echo \CHtml::openTag($this->tag, $this->options);
		echo \CHtml::closeTag($this->tag);
	}

	/**
	 * Registers required client script for sparklines
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.sparklines');

		/* @var $cs \CClientScript */
		$cs = \Yii::app()->getClientScript();

		$script = YII_DEBUG ? 'jquery.sparkline.js' : 'jquery.sparkline.min.js';

		$cs->registerScriptFile($assetsUrl . '/js/' . $script);

		$data = \CJavaScript::encode($this->data);
		$options = \CJavaScript::encode($this->clientOptions);
		$js =

		$js = array();
		$js[] = ";jQuery('#{$this->options['id']}').sparkline({$data}, {$options})";

		if (!empty($this->clientEvents) && is_array($this->evenclientEventsts)) {
			foreach ($this->clientEvents as $name => $handler) {
				$handler = ($handler instanceof \CJavaScriptExpression)
					? $handler
					: new \CJavaScriptExpression($handler);

				$js[] = ".on('{$name}', {$handler});";
			}
		}

		$js = implode("\n", $js);
		$cs->registerScript(md5($js), $js);
	}
}