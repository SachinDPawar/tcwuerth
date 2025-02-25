
App.controller('certificateController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,$timeout,alldata)
{
	'use strict';
	
	$scope.certs=[];
	$scope.animationsEnabled = true;
	$scope.pageSize=25;
	$scope.currentPage=1;
	$scope.flags={};
	$scope.flags.createpdf=false;
	$scope.mecht={proofload:"Proofload (KN)"};
	$scope.myflag={};
	$scope.myflag.heatno="Heat No.";
if(angular.isDefined(alldata.data))
{
	
	$scope.certs=alldata.data.allcerts;
	$scope.totalitems=alldata.data.totalitems;
	//console.log(alldata.data);
}
	
  $scope.certificateedit=function(param,id)
  {
	  console.log(id);
	 
	if(param==='new')
	{ console.log(param);
		$state.go('app.certificateadd');
	}
	else
	{
		 console.log(param);
		$state.go('app.certificateedit',{id:id});
	}
	  
	  
  }
  
   $scope.showview = function (cert) 
   {
	   
	   $scope.cert={};
	   
	   
	   $http({	
					method:'PUT',
					url:$rootScope.apiurl+'certapi/getcertinfo/'+cert.basic.Id,
					data:{}	
	     	 
	     	}).then(function successCallback(data) {
					console.log(data);
				$scope.cert=data.data;
				
				 $scope.modalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'templates/certificate/viewcertificate.html',
	  backdrop:"static",
      scope:$scope,
      size: 'lg',
     
    });
			     				
	     		}, function errorCallback(data) {
	     			console.log(data);
					$scope.flags.loadingdata=false;
	     		});
				
				



  
   }
 
   $scope.cancel = function () {
    $scope.modalInstance.dismiss('cancel');
  };
  
  
  // $scope.getallcerts=function()
	// {
			// $http({	
					// method:'GET',
					// url:$rootScope.apiurl+'certapi/cert',	
	     	 
	     		// }).success(function(data){
					// //console.log(data);
					// $scope.certs=data;
			     				
	     		// }, function errorCallback(data) {
	     			// console.log(data);
	     		// });
	// }
	
	$scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		//console.log(pageno);
		$http({	
					method:'GET',
					url:$rootScope.apiurl+'certapi/cert/'+$scope.pageSize+'/'+pageno,	
	     	 
	     	}).then(function successCallback(data) {
					console.log(data);
				$scope.certs=data.data.allcerts;
		$scope.totalitems=data.data.totalitems;
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			console.log(data);
					$scope.flags.loadingdata=false;
	     		});
		
	}
	
	
  
  
  $scope.closeconfirmModal3=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance3.dismiss('cancel');
 	}
  
  $scope.approvecert=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.basic.Id;
			$scope.Test=id.basic.TCNo;
			
			$scope.modalInstance3 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'approveModal',
				scope:$scope,
     	
				});
		}
		else if(con=='approve')
		{
			$scope.oneclick=true;
			
			$http({
		    		 method:'PUT',
		       		 url:$rootScope.apiurl+'certapi/approvecert/'+id,
					 data:{ApprovedBy:$rootScope.dboard.uid}
		        
		   			}).then(function successCallback(data) {
					 console.log(data);
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal3();
		        }, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
				});
		}

	}

  
   $scope.closeconfirmModal4=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance4.dismiss('cancel');
 	}
  
  $scope.checkcert=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.basic.Id;
			$scope.Test=id.basic.TCNo;
			$scope.modalInstance4 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'checkModal',
				scope:$scope,
     	
				});
		}
		else if(con=='approve')
		{
			$scope.oneclick=true;
			
			$http({
		    		 method:'PUT',
		       		 url:$rootScope.apiurl+'certapi/checkcert/'+id,
					 data:{CheckedBy:$rootScope.dboard.uid}
		        
		   			}).then(function successCallback(data) {
					 console.log(data);
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal4();
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
				});
		}

	}
	
  $scope.createpdf = function (item) {
	  $scope.flags.createpdf=true;
	  console.log(item);
/*Code to generate pdf*/
 $http({
		    		 method:'GET',
		       		 url:$rootScope.apiurl+'certpdf/createpdf/'+item.basic.Id,
					 //data:{CheckedBy:$rootScope.dboard.uid}
		        
		   		 	}).then(function successCallback(data) {
					 $scope.exportpdf(item);
					console.log(data);
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.flags.createpdf=false;
						//$scope.oneclick=false;
				});
/*Code to generate pdf ends here*/  

};
  
  $scope.exportpdf=function(item)
  {
	  
	   
	  console.log(item);
	  $http({
		    		 method:'GET',
		       		 url:$rootScope.apiurl+'certpdf/exportpdf/'+item.basic.Id,
					 //data:{CheckedBy:$rootScope.dboard.uid}
		        
		   		 	}).then(function successCallback(data) {
					 $scope.flags.createpdf=false;
					console.log(data);
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.flags.createpdf=false;
						//$scope.oneclick=false;
				});
  }
  
  
    $scope.oneclick=false;
  $scope.deletecert=function(con,item)
	{
console.log(item);
		if(con=='confirm')
		{
			$scope.confirmdelModal = true;
			$scope.cert=item
			//$scope.Test=id.PartName;
			$scope.modalInstanced2 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'delcertModal',
				scope:$scope,
     	
				});
		}
		else if(con=='delete')
		{
			$scope.oneclick=true;
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'certapi/delcert/'+item.basic.Id,
		        
		   		 	}).then(function successCallback(data) {
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closedelconfirmModal();
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
				});
		}

	}

	$scope.closedelconfirmModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstanced2.dismiss('cancel');
 	}

  
  $scope.closepdfModal=function()
 	{
		$scope.oneclick=false;
 		$scope.pdfmodalInstance.dismiss('cancel');
 	}
  

  
	$scope.searchdata=function(searchtext)
{
	if(!_.isEmpty(searchtext))
	{
	$scope.flags.loadingdata=true;
	var pageno=1;
		//console.log(pageno);
		$http({	
					method:'POST',
					url:$rootScope.apiurl+'certapi/searchcert/',	
					data:{text:searchtext,pageSize:$scope.pageSize}
	     	 
	     			}).then(function successCallback(data) {
					//console.log(data);
				$scope.certs=data.data.allcerts;
	$scope.totalitems=data.data.totalitems;
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			console.log(data);
					$scope.flags.loadingdata=false;
	     		});
	}
	
}

