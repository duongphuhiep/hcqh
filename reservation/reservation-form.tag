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

		<seat num="1" required='true'></seat>
		<seat num="2" class={invisible:this.seatCount.value<2} required={this.seatCount.value>=2}></seat>
		<seat num="3" class={invisible:this.seatCount.value<3} required={this.seatCount.value>=3}></seat>
		<seat num="4" class={invisible:this.seatCount.value<4} required={this.seatCount.value>=4}></seat>

		<div class="form-group">
			<div class="col-sm-10 center-block">
			<button type="submit" class="btn btn-primary center-block" style="min-width:120px">Submit</button>
			</div>
		</div>
	</form>


	<style scoped>
		.invisible {
			display: none;
		}
	</style>


</reservation-form>
