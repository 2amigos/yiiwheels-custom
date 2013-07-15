<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\widgets;

use wheels\helpers\ArrayHelper;

/**
 * wheels\widgets\FineUploader implements fineuploader
 * @see http://github.com/Valums-File-Uploader/file-uploader
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class FineUploader extends Input
{
	/**
	 * @var string upload action url
	 */
	public $route;
	/**
	 * @var string the HTML tag to render the uploader to
	 */
	public $tag = 'div';
	/**
	 * @var string text to display if javascript is disabled
	 */
	public $noScriptText;


	/**
	 * Widget's initialization method
	 * @throws \CException
	 */
	public function init()
	{
		parent::init();
		if ($this->route === null) {
			throw new \CException(\Yii::t('wheels', '"route" attribute cannot be blank'));
		}
		if ($this->noScriptText === null) {
			$this->noScriptText = \Yii::t('wheels', "Please enable JavaScript to use file uploader.");
		}
		$this->clientOptions = ArrayHelper::merge(array(
			'request' => array(
				'endpoint' => \CHtml::normalizeUrl($this->route),
				'inputName' => ArrayHelper::getValue($this->options, 'name'),
			),
			'validation' => $this->getValidator(),
			'messages' => array(
				'typeError' => \Yii::t('wheels', '{file} has an invalid extension. Valid extension(s): {extensions}.'),
				'sizeError' => \Yii::t('wheels', '{file} is too large, maximum file size is {sizeLimit}.'),
				'minSizeError' => \Yii::t('wheels', '{file} is too small, minimum file size is {minSizeLimit}.'),
				'emptyError:' => \Yii::t('wheels', '{file} is empty, please select files again without it.'),
				'noFilesError' => \Yii::t('wheels', 'No files to upload.'),
				'onLeave' => \Yii::t(
					'zii',
					'The files are being uploaded, if you leave now the upload will be cancelled.'
				)
			),
		), $this->clientOptions);
	}

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		$this->renderTag();
		$this->registerClientScript();
	}

	/**
	 * Renders the tag where the button is going to be rendered
	 */
	public function renderTag()
	{
		echo \CHtml::tag($this->tag, $this->options, '<noscript>' . $this->noScriptText . '</noscript>', true);
	}

	/**
	 * Registers client script
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$assetsUrl = $this->getAssetsUrl('wheels.widgets.assets.fineupload');

		/* @var $cs \CClientScript */
		$cs = \Yii::app()->getClientScript();

		$script = YII_DEBUG ? 'jquery.fineuploader-3.2.js' : 'jquery.fineuploader-3.2.min.js';
		$cs->registerCssFile($assetsUrl . '/css/fineuploader.css');
		$cs->registerScriptFile($assetsUrl . '/js/' . $script);

		$this->registerPlugin('fineupload');
	}

	/**
	 * @return array
	 */
	protected function getValidator()
	{
		$ret = array();
		if ($this->hasModel()) {
			if ($this->scenario !== null) {
				$originalScenario = $this->model->getScenario();
				$this->model->setScenario($this->scenario);
				$validators = $this->model->getValidators($this->attribute);
				$this->model->setScenario($originalScenario);

			} else {
				$validators = $this->model->getValidators($this->attribute);
			}

			// we are just looking for the first founded \CFileValidator
			foreach ($validators as $validator) {
				if ($validator instanceof \CFileValidator) {
					$ret = array(
						'allowedExtensions' => explode(',', str_replace(' ', '', $validator->types)),
						'sizeLimit' => $validator->maxSize,
						'minSizeLimit' => $validator->minSize,
					);
					break;
				}
			}
		}
		return $ret;
	}

}