<asyn-img>
	<img id="img" class="img-responsive" width={opts.width} height={opts.height} />

	<style scoped>
		img {
			min-height: 150px;
			background: url(content/loading.gif) 50% no-repeat;
			/*border: 1px solid black;
			border-radius: 5px;*/
		}
	</style>

	<script>
		var _this = this;

		_this.currentSrc = _this.opts.riotSrc;
		_this.downloadingImage = new Image();
		_this.downloadingImage.onload = function(){
			//console.info("loaded ",_this.downloadingImage.src);
			_this.img.src = _this.downloadingImage.src;
		};

		_this.on("update", function() {
			if (_this.currentSrc === _this.opts.riotSrc) {
				return;
			}
			//console.info("start to load", _this.opts.riotSrc, _this.downloadingImage.src)
			_this.currentSrc = _this.opts.riotSrc;
			_this.downloadingImage.src = _this.currentSrc;
		});
	</script>

</asyn-img>
