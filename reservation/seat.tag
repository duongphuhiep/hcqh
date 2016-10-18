<seat>
	<hr/>
	<h3>Place {opts.num}</h3>
	<div class='form-group'>
		<label class='control-label col-sm-2' for={'firstName'+opts.num}>First Name:</label>
		<div class="col-sm-10"><input class="form-control" id={'firstName'+opts.num} name={'firstName'+opts.num} required={opts.required} /></div>
	</div>
	<div class='form-group'>
		<label class='control-label col-sm-2' for={'lastName'+opts.num}>Last Name:</label>
		<div class="col-sm-10"><input class="form-control" id={'lastName'+opts.num} name={'lastName'+opts.num} required={opts.required} /></div>
	</div>
	<div class='form-group'>
		<label class='control-label col-sm-2' for={'email'+opts.num}>Email:</label>
		<div class="col-sm-10"><input class="form-control" id={'email'+opts.num} name={'email'+opts.num} required={opts.required} /></div>
	</div>
</seat>
