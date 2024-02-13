
$(function() {

  $(#formulario).validate({
   
    rules: {
      titulo: { required: true, minlength: 3, maxlength:50 },
      descripcion: { required: true, minlength: 5, maxlength:50 }
    },
    // Specify validation error messages
    messages: {
      titulo: {
        required: "Este campo es obligatorio",
        minlength: "El Titulo introducido tiene muy pocos caracteres",
        maxlength: "El Titulo introducido tiene mas caracteres de los permitidos"
      },
      descripcion: { 
        required: "Este campo es obligatorio",
        minlength: "La Descripcion introducida tiene muy pocos caracteres",
        maxlength: "La Descripcion introducida tiene mas caracteres de los permitidos"
      }
    },

    submitHandler: function(form) {
      form.submit();
    }
  });
});