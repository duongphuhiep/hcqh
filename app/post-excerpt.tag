<post-excerpt>

	<div hide={ translationFound } class="alert alert-warning" role="alert" data-i18n="Translation not found"></div>
	<h2><a href="#{ postLink }">{ title }</a></h2>

	<p>
		<i class="glyphicon glyphicon-time"></i> { publish } by <em>{ author }</em>
	</p>

	<p>{ excerpt } </p>
	<p class="text-right">
	<a class="btn btn-default" href="#{ postLink }">Read More <i class="glyphicon glyphicon-menu-right"></i></a>
	</p>
	<hr>

	<script>
		var _this = this;
		var Lang = require('../app/lang');
		var $ = require('jquery');
		var i18n = require("i18next");

		_this.reloadState = function() {
			var postId = this.publish +' '+ this.name;
			_this.postLink =  "post/"+postId;
			_this.translationFound = this.lang === Lang.getCurrentLanguage();
		}

		this.on("mount update", function() {
			_this.reloadState();
			$(function() { $(_this.root).i18n(); });
		});
	</script>
</post-excerpt>