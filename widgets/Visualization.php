<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace wheels\widgets;

/**
 * wheels\widgets\Visualization implements basic Google's Visualization chart
 * @see https://developers.google.com/chart/interactive/docs/reference
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class Visualization extends Widget
{
	/**
	 * @var string $containerId the container Id to render the visualization to
	 */
	public $containerId;
	/**
	 * @var string $visualization the type of visualization -ie PieChart
	 * @see https://google-developers.appspot.com/chart/interactive/docs/gallery
	 */
	public $visualization;
	/**
	 * @var array $data the data to configure visualization
	 * @see https://google-developers.appspot.com/chart/interactive/docs/datatables_dataviews#arraytodatatable
	 */
	public $data = array();


	/**
	 * Widget's run method
	 */
	public function run()
	{
		$this->renderContainer();
		$this->registerClientScript();
	}

	/**
	 * Renders container where to allocate the chart.
	 */
	public function renderContainer()
	{
		// if no container is set, it will create one
		if ($this->containerId == null) {
			$this->containerId = 'div-chart' . $this->options['id'];
			echo '<div ' . \CHtml::renderAttributes($this->options) . '></div>';
		}
	}

	/**
	 * Registers required scripts
	 */
	public function registerClientScript()
	{
		$id = $this->options['id'];
		$jsData = \CJavaScript::jsonEncode($this->data);
		$jsOptions = \CJavaScript::jsonEncode($this->options);

		$script = "
			google.setOnLoadCallback(drawChart{$id});
			var {$id}=null;
			function drawChart{$id}() {
				var data = google.visualization.arrayToDataTable({$jsData});

				var options = {$jsOptions};

				{$id} = new google.visualization.{$this->visualization}(document.getElementById('{$this->containerId}'));
				{$id}.draw(data, options);
			}";

		/** @var $cs \CClientScript */
		$cs = \Yii::app()->getClientScript();
		$cs->registerScriptFile('https://www.google.com/jsapi');
		$cs->registerScript(
			__CLASS__,
			'google.load("visualization", "1", {packages:["corechart"]});',
			\CClientScript::POST_END
		);
		$cs->registerScript(md5($script), $script, \CClientScript::POS_END);
	}
}