/*
Display a page blog
possible route:
	blog/ must to render data from blog.php?page=1
	blog/1 must to render data from blog.php?page=1
	blog/2 must to render data from blog.php?page=2
*/
<blog-page>
	<rg-loading show="{ loading }" spinner="true">
		<span data-i18n="Loading"></span>
	</rg-loading>

	 <!-- Page Content -->
    <div class="container">

        <!-- Page Heading/Breadcrumbs -->
        <div class="row">
			<h1 class="page-header" data-i18n="Blog"></h1>
        </div>

        <!-- /.row -->

        <div class="row">
			<post-excerpt each={ data.posts }></post-excerpt>
			<!-- Pager -->
			<ul class="pager">
				<li hide={ data.page <= 1 } class='previous'><a href='#blog/{ data.page - 1 }'>&larr; <span data-i18n='Newer'></span></a></li>
				<li hide={ data.page >= data.totalpages } class='next'><a href='#blog/{ data.page + 1 }'><span data-i18n='Older'></span> &rarr;</a></li>
			</ul>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->

	<script>
		var _this = this;
		var RiotControl = require("RiotControl");
		RiotControl.addStore(_this);

		var riot = require("riot");
		var i18n = require("i18next");
		var Lang = require("../app/lang");
		var Route = require("../app/route");
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
				pageNumber = currentRouteInfo.params[0];
			}
			if (!pageNumber) {
				pageNumber = 1; //fallback to 1 as default pageNumber
			}

			load(pageNumber, Lang.getCurrentLanguage());
			_this.reloadTranslation();
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
				_this.loadedPageNumber = pageNumber;
				_this.loadedLang = lang;
			}).fail(function (error) {
				console.log(error);
				Route.switchToPage("404");
			}).always(function() {
				_this.hideLoading();
			});
		}

		var Mixins = require('../app/mixins');
		_this.mixin(Mixins.LoadingMixin);

	</script>
</blog-page>