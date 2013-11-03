<?php
/**
 * Tinymce Helper
 *
 * PHP version 5
 *
 * @category Tinymce.Helper
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class TinymceHelper extends AppHelper {

/**
 * Other helpers used by this helper
 *
 * @var array
 * @access public
 */
	public $helpers = array(
		'Html',
		'Js',
			'Form'
	);

/**
 * Actions
 *
 * Format: ControllerName/action_name => settings
 *
 * @var array
 */
	public $actions = array();

/**
 * Default settings for tinymce
 *
 * @var array
 * @access public
 */
	public $settings = array(
		// General options
		'mode' => 'exact',
		'elements' => '',
		'theme' => 'advanced',
		'relative_urls' => false,
		'plugins' => 'safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template',
		'width' => '100%',
		'height' => '250px',

		// Theme options
		'theme_advanced_buttons1' => 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect',
		'theme_advanced_buttons2' => 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,forecolor,backcolor',
		'theme_advanced_buttons3' => 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen',
		//'theme_advanced_buttons4' => 'insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak',
		'theme_advanced_toolbar_location' => 'top',
		'theme_advanced_toolbar_align' => 'left',
		'theme_advanced_statusbar_location' => 'bottom',
		'theme_advanced_resizing' => true,

		// Example content CSS (should be your site CSS)
		//'content_css' => 'css/content.css',

		// Drop lists for link/image/media/template dialogs
		'template_external_list_url' => 'lists/template_list.js',
		'external_link_list_url' => 'lists/link_list.js',
		'external_image_list_url' => 'lists/image_list.js',
		'media_external_list_url' => 'lists/media_list.js',

		// Attachments browser
		'file_browser_callback' => 'fileBrowserCallBack',
	);

/**
 * fileBrowserCallBack
 *
 * @return string
 */
	public function fileBrowserCallBack() {
		$output = <<<EOF
function fileBrowserCallBack(field_name, url, type, win) {
	browserField = field_name;
	browserWin = win;
	window.open('%s', 'browserWindow', 'modal,width=960,height=700,scrollbars=yes');
}
EOF;
		$output = sprintf($output, $this->Html->url(
			array('plugin' => false, 'controller' => 'attachments', 'action' => 'browse')
			));

		return $output;
	}

/**
 * selectURL
 *
 * @return string
 */
	public function selectURL() {
		$output = <<<EOF
function selectURL(url) {
	if (url == '') return false;

	url = '%s' + url;

	field = window.top.opener.browserWin.document.forms[0].elements[window.top.opener.browserField];
	field.value = url;
	if (field.onchange != null) field.onchange();
		window.top.close();
		window.top.opener.browserWin.focus();
}
EOF;
		$output = sprintf($output, Router::url('/uploads/', true));

		return $output;
	}

/**
 * getSettings
 *
 * @param array $settings
 * @return array
 */
	public function getSettings($settings = array()) {
		$_settings = $this->settings;
		$action = Inflector::camelize($this->params['controller']) . '/' . $this->params['action'];
		if (isset($this->actions[$action])) {
			$settings = array();
			foreach ($this->actions[$action] as $action) {
				$settings[] = Set::merge($_settings, $action);
			}
		}
		return $settings;
	}

