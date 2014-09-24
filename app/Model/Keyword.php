<?php
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
   ---------------------------------------------------------------------------------------*/

App::uses('AppModel', 'Model');
class Keyword extends AppModel 
{
	public $name = 'Keyword';
	
	
	/**
	 * model validation array
	 *
	 * @var array
	 */
	function keywordValidation() { 
		$validate1 = array(
				'keyword'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Enter a keyword',
						'last'=>true),
					'mustMatch'=>array(
						'rule' => 'uniqueKeyword',
						'message'=> 'Keyword already used for this campaign',
						'last'=>true),	
					),
				'value'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Enter keyword value',
						'last'=>true)
					)
			);
		$this->validate=$validate1;
		return $this->validates();
	}
	
	/**
	 * Used to match old password
	 *
	 * @access public
	 * @return boolean
	 */
	public function uniqueKeyword() {
		
		$res = $this->find('count', array('conditions'=>array('Keyword.campaign_id'=>$this->data['Keyword']['campaign_id'],'Keyword.keyword'=>$this->data['Keyword']['keyword'])));
		
		if($res==0)
		return true;
		else
		return false;
		
	}

	function editkeywordValidation() { 
		$validate1 = array(
				'keyword'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Enter a keyword',
						'last'=>true),
					'mustMatch'=>array(
						'rule' => 'uniqueEditKeyword',
						'message'=> 'Keyword already used for this campaign',
						'last'=>true),	
					),
				'value'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Enter keyword value',
						'last'=>true)
					)
			);
		$this->validate=$validate1;
		return $this->validates();
	}
	
	
	/**
	 * Used to match old password
	 *
	 * @access public
	 * @return boolean
	 */
	public function uniqueEditKeyword() {
		
		$res = $this->find('count', array('conditions'=>array('Keyword.campaign_id'=>$this->data['Keyword']['campaign_id'],'Keyword.id !='=>$this->data['Keyword']['id'],'Keyword.keyword'=>$this->data['Keyword']['keyword'])));
		
		if($res==0)
		return true;
		else
		return false;
		
	}
			
			
}
?>