$scope.refreshdata=function()
{
	$state.reload();//('app.bcregdetails',{});
	
}
  
  $scope.mailflag={};
   $scope.mailflag.sending=false;
  $scope.sendmail=function(param, con)
	{
		if(con=='confirm')
		{
			$scope.confirmModal = true;
			
			$scope.mailinfo=param;
			$scope.mailinfo.MailTo=param.CustEmail;
			$scope.confirmmailmodal = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'templates/common/confirmmailmodal.html',
				scope:$scope,
     	
				});
		}
		else if(con=='send')
		{
			 $scope.mailflag.sending=true;
			 console.log(param);
			$http({
		    		 method:'PUT',
		       		 url:'admin/certapi/sendcertmail/'+param.Id,
					data:param
		   		 }).then(function successCallback(data) {
					 
					 toaster.pop({toasterId:11,type:"success",body:data.data.msg});
					 $scope.mailflag.sending=false;
					$scope.closesendmailmodal();
				
		        	}, function errorCallback(data) {
						toaster.pop({toasterId:11,type:"error",body:data.data.msg});
		        	
						 $scope.mailflag.sending=false;
				});
		}
	}
	
	$scope.closesendmailmodal=function()
 	{
		 $scope.mailflag.sending=false;
 		$scope.confirmmailmodal.dismiss('cancel');
 	}

  
});

App.filter('objectToArray', function() {
            return function(input) {
                var array = [];
                for (var key in input) {
                    if (input.hasOwnProperty(key)) {
                        array.push({ key: key, value: input[key] });
                    }
                }
                return array;
            };
        });

