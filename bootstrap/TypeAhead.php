<?php
/**
 * @link http://2amigos.us/
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group  LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace wheels\bootstrap;

/**
 * TypeAhead renders a typehead bootstrap javascript component.
 *
 * For example,
 *
 * ```php
 * echo TypeAhead::widget(array(
 *     'model' => $model,
 *     'attribute' => 'country',
 *     'clientOptions' => array(
 *         'source' => array('USA', 'ESP'),
 *     ),
 * ));
 * ```
 *
 * The following example will use the name property instead
 *
 * ```php
 * echo TypeAhead::widget(array(
 *     'name'  => 'country',
 *     'clientOptions' => array(
 *         'source' => array('USA', 'ESP'),
 *     ),
 * ));
 *```
 *
 * @see http://twitter.github.io/bootstrap/javascript.html#tabs
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package wheels\bootstrap
 * @since 1.0
 */
class TypeAhead extends Widget
{
	/**
	 * @var \CModel the data model that this widget is associated with
	 */
	public $model;
	/**
	 * @var string the model attribute that this widget is associated with
	 */
	public $attribute;
	/**
	 * @var string the input name. This must be set if [[model]] and [[attribute]] are not set.
	 */
	public $name;
	/**
	 * @var string the input value.
	 */
	public $value;


	/**
	 * Renders the widget
	 */
	public function run()
	{
		echo $this->renderField();
		$this->registerPlugin('typeahead');
	}

	/**
	 * Renders the TypeAhead field. If [[model]] has been specified then it will render an active field.
	 * If [[model]] is null or not from an [[Model]] instance, then the field will be rendered according to
	 * the [[name]] attribute.
	 * @return string the rendering result
	 * @throws \CException when none of the required attributes are set to render the textInput.
	 * That is, if [[model]] and [[attribute]] are not set, then [[name]] is required.
	 */
	public function renderField()
	{
		if ($this->model instanceof \CModel && $this->attribute !== null) {
			return \CHtml::activeTextField($this->model, $this->attribute, $this->options);
		} elseif ($this->name !== null) {
			return \CHtml::textField($this->name, $this->value, $this->options);
		} else {
			throw new \CException("Either 'name' or 'model' and 'attribute' properties must be specified.");
		}
	}
}
