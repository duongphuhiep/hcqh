<banner-carousel>

    <!--<div id="myCarousel" class="carousel slide" data-ride="carousel">
        &lt;!&ndash; Indicators &ndash;&gt;
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <img class="first-slide" src="content/home/banner/image1.jpg" alt="First slide">
                <div class="carousel-caption">
                    <h1>Example headline.</h1>
                    &lt;!&ndash; <p>Note: If you're viewing this page via a <code>file://</code> URL, the "next" and "previous" Glyphicon
                    buttons on the left and right might not load/display properly due to web browser security rules.</p> &ndash;&gt;
                    &lt;!&ndash; <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p> &ndash;&gt;
                </div>
            </div>
            <div class="item">
                <img class="second-slide" src="content/home/banner/image2.jpg" alt="Second slide">
                <div class="carousel-caption">
                    <h1>Another example headline.</h1>
                    <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at
                        eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                    &lt;!&ndash; <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p> &ndash;&gt;
                </div>
            </div>

            <div class="item">
                <img class="third-slide" src="content/home/banner/image3.jpg" alt="Third slide">
                <div class="carousel-caption">
                    <h1>One more for good measure.</h1>
                    <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at
                        eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                    &lt;!&ndash; <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p> &ndash;&gt;
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

    </div>-->


              <!--<ol class="carousel-indicators">
                  <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                  <li data-target="#myCarousel" data-slide-to="1" class=""></li>
                  <li data-target="#myCarousel" data-slide-to="2" class=""></li>
                  <li data-target="#myCarousel" data-slide-to="3" class=""></li>
                  <li data-target="#myCarousel" data-slide-to="4" class=""></li>
                  <li data-target="#myCarousel" data-slide-to="5" class=""></li>
                  <li data-target="#myCarousel" data-slide-to="6" class=""></li>
                  <li data-target="#myCarousel" data-slide-to="7" class=""></li>
                  &lt;!&ndash;riot placeholder&ndash;&gt; </ol>

              <div class="carousel-inner" role="listbox">
                      <div class="item active"><img src="backend_mock/home/banner/piano-banner.jpg">

                          <div class="carousel-caption"><h1>Mái nhà thứ hai</h1>

                              <p>Tình bạn xây dựng trên niềm đam mê Âm nhạc</p></div>
                      </div>
                      <div class="item"><img src="backend_mock/home/banner/2014-spring-show.jpg">

                          <div class="carousel-caption"><h1>23 Janvier 2016 à Eglise La Madeleine</h1>

                              <p>Eglise La Madeleine, Rencontres internationales Chant choral de Paris, organisé par
                                  l’Association Music &amp; Friends</p>

                              <p><a class="btn btn-lg btn-primary" role="button"
                                    href="#post/2009-08-04 Bai viet 04"></a></p></div>
                      </div>
                      <div class="item"><img src="backend_mock/home/banner/prochoir-1920x600.jpg">

                          <div class="carousel-caption"><h1>23/01/2016 nhà thờ Madeleine</h1>

                              <p><a class="btn btn-lg btn-primary" role="button"
                                    href="#post/2009-08-04 Bai viet 04"></a></p></div>
                      </div>
                      <div class="item"><img src="backend_mock/home/banner/music.jpg">

                          <div class="carousel-caption"><p>Eglise La Madeleine, Rencontres internationales Chant choral
                              de Paris, organisé par l’Association Music &amp; Friends</p>

                              <p><a class="btn btn-lg btn-primary" role="button"
                                    href="#post/2009-08-04 Bai viet 04"></a></p></div>
                      </div>
                      <div class="item"><img src="backend_mock/home/banner/CCC-8991-1920x600.jpg">

                          <div class="carousel-caption"><p><a class="btn btn-lg btn-primary" role="button"
                                                              href="#post/2009-08-04 Bai viet 03">Xem bài viết Tiếng
                              Nhật</a></p></div>
                      </div>
                      <div class="item"><img src="backend_mock/home/banner/web.jpg">

                          <div class="carousel-caption"></div>
                      </div>
                      <div class="item"><img src="backend_mock/home/banner/about_in5.jpg">

                          <div class="carousel-caption"></div>
                      </div>
                      <div class="item"><img src="backend_mock/home/banner/slide_1.png">

                          <div class="carousel-caption"></div>
                      </div>
              </div>

              -->

    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li each="{ bannerItems }" data-target="#myCarousel" data-slide-to={ this.index } class={ active : this.index==0 }></li>
        </ol>
        <div class="carousel-inner" role="listbox">
            <!--<banner-carousel-item each="{ bannerItems }"></banner-carousel-item>-->

            <div each={ bannerItems } class={ item : true, active : index==0 }>
                <img src={ linkToImage(this.image) }>
                <div if={ title || body || post } class="carousel-caption">
                    <h1 if={ title } class="transparent-background">{ title }</h1>
                    <p if={ body } class="transparent-background">{ body }</p>
                    <p if="{ post }"><a class="btn btn-lg btn-primary" href={ linkToPost(this.post) } role="button" data-i18n="Learn more"></a></p>
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
  </style>

    <script>
        var _this = this;
        var RiotControl = require("RiotControl");
        RiotControl.addStore(_this);

        var Route = require('../app/route');
        var $ = require("jquery");
        var Utils = require("../lib/utils");
        var i18n = require("i18next");

        $.ajax({
            url:Route.pathToBannerFolder+"text.txt",
            datatype:"text"
        }).success(function(data){
            var rawItems = data.split("--");
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
            return '#post/' + post;
        }
    </script>
</banner-carousel>