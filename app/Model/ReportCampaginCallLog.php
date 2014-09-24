<?php
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
  
   ---------------------------------------------------------------------------------------*/
   
App::uses('AppModel', 'Model');
class ReportCampaginCallLog extends AppModel 
{
	public $name = 'ReportCampaginCallLog';
	public $useTable ='report_campagin_call_logs';
	//public $virtualFields = array('name' => 'CONCAT(User.first_name, " ", User.last_name)');

	function beforeSave() {
	
	
		if (!empty($this->data['ReportCampaginCallLog']['timestamp'])) {
				$this->data['ReportCampaginCallLog']['timestamp'] = strtotime($this->data['ReportCampaginCallLog']['timestamp']);
		}
		return true;
}

}
?>
