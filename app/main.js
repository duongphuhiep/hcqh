"use strict";

var option = { resGetPath: 'content/locales/__lng__-__ns__.json' }; //default ns is translation
i18n.init(option, function(err, t) {
    $(document).i18n();
});

$(function () {
    /* hide nav bar when click outside*/
    $(document).click(function (event) {
        var clickover = $(event.target);
        var _opened = $(".navbar-collapse").hasClass("collapse in");
        if (_opened === true && !clickover.hasClass("navbar-toggle") && !clickover.is('input')) {
            $("button.navbar-toggle").click();
        }
    });

});

riot.mount('*');






