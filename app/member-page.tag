<member-page>
	<rg-loading show="{ loading }" spinner="true">
		<span data-i18n="Loading"></span>
	</rg-loading>

	<div class="container">
		<div class="row">
			<h1 class="page-header" data-i18n="Members"></h1>
		</div>
		<div class="row">
			<div each={ members }  class="col-lg-3 col-md-4 col-xs-6 thumb limitedSize">
				<div class="thumbnail">
					<img class="img-responsive limitedSize" src={ this.image }>
				</div>
			</div>
		</div>
	</div>

	<style scoped>
		.limitedSize {
			max-width: 400px;
			max-height: 300px;
		}
	</style>

	<script>
		var _this = this;
		var RiotControl = require("RiotControl");
		RiotControl.addStore(_this);
		var riot = require("riot");
		var $ = require("jquery");
		var Route = require("../app/route");
		var i18n = require("i18next");
		var Utils = require("../lib/utils");
		var Mixins = require('../app/mixins');
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
				console.log(error);
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
					data.push(imgConfig);
				}
			});
			return data;
		}

		_this.on('mount languageChange', function() {
			$(function() { $(_this.root).i18n(); });
		});

		_this.on('mount', function() {
			load();
		});

	</script>
</member-page>