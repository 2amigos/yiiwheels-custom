<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace wheels\bootstrap;

/**
 * wheels\bootstrap\Button renders a bootstrap button.
 *
 * For example,
 *
 * ```php
 * $this->widget('wheels.bootstrap.Button', array(
 *     'label' => 'Action',
 *     'options' => array('class' => 'btn-large'),
 * ));
 * ```
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\bootstrap
 * @since 1.0
 */
class Button extends Widget
{
	/**
	 * @var string the tag to use to render the button
	 */
	public $tagName = 'button';
	/**
	 * @var string the button label
	 */
	public $label = 'Button';
	/**
	 * @var boolean whether the label should be HTML-encoded.
	 */
	public $encodeLabel = true;


	/**
	 * Initializes the widget.
	 * If you override this method, make sure you call the parent implementation first.
	 */
	public function init()
	{
		parent::init();
		$this->clientOptions = false;
		$this->addCssClass($this->options, 'btn');
	}

	/**
	 * Renders the widget.
	 */
	public function run()
	{
		echo \CHtml::tag($this->tagName, $this->encodeLabel ? \CHtml::encode($this->label) : $this->label, $this->options);
		$this->registerPlugin('button');
	}
}
