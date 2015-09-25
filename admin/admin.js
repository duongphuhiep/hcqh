/*DEBUG only: remove this line on production*/
//require("../backend_mock/fake-backend");

/**
 * Common Helper, Business Log used by other elements
 */
(function(document) {
	'use strict';

	var app = document.querySelector('#app');

	app.helper = {
		/**
		 * Check if fileName is a markdown file. The fileName starts with "_" is a temp file, not markdown file
		 *
		 * @param fileName: String
		 * @return Boolean true if fileName match *.md
		 */
		isMarkdown: function(fileName) {
			return fileName.indexOf(".md", fileName.length - 3) !== -1 && !this.isTemp(fileName);
		},

		/**
		 * Check if fileName is a temporary file. The fileName starts with "_" is a temp file, not markdown file
		 *
		 * @param fileName: String
		 * @return Boolean true if fileName matches _*.*
		 */
		isTemp: function(fileName) {
			return fileName.indexOf("_") === 0;
		},

		/**
		 * Send this token along with "write nature" request (remove, rename..).
		 * So that server can check user privilege.
		 * @returns admin token
		 */
		adminToken: function() {
			var currentUser = gapi.auth2.getAuthInstance().currentUser.get();
			if (currentUser) {
				return currentUser.getAuthResponse().id_token
			}
			return null;
		},

		/*string utils*/

		endsWith: function(s, suffix) {
			return s.indexOf(suffix, s.length - suffix.length) !== -1;
		},

		startsWith: function(s, prefix) {
			return s.indexOf(prefix) === 0;
		},

		/**
		 * ("foo/", "bar") return "foo/bar"
		 * ("foo", "bar") return "foo/bar"
		 * @param p1: String
		 * @param p2: String
		 */
		joinPath: function(p1, p2) {
			return this.endsWith(p1, "/") ? p1 + p2 : p1 + "/" + p2;
		}
	}
})(document);
