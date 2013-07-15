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
 * wheels\widgets\FileuploadX implements an extended version of jQuery-File-Upload JS plugin with views.
 * @see https://github.com/blueimp/jQuery-File-Upload
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class FileuploadX extends Fileupload
{
	/**
	 * set to true to use multiple file upload
	 * @var boolean
	 */
	public $multiple = false;
	/**
	 * The upload template id to display files available for upload
	 * defaults to null, meaning using the built-in template
	 */
	public $uploadTemplate;
	/**
	 * The template id to display files available for download
	 * defaults to null, meaning using the built-in template
	 */
	public $downloadTemplate;
	/**
	 * Wheter or not to preview image files before upload
	 */
	public $previewImages = true;
	/**
	 * Wheter or not to add the image processing pluing
	 */
	public $imageProcessing = true;
	/**
	 * @var string name of the form view to be rendered
	 */
	public $formView = 'wheels.views.fileupload.form';
	/**
	 * @var string name of the upload view to be rendered
	 */
	public $uploadView = 'wheels.views.fileupload.upload';
	/**
	 * @var string name of the download view to be rendered
	 */
	public $downloadView = 'wheels.views.fileupload.download';
	/**
	 * @var string name of the view to display images at bootstrap-slideshow
	 */
	public $galleryView = 'wheels.views.fileupload.gallery';


	/**
	 * Widget initialization
	 */
	public function init()
	{
		parent::init();

		if ($this->uploadTemplate === null) {
			$this->uploadTemplate = "#template-upload";
		}

		if ($this->downloadTemplate === null) {
			$this->downloadTemplate = "#template-download";
		}
		ArrayHelper::remove($this->options, 'data-url'); // remove parent's unnecessary setting
		$this->options['encrypt'] = ArrayHelper::getValue($this->options, 'encrypt', 'multipart/form-data');
		$this->options['id'] = ($this->hasModel() ? get_class($this->model) : 'fileupload') . '-form';
		$this->clientOptions['url'] = \CHtml::normalizeUrl($this->route);
		if ($this->multiple) {
			$this->options["multiple"] = true;
		}
	}

	/**
	 * Generates the required HTML and Javascript
	 */
	public function run()
	{
		$this->renderViews();
		$this->registerClientScript();
	}

	/**
	 * Renders fileupload views
	 */
	public function renderViews()
	{
		$htmlOptions = array();
		$htmlOptions['multiple'] = ArrayHelper::remove($this->options, 'multiple', '');
		$htmlOptions['name'] = ArrayHelper::remove($this->options, 'name');
		$this->render($this->uploadView);
		$this->render($this->downloadView);
		$this->render($this->formView, compact('htmlOptions'));

		if ($this->previewImages || $this->imageProcessing) {
			$this->render($this->galleryView);
		}
	}

	/**
	 * Registers and publishes required scripts
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.fileupload');

		/* @var $cs \CClientScript */
		$cs = \Yii::app()->getClientScript();

		$cs->registerCssFile($assetsUrl . '/css/jquery.fileupload-ui.css');

		// Upgrade widget factory
		// @todo remove when jquery.ui 1.9+ is fully integrated into stable Yii versions
		AssetManager::registerScriptFile('jquery.ui.widget.js');

		//The Templates plugin is included to render the upload/download listings
		$cs->registerScriptFile($assetsUrl . '/js/tmpl.min.js', \CClientScript::POS_END);

		if ($this->previewImages || $this->imageProcessing) {
			$cs->registerScriptFile($assetsUrl . '/js/load-image.min.js', \CClientScript::POS_END);
			$cs->registerScriptFile($assetsUrl . '/js/canvas-to-blob.min.js', \CClientScript::POS_END);
			// gallery :)
			AssetManager::registerCssFile(YII_DEBUG ? "bootstrap-image-gallery.css" : "bootstrap-image-gallery.min.css");
			AssetManager::registerScriptFile((YII_DEBUG ? "bootstrap-image-gallery.js" : "bootstrap-image-gallery.min.js"));
		}
		//The Iframe Transport is required for browsers without support for XHR file uploads
		$cs->registerScriptFile($assetsUrl . 'jquery.iframe-transport.js');
		$cs->registerScriptFile($assetsUrl . 'jquery.fileupload.js');
		// The File Upload image processing plugin
		if ($this->imageProcessing) {
			$cs->registerScriptFile($assetsUrl . '/js/jquery.fileupload-ip.js');
		}
		// The File Upload file processing plugin
		if ($this->previewImages) {
			$cs->registerScriptFile($assetsUrl . '/js/jquery.fileupload-fp.js');
		}
		// locale
		$cs->registerScriptFile($assetsUrl . '/js/jquery.fileupload-locale.js');
		//The File Upload user interface plugin
		$cs->registerScriptFile($assetsUrl . '/js/jquery.fileupload-ui.js');

		$this->registerPlugin('fileupload');
	}
}