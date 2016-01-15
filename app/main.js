
"use strict";

(function () {
	window.DEBUG = false;

    //This line will automaticly removed when compiling to the prod package
    require("./backend_mock/fake-backend"); //it will switch window.DEBUG to true
    var riot = require("riot");

	require("./route");
	require("./home-page");
	require("./member-page");
	require("./blog-page");
	require("./post-page");
	require("./contact-page");
	require("./page-404");
	require("./banner-carousel");
	require("./rg-loading");
	require("./asyn-img");
	require("./post-excerpt");
	require("./navbar-search");
	require("./language-selector");
	require("./content-route");

    riot.mount('*');
}());
