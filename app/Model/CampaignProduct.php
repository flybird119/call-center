<?php
//==============================  Pushkar Soni =============================================
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
  ---------------------------------------------------------------------------------------*/
  
App::uses('AppModel', 'Model');
class CampaignProduct extends AppModel 
{
	public $name = 'CampaignProduct';
	var $virtualFields = array('prodct_description' => 'CONCAT(CampaignProduct.name," - ",CampaignProduct.total_price)');
	//======== realations ======

	function product_Validation() { 
		$validate1 = array(
				'name'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter name',
						'last'=>true)
					),
				'product_id'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter product id',
						'last'=>true),
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'product id must be unique.',
						'last'=>true)
					),
				'total_price'=> array(
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