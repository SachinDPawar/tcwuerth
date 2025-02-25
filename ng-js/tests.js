App.controller('testsController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,configparam,
$rootScope,$state,$stateParams,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.allrirs=[];
	$scope.animationsEnabled = true;
	var tid=$stateParams.tid;
	var sid=$stateParams.sid;
	var exists=$localstorage.getObject(configparam.AppName);
	 $scope.pageSize=30;
	$scope.currentPage=1;
	$scope.nablview=false;
	console.log(sid);
	console.log(tid);
	 $scope.getperm=_.findWhere($scope.permissions,{SectionId:parseInt(sid)});
	// console.log($scope.permissions);
	// console.log($scope.getperm);
	if(angular.isDefined(alldata.data))
	{
		$scope.allrirs=alldata.data.allrirs;
		$scope.totalitems=alldata.data.totalitems;
		$scope.tuid=alldata.data.TUID;
		console.log(alldata.data);
	}
	
  $scope.editresult=function(param,id)
  {
	 // var tid=$stateParams.tid;
	   var tid=$scope.tuid;
	   
	   switch(tid)
	{
		
		
		
		
		
		
		default:$state.go('app.testobsadd',{id:id});break;
	}

 
	  
	
  }
  
  
  
  $scope.chemscopes=[{Id:0,Scope:"All Elements"},{Id:1,Scope:"Low Carbon Steel &amp; Alloy",Elements:["B","C","Cr","Cu","Mn","Mo","Ni","P","Si","S"]},
  {Id:2,Scope:"Stainless steel",Elements:["C","Cr","Mn","Mo","Ni","P","Si","S"]}];
 
  
   $scope.eleinscope=function(ele,csid)
  {	
  var eles=_.findWhere($scope.chemscopes,{Id:csid});
		  var t=_.contains(eles.Elements,ele.PSymbol);		 
			 return t; 	
  }
  
  
    $scope.Math = $window.Math;
   $scope.showrir = function (size,rir) {
	   
	   var tempurl="";
	   $scope.nablview=false;
	    var tid=$scope.tuid;
		$scope.info=rir; 
		console.log(tid);
	switch(tid)
	{
		case 'CHEM': tempurl='templates/tests/views/chemical.html';break;
		case 'TENSILE':tempurl='templates/tests/views/tensile.html';break;
		case 'IMP': tempurl='templates/tests/views/impact.html';break;
		case 'RBHARD':
		case 'RCHARD':
		case 'MVHARD':
		case 'VHARD':
		case 'BHARD': tempurl='templates/tests/views/hardness.html';break;
		case 'PROOF': tempurl='templates/tests/views/proofload.html';break;
		case 'MSTRUCT': tempurl='templates/tests/views/microstructure.html';break;
		case 'GRAIN': tempurl='templates/tests/views/grainsize.html';break;
		case 'MDCARB': tempurl='templates/tests/views/microdecarb.html';break;		
		case 'CARBDC': tempurl='templates/tests/views/carbdecarb.html';break;
		case 'CASE': tempurl='templates/tests/views/casedepth.html';break;
		case 'MCASE': tempurl='templates/tests/views/microcasedepth.html';break;
		case 'MCOAT': tempurl='templates/tests/views/microcoating.html';break;
		case 'TORQ': tempurl='templates/tests/views/torque.html';break;
		case 'THREAD': tempurl='templates/tests/views/threadlap.html';break;
		case 'WEDGE': tempurl='templates/tests/views/wedge.html';break;
		case 'IRK': tempurl='templates/tests/views/kinclrating.html';break;
		case 'IRW': tempurl='templates/tests/views/winclrating.html';break;
		case 'HET': tempurl='templates/tests/views/hydrogen.html';break;
		case 'FULLBOLT': tempurl='templates/tests/views/bolt.html';break;
		case 'SHEAR': tempurl='templates/tests/views/shear.html';break;
		case 'MET': tempurl='templates/tests/views/microetch.html';break;
		case 'BEND': tempurl='templates/tests/views/bend.html';break;
		case 'DR': tempurl='templates/tests/views/dimensionreport.html';break;
		
		
		default:tempurl='templates/tests/views/defaultview.html'
	}



 

		  $scope.tempurl=tempurl;
		//console.log($scope.carb);
		   $scope.modalInstance = $uibModal.open({
			  animation: $scope.animationsEnabled,
			  templateUrl: 'templates/tests/testview.html',
			  scope:$scope,
			  size: size,
			  windowClass:'fade-scale',
			 
			});
   }
 
   $scope.cancel = function () {
    $scope.modalInstance.dismiss('cancel');
  };
  
  $scope.oneclick=false;
  $scope.deletetest=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.Test=id.PartName;
			$scope.modalInstance2 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'deltestModal',
				scope:$scope,
				windowClass:'fade-scale',
     	
				});
		}
		else if(con=='delete')
		{
			$scope.oneclick=true;
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'api/droptest/'+id,
		        
		   		}).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					toaster.pop({toasterId:11,type:"success",body:"Test Deleted Successfully"});
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
						toaster.pop({toasterId:11,type:"error",body:data.data});
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance2.dismiss('cancel');
 	}

	$scope.createpdf=function(item)
	{
		console.log("creating pdf");
		$http({
		    		 method:'PUT',
		       		 url:$rootScope.apiurl+'api/createtestpdf/'+item.Id,
					 data:{ApprovedBy:$rootScope.dboard.uid,SectionId:$stateParams.sid}
		        
		   		}).then(function successCallback(data) {
					 	var url=angular.fromJson(data.data);
					 console.log(data);
					 $scope.viewpdf(url);
					
					
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
						toaster.pop({toasterId:11,type:"error",body:data.data});
				});
				
	}
	$scope.closepdfModal=function()
	{
		$scope.pdfmodalInstance.dismiss('cancel');
	}
	
	$scope.viewpdf=function(url)
	{
		console.log(url);
		$scope.pdffile={};
			$scope.pdffile.url=url;
			
		 $scope.pdfmodalInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'templates/common/pdfview.html',
     	 scope: $scope,
   	 });
	}
	
		
	$scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		  console.log(tid);
		  console.log(pageno);
		$http({	
					method:'PUT',
					url:$rootScope.apiurl+'api/testdata/'+exists.uid,	
					data:{TestId:tid,pl:$scope.pageSize,pn:pageno}
	     		}).then(function successCallback(data) {
					console.log(data);
				$scope.allrirs=data.data.allrirs;
		$scope.totalitems=data.data.totalitems;
		
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			console.log(data);
					$scope.flags.loadingdata=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});
	     		});
		
	}
	
	
	
	
	$scope.closeconfirmModal3=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance3.dismiss('cancel');
 	}
	
