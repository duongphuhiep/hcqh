/*
Display a page blog
possible route:
	blog/ must to render data from blog.php?page=1
	blog/1 must to render data from blog.php?page=1
	blog/2 must to render data from blog.php?page=2
*/
<blog-page>
	<rg-loading show="{ loading }" spinner="true">
		<span data-i18n="Loading"></span>
	</rg-loading>

	 <!-- Page Content -->
    <div class="container">

        <!-- Page Heading/Breadcrumbs -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Blog Home One
                    <small>Subheading</small>
                </h1>
            </div>
        </div>

        <!-- /.row -->

        <div class="row">

			<!-- Blog Entries Column -->
			<div class="col-md-8">
				Page { data.page }, count { data.count }. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.
				<post-excerpt each={ data.posts }></post-excerpt>
				<!-- Pager -->
				<ul class="pager">
					<li hide={ data.page <= 1 } class='previous'><a href='#blog/{ data.page - 1 }'>&larr; <span data-i18n='Newer'></span></a></li>
					<li hide={ data.page >= data.totalpages } class='next'><a href='#blog/{ data.page + 1 }'><span data-i18n='Older'></span> &rarr;</a></li>
				</ul>
			</div>

            <!-- Blog Sidebar Widgets Column -->
			<div class="col-md-4">
				<div class="well">
					<h4>Recent posts</h4>
					<ul>
						<li><a href="#"> Lorem ipsum dolor sit amet, consectetuer adipiscing elit</a></li>
						<li><a href="#"> Lorem ipsum dolor sit amet, consectetuer adipiscing elit</a></li>
						<li><a href="#"> Lorem ipsum dolor sit amet, consectetuer adipiscing elit</a></li>
						<li><a href="#"> Lorem ipsum dolor sit amet, consectetuer adipiscing elit</a></li>
					</ul>
				</div>
				<div class="well">
					<h4>Archive</h4>
					<ul>
						<li><a href="#">April 2010</a></li>
						<li><a href="#">April 2010</a></li>
						<li><a href="#">April 2010</a></li>
						<li><a href="#">April 2010</a></li>
					</ul>
				</div>
				
			</div>

        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->

	<script>
		var _this = this;
		var RiotControl = require("RiotControl");
		RiotControl.addStore(_this);

		var i18n = require("i18next");
		var Lang = require("../app/lang");
		var Route = require("../app/route");

		_this.on("pageChange languageChange", function(type, e) {
			var currentPageInfo = Route.getCurrentPageInfo();
			if (currentPageInfo.pageName !== 'blog') {
				return; //not concerned
			}

			var pageNumber;
			if (currentPageInfo.params) {
				pageNumber = currentPageInfo.params[0];
			}
			if (!pageNumber) {
				pageNumber = 1; //fallback to 1 as default pageNumber
			}

			load(pageNumber, Lang.getCurrentLanguage());
		});

		var $ = require("jquery");
		_this.on("update", function(){
			$(_this.root).i18n();
		});

		function load(pageNumber, lang) {
			var src = 'backend/blog.php?page='+pageNumber+'&lang='+lang;

			console.log('loading '+src);

			_this.waitMsg = src;
			_this.loading = true;
			_this.update();

			var oReq = new XMLHttpRequest();
			oReq.open('get', src);
			oReq.onreadystatechange = function (e) {
				if (oReq.readyState === 4) {
					if (oReq.status === 200) {
						_this.data = JSON.parse(oReq.responseText);
					} else {
						console.log("ERROR "
							+ oReq.status
							+ " (" + oReq.statusText + "): "
							+ oReq.responseText);
					}
					_this.loading = false;
					_this.update();
				}
			};
			oReq.send();
		}

		//init like this to display the two buttons (older, newer)
		// at the first time so that they will initially translated
		//_this.data = {
		//	"page": 2,
		//	"totalpages": 3,
		//	"totalposts": 0
		//}

	</script>
</blog-page>