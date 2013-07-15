<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\bootstrap;

/**
 * wheels\bootstrap\Widget is the base class for all wheels\bootstrap\widgets
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\bootstrap
 * @since 1.0
 */
class Widget extends \CWidget
{
	/**
	 * @var boolean whether to use the responsive version of Bootstrap.
	 */
	public static $responsive = true;
	/**
	 * @var array the HTML attributes for the widget container tag.
	 */
	public $options = array();
	/**
	 * @var array the options for the underlying Bootstrap JS plugin.
	 * Please refer to the corresponding Bootstrap plugin Web page for possible options.
	 * For example, [this page](http://twitter.github.io/bootstrap/javascript.html#modals) shows
	 * how to use the "Modal" plugin and the supported options (e.g. "remote").
	 */
	public $clientOptions = array();
	/**
	 * @var array the event handlers for the underlying Bootstrap JS plugin.
	 * Please refer to the corresponding Bootstrap plugin Web page for possible events.
	 * For example, [this page](http://twitter.github.io/bootstrap/javascript.html#modals) shows
	 * how to use the "Modal" plugin and the supported events (e.g. "shown").
	 */
	public $clientEvents = array();
	/**
	 * @var string holds a reference to the bootstrap assets url
	 */
	private $_assetsUrl;


	/**
	 * Initializes the widget.
	 * This method will register the bootstrap asset bundle. If you override this method,
	 * make sure you call the parent implementation first.
	 */
	public function init()
	{
		parent::init();
		if (!isset($this->options['id'])) {
			$this->options['id'] = $this->getId();
		}
		// ensure alias
		if(\Yii::getPathOfAlias('wheels')===false){
			\Yii::setPathOfAlias('wheels', realpath(dirname(__FILE__) . '/../'));
		}
		$this->registerCss();
	}

	/**
	 * Creates a widget instance and runs it.
	 * The widget rendering result is returned by this method.
	 * @param array $config name-value pairs that will be used to initialize the object properties
	 * @return string the rendering result of the widget.
	 */
	public static function widget($config = array())
	{
		ob_start();
		ob_implicit_flush(false);
		/** @var Widget $widget */
		$config['class'] = get_called_class();
		$widget = \Yii::createComponent($config);
		$widget->init();
		$widget->run();
		return ob_get_clean();
	}

	/**
	 * Registers a specific Bootstrap plugin and the related events
	 * @param string $name the name of the Bootstrap plugin
	 */
	protected function registerPlugin($name)
	{
		$id = $this->options['id'];
		/* @var  \CClientScript $cs */
		$cs = \Yii::app()->clientScript;
		$cs->registerCoreScript('jquery')
			->registerScriptFile($this->getAssetsUrl() . "/js/bootstrap-$name.js");

		if ($this->clientOptions !== false) {
			$options = empty($this->clientOptions) ? '' : \CJavaScript::encode($this->clientOptions);
			$js = "jQuery('#$id').$name($options);";
			$cs->registerScript(md5($js), $js);
		}

		if (!empty($this->clientEvents)) {
			$js = array();
			foreach ($this->clientEvents as $event => $handler) {
				$js[] = "jQuery('#$id').on('$event', $handler);";
			}
			$js = implode("\n", $js);
			$cs->registerScript(md5($js), $js);
		}
	}

	/**
	 * Adds a CSS class to the specified options.
	 * This method will ensure that the CSS class is unique and the "class" option is properly formatted.
	 * @param array $options the options to be modified.
	 * @param string $class the CSS class to be added
	 */
	protected function addCssClass(&$options, $class)
	{
		if (isset($options['class'])) {
			$classes = preg_split('/\s+/', $options['class'] . ' ' . $class, -1, PREG_SPLIT_NO_EMPTY);
			$options['class'] = implode(' ', array_unique($classes));
		} else {
			$options['class'] = $class;
		}
	}

	/**
	 * Returns the url to the published assets folder.
	 * @return string the url.
	 */
	protected function getAssetsUrl()
	{
		if (isset($this->_assetsUrl))
			return $this->_assetsUrl;
		else {
			$assetsPath = \Yii::getPathOfAlias('wheels.bootstrap.assets');
			$assetsUrl = \Yii::app()->assetManager->publish($assetsPath, true, -1, YII_DEBUG);
			return $this->_assetsUrl = $assetsUrl;
		}
	}

	/**
	 * Registers core bootstrap css scripts
	 */
	public function registerCss()
	{
		/* @var  \CClientScript $cs */
		$cs = \Yii::app()->clientScript;
		$fileName = YII_DEBUG ? 'bootstrap.css' : 'bootstrap.min.css';
		$url = $this->getAssetsUrl() . '/css/' . $fileName;
		$cs->registerCssFile($url);
		if (static::$responsive) {
			$fileName = YII_DEBUG ? 'bootstrap-responsive.css' : 'bootstrap-responsive.min.css';
			$url = $this->getAssetsUrl() . '/css/' . $fileName;
			$cs->registerMetaTag('width=device-width, initial-scale=1.0', 'viewport');
			$cs->registerCssFile($url);
		}
	}
}
