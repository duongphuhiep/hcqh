<reservation-form>
	<div class='alert alert-warning'>
		It's better to make reservation by group so that we'll try our best to make you sit together. How many person are there in your group?
	</div>
	<form class='form-horizontal' onsubmit='return validateForm()' action='http://localhost/dev/lognotif/' method='post'>
		<div class='form-group'>
			<label class='control-label col-sm-2' for='seatCount'>Nombre de places</label>
			<div class="col-sm-10"><select class="form-control" id='seatCount' onchange={update} required>
				<option value='1'>1 - Je suis tout seul</option>
				<option value='2'>2 personnes</option>
				<option value='3'>3 personnes</option>
				<option value='4'>4 personnes</option>
			</select></div>
		</div>

		<h2>Réservez votre place</h2>
		<span class="label label-info">Info</span> Nous ne communiquons jamais vos informations aux autres personnes, nous allons même les supprimers après le jour de la concert.
		<div each={i in this.seatNumbers(this.seatCount.value)}>
			<seat num={i}></seat>
		</div>

		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<button type="submit" class="btn btn-primary center-block" style="min-width:120px">Submit</button>
			</div>
		</div>
	</form>

	/*return array [1,2,3,..,n]*/
	seatNumbers(n) {
		var ss = [];
		for (var i=1; i<=n; i++) {
			ss.push(i);
		}
		return ss;
	}
</reservation-form>
