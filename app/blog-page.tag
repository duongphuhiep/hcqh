/*
Display a page blog
possible route:
	blog/ must to render data from blog.php?page=1
	blog/1 must to render data from blog.php?page=1
	blog/2 must to render data from blog.php?page=2
*/
<blog-page>
	<rg-loading show="{ loading }" spinner="true">
		<span data-i18n="Loading">Đang tải</span>
	</rg-loading>

	 <!-- Page Content -->
    <div class="container">

        <!-- Page Heading/Breadcrumbs -->
        <div class="row">
			<h1 class="page-header" data-i18n="Blog">Bài viết</h1>
        </div>

        <!-- /.row -->

        <div class="row">

			<div class="col-lg-8">
				<post-excerpt each={ data.posts }></post-excerpt>

				<!-- Pager -->
				<ul class="pager">
					<li hide={ data.page <= 1 } class='previous'><span onclick={previousClick}>&larr; <span data-i18n='Newer'></span></span></li>
					<li hide={ data.page >= data.totalpages } class='next'><span onclick={nextClick}><span data-i18n='Older'></span> &rarr;</span></li>
				</ul>
			</div>

			<div class="col-lg-4">
				<blog-list></blog-list>
			</div>
        </div>

        <!-- /.row -->
    </div>
    <!-- /.container -->

	<style>
		.previous, .next {
			cursor: pointer;
		}
	</style>

	<script>
		//require('./rg-loading.tag');
		//require('./blog-list.tag');
		//require('./post-excerpt.tag');

		var _this = this;
		var RiotControl = require("RiotControl");
		RiotControl.addStore(_this);

		var riot = require("riot");
		//var i18n = require("i18next");
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
				console.error(error);
				Route.switchToPage("404");
			}).always(function() {
				_this.hideLoading();
			});
		};

		nextClick() {
			var newRoute = "blog/"+ (_this.data.page + 1);
			riot.route(newRoute);
			window.scrollTo(0, 0);
		};

		previousClick() {
			var newRoute = "blog/"+ (_this.data.page - 1);
			riot.route(newRoute);
			window.scrollTo(0, 0);
		};


		var Mixins = require('./mixins');
		_this.mixin(Mixins.LoadingMixin);

	</script>
</blog-page>
