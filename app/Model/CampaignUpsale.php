<?php
//==============================  Pushkar Soni =============================================
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
  
   ---------------------------------------------------------------------------------------*/
   
App::uses('AppModel', 'Model');
class CampaignUpsale extends AppModel 
{
	public $name = 'CampaignUpsale';
	//var $virtualFields = array('upsell_description' => 'CONCAT(Upsale.upsell_description, "  ", Upsale.upsell_price)');
	//======== realations ======
	
	function upsell_validation() { 
		$validate1 = array(
				'upsell_description'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter name',
						'last'=>true)
					),
				'upsell_id'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter product id',
						'last'=>true)
					),
				'upsell_id'=> array(
					'unique'=>array(
						'rule' => 'isUnique',
						'message'=> 'upsell id must be unique.',
						'last'=>true)
					),
				'upsell_price'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter price',
						'last'=>true),
					'number'=>array(
						'rule' => 'Numeric',
						'message'=> 'Please enter valid price',
						'last'=>true)
					)
					
				
			);
		$this->validate=$validate1;
		return $this->validates();
	}
	//======== other functions ======
}
?>