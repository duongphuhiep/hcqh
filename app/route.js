(function() {
    var riot = require("riot");
    var RiotControl = require("RiotControl");
    var currentPageInfo = getCurrentPageInfoFromBrowser(); //use for caching

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
        else if (path === '404') {
            pageName = '404';
        }
        else {
            var spl = path.split('/');
            pageName = spl[0];

            rest = [];
            for (var i = 1; i < spl.length; i++) {
                rest[i - 1] = spl[i];
            }
        }
        return {pageName: pageName, params: rest};
    }

    function getCurrentPageInfoFromBrowser() {
        var loc = window.location;
        var path = loc.href.split('#')[1] || '';   // why not loc.hash.splice(1) ?
        return parsePath(path);
    }

    /**
     notify navbar and content-route to change the view
     */
    riot.route(function (path) {
        currentPageInfo = parsePath(path);
        RiotControl.trigger('pageChange', currentPageInfo);
    });

    module.exports.getCurrentPageInfo = function() {return  currentPageInfo;}
    module.exports.pathToBlogFolder = (DEBUG ? "backend_mock" : "content" )+"/blog/";
})();


/*
riot.mixin('routeInfo', {
    /!**
        return the current pageName (by extracting it from the window.location)
        when user refresh (F5) the page, certain tag file must to initialize its view base on the current pageName
    *!/
    getCurrentPageName: function(){
        var loc = window.location;
        var path = loc.href.split('#')[1] || '';   // why not loc.hash.splice(1) ?
        return parsePath(path).pageName;
    }
});*/
