"use strict";

//(function () {

    var riot = require("riot");
    var i18n = require("i18next");
    /**
     * the default namespace __ns__ is 'translation' so it fetch for
     * vi-translation.json, en-US-translation.json...
     */
    var option = { resGetPath: 'content/locales/__lng__-__ns__.json', detectLngQS: 'lang', cookieName: 'lang', fallbackLng: 'en' };
    i18n.init(option, function(err, t) {
        $(document).i18n();
    });

    require('./route');
    require("../gen/tags");
    riot.mount('*');

//}());

/**
 * Application module (use pattern from https://github.com/umdjs/umd/blob/master/returnExportsGlobal.js)
 */
// (function (root, factory) {
//     if (typeof define === 'function' && define.amd) {
//         // AMD. Register as an anonymous module.
//         define(['jquery', 'riot'], function (jquery, riot) {
//             return (root.app = factory(jquery, riot));
//         });
//     } else if (typeof module === 'object' && module.exports) {
//         // Node. Does not work with strict CommonJS, but
//         // only CommonJS-like enviroments that support module.exports,
//         // like Node.
//         module.exports = factory(require('jquery'), require('riot'));
//     } else {
//         // Browser globals
//         root.app = factory(jQuery, riot);
//     }
// }(this, function ($, riot) {
    
//     //use jquery and riot
//     /*console.log($);
//     console.log(riot);*/
    
//     return {
//         getLanguage: function() {
//             return $('#language_selector').val();
//         }

//     };
// }));


