<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace wheels\widgets;

use wheels\helpers\ArrayHelper;

/**
 * wheels\widgets\HighCharts widget class
 *
 * HighCharts is a layer of the amazing {@link http://www.highcharts.com/ Highcharts}
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * HighCharts::widget(array(
 *    'options'=>array(
 *       'title' => array('text' => 'Fruit Consumption'),
 *       'xAxis' => array(
 *          'categories' => array('Apples', 'Bananas', 'Oranges')
 *       ),
 *       'yAxis' => array(
 *          'title' => array('text' => 'Fruit eaten')
 *       ),
 *       'series' => array(
 *          array('name' => 'Jane', 'data' => array(1, 0, 4)),
 *          array('name' => 'John', 'data' => array(5, 7, 3))
 *       )
 *    )
 * ));
 * </pre>
 *
 * To find out more about the possible {@link $options} attribute please refer to
 * {@link http://www.hightcharts.com/ Highcharts site}
 *
 * @see https://developers.google.com/chart/interactive/docs/reference
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */

class WhHighCharts extends Widget
{

	/**
	 * Widget's initialization method
	 */
	public function init()
	{
		parent::init();
		// if there is no renderTo id, build the layer with current id and initialize renderTo option
		if (!isset($this->clientOptions['chart']) || !isset($this->clientOptions['chart']['renderTo'])) {
			echo \CHtml::tag('div', $this->options);
			if (isset($this->clientOptions['chart']) && is_array($this->clientOptions['chart'])) {
				$this->clientOptions['chart']['renderTo'] = $this->options['id'];
			} else {
				$this->clientOptions['chart'] = array('renderTo' => $this->options['id']);
			}
		}
	}

	/**
	 * Renders the widget.
	 */
	public function run()
	{
		$this->registerClientScript();
	}

	/**
	 * Publishes and registers the necessary script files.
	 */
	protected function registerClientScript()
	{
		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.highcharts');

		/* @var $cs CClientScript */
		$cs = \Yii::app()->getClientScript();
		$cs->registerScriptFile($assetsUrl . '/js/highcharts.js');

		/* register required files */
		$defaultOptions = array('exporting' => array('enabled' => true));
		$this->clientOptions = ArrayHelper::merge($defaultOptions, $this->clientOptions);

		if (isset($this->clientOptions['exporting']) && @$this->clientOptions['exporting']['enabled']) {
			$cs->registerScriptFile($assetsUrl . '/js/modules/exporting.js');
		}
		if ($theme = ArrayHelper::getValue($this->clientOptions, 'theme')) {
			$cs->registerScriptFile($assetsUrl . '/js/themes/' . $theme . '.js');
		}

		$options = \CJavaScript::encode($this->clientOptions);
		$script = "var highchart{$this->options['id']} = new Highcharts.Chart({$options});";

		$cs->registerScript(md5($script), $script);
	}
}