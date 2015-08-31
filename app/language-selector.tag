<language-selector>
	<select id="language_selector" onchange={ changeLanguageFromComboBox }>
	    <option value="vi">Tiếng Việt</option>
		<option value="fr">Française</option>
		<option value="en">English</option>
	</select>

	<style>
		/*
		make it blink to be noticed by user,
		*/
		@-webkit-keyframes notice-me {
			0% { -webkit-transform: scale(1); }
			50% { -webkit-transform: scale(1.5); }
			100% { -webkit-transform: scale(1); }
		}
		#language_selector {
			position: fixed;
			bottom: 0;
			right: 0;
			z-index: 2000;
			-webkit-animation-name: notice-me;
			-webkit-animation-duration: 200ms;
			-webkit-transform-origin:50% 50%;
			-webkit-animation-iteration-count: 5;
			-webkit-animation-timing-function: linear;
		}
	</style>

	<script>

	var _this = this;
	var Lang = require('../app/lang');
	var RiotControl = require("RiotControl");
	RiotControl.addStore(_this);

	//listen to other request to change language
	_this.on("languageChange", function(lang) {
		_this.language_selector.value = lang;
	});

	_this.changeLanguageFromComboBox = function() {
		var lang = _this.language_selector.value; //vi, fr, en
		Lang.setLanguage(lang);
	};

	//init language
	var currentLang = Lang.getCurrentLanguage();
	Lang.setLanguage(currentLang);

	</script>
</language-selector>