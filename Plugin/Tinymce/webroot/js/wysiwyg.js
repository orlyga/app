Croogo.Wysiwyg.Tinymce = {

	presets: {

		basic: {},

		standard: {
		  mode: 'exact',
		  relative_urls: false,
		  plugins: 'advlist paste searchreplace spellchecker link anchor autolink image table charmap fullscreen code',
		  tools: 'inserttable',
		  toolbar: 'undo redo | searchreplace spellchecker | bold italic underline strikethrough remove format | bullist numlist outdent indent blockquote hr | link unlink anchor',
		},

		full: {}
	},

	setup: function(el, config) {
		var preset = null;
		var defaults = {
		  elements: el
		};
		if (config.preset === false) {
			delete config.preset;
		} else {
			if (typeof config.preset == 'undefined') {
				config.preset = 'standard';
			}
			preset = Croogo.Wysiwyg.Tinymce.presets[config.preset];
			delete config.preset;
		}
		if (typeof config.extra_plugins != 'undefined') {
			preset.plugins = preset.plugins + ' ' + config.extra_plugins;
			delete config.extra_plugins;
		}
		$.extend(defaults, preset);
		$.extend(defaults, config);
		$.extend(config, defaults);
    
		config.file_browser_callback = fileBrowserCallback;
			  
	  tinymce.init(config);
	}

}

/**
 * This function is called when you select an image file to be inserted in your editor.
 */
Croogo.Wysiwyg.choose = function(url, title, description) {
	if (url == '') return false;
	url = Croogo.Wysiwyg.uploadsPath + url;

	desc_field = window.top.opener.browserWin.document.forms[0].elements[2];
	if (typeof description !== 'undefined') {
		desc_field.value = description;
	}

	title_field = window.top.opener.browserWin.document.forms[0].elements[3];
	if (typeof title !== 'undefined') {
		title_field.value = title;
	}

	field = window.top.opener.browserWin.document.getElementById(window.top.opener.browserField);
	field.value = url;
	
	if (field.onchange != null) {
		field.onchange();
	}
	window.top.close();
	window.top.opener.browserWin.focus();
}

/**
 * This function is responsible for integrating attachments/file browser in the editor.
 */
Croogo.Wysiwyg.browser = function() {
  window.fileBrowserCallback = function(field_name, url, type, win) {
		browserField = field_name;
		browserWin = win;
		window.open(Croogo.Wysiwyg.attachmentsPath, 'browserWindow', 'modal,width=960,height=700,scrollbars=yes');
	}
};
