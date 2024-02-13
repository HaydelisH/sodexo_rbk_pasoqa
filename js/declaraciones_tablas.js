
"use strict"

//Tablas de Listado
$(function () {
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
})
//Tabla de seleccion de Clausulas dentro de Plantillas
$(function () {
    $('#example3').DataTable({
        'paging': true,
        'lengthChange': false,
        'searching': false,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        'fixedColumns': true
    })
})

//Tabla de resultado de generaci�n masiva 
$(function () {
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
})