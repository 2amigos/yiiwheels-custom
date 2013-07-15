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
 * WhDateRangePicker widget class
 * A simple implementation for date range picker for Twitter Bootstrap
 * @see <http://www.dangrossman.info/2012/08/20/a-date-range-picker-for-twitter-bootstrap/>
 * @ssee https://github.com/dangrossman/bootstrap-daterangepicker
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class RangeSlider extends Input
{

	/**
	 * @var string lets you specify what type of jQRangeSlider you wish to display. Defaults to "range". Possible values
	 * are:
	 * - 'range'
	 * - 'editRange'
	 * - 'dateRange'
	 */
	public $type = 'range';
	/**
	 * @var bool lets you remove scrolling arrows on both sides of the slider. Defaults to true.
	 */
	public $arrows = true;
	/**
	 * @var mixed lets you specify the min bound value of the range. This value Defaults to 0.
	 * <strong>Note</strong> when using 'dateRange' type, this value can be string representing a javascript date. For
	 * example: `'minValue'=>'js:new Date( 2012, 0, 1)`'
	 */
	public $minValue = 0;
	/**
	 * @var mixed lets you specify the max bound value of the range. This value Defaults to 100.
	 * <strong>Note</strong> when using 'dateRange' type, this value can be a string representing a javascript date. For
	 * example: `'maxValue'=>'js:new Date( 2012, 0, 1)`'
	 */
	public $maxValue = 100;
	/**
	 * @var mixed lets you specify min value by default on construction of the widget. Defaults to 0.
	 * <strong>Note</strong> when  using 'dateRange' type, this value can be a string representing a javascript date. For
	 * example: `'minDefaultValue'=>'js:new Date( 2012, 0, 1)`'
	 */
	public $minDefaultValue = 0;
	/**
	 * @var mixed lets you specify max valuee by default on construction of the widget. Defaults to 100.
	 * <strong>Note</strong> when  using 'dateRange' type, this value can be a string representing a javascript date. For
	 * example: `'minDefaultValue'=>'js:new Date( 2012, 0, 1)`'
	 */
	public $maxDefaultValue = 100;
	/**
	 * @var int lets you specify the duration labels are shown after the user changed its values. Defaults to null.
	 * <strong>Note</strong>: This option can only be used in conjunction with `'valueLabels'=>'change'`
	 */
	public $delayOut;
	/**
	 * @var int lets you specify the animation length when displaying value labels. Similarly,`durationOut` allows to
	 * customize the animation duration when hiding those labels. Defaults to null.
	 * <strong>Note</strong>: This option can only be used in conjunction with `'valueLabels'=>'change'`
	 */
	public $durationIn;
	/**
	 * @var int lets you specify the animation length when hiding value labels. Similarly,`durationIn` allows to
	 * customize the animation duration when displaying those labels. Defaults to null.
	 * <strong>Note</strong>: This option can only be used in conjunction with `'valueLabels'=>'change'`
	 */
	public $durationOut;
	/**
	 * @var string a javascript function that lets you customize displayed label text.
	 * Example:
	 * ```
	 *   'formater'=>'js:function(val){
	var value = Math.round(val * 5) / 5,
	 *      decimal = value - Math.round(val);
	 *      return decimal == 0 ? value.toString() + ".0" : value.toString();
	 *   }'
	 */
	public $formatter;
	/**
	 * @var int lets you specify minimum range length. It works in conjunction with `maxRange`.
	 * For instance, let's consider you want the user to choose a range of dates during 2012. You can constraint people
	 * to choose at least 1 week during this period. Similarly, you also can constraint the user to choose 90 days at
	 * maximum.
	 * When this option is activated, the slider constraints the user input. When minimum or maximum value is reached,
	 * the user cannot move an extremity further to shrink/enlarge the selected range.
	 */
	public $minRange = 0;
	/**
	 * @var int see `minRange` documentation above.
	 */
	public $maxRange = 100;
	/**
	 * @var int allows to customize values rounding, and graphically render this rounding. Considering you configured a
	 * slider with a step value of 10: it'll only allow your user to choose a value corresponding to minBound + 10n.
	 * <strong>Warning</strong> For the date slider there is a small variation, the following is an example with days:
	 *
	 * ```
	 *  \\...
	 *  'step'=>array('days'=>2)
	 *  \\...
	 */
	public $step;
	/**
	 * @var string allows to specify input types in edit slider. Possible values are 'text' (default) and 'number'.
	 * <strong>Note</strong>: This option is only available on `editRange` `type`.
	 */
	public $inputType = 'text';
	/**
	 * @var string lets you specify a display mode for value labels: `hidden`, `shown`, or only shown when moving.
	 * Possible values are: show, hide and change.
	 */
	public $valueLabels = 'shown';
	/**
	 * @var string allows to use the mouse wheel to `scroll` (translate) or `zoom` (enlarge/shrink) the selected area in
	 * jQRangeSlider. Defaults to null.
	 * <strong>Note</strong>: This option requires the plugin `jquery mousehwheel to be loaded`
	 */
	public $wheelMode;
	/**
	 * @var int lets you customize the speed of mouse wheel interactions. This parameter requires the `wheelMode` to be set.
	 */
	public $wheelSpeed;
	/**
	 * @var array The option scales lets you add a ruler with multiple scales to the slider background.
	 *
	 *
	 * ```
	 * 'scales' => array(
	 *      // primary scale
	 *      array(
	 *          'first'=>'js:function(val){return val;},
	 *          'next'=>'js:function(val){return val+10;}',
	 *          'stop'=>'js:function(val){return false;}',
	 *          'label'=>'js:function(val){return val;}',
	 *          'format'=> 'js:function(tickContainer, tickStart, tickEnd){
	 *              tickContainer.addClass("myCustomClass");
	 *          }'
	 *      ),
	 *      // secondary scale
	 *      array(
	 *          'first'=>'js:function(val){return val;},
	 *          'next'=>'js:function(val){ if(val%10 === 9){return val+2;} return val + 1;}',
	 *          'stop'=>'js:function(val){return false;}',
	 *          'label'=>'js:function(val){return null;}',
	 *      ),
	 *  )
	 * ```
	 */
	public $scales;
	/**
	 * @var string the theme to use with the slider. Supported values are "iThing" and "classic"
	 */
	public $theme = 'iThing';


	/**
	 * Widget's initialization
	 */
	public function init()
	{
		parent::init();
		$this->checkOptionAttribute($this->type, array('range', 'editRange', 'dateRange'), 'type');
		$this->checkOptionAttribute($this->inputType, array('text', 'number'), 'inputType');
		$this->checkOptionAttribute($this->valueLabels, array('shown', 'hidden'), 'valueLabels');
		$this->checkOptionAttribute($this->theme, array('iThing', 'classic'), 'theme');
		if ($this->wheelMode) {
			$this->checkOptionAttribute($this->wheelMode, array('zoom', 'scroll'), 'wheelMode');
		}
		$this->buildOptions();
	}

	/**
	 * Widget's run method
	 */
	public function run()
	{
		$this->renderField();
		$this->registerClientScript();
	}

	/**
	 * Renders field and tag
	 */
	public function renderField()
	{
		if ($this->hasModel()) {
			echo \CHtml::activeHiddenField($this->model, $this->attribute, $this->options);
		} else {
			echo \CHtml::hiddenField($this->options['name'], $this->value, $this->options);
		}

		echo '<div id="slider_' . $this->options['id'] . '"></div>';
	}

	/**
	 * Registers required files and initialization script
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.rangeslider');

		$id = $this->options['id'];

		$jsFile = !empty($this->scales)
			? 'JQAllRangeSliders-withRuler-min.js'
			: 'JQAllRangeSliders-min.js';

		/* @var $cs \CClientScript */
		$cs = \Yii::app()->getClientScript();

		$cs->registerCoreScript('jquery')
			->registerCoreScript('jquery.ui')
			->registerScriptFile($assetsUrl . '/js/' . $jsFile)
			->registerCssFile($assetsUrl . '/css/' . $this->theme . '.css');

		AssetManager::registerScriptFile('jquery.mousewheel.min.js');

		$options = !empty($this->clientOptions) ? \CJavaScript::encode($this->clientOptions) : '';

		$inputValSet = "$('#{$id}').val(data.values.min+','+data.values.max);";

		//inserting trigger
		if (isset($this->clientEvents['valuesChanged'])) {
			$orig = $this->clientEvents['valuesChanged'];
			if (strpos($orig, 'js:') === 0) {
				$orig = substr($orig, 3);
			}
			$orig = "\n($orig).apply(this, arguments);";
		} else {
			$orig = '';
		}
		$this->clientEvents['valuesChanged'] = "js: function(id, data) {
			$inputValSet $orig
		}";

		$script = array();
		$script[] = "jQuery('#slider_{$id}').{$this->type}Slider({$options})";
		foreach ($this->events as $event => $handler) {
			$script[] = ".on('{$event}', " . \CJavaScript::encode($handler) . ")";
		}
		$script = implode("\n", $script);
		$cs->registerScript(md5($script), $script . ';');
	}

	/**
	 * Builds the options
	 */
	protected function buildOptions()
	{
		$options = array(
			'arrows' => $this->arrows,
			'delayOut' => $this->delayOut,
			'durationIn' => $this->durationIn,
			'durationOut' => $this->durationOut,
			'valueLabels' => $this->valueLabels,
			'formatter' => $this->formatter,
			'step' => $this->step,
			'wheelMode' => $this->wheelMode,
			'wheelSpeed' => $this->wheelSpeed,
			'type' => ($this->type == 'dateRange' ? null : $this->inputType)
		);
		$this->clientOptions = array_filter($options);

		if ($this->minRange && $this->maxRange && $this->minRange < $this->maxRange) {
			$this->clientOptions = ArrayHelper::merge(
				$this->clientOptions,
				array('range' => array('min' => $this->minRange, 'max' => $this->maxRange))
			);
		}
		if ($this->minValue && $this->maxValue && $this->minValue < $this->maxValue) {
			$this->clientOptions = ArrayHelper::merge(
				$this->clientOptions,
				array('bounds' => array('min' => $this->minValue, 'max' => $this->maxValue))
			);
		}
		if ($this->minDefaultValue && $this->maxDefaultValue && $this->minDefaultValue < $this->maxDefaultValue) {
			$this->clientOptions = ArrayHelper::merge(
				$this->clientOptions,
				array(
					'defaultValues' => array(
						'min' => $this->minDefaultValue,
						'max' => $this->maxDefaultValue
					)
				)
			);
		}
	}

	/**
	 * Checks whether the option set is supported by the plugin
	 * @param mixed $attribute attribute
	 * @param array $availableOptions the possible values
	 * @param string $name the name of the attribute
	 * @throws \CException
	 */
	protected function checkOptionAttribute($attribute, $availableOptions, $name)
	{
		if (in_array($attribute, $availableOptions)) {
			throw new \CException(\Yii::t(
				'zii',
				'Unsupported "{attribute}" setting.',
				array('{attribute}' => $name)
			));
		}
	}
}