$scope.approvetest=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.log=id;
			
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
		       		 url:$rootScope.apiurl+'api/approvetest/'+id,
					 data:{ApprovedBy:$rootScope.dboard.uid,SectionId:$stateParams.sid}
		        
		   		}).then(function successCallback(data) {
					 	var url=angular.fromJson(data.data);
					 console.log(data);
					 $scope.getdata($scope.currentPage);
					 //$scope.viewpdf(url);
					$scope.closeconfirmModal3();
					toaster.pop({toasterId:11,type:"success",body:"Test Approved Successfully"});
					
					// $scope.getdata($scope.
					//$state.go('app.tests',{tid:$stateParams.tid,sid:$stateParams.sid},{reload:true});
					// );
					
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
						toaster.pop({toasterId:11,type:"error",body:data.data});
				});
		}

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
							url:$rootScope.apiurl+'api/searchtestob/',	
							data:{TestId:tid,text:searchtext,pageSize:$scope.pageSize}
					 
						}).then(function successCallback(data) {
							console.log(data);
							
						$scope.allrirs=data.data.allrirs;
				$scope.totalitems=data.data.totalitems;
				$scope.flags.loadingdata=false;
										
						}, function errorCallback(data) {
							console.log(data);
							$scope.flags.loadingdata=false;
							toaster.pop({toasterId:11,type:"error",body:data.data});
						});
		}

	}

