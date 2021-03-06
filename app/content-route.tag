/* use router API to display the content */
<content-route>
	<home-page if={ pageName == 'home' }></home-page>
	<member-page if={ pageName == 'member' }></member-page>
	<blog-page if={ pageName == 'blog' }></blog-page>
	<post-page if={ pageName == 'post' }></post-page>
	<contact-page if={ pageName == 'contact' }></contact-page>
	<page-404 if={ pageName == '404' }></page-404>

	<script>
		//require("./home-page.tag");
		//require("./member-page.tag");
		//require("./blog-page.tag");
		//require("./post-page.tag");
		//require("./contact-page.tag");
		//require("./page-404.tag");

		var _this = this;
		var RiotControl = require("RiotControl");
		RiotControl.addStore(_this);

		_this.on('pageChange', function(e) {
			if (_this.pageName === e.pageName) { return; }
			_this.pageName = e.pageName;
			_this.update();
		});

		var Route = require("./route");
		_this.pageName = Route.getCurrentPageInfo().pageName;
	</script>
</content-route>

