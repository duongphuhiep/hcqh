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
var utils = require('../utils');
//var $ = require('jquery');

var blogpageFakeData = {
	"blogpage-1-en": require('./blogpage-1-en'),
	"blogpage-1-fr": require('./blogpage-1-fr'),
	"blogpage-1-vi": require('./blogpage-1-vi'),
	"blogpage-2-en": require('./blogpage-2-en'),
	"blogpage-2-fr": require('./blogpage-2-fr'),
	"blogpage-2-vi": require('./blogpage-2-vi')
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

	console.log("[Fake Request]", request.method, request.url, request.requestBody);

	if (parsedUrl.file === 'blog.php') {
		var page  = parsedUrl.queryKey.page || 1;
		var lang = parsedUrl.queryKey.lang || 'vi';

		var fakeData = blogpageFakeData['blogpage-'+page+'-'+lang];
		request.respond(200, {"Content-Type": "application/json"}, JSON.stringify(fakeData));
		return;
	}
	else if (parsedUrl.file === 'admin.php') {
		var action = request.requestBody["action"];

		if (action === "ls") {
			var path = request.requestBody["path"];
			if (path === "content/blog") {
				var fakePostIds = [
					"2015-05-19 Bai viet 04",
					"2015-05-18 Aee primus axona",
					"2015-05-17 Racanas observare",
					"2015-05-16 Cur cobaltum mori",
					"2015-05-15 Nunquam manifestum planeta",
					"2015-05-14 Omnes ususes amor alter, altus urbses.",
					"2015-05-13 Pius gemna sensim carpseriss visus est."
				];
				request.respond(200, {"Content-Type": "application/json"}, JSON.stringify(fakePostIds));
				return;
			}
			else {
				var fakePostItems = [];

				if (path === "content/blog/2015-05-19 Bai viet 04") {
					fakePostItems = [
						"en.md", "fr.md", "mozart_requiem_poster.jpg", "vi.md"
					];
				}
				else {
					fakePostItems = [
						"Vae, primus axona.png",
						"en.md",
						"Racanas observare.png",
						"Nunquam dignus era.jpg",
						"fr.md",
						"Nunquam locus medicina.gif",
						"Ubi est azureus terror.bmp",
						"vi.md",
						"_vi.md",
						"_" + path + ".jpeg" //to recognize the folder
					];
				}
				request.respond(200, {"Content-Type": "application/json"}, JSON.stringify(fakePostItems));
				return;
			}
		}
		else if (action === "ren") {
			var path = request.requestBody["path"];
			var newName = request.requestBody["newName"];
			if (newName ==="_fr.md") {
				var fakePostItems = [
					"en.md", "_fr.md", "mozart_requiem_poster.jpg", "vi.md"
				];
				request.respond(200, {"Content-Type": "application/json"}, JSON.stringify(fakePostItems));
			}
			else if (newName === "foo") {
				var errorMessage = {error: "Unauthorized"}
				request.respond(401, {"Content-Type": "application/json"}, JSON.stringify(errorMessage));
			}
		}

		console.error("Cannot simulate reponse for", request);

		var errorMessage = {error: "Not support"}
		request.respond(404, {"Content-Type": "application/json"}, JSON.stringify(errorMessage));
	}
});



