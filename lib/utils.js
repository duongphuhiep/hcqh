/**
 * Created by hiep on 28/08/2015.
 */
/**
 * Parse RFC 3986 compliant URIs.
 * Based on parseUri by Steven Levithan <stevenlevithan.com>
 * See http://blog.stevenlevithan.com/archives/parseuri
 *
 * Example: parse "backend/blog.php?page=1&lang=fr" to
 * {
	  anchor: "",
	  authority: "",
	  directory: "backend/",
	  file: "blog.php",
	  host: "",
	  password: "",
	  path: "backend/blog.php",
	  port: "",
	  protocol: "",
	  query: "page=1&lang=fr",
	  queryKey: { lang: "fr", page: "1" },
	  relative: "backend/blog.php?page=1&lang=fr",
	  source: "backend/blog.php?page=1&lang=fr",
	  user: "",
	  userInfo: ""
	}
*/
module.exports.parseUri = function(str) {
    var pattern = /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/;
    var key = ["source", "protocol", "authority", "userInfo", "user",
               "password", "host", "port", "relative", "path",
               "directory", "file", "query", "anchor"];
    var querypattern = /(?:^|&)([^&=]*)=?([^&]*)/g;

    var match = pattern.exec(str);
	var uri = {};
	var i = 14;
    while (i--) {
        uri[key[i]] = match[i] || "";
    }

    uri.queryKey = {};
    uri[key[12]].replace(querypattern, function ($0, $1, $2) {
	    if ($1) {
            uri.queryKey[$1] = $2;
        }
    });

    return uri;
};

/**
 * Parse a key-value string to the javascript object
 *   "title: abcd
 *   author: hiep"
 *
 * @param rawConfig is a multi-line string which line contains "key:value"
 * @return javascript object
 */
module.exports.parseConfig = function (rawConfig) {
	var config = {};
	var items = rawConfig.split("\n");
	$.each(items, function( index, value ) {
		var separatorPos = value.indexOf(":");
		var k = value.substr(0, separatorPos).trim();
		var v = value.substr(separatorPos+1).trim();
		if (k) {
			config[k] = v;
		}
	});
	return config;
};