<?php
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
   ---------------------------------------------------------------------------------------*/

App::uses('AppModel', 'Model');
class Campaign extends AppModel 
{
	public $name = 'Campaign';
	public $hasMany = array('CallScript'=>array('dependent' => true ),
							'Lead'=>array('dependent' => true ),
							'Keyword'=>array('dependent' => true ),
							'CampaignUpsale'=>array('dependent' => true ),
							'CampaignProduct'=>array('dependent' => true ),
							'CampaignUpsale'=>array('dependent' => true ),
							);
	
	  public $belongsTo = array(
        'Customer' => array(
            'className'    => 'Customer',
            'foreignKey'   => 'customer_id',
			'fields'    => array('id','name')
        ),
		'PaymentgatwayList' => array(
            'className'    => 'PaymentgatwayList',
            'foreignKey'   => 'paymentgetwaylist_id',
			'fields'    => array('id','name')
        )
		
		
		// ''
    );
	
	/**
	 * model validation array
	 *
	 * @var array
	 */
	function campaignValidation () { 
		$validate1 = array(
				'customer_id'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please select a customer',
						'last'=>true)
					),
				'name'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter campaign name',
						'last'=>true)
					),
				'five9_campaign_id'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter Five9 Campagin ID.',
						'last'=>true)
					),
				'campaign_list_name'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please select a List.',
						'last'=>true)
					),	
				'paymentgetwaylist_id'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please select payment getway',
						'last'=>true)
					)
			);
		$this->validate=$validate1;
		return $this->validates();
	}
			
	  
   function field_comparison($check1, $operator, $field2) {
        foreach($check1 as $key=>$value1) {
            $value2 = $this->data[$this->alias][$field2];
            if (!Validation::comparison($value1, $operator, $value2))
                return false;
        }
        return true;
    }			
			
}
?>