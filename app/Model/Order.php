<?php
//==============================  Pushkar Soni =============================================
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
  ---------------------------------------------------------------------------------------*/
   
App::uses('AppModel', 'Model');
class Order extends AppModel 
{
	public $name = 'Order';
	//======== realations ======
	//public $hasOne = array('Campaign');
	public $hasMany = array('OrderUpsale');
	//public $belongsTo = array('Campaign');
	

	//======== validation ======
	////var $virtualFields = array('bill_name' => 'CONCAT(Order.bill_fname, " ", Order.bill_lname)');
	//var $virtualFields = array('ship_name' => 'CONCAT(Order.ship_fname, " ", Order.ship_lname)');
	
	//======== other functions ======
	function shipping_validation()
	{ 
		$validate1 = array(
				'ship_fname'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter first name',
						'last'=>true)
					),
				'ship_lname'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter last name',
						'last'=>true)
					),
				'ship_address1'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter address',
						'last'=>true)
					),
				'ship_email'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter e-mail address',
						'last'=>true),
					'mustBeEmail'=> array(
						'rule' => array('email'),
						'message' => 'Please enter valid e-mail address',
						'last'=>true)
					),
				'ship_city'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter city',
						'last'=>true)
					),
				'ship_state'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter state',
						'last'=>true)
					),
				'ship_phone_number'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter phone number',
						'last'=>true)
					),
				'ship_zip'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter zip code',
						'last'=>true)
					)/*,
				ship_country'=> array(
					mustNotEmpty'=>array(
						rule' => 'notEmpty',
						message'=> 'Please enter shipping country',
						last'=>true)
					)*/
			);
		$this->validate=$validate1;
		return $this->validates();
	}
	
	
	function billing_validation()
	{  
		$validate1 = array(
				'bill_fname'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter first name.',
						'last'=>true)
					),
				'bill_lname'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter last name.',
						'last'=>true)
					),
				'bill_address1'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter address.',
						'last'=>true)
					),
				'bill_email'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter e-mail address.',
						'last'=>true),
					'mustBeEmail'=> array(
						'rule' => array('email'),
						'message' => 'Please enter valid e-mail address.',
						'last'=>true)
					),
				'bill_city'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter city.',
						'last'=>true)
					),
				'bill_state'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter state.',
						'last'=>true)
					),
				'bill_phone_number'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter phone number.',
						'last'=>true)
					),
				/*'bill_country'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter shipping country.',
						'last'=>true)
					),*/
				'bill_zip'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter zip .',
						'last'=>true)
					),
				'bill_zip'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter zip .',
						'last'=>true)
					),
				'product_id'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please select product.',
						'last'=>true)
					),
				'cvv_number'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter CVV number.',
						'last'=>true),
					'betwen'=>array(
						'rule' => array('between', 3, 4),
						'message'=> 'Please enter valid CVV number.',
						'last'=>true)
					),
				'cc_expiration_month'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please select month.',
						'last'=>true),
					'validmonth'=>array(
						'rule' => 'monthvalidation',
						'message'=> 'Select month',
						'last'=>true)
					),
				'cc_expiration_year'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please select year.',
						'last'=>true)
					),
				'cc_number'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter CC Number.',
						'last'=>true)/*,
					'ccnumber'=>array(
						'rule'    => array('cc', array($this->data['Order']['cc_card_type']), false, null),
						'message' => 'Invalid credit card number'),*/
						/*'mustValid'=>array(
						'rule'    => array('checkCreditCard', array($this->data['Order']['cc_number'],$this->data['Order']['cc_card_type']), 0, null),
						'message' => 'The credit card number you supplied was invalid'),
						*/
					),	
			);
		$this->validate=$validate1;
		return $this->validates();
	}
	
	
	function varification_validation2()
	{  
		$validate1 = array(
				
				'bill_address11'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter address.',
						'last'=>true)
					),
				'bill_city1'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter city.',
						'last'=>true)
					),
				'bill_state1'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter state.',
						'last'=>true)
					),
				
				'bill_zip1'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter zip .',
						'last'=>true)
					)/*,
				
				'cvv_number1'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter CVV number.',
						'last'=>true),
					'betwen'=>array(
						'rule' => array('between', 3, 4),
						'message'=> 'Please enter valid CVV number.',
						'last'=>true)
					),
				'cc_expiration_month1'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please select month.',
						'last'=>true),
					'validmonth'=>array(
						'rule' => 'monthvalidation',
						'message'=> 'Please enter correct expiration date.',
						'last'=>true)
					),
				'cc_expiration_year1'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please select year.',
						'last'=>true)
					),
				'cc_number1'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter CC Number.',
						'last'=>true),
					'ccnumber'=>array(
						'rule'    => array('cc', array($this->data['Order']['cc_card_type']), false, null),
						'message' => 'Invalid credit card number')
					)*/
			);
		$this->validate=$validate1;
		return $this->validates();
	}
	
 //'rule' => array('checkCreditCard('.$this->data['order']['cc_number'].','.$this->data['order']['cc_card_type'].')'),
	
	public function monthvalidation()
	{
		$cc_expiration_year=strtotime($this->data['Order']['cc_expiration_year'].'-'.$this->data['Order']['cc_expiration_month']);
		$date=strtotime(date('Y-M',CURRENTTIME));
		if($date > $cc_expiration_year){
			return false;
		}

		return true;
	}
	
	
	public function checkCreditCard ($cardnumber, $cardname, &$errornumber, &$errortext) {

  // Define the cards we support. You may add additional card types.
  
  //  Name:      As in the selection box of the form - must be same as user's
  //  Length:    List of possible valid lengths of the card number for the card
  //  prefixes:  List of possible prefixes for the card
  //  checkdigit Boolean to say whether there is a check digit
  
  // Don't forget - all but the last array definition needs a comma separator!
  
  $cards = array (  array ('name' => 'American Express', 
                          'length' => '15', 
                          'prefixes' => '34,37',
                          'checkdigit' => true
                         ),
                   array ('name' => 'Diners Club Carte Blanche', 
                          'length' => '14', 
                          'prefixes' => '300,301,302,303,304,305',
                          'checkdigit' => true
                         ),
                   array ('name' => 'Diners Club', 
                          'length' => '14,16',
                          'prefixes' => '36,38,54,55',
                          'checkdigit' => true
                         ),
                   array ('name' => 'Discover', 
                          'length' => '16', 
                          'prefixes' => '6011,622,64,65',
                          'checkdigit' => true
                         ),
                   array ('name' => 'Diners Club Enroute', 
                          'length' => '15', 
                          'prefixes' => '2014,2149',
                          'checkdigit' => true
                         ),
                   array ('name' => 'JCB', 
                          'length' => '16', 
                          'prefixes' => '35',
                          'checkdigit' => true
                         ),
                   array ('name' => 'Maestro', 
                          'length' => '12,13,14,15,16,18,19', 
                          'prefixes' => '5018,5020,5038,6304,6759,6761,6762,6763',
                          'checkdigit' => true
                         ),
                   array ('name' => 'MasterCard', 
                          'length' => '16', 
                          'prefixes' => '51,52,53,54,55',
                          'checkdigit' => true
                         ),
                   array ('name' => 'Solo', 
                          'length' => '16,18,19', 
                          'prefixes' => '6334,6767',
                          'checkdigit' => true
                         ),
                   array ('name' => 'Switch', 
                          'length' => '16,18,19', 
                          'prefixes' => '4903,4905,4911,4936,564182,633110,6333,6759',
                          'checkdigit' => true
                         ),
                   array ('name' => 'VISA', 
                          'length' => '16', 
                          'prefixes' => '4',
                          'checkdigit' => true
                         ),
                   array ('name' => 'VISA Electron', 
                          'length' => '16', 
                          'prefixes' => '417500,4917,4913,4508,4844',
                          'checkdigit' => true
                         ),
                   array ('name' => 'LaserCard', 
                          'length' => '16,17,18,19', 
                          'prefixes' => '6304,6706,6771,6709',
                          'checkdigit' => true
                         )
                );

  $ccErrorNo = 0;

  $ccErrors [0] = "Unknown card type";
  $ccErrors [1] = "No card number provided";
  $ccErrors [2] = "Credit card number has invalid format";
  $ccErrors [3] = "Credit card number is invalid";
  $ccErrors [4] = "Credit card number is wrong length";
               
  // Establish card type
  $cardType = -1;
  for ($i=0; $i<sizeof($cards); $i++) {

    // See if it is this card (ignoring the case of the string)
    if (strtolower($cardname) == strtolower($cards[$i]['name'])) {
      $cardType = $i;
      break;
    }
  }
  
  // If card type not found, report an error
  if ($cardType == -1) {
     $errornumber = 0;     
     $errortext = $ccErrors [$errornumber];
     return false; 
  }
   
  // Ensure that the user has provided a credit card number
  if (strlen($cardnumber) == 0)  {
     $errornumber = 1;     
     $errortext = $ccErrors [$errornumber];
     return false; 
  }
  
  // Remove any spaces from the credit card number
  $cardNo = str_replace (' ', '', $cardnumber);  
   
  // Check that the number is numeric and of the right sort of length.
  if (!preg_match("/^[0-9]{13,19}$/",$cardNo))  {
     $errornumber = 2;     
     $errortext = $ccErrors [$errornumber];
     return false; 
  }
       
  // Now check the modulus 10 check digit - if required
  if ($cards[$cardType]['checkdigit']) {
    $checksum = 0;                                  // running checksum total
    $mychar = "";                                   // next char to process
    $j = 1;                                         // takes value of 1 or 2
  
    // Process each digit one by one starting at the right
    for ($i = strlen($cardNo) - 1; $i >= 0; $i--) {
    
      // Extract the next digit and multiply by 1 or 2 on alternative digits.      
      $calc = $cardNo{$i} * $j;
    
      // If the result is in two digits add 1 to the checksum total
      if ($calc > 9) {
        $checksum = $checksum + 1;
        $calc = $calc - 10;
      }
    
      // Add the units element to the checksum total
      $checksum = $checksum + $calc;
    
      // Switch the value of j
      if ($j ==1) {$j = 2;} else {$j = 1;};
    } 
  
    // All done - if checksum is divisible by 10, it is a valid modulus 10.
    // If not, report an error.
    if ($checksum % 10 != 0) {
     $errornumber = 3;     
     $errortext = $ccErrors [$errornumber];
     return false; 
    }
  }  

  // The following are the card-specific checks we undertake.

  // Load an array with the valid prefixes for this card
  $prefix = explode(',',$cards[$cardType]['prefixes']);
      
  // Now see if any of them match what we have in the card number  
  $PrefixValid = false; 
  for ($i=0; $i<sizeof($prefix); $i++) {
    $exp = '/^' . $prefix[$i] . '/';
    if (preg_match($exp,$cardNo)) {
      $PrefixValid = true;
      break;
    }
  }
      
  // If it isn't a valid prefix there's no point at looking at the length
  if (!$PrefixValid) {
     $errornumber = 3;     
     $errortext = $ccErrors [$errornumber];
     return false; 
  }
    
  // See if the length is valid for this card
  $LengthValid = false;
  $lengths = explode(',',$cards[$cardType]['length']);
  for ($j=0; $j<sizeof($lengths); $j++) {
    if (strlen($cardNo) == $lengths[$j]) {
      $LengthValid = true;
      break;
    }
  }
  
  // See if all is OK by seeing if the length was valid. 
  if (!$LengthValid) {
     $errornumber = 4;     
     $errortext = $ccErrors [$errornumber];
     return false; 
  };   
  
  // The credit card is in the required format.
  return true;
}
	
	function varification_validation()
	{ 
		$validate1 = array(
				'cc_number'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter cart number.',
						'last'=>true)
					),
				'cc_expiration_month'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Select month.',
						'last'=>true)
					),
				'cc_expiration_year'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Select year.',
						'last'=>true)
					)
			);
		$this->validate=$validate1;
		return $this->validates();
	}
	
}
?>