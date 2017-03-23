(function ($) {

    jQuery.fn.validChar = function (config) {
        var configDefault = {
            '_valid': ['numeros'],
            '_others_valid': null,
            '_invalid': null,
            '_exp_reg': null,
            '_cantCharacter': -1,
        };
        jQuery.extend(configDefault, config);
        return this.data("param", configDefault).keypress(jQuery.fn.validChar.keypress).keyup(jQuery.fn.validChar.keyup).blur(jQuery.fn.validChar.blur).bind('paste', jQuery.fn.validChar.paste);
    };

    jQuery.fn.validChar.paste = function (e) {
        var param = $.data(this, "param");
        var self = $(this);
        setTimeout(function (e) {
            var value = checkValue($(self).val(), param);
            $(self).val(value);
        }, 0);
    }

    jQuery.fn.validChar.keypress = function (e) {

        letras = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZáéíóúÁÉÍÓÚñÑ";
        letrasMin = "abcdefghijklmnopqrstuvwxyz";
        letrasMay = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        tildeMin = "áéíóú";
        tildeMay = "ÁÉÍÓÚ";
        numeros = "0123456789";
        nnMin = "ñ";
        nnMay = "Ñ";


        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if (key == 13 && this.nodeName.toLowerCase() == "input") {
            return false;
        }
        else if (key == 13) {
            return false;
        }
        var allow = false;
        // allow Ctrl+A
        if ((e.ctrlKey && key == 97 /* firefox */) || (e.ctrlKey && key == 65) /* opera */) {
            return true;
        }
        // allow Ctrl+X (cut)
        if ((e.ctrlKey && key == 120 /* firefox */) || (e.ctrlKey && key == 88) /* opera */) {
            return true;
        }
        // allow Ctrl+C (copy)
        if ((e.ctrlKey && key == 99 /* firefox */) || (e.ctrlKey && key == 67) /* opera */) {
            return true;
        }
        // allow Ctrl+Z (undo)
        if ((e.ctrlKey && key == 122 /* firefox */) || (e.ctrlKey && key == 90) /* opera */) {
            return true;
        }
        // allow or deny Ctrl+V (paste), Shift+Ins
        if ((e.ctrlKey && key == 118 /* firefox */) || (e.ctrlKey && key == 86) /* opera */ ||
            (e.shiftKey && key == 45)) {
            return true;
        }

        var param = $.data(this, "param");

        if ((key == 8 ) || /* backspace */
            (key == 9 && e.which == 0) || /* tab */
            (key == 13 && e.which == 0) || /* enter */
            (key == 35 && e.which == 0) || /* end */
            (key == 36 && e.which == 0) || /* home */
            (key == 37 && e.which == 0) || /* left */
            (key == 39 && e.which == 0) || /* right */
            (key == 46 && e.which == 0) /* del */
            ) {
            if (typeof e.charCode != "undefined") {
                if (e.keyCode == e.which && e.which !== 0) {
                    allow = true;
                    if (e.which == 46) {
                        allow = false;
                    }
                }
                else if (e.keyCode !== 0 && e.charCode === 0 && e.which === 0) {
                    allow = true;
                }
            }
            return allow;
        }
        else {
            if ($.inArray("espacio", param._valid) != -1 && key == 32) {
                allow = true;
            }
            else if ($.inArray("letrasMin", param._valid) != -1 && letrasMin.indexOf(String.fromCharCode(key)) != -1) {
                allow = true;
            }
            else if ($.inArray("letrasMay", param._valid) != -1 && letrasMay.indexOf(String.fromCharCode(key)) != -1) {
                allow = true;
            }
            else if ($.inArray("tildeMin", param._valid) != -1 && tildeMin.indexOf(String.fromCharCode(key)) != -1) {
                allow = true;
            }
            else if ($.inArray("tildeMay", param._valid) != -1 && tildeMay.indexOf(String.fromCharCode(key)) != -1) {
                allow = true;
            }
            else if ($.inArray("numeros", param._valid) != -1 && numeros.indexOf(String.fromCharCode(key)) != -1) {
                allow = true;
            }
            else if ($.inArray("nnMin", param._valid) != -1 && nnMin.indexOf(String.fromCharCode(key)) != -1) {
                allow = true;
            }
            else if ($.inArray("nnMay", param._valid) != -1 && nnMay.indexOf(String.fromCharCode(key)) != -1) {
                allow = true;
            }
            else if (param._others_valid != null && param._others_valid.indexOf(String.fromCharCode(key)) != -1) {
                allow = true;
            }
            else {
                allow = false;
            }

            if (param._invalid != null && param._invalid.indexOf(String.fromCharCode(key)) != -1) {
                allow = false;
            }

        }

        var value = $(this).val();

        if (param._cantCharacter != -1 && param._cantCharacter <= value.length) {
            allow = false;
        }

        return allow;
    };

    function checkValue(value, param) {
        letras = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZáéíóúÁÉÍÓÚñÑ";
        letrasMin = "abcdefghijklmnopqrstuvwxyz";
        letrasMay = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        tildeMin = "áéíóú";
        tildeMay = "ÁÉÍÓÚ";
        numeros = "0123456789";
        nnMin = "ñ";
        nnMay = "Ñ";

        var i = 0;
        while (i < value.length) {
            var entro = false;
            var ch = value.charAt(i);


            if ($.inArray("espacio", param._valid) != -1 && ch.charCodeAt(0) == 32) {
                allow = true;
            }
            else if ($.inArray("letrasMin", param._valid) != -1 && letrasMin.indexOf(ch) != -1) {
                allow = true;
            }
            else if ($.inArray("letrasMay", param._valid) != -1 && letrasMay.indexOf(ch) != -1) {
                allow = true;
            }
            else if ($.inArray("tildeMin", param._valid) != -1 && tildeMin.indexOf(ch) != -1) {
                allow = true;
            }
            else if ($.inArray("tildeMay", param._valid) != -1 && tildeMay.indexOf(ch) != -1) {
                allow = true;
            }
            else if ($.inArray("numeros", param._valid) != -1 && numeros.indexOf(ch) != -1) {
                allow = true;
            }
            else if ($.inArray("nnMin", param._valid) != -1 && nnMin.indexOf(ch) != -1) {
                allow = true;
            }
            else if ($.inArray("nnMay", param._valid) != -1 && nnMay.indexOf(ch) != -1) {
                allow = true;
            }
            else if (param._others_valid != null && param._others_valid.indexOf(ch) != -1) {
                allow = true;
            }
            else {
                allow = false;
            }

            if (param._invalid != null && param._invalid.indexOf(ch) != -1) {
                allow = false;
            }


            if (!allow) {
                value = value.substring(0, i) + value.substring(i + 1);
                entro = true;
            }
            if (!entro) {
                i = i + 1;
            }
        }

        if (param._cantCharacter != -1 && param._cantCharacter <= value.length) {
            value = value.substring(0, param._cantCharacter);
        }

        return value;
    }

    jQuery.fn.validChar.keyup = function (e) {
        var value = this.value;
        if (value && value.length > 0) {
            // get carat (cursor) position
            var carat = $.fn.getSelectionStart(this);
            var param = $.data(this, "param");

            this.value = checkValue(value, param);

            $.fn.setSelection(this, carat);
        }
    };

    jQuery.fn.validChar.blur = function () {
        var value = this.value;
        if (value && value.length > 0) {
            var param = $.data(this, "param");

            value = checkValue(value, param);
            this.value = value;
        }
    };


    $.fn.getSelectionStart = function (o) {
        if (o.createTextRange) {
            var r = document.selection.createRange().duplicate();
            r.moveEnd('character', o.value.length);
            if (r.text === '') {
                return o.value.length;
            }
            return o.value.lastIndexOf(r.text);
        } else {
            return o.selectionStart;
        }
    };

    jQuery.fn.setSelection = function (o, p) {
        if (typeof p == "number") {
            p = [p, p];
        }
        if (p && p.constructor == Array && p.length == 2) {
            if (o.createTextRange) {
                var r = o.createTextRange();
                r.collapse(true);
                r.moveStart('character', p[0]);
                r.moveEnd('character', p[1]);
                r.select();
            }
            else if (o.setSelectionRange) {
                o.focus();
                o.setSelectionRange(p[0], p[1]);
            }
        }
    };

})(jQuery);

$(document).ready(function() {
    $('form[validar="true"] input[type=text]').blur(function(e){
        var id = $(this).attr('id');
        var label = $("label[for=" + id + "]").html();
        
        if(($.trim( $(this).val() )).length==0) {
            $(this).val("");
        }
        
    });
});