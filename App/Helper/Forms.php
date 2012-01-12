<?php
namespace App\Helper;
use PPI\Core;

class Forms {

	protected $_baseUrl = '';
	protected $_config  = null;

	function __construct() {
		$this->_config = Core::getConfig();
	}

	/**
	 *
	 * Creates a form markup from an array
	 *
	 * @param $array The array
	 * @return string The entire markup for the form.
	 * @author Cybidea, Inc
	 * @author Alfredo Juarez
	 */

    public function createFromArray( $array ) {

		$form    = '';
		$baseUrl = $this->_config->system->base_url;

		foreach( $array as $key => $val ) {
			$model = $key;
		}

		foreach( $array as $row ) {

			$enctype    = isset($row['file'])   ? "enctype='multipart/form-data'" : '';
			$method     = isset($row['method']) ? $row['method'] : 'post';
			$legend     = isset($row['legend']) ? $row['legend'] : '';
			$class      = isset($row['class'])  ? $row['class']  : '';

			$form  = "<form id='{$model}' name='{$model}' action='".$baseUrl."".$row["action"]."' method='{$method}' {$enctype}>";
			$form .= $this->fieldset( array( 'class' => $class, 'legend' => $legend ));

			//fields

			foreach ( $row["fields"] as $field ) {
				if ( $field["type"] == "select") {
					$form .= $this->select( $field, $field["values"] );
				} else if ( $field["type"] == "textarea") {
					$form .= $this->textarea( $field );
				} else if ( $field["type"] == "button" ) {
					$form .= $this->button( $field );
				} else {
					$form .= $this->input( $field );
				}
			}

			if (isset($row['actions'])) {

				$form .= "<div class='actions'>";
				foreach ( $row["actions"] as $action ) {
					$redirect   = isset($action['redirect']) ? $action['redirect']  : false;
					$class      = isset($action['class'])    ? $action['class']     : 'btn';
					$type       = isset($action['type'])     ? $action['type']      : '';
					$label      = isset($action['label'])    ? $action['label']     : 'Submit';
					$id         = isset($action['id'])       ? $action['id']        : '';

					$redirect = $redirect ? "onclick=\"document.location.href='".$redirect."'\"" : '';

					$form .= "<button class='{$class}' id='{$id}' type='{$type}' {$redirect}>{$label}</button>";
				}
				$form .= "</div>";
			}

			// end fields

			$form .= $this->endTag( "fieldset" );
			$form .= $this->endTag( );
		}

		return $form;
	}


	/**
	 *
	 * Creates a form markup from a yaml file.
	 *
	 * @param $yaml The yaml file.
	 * @return string The entire markup for the form.
	 * @author Cybidea, Inc
	 * @author Alfredo Juarez
	 */
	public function createFromYaml( $yaml ) {
		$array  = \PPI\Crud::getFormStructure( $yaml );
		return $this->createFromArray( $array );
	}

	/**
	 * @param string $model The Name of the form.
	 * @param array $options The Options
	 * @return string the form tag.
	 * @author Cybidea, Inc
	 * @author Alfredo Juarez
	 */

	public function create( $model = 'form', $options = array( ) ) {

		$enctype    = isset($options['file'])   ? "enctype='multipart/form-data'"   : '';
		$method     = isset($options['method']) ? $options['method']                : 'post';
		$class      = isset($options['class'])  ? $options['class']                 : '';

		return "<form id='{$model}' name='{$model}' action='{$options["action"]}'
					  method='{$method}' {$enctype} class='{$class}'>";
	}

	/**
	 *
	 * Creates the fieldset code.
	 *
	 * @param array $options The Options
	 * @return string The fieldset
	 * @author Cybidea, Inc
	 * @author Alfredo Juarez
	 */
	public function fieldset( $options = array( ) ) {

		$legend     = isset($row['legend']) ? $row['legend'] : '';
		$class      = isset($row['class']) ? $row['class'] : '';

		return "<fieldset class='".$class."'>
				<legend>".$legend."</legend>";
	}

