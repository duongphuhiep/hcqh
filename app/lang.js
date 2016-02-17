/**
 * Language utilities
 * - setLanguage(lang)
 * - getCurrentLanguage()
 *
 * The language flag is store in the Cookie or from the queryString
 * Each time the language is changed with setLanguage()
 * the "languageChange" event will be triggered
 */
(function() {
	var i18n = require("i18next");
	var Cookies = require("jscookie");
	var RiotControl = require("RiotControl");
	var $ = require("jquery");
	var moment = require('moment');

	//initialization

	/**
	 * the default namespace __ns__ is 'translation' so it fetch for
	 * vi-translation.json, en-US-translation.json... from the resGetPath
	 */
	var option = { resGetPath: 'content/locales/__lng__-__ns__.json', detectLngQS: 'lang', cookieName: 'lang', fallbackLng: 'vi' };
	i18n.init(option, function(err, t) {
		//$(function() { $(document).i18n(); });
		setLanguage(getCurrentLanguage());
	});

	moment.locale(getCurrentLanguage());

	function getQueryStringParams(sParam) {
		var sPageURL = window.location.search.substring(1);
		var sURLVariables = sPageURL.split('&');

		for (var i = 0; i < sURLVariables.length; i++) {
			var sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] == sParam) {
				return sParameterName[1];
			}
		}
	}

	/**
	 * The current language comes from QueryString '?lang=en' or from the cookies, otherwise it fallback to "vi"
	 */
	function getCurrentLanguage() {
		var currentLang = getQueryStringParams('lang');
		if (!currentLang) {
			currentLang = Cookies.get('lang');
		}
		if (!currentLang || $.inArray(currentLang,["fr","en","vi"])<0) {
			return "vi";
		}
		return currentLang;
	};
	module.exports.getCurrentLanguage = getCurrentLanguage;

	/**
	 * Set the current language flag, and fire "languageChange" event
	 */
	 function setLanguage(lang) {
		if (!lang) {
			return;
		}

		var previousLang = getCurrentLanguage();

		Cookies.set('lang', lang, { expires: 365 });
		moment.locale(lang);

		$(function() {
			i18n.setLng(lang, function(err, t) {
				$(document).i18n();
			});
		});

		moment.locale(lang);

		if (previousLang !== lang) {
			RiotControl.trigger("languageChange", lang);
		}
		else {
			console.info("Ignore languageChange event");
		}
	};
	module.exports.setLanguage = setLanguage;
})();
