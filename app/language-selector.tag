<language-selector>
	<select class="animated fadeInDownBig" id="language_selector" onchange={ changeLanguageFromComboBox }>
	    <option value="vi">Tiếng Việt</option>
		<option value="fr">Française</option>
		<option value="en">English</option>
	</select>

	<style>
		#language_selector {
			position: fixed;
			bottom: 0;
			right: 0;
			z-index: 2000;
		}
	</style>

	<script>

	var _this = this;
	var Lang = require('./lang');
	var RiotControl = require("RiotControl");
	RiotControl.addStore(_this);

	//listen to other request to change language
	_this.on("mount languageChange", function() {
		_this.language_selector.value = Lang.getCurrentLanguage();
	});

	_this.changeLanguageFromComboBox = function() {
		var lang = _this.language_selector.value; //vi, fr, en
		Lang.setLanguage(lang);
	};

	</script>
</language-selector>
