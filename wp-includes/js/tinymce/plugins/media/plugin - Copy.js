/**
 * plugin.js
 *
 * Copyright, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/*jshint maxlen:255 */
/*eslint max-len:0 */
/*global tinymce:true */

tinymce.PluginManager.add('media', function(editor, url) {
	

	editor.addButton('media', {
		tooltip: 'Insert/edit video',
		onclick: showDialog,
		stateSelector: ['img[data-mce-object=video]', 'img[data-mce-object=iframe]']
	});


});
