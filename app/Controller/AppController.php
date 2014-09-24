<?php

App::uses('Controller', 'Controller');


class AppController extends Controller
{
	var $helpers = array('Form', 'Html', 'Session', 'Js', 'Usermgmt.UserAuth','CakeGrid.Grid','Ajax','Text','Paginator');
	public $components = array('Session','RequestHandler', 'Usermgmt.UserAuth','common','Cookie','Email');
	public $paginate=array(); 
	var $settingsRec = array();
	public $pageVariables=array();
	
	
	private function userAuth(){
		$this->UserAuth->beforeFilter($this);
	}
	
	function beforeFilter(){
	 # To enable portuguese language as main
	 #Configure::write('Config.language', 'fre');  	
	
	$this->set('common',$this->common); // set common component for ctp files
	$this->userAuth();

	//=================== global variables=============
		@define('APPLICATION_URL',FULL_BASE_URL.router::url('/',false));
		@define('CURRENT_URL',FULL_BASE_URL.router::url($this->here, false));
		@define('BASE_URL',Router::url('/',false));
		@define('GOLBAL_EMAIL','4003@dothejob.org');
		@define('DATE_TIME_FORMATE','M-d-Y h:m:i a');
		
 	//End
	
		if(isset($this->params->query['PageRecord']))
			@define('PAGE_RECORD',$this->params->query['PageRecord']);
		else
			@define('PAGE_RECORD','10');	
		
		
		
		
		//======== get Application common setttings =========
			$this->loadModel('Setting');
			$this->settingsRec=$this->Setting->find('first');                
			$this->set('settingsRec',$this->settingsRec);
    	
				
		@define('Five9_username',trim($this->settingsRec['Setting']['five9_username']));
		@define('Five9_password',trim($this->settingsRec['Setting']['five9_password']));
		
		
	// ============= global date formating ========================
	
		@define('EMAIL_FROM_ADDRESS',$this->settingsRec['Setting']['general_email']);
		
		if(!isset($this->settingsRec['Setting']['date_format']))
			@define('DATE_FORMAT',$this->settingsRec['Setting']['date_format']);
		else
			@define('DATE_FORMAT','Y-m-d');
	//End
		
		
		//============================ SMTP settings================
		if($this->settingsRec['Setting']['mail_type']=='smtp' )
		{
			$smtpSettings = array(
							 'transport' => 'Smtp',
							'from' => array('seobranddevelopers@gmail.com' => 'Rank Media'),
							'host' => $this->settingsRec['Setting']['host'],
							'port' => $this->settingsRec['Setting']['port_number'],
							'timeout' => $this->settingsRec['Setting']['timeout'],
							'username' => $this->settingsRec['Setting']['username'],
							'password' => $this->settingsRec['Setting']['password'],
							'client' => null,
							'log' => false 
							);
			@define('SMTP_SETTING',serialize($smtpSettings));	
			
			// smtp mail setting  3/7/2013
			@define('HOST',$this->settingsRec['Setting']['host']);	
			@define('TIMEOUT',$this->settingsRec['Setting']['timeout']);	
			@define('PORT_NUMBER',$this->settingsRec['Setting']['port_number']);	
			@define('USERNAME',$this->settingsRec['Setting']['username']);	
			@define('PASSWORD',$this->settingsRec['Setting']['password']);			
		}
        //End   
	
		
		
		
		$this->languageSettings();
		  
		  
		 
	}
	
	//================ call different callbacks ========
		function beforeRender() 
		{
			
		}
	//End
	
	// model other functions ========
	function languageSettings()  // function to manage multiple languages
	{
		//============== Wirte session for language admin ===========
			//setlocale(LC_ALL,'fr_FR');
             $this->loadModel('Language');
		  
			if(!empty($this->params->query['Adminlang']))
			{
					$language=$this->params->query['Adminlang'];
					$this->Session->write('Admin.Language',$language);
					
					$LanguageId=$this->params->query['Adminlang'];
					$this->Language->id=$LanguageId;
					$locaLan=$this->Language->field('time');
					setlocale(LC_ALL,$locaLan);
					
			}elseif(empty($this->params->query['Adminlang']))
			{
				
				if(!$this->Session->read('Admin.Language'))
				{
					
					$this->Session->write('Admin.Language',$this->settingsRec['Setting']['default_language']);
					$LanguageId=$this->settingsRec['Setting']['default_language'];
					$this->Language->id=$LanguageId;
					$locaLan=$this->Language->field('time');
					setlocale(LC_ALL,$locaLan);				
				
				}else
				{
					
					$LanguageId=$this->Session->read('Admin.Language');
					$this->Language->id=$LanguageId;
					$locaLan=$this->Language->field('time');
					setlocale(LC_ALL,$locaLan);
				
				}
			}
			$this->set('Language',$this->Session->read('Admin.Language'));
			if($this->params['action']=='logout') 
		 	{	
		 	$this->Session->write('Admin.Language','');
		 	}
		//End
	}
	
	function emailSettings()  // function to manage multiple languages
	{}
	
	//End======
}
