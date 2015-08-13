System.config({
	"main": "main",

	//"transpiler": "traceur",
	"paths": {
		"*": "*.js",
		"node:*":"node_modules/*.js",
		"vendor:*": "vendor/*",
		"vendorjs:*": "vendor/js/*.js"
		//"jquery": "https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"
	},

	"map": {
		"jquery": "node:jquery/dist/jquery",
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
*/
