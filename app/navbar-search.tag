/**
Responsive navigation bar
*/
<navbar-search>
	<!--<nav class="navbar navbar-fixed-top">-->
	<nav class={ navbar:true, navbar-inverse:true, navbar-fixed-top:true }>
		<div class ="container">
			<div class ="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<!--make logo bigger-->
				<a class="navbar-brand" style="padding:0px" href="#">
					<img alt="Brand" src="content/logo.svg">
				</a>
				<a class="navbar-brand" href="#">
					<span data-i18n="Que Huong Chorale"></span>
				</a>
			</div>

			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav navbar-right">
					<li class={ active: pageName == 'home' }><a href="#" data-i18n="Home"></a></li>
					<li class={ active: pageName == 'member' }><a href="#member" data-i18n="Members"></a></li>
					<li class={ active: pageName == 'blog' || pageName == 'post' }><a href="#blog" data-i18n="Blog"></a></li>
					<li class={ active: pageName == 'contact' }><a href="#contact" data-i18n="Contact"></a></li>
				</ul>
			</div>
		</div>
	</nav>

	<script>
		var _this = this;
		var RiotControl = require("RiotControl");
		RiotControl.addStore(_this);

		var Route = require("./route");
		var $ = require("jquery");
		var i18n = require("i18next");

		_this.on('mount', function() {
			_this.pageName = Route.getCurrentPageInfo().pageName;
			$(function() { $(_this.root).i18n(); });
		});
		_this.on('pageChange', function() {
			_this.pageName = Route.getCurrentPageInfo().pageName;
			_this.update();
		});
		_this.on('languageChange', function() {
			$(function() { $(_this.root).i18n(); });
		});

		$(function () {
		    /* hide nav bar when click outside*/
		    $(document).click(function (event) {
		        var clickover = $(event.target);
		        var _opened = $(".navbar-collapse").hasClass("collapse in");
		        if (_opened === true && !clickover.hasClass("navbar-toggle") && !clickover.is('input')) {
		            $("button.navbar-toggle").click();
		        }
		    });
		});
	</script>
</navbar-search>
