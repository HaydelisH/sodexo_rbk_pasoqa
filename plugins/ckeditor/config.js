/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	//Copiar desde Word 
	config.extraPlugins = 'pastefromword';
		//Dependencias
		config.extraPlugins = 'clipboard';
		config.extraPlugins = 'dialog';
			//Dependencias
			config.extraPlugins = 'dialogui';

		config.extraPlugins = 'notification';
		config.extraPlugins = 'toolbar';
			//Dependencias
			config.extraPlugins = 'button';

	//Copiar desde Excel
	config.extraPlugins = 'pastefromexcel';

	//Remover formato
	config.extraPlugins = 'removeformat';

	//Menciones
	config.extraPlugins = 'mentions';
	config.extraPlugins = 'emoji';
		//Dependencias
		config.extraPlugins = 'autocomplete';

		config.extraPlugins = 'textwatcher';
			//Dependencias
			config.extraPlugins = 'textmatch';

		config.extraPlugins = 'ajax';
			//Dependencias
			config.extraPlugins = 'xml';

    config.extraPlugins = 'widget';
    config.extraPlugins = 'placeholder';

	//Autotag
    config.extraPlugins = 'autotag';

};


