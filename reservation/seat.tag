<seat>

	<h3 if={opts.num==1}>Saisir votre information</h3>
	<h3 if={opts.num!=1}>Votre compagnon <span if={opts.num > 2}>n°{opts.num-1}</span></h3>
	<div class='form-group'>
		<label class='control-label col-sm-2' for={'firstName'+opts.num}>Prénom (*)</label>
		<div class="col-sm-10"><input class="form-control" id={'firstName'+opts.num} name={'firstName'+opts.num} required /></div>
	</div>
	<div class='form-group'>
		<label class='control-label col-sm-2' for={'lastName'+opts.num}>Nom (*)</label>
		<div class="col-sm-10"><input class="form-control" id={'lastName'+opts.num} name={'lastName'+opts.num} required /></div>
	</div>
	<div class='form-group'>
		<label class='control-label col-sm-2' for={'email'+opts.num}>Email<span if={ opts.num==1 }> (*)</span></label>
		<div class="col-sm-10">
			<input class="form-control" id={'email'+opts.num} name={'email'+opts.num} required={opts.num==1} onblur={validate}/>
			<span class="error" show={ errorFormat }>email non valide. </span>
			<span class="error" show={ alreadyExist }>Une reservation est deja fait pour cet email. Veuillez utiliser un autre</span>
			<div if={ opts.num==1 }>Les address emails doivent etre distingués par personne</div>
			<div if={ opts.num!=1 }>L'email est optionel, et distingué par personne. Si vous le remplissez, alors elle doit etre different aux autres</div>
		</div>	
	</div>

	<style scoped>
		.error {
			color: red;
		}
	</style>
	<script>
		var self = this;
		validate() {
			var email = $('#email'+self.opts.num, self.root).val();
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    		self.errorFormat = email && !re.test(email);
    		self.update();

    		$.get('verifymail.php?mail=' + email, function(data) {
    			self.alreadyExist = (data === 'false');
    			self.update();
    		});
		}
	</script>
</seat>
