<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace wheels\bootstrap;

use wheels\helpers\ArrayHelper;

/**
 * Collapse renders an accordion bootstrap javascript component.
 *
 * For example:
 *
 * ```php
 * echo Collapse::widget(array(
 *     'items' => array(
 *         // equivalent to the above
 *         'Collapsible Group Item #1' => array(
 *             'content' => 'Anim pariatur cliche...',
 *             // open its content by default
 *             'contentOptions' => array('class'=>'in')
 *         ),
 *         // another group item
 *         'Collapsible Group Item #2' => array(
 *             'content' => 'Anim pariatur cliche...',
 *             'contentOptions' => array(...),
 *             'options' => array(...),
 *         ),
 *     )
 * ));
 * ```
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\bootstrap
 * @since 1.0
 */
class Collapse extends Widget
{
	/**
	 * @var array list of groups in the collapse widget. Each array element represents a single
	 * group with the following structure:
	 *
	 * ```php
	 * // item key is the actual group header
	 * 'Collapsible Group Item #1' => array(
	 *     // required, the content (HTML) of the group
	 *     'content' => 'Anim pariatur cliche...',
	 *     // optional the HTML attributes of the content group
	 *     'contentOptions'=> array(),
	 *     // optional the HTML attributes of the group
	 *     'options'=> array(),
	 * )
	 * ```
	 */
	public $items = array();


	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		parent::init();
		$this->addCssClass($this->options, 'accordion');
	}

	/**
	 * Renders the widget.
	 */
	public function run()
	{
		echo \CHtml::openTag('div', $this->options) . "\n";
		echo $this->renderItems() . "\n";
		echo \CHtml::closeTag('div') . "\n";
		$this->registerPlugin('collapse');
	}

	/**
	 * Renders collapsible items as specified on [[items]].
	 * @return string the rendering result
	 */
	public function renderItems()
	{
		$items = array();
		$index = 0;
		foreach ($this->items as $header => $item) {
			$options = ArrayHelper::getValue($item, 'options', array());
			$this->addCssClass($options, 'accordion-group');
			$items[] = \CHtml::tag('div', $options, $this->renderItem($header, $item, ++$index));
		}

		return implode("\n", $items);
	}

	/**
	 * Renders a single collapsible item group
	 * @param string $header a label of the item group [[items]]
	 * @param array $item a single item from [[items]]
	 * @param integer $index the item index as each item group content must have an id
	 * @return string the rendering result
	 * @throws \CException
	 */
	public function renderItem($header, $item, $index)
	{
		if (isset($item['content'])) {
			$id = $this->options['id'] . '-collapse' . $index;
			$options = ArrayHelper::getValue($item, 'contentOptions', array());
			$options['id'] = $id;
			$this->addCssClass($options, 'accordion-body collapse');

			$header = \CHtml::link($header, '#' . $id, array(
					'class' => 'accordion-toggle',
					'data-toggle' => 'collapse',
					'data-parent' => '#' . $this->options['id']
				)) . "\n";

			$content = \CHtml::tag('div', $item['content'], array('class' => 'accordion-inner')) . "\n";
		} else {
			throw new \CException('The "content" option is required.');
		}
		$group = array();

		$group[] = \CHtml::tag('div', array('class' => 'accordion-heading'), $header);
		$group[] = \CHtml::tag('div', $options, $content);

		return implode("\n", $group);
	}
}
