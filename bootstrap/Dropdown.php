<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace wheels\bootstrap;

use wheels\helpers\ArrayHelper;

/**
 * Dropdown renders a Bootstrap dropdown menu component.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\bootstrap
 * @since 1.0
 */
class Dropdown extends Widget
{
	/**
	 * @var array list of menu items in the dropdown. Each array element represents a single
	 * menu with the following structure:
	 * - label: string, required, the label of the item link
	 * - url: string, optional, the url of the item link. Defaults to "#".
	 * - linkOptions: array, optional, the HTML attributes of the item link.
	 * - options: array, optional, the HTML attributes of the item.
	 * - items: array, optional, the dropdown items configuration array. if `items` is set, then `url` of the parent
	 *   item will be ignored and automatically set to "#"
	 *
	 * @see https://github.com/twitter/bootstrap/issues/5050#issuecomment-11741727
	 */
	public $items = array();
	/**
	 * @var boolean whether the labels for header items should be HTML-encoded.
	 */
	public $encodeLabels = true;


	/**
	 * Initializes the widget.
	 * If you override this method, make sure you call the parent implementation first.
	 */
	public function init()
	{
		parent::init();
		$this->addCssClass($this->options, 'dropdown-menu');
	}

	/**
	 * Renders the widget.
	 */
	public function run()
	{
		echo $this->renderItems($this->items);
		$this->registerPlugin('dropdown');
	}

	/**
	 * Renders menu items.
	 * @param array $items the menu items to be rendered
	 * @return string the rendering result.
	 * @throws \CException if the label option is not specified in one of the items.
	 */
	protected function renderItems($items)
	{
		$lines = array();
		foreach ($items as $item) {
			if (is_string($item)) {
				$lines[] = $item;
				continue;
			}
			if (!isset($item['label'])) {
				throw new \CException("The 'label' option is required.");
			}
			$label = $this->encodeLabels ? \CHtml::encode($item['label']) : $item['label'];
			$options = ArrayHelper::getValue($item, 'options', array());
			$linkOptions = ArrayHelper::getValue($item, 'linkOptions', array());
			$linkOptions['tabindex'] = '-1';

			if (isset($item['items'])) {
				$this->addCssClass($options, 'dropdown-submenu');
				$content = \CHtml::link($label, '#', $linkOptions) . $this->renderItems($item['items']);
			} else {
				$content = \CHtml::link($label, ArrayHelper::getValue($item, 'url', '#'), $linkOptions);
			}
			$lines[] = \CHtml::tag('li', $options, $content);
		}

		return \CHtml::tag('ul', $this->options, implode("\n", $lines));
	}
}
