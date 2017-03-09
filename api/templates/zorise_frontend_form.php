<form action="<?=plugin_dir_url( __FILE__ ). '../FormController.php'?>" method="POST">
	<input type="hidden" name="id" value="zorise_frontend_form" required />
	<div class="wrap grid">
		<div class="unit w-1-2">
			<input type="text" name="fname" placeholder="Firstname" required />
		</div>
		<div class="unit w-1-2">
			<input type="text" name="lname" placeholder="Lastname" required />
		</div>
		<div class="unit w-1-2">
			<input type="text" name="email" placeholder="Email" required />
		</div>
		<div class="unit w-1-2">
			<input type="text" name="phone" placeholder="Phone" required />
		</div>
		<div class="unit w-1-2">	
			<label>Diagnosis</label><br/>
			<select name="diagnosis">
				<option selected disabled>--SELECT--</option>
				<option value="cancer, breast">Breast Cancer</option>
				<option value="cancer, melanoma">Skin Cancer (Melanoma)</option>
			</select>
		</div>
		<div class="unit w-1-2">
			<input type="text" name="company" placeholder="Company / Organization" />
		</div>
		<div class="unit w-1-1">
			<textarea name="message"></textarea>
		</div>
	</div>
	<br/>
	<div align="right">
		<button>Submit</button> 
	</div>
</form>