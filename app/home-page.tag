<home-page>

	<banner-carousel></banner-carousel>

	<div class="container">

		<section>
			<h1><span data-i18n="A vietnamese vocal ensemble in France"></span><br><small data-i18n="2 minutes introduction..."></small></h1>

			<!-- 16:9 aspect ratio -->
			<div class="embed-responsive embed-responsive-16by9 center-block">
			  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/uiZSZE_TD-w?cc_load_policy=1&cc_lang_pref=en" allowfullscreen></iframe>
			</div>

			<p class="btn center-block" data-toggle="collapse" data-target="#speechText">
				Cannot see the video or subtitle?
			</p>
			<div class="collapse" id="speechText">
				<div class="row">
					<div class='col-md-3 text-center'></div>
					<div class='col-md-6 well'>
						Hợp ca Quê hương ra đời vào tháng 4 năm 2009, khởi điểm chỉ với khoảng 15 thành viên cùng chung niềm đam mê âm nhạc. Dưới sự chỉ huy đầy tâm huyết của nhạc trưởng Nguyễn Ngân Hà cùng bao công sức và nỗ lực tập luyện của mỗi thành viên; Hôm nay, chúng tôi có thể tự tin đem lời ca, tiếng hát của mình góp phần phát huy và quảng bá nền âm nhạc Việt nam tới kiều bào và bạn bè quốc tế.
						<br>
						Nhưng trên tất cả, Hợp ca chúng tôi là mái nhà chung, là nơi giao lưu, gặp gỡ, của những người con xa nhà, là nơi cho chúng tôi mượn lời ca tiếng hát để thể hiện tình yêu quê hương đất nước Việt nam. Rất nhiều bản hợp xướng bất hủ như « Tiếng hát biên thùy » của Tô Hải, Du kích Sông Thao » của Đỗ Nhuận, "Ca ngợi Tổ quốc" " Hồ Bắc đã được chúng tôi dựng lại thành công trên nhiều sân khấu lớn nhỏ. Có thể nói, con đường và hành trình mà chúng tôi đã và đang bền bỉ theo đuổi được kết tinh bằng tình yêu, lòng đam mê và nhiệt huyết cháy bỏng với âm nhạc dân tộc. Đó là tinh thần quý báu mà mỗi thành viên luôn giữ trong tim. 
					</div>
					<div class='col-md-3 text-center'></div>
				</div>
			</div>


		</section>
		
		<section>
			<h1 data-i18n="We are recruiting"></h1>

			<div class="row">
				<div class='col-md-3 text-center'>
					<img src="content/home/recruit.png" alt="Recruting" width="100px" height="100px">
				</div>
				<div class='col-md-6 pad10' data-i18n="RecruitmentMessage">
				</div>
				<div class='col-md-3 text-center pad10'>
					<a class="btn btn-primary btn-lg" href="#contact" role="button" data-i18n="Contact Us"></a>
				</div>
			</div>
		</section>
			
		<section>
			<h1 data-i18n="On Facebook"></h1>
			<div class="center-block" style="max-width:500px;">
				<div class="fb-page"
					data-href="https://www.facebook.com/hopcaquehuong?fref=ts"
					data-small-header="true"
					data-width="500"
					data-adapt-container-width="true"
					data-hide-cover="false"
					data-show-facepile="true"
					data-show-posts="true"
					>
					<div class="fb-xfbml-parse-ignore">
						<blockquote cite="https://www.facebook.com/hopcaquehuong?fref=ts">
							<a href="https://www.facebook.com/hopcaquehuong?fref=ts">Hợp Ca Quê Hương</a>
						</blockquote>
					</div>
				</div>
			</div>
		</section>
	</div>

	<style scoped>
		/* make white text in the carousel more readable */
		.carousel-caption {
			text-shadow: 1px 1px 8px #000000;
		}

		section {
			padding-top: 80px;
		}

		section h1 {
			padding-bottom: 30px;
			text-align: center;
		}

		.pad10 {
			padding-top: 10px;
			padding-bottom: 10px;
		}

		p {
			padding-top: 20px;
			padding-bottom: 20px;
		}
	</style>

	<script>
		var _this = this;
		var RiotControl = require("RiotControl");
		RiotControl.addStore(_this);
		var facebook = require("../lib/facebook");

		var $ = require("jquery");
		var i18n = require("i18next");

		_this.on('mount languageChange', function() {
			$(function() { $(_this.root).i18n(); });
		});

		_this.on('mount', function(){
			facebook.init();
		});
	</script>

</home-page>