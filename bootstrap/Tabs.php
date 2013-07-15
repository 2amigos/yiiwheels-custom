<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace wheels\bootstrap;

use wheels\helpers\ArrayHelper;

/**
 * Tabs renders a Tab bootstrap javascript component.
 *
 * For example:
 *
 * ```php
 * echo Tabs::widget(array(
 *     'items' => array(
 *         array(
 *             'label' => 'One',
 *             'content' => 'Anim pariatur cliche...',
 *             'active' => true
 *         ),
 *         array(
 *             'label' => 'Two',
 *             'content' => 'Anim pariatur cliche...',
 *             'headerOptions' => array(...),
 *             'options' => array('id'=>'myveryownID'),
 *         ),
 *         array(
 *             'label' => 'Dropdown',
 *             'items' => array(
 *                  array(
 *                      'label' => 'DropdownA',
 *                      'content' => 'DropdownA, Anim pariatur cliche...',
 *                  ),
 *                  array(
 *                      'label' => 'DropdownB',
 *                      'content' => 'DropdownB, Anim pariatur cliche...',
 *                  ),
 *             ),
 *         ),
 *     ),
 * ));
 * ```
 *
 * @see http://twitter.github.io/bootstrap/javascript.html#tabs
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\bootstrap
 * @since 1.0
 */
class Tabs extends Widget
{
	/**
	 * @var array list of tabs in the tabs widget. Each array element represents a single
	 * tab with the following structure:
	 *
	 * - label: string, required, the tab header label.
	 * - headerOptions: array, optional, the HTML attributes of the tab header.
	 * - content: array, required if `items` is not set. The content (HTML) of the tab pane.
	 * - options: array, optional, the HTML attributes of the tab pane container.
	 * - active: boolean, optional, whether the item tab header and pane should be visible or not.
	 * - items: array, optional, if not set then `content` will be required. The `items` specify a dropdown items
	 *   configuration array. Each item can hold two extra keys, besides the above ones:
	 *     * active: boolean, optional, whether the item tab header and pane should be visible or not.
	 *     * content: string, required if `items` is not set. The content (HTML) of the tab pane.
	 *     * contentOptions: optional, array, the HTML attributes of the tab content container.
	 */
	public $items = array();
	/**
	 * @var array list of HTML attributes for the item container tags. This will be overwritten
	 * by the "options" set in individual [[items]]. The following special options are recognized:
	 *
	 * - tag: string, defaults to "div", the tag name of the item container tags.
	 */
	public $itemOptions = array();
	/**
	 * @var array list of HTML attributes for the header container tags. This will be overwritten
	 * by the "headerOptions" set in individual [[items]].
	 */
	public $headerOptions = array();
	/**
	 * @var boolean whether the labels for header items should be HTML-encoded.
	 */
	public $encodeLabels = true;


	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		parent::init();
		$this->addCssClass($this->options, 'nav nav-tabs');
	}

	/**
	 * Renders the widget.
	 */
	public function run()
	{
		echo $this->renderItems();
		$this->registerPlugin('tab');
	}

	/**
	 * Renders tab items as specified on [[items]].
	 * @return string the rendering result.
	 * @throws \CException.
	 */
	protected function renderItems()
	{
		$headers = array();
		$panes = array();
		foreach ($this->items as $n => $item) {
			if (!isset($item['label'])) {
				throw new \CException("The 'label' option is required.");
			}
			$label = $this->encodeLabels ? \CHtml::encode($item['label']) : $item['label'];
			$headerOptions = array_merge($this->headerOptions, ArrayHelper::getValue($item, 'headerOptions', array()));

			if (isset($item['items'])) {
				$label .= ' <b class="caret"></b>';
				$this->addCssClass($headerOptions, 'dropdown');

				if ($this->renderDropdown($item['items'], $panes)) {
					$this->addCssClass($headerOptions, 'active');
				}

				$header = \CHtml::link($label, "#", array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown')) . "\n"
					. Dropdown::widget(array('items' => $item['items'], 'clientOptions' => false));
			} elseif (isset($item['content'])) {
				$options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', array()));
				$options['id'] = ArrayHelper::getValue($options, 'id', $this->options['id'] . '-tab' . $n);

				$this->addCssClass($options, 'tab-pane');
				if (ArrayHelper::remove($item, 'active')) {
					$this->addCssClass($options, 'active');
					$this->addCssClass($headerOptions, 'active');
				}
				$header = \CHtml::link($label, '#' . $options['id'], array('data-toggle' => 'tab', 'tabindex' => '-1'));
				$panes[] = \CHtml::tag('div', $options, $item['content']);
			} else {
				throw new \CException("Either the 'content' or 'items' option must be set.");
			}

			$headers[] = \CHtml::tag('li', $headerOptions, $header);
		}

		return \CHtml::tag('ul', $this->options, implode("\n", $headers)) . "\n"
			. \CHtml::tag('div', array('class' => 'tab-content'), implode("\n", $panes));
	}

	/**
	 * Normalizes dropdown item options by removing tab specific keys `content` and `contentOptions`, and also
	 * configure `panes` accordingly.
	 * @param array $items the dropdown items configuration.
	 * @param array $panes the panes reference array.
	 * @return boolean whether any of the dropdown items is `active` or not.
	 * @throws \CException
	 */
	protected function renderDropdown(&$items, &$panes)
	{
		$itemActive = false;

		foreach ($items as $n => &$item) {
			if (is_string($item)) {
				continue;
			}
			if (!isset($item['content'])) {
				throw new \CException("The 'content' option is required.");
			}

			$content = ArrayHelper::remove($item, 'content');
			$options = ArrayHelper::remove($item, 'contentOptions', array());
			$this->addCssClass($options, 'tab-pane');
			if (ArrayHelper::remove($item, 'active')) {
				$this->addCssClass($options, 'active');
				$this->addCssClass($item['options'], 'active');
				$itemActive = true;
			}

			$options['id'] = ArrayHelper::getValue($options, 'id', $this->options['id'] . '-dd-tab' . $n);
			$item['url'] = '#' . $options['id'];
			$item['linkOptions']['data-toggle'] = 'tab';

			$panes[] = \CHtml::tag('div', $options, $content);

			unset($item);
		}
		return $itemActive;
	}
}
