<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace wheels\widgets;

use wheels\helpers\ArrayHelper;

\Yii::import('zii.widgets.CDetailView');

/**
 * wheels\widgets\DetailView
 * @ssee https://github.com/ajaxorg/ace
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\widgets
 * @since 1.0
 */
class DetailView extends \CDetailView
{
	/**
	 * @var string|array the table type.
	 * Valid values are `table-striped`, `table-bordered`, `table-condensed`, 'table-hover
	 */
	public $type = array('striped', 'condensed');

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		parent::init();

		$classes = array('table');

		if (isset($this->type) && !empty($this->type)) {
			if (is_string($this->type)) {
				$this->type = explode(' ', $this->type);
			}
		}
		$this->addCssClass(implode(' ', $classes));
	}

	/**
	 * Adds a CSS class to the specified options.
	 * This method will ensure that the CSS class is unique and the "class" option is properly formatted.
	 * @param string $class the CSS class to be added
	 */
	protected function addCssClass($class)
	{
		if (isset($this->options['class'])) {
			$classes = preg_split('/\s+/', $this->htmlOptions['class'] . ' ' . $class, -1, PREG_SPLIT_NO_EMPTY);
			$this->htmlOptions['class'] = implode(' ', array_unique($classes));
		} else {
			$this->htmlOptions['class'] = $class;
		}
	}
}