<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace wheels\bootstrap;

use wheels\helpers\ArrayHelper;

/**
 * ButtonGroup renders a button group bootstrap component.
 *
 * For example,
 *
 * ```php
 * // a button group with items configuration
 * $this->widget('wheels.bootstrap.ButtonGroup', array(
 *     'items' => array(
 *         array('label' => 'A'),
 *         array('label' => 'B'),
 *     )
 * ));
 *
 * // button group with an item as a string
 *  $this->widget('wheels.bootstrap.ButtonGroup', array(
 *     'items' => array(
 *         Button::widget(array('label' => 'A')),
 *         array('label' => 'B'),
 *     )
 * ));
 * ```
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\bootstrap
 * @since 1.0
 */
class ButtonGroup extends Widget
{
	/**
	 * @var array list of buttons. Each array element represents a single button
	 * which can be specified as a string or an array of the following structure:
	 *
	 * - label: string, required, the button label.
	 * - options: array, optional, the HTML attributes of the button.
	 */
	public $buttons = array();
	/**
	 * @var boolean whether to HTML-encode the button labels.
	 */
	public $encodeLabels = true;


	/**
	 * Initializes the widget.
	 * If you override this method, make sure you call the parent implementation first.
	 */
	public function init()
	{
		parent::init();
		$this->clientOptions = false;
		$this->addCssClass($this->options, 'btn-group');
	}

	/**
	 * Renders the widget.
	 */
	public function run()
	{
		echo \CHtml::tag('div', $this->options, $this->renderButtons());
		$this->registerPlugin('button');
	}

	/**
	 * Generates the buttons that compound the group as specified on [[items]].
	 * @return string the rendering result.
	 */
	protected function renderButtons()
	{
		$buttons = array();
		foreach ($this->buttons as $button) {
			if (is_array($button)) {
				$label = ArrayHelper::getValue($button, 'label');
				$options = ArrayHelper::getValue($button, 'options');
				$buttons[] = Button::widget(array(
						'label' => $label,
						'options' => $options,
						'encodeLabel' => $this->encodeLabels,
					)
				);
			} else {
				$buttons[] = $button;
			}
		}
		return implode("\n", $buttons);
	}
}
