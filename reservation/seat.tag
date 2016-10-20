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
		<div class="col-sm-10"><input class="form-control" id={'email'+opts.num} name={'email'+opts.num} required={opts.num==1} /></div>
	</div>
</seat>
