/* use router API to display the content */
<content-route>
	<home-page if={ pageName == 'home' }></home-page>
	<repertoire-page if={ pageName == 'repertoire' }></repertoire-page>
	<member-page if={ pageName == 'member' }></member-page>
	<blog-page if={ pageName == 'blog' }></blog-page>
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

		var route = require("../app/route");
		_this.pageName = route.getCurrentPageInfo().pageName;
		
	</script>
</content-route>

