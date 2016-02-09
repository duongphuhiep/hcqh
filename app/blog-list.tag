/*
Display a list of blog
possible route:
	blog/ must to render data from blog.php?page=1
	blog/1 must to render data from blog.php?page=1
	blog/2 must to render data from blog.php?page=2
*/
<blog-list>
    <div class="well">
		<rg-loading show="{ loading }" spinner="true">
			<span data-i18n="Loading">Đang tải</span>
		</rg-loading>
		<h3 data-i18n="Older posts">Bài cũ hơn</h3>
		<ul>
			<li each={ data.posts }><a href={ postLink(publish, name) }>{ title }</a></li>
		</ul>
    </div>

	<style scoped>
		ul {
			padding:0;
			list-style: none;
		}
		li {
			padding-bottom: 1em;
			line-height: 1.5em;
		}
	</style>

	<script>
		var _this = this;
		var RiotControl = require("RiotControl");
		RiotControl.addStore(_this);

		var riot = require("riot");
		var i18n = require("i18next");
		var Lang = require("./lang");
		var Route = require("./route");
		var $ = require("jquery");
		var debounce = require("lodash.debounce");

		_this.on("mount pageChange languageChange", function(type, e) {
			var currentRouteInfo = Route.getCurrentPageInfo();
			if (currentRouteInfo.pageName !== 'blog') {
				return; //not concerned
			}
			_this.reloadState();
		});

		if (DEBUG && DEBUG.disableDebouncing) {
			_this.reloadState = _reloadState;
		}
		else {
			_this.reloadState = debounce(_reloadState, 200);
		}
		function _reloadState() {
			var currentRouteInfo = Route.getCurrentPageInfo();

			//Get the pageNumber from route info or defaulting to 1
			var pageNumber;
			if (currentRouteInfo.params) {
				pageNumber = Number(currentRouteInfo.params[0])+1;
			}
			if (!pageNumber) {
				pageNumber = 2; //fallback to 2 as default pageNumber
			}

			load(pageNumber, Lang.getCurrentLanguage());
			_this.reloadTranslation();
		}

		_this.postLink = function(publish, postName) {
			return '#post/'+publish+' '+postName;
		}

		_this.on('update', function() {
			_this.reloadTranslation();
		});
		if (DEBUG && DEBUG.disableDebouncing) {
			_this.reloadTranslation = _reloadTranslation;
		}
		else {
			_this.reloadTranslation = debounce(_reloadTranslation, 200);
		}
		function _reloadTranslation() {
			$(function() { $(_this.root).i18n(); });
		}

		function load(pageNumber, lang) {
			if (_this.loadedPageNumber===pageNumber && _this.loadedLang===lang) {
				return;
			}

			_this.showLoading();

			$.ajax({
				url: 'backend/blog.php?page='+pageNumber+'&lang='+lang,
				dataType: 'json'
			}).done(function (data) {
				_this.data = data;
				console.info(data);
				_this.loadedPageNumber = pageNumber;
				_this.loadedLang = lang;
			}).fail(function (error) {
				console.error(error);
				Route.switchToPage("404");
			}).always(function() {
				_this.hideLoading();
			});
		};

		var Mixins = require('./mixins');
		_this.mixin(Mixins.LoadingMixin);

	</script>
</blog-list>
