<?php

class CoatapiController extends Controller
{
    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */
    Const APPLICATION_ID = 'ASCCPE';
 
    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';
    /**
     * @return array action filters
     */
    public function filters()
    {
            return array();
    }
 
 
    // Actions
    public function actionList()
    {
				
			// Get the respective model instance
			switch($_GET['model'])
			{
				
				case 'coatcertdata':
				
				$allpdirs=Coatcertbasic::model()->findAll();
				
				$totalitems=count($allpdirs);

				if(isset($_GET['pl']))
				{
				$allpdirs=Coatcertbasic::model()->findAll(array(
    'order' => 'Id desc',
    'limit' => $_GET['pl'],
    'offset' => ($_GET['pn']-1)*$_GET['pl']
));
				}
				
				
				
				$models=array();
				foreach($allpdirs as $p)
				{
					$pi=Coatcertuploads::model()->find(array('condition'=>'coatcertid=:Id',
											 'params'=>array(':Id'=>$p->Id),));
					
					$models[]=(object)array('Id'=>$p->Id,'TCNo'=>$p->TCNo,'TopCoatDate'=>$p->TopCoatDate,
					'Customer'=>$p->Customer,'PartCoated'=>$p->PartCoated,'Quantity'=>$p->Quantity,'RFICode'=>$p->RFICode,'DINNo'=>$p->DINNo,
					'BatchNo'=>$p->BatchNo,'Process'=>$p->Process,'image'=>$pi);
				}
				
				$data=(object)array('totalitems'=>$totalitems,'allrirs'=>$models);
				$this->_sendResponse(200, CJSON::encode($data));
				break;
							
				case 'sstcertdata':
							$allcerts=Sstcertbasic::model()->findAll();
								
								$totalitems=count($allcerts);

				if(isset($_GET['pl']))
				{
				$allcerts=Sstcertbasic::model()->findAll(array(
    'order' => 'Id desc',
    'limit' => $_GET['pl'],
    'offset' => ($_GET['pn']-1)*$_GET['pl']
));
				}
											
								
							$models=array();	
							
							foreach($allcerts as $s)
							{
								$testedby="";
							$testsign="";
							if(!empty($s->TestedBy))
							{
							$testsign=empty($s->testedBy->usersignuploads)?"":($s->testedBy->usersignuploads[0]->url);
							$testedby=$s->testedBy->FName." ".$s->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($s->ApprovedBy))
							{
							$approvedsign=empty($s->approvedBy->usersignuploads)?"":($s->approvedBy->usersignuploads[0]->url);
							$approvedby=$s->approvedBy->FName." ".$s->approvedBy->LName;	
							}			
								
								$models[]=(object)array('BatchNo'=>$s->BatchNo,'CertDate'=>$s->CertDate,'Component'=>$s->Component,'Hrs'=>$s->Hrs,
								'Id'=>$s->Id,'LoadedOn'=>$s->LoadedOn,'OnDate'=>$s->OnDate,'Ref'=>$s->Ref,'Remarks'=>$s->Remarks,'SampleNos'=>$s->SampleNos,
								'SerialNo'=>$s->SerialNo,'To'=>$s->To,'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby);
								
								
							}							
										 
										 
							$data=(object)array('totalitems'=>$totalitems,'allrirs'=>$models);
				$this->_sendResponse(200, CJSON::encode($data));
							break;			
				
				
				case 'contsstpredata':
				
				$rirs=Receiptir::model()->findAll();
				
					$allrirs=array();
					$set=Settingslab::model()->findByPk(1);
					
					$ssthrs=Ssthours::model()->findAll();
					$observations=array();
					$sstbasic=(object)array('Interval'=>"24",'CoatingSystem'=>"Delta tone9000+ Delta seal Silver",'SaltSolnConc'=>"50 gm/lit ± 5 gm/lit",
						'FogCollection'=>"1-2 ml/hr",'PhTestSoln'=>"< 6.5",'PhCollectedSample'=>"6.5 to 7.2",'ChemberTemp'=>"35°C ± 20°C",
						'Angle'=>"15°c -25°c",'Note'=>$set->DefaultTestNote,'observations'=>$observations,'Ref'=>" Coating Test certificate No-RFI/QA/7-16/565");
					
					$data=(object)array('allrirs'=>$allrirs,'ssthrs'=>$ssthrs,'basic'=>$sstbasic);
						$this->_sendResponse(200, CJSON::encode($data));
				break;
				
					case 'contsstdata':
							$ssts=Sstbasicinfo::model()->findAll();
							
							$totalitems=count($ssts);

				if(isset($_GET['pl']))
				{
				$ssts=Sstbasicinfo::model()->findAll(array(
    'order' => 'Id desc',
    'limit' => $_GET['pl'],
    'offset' => ($_GET['pn']-1)*$_GET['pl']
));
				}
							
										 $allsst=array();
							foreach($ssts as $d)
							{			 
															
$len=(int)((count($d->sstobservations))/2);

$observations1 = array_slice($d->sstobservations, 0, $len);
$observations2 = array_slice($d->sstobservations, $len);
$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							$basic=(object)array('Id'=>$d->Id,'Angle'=>$d->Angle,'BatchNo'=>$d->BatchNo,'ChemberTemp'=>$d->ChemberTemp,'LoadTime'=>$d->LoadTime,'UnloadTime'=>$d->UnloadTime,
							'CoatingSystem'=>$d->CoatingSystem,'CompleteDate'=>$d->CompleteDate,'CustomerName'=>$d->CustomerName,'FogCollection'=>$d->FogCollection,
							'Interval'=>$d->Interval,'LabNo'=>$d->LabNo,'LoadingDate'=>$d->LoadingDate,'Note'=>$d->Note,'PhCollectedSample'=>$d->PhCollectedSample,
							'PhTestSoln'=>$d->PhTestSoln,'ReceiptOn'=>$d->ReceiptOn,'Ref'=>$d->Ref,'RefStd'=>$d->RefStd,'Remark'=>$d->Remark,'ReportDate'=>$d->ReportDate,
							'ReportNo'=>$d->ReportNo,'SaltSolnConc'=>$d->SaltSolnConc,'Sample'=>$d->Sample,'Status'=>$d->Status,'TestDuration'=>$d->TestDuration,
							'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby);
													
									$allsst[]=(object)array('Id'=>$d->Id,'basic'=>$basic,'observations1'=>$observations1,'observations2'=>$observations2				);

							}
							
							$data=(object)array('totalitems'=>$totalitems,'allrirs'=>$allsst);
							$this->_sendResponse(200, CJSON::encode($data));	
							break;
				default:
					// Model not implemented error
					$this->_sendResponse(501, sprintf(
						'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
						$_GET['model']) );
					Yii::app()->end();
			}
			// Did we get some results?
			if(empty($models)) {
				// No
				$this->_sendResponse(200, 
						sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
			} else {
				// Prepare response
				$rows = array();
				foreach($models as $model)
					$rows[] = $model->attributes;
				// Send the response
				$this->_sendResponse(200, CJSON::encode($rows));
			}
    }
	
	
    public function actionView()
    {
				
			// Check if id was submitted via GET
			if(!isset($_GET['id']))
				$this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing' );
		 
			switch($_GET['model'])
			{
				// Find respective model  
				
					case 'sstcerteditdata':		
				
							$model=Sstcertbasic::model()->findByPk($_GET['id']);
							break;
				
					case 'coatcerteditdata':		
				
							$model=Coatcertbasic::model()->findByPk($_GET['id']);
							break;
				
				case 'contssteditdata':
							$d=Sstbasicinfo::model()->findByPk($_GET['id']);
										
									
								
											
							$sstcond="";
							
							
							$basic=(object)array('Angle'=>$d->Angle,'BatchNo'=>$d->BatchNo,'ChemberTemp'=>$d->ChemberTemp,'CoatingSystem'=>$d->CoatingSystem,
							'CompleteDate'=>$d->CompleteDate,'CustomerName'=>$d->CustomerName,'FogCollection'=>$d->FogCollection,'Id'=>$d->Id,
							'Interval'=>$d->Interval,'LabNo'=>$d->LabNo,'LoadingDate'=>$d->LoadingDate,'Note'=>$d->Note,'Ref'=>$d->Ref,'LoadTime'=>$d->LoadTime,'UnloadTime'=>$d->UnloadTime,
							'PhCollectedSample'=>$d->PhCollectedSample,'PhTestSoln'=>$d->PhTestSoln,'ReceiptOn'=>$d->ReceiptOn,'RefStd'=>$d->RefStd,
							'Remark'=>$d->Remark,'ReportDate'=>$d->ReportDate,'ReportNo'=>$d->ReportNo,'SaltSolnConc'=>$d->SaltSolnConc,
							'Sample'=>$d->Sample,'Status'=>$d->Status,'TestDuration'=>$d->TestDuration,	'observations'=>$d->sstobservations);
						
								
					$model=(object)array('basic'=>$basic, 'cond'=>$sstcond);		
							
						break;	
				
					
				default:
					$this->_sendResponse(501, sprintf(
						'Mode <b>view</b> is not implemented for model <b>%s</b>',
						$_GET['model']) );
					Yii::app()->end();
			}
			// Did we find the requested model? If not, raise an error
			if(is_null($model))
				$this->_sendResponse(404, 'No Item found with id '.$_GET['id']);
			else
				$this->_sendResponse(200, CJSON::encode($model));
    }
	
	
    public function actionCreate()
    {
				
			switch($_GET['model'])
			{
				// Get an instance of the respective model
					case 'searchcontsst':		$model=new Sstbasicinfo;break;
					
					case 'searchcoatcert':		$model=new Coatcertbasic;break;
						
					case 'searchsstcert':		$model=new Sstcertbasic;break;
			
					case 'contsstadd':		$model=new Sstbasicinfo;break;
				
					case 'coatcertadd':		$model=new Coatcertbasic;break;
					
					case 'sstcertadd':		$model=new Sstcertbasic;break;
						   
				default:
					$this->_sendResponse(501, 
						sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>',
						$_GET['model']) );
						Yii::app()->end();
			}
			$json = file_get_contents('php://input'); //$GLOBALS['HTTP_RAW_POST_DATA'] is not preferred: http://www.php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
            $data = CJSON::decode($json,true);//value decode in array format
	        //$this->_sendResponse(200, CJSON::encode($json));
	        switch($_GET['model'])
           {
			   
			   case 'searchcontsst':

$ssts=Sstbasicinfo::model()->findAll();
							
							$totalitems=count($ssts);

				if(isset($data['pageSize']))
				{
				$ssts=Sstbasicinfo::model()->findAll(array(
   					'order' => 'Id desc',
					'limit' => $data['pageSize'],
					'condition'=>'ReportNo LIKE :rn OR  BatchNo LIKE :bn OR LabNo LIKE :ln OR ReceiptOn LIKE :ron OR RefStd LIKE :ref OR ReportDate LIKE :rd OR
					CoatingSystem LIKE :coat OR CustomerName LIKE :cust',
					'params'=>array(':rn'=>'%'.$data['text'].'%',':bn'=>'%'.$data['text'].'%',':ln'=>'%'.$data['text'].'%',':ron'=>$data['text'].'%',
					':ref'=>'%'.$data['text'].'%',':rd'=>$data['text'].'%',':coat'=>'%'.$data['text'].'%',':cust'=>'%'.$data['text'].'%'),));
				}
							
										 $allsst=array();
							foreach($ssts as $d)
							{			 
															
$len=(int)((count($d->sstobservations))/2);

$observations1 = array_slice($d->sstobservations, 0, $len);
$observations2 = array_slice($d->sstobservations, $len);
$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":($d->testedBy->usersignuploads[0]->url);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":($d->approvedBy->usersignuploads[0]->url);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							$basic=(object)array('Id'=>$d->Id,'Angle'=>$d->Angle,'BatchNo'=>$d->BatchNo,'ChemberTemp'=>$d->ChemberTemp,
							'CoatingSystem'=>$d->CoatingSystem,'CompleteDate'=>$d->CompleteDate,'CustomerName'=>$d->CustomerName,'FogCollection'=>$d->FogCollection,
							'Interval'=>$d->Interval,'LabNo'=>$d->LabNo,'LoadingDate'=>$d->LoadingDate,'Note'=>$d->Note,'PhCollectedSample'=>$d->PhCollectedSample,
							'PhTestSoln'=>$d->PhTestSoln,'ReceiptOn'=>$d->ReceiptOn,'Ref'=>$d->Ref,'RefStd'=>$d->RefStd,'Remark'=>$d->Remark,'ReportDate'=>$d->ReportDate,
							'ReportNo'=>$d->ReportNo,'SaltSolnConc'=>$d->SaltSolnConc,'Sample'=>$d->Sample,'Status'=>$d->Status,'TestDuration'=>$d->TestDuration,
							'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby);
													
									$allsst[]=(object)array('Id'=>$d->Id,'basic'=>$basic,'observations1'=>$observations1,'observations2'=>$observations2				);

							}
							
							$data=(object)array('totalitems'=>$totalitems,'allrirs'=>$allsst);
							$this->_sendResponse(200, CJSON::encode($data));	

			   break;
					
					case 'searchcoatcert':	

$allpdirs=Coatcertbasic::model()->findAll();
				
				$totalitems=count($allpdirs);

				// if(isset($_GET['pl']))
				// {
				// $allpdirs=Coatcertbasic::model()->findAll(array(
    // 'order' => 'Id desc',
    // 'limit' => $_GET['pl'],
    // 'offset' => ($_GET['pn']-1)*$_GET['pl']
// ));
				// }
				
				if(isset($data['pageSize']))
				{
				$allpdirs=Coatcertbasic::model()->findAll(array(
   					'order' => 'Id desc',
					'limit' => $data['pageSize'],
					'condition'=>'TCNo LIKE :tcno OR TopCoatDate LIKE :topdate OR Customer LIKE :cust OR PartCoated LIKE :pc OR BatchNo LIKE :bn OR Process LIKE :pro OR RFICode LIKE :rfi OR DINNo LIKE :din',
					'params'=>array(':tcno'=>'%'.$data['text'].'%',':topdate'=>'%'.$data['text'].'%',':cust'=>'%'.$data['text'].'%',':pc'=>'%'.$data['text'].'%',
					':bn'=>$data['text'].'%',':pro'=>'%'.$data['text'].'%',':rfi'=>'%'.$data['text'].'%',':din'=>'%'.$data['text'].'%'),));
				}
				
				
				$models=array();
				foreach($allpdirs as $p)
				{
					$pi=Coatcertuploads::model()->find(array('condition'=>'coatcertid=:Id',
											 'params'=>array(':Id'=>$p->Id),));
					
					$models[]=(object)array('Id'=>$p->Id,'TCNo'=>$p->TCNo,'TopCoatDate'=>$p->TopCoatDate,
					'Customer'=>$p->Customer,'PartCoated'=>$p->PartCoated,'Quantity'=>$p->Quantity,'RFICode'=>$p->RFICode,'DINNo'=>$p->DINNo,
					'BatchNo'=>$p->BatchNo,'Process'=>$p->Process,'image'=>$pi);
				}
				
				$data=(object)array('totalitems'=>$totalitems,'allrirs'=>$models);
				$this->_sendResponse(200, CJSON::encode($data));

					break;
						
					case 'searchsstcert':	
$allcerts=Sstcertbasic::model()->findAll();
								
								$totalitems=count($allcerts);

				// if(isset($_GET['pl']))
				// {
				// $allcerts=Sstcertbasic::model()->findAll(array(
    // 'order' => 'Id desc',
    // 'limit' => $_GET['pl'],
    // 'offset' => ($_GET['pn']-1)*$_GET['pl']
// ));
				// }
				if(isset($data['pageSize']))
				{
				$ssts=Sstcertbasic::model()->findAll(array(
   					'order' => 'Id desc',
					'limit' => $data['pageSize'],
					'condition'=>'`To` LIKE :to OR BatchNo LIKE :bn OR Component LIKE :cm OR LoadedOn LIKE :lon OR Hrs LIKE :hrs OR SerialNo LIKE :sn OR	Ref LIKE :ref OR CertDate LIKE :date',
					'params'=>array(':to'=>'%'.$data['text'].'%',':bn'=>'%'.$data['text'].'%',':cm'=>'%'.$data['text'].'%',':lon'=>$data['text'].'%',
					':hrs'=>$data['text'].'%',':sn'=>'%'.$data['text'].'%',':ref'=>'%'.$data['text'].'%',':date'=>$data['text'].'%'),));
				}
											
								
							$models=array();	
							
							foreach($allcerts as $s)
							{
								$testedby="";
							$testsign="";
							if(!empty($s->TestedBy))
							{
							$testsign=empty($s->testedBy->usersignuploads)?"":($s->testedBy->usersignuploads[0]->url);
							$testedby=$s->testedBy->FName." ".$s->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($s->ApprovedBy))
							{
							$approvedsign=empty($s->approvedBy->usersignuploads)?"":($s->approvedBy->usersignuploads[0]->url);
							$approvedby=$s->approvedBy->FName." ".$s->approvedBy->LName;	
							}			
								
								$models[]=(object)array('BatchNo'=>$s->BatchNo,'CertDate'=>$s->CertDate,'Component'=>$s->Component,'Hrs'=>$s->Hrs,
								'Id'=>$s->Id,'LoadedOn'=>$s->LoadedOn,'OnDate'=>$s->OnDate,'Ref'=>$s->Ref,'Remarks'=>$s->Remarks,'SampleNos'=>$s->SampleNos,
								'SerialNo'=>$s->SerialNo,'To'=>$s->To,'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby);
								
								
							}							
										 
										 
							$data=(object)array('totalitems'=>$totalitems,'allrirs'=>$models);
				$this->_sendResponse(200, CJSON::encode($data));	
				break;
			   
			   
			   case 'contsstadd':
						foreach($data['basic'] as $var=>$value)
				  {
					// Does the model have this attribute? If not raise an error
					if($model->hasAttribute($var))
						$model->$var = $value;
				  }					
					break;
					
					case 'sstcertadd':
						foreach($data as $var=>$value)
				  {
					// Does the model have this attribute? If not raise an error
					if($model->hasAttribute($var))
						$model->$var = $value;
				  }					
					break;	
			   						
				case 'coatcertadd':
						foreach($data as $var=>$value)
				  {
					// Does the model have this attribute? If not raise an error
					if($model->hasAttribute($var))
						$model->$var = $value;
				  }					
					break;	 
			   default:
			// Try to assign POST values to attributes
			   foreach($data as $var=>$value) 
			 {
				// Does the model have this attribute? If not raise an error
				if($model->hasAttribute($var))
					$model->$var = $value;
				else
					$this->_sendResponse(500, 
						sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
						$_GET['model']) );
             }
		   }
		   
		   switch($_GET['model'])
           {
			    case 'searchcontsst':		break;
					
					case 'searchcoatcert':		break;
						
					case 'searchsstcert':		break;
			   
			   
			    case 'sstcertadd':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						$model->CreationDate=date('Y-m-d H:i:s');
						$model->LastModified=date('Y-m-d H:i:s');
						
						
						$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId="17"',
							'params'=>array(':uid'=>$data['ModifiedBy']),));
							if(!empty($uir))
							{
								if($uir->C==='true')
								{
									$model->TestedBy=$data['ModifiedBy'];	
								}
							}		
						
						// $uir=Userinroles::model()->find(array('condition'=>'UserId =:uid ',
							// 'params'=>array(':uid'=>$data['ModifiedBy']),));
							// if(!empty($uir))
							// {
								// if($uir->RoleId==='6')
								// {
									// $model->TestedBy=$data['ModifiedBy'];	
								// }
							// }		
						
						$model->ModifiedBy=$data['ModifiedBy'];
						$model->save(false);
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode("success"));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e));
					}
			   
				break;
			   
			   
			    case 'coatcertadd':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						$model->CreationDate=date('Y-m-d H:i:s');
						$model->LastModified=date('Y-m-d H:i:s');
						$model->save(false);
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($model->Id));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e));
					}
			   
				break;
			   
			   
			    case 'contsstadd':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						$model->TestDuration=$data['basic']['TestDuration']['Hours'];
						$model->LastModified=date('Y-m-d H:i:s');
						
						$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId="15"',
							'params'=>array(':uid'=>$data['ModifiedBy']),));
							if(!empty($uir))
							{
								if($uir->C==='true')
								{
									$model->TestedBy=$data['ModifiedBy'];	
								}
							}		
						
						// $uir=Userinroles::model()->find(array('condition'=>'UserId =:uid ',
							// 'params'=>array(':uid'=>$data['ModifiedBy']),));
							// if(!empty($uir))
							// {
								// if($uir->RoleId==='6')
								// {
									// $model->TestedBy=$data['ModifiedBy'];	
								// }
							// }		
						
						$model->ModifiedBy=$data['ModifiedBy'];
						
						
						
						$model->save(false);
												

						foreach($data['basic']['observations'] as $d)
						{
							
							
							
								$sd=new Sstobservations;
								$sd->SstBasicId=$model->getPrimaryKey();
							
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($sd->hasAttribute($var))
										$sd->$var = $value;
								  }		
							
						
							$sd->save(false);
							
						}

												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($model));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e));
					}
			   
				break;
			   		
								         
				    default:
			// Try to save the model
			if($model->save())
				$this->_sendResponse(200, CJSON::encode($model));
			else {
				// Errors occurred
				$msg = "<h1>Error</h1>";
				$msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
				$msg .= "<ul>";
				foreach($model->errors as $attribute=>$attr_errors) {
					$msg .= "<li>Attribute: $attribute</li>";
					$msg .= "<ul>";
					foreach($attr_errors as $attr_error)
						$msg .= "<li>$attr_error</li>";
					$msg .= "</ul>";
				}
				$msg .= "</ul>";
				$this->_sendResponse(500, $msg );
			}
		 }
		
    }
	
	
    public function actionUpdate()
    {
				 // Parse the PUT parameters. This didn't work: parse_str(file_get_contents('php://input'), $put_vars);
			$json = file_get_contents('php://input'); //$GLOBALS['HTTP_RAW_POST_DATA'] is not preferred: http://www.php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
			$put_vars = CJSON::decode($json,true);  //true means use associative array
		 
			switch($_GET['model'])
			{
				// Find respective model
			case 'approvecontsst':		$model=Sstbasicinfo::model()->findByPk($_GET['id']); 		break;

			case 'approvesstcert':		$model=Sstcertbasic::model()->findByPk($_GET['id']); 		break;				
				
			case 'sstcertupdate':		$model=Sstcertbasic::model()->findByPk($_GET['id']);		break;
							
			case 'contsstupdate':		$model=Sstbasicinfo::model()->findByPk($_GET['id']); 		break;	
				
			case 'coatcertupdate':		$model=Coatcertbasic::model()->findByPk($_GET['id']); 		break;	
						
				default:
					$this->_sendResponse(501, 
						sprintf( 'Error: Mode <b>update</b> is not implemented for model <b>%s</b>',
						$_GET['model']) );
					Yii::app()->end();
			}
			// Did we find the requested model? If not, raise an error
			if($model === null)
				$this->_sendResponse(400, 
						sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
						$_GET['model'], $_GET['id']) );
		 
		 switch($_GET['model'])
			{
		
			case 'approvecontsst':$data=$put_vars;break;
			case 'approvesstcert':$data=$put_vars;break;
		
			case 'contsstupdate':$data=$put_vars;break;
			
			case 'sstcertupdate':
			$data=$put_vars;
						foreach($put_vars as $var=>$value)
				  {
					// Does the model have this attribute? If not raise an error
					if($model->hasAttribute($var))
						$model->$var = $value;
				  }					
					break;	 
			
			case 'coatcertupdate':
						foreach($put_vars as $var=>$value)
				  {
					// Does the model have this attribute? If not raise an error
					if($model->hasAttribute($var))
						$model->$var = $value;
				  }					
					break;	 
		 
			default :
			// Try to assign PUT parameters to attributes
			foreach($put_vars as $var=>$value) {
				// Does model have this attribute? If not, raise an error
				if($model->hasAttribute($var))
					$model->$var = $value;
				else {
					$this->_sendResponse(500, 
						sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>',
						$var, $_GET['model']) );
				}
			}
			
			
			}
			// Try to save the model
			
			
			 switch($_GET['model'])
			{
				
			case 'approvecontsst':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
									
							$uir=Userinroles::model()->find(array('condition'=>'UserId =:uid ',
							'params'=>array(':uid'=>$data['ApprovedBy']),));
							if(!empty($uir))
							{
								if($uir->RoleId==='7')
								{
									$model->ApprovedBy=$data['ApprovedBy'];	
								}
							}		
							
						
						$model->save(false);
						
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;				
				
			case 'approvesstcert':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
									
							$uir=Userinroles::model()->find(array('condition'=>'UserId =:uid ',
							'params'=>array(':uid'=>$data['ApprovedBy']),));
							if(!empty($uir))
							{
								if($uir->RoleId==='7')
								{
									$model->ApprovedBy=$data['ApprovedBy'];	
								}
							}		
							
						
						$model->save(false);
						
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;				
			 case 'sstcertupdate':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						$model->LastModified=date('Y-m-d H:i:s');
						$uir=Userinroles::model()->find(array('condition'=>'UserId =:uid ',
							'params'=>array(':uid'=>$data['ModifiedBy']),));
							if(!empty($uir))
							{
								if($uir->RoleId==='6')
								{
									$model->TestedBy=$data['ModifiedBy'];	
								}
							}		
						
						$model->ModifiedBy=$data['ModifiedBy'];
						$model->save(false);
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($model->Id));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e));
					}
			   
				break;			
		 case 'coatcertupdate':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						$model->LastModified=date('Y-m-d H:i:s');
						$model->save(false);
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($model->Id));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e));
					}
			   
				break;		
				
				
		case 'contsstupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						 foreach($data['basic'] as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($model->hasAttribute($var))
										$model->$var = $value;
								  }
								  
								  
						$remark="failed";
						$status="pending";
						
						if($data['basic']['Remark'] ==='passed')
							{
								$remark="passed";
							}
						if($data['basic']['Remark'] ==='pending')
							{
								$remark="pending";
							}
						
						if($remark==='passed')
						{
							$status="complete";
						}		  
						
							$uir=Userinroles::model()->find(array('condition'=>'UserId =:uid ',
							'params'=>array(':uid'=>$data['ModifiedBy']),));
							if(!empty($uir))
							{
								if($uir->RoleId==='6')
								{
									$model->TestedBy=$data['ModifiedBy'];	
								}
							}		
						
						$model->ModifiedBy=$data['ModifiedBy'];
						$model->Note=$data['basic']['Note'];		  
						$model->Remark=$remark;
						$model->LastModified=date('Y-m-d H:i:s');
						$model->Status=$status;
						$model->save(false);
						
						foreach($data['basic']['observations'] as $d)
						{
							
							
							if(!empty($d['Id']) || isset($d['Id']))
							{
								$sd=Sstobservations::model()->findByPk($d['Id']);
							}
							else
							{
								$sd=new Sstobservations;
								$sd->SstBasicId=$model->getPrimaryKey();
							}	
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($sd->hasAttribute($var))
										$sd->$var = $value;
								  }		
							
						
							$sd->save(false);
							
						}
						
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;
			default:
			if($model->save())
				$this->_sendResponse(200, CJSON::encode($model));
			else
				// prepare the error $msg
				// see actionCreate
				// ...
				$msg="errot";
				$this->_sendResponse(500, $msg );
			}
    }
	
	
    public function actionDelete()
    {
				switch($_GET['model'])
			{
				// Load the respective model
				
				case 'dropsstcert':
					$model = Sstcertbasic::model()->findByPk($_GET['id']);                    
					break;
				
				
				case 'dropcoatcert':
					$model = Coatcertbasic::model()->findByPk($_GET['id']);                    
					break;
				
				case 'delcontsst':
					$model = Sstbasicinfo::model()->findByPk($_GET['id']);                    
					break;
			
					
				default:
					$this->_sendResponse(501, 
						sprintf('Error: Mode <b>delete</b> is not implemented for model <b>%s</b>',
						$_GET['model']) );
					Yii::app()->end();
			}
			// Was a model found? If not, raise an error
			if($model === null)
				$this->_sendResponse(400, 
						sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
						$_GET['model'], $_GET['id']) );
		 
			// Delete the model
			$num = $model->delete();
			if($num>0)
				$this->_sendResponse(200, $num);    //this is the only way to work with backbone
			else
				$this->_sendResponse(500, 
						sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
						$_GET['model'], $_GET['id']) );
    }
	
	
	private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
{
    // set the status
    $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
    header($status_header);
    // and the content type
    header('Content-type: ' . $content_type);
 
    // pages with body are easy
    if($body != '')
    {
        // send the body
        echo $body;
    }
    // we need to create the body if none is passed
    else
    {
        // create some body messages
        $message = '';
 
        // this is purely optional, but makes the pages a little nicer to read
        // for your users.  Since you won't likely send a lot of different status codes,
        // this also shouldn't be too ponderous to maintain
        switch($status)
        {
            case 401:
                $message = 'You must be authorized to view this page.';
                break;
            case 404:
                $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                break;
            case 500:
                $message = 'The server encountered an error processing your request.';
                break;
            case 501:
                $message = 'The requested method is not implemented.';
                break;
        }
 
        // servers don't always have a signature turned on 
        // (this is an apache directive "ServerSignature On")
        $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];
 
        // this should be templated in a real-world solution
        $body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
</head>
<body>
    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
    <p>' . $message . '</p>
    <hr />
    <address>' . $signature . '</address>
</body>
</html>';
 
        echo $body;
    }
    Yii::app()->end();
}
	
	
	
	       private function _getStatusCodeMessage($status)
{
    // these could be stored in a .ini file and loaded
    // via parse_ini_file()... however, this will suffice
    // for an example
    $codes = Array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
    );
    return (isset($codes[$status])) ? $codes[$status] : '';
}


private function _checkAuth()
{
    // Check if we have the USERNAME and PASSWORD HTTP headers set?
    if(!(isset($_SERVER['HTTP_X_USERNAME']) and isset($_SERVER['HTTP_X_PASSWORD']))) {
        // Error: Unauthorized
        $this->_sendResponse(401);
    }
    $username = $_SERVER['HTTP_X_USERNAME'];
    $password = $_SERVER['HTTP_X_PASSWORD'];
    // Find the user
    $user=User::model()->find('LOWER(username)=?',array(strtolower($username)));
    if($user===null) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Name is invalid');
    } else if(!$user->validatePassword($password)) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Password is invalid');
    }
}
	
}

?>