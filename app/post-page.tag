/*
Display a blog post
route:
	/post/2015-20-18%20Bai%20viet%2001 should render the markdown in the folder "/content/blog/Bai viet 01/vi.md"
*/
<post-page>
	<rg-loading show="{ loading }" spinner="true">
		<span data-i18n="Loading"></span>
	</rg-loading>

	<div class="container">

		<div hide={ translationFound } class="alert alert-warning" role="alert" data-i18n="Translation not found"></div>

		<!-- Page Heading -->
		<h1>{ head.title }</h1>

		<!-- Date/Time -->
		<p>
			<i class="glyphicon glyphicon-time"></i> { head.publish } by <em>{ head.author }</em>
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
		var commonmark = require("commonmark");
		var Route = require("../app/route");
		var Lang = require("../app/lang");

		_this.reader = new commonmark.Parser();
		_this.writer = new commonmark.HtmlRenderer();

		_this.reloadState = function() {
			//extract the postId from route
			var postId;
			var routeInfo = Route.getCurrentPageInfo();
			if (routeInfo.pageName !== "post") {
				return; //not concern
			}
			postId =  routeInfo.params[0];
			if (!postId) {
				riot.route("404");
				return;
			}

			_this.loading = true; _this.update();

			var postFolderPath = Route.pathToBlogFolder + postId + "/";

			console.log(postFolderPath);

			//load the post in the current language
			$.ajax({
				url: postFolderPath + Lang.getCurrentLanguage() + ".md",
				dataType: 'text'
			}).done(function (data) {
				_this.markItDown(data);
				_this.translationFound = true;
				_this.loading = false; _this.update();
			}).fail(function (error) {
				console.log("fallback to vi");
				_this.translationFound = false;
				_this.loading = true; _this.update();
				$.ajax({
					url: postFolderPath + "vi.md",
					dataType: 'text'
				}).done(function (data) {
					_this.markItDown(data);
					_this.loading = false; _this.update();
				}).fail(function (error) {
					riot.route("404");
				});
			});
		};

		_this.on("mount pageChange languageChange", function(type) {
			//console.log(type);
			_this.reloadState();
			$(_this.root).i18n();
		});

		_this.markItDown = function(content) {
			var parsed = _this.reader.parse(content);
			var rawHead = parsed.firstChild.literal;
			_this.head = parseHead(rawHead);
			_this.post_content.innerHTML = _this.writer.render(parsed);
		};

		function parseHead(rawHead) {
			var head = {};
			var items = rawHead.split("\n");
			$.each(items, function( index, value ) {
				var separatorPos = value.indexOf(":");
				var k = value.substr(0, separatorPos).trim();
				var v = value.substr(separatorPos+1).trim();
				if (k) {
					head[k] = v;
				}
			});
			return head;
		};
	</script>

</post-page>