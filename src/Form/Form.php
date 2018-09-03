<?php

namespace Osprey\Form;

use Osprey\Form\Field\FieldInterface;
use Osprey\Form\Style\StyleInterface;
use Osprey\Registry;
use Osprey\Services;

/**
 * Class Form
 *
 * @package Osprey
 */
class Form {
    
	/**
	 * Necessary argument defaults for a working form
	 *
	 * @var array
	 */
	public $default_form_args = [
		'id' => '',
		'classes' => [],
		'method' => 'POST',
		'action' => '',
		'attributes' => [],
		'style' => 'default',
		'field_prefix' => '',
        'fields' => [],
	];

	/**
	 * Form settings (arguments). Combination of arguments provided to the
	 * constructor and the default arguments
	 *
	 * @var array
	 */
	public $form_args = [];

	/**
	 * Form Styles registry.
	 *
	 * @var Registry
	 */
	public $styles;

	/**
     * The form's current style.
     *
	 * @var StyleInterface
	 */
    public $style;

	/**
	 * Field Types
	 *
	 * @var Registry
	 */
	public $field_types;

	/**
	 *  Necessary argument defaults for a working field
	 *
	 * @var array
	 */
	public $default_field_args = [
		'title' => '',
		'description' => '',
		'help' => '',
		'type' => 'text',
		'classes' => [],
		'value' => '',
		'name' => '',
		'label_first' => TRUE,
		'access' => TRUE,

		// [top-lvl][mid-lvl][bottom-lvl]
		'name_prefix' => '',

		// additional special attributes like size, rows, cols, etc
		'attributes' => [],

		// only for some field types
		// options = array(),

		# generated automatically
		#'form_name' => '',
		#'id' => '',
	];

	/**
     * Array of field definitions.
     *
	 * @var array
	 */
	public $fields = [];

	/**
	 * OspreyForm constructor.
	 *
	 * @param array $form_args
	 */
	function __construct( $form_args = [] ) {
		$this->form_args = array_replace( $this->default_form_args, $form_args );
		$this->fields = $this->form_args['fields'];
		$this->styles = Services::formStyles();
		$this->style = $this->getFormStyle();
		$this->field_types = Services::formFields();
	}

	/**
	 * Simple conversion of an array to tml attributes string
	 *
	 * @param array $array
	 * @param string $prefix
	 *
	 * @return string
	 */
	static public function attributes( $array = [], $prefix = '' ) {
		$html = '';

		foreach( $array as $key => $value ){
			if ( !empty( $value ) ) {
				$value = esc_attr( $value );
				$html .= " {$prefix}{$key}='{$value}'";
			}
		}

		return $html;
	}

	/**
	 * Retrieve the current form_style array
	 *
	 * @return \Osprey\Form\Style\StyleInterface
	 */
	function getFormStyle() {
	    $styles = $this->styles->items();
	    $style = $styles['default'];
	    $form_style = $this->form_args['style'];

	    if ( isset( $styles[ $form_style ] ) ) {
	        $style = $styles[ $form_style ];

	        if ( class_exists( $style['class'] ) ) {
	            $instance = new $style['class']();

	            if ( is_a($instance, '\Osprey\Form\Style\StyleInterface') ) {
	                $style_instance = $instance;
                }
            }
        }

        if ( empty( $style_instance ) ) {
		    $style_instance = new $style['class']();
        }

		return $style_instance;
	}

	/**
	 * Merge default and set attributes for the html form element
	 *
	 * @return array
	 */
	function getFormAttributes() {
		$atts_keys = array( 'id', 'action', 'method', 'class' );
		$attributes = array();

		foreach( $atts_keys as $key ){
			if ( !empty( $this->form_args[ $key ] ) ) {
				$attributes[ $key ] = $this->form_args[ $key ];
			}
		}

		if ( !empty( $this->form_args['attributes'] ) ) {
			$attributes = array_replace( $attributes, $this->form_args['attributes'] );
		}

		if ( !empty( $attributes['classes'] ) ) {
			$attributes['class'] = implode( ' ', $attributes['classes'] );
		}

		return $attributes;
	}

