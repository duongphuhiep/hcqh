var stealTools = require("steal-tools");

stealTools.build({
	config: "steal-config.js"
}, {
	watch: true
});


/*stealTools.export({
	system: {
		main: "main",
		config: __dirname+"/app/steal-config.js"
	},
	options: {
		verbose: true
	},
	outputs: {
/!*
		amd: {
			format: "amd",
			graphs: ["app/main"],
			dest: __dirname+"/dist/amd"
		},
*!/
		standalone: {
			format: "global",
			modules: ["app/main"],
			dest: __dirname+"/dist/standalone.js",
			minify: true
		}
	}
});*/
