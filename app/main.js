"use strict";

(function () {

    /**
     * the default namespace __ns__ is 'translation' so it fetch for
     * vi-translation.json, en-US-translation.json...
     */
    var option = { resGetPath: 'content/locales/__lng__-__ns__.json' };
    i18n.init(option, function(err, t) {
        $(document).i18n();
    });


    /**
    Disable the default riot route parser
    */
    riot.route.parser(function(path) {
        return path;
    });

    /**
    input: path is '/post/2015-02-28%20learn_markdown/en'  ->  
    return   { pageName: 'post', params: ['2015-02-28%20learn_markdown', 'en'] };
    */
    function parsePath(path) {
        var pageName, rest;
        if (!path) {
            pageName = 'home';
        }
        else if (path==='404') {
            pageName = '404';
        }   
        else {
            var spl = path.split('/');
            var pageName = spl[0];

            var rest = [];
            for (var i = 1; i < spl.length; i++) {
                rest[i - 1] = spl[i];
            }
        }
        return { pageName: pageName, params: rest };
    }

    /**
        notify navbar and content-router to change the view
    */
    riot.route(function(path) {
        
        RiotControl.trigger('pageChange', parsePath(path));
    });


    riot.mixin('routeInfo', {
        /**
            return the current pageName (by extracting it from the window.location)
            when user refresh (F5) the page, certain tag file must to initialize its view base on the current pageName
        */
        getCurrentPageName: function(){
            var loc = window.location;
            var path = loc.href.split('#')[1] || '';   // why not loc.hash.splice(1) ?
            return parsePath(path).pageName;
        }
    });


    riot.mount('*');

}());

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


