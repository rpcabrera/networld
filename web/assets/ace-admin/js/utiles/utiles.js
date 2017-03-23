/*
 *@Author mibrito
 * cosas utiles para todos los sub-proyectos
 * cada funcion debe comenzar con el prefijo(namespace) "sit_utiles"
 * para evitar interferencias con futuras funciones implementadas
 */
//declaracion de los namespace
/**
 * Namespace para publicar funciones utiles de todo el proyecto
 */
sit_utiles = {};
var loadingStack = new Array();

$(document).on('ready', function() {
    
    $('input[type="text"][class ^="date"]').keypress(function(e) {
        var key = e.keyCode ? e.keyCode : e.which;
        if (key == 13)
        {
            e.preventDefault();
            return false;
        }
    });
    
    if (typeof history.pushState === "function") {
        history.pushState("jibberish", null, null);
        window.onpopstate = function () {
            history.pushState('newjibberish', null, null);
            // Handle the back (or forward) buttons here
            // Will NOT handle refresh, use onbeforeunload for this.
        };
    } else {
        var ignoreHashChange = true;
        window.onhashchange = function () {
            if (!ignoreHashChange) {
                ignoreHashChange = true;
                window.location.hash = Math.random();
                // Detect and redirect change here
                // Works in older FF and IE9
                // * it does mess with your hash symbol (anchor?) pound sign
                // delimiter on the end of the URL
            }
            else {
                ignoreHashChange = false;
            }
        };
    }
    
    window.onbeforeunload = function() {
        sit_utiles.DesabilitarElemento();
    };
    window.onload = function() {
        sit_utiles.HabilitarElemento();
    };

    jQuery.ajaxSetup({
        // Abort all Ajax requests after 20 seconds
        timeout: 60000,
        // Defeat browser cache by adding a timestamp to URL
        cache: false
    });
});

$(document).ajaxSend(function(event, jqxhr, settings) {
    if (settings.type == "POST" || settings.type == "GET") {
        sit_utiles.DesabilitarElemento();
    }
});

$(document).ajaxComplete(function(event, jqxhr, settings) {
    if (settings.type == "POST" || settings.type == "GET") {
        sit_utiles.HabilitarElemento();
    }
});

/**
 * Desabilita un elemento dado, si no se especifica se desabilita toda la página
 * @param idelemento identificador del elemento a desabilitar
 * @constructor
 */
sit_utiles.DesabilitarElemento = function(idelemento) {
    var elemento;
    if (idelemento == null) {
        elemento = $('#body_content');
    } else {
        elemento = $('#' + idelemento);
    }
    $('#body_content').append('<div id="dvLoading">Cargando...</div>');
    elemento.addClass('disablingDiv');
    loadingStack.push('off');
};

/**
 * Habilita un elemento dado, si no se especifica se habilita toda la página
 * @param idelemento identificador del elemento a habilitar
 * @constructor
 */
sit_utiles.HabilitarElemento = function(idelemento) {
    loadingStack.pop();
    if (loadingStack.length == 0) {
        var elemento;
        if (idelemento == null) {
            elemento = $('#body_content');
        } else {
            elemento = $('#' + idelemento);
        }
        $('#body_content').children('#dvLoading').remove();
        elemento.removeClass('disablingDiv');
    }
};

/**
 * Desabilita un componente dado si no se especifica no se hace nada
 * @param idcomplemento identificador del componente a habilitar
 * @constructor
 */
sit_utiles.DesabilitarComponente = function(idcomplemento) {
    var elemento = $('#' + idcomplemento);
    elemento.addClass('disablingWaitDiv');
};

/**
 * Habilita un componente dado, si no se especifica se habilita toda la página
 * @param idcomplemento identificador del componente a habilitar
 * @constructor
 */
sit_utiles.HabilitarComponente = function(idcomplemento) {
    var elemento = $('#' + idcomplemento);
    elemento.removeClass('disablingWaitDiv');
};

/**
 * Cambia en el navegador la url de la pagina por una nueva.
 * @param page título de la página, url dirección de la petición.
 * @param url dirección de la petición.
 * @constructor
 */
sit_utiles.ChangeUrl = function(page, url) {
    if (typeof (history.pushState) != "undefined") {
        var obj = {Page: page, Url: url};
        history.pushState(obj, obj.Page, obj.Url);
    } else {
        alert("Este navegador no tiene soporte para HTML5. Algunas funcionalidades no funcionarán correctamente.");
        window.location.href = url;
    }
};

/**
 * Realiza una petición GET vía ajax.
 * @param url dirección de la petición.
 * @constructor
 */
sit_utiles.AjaxGetRequest = function(url, callback) {
    if('#' == url) {
        return;
    }
    
    var url_ajax = sit_utiles.buildUrl(url, '_ajax', true);
    var $container = $("#ajax-update");
    $container.load(url_ajax, function(){
        sit_utiles.ChangeUrl('SGBC', url);
        if(callback){
            callback();
        }
    });
};

/**
 * Agregar parametros extars a una url.
 * @param base dirección de la petición.
 * @param key nombre del parametro a agregar.
 * @param value valor del parametro a agregar.
 * @constructor
 */
sit_utiles.buildUrl = function(base, key, value) {
    var sep = (base.indexOf('?') > -1) ? '&' : '?';
    return base + sep + key + '=' + value;
}