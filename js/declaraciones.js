
"use strict"

//Switch
if( document.getElementById('tabla_switch') != null ){

  var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

  elems.forEach(function(html) {
    var switchery = new Switchery(html);
  });
}

//Tablas de Listado

$(document).ready(function() {

   if( $('#example2') ){
      $('#example2').DataTable({
          'paging': true,
          'lengthMenu': [10, 25, 50],
          'lengthChange': true,
          'searching': true,
          'ordering': true,
          'info': true,
          'autoWidth': false,
          'fixedColumns': true
      })
  }
  
  if( $('#example3') ){
    //Tabla de seleccion de Clausulas dentro de Plantillas
    $('#example3').DataTable({
        'paging': true,
        'lengthChange': false,
        'searching': false,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        'fixedColumns': true
    })
  }

  if( $('#example4') ){
    //Tabla de resultado de generaci�n masiva 
    $('#example4').DataTable({
        'paging': true,
        'lengthMenu': [100, 500, 1000],
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        'fixedColumns': true
    })
  }

if( document.getElementById('TextoTxt') ){
  
  $(function () {
      //Genera el editor de texto para Correo y Personerias
      CKEDITOR.replace('TextoTxt',
      {
        toolbar: [
          { name: 'clipboard', items: [ 'PasteFromWord', '-', 'Undo', 'Redo' ] },
          { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'Subscript', 'Superscript' ] },
          { name: 'links', items: [ 'Link', 'Unlink' ] },
          { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
          { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule','PageBreak' ] },
          { name: 'editing', items: [ 'Scayt' ] },
          '/',
          { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
          { name: 'colors', items: [ 'TextColor', 'BGColor', 'CopyFormatting' ] },
          { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
          { name: 'document', items: [ 'Print', 'Source' ] }
        ],

        // Enabling extra plugins, available in the full-all preset: https://ckeditor.com/cke4/presets
        extraPlugins: 'colorbutton,justify,tableresize,colordialog',
        removeButtons: '',
        customConfig: '',
        bodyClass: 'document-editor',

        // Reduce the list of block elements listed in the Format dropdown to the most commonly used.
        format_tags: 'p;h1;h2;h3;pre',

      })
    });
  }

  if( document.getElementById('TextoTxt_Clausulas') ){
      //Genera el editor de texto para Clausulas 
      CKEDITOR.replace('TextoTxt_Clausulas',
      {
      
      toolbar: [
        { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText','PasteFromWord', '-', 'Undo', 'Redo' ] },
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'Subscript', 'Superscript', 'cleanup' ] },
        { name: 'links', items: [ 'Link', 'Unlink' ] },
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule','PageBreak' ] },
        { name: 'editing', items: [ 'Scayt', 'find', 'selection', 'spellchecker' ] },
        '/',
        { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor', 'CopyFormatting' ] },
        { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
        { name: 'document', items: [ 'Print', 'Source','mode', 'document', 'doctools', '-', 'NewPage', 'Preview', '-', 'Templates' ] } 
      ],

      // Enabling extra plugins, available in the full-all preset: https://ckeditor.com/cke4/presets
      
      extraPlugins: 'mentions,colorbutton,font,justify,print,tableresize,pastefromword,liststyle,colordialog',
      removeButtons: '',

      customConfig: '',

      // Upload images to a CKFinder connector (note that the response type is set to JSON).
      uploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',

      // Configure your file manager integration. This example uses CKFinder 3 for PHP.
      filebrowserBrowseUrl: '/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl: '/ckfinder/ckfinder.html?type=Images',
      filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
      filebrowserImageUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',

      disallowedContent: 'img{width,height,float}',
      extraAllowedContent: 'img[width,height,align];span{background};p{*}[*];table{*}[*];div{*}[*]',

      // Enabling extra plugins, available in the full-all preset: https://ckeditor.com/cke4/presets
    //extraPlugins: 'mentions,easyimage,sourcearea,toolbar,undo,wysiwygarea,basicstyles',
      extraPlugins: 'colorbutton,font,justify,print,tableresize,uploadimage,uploadfile,pastefromword,liststyle',

      // Note: it is recommended to keep your own styles in a separate file in order to make future updates painless.
      contentsCss: [ CKEDITOR.basePath + 'contents.css', 'https://sdk.ckeditor.com/samples/assets/css/pastefromword.css' ],

      // This is optional, but will let us define multiple different styles for multiple editors using the same CSS file.
      bodyClass: 'document-editor',

      // Reduce the list of block elements listed in the Format dropdown to the most commonly used.
      format_tags: 'p;h1;h2;h3;pre',

      // Simplify the Image and Link dialog windows. The "Advanced" tab is not needed in most cases.
      removeDialogTabs: 'image:advanced;link:advanced',

      stylesSet: [
        /* Inline Styles */
        { name: 'Marker', element: 'span', attributes: { 'class': 'marker' } },
        { name: 'Cited Work', element: 'cite' },
        { name: 'Inline Quotation', element: 'q' },

        /* Object Styles */
        {
          name: 'Special Container',
          element: 'div',
          styles: {
            padding: '5px 10px',
            background: '#eee',
            border: '1px solid #ccc'
          }
        },
        {
          name: 'Compact table',
          element: 'table',
          attributes: {
            cellpadding: '5',
            cellspacing: '0',
            border: '1',
            bordercolor: '#ccc'
          },
          styles: {
            'border-collapse': 'collapse'
          }
        },
        { name: 'Borderless Table', element: 'table', styles: { 'border-style': 'hidden', 'background-color': '#E6E6FA' } },
        { name: 'Square Bulleted List', element: 'ul', styles: { 'list-style-type': 'square' } }
      ]
    });
}

  if( $("#table") ){
    $('#table').tableDnD({
          onDrop: function(table, row) {
            //Asigno el contenido del resultado al div
            var orden = $.tableDnD.serialize();
            //orden = table[]=&table[]=1&table[]=2&table[]=17&table[]=19
            var idPlantilla = $('#idPlantilla').val();
            //Paso all Ajax
            $.ajax({
                url: 'Plantillas.php?',
                data: orden + '&idPlantilla=' + idPlantilla + '&accion=CAMBIAR_ORDEN',
                type: 'GET'
            });
          }
      });
  }
  if( $("#table") ){
    $('table').each(function() {
      var thetable = $(this);
      $(this).find('tbody td').each(function() {
          $(this).attr('data-heading', thetable.find('thead th:nth-child('+($(this).index()+1)+')').text());
      });
    });
  }
  
})