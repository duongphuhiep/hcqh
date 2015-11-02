/*
Display a blog post
route:
	/post/2015-20-18%20Bai%20viet%2001 should render the markdown in the folder "/content/blog/Bai viet 01/vi.md"

The content of vi.md is usually in vietnames, but it might be in other language as well (eg: italien), in this case
the post header (post meta-data) will have the property "language: it". The translationFound properties of this
component is calculated base on the language meta-data or by the markdown file.
*/
<post-page>
	<rg-loading show="{ loading }" spinner="true">
		<span data-i18n="Loading"></span>
	</rg-loading>

	<div class="container">

		<div hide={ translationFound } class="alert alert-danger" role="alert" data-i18n="Translation not found"></div>
		<div hide={ statusIsComplete } class="alert alert-danger" role="alert">{ head.status } - <span data-i18n="The post status is not completed"></span></div>

		<!-- Page Heading -->
		<h1>{ head.title }</h1>

		<!-- Date/Time -->
		<p>
			<i class="glyphicon glyphicon-time"></i> { publish } by <em>{ head.author }</em>
		</p>

		<!-- Preview Image
		<img class="img-responsive" src="http://placehold.it/900x300" alt="">
		-->

		<!-- Post Content -->
		<div id="post_content"></div>

	</div>

	<script>
		var _this = this;
		var RiotControl = require("RiotControl");
		RiotControl.addStore(_this);

		var i18n = require("i18next");
		var $ = require("jquery");
		var Route = require("./route");
		var Lang = require("./lang");
		var debounce = require("lodash.debounce");
		var Utils = require("./utils");
		var Markdown = require("./markdown");

		var Mixins = require('./mixins');
		_this.mixin(Mixins.LoadingMixin);

		_this.on("mount pageChange languageChange", function(type) {
			var routeInfo = Route.getCurrentPageInfo();
			if (routeInfo.pageName !== "post") {
				return; //not concern
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
			//extract the postId from route
			var postId;
			var routeInfo = Route.getCurrentPageInfo();
			postId = routeInfo.params[0];
			if (!postId) {
				console.error("postId not found");
				Route.switchToPage("404");
				return;
			}
			load(postId, Lang.getCurrentLanguage())
		}

		function load(postId, lang) {
			if (postId===_this.loadedPostId && lang===_this.loadedLang) {
				return;
			}

			_this.showLoading();

			var postFolderPath = Route.pathToBlogFolder + postId + "/";
			_this.postFolderPath = postFolderPath;
			_this.publish = getPublishDate(postId);
			//console.log(postFolderPath);

			//load the post in the current language
			$.ajax({
				url: postFolderPath + lang + ".md",
				dataType: 'text'
			}).done(function (data) {
				_this.loadMarkDown(data);
				_this.loadedPostId = postId;
				_this.loadedLang = _this.head["lang"] ? _this.head["lang"] : lang;
				_this.translationFound = _this.loadedLang===lang;
				_this.hideLoading();
			}).fail(function (error) {
				//console.info("fallback to vi.md", error);
				_this.showLoading();
				$.ajax({
					url: postFolderPath + "vi.md",
					dataType: 'text'
				}).done(function (data) {
					_this.loadMarkDown(data);
					_this.loadedPostId = postId;
					_this.loadedLang = _this.head["lang"] ? _this.head["lang"] : 'vi';
					_this.translationFound = _this.loadedLang===lang;
					_this.hideLoading();
				}).fail(function (error) {
					_this.loadedPostId = null;
					_this.loadedLang = null;
					Route.switchToPage("404");
				});
			});

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

		/**
		 * load the markdown content:
		 * - parse the header meta-data to _this.head
		 * - convert the markdown to HTML in the div post_content
		 */
		_this.loadMarkDown = function(content) {
			var md = Markdown.process(content, _this.postFolderPath);
			if (md.header) {
				_this.head = Utils.parseConfig(md.header);
				_this.statusIsComplete = true;
				if (_this.head.status && _this.head.status !== 'completed') {
					_this.statusIsComplete = false;
				}
			}
			else {
				console.warn("No header detected on ", Route.getCurrentPageInfo());
			}
			_this.post_content.innerHTML = md.html;
		};

		/**
		 * return the publish date from the postId
		 * @param postId: string eg: "2014-02-28 Tap viet markdown"
		 */
		function getPublishDate(postId) {
			return postId.substr(0, 10);
		}
	</script>

</post-page>
