/* use router API to display the content */
<content-route>
	<home-page if={ pageName == 'home' }></home-page>
	<member-page if={ pageName == 'member' }></member-page>
	<blog-page if={ pageName == 'blog' }></blog-page>
	<post-page if={ pageName == 'post' }></post-page>
	<contact-page if={ pageName == 'contact' }></contact-page>
	<page-404 if={ pageName == '404' }></page-404>

	<script>
		var _this = this;
		var RiotControl = require("RiotControl");
		RiotControl.addStore(_this);

		_this.on('pageChange', function(e) {
			_this.pageName = e.pageName;
			_this.update();
		});

		var Route = require("./route");
		_this.pageName = Route.getCurrentPageInfo().pageName;

		var googleAnalytics = require("./googleAnalytics");
		googleAnalytics.init();

	</script>
</content-route>

