<post-excerpt>

	<div hide={ translationFound } class="alert alert-warning" role="alert" data-i18n="Translation not found"></div>
	<h2><a href="#{ postLink }">{ title }</a></h2>

	<p>
		<i class="glyphicon glyphicon-time"></i> { publishString } by <em>{ author }</em>
	</p>

	<p class="animated fadeInDown">{ excerpt } </p>
	<p class="text-right">
	<a class="btn btn-default animated fadeInUp" href="#{ postLink }"><span data-i18n="Read More"></span> <i class="glyphicon glyphicon-menu-right"></i></a>
	</p>
	<hr>

	<script>
		var _this = this;
		var Lang = require('./lang');
		var $ = require('jquery');
		var i18n = require("i18next");
		var moment = require("moment");


		this.getMomentString = function(date) {
			var momentDate = moment(date);
			//return momentDate.format('ll') + ' ('+momentDate.fromNow()+')'
			return date + ' ('+momentDate.fromNow()+')'
		};

		_this.reloadState = function() {
			var postId = this.publish +' '+ this.name;
			_this.postLink =  "post/"+postId;
			_this.translationFound = this.lang === Lang.getCurrentLanguage();
			_this.publishString = _this.getMomentString(_this.publish);

		};

		this.on("languageChange", function() {
			_this.publishString = _this.getMomentString(_this.publish);
		});

		this.on("mount update", function() {
			_this.reloadState();
			$(function() { $(_this.root).i18n(); });
		});
	</script>
</post-excerpt>
