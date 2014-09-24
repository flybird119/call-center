<?php
/*
	 * Used to display all Category ( Project Management Section )
	 *  Created by : 
*/

App::uses('AppController', 'Controller');

class SettingsController extends AppController {
	public $theme = 'Admin';
	
	
	/**
	 * This controller uses following models
	 *
	 * @var array
	 */
	public $name = 'Settings'; 
	public $helpers = array('Js','Html','Javascript','Text','Paginator','Ajax');
	public $uses = array('ProjectCategory','Project','ProjectImage','Setting');
	public $components = array('RequestHandler','common','Session','Cookie');
	

	/**
	 * Used to display all users by Admin
	 *
	 * @access public
	 * @return array
	 */
	public function adminSetting() {

		$success = __('Setting is successfully updated');
		
	
		if($this->request->isPost())
		{
			$this->Setting->set( $this->request->data );
			if($this->Setting->validates()){
				$this->request->data['Setting']['id']='1';
				
				$this->Setting->save($this->request->data,false);
				//$this->Session->setFlash($success, 'Dialogs/enotify/growl/top_right', array('type'=>'success'), 'top_right');
					$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');
			}
		}
		
		$this->request->data=$this->Setting->find('first',array('conditions'=>array('Setting.id'=> '1' ))); // set data for edit 
		
	}
	
	
		

}