$scope.refreshdata=function()
{
	$state.reload();//('app.bcregdetails',{});
	
}


	$scope.mflags={};
	$scope.mflags.oneclick=[];
	
	$scope.closesendmailmodal=function()
	{
		$scope.emailconfirmmodalInstance.dismiss('cancel');
	}
	$scope.sendmail=function(test,param)
	{
		if(param==='confirm')
		{
			
			$scope.test=test;
			$scope.test.MailTo=test.CustEmail;
			 $scope.emailconfirmmodalInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'emailsendModal.html',
     	 scope: $scope,
   	 });
	 
		}
		else
		{
			$scope.mflags.oneclick=true;
		$http({		method:'PUT',
				url:$rootScope.apiurl+'adminapi/sendtestreport/'+test.Id,	
				data:test
				}).then(function successCallback(data) {
			
				console.log(data);
				toaster.pop({ type: 'info', body: data.data.msg, toasterId: '11' });
				$scope.mflags.oneclick=false;
				
				$scope.closesendmailmodal();
				}, function errorCallback(data) {
					console.log(data);
					$scope.mflags.oneclick=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
		}
	}
	
	$scope.closeqrModal=function()
	{
		$scope.qrcodemodalInstance.dismiss('cancel');
		
	}
	
	$scope.showqrcode=function(param)
	{
		$scope.qrstring=param.QRImg;
		
		 $scope.qrcodemodalInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'templates/common/qrcode.html',
     	 scope: $scope,
   	 });
	 
	}

});