App.controller('certificateaddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,configparam,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	//,allpredata
	'use strict';

var exists=$localstorage.getObject(configparam.AppName);
$scope.isflag={addcert:true};
	
	
	$scope.flags={};
	$scope.flags.editflag=false;
	$scope.oneclick=false;
	 $scope.status = {
    isCustomHeaderOpen: true,
    isFirstOpen: true,
    isFirstDisabled: false
  }; 
   $scope.oneAtATime = false;
    $scope.popup = {
    certdate: false,
	podate:false,
	rfidate:false
  };
   $scope.opencert = function() {
    $scope.popup.certdate = true;
  };
  $scope.openpo = function() {
    $scope.popup.podate = true;
  };
  $scope.openrfi = function() {
    $scope.popup.rfidate = true;
  };
 

   $scope.imageerror=function(file)
	{
		console.log(file);
		console.log(file.name); 
		console.log(file.error);
		if(angular.isDefined(file.error))
		{
			//console.log("invalid file");
			$scope.imageflags.error=true;
			toaster.pop('error', "", "File size too large or not a valid file type. Only JPG & PNG files allowed.");
			file.$cancel();

		}
	}		
		
   
     $scope.getrirdetails=function()
  {
	    $http({
		  
					method:'POST',
					url: $rootScope.apiurl+'certapi/getrirs',
					data:{}					
					}).then(function successCallback(data) {
				console.log(data);
				$scope.allrirs=data.data.allrirs;
			 	
					}, function errorCallback(data) {
					console.log(data);
					$scope.sectionview=false;
					 $scope.flags.loadwait=false;
		
				}); 
  }
  
   $scope.arrangecert=function(certdata)
  {  
	    $scope.cert.sections=certdata;
		console.log( $scope.cert.sections);
			
  }
  
  
  $scope.loadallsections=function(bcs)
  {
	  console.log(bcs);
	  $scope.cert.sections={};
	 
		   $scope.flags.loadwait=true;
		  $http({
		  
					method:'POST',
					url: $rootScope.apiurl+'certapi/loadcertsects',
					data:{bcs:bcs,certtype:$scope.cert.basic.CertType}					
					}).then(function successCallback(data) {
				console.log(data);
				 $scope.flags.loadwait=false;
				
				 $scope.alllabs=data.data.alllabs;
				
				
				$scope.arrangecert(data.data);
			
				$scope.sectionview=true;
				 $scope.flags.loadwait=false;
			 	//$scope.getpages();
				//$state.go('app.certificates');
			 	
					}, function errorCallback(data) {
					console.log(data);
					$scope.sectionview=false;
					 $scope.flags.loadwait=false;
		//toaster.pop('error', "", "You are not authorized to view category Please Login");
				}); 
	  
  }
  
  
  
	if(angular.isDefined(allpredata.data))
	{
		console.log(allpredata.data);
		$scope.allrirs=[];//allpredata.data.allrirs;
		$scope.allcusts=allpredata.data.allcusts;
		$scope.allformats=allpredata.data.allformats;
		$scope.allextsections=allpredata.data.allextsections;
		$scope.allcertformats=allpredata.data.allcertformats;
		$scope.allcerttypes=allpredata.data.allcerttypes;
		$scope.alltestinfo=['Top','Middle','Bottom'];
		
		$scope.cert={};	
		$scope.cert.basic={TCFormat:"3.1",CertType:"Normal",Rirs:[]};
		$scope.cert.basicextra={};
		$scope.cert.basic.CertDate=new Date();
		$scope.cert.basicextra.PoDate=new Date();
		$scope.cert.basicextra.RFIDate=new Date();
		$scope.cert.sections={chemicals:{},mechanicals:{},externals:{'Section':"External",'ExtTests':[]}};	
		
		$scope.path='images/certattachments/index.php';
		$scope.options = { url:$scope.path};
		$scope.queue=[];

$scope.getrirdetails();
	}
	
	
  $scope.itemLimit=50;
  
  $scope.addtocert=function(param,param2)
  {
	  console.log(param);
	  var keyword=param.KeyWord;
	   console.log( $scope.cert.sections.externals);
	   param.Selected=1;
	 $scope.cert.sections.externals[keyword]=(param);
	//  $scope.cert.sections.externals[keyword].Selected=true;
	  console.log( $scope.cert.sections);
  }
  
   $scope.removeexttest=function(param2)
  {
	  
	 //  param.Selected=0;
	 $scope.cert.sections.externals[param2].Selected=0;
	  console.log(param2);
	 $scope.cert.sections.externals.ExtTests.splice();
  }
 

 /********************External Update*********************/
  

	$scope.addrownm=function()
	{
		$scope.cert.sections.externals['NM'].Test.Observations.push({HeatNo:"",Parameter:"",Required:"",Observation:"",Remark:"OK"});
	}
      
	  $scope.deletenmrow=function(item,index)
	  {
		  $scope.cert.sections.externals['NM'].Test.Observations.splice(index, 1);
		  if(angular.isDefined(item.Id))
		  {
			  $scope.cert.sections.externals['NM'].Test.delobservations.push(item);
		  }
	  }
    
  
  
  
  $scope.addrowme=function()
	{
		$scope.cert.sections.externals['ME'].Test.Observations.push({BatchCode:"",Parameter:"",Required:"",Observation:"",Remark:"OK"});
	}
      
	  $scope.deletemerow=function(item,index)
	  {
		  $scope.cert.sections.externals['ME'].Test.Observations.splice(index, 1);
		  if(angular.isDefined(item.Id))
		  {
			  $scope.cert.sections.externals['ME'].Test.delobservations.push(item);
		  }
	  }
    
 
  
  $scope.addrowmpi=function()
	{
		$scope.cert.sections.externals['MPI'].Test.Observations.push({Parameter:"",Required:"",Observation:"",Remark:"OK"});
	}
      

      $scope.deletempirow=function(item,index)
	  {
		  $scope.cert.sections.externals['MPI'].Test.Observations.splice(index, 1);
		  if(angular.isDefined(item.Id))
		  {
			  $scope.cert.sections.externals['MPI'].Test.delobservations.push(item);
		  }
	  }
 
  
  
  
  $scope.addrownd=function()
	{
		$scope.cert.sections.externals['ND'].Test.Observations.push({Parameter:"",Required:"",Observation:"",Remark:"OK"});
	}
      
  $scope.deletendrow=function(item,index)
  {
	  $scope.cert.sections.externals['ND'].Test.Observations.splice(index, 1);
	  if(angular.isDefined(item.Id))
	  {
		  $scope.cert.sections.externals['ND'].Test.delobservations.push(item);
	  }
  }
    
	
	
	  
  $scope.addrowhard=function()
	{
		$scope.cert.sections.externals['H'].Test.Observations.push({Parameter:"",BatchCode:"",Requirment:"",Observation:"",Remark:"OK"});
	}
      
  $scope.deletehardrow=function(item,index)
  {
	  $scope.cert.sections.externals['H'].Test.Observations.splice(index, 1);
	  if(angular.isDefined(item.Id))
	  {
		  $scope.cert.sections.externals['H'].Test.delobservations.push(item);
	  }
  }
    
 
  
  
  $scope.addrowst=function()
	{
		$scope.cert.sections.externals['ST'].Test.Observations.push({Painting:"",Coat:"",Remark:"OK"});
	}
      
  $scope.deletestrow=function(item,index)
  {
	  $scope.cert.sections.externals['ST'].Test.Observations.splice(index, 1);
	  if(angular.isDefined(item.Id))
	  {
		  $scope.cert.sections.externals['ST'].Test.delobservations.push(item);
	  }
  }
  
  
  
  $scope.addrowod=function()
	{
		$scope.cert.sections.externals['OD'].Test.Observations.push({Parameter:"",Required:"",Observation:"",Remark:"OK"});
	}
      
  $scope.deleteodrow=function(item,index)
  {
	  $scope.cert.sections.externals['OD'].Test.Observations.splice(index, 1);
	  if(angular.isDefined(item.Id))
	  {
		  $scope.cert.sections.externals['OD'].Test.delobservations.push(item);
	  }
  }
  
 
  
  $scope.imageflags={};
  $scope.saveimage=function()
	{
		console.log('saving image..');
		if($scope.queue.length>0)
		{
		console.log('found Queue');
			for(var i=0;i<$scope.queue.length;i++)
			{
				if(angular.isUndefined($scope.queue[i].id))
				{
				console.log('found image..');
				var file=$scope.queue[i];
				file=_.extend(file,{"certid":$scope.certid});
				
				var result=file.$submit();
				console.log(result);
				$scope.imageflags.error=false;
				}
					else
				{
					$state.go('app.certificates',{},{ reload: true });
				}
							
			}
		}
		else
		{
			console.log('found Queue empty');
		//$scope.flags.oneclick=false;
		$state.go('app.certificates');
		//$scope.cancel();
		}
		
	}

	
	
	$scope.$on('fileuploadstop', function()
	{
		//$scope.flags.oneclick=false;
		$state.go('app.certificates',{},{ reload: true });
		//$scope.cancel();
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
	data.formData = {certid: data.files[0].certid};
 	});
	
	


	$scope.deletefile=function(index,file)
	{
			file.$cancel();
			$scope.number++;	
	}

 $scope.certid="";
  $scope.saveallcert=function(basic)
  {
	console.log(basic);  
	basic.PreparedBy=exists.uid;
	$scope.flags.editflag=true;
	$scope.oneclick=true;
	 $http({
					method:'POST',
					url: $rootScope.apiurl+'certapi/certbasicadd',
					data:basic					
					}).then(function successCallback(data) {
				console.log(data.data);
			 	//$scope.getpages();
				
				
				
				if(_.isEmpty($scope.queue))
			{
				$scope.flags.editflag=false;
				$scope.oneclick=false;
				$state.go('app.certificates');
				toaster.pop('success', "", "Data added successfully.");
			}
			else
			{
				console.log("image");
				$scope.certid=angular.fromJson(data.data);
				$scope.saveimage();
				
			}
			
			
			 	
					}, function errorCallback(data) {
					console.log(data);
						$scope.flags.editflag=false;
					$scope.oneclick=false;
		//toaster.pop('error', "", "You are not authorized to view category Please Login");
				}); 
  }
  
});