	/**
	 *
	 * Creates an html ending tag, like </form>
	 *
	 * @param string $tag The tag
	 * @return string end tag.
	 * @author Cybidea, Inc
	 * @author Alfredo Juarez
	 */

	public function endTag( $tag = 'form' ) {
		return "</".$tag.">";
	}

	/**
	 *
	 * Creates an input field.
	 *
	 * @param array $opt The Options
	 * @return string The input field.
	 * @author Cybidea, Inc
	 * @author Alfredo Juarez
	 */
	public function input( $opt = array() ) {

		$ro         = isset($opt['readonly'])   ? " onkeypress='return()' readonly='readonly'" : null;
		$maxlength  = isset($opt['maxlength'])  ? "maxlength='{$opt['legend']}'" : null;
		$name       = isset($opt['name'])       ? $opt['name']  : $opt['id'];
		$value      = isset($opt['value'])      ? $opt['value'] : '';
		$size       = isset($opt['size'])       ? $opt['size']  : '35';
		$label      = isset($opt['label'])      ? $opt['label'] : '';
		$type       = isset($opt['type'])       ? $opt['type']  : 'text';
		$class      = isset($row['class'])      ? $row['class'] : '';

		$input = '';

		if ( $type != "hidden" ) {
			$input .= "<div class='clearfix {$opt['id']}'>";
			if (strlen($label) > 1) {
				$input .= "<label>{$opt["label"]}</label>";
			}
			$input .="
					<div class='input'>
						<input type='{$type}' name='{$name}'
						size='{$size}' id='{$opt["id"]}' value='{$value}'
						class='{$class}' {$maxlength}
						{$ro}>
					</div>
				</div>";
		} else {
			$input .= "<input type='{$type}' name='{$name}'
						id='{$opt["id"]}' value='{$value}'>";
		}

		return $input;
	}

	/**
	 *
	 * Creates a textarea field.
	 *
	 * @param array $opt The Options
	 * @return string The textarea code.
	 * @author Cybidea, Inc
     * @author Alfredo Juarez
	 */
	public function textarea( $opt = array() ) {

		$name     = isset($opt['name'])  ? $opt['name']  : $opt['id'];
		$value    = isset($opt['value']) ? $opt['value'] : '';
		$cols     = isset($opt['cols'])  ? $opt['cols']  : '35';
		$rows     = isset($opt['rows'])  ? $opt['rows']  : '5';
		$class    = isset($opt['class']) ? $opt['class'] : '';

		return "
			<div class='clearfix {$opt["id"]}'>
				<label>{$opt["label"]}</label>
				<div class='input'>
					<textarea name='{$name}'
						cols='{$cols}' rows='{$rows}'
						id='{$opt["id"]}' class='{$class}'>{$value}</textarea>
				</div>
			</div>";
	}

	/**
	 *
	 *  Returns a Select Field based on the params sent.
	 *  Sample creation:
	 *          $forms->select(
	 *          array( "id" => "sample", "label" => "Label: ", "name" => "sample"),
	 *          array( "Label 1" => "Value 1", "Label 2" => "Value 2"));
	 *
	 * @param array $attributes
	 * @param array $options
	 * @author Cybidea, Inc
	 * @author Alfredo Juarez
	 *
	*/

	public function select( $attributes = array(), $options = array() ) {

		$name       = isset($attributes['name'])     ? $attributes['name'] : $attributes['id'];
		$size       = isset($attributes['size'])     ? $attributes['size'] : '';
		$readonly   = isset($attributes['readonly']) ? "onmousrover='this.disabled=true;' onmouseout='this.disabled=false;" : '';
		$selected   = isset($attributes['value'])    ? $attributes['value'] : '';

		$select = '';

		$select .= "
			<div class='clearfix {$attributes['id']}'>
				<label>{$attributes["label"]}</label>
				<div class='input'>
				<select id='{$attributes["id"]}' name='{$name}' {$readonly}>";

		foreach($options as $key => $value) {
			if ($selected == $value) {
				$select .= "<option value='{$value}' selected>{$key}</option>";
			} else {
				$select .= "<option value='{$value}'>{$key}</option>";
			}
		}

		$select .= "</select></div></div>";

		return $select;
	}

}