(function() {
    var riot = require("riot");
    var RiotControl = require("RiotControl");
    var currentPageInfo = getCurrentPageInfoFromBrowser(); //use for caching

    /**
     * Disable the default riot route parser
     */
    riot.route.parser(function(path) {
		if (!path) {
			switchToPage(path);
		}
        return path;
    });

    /**
     input: path is '/post/2015-02-28%20learn_markdown/en'  ->
     return   { pageName: 'post', params: ['2015-02-28%20learn_markdown', 'en'] };
     */
    function parsePath(path) {
		//console.info("parsePath", path);
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
     *  notify navbar and content-route to change the view
     */
    function switchToPage(path) {
		//console.info("switchToPage",path);
        currentPageInfo = parsePath(path);
        RiotControl.trigger('pageChange', currentPageInfo);
    }

    /**
     * Handle route change event
     */
    riot.route(function (path) {
        switchToPage(path)
    });

    var RootContent = (DEBUG ? "backend_mock" : "content" )+"/";

    module.exports.getCurrentPageInfo = function() {return  currentPageInfo;};
    module.exports.switchToPage = switchToPage;
    module.exports.pathToBlogFolder = RootContent+"blog/";
    module.exports.pathToBannerFolder = RootContent+"home/banner/";
    module.exports.pathToMemberFolder = RootContent+"members/";

	riot.route.start(true);
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
