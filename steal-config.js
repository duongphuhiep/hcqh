System.config({
	"main": "main",

	//"transpiler": "traceur",
	"paths": {
		"*": "*.js",
		"github:*": "jspm_packages/github/*.js",
		"npm:*": "jspm_packages/npm/*.js",
		"node:*":"node_modules/*.js",
		"bower:*": "bower_components/*.js",
		"vendor:*": "vendor/*",
		"vendorjs:*": "vendor/js/*.js"
		//"jquery": "https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"
	},

	"map": {
		"jquery": "node:jquery/dist/jquery",
		//"riot": "bower:riot/riot", //"npm:riot@2.2.3"
		"riot": "node:riot/riot",
		"modernizr": "vendorjs:modernizr.custom.71422",
		"deliver": "vendorjs:deliver",
		"slidebars": "vendorjs:slidebars.min",
		"jquery.slideme2": "vendorjs:jquery.slideme2"
	}
	/*"meta": {
		"deliver": { deps: ["jquery"] },
		"jquery.slideme2": { deps: ["jquery"] }
	}*/
});

/*
System.meta['deliver'] = { "deps": ['jquery'] };
System.meta['jquery.slideme2'] = { deps: ['jquery'] };
*/