App.controller('certificateeditController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,configparam,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	//,allpredata
	'use strict';
	console.log("edit cert");
$scope.formats=[{Format:"3.1",TCFormat:" 3.1 CERTIFICATE AS PER EN 10204 "},{Format:"3.2",TCFormat:" 3.2 CERTIFICATE AS PER EN 10204 "}];
	$scope.cert={};
	$scope.allrirs=[];
	$scope.attachcats=[];
	
	 $scope.allcusts=[];
	$scope.flags={}
	$scope.flags.editflag=false;
	 $scope.flags.loadwait=false;
	$scope.sectionview=false;
	$scope.oneclick=false;
	 $scope.status = {
    isCustomHeaderOpen: true,
    isFirstOpen: true,
    isFirstDisabled: false
  }; 
  
  var exists=$localstorage.getObject(configparam.AppName);
   $scope.oneAtATime = false;
   
   $scope.limitedItems = function() {
    const regularItems = $scope.allrirs.filter(item => !$scope.cert.basic.Rirs.includes(item));
    return regularItems.slice(0, $scope.itemLimit);
};
   $scope.itemLimit=20;
   
   $scope.path='images/certattachments/index.php';
		$scope.options = { url:$scope.path};
		$scope.queue=[];
		$scope.tempqueue=[];
    $scope.loadcert=function()
  {
	 
		
		$scope.allcusts=allpredata.data.allcusts;
		$scope.allformats=allpredata.data.allformats;
		$scope.allextsections=allpredata.data.allextsections;
		$scope.allcertformats=allpredata.data.allcertformats;
		
		$scope.cert=allpredata.data.cert;	
		
		
		$scope.cert.basic.CertDate=new Date($scope.cert.basic.CertDate);
		$scope.cert.basicextra.PoDate=new Date($scope.cert.basicextra.PoDate);
		$scope.cert.basicextra.RFIDate=new Date($scope.cert.basicextra.RFIDate);
		// $scope.cert.sections={chemicals:{},mechanicals:{},externals:{'Section':"External",'ExtTests':[]}};	
		
		
		 
		console.log(allpredata.data);
		 $scope.allcusts=allpredata.data.allcusts;
		 console.log($scope.allcusts);
		  $scope.alllabs=allpredata.data.alllabs;
		// $scope.allrirs=allpredata.data.allrirs;
		// $scope.cert=allpredata.data.certdata;
		  // $scope.cert.basic={};
		  // $scope.cert.basic.Id=allpredata.data.CertBasicId;
		  // $scope.cert.basic.CustomerId=allpredata.data.CustomerId;
		  // $scope.cert.basic.Rirs=allpredata.data.Rirs;
	
	
	
		
	
  }
    
 
 /********************External Update*********************/
  

	$scope.addrownm=function()
	{
		$scope.cert.sections.externals['NM'].Test.Observations.push({HeatNo:"",Parameter:"",Required:"",Observation:"",Remark:"OK"});
	}
      
	  $scope.deletenmrow=function(item,index)
	  {
		  $scope.cert.sections.externals['NM'].Test.Observations.splice(index, 1);
		  if(angular.isDefined(item.Id))
		  {
			  $scope.cert.sections.externals['NM'].Test.delobservations.push(item);
		  }
	  }
    
  
  
  
  $scope.addrowme=function()
	{
		$scope.cert.sections.externals['ME'].Test.Observations.push({BatchCode:"",Parameter:"",Required:"",Observation:"",Remark:"OK"});
	}
      
	  $scope.deletemerow=function(item,index)
	  {
		  $scope.cert.sections.externals['ME'].Test.Observations.splice(index, 1);
		  if(angular.isDefined(item.Id))
		  {
			  $scope.cert.sections.externals['ME'].Test.delobservations.push(item);
		  }
	  }
    
 
  
  $scope.addrowmpi=function()
	{
		$scope.cert.sections.externals['MPI'].Test.Observations.push({Parameter:"",Required:"",Observation:"",Remark:"OK"});
	}
      

      $scope.deletempirow=function(item,index)
	  {
		  $scope.cert.sections.externals['MPI'].Test.Observations.splice(index, 1);
		  if(angular.isDefined(item.Id))
		  {
			  $scope.cert.sections.externals['MPI'].Test.delobservations.push(item);
		  }
	  }
 
  
  
  
  $scope.addrownd=function()
	{
		$scope.cert.sections.externals['ND'].Test.Observations.push({Parameter:"",Required:"",Observation:"",Remark:"OK"});
	}
      
  $scope.deletendrow=function(item,index)
  {
	  $scope.cert.sections.externals['ND'].Test.Observations.splice(index, 1);
	  if(angular.isDefined(item.Id))
	  {
		  $scope.cert.sections.externals['ND'].Test.delobservations.push(item);
	  }
  }
    
	
	
	  
  $scope.addrowhard=function()
	{
		$scope.cert.sections.externals['H'].Test.Observations.push({Parameter:"",BatchCode:"",Requirment:"",Observation:"",Remark:"OK"});
	}
      
  $scope.deletehardrow=function(item,index)
  {
	  $scope.cert.sections.externals['H'].Test.Observations.splice(index, 1);
	  if(angular.isDefined(item.Id))
	  {
		  $scope.cert.sections.externals['H'].Test.delobservations.push(item);
	  }
  }
    
 
  
  
  $scope.addrowst=function()
	{
		$scope.cert.sections.externals['ST'].Test.Observations.push({Painting:"",Coat:"",Remark:"OK"});
	}
      
  $scope.deletestrow=function(item,index)
  {
	  $scope.cert.sections.externals['ST'].Test.Observations.splice(index, 1);
	  if(angular.isDefined(item.Id))
	  {
		  $scope.cert.sections.externals['ST'].Test.delobservations.push(item);
	  }
  }
  
  
  
  $scope.addrowod=function()
	{
		$scope.cert.sections.externals['OD'].Test.Observations.push({Parameter:"",Required:"",Observation:"",Remark:"OK"});
	}
      
  $scope.deleteodrow=function(item,index)
  {
	  $scope.cert.sections.externals['OD'].Test.Observations.splice(index, 1);
	  if(angular.isDefined(item.Id))
	  {
		  $scope.cert.sections.externals['OD'].Test.delobservations.push(item);
	  }
  }
  
 
   
     $scope.getrirdetails=function()
  {
	    $http({
		  
					method:'POST',
					url: $rootScope.apiurl+'certapi/getrirs',
					data:{}					
					}).then(function successCallback(data) {
				console.log(data);
				$scope.allrirs=data.data.allrirs;
			 	 $scope.loadcert();
					}, function errorCallback(data) {
					console.log(data);
					$scope.sectionview=false;
					 $scope.flags.loadwait=false;
		
				}); 
  }
  
   
 
  
    $scope.getrirdetails();
	
  
 

	 $scope.popup = {
    certdate: false,
	podate:false,
	rfidate:false
  };
   $scope.opencert = function() {
    $scope.popup.certdate = true;
  };
  $scope.openpo = function() {
    $scope.popup.podate = true;
  };
  $scope.openrfi = function() {
    $scope.popup.rfidate = true;
  };
  

   
     $scope.arrangecert=function(certdata)
  {  
	    $scope.cert.sections.chemicals=certdata.chemicals;
	    $scope.cert.sections.mechanicals=certdata.mechanicals;
		console.log( $scope.cert.sections);
			
  }
  
  
  
  $scope.addtocert=function(param,param2)
  {
	  console.log(param);
	  var keyword=param.KeyWord;
	   console.log( $scope.cert.sections.externals);
	   param.Selected=1;
	 $scope.cert.sections.externals[keyword]=(param);
	//  $scope.cert.sections.externals[keyword].Selected=true;
	  console.log( $scope.cert.sections);
  }
  
   $scope.removeexttest=function(param2)
  {
	  
	 //  param.Selected=0;
	 $scope.cert.sections.externals[param2].Selected=0;
	  console.log(param2);
	 $scope.cert.sections.externals.ExtTests.splice();
  }
 

  
  
  $scope.loadallsections=function(bcs)
  {
	  console.log(bcs);
	 // $scope.cert.sections={};
	 
		   $scope.flags.loadwait=true;
		  $http({
		  
					method:'POST',
					url: $rootScope.apiurl+'certapi/loadcertsects',
					data:{bcs:bcs,certtype:$scope.cert.basic.CertType}					
					}).then(function successCallback(data) {
				console.log(data);
				 $scope.flags.loadwait=false;
				
				 $scope.alllabs=data.data.alllabs;
				
				
				$scope.arrangecert(data.data);
			
				$scope.sectionview=true;
				 $scope.flags.loadwait=false;
			 	//$scope.getpages();
				//$state.go('app.certificates');
			 	
					}, function errorCallback(data) {
					console.log(data);
					$scope.sectionview=false;
					 $scope.flags.loadwait=false;
		//toaster.pop('error', "", "You are not authorized to view category Please Login");
				}); 
	  
  }
  
  
  
 
	$scope.imageflags={};
  $scope.saveimage=function()
	{
		console.log('saving image..');
		if($scope.queue.length>0)
		{
		console.log('found Queue');
			for(var i=0;i<$scope.queue.length;i++)
			{
				if(angular.isUndefined($scope.queue[i].id))
				{
				console.log('found image..');
				var file=$scope.queue[i];
				file=_.extend(file,{"certid":$scope.certid});
				
				var result=file.$submit();
				console.log(result);
				$scope.imageflags.error=false;
				}
					else
				{
					$state.go('app.certificates',{},{ reload: true });
				}
							
			}
		}
		else
		{
			console.log('found Queue empty');
		//$scope.flags.oneclick=false;
		$state.go('app.certificates');
		//$scope.cancel();
		}
		
	}

	
	
	$scope.deletefile=function(index,file)
	{
		if(angular.isUndefined(file.id))
		{
			file.$cancel();
		}
		else
		{
			//$scope.tempqueue.push(file);
			file.$destroy = function () {
                        state = 'pending';
                        return $http({
                            url: file.deleteUrl,
                            method: file.deleteType
                        }).then(
                           function () {
                                state = 'resolved';
                               // $scope.clear(file);
								//console.log(state);
								//$rootScope.loadingView = false;
								//	console.log("success 1");
									$scope.saveimage();
									//	$rootScope.savebgimage();
								//$state.go('design', null, { reload: true });
                            },
                            function () {
                                state = 'rejected';
								//console.log(state);
                            }
                        );
                    };
					file.$destroy();
		}
			
		
	}
	  
  	$scope.deleteimage=function()
	{
		console.log("delete");
		if($scope.tempqueue.length>0)
			{
				for(var i=0;i<$scope.tempqueue.length;i++)
				{
					if(angular.isDefined($scope.tempqueue[i].coatcertid))
					{
					var file=$scope.tempqueue[i],state;
				
						 file.$destroy = function () {
                        state = 'pending';
                        return $http({
                            url: file.deleteUrl,
                            method: file.deleteType
                        }).then(
                           function () {
                                state = 'resolved';
                               // $scope.clear(file);
								//console.log(state);
								//$rootScope.loadingView = false;
								//	console.log("success 1");
									$scope.saveimage();
									//	$rootScope.savebgimage();
								//$state.go('design', null, { reload: true });
                            },
                            function () {
                                state = 'rejected';
								//console.log(state);
                            }
                        );
                    };
					file.$destroy();
				
				
				
					}
					
				}
			}
			else
			{
				$scope.saveimage();
			}
		
	}
  
  
   $scope.saveallcert=function(cert)
  {
	console.log(cert);  
	cert.PreparedBy=exists.uid;
	$scope.flags.editflag=true;
	$scope.oneclick=true;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'certapi/certbasicupdate/'+cert.basic.Id,
					data:cert					
					}).then(function successCallback(data) {
				console.log(data.data);
			 	//$scope.getpages();
				
				
				
				if(_.isEmpty($scope.queue))
			{
				$scope.flags.editflag=false;
				$scope.oneclick=false;
				$state.go('app.certificates');
				toaster.pop('success', "", "Data added successfully.");
			}
			else
			{
				console.log("image");
				$scope.certid=angular.fromJson(data.data);
				$scope.deleteimage();
				
			}
			
			
			 	
					}, function errorCallback(data) {
					console.log(data);
						$scope.flags.editflag=false;
					$scope.oneclick=false;
		//toaster.pop('error', "", "You are not authorized to view category Please Login");
				}); 
  }
  



	$scope.$on('fileuploadstop', function()
	{
		//$scope.flags.oneclick=false;
		$state.go('app.certificates',{},{ reload: true });
		//$scope.cancel();
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
	data.formData = {certid: data.files[0].certid};
 	});
	
	
	
});