App.controller('testobsaddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$sce,$parse,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';
	$scope.alltests=[];
	$scope.info={};
	$scope.editflag=false;
	$scope.flags={};
	$scope.flags.oneclick=false;
	$scope.flags.loadwait=false;
	
	$scope.remarks=allpredata.data.allremarks;

	$scope.loadingFiles = true;	
	$scope.path='images/testuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
		$scope.tempqueue=[];
	
	 $scope.dateOptions = {
    //customClass: getDayClass,
    
    showWeeks: false
  };
	
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
	
	$scope.tuid=allpredata.data.tuid;
	$scope.modifyurl="";
	switch($scope.tuid)
	{
		case 'CHEM': $scope.modifyurl='templates/tests/modify/chemicaladd.html';break;
		
		case 'RBHARD':
		case 'RCHARD':
		case 'MVHARD':
		case 'VHARD':
		case 'BHARD': $scope.modifyurl='templates/tests/modify/hardnessadd.html';break;
		
		case 'CASE':  $scope.modifyurl='templates/tests/modify/casedepthadd.html';break;
		//case 'CARBDC': $scope.modifyurl='templates/tests/modify/carbdecarbadd.html';break;
		
		case 'GRAIN': $scope.modifyurl='templates/tests/modify/grainsizeadd.html';break;
		
			case 'IRK': $scope.modifyurl='templates/tests/modify/kinclratingadd.html';break;
		case 'IRW': $scope.modifyurl='templates/tests/modify/winclratingadd.html';break;
		
		//case 'THREAD':  $scope.modifyurl='templates/tests/modify/threadlapadd.html';break;
		
		case 'MDCARB': $scope.modifyurl='templates/tests/modify/microdecarbadd.html';break;				
		case 'MCASE': $scope.modifyurl='templates/tests/modify/microcasedepthadd.html';break;
		case 'MCOAT': $scope.modifyurl='templates/tests/modify/microcoatingadd.html';break;	
		
		//case 'MSTRUCT': $scope.modifyurl='templates/tests/views/microstructure.html';break;
		
		
		
		
		case 'THREAD': $scope.modifyurl='templates/tests/modify/threadlapadd.html';break;	
		
		
		
		
		
		default:
		//case 'TENSILE':
		//case 'IMP': 
			// case 'PROOF': 
		//	case 'TORQ': 
		//case 'HET':
		//case 'WEDGE':
		$scope.modifyurl='templates/tests/modify/testobsadd.html'
	}

 
 $scope.getmicrodecarbavg=function(param)
 {
	 var avg=0;
	 angular.forEach(param,function(val){
		 avg=avg+parseFloat(val.Value);
	 });
	 
	 avg=parseFloat(avg/param.length);
	 return avg;
 }
	
	 
 $scope.getmicrocoatingavg=function(param)
 {
	 var avg=0;
	 angular.forEach(param,function(val){
		 avg=avg+parseFloat(val.Value);
	 });
	 
	 avg=parseFloat(avg/param.length);
	 return avg;
 }
  
 $scope.getmicrocaseavg=function(param)
 {
	 var avg=0;
	 angular.forEach(param,function(val){
		 avg=avg+parseFloat(val.Value);
	 });
	 
	 avg=parseFloat(avg/param.length);
	 return avg;
 }
	
  	$scope.cancelsave=function()
	{
		$state.go('app.tests',{tid:$scope.tid,sid:$scope.sid});
	}  
	
	$scope.ifvaluetrue=function(k,item)
	{
		//console.log(val);
		if(k.Value !='' )
		{
			if(k.Value<item.SpecMin || k.Value>item.SpecMax)
			{
				return true;
			}
		else {return false };
		
		}
		
	}
	
	
	$scope.validobsvalues=function(params)
	{
		 var valid=true;
		angular.forEach(params,function(val){
			if(val.Values[0].Value<val.RangeMin || val.Values[0].Value>val.RangeMax)
			{
				valid=false;
			}
			
		});
		 return valid;
	}
	
	
	
	$scope.addiwrrow=function()
{
	var hard={ThinA:"",ThickA:"",ThinB:"",ThickB:"",ThinC:"",ThickC:"",ThinD:"",ThickD:""};
	$scope.info.basic.Parameters[0].Values.Values.push(hard);
}

$scope.deliwrrow=function(idx)
	{
		$scope.info.basic.Parameters[0].Values.Values.splice(idx,1);
	}
	
	
	
	$scope.addhardnessrow=function()
{
	var hard={SValue:"",CValue:""};
	$scope.info.basic.Parameters[0].Values.Values.push(hard);
}

$scope.delhardnessrow=function(idx)
	{
		$scope.info.basic.Parameters[0].Values.Values.splice(idx,1);
	}
	

$scope.addcasedepthrow=function()
{
	var hard={Distance:"",Hardness:""};
	$scope.info.basic.Parameters[0].Values.Values.push(hard);
}

$scope.delcasedepthrow=function(idx)
	{
		$scope.info.basic.Parameters[0].Values.Values.splice(idx,1);
	}
	
	
	
$scope.addgrainrow=function()
{
	
	var hard={Value:""};
	$scope.info.basic.Parameters.Obs.Values.push(hard);
}

$scope.delgrainrow=function(idx)
	{
		
		$scope.info.basic.Parameters.Obs.Values.splice(idx,1);
	}
	
	$scope.filedata="";
	
	
	$scope.addrow=function()
	{
		var ity=$scope.info.basic.Parameters[0].Values.length;
		angular.forEach($scope.info.basic.Parameters,function(val,idx)
		{
			console.log(val);
			if(val.FormVal)
			{
				var formula=val.Formula;
				
			formula=formula.replace(/R0/g, "R"+ity)
				val.Values.push({Value:"",Formula:formula});
			}
			else
			{
				val.Values.push({Value:""});
			}
			
		});
	}
	
	$scope.delrow=function(idx)
	{
		angular.forEach($scope.info.basic.Parameters,function(val)
		{
			console.log(val);
			val.Values.splice(idx,1);
		});
	}
	
	
	
	
	


$scope.vars=[];
$scope.updateformvalues=function(param,k,idx)
{
	// console.log(idx);
	
	if(param.informdts)
	{
		if(k.Value)
		{
		$scope['R'+idx+param.informname]=parseFloat(k.Value);
		}
	}
	else
	{
		//console.log(k,param);
		return true;
	}
}
$scope.saveupload=function()
	{
		console.log("saving images..");
		angular.forEach($scope.queue,function(file){
			
			if(angular.isUndefined(file.id))
			{
				file=_.extend(file,{"rirtestid":$scope.info.basic.Id});
				console.log(file);
				var result=file.$submit();
				console.log(result);
			}
			
		})
		var activeUploads = $('#fileupload').fileupload('active');
		if(activeUploads<1)
		{
			toaster.pop({toasterId:11,type:"success",body:"Updated Successfully"});
		$state.go('app.tests',{tid:$scope.tid,sid:$scope.sid});
		}
	}
	
$scope.$on('fileuploadstop', function()
	{
		toaster.pop({toasterId:11,type:"success",body:"Updated Successfully"});
		$state.go('app.tests',{tid:$scope.tid,sid:$scope.sid});
	});
	
	
	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {rirtestid: data.files[0].rirtestid,description: data.files[0].description};
 
});


	$scope.deleteimage=function()
	{
		
				$scope.saveupload();
		
	}
	
	
	
	$scope.deletefile=function(idx,file)
    {
        if(angular.isUndefined(file.id))
        {
            file.$cancel();
            $scope.number++;
			$scope.queue.splice(idx,1);
        }
        else
        {
            if (angular.isDefined(file.id))
                    {
                
				$scope.tempqueue.push(file);
                        $scope.info.basic.delfiles.push(file);			
			$scope.info.basic.fileuploads.splice(idx,1);	
                        
                    }
	
			
		}
	}
	



  $scope.saveinfotest=function(water , testform)
  {
	
	
	// if(water.basic.TestType==='D' && water.basic.IsStd)
	// {
		// if($scope.validobsvalues(water.basic.Parameters))
		// {
			// if(testform.$valid)
			// {
			// water.basic.Remark='Passed';
			// }
		// }
		// else
		// {
			// water.basic.Remark='Failed';
		// }
	// }
	console.log(water);
	$scope.flags.oneclick=true;	
	water.ModifiedBy=$rootScope.dboard.uid;
	water.TestedBy=$rootScope.dboard.uid;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'api/testobsupdate/'+water.basic.Id,
					data:water					
				}).then(function successCallback(data) {
				console.log(data);
			 	$scope.flags.oneclick=false;
				
				$scope.flags.oneclick=false;
				if($scope.queue.length>0)
				{
					$scope.deleteimage();
				}
				else
				{
					toaster.pop({toasterId:11,type:"success",body:"Updated Successfully"});
				$state.go('app.tests',{tid:$scope.tid,sid:$scope.sid});
				}
				
				
				
			 	
				}, function errorCallback(data) {
					console.log(data);
					$scope.flags.oneclick=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
  }
  
  
  $scope.systemupload=function(param)
	{
		
		
		$scope.flags.oneclick=true;	
		$scope.flags.loadwait=true;
		console.log(param);
		var testname=param.tuid;
		
		switch(testname)
		{
			
			case 'CHEM':
			
					console.log('getting chem data');
							$http({
					method:'POST',
					url: $rootScope.apiurl+'api/chemsystemupdate/',
					data:param.basic					
				}).then(function successCallback(data) {
				console.log(data);
				$scope.filedata=[];
				var keys=[];
				angular.forEach(data.data.results,function(value){
					var y = _.contains(value, 'Header_1');
					var z = _.contains(value, '?Header_1');
					
					if( y || z)
					{
						keys=angular.copy(value);
						for (var i = 0; i < keys.length; i++)
						{  keys[i] = keys[i].replace(/\(%\)/, "") ;
							keys[i] = keys[i].replace(/\s+/, "") ;
							keys[i] = keys[i].replace(/\.$/, "");
						}
						$scope.filedata.push(_.object(keys, value));
					}else
					{
					$scope.filedata.push(_.object(keys, value));
					}
				});
				
				console.log($scope.filedata);
			var labnosupply="";
			var myparam="";
			if($scope.info.basic.TestName==='Chemical')
			{
				myparam="";
			}
			
			
				$scope.filedata.reverse();
			
				labnosupply=param.basic.LabNo+'-'+param.basic.TNo;
				console.log(labnosupply);
				$scope.filevalue=_.findWhere($scope.filedata, {LabNo: labnosupply});
				if(_.isEmpty($scope.filevalue))
				{
					var bc=param.basic.BatchCode;
					if(param.basic.IsMdsTds==='tds')
					{
						bc=param.basic.BatchCode+'-'+param.basic.BatchNo;
					}
						
					
					$scope.filevalue=_.findWhere($scope.filedata, {"BatchCode No": bc});
				}
				//$scope.filevalue=_.findWhere($scope.filedata, {Sample: labnosupply});
				
			if(_.isEmpty($scope.filevalue))
			{
				toaster.pop({ type: 'error', body: "No data with LabNo="+param.basic.LabNo, toasterId: 1 });
				$scope.flags.loadwait=false;
			}
			else
			{
				var p;
				var s;
				
					if(_.has($scope.filevalue,'P'))
					{
						var av=$scope.filevalue['P']
						p=parseFloat(av).toFixed(4);
					}
					if(_.has($scope.filevalue,'S'))
					{
						var av=$scope.filevalue['S']
						s=parseFloat(av).toFixed(4);
					}
					
				angular.forEach(param.basic.Parameters,function(val){
					//var value=_.findWhere($scope.filevalue, {Element: val.Element});
					if(_.has($scope.filevalue,val.PSymbol))
					{
						var av=$scope.filevalue[val.PSymbol];
					val.Values[0]={Value:parseFloat(av).toFixed(4)};
					
					}
					
				if(val.PSymbol==='P+S')
					{
						console.log(val.PSymbol)
					var av=parseFloat(p)+parseFloat(s);
					console.log(av);
						
						val.Values[0]={Value:parseFloat(av).toFixed(4)};
					}
						});
						
						
						toaster.pop({ type: 'success', body: "Data uploaded successfully.", toasterId: 1 });
						$scope.flags.loadwait=false;
			}
			 	$scope.flags.oneclick=false;
				 //toaster.pop({ type: 'info', body: "data", toasterId: 1 });
			 	
				}, function errorCallback(data) {
					console.log(data);
					$scope.flags.oneclick=false;
					$scope.flags.loadwait=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});

				}); 
			break;
			
			
			case 'TENSILE':
				$http({
					method:'POST',
					url: $rootScope.apiurl+'api/utesystemupdate/',
					data:param.basic					
				}).then(function successCallback(data) {
			
			
				console.log(data.data);
				//console.log($scope.info.basic.Parameters);

			angular.forEach($scope.info.basic.Parameters,function(val){
					//var value=_.get(data.data.observations, {val.PSymbol});
					//console.log(value);
					if(_.has(data.data.observations,val.PSymbol))
					{
						//console.log(val.PSymbol);
						var av=data.data.observations[val.PSymbol];
						console.log(av);
						//console.log(val.Values[0]);
					val.Values[0].Value=av;
					
					
					
					}
					
					
					
				
						});
			
			
				
						 toaster.pop({ type: 'success', body: "Data uploaded successfully.", toasterId: 1 });
						 $scope.flags.loadwait=false;
			
			 	$scope.flags.oneclick=false;
				
			 	
				}, function errorCallback(data) {
					console.log(data);
					$scope.flags.oneclick=false;
					$scope.flags.loadwait=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});

				}); 
			
			break;
			
			case 'VHARD':
				$http({
					method:'POST',
					url: $rootScope.apiurl+'api/vhardsystemupdate/',
					data:param.basic					
				}).then(function successCallback(data) {
			
			
				console.log(data.data.results.observations);
				console.log($scope.info.basic.Parameters[0].Values);

			
			
				
						 toaster.pop({ type: 'success', body: "Data uploaded successfully.", toasterId: 1 });
						 $scope.flags.loadwait=false;
			
			 	$scope.flags.oneclick=false;
				
				$scope.info.basic.Parameters[0].Values.Values=data.data.results.observations;
					
			
				
			 	
				}, function errorCallback(data) {
					console.log(data);
					$scope.flags.oneclick=false;
					$scope.flags.loadwait=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});

				}); 
			
			break;
			
			
			case 'MVHARD':
				$http({
					method:'POST',
					url: $rootScope.apiurl+'api/mvhardsystemupdate/',
					data:param.basic					
				}).then(function successCallback(data) {
			
			
				console.log(data.data);
				console.log($scope.info.basic.Parameters[0].Values);

			$scope.info.basic.Parameters[0].Values.Values=data.data;
			
				
						 toaster.pop({ type: 'success', body: "Data uploaded successfully.", toasterId: 1 });
						 $scope.flags.loadwait=false;
			
			 	$scope.flags.oneclick=false;
				
				
					
			
				
			 	
				}, function errorCallback(data) {
					console.log(data);
					$scope.flags.oneclick=false;
					$scope.flags.loadwait=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});

				}); 
			
			break;
			
				case 'CASE':
				$http({
					method:'POST',
					url: $rootScope.apiurl+'api/casesystemupdate/',
					data:param.basic					
				}).then(function successCallback(data) {
			
			
				console.log(data.data);
				console.log($scope.info.basic.Parameters[0].Values);

			$scope.info.basic.Parameters[0].Values.Values=data.data;
			
				
						 toaster.pop({ type: 'success', body: "Data uploaded successfully.", toasterId: 1 });
						 $scope.flags.loadwait=false;
			
			 	$scope.flags.oneclick=false;
				
				
					
			
				
			 	
				}, function errorCallback(data) {
					console.log(data);
					$scope.flags.oneclick=false;
					$scope.flags.loadwait=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});

				}); 
			
			break;
			
			
			case 'CARBDC':
				$http({
					method:'POST',
					url: $rootScope.apiurl+'api/carbsystemupdate/',
					data:param.basic					
				}).then(function successCallback(data) {
			
			
				console.log(data.data);
				console.log($scope.info.basic.Parameters[0].Values);

			$scope.info.basic.Parameters[0].Values=data.data;
			
				
						 toaster.pop({ type: 'success', body: "Data uploaded successfully.", toasterId: 1 });
						 $scope.flags.loadwait=false;
			
			 	$scope.flags.oneclick=false;
				
				
					
			
				
			 	
				}, function errorCallback(data) {
					console.log(data);
					$scope.flags.oneclick=false;
					$scope.flags.loadwait=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});

				}); 
			
			break;
			
			default:
			$scope.flags.oneclick=false;
					$scope.flags.loadwait=false;
			 toaster.pop({ type: 'error', body: "No data available for this test.", toasterId: 1 });
			break;
		}
		/*
			
		*/
	}
	

	
	if(angular.isDefined(allpredata.data))
	{
		console.log(allpredata.data);
		$scope.info=allpredata.data;
		$scope.standards=allpredata.data.SubStandards;
		//$scope.info.basic.SpectroDetails=allpredata.data.Parameters;
		$scope.tid=allpredata.data.tid;
		$scope.sid=allpredata.data.sid;
		$scope.testmethods=allpredata.data.testmethods;
		if(!_.isEmpty($scope.info.basic.TestDate)){
			
			$scope.info.basic.TestDate=new Date($scope.info.basic.TestDate);
		}else
		{
			$scope.info.basic.TestDate=new Date();	
			$scope.info.basic.Remark="Failed";
		}
	$scope.loadingFiles = true;
	
						
					
					
					angular.forEach($scope.info.basic.Parameters,function(param){
						//console.log(param);
						var idx=0;
						angular.forEach(param.Values,function(k){
							//console.log(k);
							
							$scope.updateformvalues(param,k,idx);
							idx++;
						});
						
					});
					
					
					angular.forEach($scope.info.basic.TopBasicParameters,function(val){
						
							if(val.PDType==='D')
							{
								if(_.isEmpty(val.BValue))
								{
									val.BValue=new Date();
								}
								else
								{	
									val.BValue=new Date(val.BValue);
								}
							}
							
					});
	
	}
	
	//---inclusion k-------------//
	
	  
  
  $scope.closeaddrowModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalnewrow.dismiss('cancel');
 	}
	
  
  $scope.addirkobs=function(param1,param2)
  {
	  //console.log(param);
	  
	  if(param1==='new')
	  {
		   $scope.row={
	   SpecNo:"",AreaPol:"",
	   obs:[
		{TypeOfInc:"SS",RatNo0:0,RatNo1:0,RatNo2:0,RatNo3:0,RatNo4:0,RatNo5:0,RatNo6:0,RatNo7:0,RatNo8:0,SStar:0},
		{TypeOfInc:"OA",RatNo0:0,RatNo1:0,RatNo2:0,RatNo3:0,RatNo4:0,RatNo5:0,RatNo6:0,RatNo7:0,RatNo8:0,OStar:0},
		{TypeOfInc:"OS",RatNo0:0,RatNo1:0,RatNo2:0,RatNo3:0,RatNo4:0,RatNo5:0,RatNo6:0,RatNo7:0,RatNo8:0,OStar:0},
		{TypeOfInc:"OG",RatNo0:0,RatNo1:0,RatNo2:0,RatNo3:0,RatNo4:0,RatNo5:0,RatNo6:0,RatNo7:0,RatNo8:0,OStar:0},
	   ]
	   
	   };
	    $scope.rowidx='new';
		  	 $scope.modalnewrow = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				size:'lg',
				templateUrl: 'newrowModal',
				scope:$scope,
     	
				});
				
	  }
	  else
	  {
		   
		   $scope.modalnewrow = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				size:'lg',
				templateUrl: 'newrowModal',
				scope:$scope,
     	
				});
		  $scope.row=angular.copy(param1);
		  $scope.rowidx=angular.copy(param2);
		  //console.log($scope.row);
	  }
	 
	 
  }
  
  $scope.getrowtot=function(param)
  {
	  var ss=(param.RatNo0 * 0.05)+(param.RatNo1 * 0.1)+(param.RatNo2 * 0.2)+(param.RatNo3 * 0.5)+(param.RatNo4 * 1)+(param.RatNo5 *2)+(param.RatNo6 * 5)+(param.RatNo7 * 10) +(param.RatNo8 * 20) ;
	  param.SStar=ss;
	  return param.SStar;
  }
  
   $scope.getrowtot1=function(param)
  {
	  var ss=(param.RatNo0 * 0.05)+(param.RatNo1 * 0.1)+(param.RatNo2 * 0.2)+(param.RatNo3 * 0.5)+(param.RatNo4 * 1)+(param.RatNo5 *2)+(param.RatNo6 * 5)+(param.RatNo7 * 10) +(param.RatNo8 * 20) ;
	  param.OStar=ss;
	  return param.OStar;
  }
  
   $scope.getrowtot2=function(param)
  {
	  var ss=(param.RatNo0 * 0.05)+(param.RatNo1 * 0.1)+(param.RatNo2 * 0.2)+(param.RatNo3 * 0.5)+(param.RatNo4 * 1)+(param.RatNo5 *2)+(param.RatNo6 * 5)+(param.RatNo7 * 10) +(param.RatNo8 * 20) ;
	  param.OStar=ss;
	  return param.OStar;
  }
  
   $scope.getrowtot3=function(param)
  {
	  var ss=(param.RatNo0 * 0.05)+(param.RatNo1 * 0.1)+(param.RatNo2 * 0.2)+(param.RatNo3 * 0.5)+(param.RatNo4 * 1)+(param.RatNo5 *2)+(param.RatNo6 * 5)+(param.RatNo7 * 10) +(param.RatNo8 * 20) ;
	  param.OStar=ss;
	  return param.OStar;
  }
  