/**
 * beforeRender
 *
 * @param string $viewFile
 * @return void
 */
	function textarea($fieldName, $options = array(), $tinyoptions = array(), $preset = null){
		// If a preset is defined
		if(!empty($preset)){
			$preset_options = $this->preset($preset);
	
			// If $preset_options && $tinyoptions are an array
			if(is_array($preset_options) && is_array($tinyoptions)){
				$tinyoptions = array_merge($preset_options, $tinyoptions);
			}else{
				$tinyoptions = $preset_options;
			}
		}
		return $this->Form->textarea($fieldName, $options) . $this->_build($fieldName, $tinyoptions);
	}
	
	/**
	 * Creates a TinyMCE textarea.
	 *
	 * @param string $fieldName Name of a field, like this "Modelname.fieldname"
	 * @param array $options Array of HTML attributes.
	 * @param array $tinyoptions Array of TinyMCE attributes for this textarea
	 * @return string An HTML textarea element with TinyMCE
	 */
	function input($fieldName, $options = array(), $tinyoptions = array(), $preset = null){
		// If a preset is defined
		if(!empty($preset)){
			$preset_options = $this->preset($preset);
	
			// If $preset_options && $tinyoptions are an array
			if(is_array($preset_options) && is_array($tinyoptions)){
				$tinyoptions = array_merge($preset_options, $tinyoptions);
			}else{
				$tinyoptions = $preset_options;
			}
		}
		$options['type'] = 'textarea';
		return $this->Form->input($fieldName, $options) . $this->_build($fieldName, $tinyoptions);
	}
	
	public function beforeRender($viewFile) {
		if (is_array(Configure::read('Tinymce.actions'))) {
			$this->actions = Set::merge($this->actions, Configure::read('Tinymce.actions'));
		}
		$action = Inflector::camelize($this->params['controller']) . '/' . $this->params['action'];
		if (Configure::read('Writing.wysiwyg') && isset($this->actions[$action])) {
			$this->Html->script('/tinymce/js/tiny_mce', array('inline' => false));
			$this->Html->scriptBlock($this->fileBrowserCallBack(), array('inline' => false));
			$settings = $this->getSettings();
			foreach ($settings as $setting) {
				$this->Html->scriptBlock('tinyMCE.init(' . $this->Js->object($setting) . ');', array('inline' => false));
			}
		}

		if ($this->params['controller'] == 'attachments' && $this->params['action'] == 'admin_browse') {
			$this->Html->scriptBlock($this->selectURL(), array('inline' => false));
		}
	}
	private function preset($name){
		// Full Feature
		if($name == 'full'){
			return array(
					'theme' => 'advanced',
					'plugins' => 'safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template',
					'theme_advanced_buttons1' => 'save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect',
					'theme_advanced_buttons2' => 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor',
					'theme_advanced_buttons3' => 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen',
					'theme_advanced_buttons4' => 'insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak',
					'theme_advanced_toolbar_location' => 'top',
					'theme_advanced_toolbar_align' => 'left',
					'theme_advanced_statusbar_location' => 'bottom',
					'theme_advanced_resizing' => true,
					'theme_advanced_resize_horizontal' => false,
					'convert_fonts_to_spans' => true,
					'directionality' => "rtl",
					'file_browser_callback' => 'ckfinder_for_tiny_mce'
			);
		}
	
		// Basic
		if($name == 'basic'){
			//orly changed
			return array(
					'theme' => 'advanced',
					'plugins' => 'safari,pagebreak,style,layer,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,media,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,template',
					'theme_advanced_buttons1' => 'bold,italic|,justifyleft,justifycenter,justifyright,justifyfull,ltr,rtl,formatselect,fontselect,fontsizeselect',
					'theme_advanced_buttons2' => 'copy,paste|,bullist,numlist,|,outdent,indent|,undo,redo,|media,image,cleanup,emotions|,forecolor,backcolor,fullscreen',
					'theme_advanced_buttons3' => '',
					'theme_advanced_toolbar_location' => 'top',
					'theme_advanced_toolbar_align' => __('left'),
					'theme_advanced_statusbar_location' => 'bottom',
					'theme_advanced_resizing' => true,
					'theme_advanced_resize_horizontal' => false,
					'convert_fonts_to_spans' => true,
					'directionality' => "rtl",
					'file_browser_callback' => 'ckfinder_for_tiny_mce'
			);
		}
	
		// Simple
		if($name == 'simple'){
			return array(
					'theme' => 'simple',
			);
		}
	
		// BBCode
		if($name == 'bbcode'){
			return array(
					'theme' => 'advanced',
					'plugins' => 'bbcode',
					'theme_advanced_buttons1' => 'bold,italic,underline,undo,redo,link,unlink,image,forecolor,styleselect,removeformat,cleanup,code',
					'theme_advanced_buttons2' => '',
					'theme_advanced_buttons3' => '',
					'theme_advanced_toolbar_location' => 'top',
					'theme_advanced_toolbar_align' => 'left',
					'theme_advanced_styles' => 'Code=codeStyle;Quote=quoteStyle',
					'theme_advanced_statusbar_location' => 'bottom',
					'theme_advanced_resizing' => true,
					'theme_advanced_resize_horizontal' => false,
					'entity_encoding' => 'raw',
					'add_unload_trigger' => false,
					'remove_linebreaks' => false,
					'inline_styles' => false
			);
		}
		return null;
	}
	function _build($fieldName, $tinyoptions = array()){
		if(!$this->_script){
			// We don't want to add this every time, it's only needed once
			$this->_script = true;
			$this->Html->script('/tinymce/js/tiny_mce', array('inline' => false));
					}
	
		// Ties the options to the field
		$tinyoptions['mode'] = 'exact';
		$tinyoptions['elements'] = $this->domId($fieldName);
	
		// List the keys having a function
		$value_arr = array();
		$replace_keys = array();
		foreach($tinyoptions as $key => &$value){
			// Checks if the value starts with 'function ('
			if(strpos($value, 'function(') === 0){
				$value_arr[] = $value;
				$value = '%' . $key . '%';
				$replace_keys[] = '"' . $value . '"';
			}
		}
	
		// Encode the array in json
		$json = $this->Js->object($tinyoptions);
	
		// Replace the functions
		$json = str_replace($replace_keys, $value_arr, $json);
		$this->Html->scriptStart(array('inline' => false));
		echo 'tinyMCE.init(' . $json . ');';
		$this->Html->scriptEnd();
	}	
}