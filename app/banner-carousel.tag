<banner-carousel>
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li each="{ bannerItems }" data-target="#myCarousel" data-slide-to={ this.index } class={ active : this.index==0 }></li>
        </ol>
        <div class="carousel-inner" role="listbox">
            <div each={ bannerItems } class={ item : true, active : index==0 }>
				<asyn-img src={linkToImage(this.image)} width=1920 height=600 />
                <div if={ title || body || post } class="carousel-caption">
                    <h1 if={ title } class="transparent-background animated fadeInDownBig">{ title }</h1>
                    <div if={ body } class="transparent-background">{ body }</div>
                    <div if={ post } class="learnmore"><a class="btn btn-lg btn-primary animated fadeInUp" href={ linkToPost(this.post) } target={ openTarget(this.post) } role="button" data-i18n="Learn more"></a></div>
                </div>
            </div>

        </div>
        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

  <style scoped>
      .transparent-background {
          background-color: rgba(0,0,0,0.5);
      }
      .carousel-caption>h1 {
          margin:0px;
          padding-bottom: 20px;
      }
      .learnmore {
          margin: 10px;
      }
  </style>

    <script>
        var _this = this;
        var RiotControl = require("RiotControl");
        RiotControl.addStore(_this);

        var Route = require('./route');
        var $ = require("jquery");
        var Utils = require("./utils");
        var i18n = require("i18next");

        $.ajax({
            url:Route.pathToBannerFolder+"config.txt",
            datatype:"text"
        }).success(function(data){
			var withoutComment = Utils.stripBracesComments(data);
            var rawItems = withoutComment.split("--");
            var bannerItems = [];
            var count = 0;
            $.each(rawItems, function( index, rawItem ) {
                var bannerItem = Utils.parseConfig(rawItem);
                if (bannerItem["image"]) {
                    bannerItem.index = count;
                    bannerItems.push(bannerItem);
                    count++;
                }
            });
            _this.bannerItems = bannerItems;
            _this.update();
        });

        _this.on('pageChange', function() {
            if (Route.getCurrentPageInfo().pageName !== "home") {
                return;
            }
            $(function() { $(_this.root).i18n(); });
        });

        linkToImage(image) {
            return Route.pathToBannerFolder + image;
        }

        linkToPost(post) {
			if (post.indexOf('/') >= 0) {
				return post;
			}
			return '#post/' + post;
        }

		/**
		 * http://www.w3schools.com/tags/att_a_target.asp
		 */
		openTarget(post) {
			if (post.indexOf('/') >= 0) {
				return '_blank'; //open in new tab
			}
			return '_self'; //open in the same tab (default)
		}
    </script>
</banner-carousel>
