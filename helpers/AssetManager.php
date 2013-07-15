<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\helpers;

/**
 * wheels\widgets\AssetManager eases the registration of utility js and css assets
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\helpers
 * @since 1.0
 */
class AssetManager
{
	private static $_assetsUrl;

	/**
	 * Gets the assets url
	 * @return string
	 */
	public static function assetsUrl()
	{
		if (!isset(static::$_assetsUrl)) {
			if (\Yii::getPathOfAlias('wheels') === false) {
				\Yii::setPathOfAlias('wheels', realpath(dirname(__FILE__) . '/../'));
			}
			static::$_assetsUrl = \Yii::app()->assetManager->publish('wheels.assets', true, -1, YII_DEBUG);
		}
		return static::$_assetsUrl;
	}

	/**
	 * Registers a script js file from the main assets folder at wheels
	 * @param string $file the file to register
	 * @param int $position the position where to render the script in the document
	 */
	public static function registerScriptFile($file, $position = \CClientScript::POS_END)
	{
		/* @var  \CClientScript $cs */
		$cs = \Yii::app()->clientScript;
		$url = static::assetsUrl() . '/js/' . $file;
		$cs->registerScriptFile($url, $position);
	}

	/**
	 * Registers a css file from the main assets folder at wheels
	 * @param string $file the file to register
	 * @param string $media
	 */
	public static function registerCssFile($file, $media = '')
	{
		/* @var  \CClientScript $cs */
		$cs = \Yii::app()->clientScript;
		$url = static::assetsUrl() . '/css/' . $file;
		$cs->registerCssFile($url, $media);
	}
}