$scope.areapoltotal = function () {
    var areatotal = 0;
    var stotal = 0;
    var ototal = 0;

    // Check if 'Obs' is defined and is an array

        angular.forEach($scope.info.basic.Parameters.Obs.Values, function (val) {
			//console.log(val);
            // Ensure 'val' is an object and 'AreaPol' exists and is a valid number
            if (val && val.AreaPol && !isNaN(val.AreaPol)) {
				console.log(val.AreaPol);
                areatotal += parseFloat(val.AreaPol);
            }

            // Ensure 'val.obs' exists and is an array before looping through it
            if (Array.isArray(val.obs)) {
                angular.forEach(val.obs, function (item) {
                    // Ensure 'item' is an object and 'SStar' exists and is a valid number
                    if (item && item.TypeOfInc === 'SS' && item.SStar && !isNaN(item.SStar)) {
                        stotal += parseFloat(item.SStar);
                    }

                    // Ensure 'item' is an object and 'OStar' exists and is a valid number
                    if (item && (item.TypeOfInc === 'OA' || item.TypeOfInc === 'OS' || item.TypeOfInc === 'OG') && item.OStar && !isNaN(item.OStar)) {
                        ototal += parseFloat(item.OStar);
                    }
                });
            }
        });
    
	console.log(areatotal);

    // Assign the calculated values to the scope variables
    $scope.info.basic.Parameters.Total.Values[0].Value = areatotal;
    $scope.info.basic.Parameters.SSTotalS.Values[0].Value = stotal.toFixed(4);
    $scope.info.basic.Parameters.SSTotalO.Values[0].Value = ototal.toFixed(4);

    // Calculate the K3S and K3O values
    if (areatotal !== 0) {  // Prevent division by zero
        var totalk3s = (stotal * 1000) / areatotal;
        var totalk3o = (ototal * 1000) / areatotal;

        // Assign the K3 values to the scope
        $scope.info.basic.Parameters.TotalK3S.Values[0].Value = totalk3s.toFixed(4);
        $scope.info.basic.Parameters.TotalK3O.Values[0].Value = totalk3o.toFixed(4);

        // Calculate the overall K3 total (sum of K3S and K3O)
        $scope.info.basic.Parameters.OverAllTotK3.Values[0].Value = (totalk3o + totalk3s).toFixed(4);
    } else {
       // console.warn('Total Area is zero, cannot calculate K3 values.');
    }

    return areatotal;
};

  
  
  $scope.saveobs=function(row,param)
  {
	  console.log(row);
	  if(param==='new')
	  {
		  //row.SpecNo=$scope.info.basic.Parameters.Obs.Value.obs.length+1;
		  
		  $scope.info.basic.Parameters.Obs.Values.push(row);
		   $scope.areapoltotal();
		$scope.closeaddrowModal();
	  }
	  else
	  {
		  
		  $scope.info.basic.Parameters.Obs.Values[param]=row;
		  $scope.areapoltotal();
		  $scope.closeaddrowModal();
	  }
	  
  }
  
  
  $scope.removeirkobs=function(item)
  {
	  //console.log(item);
	  if(angular.isDefined(item.Id))
	  {
		  $scope.info.basic.Parameters.delobs.push(item);
	  }
	   var i=angular.copy(item.SpecNo-1);
	  $scope.info.basic.Parameters.Obs.splice(i,1);
	  var j=1;
	  angular.forEach( $scope.info.basic.Parameters.Obs,function(val){
		  
		  val.SpecNo=j;
		  j++;
	  });
  }
  

  
  
});

