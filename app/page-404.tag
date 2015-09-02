<page-404>
	<div class="container">
		<div class="jumbotron text-center">
			<h1>404</h1>
			<p data-i18n="Sorry! Page not found"></p>
			<p><a class="btn btn-primary btn-lg" href="#" role="button" data-i18n="Return to Home"></a></p>
		</div>
	</div>
	<script>
		var _this = this;
		var RiotControl = require("RiotControl");
		RiotControl.addStore(_this);

		var $ = require("jquery");
		var i18n = require("i18next");
		var debounce = require("lodash.debounce");

		_this.on('mount languageChange pageChange', function() {
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
	</script>
</page-404>