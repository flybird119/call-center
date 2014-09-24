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
	
		
	public function allContent()   // function for admin menu list
	{
			$this->paginate	= array('order'=>'DefinedLanguage.id asc','limit'=>'50',
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
						
						$this->Session->setFlash($error, 'Dialogs/enotify/growl/top_right', array('type'=>'error'), 'top_right');
					}
					else
					{
						$this->Session->setFlash($success, 'Dialogs/enotify/growl/top_right', array('type'=>'success'), 'top_right');
						$this->DefinedLanguage->saveAll($this->request->data);
						$this->redirect(array('controller'=>'defined_languages','action'=>'allContent'));
					}
			}else 
			{
				$Rec=$this->DefinedLanguage->find('first',array('conditions'=>array('DefinedLanguage.id'=>$contentId,
																		'DefinedLanguage.id'=>$this->Session->read('Admin.Language'))));
																		
																	
				$this->request->data=$Rec;
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
