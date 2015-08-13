require("vendor:css-compiled/nucleus.css!");
require("vendor:css-compiled/template.css!");
require("vendor:css/slidebars.min.css!");
require("vendor:css/font-awesome.min.css!");
require("vendor:css/slideme.css!");
require("vendor:breadcrumbs.css!");
require("vendor:flags/css/flag-icon.min.css!");

require("jquery");
require("modernizr");
require("deliver");
require("slidebars");
require("jquery.slideme2");

$(function() {
	$.slidebars({
		hideControlClasses: true,
		scrollLock: true
	});

	$('#content-slide').slideme({
		arrows: true,
		autoslide: false,
		autoslideHoverStop: false,
		interval: 2000,
		loop: false,
		pagination: "numbers",
		transition : 'zoom',
		itemsForSlide: 0,
		touch: true,
		swipe: true
	});
});

var riot = require("riot");
require("gen/tags");
riot.mount('*');