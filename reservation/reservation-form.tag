<reservation-form>
	<div class='alert alert-warning'>
		It's better to make reservation by group so that we'll try our best to make you sit together. How many person are there in your group?
	</div>
	<form class='form-horizontal' onsubmit='return validateForm()' action='dobook.php' method='post'>
		<div class='section'>
			<div class='form-group'>
				<label class='control-label col-sm-2' for='seatCount'>Nombre de places</label>
				<div class="col-sm-10"><select class="form-control" id='seatCount' onchange={update} required>
					<option value='1'>1 - Je suis tout seul</option>
					<option value='2'>2 personnes</option>
					<option value='3'>3 personnes</option>
					<option value='4'>4 personnes</option>
				</select></div>
			</div>
		</div>
		<h2>Réservez votre place</h2>
		<span class="label label-info">Info</span> Nous ne communiquons jamais vos informations aux autres personnes, nous allons même les supprimers après le jour du concert.
		<div class='section' each={i in this.seatNumbers(this.seatCount.value)}>
			<seat num={i}></seat>
		</div>

		<div class='section'>
			<div class="form-group">
				<div class="col-sm-10 col-sm-offset-2">
					<img id="captcha" src="./securimage/securimage_show.php" alt="CAPTCHA Image" />
				</div>
				<label class='control-label col-sm-2' for='seatCount'>Que l'image dit?</label>
				<div class="col-sm-10">
					<input class="form-control" type="text" name="captcha_code" size="10" maxlength="6" />
					<a href="#" onclick="document.getElementById('captcha').src = './securimage/securimage_show.php?' + Math.random(); return false">Je ne vois pas très bien!</a>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<button type="submit" class="btn btn-primary center-block" style="min-width:120px">Réserver</button>
			</div>
		</div>
	</form>

	<style scoped>
		.section {
			background: #DCDCDC;
			padding: 10px;
			margin: 30px;
		}
	</style>

	/*return array [1,2,3,..,n]*/
	seatNumbers(n) {
		var ss = [];
		for (var i=1; i<=n; i++) {
			ss.push(i);
		}
		return ss;
	}
</reservation-form>
