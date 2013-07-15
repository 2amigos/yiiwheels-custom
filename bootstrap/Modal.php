<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace wheels\bootstrap;

use wheels\helpers\ArrayHelper;

/**
 * Modal renders a modal window that can be toggled by clicking on a button.
 *
 * The following example will show the content enclosed between the [[beginWidget()]]
 * and [[endWidget()]] calls within the modal window:
 *
 * ~~~php
 * $m = $this->beginWidget('wheels.bootstrap.Modal',array(
 *     'header' => '<h2>Hello world</h2>',
 *     'toggleButton' => array(
 *         'label' => 'click me',
 *     ),
 * ));
 *
 * echo 'Say hello...';
 *
 * $this->endWidget();
 * ~~~
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\bootstrap
 * @since 1.0
 */
class Modal extends Widget
{
	/**
	 * @var string the header content in the modal window.
	 */
	public $header;
	/**
	 * @var string the footer content in the modal window.
	 */
	public $footer;
	/**
	 * @var array the options for rendering the close button tag.
	 * The close button is displayed in the header of the modal window. Clicking
	 * on the button will hide the modal window. If this is null, no close button will be rendered.
	 *
	 * The following special options are supported:
	 *
	 * - tag: string, the tag name of the button. Defaults to 'button'.
	 * - label: string, the label of the button. Defaults to '&times;'.
	 *
	 * The rest of the options will be rendered as the HTML attributes of the button tag.
	 * Please refer to the [Modal plugin help](http://twitter.github.com/bootstrap/javascript.html#modals)
	 * for the supported HTML attributes.
	 */
	public $closeButton = array();
	/**
	 * @var array the options for rendering the toggle button tag.
	 * The toggle button is used to toggle the visibility of the modal window.
	 * If this property is null, no toggle button will be rendered.
	 *
	 * The following special options are supported:
	 *
	 * - tag: string, the tag name of the button. Defaults to 'button'.
	 * - label: string, the label of the button. Defaults to 'Show'.
	 *
	 * The rest of the options will be rendered as the HTML attributes of the button tag.
	 * Please refer to the [Modal plugin help](http://twitter.github.com/bootstrap/javascript.html#modals)
	 * for the supported HTML attributes.
	 */
	public $toggleButton;


	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		parent::init();

		$this->initOptions();

		echo $this->renderToggleButton() . "\n";
		echo \CHtml::openTag('div', $this->options) . "\n";
		echo $this->renderHeader() . "\n";
		echo $this->renderBodyBegin() . "\n";
	}

	/**
	 * Renders the widget.
	 */
	public function run()
	{
		echo "\n" . $this->renderBodyEnd();
		echo "\n" . $this->renderFooter();
		echo "\n" . \CHtml::closeTag('div');

		$this->registerPlugin('modal');
	}

	/**
	 * Renders the header HTML markup of the modal
	 * @return string the rendering result
	 */
	protected function renderHeader()
	{
		$button = $this->renderCloseButton();
		if ($button !== null) {
			$this->header = $button . "\n" . $this->header;
		}
		if ($this->header !== null) {
			return \CHtml::tag('div', array('class' => 'modal-header'), "\n" . $this->header . "\n");
		} else {
			return null;
		}
	}

	/**
	 * Renders the opening tag of the modal body.
	 * @return string the rendering result
	 */
	protected function renderBodyBegin()
	{
		return \CHtml::openTag('div', array('class' => 'modal-body'));
	}

	/**
	 * Renders the closing tag of the modal body.
	 * @return string the rendering result
	 */
	protected function renderBodyEnd()
	{
		return \CHtml::closeTag('div');
	}

	/**
	 * Renders the HTML markup for the footer of the modal
	 * @return string the rendering result
	 */
	protected function renderFooter()
	{
		if ($this->footer !== null) {
			return \CHtml::tag('div', array('class' => 'modal-footer'), "\n" . $this->footer . "\n");
		} else {
			return null;
		}
	}

	/**
	 * Renders the toggle button.
	 * @return string the rendering result
	 */
	protected function renderToggleButton()
	{
		if ($this->toggleButton !== null) {
			$tag = ArrayHelper::remove($this->toggleButton, 'tag', 'button');
			$label = ArrayHelper::remove($this->toggleButton, 'label', 'Show');
			if ($tag === 'button' && !isset($this->toggleButton['type'])) {
				$this->toggleButton['type'] = 'button';
			}
			return \CHtml::tag($tag, $this->toggleButton, $label);
		} else {
			return null;
		}
	}

	/**
	 * Renders the close button.
	 * @return string the rendering result
	 */
	protected function renderCloseButton()
	{
		if ($this->closeButton !== null) {
			$tag = ArrayHelper::remove($this->closeButton, 'tag', 'button');
			$label = ArrayHelper::remove($this->closeButton, 'label', '&times;');
			if ($tag === 'button' && !isset($this->closeButton['type'])) {
				$this->closeButton['type'] = 'button';
			}
			return \CHtml::tag($tag, $this->closeButton, $label);
		} else {
			return null;
		}
	}

	/**
	 * Initializes the widget options.
	 * This method sets the default values for various options.
	 */
	protected function initOptions()
	{
		$this->options = array_merge(array(
			'class' => 'modal hide',
		), $this->options);
		$this->addCssClass($this->options, 'modal');

		$this->clientOptions = array_merge(array(
			'show' => false,
		), $this->clientOptions);

		if ($this->closeButton !== null) {
			$this->closeButton = array_merge(array(
				'data-dismiss' => 'modal',
				'aria-hidden' => 'true',
				'class' => 'close',
			), $this->closeButton);
		}

		if ($this->toggleButton !== null) {
			$this->toggleButton = array_merge(array(
				'data-toggle' => 'modal',
			), $this->toggleButton);
			if (!isset($this->toggleButton['data-target']) && !isset($this->toggleButton['href'])) {
				$this->toggleButton['data-target'] = '#' . $this->options['id'];
			}
		}
	}
}
