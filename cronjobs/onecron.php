<?php

	try
				{		


					
								
						
					
						
						function getcronjob()
						{
								$fields=[];
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, "http://localhost/tcwuerth/admin/api/runapprovetest");
							//curl_setopt($ch, CURLOPT_HTTPHEADER, $authHeaders);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
							curl_setopt($ch, CURLOPT_HEADER, FALSE);
							curl_setopt($ch, CURLOPT_POST, TRUE);
							curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

							$response = curl_exec($ch);
						
							if($response === false)
								{
										curl_close($ch);
									echo 'Curl error: ' . curl_error($ch);
									//$this->_sendResponse(401, CJSON::encode(curl_error($ch)));
								}
								else
								{
									
									$resp=json_decode($response);
									echo "<pre>"; print_r($response); echo "</pre>";
								
										curl_close($ch);
										
								}
							
						}
						ini_set('max_execution_time', 0);		
						
						getcronjob();
						header("refresh: 2");
    
echo date('H:i:s Y-m-d');
						
					
				}
			
					catch(Exception $e)
					{						
						//$transaction->rollback();
						echo $e->getMessage();
					}	
					
					