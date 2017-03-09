<?php

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
		
		public function __construct(){		
			$highrise = new HighriseAPI();
			$highrise->setAccount('patientsupport@newhopeunlimited.com');
			$highrise->setToken('765d2f72b62057f5e370a18807dbe4ec');	
			
			$lead = new Lead();
			$zoho = new ZohoClient('884a0f309fe383254e9fa937cddb3931');			
			
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
				'email' => $email,
				'company' => $company,
				'phone' => $phone,
			];

			$insert = $lead->serializeXml($request); // Mapping the request for create xmlstr
			$lead->deserializeXml($insert);

			$zoho = $this->zoho;
			$zoho->setModule('Leads'); // Selecting the module
			$validXML = $zoho->mapEntity($lead); // Create valid XML (zoho format)

			// Insert the new record
			$response = $zoho->insertRecords($validXML, ['wfTrigger' => 'true']); 
		 }
		 
		 public function redirect(){
			header("Location: http://jcruz.primeview.com/wordpress/zorise-plugin/");			 
		 }
		 
	} 
	$FormController = new FormController();
	
	$FormController->insertPerson();
	$FormController->insertNote();
	$FormController->insertLeadToZoho();
	$FormController->redirect();
?>