<?php

class LimeLightProxy {
    var $username = '';
    var $password = '';
    // API docs: http://help.limelightcrm.com/entries/317872-transaction-api-documentation
    var $api_url = "https://crmorderhub.com/admin/transact.php";
    var $server = array();
    
    function __construct($server_info) {
		$this->server = $server_info;
    }

	
    function createProspect($form_field_values=array()) {
        $fields = array(
            "firstName" => $form_field_values["firstName"],
            "lastName" => $form_field_values["lastName"],
            "address1" => $form_field_values["address1"],
            "city" => $form_field_values["city"],
            "state" => $form_field_values["state"],
            "zip" => $form_field_values["zip"],
            "country"=>"US",
            "phone" => $form_field_values["phone"],
            "email" => $form_field_values["email"],
            "campaignId" => $form_field_values["campaignId"]
        );
        return $this->api("NewProspect", $fields);
    }
    
    function createOrderForProspect($prospect_id, $form_field_values=array()) {
        $fields = array(
            "prospectId" => $prospect_id,
            "creditCardType" => $form_field_values["cc_type"],
            "creditCardNumber" => $form_field_values["cc_number"],
            "expirationDate" => $form_field_values["exp_month"].$form_field_values["exp_year"],
            "CVV" => $form_field_values["cc_cvv"],
            "tranType" => "Sale",
            "productId" => $form_field_values["productId"],
            "campaignId" => $form_field_values["campaignId"],
            "shippingId" => $form_field_values["shippingId"],
            "upsellCount" =>0
        );
        
        if($form_field_values['radio'] == 'no'){
        // interpolate the billing fields if set
        $billing_field_ids = array("billingAddress1", "billingCity", "billingCountry", "billingState", "billingZip","billingCountry");
        foreach ($billing_field_ids as $f) {
            if (array_key_exists($f, $form_field_values)) {
                $fields[$f] = $form_field_values[$f];   
            }
        }
        }else{
            $fields['billingSameAsShipping'] = 'yes'; 
        }
        
        return $this->api("NewOrderWithProspect", $fields);
    }
    
    function createNewOrder($form_field_values = array()){
		 $upsale_product_id=$form_field_values['Order']['upsale_product_id'];
		$upsellCount=count(explode(',',trim($upsale_product_id)));
		if(!$upsellCount[0]){
			$upsellCount=0;
			 $upsale_product_id='';
		}
		
			$form_field_values['Order']['cc_card_type']=strtolower($form_field_values['Order']['cc_card_type']);
			switch($form_field_values['Order']['cc_card_type']){
				case 'mastercard':
					$form_field_values['Order']['cc_card_type']='master';
					break;
				default:
					break;
			}
			
		
			$this->username=$form_field_values['Order']['lime_light_username'];
			$this->password=$form_field_values['Order']['lime_light_password'];
			//$five9detail=array('username'=>$form_field_values['Order']['lime_light_username'],'password'=>$form_field_values['Order']['lime_light_password']);
			$form_field_values['billingAddressIsSame']='on';
			
			
		  $fields = array(
            "firstName" => $form_field_values['Order']['ship_fname'],
            "lastName" => $form_field_values['Order']['ship_lname'],
            "shippingAddress1" => $form_field_values['Order']['ship_address1'],
            "shippingCity" => $form_field_values['Order']['ship_city'],
            "shippingState" => $form_field_values['Order']['ship_state'],
            "shippingZip" => $form_field_values['Order']['ship_zip'],
            "shippingCountry"=>'US',
            "phone" => $form_field_values['Order']['ship_phone_number'],
            "email" => $form_field_values['Order']['ship_email'],
            "campaignId" => $form_field_values['Order']['lime_light_campaign_id'],  
            "creditCardType" => $form_field_values['Order']['cc_card_type'],
            "creditCardNumber" => $form_field_values['Order']['cc_number'],
            "expirationDate" => $form_field_values['Order']['cc_expiration_month'].substr($form_field_values['Order']['cc_expiration_year'],-2),
            "CVV" => $form_field_values['Order']['cvv_number'],
            "tranType" => "Sale",
            "productId" => $form_field_values['Order']['lime_light_product_id'], 
            "shippingId" =>  $form_field_values['Order']['lime_light_shipping_id'], 
       		"upsellCount" =>$upsellCount,
            "AFID"=>'',
            "SID"=>'',
            "AFFID"=>'',
            "C1"=>'',
            "C2"=>'',
            "C3"=>'',
            "click_id"=>'',
            "AID"=>'',
            "OPT"=>'',
			"billingFirstName" => $form_field_values['Order']["bill_fname"],
            "billingLastName" => $form_field_values['Order']["bill_lname"],
            "billingAddress1" => $form_field_values['Order']["bill_address1"],
			"billingaAddress2" => $form_field_values['Order']["bill_address2"],
            "billingCity" => $form_field_values['Order']["bill_city"],
            "billingState" => $form_field_values['Order']["bill_state"],
            "billingZip" => $form_field_values['Order']["bill_zip"],
            "billingCountry"=>'US'
        );
		
		
	
		
		 if($upsellCount){  $fields['upsellProductIds']=$form_field_values['Order']['upsale_product_id']; }


       $billing_field_ids = array(
            "firstName"=>"billingFirstName",
            "lastName"=>"billingLastName",
            "shippingAddress1"=>"billingAddress1", 
            "shippingCity"=>"billingCity", 
            "shippingCountry"=>"billingCountry", 
            "shippingState"=>"billingState", 
            "shippingZip"=>"billingZip"
        );
		
	
        if($form_field_values['billingAddressIsSame'] == 'on'){     
            foreach ($billing_field_ids as $k=>$f) {
                if (array_key_exists($f, $form_field_values)) {
                    $fields[$f] = $form_field_values[$k];   
                }
            }
        }else{
            foreach ($billing_field_ids as $k=>$f) {
                if (array_key_exists($f, $form_field_values)) {
                    $fields[$f] = $form_field_values[$f];   
                }
            }
        }
        //echo "<pre>";print_r($form_field_values);print_r($fields);exit;
		
        return $this->api("NewOrder", $fields);
    }
    
    private function api($method, $fields) {
		
	
        $login_fields = array(
            "username" => urlencode($this->username),
            "password" => urlencode($this->password),
            "method" => urlencode($method),
            "ipAddress" => urlencode($this->server['REMOTE_ADDR']),
        );
        
        $encoded_fields = $this->encode_fields($fields);
        $fields_to_post = array_merge($login_fields, $encoded_fields);        
        $result = $this->post($fields_to_post);
        return $result;
    }
    
    private function encode_fields($fields) {
        $encoded = array();
        foreach($fields as $key=>$val){ 
            $encoded[$key] = urlencode($val);
        }
        return $encoded;
    }
    
    private function array_to_query_string($fields) {
        $fields_string = "";
        foreach($fields as $key=>$value) { 
            $fields_string .= "$key=$value&";
        }
        rtrim($fields_string,'&');
        return $fields_string;
    }
    
    private function post($fields) {
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch,CURLOPT_URL,$this->api_url);
        curl_setopt($ch,CURLOPT_POST,count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $this->array_to_query_string($fields));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $result = $this->translate_response($response);
        return $result;
    }
    
    private function translate_response($response) {
        $response = (string)$response;
        $response_fields = explode("&", $response);
        $result = array();
        foreach ($response_fields as $f) {
            $field = explode("=", $f);
            $result[$field[0]] = $field[1];
        }
        return $result;
    }
}