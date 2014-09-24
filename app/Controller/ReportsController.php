<?php
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
  
   ---------------------------------------------------------------------------------------*/

class ReportsController extends AppController {
	//public $helpers = array('TinyMce');
	public $name = 'Report';
    public $theme = 'Admin';
    public $helpers = array('Js','Html','Javascript','Text','Paginator','Ajax'); 
	public $uses=array('ReportCampaginCallLog','CallData');   


	function beforeFilter(){
		parent::beforeFilter();
		  $this->response->disableCache();
	}

//====== Sales reports action===============
/*
 * @ Vishnu sharma
 * @Uses to campaign report
 *@On 23-05-2013
 */

public function campaign_by_rep(){
	
  // get campaign
  $campaignList = array();
  $data = array();

  	 	$campaignList = $this->common->__GetAllCampaignList(); // five 9 campagin list
		$this->set('campaignList',$campaignList);

 		$conditions='';
		if($this->request->isGet()){
			if(isset($this->request->query['submit'])){
			
					
				$StartDate='';
				$end_date='';
				
				// start date condition
				(!empty($this->request->query['StartDate'])) ? $StartDate.=date('Y-m-d',strtotime($this->request->query['StartDate'])).' 00:00:00'  : $StartDate.='';
				//	$StartDate=strtotime($StartDate);
				(!empty($this->request->query['EndDate'])) ? $end_date.=date('Y-m-d',strtotime($this->request->query['EndDate'])).' 00:00:00' : $end_date.=date('m/d/Y',CURRENTTIME);
				//	$end_date=strtotime($end_date);
				
				
				
				if($StartDate and $end_date){
					(!empty($StartDate)) ? $conditions.='CallData.created >="'.$StartDate.'"' : $conditions.='';
					(!empty($end_date)) ? $conditions.=' and CallData.created < "'.$end_date.'"' : $conditions.='';
				}
				
			
				if(!empty($this->request->query['campaign'])){
					(!empty($conditions)) ? $conditions.=' and ' : $conditions.='';
					(!empty($this->request->query['campaign'])) ? $conditions.='CallData.campaign_name like "%'.$this->request->query['campaign'].'%"' : $conditions.='';
				}
				
				
				$this->CallData->virtualFields['hours'] = 0;
				$this->CallData->virtualFields['sales'] = 0;
				$this->CallData->virtualFields['sph'] = 0 ;
				$this->CallData->virtualFields['con'] = 0 ;
				$this->CallData->virtualFields['avgsale'] = 0 ;
				$this->CallData->virtualFields['ni'] = 0 ;
				$this->CallData->virtualFields['nq'] = 0 ;
				$this->CallData->virtualFields['decl'] = 0 ;
				$this->CallData->virtualFields['declper'] = 0 ;
				$this->CallData->virtualFields['contacts'] = 0 ;
				$this->CallData->virtualFields['cchr'] = 0 ;
				$this->CallData->virtualFields['cccap'] = 0 ;
				
				$this->CallData->virtualFields['tcph'] = 0 ;
				$this->CallData->virtualFields['connects'] = 0 ;
				
				$this->CallData->virtualFields['dnc'] = 0 ;
				$this->CallData->virtualFields['cs'] = 0 ;
				
				
				$this->paginate	= array('limit'=>PAGE_RECORD,'conditions'=>$conditions,'fields'=>array(' SUM(TIME_TO_SEC(TIMEDIFF(callend_time,callstart_time))/3600) as CallData__hours','points','user_name',' count(CASE WHEN disposition_id = 1130801 THEN 1 END) as CallData__sales ',' count(disposition_id)/ SUM(TIME_TO_SEC(TIMEDIFF(callend_time,callstart_time))/3600) as CallData__sph ',' (count(CASE WHEN disposition_id = 1130801 THEN 1 END)/(count(CASE WHEN disposition_id = 1130801 THEN 1 END)+ count(CASE WHEN disposition_id = 1130793 THEN 1 END)))*100 as CallData__con',' count(disposition_id)/ SUM(TIME_TO_SEC(TIMEDIFF(callend_time,callstart_time))/3600)/count(CASE WHEN disposition_id = 1130801 THEN 1 END)  as CallData__avgsale ',' count(CASE WHEN disposition_id = 1130792 THEN 1 END) as CallData__ni ',' count(CASE WHEN disposition_id = 1130793 THEN 1 END) as CallData__nq ',' count(CASE WHEN disposition_id = 1130806 THEN 1 END) as CallData__decl ',' (count(CASE WHEN disposition_id = 1130806 THEN 1 END)/(count(CASE WHEN disposition_id = 1130801 THEN 1 END)+count(CASE WHEN disposition_id = 1130806 THEN 1 END)))*100 as CallData__declper','count(CASE WHEN disposition_id = 1130801 THEN 1 END)+count(CASE WHEN disposition_id = 1130792 THEN 1 END) as CallData__contacts ',' ((count(CASE WHEN disposition_id = 1130801 THEN 1 END) + count(CASE WHEN disposition_id = 1130806 THEN 1 END))/SUM(TIME_TO_SEC(TIMEDIFF(callend_time,callstart_time))/3600)) as CallData__cchr ',' ((count(CASE WHEN disposition_id = 1130801 THEN 1 END) + count(CASE WHEN disposition_id = 1130806 THEN 1 END))/count(disposition_id))*100 as CallData__cccap ',' (count(disposition_id)/SUM(TIME_TO_SEC(TIMEDIFF(callend_time,callstart_time))/3600)) as  CallData__tcph',' count(disposition_id) as CallData__connects ',' count(CASE WHEN disposition_id = 1130803 THEN 1 END) as CallData__dnc ',' count(CASE WHEN disposition_id = 1130808 THEN 1 END) as CallData__cs ' ),'group' => 'CallData.user_name'); 
				$campagin_rec =$this->paginate('CallData');
		
				
				
				$this->set(compact('campagin_rec'));
				$this->set('totalRecords', $this->CallData->find('count',array('conditions'=>$conditions)));	
					
			}		
		}

		
	
  
 }
 
 
 /*public function campaign_by_rep(){
  // get campaign
  $campaignList = array();
  $data = array();

  	 	$campaignList = $this->common->__GetAllCampaignList(); // five 9 campagin list
		$this->set('campaignList',$campaignList);

 		$conditions='';
		if($this->request->isGet()){
			if(isset($this->request->query['submit'])){
			
					
				$StartDate='';
				$end_date='';
				
				// start date condition
				(!empty($this->request->query['StartDate'])) ? $StartDate.=$this->request->query['StartDate'] : $StartDate.='';
				$StartDate=strtotime($StartDate);
				(!empty($this->request->query['EndDate'])) ? $end_date.=$this->request->query['EndDate'] : $end_date.=date('m/d/Y',CURRENTTIME);
				$end_date=strtotime($end_date);
				
				
		
			
				
				if($StartDate and $end_date){
					(!empty($StartDate)) ? $conditions.='ReportCampaginCallLog.timestamp >="'.$StartDate.'"' : $conditions.='';
					(!empty($end_date)) ? $conditions.=' and ReportCampaginCallLog.timestamp < "'.$end_date.'"' : $conditions.='';
				}
				
				if(!empty($this->request->query['campaign'])){
					(!empty($conditions)) ? $conditions.=' and ' : $conditions.='';
					(!empty($this->request->query['campaign'])) ? $conditions.='ReportCampaginCallLog.campagin like "%'.$this->request->query['campaign'].'%"' : $conditions.='';
				}
			
			}		
		}

		$this->paginate	= array('limit'=>PAGE_RECORD,'conditions'=>$conditions); 
		$campagin_rec =$this->paginate('ReportCampaginCallLog');
		$this->set(compact('campagin_rec'));
		$this->set('totalRecords', $this->ReportCampaginCallLog->find('count',array('conditions'=>$conditions)));	
	
  
 }*/


// 

public function summary_by_dnis(){
  // get campaign
  $campaignList = array();
  if(empty($this->data)){
   	$campaignList = $this->common->__GetAllCampaignList();
   }
  $this->set('campaignList',$campaignList);
  
  
  if(!empty($this->data)){
  	 //pr($this->data); die;
  }
  
 }

// 

public function summary_by_dialer_campaign(){
  // get campaign
  $campaignList = array();
  if(empty($this->data)){
   	$campaignList = $this->common->__GetAllCampaignList();
   }
  $this->set('campaignList',$campaignList);
  
  
  if(!empty($this->data)){
  	// pr($this->data); die;
  }
  
 }

// vishnu

public function summary_by_hour(){
  // get campaign
  $campaignList = array();
  if(empty($this->data)){
   	$campaignList = $this->common->__GetAllCampaignList();
   }
  $this->set('campaignList',$campaignList);
  
  if(!empty($this->data)){
  	// pr($this->data); die;
  }
  

}

// vishnu

public function call_sales_projections(){
  // get campaign
  $campaignList = array();
  if(empty($this->data)){
   	$campaignList = $this->common->__GetAllCampaignList();
   }
  $this->set('campaignList',$campaignList);
  
  
  if(!empty($this->data)){
  	// pr($this->data); die;
  }
  

}


// vishnu

public function team_manager(){
  // get campaign
  if(!empty($this->data)){
  	// pr($this->data); die;
  }
  

}

//====== QA reports action===============

public function call_center_summary(){
	if($this->request->isGet()){
		if(isset($this->request->query['data']['Call_Center_Summery'])){
			
		}
	}
	
}

public function sales_summary(){
	if($this->request->isGet()){
		if(isset($this->request->query['data']['sales_summery'])){
		}
	}
}

public function performance_by_program(){

	if($this->request->isGet()){
		if(isset($this->request->query['data']['performance_by_program'])){
		}
	}
}

public function performance_by_agent(){
	if($this->request->isGet()){
		if(isset($this->request->query['data']['performance_by_agent'])){
		}
	}
}


public function run_report(){
	
	if($this->request->is('post')){
		switch(isset($this->request->data['SUBMIT'])){
			case 'Campaign by Rep':
			
			
					$this->ReportCampaginCallLog->query('truncate table report_campagin_call_logs');
					$from_date='1960-09-23T21:00:00.000-07:00';
					$to_date=date('Y-m-d',CURRENTTIME);
				
				 $reportIdentifier = $this->common->__runReport($from_date,$to_date,'Call Log Reports','Call Log','CoffeePMA');
					$data = $this->common->__getReportResult($reportIdentifier); 
			
					 foreach($data->return->records as $key=>$value){
							$Rec['ReportCampaginCallLog']['call_id']=$data->return->records[$key]->values->data[0];
							$Rec['ReportCampaginCallLog']['timestamp']=$data->return->records[$key]->values->data[1];
							$Rec['ReportCampaginCallLog']['campagin']=$data->return->records[$key]->values->data[2];
							$Rec['ReportCampaginCallLog']['call_type']=$data->return->records[$key]->values->data[3];
							$Rec['ReportCampaginCallLog']['agent']=$data->return->records[$key]->values->data[4];
							$Rec['ReportCampaginCallLog']['agent_name']=$data->return->records[$key]->values->data[5];
							$Rec['ReportCampaginCallLog']['dispositions']=$data->return->records[$key]->values->data[6];
							$Rec['ReportCampaginCallLog']['ani']=$data->return->records[$key]->values->data[7];
							$Rec['ReportCampaginCallLog']['customer_name']=$data->return->records[$key]->values->data[8];
							$Rec['ReportCampaginCallLog']['dnis']=$data->return->records[$key]->values->data[9];
							$Rec['ReportCampaginCallLog']['call_time']=$data->return->records[$key]->values->data[10];
							$Rec['ReportCampaginCallLog']['bill_time']=$data->return->records[$key]->values->data[11];
							$Rec['ReportCampaginCallLog']['cost']=$data->return->records[$key]->values->data[12];
							$Rec['ReportCampaginCallLog']['ivr_time']=$data->return->records[$key]->values->data[13];
							$Rec['ReportCampaginCallLog']['queue_wait_time']=$data->return->records[$key]->values->data[14];
							$Rec['ReportCampaginCallLog']['ring_time']=$data->return->records[$key]->values->data[15];
							$Rec['ReportCampaginCallLog']['talk_time']=$data->return->records[$key]->values->data[16];
							$Rec['ReportCampaginCallLog']['hold_time']=$data->return->records[$key]->values->data[17];
							$Rec['ReportCampaginCallLog']['park_time']=$data->return->records[$key]->values->data[18];
							$Rec['ReportCampaginCallLog']['after_call_work_time']=$data->return->records[$key]->values->data[19];
							$Rec['ReportCampaginCallLog']['transfers']=$data->return->records[$key]->values->data[20];
							$Rec['ReportCampaginCallLog']['conferences']=$data->return->records[$key]->values->data[21];
							$Rec['ReportCampaginCallLog']['holds']=$data->return->records[$key]->values->data[22];
							$Rec['ReportCampaginCallLog']['abandoned']=$data->return->records[$key]->values->data[23];
							$Rec['ReportCampaginCallLog']['recordings']=$data->return->records[$key]->values->data[24];
							
							$this->ReportCampaginCallLog->save($Rec);
							$this->ReportCampaginCallLog->create();
					 }
					
				break;
			default:
				break;
		}	
	}
}

public function details_by_agent(){
if($this->request->isGet()){
		if(isset($this->request->query['data']['details_by_agent'])){
		}
	}

}


//========== End =========================


// End Clas Here	
}
?>