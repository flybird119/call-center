<?php
/* ====================================
   ************** Pushkar Soni*************
   ====================================
*/
class DefinedLanguagesController extends AppController {
	
	
	public $theme = 'Admin';
	public $name = 'DefinedLanguage'; 
	public $helpers = array('Js','Html','Javascript','Text','Paginator','Ajax');
	public $components = array('RequestHandler','common');
	public $uses=array('DefinedLanguageContent','DefinedLanguage');
		
	public function allContent()   // function for admin menu list
	{
			$this->paginate	= array('order'=>'DefinedLanguage.id desc','limit'=>'500',
										'conditions'=>'DefinedLanguage.language_id="'.$this->Session->read('Admin.Language').'"'); 
			$LanguageList =$this->paginate('DefinedLanguage');
			$this->set('LanguageList', $LanguageList);
			$this->set('totalRecords', $this->DefinedLanguage->find('count'));	
	}
	
	public function editContent($contentId=NULL) {
		$this->DefinedLanguage->id=$contentId;
		$success = __('content has been edited successfully');
		$error = __('<span style="color:red">Please fill all mendatory fields</span>');
		
			if($this->request->is('post'))  // new menu entry
			{
					$this->DefinedLanguage->set($this->request->data);
					if(!$this->DefinedLanguage->staticcontentValidation()) // checking validation
					{
						$errorsArr = $this->DefinedLanguage->validationErrors;
						
						$this->Session->setFlash($error, 'Dialogs/top_right', array('type'=>'error'), 'top_right');
					}
					else
					{
						$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');
						
						$this->DefinedLanguage->save($this->request->data);
						$this->redirect(array('controller'=>'defined_languages','action'=>'allContent'));
					}
			}else 
			{
			
			
				$Rec=$this->DefinedLanguage->find('first',array('conditions'=>array( 'DefinedLanguage.id'=>$contentId,
																		'DefinedLanguage.language_id'=>$this->Session->read('Admin.Language'))));
																	
				$this->request->data=$Rec;
			}
	}
	
	function languageVariable()
	{
		//pr($this->DefinedLanguageContent->find('first'));
		$this->paginate	= array('order'=>'DefinedLanguageContent.id desc','limit'=>'500','conditions'=>array('DefinedLanguage.language_id'=>$this->Session->read('Admin.Language'))); 
		$variabeList =$this->paginate('DefinedLanguageContent');
		
		$this->set('variabeList', $variabeList);
		$this->set('totalRecords', $this->DefinedLanguageContent->find('count'));	
		
	}
	
	function addVariable()
	{
		
		
		$success = __('content has been edited successfully');
		$error = __('<span style="color:red">Please fill all mendatory fields</span>');
		
			if($this->request->is('post'))  // new menu entry
			{
			
					$this->DefinedLanguageContent->set($this->request->data);
					if(!$this->DefinedLanguageContent->languageValidation()) // checking validation
					{
						$errorsArr = $this->DefinedLanguageContent->validationErrors;
						$this->Session->setFlash($error, 'Dialogs/top_right', array('type'=>'error'), 'top_right');
					}
					else
					{
					
						
						$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');
						$this->DefinedLanguageContent->save($this->request->data);
						$this->redirect(array('controller'=>'defined_languages','action'=>'languageVariable'));
					}
			}
	}
	
	function editVariable()
	{
	
	}
	function deleteVariable()
	{
	
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
