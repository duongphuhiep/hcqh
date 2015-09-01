/**
Responsive navigation bar
*/
<navbar-search>
	<nav class="navbar navbar-inverse navbar-fixed-top">
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
				<li class={ active: pageName == 'repertoire' }><a href="#repertoire" data-i18n="Repertoire"></a></li>
				<li class={ active: pageName == 'member' }><a href="#member" data-i18n="Members"></a></li>
				<li class={ active: pageName == 'blog' || pageName == 'post' }><a href="#blog" data-i18n="Blog"></a></li>
				<li class={ active: pageName == 'contact' }><a href="#contact" data-i18n="Contact"></a></li>
			</ul>
	
			<!--search panel on small screen-->
			 <!--div class="visible-xs-block">
				<form class="navbar-form" role="search">
					<div class="input-group">
						<input type="text" class="form-control" data-i18n="[placeholder]Search" name="srch-term" id="srch-term">
						<div class="input-group-btn" >
							<button class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
						</div>
					</div>
				</form>
			</div-->

			<!--search button toggle the search panel, only visible on large screen-->
			<!--button class="hidden-xs btn btn-default pull-right" data-toggle="collapse" data-target="#search-panel"><i class="glyphicon glyphicon-search"></i></button-->
		</div>
		</div>
	</nav>
	<!--div class="hidden-xs collapse" id="search-panel">
		<form role="search">
			<input type="text" class="form-control" data-i18n="[placeholder]Search" name="srch-term" id="srch-term">
		</form>
	</div-->

	<style scoped>
		/* 
		//Make navbar transparent before scroll
		.navbar {
			-webkit-transition: all 0.6s ease-out;
		    -moz-transition: all 0.6s ease-out;
		    -o-transition: all 0.6s ease-out;
		    -ms-transition: all 0.6s ease-out;
		    transition: all 0.6s ease-out;
		}
		
		.navbar.transparent {
		    		background: rgba(0, 0, 0, 0.2); 
		    		border: none;
		    		text-color: ;
		} */
	</style>

	<script>
		var _this = this;
		var RiotControl = require("RiotControl");
		RiotControl.addStore(_this);

		_this.on('pageChange', function(e) {
			_this.pageName = e.pageName;
			_this.update();
		});

		var Route = require("../app/route");
		_this.pageName = Route.getCurrentPageInfo().pageName;

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

		// navbar become solid on scroll
		// $(window).scroll(function() {
		//     if ($(".navbar").offset().top <= 50) {
		//         $(".navbar-fixed-top").addClass("transparent");
		//     } else {
		//         $(".navbar-fixed-top").removeClass("transparent");
		//     }
		// });

	</script>
</navbar-search>