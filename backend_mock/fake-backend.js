//other module use this global variable to detect if the DEBUG mode is on
DEBUG = {
	/**
	 * Disable some debounced functions to become normal function, in order to detect deeper problem. Eg:
	 * Without debouncing, a XHR request was called 2 times consecutive (due to some events triggering),
	 * It is not normal, some events was triggering too much. It won't happen in production because
	 * the function was debounced so that the XHR request was called only 1 times on production, Debouncing
	 * had saved the situation and we won't see the deeper issue
	 */
	disableDebouncing: false
};

var sinon = require("sinon");
var utils = require('../lib/utils');
//var $ = require('jquery');

var blogpageFakeData = {
	"blogpage-1-en": require('../backend_mock/blogpage-1-en'),
	"blogpage-1-fr": require('../backend_mock/blogpage-1-fr'),
	"blogpage-1-vi": require('../backend_mock/blogpage-1-vi'),
	"blogpage-2-en": require('../backend_mock/blogpage-2-en'),
	"blogpage-2-fr": require('../backend_mock/blogpage-2-fr'),
	"blogpage-2-vi": require('../backend_mock/blogpage-2-vi')
};

var xhr = sinon.useFakeXMLHttpRequest(); //in fact xhr === sinon.FakeXMLHttpRequest
xhr.useFilters = true;
xhr.addFilter(function(method, url, async, username, password) {
	var parsedUrl= utils.parseUri(url);

	//mock all the request which filename ends with .php
	var patt = /.+\.php$/g;
	if (patt.test(parsedUrl.file)) {
		//console.log("Sinon filter matched fall to fake Server: ",method, url, async, username, password);
		return false; //return false to mock the request and return fake the result
	}
	return true; //return true to use the native XMLHttpRequest
});

var server = sinon.fakeServer.create({autoRespond : true, autoRespondAfter : 1000});

//server.respondWith(/\/post\.php\?id=(\d+)/, function (request, id) {
//	console.log("response with", request, id);
//	request.respond(200, {"Content-Type": "text/plain"}, "Here the content "+id);
//});

server.respondWith(function(request){
	var parsedUrl= utils.parseUri(request.url);

	if (parsedUrl.file === 'blog.php') {
		var page  = parsedUrl.queryKey.page || 1;
		var lang = parsedUrl.queryKey.lang || 'vi';

		var fakeData = blogpageFakeData['blogpage-'+page+'-'+lang];
		request.respond(200, {"Content-Type": "application/json"}, JSON.stringify(fakeData));
	}
	else if (parsedUrl.file === 'admin.php') {
		var fakePostIds = [
			"2015-05-18 aee, primus axona",
			"2015-05-17 Racanas observare",
			"2015-05-16 Nunquam dignus era",
			"2015-05-15 Nunquam locus medicina",
			"2015-05-14 Nunquam gratia urbs",
			"2015-05-13 Nunquam demitto abnoba"
		];
		console.log("Post request body", request.requestBody);
		request.respond(200, {"Content-Type": "application/json"}, JSON.stringify(fakePostIds));
	}
});



