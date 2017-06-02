<?php
	
	/**
	 * Require Base WP functions
	 */
	 require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
	 
	/**
	 * HIGHRISE API
	 */
	require_once('HighriseAPI.class.php');

	/** 
	 * ZOHO API
	 */
	require_once 'Zoho/CRM/Common/HttpClientInterface.php';
	require_once 'Zoho/CRM/Common/FactoryInterface.php';
	require_once 'Zoho/CRM/Request/HttpClient.php';
	require_once 'Zoho/CRM/Request/Factory.php'; 
	require_once 'Zoho/CRM/Request/Response.php';
	require_once 'Zoho/CRM/ZohoClient.php';
	require_once 'Zoho/CRM/Wrapper/Element.php';
	require_once 'Zoho/CRM/Entities/Lead.php';	
	
	use Zoho\CRM\Entities\Lead;
	use Zoho\CRM\ZohoClient;
	
	class FormController{
		
		protected $post;
		protected $highrise;
		protected $zoho; 
		protected $lead; 
		protected $cfdb;
		
		public function __construct(){		
			$highrise = new HighriseAPI();
			$highrise->setAccount(get_option('highrise_email'));
			$highrise->setToken(get_option('highrise_token'));	
			
			$lead = new Lead();
			$zoho = new ZohoClient(get_option('zoho_token'));			

			if($_POST){
				$this->post = $_POST;
				$this->highrise = $highrise;
				$this->lead = $lead;
				$this->zoho = $zoho;
			}else{
				die('Forbidden.');
			}
		}		
		
		/**
		 * Insert People to HR
		 */		
		public function insertPerson(){
			extract($this->post);
			$highrise = $this->highrise;
			
			$person = new HighrisePerson($highrise);
			$person->setFirstName($fname);
			$person->setLastName($lname);
			$person->setTitle($fname.' '.$lname);
			$person->setBackground($message);
			$person->setCompanyName($diagnosis);
			$person->addEmailAddress($email); 
			//$person->addEmailAddress("john@primeview.com", "work");
			$person->addPhoneNumber($phone, "Work");
			$person->addPhoneNumber($phone, "Home");
			//$person->addTwitterAccount("john");
			//$person->addTwitterAccount("johndoework", "Business");
			//$person->addWebAddress("http://john.wordpress.com", "Personal");
			//$person->addWebAddress("http://corporation.com/~john");
			//$person->addInstantMessenger("MSN", "johnnydoe@live.com");
			//$person->addInstantMessenger("AIM", "johndoe@corporation.com", "Work");
			$person->save();
			$person = null;
		}
		
		/**
		 * Search People from HR
		 */
		public function searchPeople(){
			extract($this->post);
			
			$highrise = $this->highrise;
			
			$search = $highrise->findPeopleBySearchTerm($fname.' '.$lname);
			$search = $search[0];		
			return $search;
		}
		
		/**
		 * Insert Note To HR
		 */
		public function insertNote(){
			extract($this->post);
			$highrise = $this->highrise;
			
			$person = $this->searchPeople();
			
			$note = new HighriseNote($highrise);	
			$note->setSubjectType("Party");
			$note->setSubjectId($person->getId());
			$note->setBody($message);
			$note->save();		
			$note = null;				
		}
		
		/**
		 * Insert Lead to ZOHO
		 */
		 public function insertLeadToZoho(){
			 extract($this->post);

			$lead = $this->lead;

			// Receiving request
			$request = [
				'first_name' => $fname,
				'last_name' => $lname,
				'lead_diagnosis' => $diagnosis,
				'email' => $email,
				'company' => $company,
				'phone' => $phone,
				'message' => $message
			];

			$insert = $lead->serializeXml($request); // Mapping the request for create xmlstr
			$lead->deserializeXml($insert);

			$zoho = $this->zoho;
			$zoho->setModule('Leads'); // Selecting the module
			$validXML = $zoho->mapEntity($lead); // Create valid XML (zoho format)

			// Insert the new record
			$response = $zoho->insertRecords($validXML, ['wfTrigger' => 'true']); 
		}
		public function sendEmail(){
			extract($this->post);
			
			
			$to = get_option('zorise_email_recipient');
			$subject = get_option('zorise_email_subject');
			$email_body = '
				<table border="1" cellpadding="2">
					<tr>
						<td>Name: </td>
						<td>'.$fname.' '.$lname.'</td>
					</tr>
					<tr>
						<td>Email: </td>
						<td>'.$email.'</td>
					</tr>
					<tr>
						<td>Phone: </td>
						<td>'.$phone.'</td>
					</tr>	
					<tr>
						<td>Company: </td>
						<td>'.$company.'</td>
					</tr>
					<tr>	
						<td>Diagnosis: </td>
						<td>'.$diagnosis.'</td>
					</tr>
					<tr>	
						<td colspan="2">Message: '.$message.'</td>
					</tr>
				</table>			
			';
					
			$headers = 'From: '.$fname.' '.$lname.' <'.get_option('zorise_email_from').'>' . "\r\n" .
				'MIME-Version: 1.0' . "\r\n" .
				'Content-Type: text/html;charset=UTF-8' . "\r\n" .
				'Reply-To: '.$email.'' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
				
			$result = mail($to, $subject, $email_body, $headers);	
			
			if(!$result){
				die('Can not send Mail.');
			}
		}
		
		public function insertToCFDB(){
			extract($this->post);
			$data = (object) array(
				'title' => 'zoho-form',
				'posted_data' => array(
					'fname' => $fname,
					'lname' => $lname,
					'email' => $email,
					'phone' => $phone,
					'company' => $company,
					'diagnosis' => $diagnosis,
					'message' => $message
				),
				'uploaded_files' => null
			);	
			if (class_exists('CF7DBPlugin')) {
				do_action_ref_array( 'cfdb_submit', array( &$data ) );
			}
			
		}
		
		public function redirect(){
			header("Location: ".get_option('zorise_redirect')."");			 
		}
		
		public function debug(){
			//echo get_option('title');
		}	 
		

	} 

	/**
	 * Instantiate
	 */
	$FormController = new FormController();
	
	/**
	 * Insert Leads to Highrise CRM
	 */
	//$FormController->insertPerson();
	//$FormController->insertNote();
	
	/**
	 * Insert Leads to ZOHO CRM
	 */
	$FormController->insertLeadToZoho();
	
	/**
	 * Send Email
	 */
	$FormController->sendEmail(); 
	
	/**
	 * Save to CFDB
	 */
	$FormController->insertToCFDB();
	 
	/**
	 * Redirect
	 */
	$FormController->redirect();
	
	/**
	 *Debug
	 */
	//$FormController->debug();
	
?>