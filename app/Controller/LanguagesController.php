<?php
/* ====================================
   ************** Pushkar Soni*************
   ====================================
*/
class LanguagesController extends AppController {
	
	public $theme = 'Admin';
	public $name = 'Language'; 
	public $helpers = array('Js','Html','Javascript','Text','Paginator','Ajax');
	public $components = array('RequestHandler','common');
	//public $uses = array('');
	public $uses = array('DefinedLanguageContent','DefinedLanguage','Language');

		
	public function allLanguage()   // function for admin menu list
	{
			$this->paginate	= array('order'=>'Language.name desc','limit'=>'15'); 
			$LanguageList =$this->paginate('Language');
			$this->set('LanguageList', $LanguageList);
			$this->set('totalRecords', $this->Language->find('count'));	 
	}
		

	
	public function addLanguage()  // action to add new menu
	{
		$success = __('Language has been added successfully');
		$error = __('<span style="color:red">Please fill all mendatory fields</span>');
	
		if($this->request->data) /// check form submition
		{
			if($this->request->is('post'))  // new mnenu entry
			{
					$this->Language->set($this->request->data);
					if(!$this->Language->languageValidation()) // checking validation
					{
						$errorsArr = $this->Language->validationErrors;
						$this->Session->setFlash($error, 'Dialogs/enotify/growl/top_right', array('type'=>'error'), 'top_right');
					}
					else
					{
						$allStaticContent=$this->DefinedLanguageContent->find('list',array('fields'=>'id,content'));
						
						
						$this->Language->set($this->request->data);
						
						$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');
						
						$this->Language->saveAll($this->request->data);
						
						
						$definedLanguage['DefinedLanguage']['language_id']=$this->Language->getLastInsertId();
						foreach($allStaticContent as $key=>$value)
						{
								$this->DefinedLanguage->create();
								$definedLanguage['DefinedLanguage']['defined_language_content_id']=$key;
								$this->DefinedLanguage->save($definedLanguage);
								$definedLanguage['DefinedLanguage']['id']='';
						}
						
						
						$this->redirect(array('controller'=>'languages','action'=>'allLanguage'));
					}
			}
		}
		
	}
	
	public function editLanguage($languageId=NULL) {
	
		$this->Language->id=$languageId;
		$success = __('Language has been edited successfully');
		$error = __('<span style="color:red">Please fill all mendatory fields</span>');
		
			if($this->request->is('post'))  // new menu entry
			{
					$this->Language->set($this->request->data);
					if(!$this->Language->languageValidation()) // checking validation
					{
						$errorsArr = $this->Language->validationErrors;
						$this->Session->setFlash($error, 'Dialogs/enotify/growl/top_right', array('type'=>'error'), 'top_right');
					}
					else
					{
						
						$allStaticContent=$this->DefinedLanguageContent->find('list',array('fields'=>'id,content'));
						$definedLanguage['DefinedLanguage']['language_id']=$this->Session->read('Admin.Language');
						
				
						
						foreach($allStaticContent as $key=>$value)
						{
							 $this->Session->read('Admin.Language').'<br>';
							
							
							
							$totalRec=$this->DefinedLanguage->find('count',array(
											'conditions'=>array('DefinedLanguage.language_id="'.$this->Session->read('Admin.Language').'" and 
												DefinedLanguage.defined_language_content_id="'.$key.'"')));
								
									
								//pr($this->request->data);	
								
							if($totalRec)
							{
								
							}else
							{
							
								//$definedLanguage['DefinedLanguage']['id']=$key;
								$definedLanguage['DefinedLanguage']['language_id']=$this->Session->read('Admin.Language');
								$definedLanguage['DefinedLanguage']['defined_language_content_id']=$key;
							
								$this->DefinedLanguage->save($definedLanguage);
								$definedLanguage['DefinedLanguage']['id']='';
							}
							
						}
						  	
						$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');
						$this->Language->saveAll($this->request->data);
						$this->redirect(array('controller'=>'languages','action'=>'allLanguage'));
					}
			}else
			{
				$Rec=$this->Language->find('first',array('conditions'=>array('Language.id'=>$languageId))); // set data for edit 
				$this->request->data=$Rec;
			}
	}
	

	
	public function deleteLanguage($Id=NULL)   // action for delete Skill 
	{
			$success = __('Language has been successfully deleted');
			$error = __('You cannot delete this Banner');
		
			if (!empty($Id)) 
			{
				if ($this->Language->delete($Id, false)) {
					$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');
				}
				$this->redirect(array('controller'=>'languages','action'=>'allLanguage'));
			} else 
			{
				$this->redirect(array('controller'=>'languages','action'=>'allLanguage'));
			}
	}
	

	
	function beforeFilter() 
	{ 
	     parent::beforeFilter();
	}
	function beforeRender(){
	    parent::beforeRender();
		
	}   
	
}
?>