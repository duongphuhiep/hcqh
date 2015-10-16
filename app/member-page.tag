<member-page>
	<rg-loading show="{ loading }" spinner="true">
		<span data-i18n="Loading"></span>
	</rg-loading>

	<div class="container">
		<div class="row">
			<h1 class="page-header" data-i18n="Members"></h1>
		</div>
		<div class="row">
			<div each={ members }  class="col-lg-3 col-md-4 col-sm-6 col-xs-12 thumb">
				<div class="thumbnail fadeInOnScroll">
					<!--<img class="img-responsive limitedSize animated bounceIn" src={ this.image }>-->
					<!--<img class={img-responsive:true, limitedSize:true, animated:true, animation:true} src={ this.image }>-->
					<img class={ this.cssClazz } src={ this.image }>
				</div>
			</div>
		</div>
	</div>

	<style scoped>
		.limitedSize {
			max-width: 400px;
			max-height: 300px;
		}
		.thumbnail {
			height: 300px;
		}
	</style>

	<script>
		var _this = this;
		var RiotControl = require("RiotControl");
		RiotControl.addStore(_this);
		var riot = require("riot");
		var $ = require("jquery");
		var Route = require("./route");
		var i18n = require("i18next");
		var Utils = require("./utils");
		var Mixins = require('./mixins');
		_this.mixin(Mixins.LoadingMixin);

		/**
		 * Load config from member/config.txt to _this.members
		 */
		function load() {
			_this.showLoading();

			$.ajax({
				url: Route.pathToMemberFolder+'config.txt',
				dataType: 'text'
			}).done(function (rawMembersConfig) {
				_this.members = parseMembersConfig(rawMembersConfig);
			}).fail(function (error) {
				console.error(error);
				Route.switchToPage("404");
			}).always(function() {
				_this.hideLoading();
			});
		}

		/**
		 * Parse member/config.txt. Return an array like following:
		 * [
		 * 	{image: "content/members/hiep.jpg"},
		 * 	{image: "content/members/nganha.jpg"},
		 * ]
		 * @param rawMembersConfig: string
		 */
		function parseMembersConfig(rawMembersConfig) {
			var data = [];
			$.each(rawMembersConfig.split('--'), function(index, item) {
				var imgConfig = Utils.parseConfig(item);
				if (imgConfig.image) {
					imgConfig.image = Route.pathToMemberFolder + imgConfig.image;
					imgConfig.cssClazz = "img-responsive limitedSize animated "+randomAnimation();
					data.push(imgConfig);
				}
			});
			return data;
		}

		/**
		 * return a random entrance animation of animaite.css
		 * @returns {string}
		 */
		function randomAnimation() {
			var entrancesAnimation = [
				"slideInUp",
				"slideInDown",
				"slideInLeft",
				"slideInRight"
			];
			return entrancesAnimation[Math.floor(Math.random()*entrancesAnimation.length)];
		}

		_this.on('mount languageChange', function() {
			$(function() { $(_this.root).i18n(); });
		});

		_this.on('mount', function() {
			load();
		});

	</script>
</member-page>
