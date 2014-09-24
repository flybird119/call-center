<?php
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
  
   ---------------------------------------------------------------------------------------*/

class CustomersController extends AppController {
	//public $helpers = array('TinyMce');
	public $name = 'Customers';
    public $theme = 'Admin';
        
        

	/**
	* Customer listing
	*
	*/
	
	public function customerslist()
	{
		
		if($this->request->isPost())
		{
		
			// enter in search condidtions
		$name = $this->request->data['name'];
		$email = $this->request->data['email'];
	
		 $conditions ='';
			 if(!empty($name))
			 {
				$conditions.='Customer.name like "%'.$name.'%" ';	 
			 }
			 
			 if(!empty($email))
			 {	
			 	if(!empty($conditions)) $conditions.=' OR ';
				$conditions.='Customer.email like "%'.$email.'%"';	 
			 }
	
			$this->paginate	= array('conditions'=>$conditions,'order'=>array('Customer.name desc'),'limit'=>PAGE_RECORD);
			$this->set('customerList', $this->paginate('Customer'));
			$this->set('totalRecords', $this->Customer->find('count'));	
			}
			else
			 {
				 $this->paginate	= array('order'=>'Customer.name desc','limit'=>PAGE_RECORD); 
				 $customerList =$this->paginate('Customer');
				 $this->set('customerList', $customerList);
				 $this->set('totalRecords', $this->Customer->find('count'));	
			 }
		 
		 
	
		
		 
		
	}
	
	/**
	* function used to edit customer
	*
	*/
	
	public function editcustomer($customerId = null)
	{
		$success = __('Customer has been edited successfully');
		$error = __('<span style="color:red">Please fill all mendatory fields</span>');
		
		if($this->request->is('post'))  // new mnenu entry
			{ 
					$this->Customer->set($this->request->data);
				
					if(!$this->Customer->customerValidation()) // checking validation
					{
						$errorsArr = $this->Customer->validationErrors;
					}
					else
					{ 
						$this->Customer->save($this->request->data);
						$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');	
						$this->redirect(array('controller'=>'customers','action'=>'customerslist'));
					}
			}
			else
			{
			 	$this->request->data = $this->Customer->find('first',array('conditions'=>array('Customer.id'=>$customerId))); // set data for edit 
			}
		
	}
	
	/**
	* function used to add customer
	*
	*/
	
	public function addcustomer()
	{
		$success = __('Customer has been added successfully');
		$error = __('<span style="color:red">Please fill all mendatory fields</span>');
		
		if($this->request->is('post'))  // new mnenu entry
			{ 
					$this->Customer->set($this->request->data);
				
					if(!$this->Customer->customerValidation()) // checking validation
					{
						$errorsArr = $this->Customer->validationErrors;
					}
					else
					{ 
						$this->Customer->save($this->request->data);
						$this->Session->setFlash($success,'Dialogs/top_right', array('type'=>'success'),'top_right');
						$this->redirect(array('controller'=>'customers','action'=>'customerslist'));
					}
			}
			
		
	}
	
	/**
	 * Used to delete the customer by Admin
	 *
	 * @access public
	 * @return void
	 */
	public function deleteCustomer($userId = null) {
		$success = __('Customer is successfully deleted');
		$error = __('You cannot delete this customer');
		
		
		$this->Customer->delete($userId,false);
		$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');	
		$this->redirect(array('controller'=>'customers','action'=>'customerslist'));
	
	}
	
	function exportAll()
	{  
		$this->autoRender = false;
    	if($this->request->is('post'))  // new job entry
			{ 
				$conditions = '';
					if(!empty($this->request->data['customers']['start_date']))
				{
					$start_date = $this->request->data['customers']['start_date'];
				}
				if(!empty($this->request->data['customers']['end_date']))
				{
					$end_date = $this->request->data['customers']['end_date'];
				}
			
					// condition for if  start dat grater then end date
				if(isset($start_date) &&  isset($end_date))
				{
					if($start_date > $end_date)
					{
						$this->Session->setFlash("start date can\'t greater then end date", 'Dialogs/enotify/growl/top_right', array('type'=>'error'), 'top_right');
						$this->redirect(array('controller'=>'customers','action'=>'customerslist'));
						
					}
					
				}
				
				// condition for if  start dat equal end date
				if(!empty($start_date) && !empty($end_date) && ($start_date==$end_date))
				{
					$start_date = date("Y-m-d",strtotime($start_date));
					if(!empty($conditions)) $conditions.=' AND ';
					$conditions.='Customer.created like "%'.$start_date.'%"';
					$results = $this->Contact->find('all',array('conditions'=>$conditions));
				}
				else if(isset($start_date) || isset($end_date))
				{
					
					 if(!empty($start_date))
						{	
						   $start_date = date("Y-m-d h:m:s",strtotime($start_date));
						   if(!empty($conditions)) $conditions.=' AND ';
						   $conditions.='Customer.created >= "'.$start_date.'"';	 
						}
						if(!empty($end_date))
						{	
							$end_date = date("Y-m-d h:m:s",strtotime($end_date));
							if(!empty($conditions)) $conditions.=' AND ';
							$conditions.='Customer.created <= "'.$end_date.'"';	 
						}
				
					$results = $this->Customer->find('all',array('conditions'=>$conditions));
					
				}
				else{
							$results = $this->Customer->find('all');
						
					}	
								ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large
		
								//create a file
								$filename = "customer_list_".date("Y.m.d").".csv";
								$csv_file = fopen('php://output', 'w');
								
								header('Content-type: application/csv');
								header('Content-Disposition: attachment; filename="'.$filename.'"');
						
						
								// The column headings of your .csv file
								$header_row = array("name","email","website_url","created");
								fputcsv($csv_file,$header_row,',','"');
							
								// Each iteration of this while loop will be a row in your .csv file where each field corresponds to the heading of the column
								foreach($results as $result)
								{ // Array indexes correspond to the field names in your db table(s)
									$row = array(
										
										$result['Customer']['name'],
										$result['Customer']['email'],
										$result['Customer']['website_url'],
										$result['Customer']['created']
										
									);
									
									fputcsv($csv_file,$row,',','"');
								}
								
								fclose($csv_file);die;
		}
		
	}
}
?>