
"use strict";

(function () {
	window.DEBUG = false;

    //This line will automaticly removed when compiling to the prod package
    //require("./backend_mock/fake-backend"); //it will switch window.DEBUG to true
    var riot = require("riot");


	require("i18next");
	//require("jquery-i18next");

	require("./route");
	require("./home-page.tag");
	require("./member-page.tag");
	require("./blog-page.tag");
	require("./post-page.tag");
	require("./contact-page.tag");
	require("./page-404.tag");
	require("./banner-carousel.tag");
	require("./rg-loading.tag");
	require("./asyn-img.tag");
	require("./post-excerpt.tag");
	require("./blog-list.tag");

	require("./navbar-search.tag");
	require("./content-route.tag");
	require("./language-selector.tag");

    riot.mount('*');
}());