	/**
     * Wrap the form fields in a form HTML element.
     *
	 * @param $form
	 *
	 * @return string
	 */
    function wrapFormFields( $form ) {
	    return '<form ' . self::attributes( $this->getFormAttributes() ) . '>' . $form . '</form>';
    }

	/**
     * Render an entire form that is instantiated with fields.
     *
	 * @return string
	 */
	function render() {
        $form = '';

		foreach( $this->fields as $name => $field ) {
			if ( empty($field['name']) ) {
				$field['name'] = $name;
			}
			$form.= $this->renderField($field);
		}

		$form = $this->wrapFormFields($form);

	    return $this->style->wrapForm($form);
    }

	/**
	 * Execute the filters and methods that render a field
	 *
	 * @param $field
	 *
	 * @return string
	 */
	function renderField( $field ){
		$field = $this->fieldData( $field );
		$types = $this->field_types;
		$field_html = '';

		// do not render fields users do not have access to.
		if ( !$field['access'] ) {
		    return $field_html;
        }

		// template the field
		if ( $types->get( $field['type'] ) && class_exists( $types->get( $field['type'] )['class'] ) ){
		    $type_class = $types->get( $field['type'] )['class'];
		    /** @var FieldInterface $type */
		    $type = new $type_class();

		    if ( is_a( $type, '\Osprey\Form\Field\FieldInterface' ) ) {
			    $field_html = $type->render( $field );
		    }
		}

		// do not wrap very simple fields
		if ( empty( $field['title'] ) && empty( $field['description'] ) && empty( $field['help'] ) ) {
			return $field_html;
		}

		// template the wrapper
		$wrapper_html = $this->style->wrapField( $field, $field_html );;

		return $wrapper_html;
	}

	/**
	 * Preprocess field
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	function fieldData( $args = array() ){
		$field = array_replace( $this->default_field_args, $args );
		$field['name'] = sanitize_title( $args['name'] );

		if ($field['type'] == 'checkbox') {
			$field['label_first'] = FALSE;
        }

		// build the field's entire form name
		$field['form_name'] = '';
		if ( !empty( $this->form_args['field_prefix'] ) ){
			$field['form_name'].= $this->form_args['field_prefix'];
		}
		if ( !empty( $field['name_prefix'] ) ) {
			$field['form_name'].= $field['name_prefix'];
		}

		if ( !empty( $field['form_name'] ) ) {
			$field['form_name'].= '[' . $field['name'] . ']';
		}
		else {
			$field['form_name'].= $field['name'];
        }

		// gather field classes
		if ( !is_array( $field['classes'] ) ){
			$field['classes'] = [ $field['classes'] ];
		}
		$field['classes'][] = 'field';
		$field['classes'][] = 'field-' . $this->form_args['style'];
		$field['classes'][] = 'field-type-' . $field['type'];
		$field['classes'] = implode( ' ', $field['classes'] );

		if ( empty( $field['id'] ) ) {
			$field['id'] = 'edit--' . sanitize_title( $field['form_name'] );
		}

		return $field;
	}


	/**
     * Get the value of an item in the $_REQUEST array.
     *
	 * @param $field_name
     *
     * @return mixed|NULL
	 */
	function submittedValue( $field_name ) {
	    if ( $_REQUEST[$this->form_args['field_prefix']] ) {
	        $keys = explode('][', $field_name);
	        array_unshift($keys, $this->form_args['field_prefix']);

	        return $this->arrayQuery($keys, $_REQUEST);
        }

        return null;
    }

	/**
	 * Using an array of keys as a path, find a value in a multi-dimensional array
	 *
	 * @param $keys
	 * @param $data
	 *
	 * @return mixed|null
	 */
	function arrayQuery( $keys, $data ){
		if ( empty( $keys ) ){
			return $data;
		}

		$key = array_shift( $keys );

		if ( isset( $data[ $key ] ) ) {

			// if this was the last key, we have found the value
			if ( empty( $keys ) ){
				return $data[ $key ];
			}
			// if there are remaining keys and this key leads to an array,
			// recurse using the remaining keys
			else if ( is_array( $data[ $key ] ) ) {
				return $this->arrayQuery( $keys, $data[ $key ] );
			}
			// there are remaining keys, but this item is not an array
			else {
				return null;
			}
		}

		return null;
	}

}
