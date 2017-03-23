jQuery(function ($) {

    jQuery.validator.addMethod("phone", function (value, element) {
        return this.optional(element) || /^\(\d{3}\) \d{3}\-\d{4}( x\d{1,6})?$/.test(value);
    }, "Especifique un número de teléfono válido.");

    $('#validation-form').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: true,
        ignore: "",
        rules: {
            _username: {
                required: true
            },
            _password: {
                required: true
            },
            password1: {
                required: true,
                minlength: 8
            },
            password2: {
                required: true,
                minlength: 8,
                equalTo: "#password1"
            },
            phone: {
                required: true,
                phone: 'required'
            },
            url: {
                required: true,
                url: true
            },
            comment: {
                required: true
            },
            agree: {
                required: true
            },
            email: {
                required: true,
                email: true
            }
        },

        messages: {
            _username: {
                required: "Especifique un usuario."
            },
            _password: {
                required: "Especifique una contraseña."
            },
            email: {
                required: "Especifique un correo",
                email: "Especifique un correo válido."
            },
            password1: {
                required: "Especifique una contraseña.",
                minlength: "Especifique una contraseña de logintud mayor a 8"
            },
            password2: {
                required: "Especifique una contraseña.",
                equalTo: "No coinciden."
            },
            cti_ctibundle_dplanresultadocentro_fecha: {
                required: "Especifique una fecha para el plan."
            }
        },

        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },

        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
            $(e).remove();
        },

        errorPlacement: function (error, element) {
            if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
            }
            else if(element.is('.select2')) {
                error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
            }
            else if(element.is('.chosen-select')) {
                error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
            }
            else error.insertAfter(element.parent());
        },

        submitHandler: function (form) {
            form.submit();
        },
        invalidHandler: function (form) {
        }
    });
});


