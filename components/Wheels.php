<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\components;

/**
 * wheels\components\Wheels is an application component to ease the task of registering bootstrap files.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\components
 * @since 1.0
 */
class Wheels extends CApplicationComponent
{
    /**
     * @var array $assetsJs of javascript library names to be registered when initializing the library.
     */
    public $assetsJs = array();
    /**
     * @var array $assetsCss of style library names to be registered when initializing the library.
     */
    public $assetsCss = array();
    /**
     * @var string holds the published assets
     */
    protected $_assetsUrl;


    /**
     * Widget's initialization
     * @throws \CException
     */
    public function init()
    {
        /* ensure all widgets - plugins are accessible to the library */
        \Yii::import('wheels.widgets.*');

        /* register css assets */
        foreach ($this->assetsCss as $css) {
            $this->registerAssetCss($css);
        }
        /* register js assets */
        foreach ($this->assetsJs as $js) {
            $this->registerAssetJs($js);
        }
    }

    /**
     * Returns the core library (yiistrap) component
     * @return TbApi
     */
    public function getApi()
    {
        return $this->_api;
    }

    /**
     * Returns the assets URL.
     * Assets folder has few orphan and very useful utility libraries.
     * @return string
     */
    public function getAssetsUrl()
    {
        if (isset($this->_assetsUrl)) {
            return $this->_assetsUrl;
        } else {
            $forceCopyAssets = $this->getApi()->forceCopyAssets;
            $path            = \Yii::getPathOfAlias('wheels');
            $assetsUrl       = \Yii::app()->assetManager->publish(
                $path . DIRECTORY_SEPARATOR . 'assets',
                false,
                -1,
                $forceCopyAssets
            );

            return $this->_assetsUrl = $assetsUrl;
        }
    }

    /**
     * Register a specific js file in the asset's js folder
     * @param string $jsFile
     * @param int $position the position of the JavaScript code.
     * @see CClientScript::registerScriptFile
     * @return $this
     */
    public function registerAssetJs($jsFile, $position = CClientScript::POS_END)
    {
        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl() . "/js/{$jsFile}", $position);
        return $this;
    }

    /**
     * Registers a specific css in the asset's css folder
     * @param string $cssFile the css file name to register
     * @param string $media the media that the CSS file should be applied to. If empty, it means all media types.
     * @return $this
     */
    public function registerAssetCss($cssFile, $media = '')
    {
        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl() . "/css/{$cssFile}", $media);
        return $this;
    }
}