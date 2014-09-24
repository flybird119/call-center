<?php
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
   ---------------------------------------------------------------------------------------*/  
class TeamsController extends AppController {
	public $name = 'Team';
    public $theme = 'Admin';
	
	public $uses = array('Team','User','TeamUser',);
  
	
	public function team_list($team_id=null){
		$this->id=$team_id;
		
	

		$team_list=$this->Team->find('all',array('fields'=>'id,name')); // get Team List
		$this->set('team_list',$team_list);
		
		
	   
	   if($this->request->is('post')){  // save Team
	   
			
			(isset($this->request->data['Team']['id']))? $success = __('Team has been updated successfully') : $success = __('Team has been added successfully');
				$error = '<span style="color:red">'.__('Please fill all mendatory fields').'</span>';
				
				$this->Team->set($this->request->data['Team']);
				
				if($this->Team->team_validation()){
					if($this->Team->Save($this->request->data['Team'])){
						$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');			
						$this->redirect($this->referer());
					}
				
				}
			
		}
		
	if($this->request->is('get')){ 
		if(isset($team_id)){
			$this->request->data=$this->Team->find('first',array('fields'=>'id,name','conditions'=>'Team.id="'.$team_id.'"')); // get Team List
		}
		}
	
	}
	public function delete_team($team_id){
			
			$success = __('Team has been deleted successfully');
		$error = __('You cannot delete this campaign');
		
		
		$this->Team->delete($team_id,false);
		$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');			
		$this->redirect($this->referer());	
	
	}
	
	
	
	public function add_agent($team_id=null){
	
	
		if(!empty($this->request->data)){
	
			$this->autoRender=false;
			
			$this->Team->save($this->request->data);
			echo 'Record has been saved successfull';die;
		}
		
		$this->set('team_id',$team_id);
		$this->request->data=$this->Team->find('first',array('conditions'=>'Team.id="'.$team_id.'"'));
		
	}
	
	


	
}
?>