App.controller('CertformatsController', function($scope,$http,$location,$rootScope,$state,toaster,$localstorage,
$window,$uibModal,AuthenticationService,alldata) {

$scope.pageSize=25;
$scope.currentPage=1;
$scope.allcfs=angular.copy(alldata.data.allcfs);
console.log($scope.allcfs);
$scope.totalitems=alldata.data.totalitems;

$scope.allformsec=alldata.data.allformsec;
$scope.allnonrirsec=alldata.data.allnonrirsec;
console.log($scope.allformsec);

var exists=$localstorage.getObject($scope.appname);


$scope.getsections=function(param)
{
	console.log(param);
	//$scope.info.CertFormDetails=[];
	angular.forEach(param,function(val){
		var cd=_.findWhere($scope.info.CertFormDetails,{FSID:val});
		if(_.isEmpty(cd))
		{
			console.log(val);
			console.log("not found");
			var fd=_.findWhere($scope.allformsec,{FSID:val});
			console.log(fd);
			$scope.info.CertFormDetails.push(fd);
		}
	});
	
}


$scope.secremoved=function(param,param1)
{
	console.log(param);
	console.log(param1);
	$scope.info.delcfd.push(param1);
	$scope.info.CertFormDetails=_.without($scope.info.CertFormDetails,_.findWhere($scope.info.CertFormDetails,{FSID:param1}));
}

$scope.addinfo=function(param)
	{
		
	 	if(param==='new')
		{
			$scope.info={}	;	
			$scope.info.CertFormDetails=[];
			
		}
		else
		{
			$rootScope.editflag=true;
			console.log(param);
			$scope.info=angular.copy(param);	
			$scope.info.delcfd=[];

			
						
		}	
				


	 $scope.modalInstance2 = $uibModal.open({
		keyboard:false,
		  animation: true,
		backdrop:"static",
   	   templateUrl: 'infoModal',
     	 scope: $scope,
   	 });
	 
	 
	}
	
	$scope.closeModal=function()
 	{
 		$scope.modalInstance2.dismiss('cancel');
 	}
	

	$scope.delcertformat=function(con,log)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.log=log;
		
			
			 $scope.modalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
   	   templateUrl: 'delinfoModal',
     		 scope: $scope,
     	 
   	 });
			
		}
		else if(con=='delete')
		{
			$scope.issaving=true;
			$http({
		    		 method:'DELETE',
		       		 url:'admin/adminapi/delcertformat/'+log.Id,
					//data:{}
		   		 }).then(function successCallback(data) {
					$scope.confirmModal = false;
					$scope.issaving=false;
					toaster.pop('success', "", "Successfully deleted.");
					$scope.closeconfirmModal();
					$scope.getallweights();
					//$rootScope.getallsub();
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.issaving=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
 		$scope.modalInstance.dismiss('cancel');
 	}

	$scope.getallcfs=function()
	{
			$http({	
					method:'GET',
					url:$rootScope.apiurl+'certapi/certformats',
					
	     	 
	     		}).then(function successCallback(data) {
					$scope.issaving=false;
					$scope.allcfs=angular.copy(data.data.allcfs);
console.log($scope.allcfs);
$scope.totalitems=alldata.data.totalitems;
	     				
	     		}, function errorCallback(data) {
	     			console.log(data);
					$scope.issaving=false;
	     		});
	}

	
	

	 $scope.issaving=false;
	$scope.saveinfo=function(info)
	{
		$scope.issaving=true;
	console.log(info);
	if(angular.isDefined($scope.info.Id))
	{
		//$scope.procatid=angular.fromJson($scope.info.Id);
		$http({	method:'PUT',
     		url:$rootScope.apiurl+'certapi/editcertformat/'+$scope.info.Id,	
     		data:info
     		}).then(function successCallback(data) {
				
				toaster.pop('success', "", "Successfully updated.");
					 $scope.getallcfs();
					 $scope.issaving=false;
				 $scope.closeModal();
     		}, function errorCallback(data) {
     				console.log(data);
					$scope.issaving=false;
     		});
	}
	else
	{	

		
		$http({		method:'POST',
				url:$rootScope.apiurl+'certapi/addcertformat',		
				data:info
			}).then(function successCallback(data) {
			
			console.log(data);
				 $scope.issaving=false;
				toaster.pop('success', "", "Successfully saved.");
					 $scope.getallcfs();
				 $scope.closeModal();
			}, function errorCallback(data) {
					console.log(data);
					 $scope.issaving=false;
			});
	 }   			
	
	};

	
 
	
});





