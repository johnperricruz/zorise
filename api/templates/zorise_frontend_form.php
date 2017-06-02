<div class="zorise">
	<form title="zoho-form" id="zoho-form" name="zoho-form" action="<?=plugin_dir_url( __FILE__ ). '../FormController.php'?>" method="POST">
		<input type="hidden" name="id" value="zorise_frontend_form" required />
		<div class="wrap grid">
			<div class="unit w-1-2">
				<input type="text" name="fname" placeholder="Firstname*" required />
			</div>
			<div class="unit w-1-2">
				<input type="text" name="lname" placeholder="Lastname*" required />
			</div>
			<div class="unit w-1-2">
				<input type="text" name="email" placeholder="Email" />
			</div>
			<div class="unit w-1-2">
				<input type="text" name="phone" placeholder="Phone*" required />
			</div>
			<div class="unit w-1-2">	
				<!--<select name="diagnosis">
					<option selected disabled>--Diagnosis--</option>
					<option value="Arthritis">Arthritis</option>
					<option value="Bile Ductmultiple Myeloma">Bile Ductmultiple Myeloma</option>
					<option value="Bone Cancer">Bone Cancer</option>
					<option value="Brain Tumor">Brain Tumor</option>
					<option value="Bladder Cancer">Bladder Cancer</option>
					<option value="Brain Cancer">Brain Cancer</option>
					<option value="Breast Cancer">Breast Cancer</option>
					<option value="Colon Cancer">Colon Cancer</option>
					<option value="Cervical Cancer">Cervical Cancer</option>
					<option value="Colorectal Cancer">Colorectal Cancer</option>
					<option value="Diabetes">Diabetes</option>
					<option value="Endometrial Cancer">Endometrial Cancer</option>
					<option value="Esophageal Cancer">Esophageal Cancer</option>
					<option value="Gastric Cancer">Gastric Cancer</option>
					<option value="Glioblastoma">Glioblastoma</option>
					<option value="Hodgkin's Lymphoma">Hodgkin's Lymphoma</option>
					<option value="Kidney Cancer">Kidney Cancer</option>
					<option value="Laryngeal Cancer">Laryngeal Cancer</option>
					<option value="Larynx Cancer">Larynx Cancer</option>
					<option value="Leukemia">Leukemia</option>
					<option value="Liver Cirrhosis">Liver Cirrhosis</option>
					<option value="Lyme's Disease">Lyme's Disease</option>
					<option value="Lymph Node">Lymph Node</option>
					<option value="Lymphedema">Lymphedema</option>
					<option value="Lymphoma">Lymphoma</option>
					<option value="Liver Cancer">Liver Cancer</option>
					<option value="Lung Cancer">Lung Cancer</option>
					<option value="Melanoma">Melanoma</option>
					<option value="Mesothelioma">Mesothelioma</option>
					<option value="Migraine/headaches">Migraine/headaches</option>
					<option value="Not Diagnosed">Not Diagnosed</option>
					<option value="Non-hodgkin Lymphoma">Non-hodgkin Lymphoma</option>
					<option value="Not Specified">Not Specified</option>
					<option value="Oral Cancer">Oral Cancer</option>
					<option value="Ovarian Cancer">Ovarian Cancer</option>
					<option value="Prostate Cancer">Prostate Cancer</option>
					<option value="Pancreatic Cancer">Pancreatic Cancer</option>
					<option value="Prostate Cancer">Prostate Cancer</option>
					<option value="Renal Cell">Renal Cell</option>
					<option value="Sarcoma">Sarcoma</option>
					<option value="Small Cell Carcinoma">Small Cell Carcinoma</option>
					<option value="Squamous Cell">Squamous Cell</option>
					<option value="Stomach Cancer">Stomach Cancer</option>
					<option value="Skin Cancer">Skin Cancer</option>
					<option value="Small Intestine">Small Intestine Cancer</option>
					<option value="Testicular Cancer">Testicular Cancer</option>
					<option value="Thyroid Cancer">Thyroid Cancer</option>
					<option value="Unknown">Unknown</option>
					<option value="Uterine Cancer">Uterine Cancer</option>
				</select>-->
				<input type="text" name="diagnosis" placeholder="Diagnosis*" required />
			</div>
			<div class="unit w-1-2">
				<input type="text" name="company" placeholder="Company / Organization" />
			</div>
			<div class="unit w-1-1">
				<textarea required placeholder="Message*" name="message"></textarea>
			</div>
		</div>
		<br/>  
		<div align="right">
			<button>Submit</button> 
		</div>
	</form>
</div>