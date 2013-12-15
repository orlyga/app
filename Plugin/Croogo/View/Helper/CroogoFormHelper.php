<?php

App::uses('FormHelper', 'View/Helper');

/**
 * Croogo Form Helper
 *
 * @package Croogo.Croogo.View.Helper
 */
class CroogoFormHelper extends FormHelper {

	public $helpers = array(
		'Html' => array('className' => 'Croogo.CroogoHtml')
	);
	//orly added
	public $_inputDefaults = array('placeholder'=>true);
	protected function _bootstrapGenerate($title, $options) {
		if (isset($options['button'])) {
			$options['class'] .= $options['button'] != 'default' ? ' btn-' . $options['button'] : '';
			unset($options['button']);
		}
		if (isset($options['icon'])) {
			$title = $this->Html->icon($options['icon']) . ' ' . $title;
			unset($options['icon']);
		}
		return array($title, $options);
	}

	protected function _helpText($options) {
		$helpClass = isset($options['helpInline']) ? 'help-inline' : 'help-block';
		$helpText = $this->Html->tag('span', $options['help'], array(
			'class' => $helpClass,
		));
		$options['after'] = isset($options['after']) ? $options['after'] . $helpText : $helpText;
		unset($options['help'], $options['helpInline']);
		return $options;
	}
	protected function _oninvalid($optons){
		if ($options['oninvalid'] === false) {
			return $options;
		}
		$oninvalid=array();
		if (is_string($options['oninvalid'])) {
			$options['oninvalid'] = array('data-title' => $options['oninvalid']);
		}
		if (isset($options['title']) && empty($options['oninvalid']['data-title'])) {
			$options['oninvalide'] = $options['title'];
		}
			
		return $options;
	
		
	}
	protected function _tooltip($options) {
		if ($options['tooltip'] === false) {
			unset($options['title']);
			return $options;
		}
		$tooltipOptions = array(
			'data-placement' => 'right',
			'data-trigger' => 'focus',
		);
		if (is_string($options['tooltip'])) {
			$options['tooltip'] = array('data-title' => $options['tooltip']);
		}
		if (isset($options['title']) && empty($options['tooltip']['data-title'])) {
			$tooltipOptions['data-title'] = $options['title'];
		}

		$tooltipOptions = Hash::merge($tooltipOptions, $options['tooltip']);
		unset($options['title']);
		unset($options['tooltip']);

		if (isset($options['type']) && in_array($options['type'], array('checkbox', 'radio', 'select'))) {
			if (isset($options['div'])) {
				if (is_string($options['div'])) {
					$options['div'] = array('div' => $options['div']) + $tooltipOptions;
				} else {
					$options['div'] += $tooltipOptions;
				}
			} else {
				$options['div'] = $tooltipOptions;
			}
		} else {
			$options += $tooltipOptions;
		}
		return $options;
	}

/**
 * placeholderOptions
 */
	protected function _placeholderOptions($fieldName, $options = array()) {
		$autoPlaceholder = empty($options['placeholder']) &&
			isset($this->_inputDefaults['placeholder']) &&
			$this->_inputDefaults['placeholder'] === true;
		$autoPlaceholder = $autoPlaceholder ||
			(isset($options['placeholder']) && $options['placeholder'] === true);
		if ($autoPlaceholder) {
			if (!empty($options['title'])) {
				$options['placeholder'] = $options['title'];
			} else {
				if (strpos($fieldName, '.') !== false) {
					//orly added
                    $x=explode('.', $fieldName);
                  

					$placeholder = end($x);
				} else {
					$placeholder = $fieldName;
				}
				$options['placeholder'] = Inflector::humanize($placeholder);
			}
			//orly added
			if(!isset($options['type'])) $options['label']=false;
			if((isset($options['type'])&&(!in_array($options['type'], array('date','checkbox','radio'))))){
				$options['label']=false;
			}
	
		}
		return $options;
	}
	//orly added new function to support html5 validation
	public function getFieldRegex($model='Group',$type,$country){
		$model=$this->_getModel($model);
		return $model->getRegex($type,$country);
	}
	public function input($fieldName, $options = array()) {
		
		$options = $this->_placeholderOptions($fieldName, $options);
		//$options = $this->_oninvalid($fieldName, $options);

		if (empty($options['title']) && empty($options['label']) && !empty($options['placeholder']) && empty($options['tooltip'])) {
			$options['tooltip'] = $options['placeholder'];
		}

		if (!empty($options['help'])) {
			$options = $this->_helpText($options);
		}

		if (array_key_exists('tooltip', $options)) {
			$options = $this->_tooltip($options);
		}
		if (isset($options['type']) && in_array($options['type'],array('email','tel'))){
			$icon_convert=array('email'=>'envelope','tel'=>'phone');
			$options['before']= '<span class="add-on"><i class="icon-large icon-'.$icon_convert[$options['type']].'"></i></span>';
			$options['div'] = 'input-prepend text';
		}
		return parent::input($fieldName, $options);
		
	}

	public function button($title, $options = array()) {
		$defaults = array('class' => 'btn');
		$options = array_merge($defaults, $options);

		list($title, $options) = $this->_bootstrapGenerate($title, $options);

		return parent::button($title, $options);
	}

	public function submit($caption = null, $options = array()) {
		$defaults = array('class' => 'btn', 'escape' => false);
		$options = array_merge($defaults, $options);

		list($caption, $options) = $this->_bootstrapGenerate($caption, $options);

		return parent::submit($caption, $options);
	}

}
