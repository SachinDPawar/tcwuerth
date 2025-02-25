App.run(['editableOptions', function(editableOptions) {
  editableOptions.theme = 'bs4'; // bootstrap3 theme. Can be also 'bs4', 'bs2', 'default'
}]); 
App.controller('rirController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$q,
$rootScope,$state,AuthenticationService,$timeout,alldata,configparam)
{
	'use strict';
	
	$scope.allrirs=[];
	$scope.animationsEnabled = true;
	$scope.pageSize=30;
	$scope.currentPage=1;
	 $scope.flags={};
	 $scope.flags.loadingdata=false;
	if(angular.isDefined(alldata.data))
	{
		console.log(alldata.data);
		$scope.allrirs=alldata.data.allrirs;
		
		$scope.totalcount=alldata.data.totalcount;
	}
	
	 $scope.closefullrir = function () {
    $scope.fullviewmodalInstance.dismiss('cancel');
  };
	
	$scope.getfullsample=function(item)
	{
		$http({	
					method:'PUT',
					url:$rootScope.apiurl+'adminapi/getfullrir/'+item.Id,	
	     	 
	     		}).then(function successCallback(data) {
					console.log(data.data);
						$scope.fullrirs=data.data.rirs;
						console.log("ui");
						
							  // $scope.substdplanInstance = $uibModal.open({
		// keyboard:false,
		  // animation: true,
		  // size:'lg',
		// backdrop:"static",
   	   // templateUrl: 'templates/settings/substdplanaddModal.html',
     	 // scope: $scope,
   	 // });
							$scope.tempurl='templates/tests/testobsview.html';
						$scope.fullviewmodalInstance = $uibModal.open({
						keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
						templateUrl: 'rirfullviewContent.html',
						scope:$scope,
						
						
						});
			     				console.log("ui");
	     			}, function errorCallback(data) {
	     			console.log(data);
					$scope.flags.loadingdata=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});
	     		});
		
				

		
	}
	
	
  $scope.editreceipt=function(param,id)
  {
	 
	if(param==='new')
	{ console.log(param);
		$state.go('app.receiptadd');
	}
	else
	{
		 console.log(id);
		$state.go('app.receiptedit',{id:id});
	}
	  
	  
  }
  
   $scope.showrir = function (size,rir) {
console.log(rir);

$scope.viewrir=rir;   
   $scope.modalInstance = $uibModal.open({
      animation: true,
      templateUrl: 'rirviewContent.html',
      scope:$scope,
      size: size,
     
    });
	console.log(rir);
   }
 
   $scope.cancelrir = function () {
    $scope.modalInstance.dismiss('cancel');
  };
  
  $scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		var exists=$localstorage.getObject(configparam.AppName);
		//console.log(pageno);
		$http({	
					method:'PUT',
					url:$rootScope.apiurl+'api/rirfiltdata/'+exists.uid,	
					data:{pn:pageno,pl:30}
	     		}).then(function successCallback(data) {
					console.log(data);
				$scope.allrirs=data.data.allrirs;
		$scope.totalcount=data.data.totalcount;
		$scope.flags.loadingdata=false;
			     				
	     			}, function errorCallback(data) {
	     			console.log(data);
					$scope.flags.loadingdata=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});
	     		});
		
	}
	
	
	$scope.searchdata=function(searchtext)
{
	console.log(searchtext);
	if(!_.isEmpty(searchtext))
	{
	$scope.flags.loadingdata=true;
	var pageno=1;
		//console.log(pageno);
		$http({	
					method:'POST',
					url:$rootScope.apiurl+'api/searchrir/',	
					data:{text:searchtext,pageSize:$scope.pageSize}
	     	 
	     			}).then(function successCallback(data) {
					$scope.pageSize=75;
					console.log(data);
				$scope.allrirs=data.data.allrirs;
		$scope.totalcount=0;//data.totalitems;
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

	$scope.separator=",";
	$scope.getHeader1=[];
			$scope.filename = "rrtest";
		$scope.head=[];
		
// $scope.getHeader=function()
// {
	// return _.keys($scope.head);
	
// }		

$scope.exportArray=function()
{
	$scope.flags.loadingdata=true;
	 var deferred = $q.defer();
        $http({method: 'GET',	url:$rootScope.apiurl+'api/rirtestreport/200/1'}).then(function (data) {
			console.log(data);
			//console.log(data.data.allrirs);
			var temp1=data.data.allrirs;
			 var temp=[];
			angular.forEach(temp1,function(val){
			//	console.log(val);
			val=_.sortBy(val,'TId');
			//console.log(val);
				var tempobj={};
							angular.forEach(val,function(key){
								//console.log(key);
								if(key.hasOwnProperty("LabNo"))
								{
									tempobj["LabNo"]=key.LabNo;
									
									if(!$scope.head.includes("LabNo"))
									{
										$scope.head.push("LabNo");
									}
									
								}
								else
								{
									tempobj[key.TestName]=key.Status;
									if(!$scope.head.includes(key.TestName))
									{
										$scope.head.push(key.TestName);
									}
								}
				
				
				
							});
					
						temp.push(tempobj);
				
			});
			//$scope.head=_.keys(temp[0]);
			//console.log($scope.head);
			console.log(temp);
			$scope.flags.loadingdata=false;
                deferred.resolve(temp);
            }, function (errorData) {
				console.log(errorData);
			$scope.flags.loadingdata=false;
            deferred.reject(errorData);
        });
        return deferred.promise;
	
}
  
  
  
   $scope.showtest = function (rir) {
	   
	   var tempurl="";
	   console.log(rir);
	    var tid=rir.Id;
	switch(tid)
	{
		case 'CDC': tempurl='templates/tests/carb.html';
		$scope.carb=rir; 
		break;
		
		case 'CD': tempurl='templates/tests/casedepth.html';$scope.casedepth=rir; break;
		
		case 'C': tempurl='templates/tests/chemical.html';$scope.chemical=rir;break;
		case 'H': tempurl='templates/tests/hardness.html';$scope.hardness=rir;break;
		case 'HET': tempurl='templates/tests/hydro.html';$scope.hydro=rir;break;
		case 'I': tempurl='templates/tests/impact.html';$scope.impact=rir;break;
		case 'PL': tempurl='templates/tests/proofload.html';$scope.proof=rir;break;
		case 'SS': tempurl='templates/tests/shear.html';$scope.shear=rir;break;
		case 'T':tempurl='templates/tests/tensile.html';$scope.tensile=rir;break;
		case 'TT': tempurl='templates/tests/tension.html';$scope.tension=rir;break;
		case 'W': tempurl='templates/tests/wedge.html';$scope.wedge=rir;break;
		
		default:tempurl='templates/tests/testobsview.html';$scope.info=rir;break;
	}

  
//console.log($scope.carb);
   $scope.testmodalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: tempurl,
      scope:$scope,
      size: 'lg',
	  windowClass:'fade-scale',
     
    });
   }
 
   $scope.cancel = function () {
    $scope.testmodalInstance.dismiss('cancel');
  };
  
  
  $scope.quotenos=[];
  $scope.loadfromquote=function()
	{
		
			$http({
							method:'POST',
							url: $rootScope.apiurl+'adminapi/loadquotenos',
							data:{Status:"Approved"}					
							}).then(function successCallback(data) {
							
$scope.quotenos=data.data;							
				console.log(data);					
								$scope.formquote={}	;			
		console.log("quote form");
			$scope.quote={};	


	$scope.qformmodalInstance = $uibModal.open({
		keyboard:false,
		size:'lg',
		backdrop:"static",
   	   templateUrl: 'templates/rir/quoteform.html',
     		scope:$scope,
     	
   	 });
								}, function errorCallback(data) {
							console.log(data);
							 $scope.flags.qdetails=false;
							toaster.pop({toasterId:11,type:"error",body:data.data.msg});
							
						}); 
								
		
	}
	
	$scope.closeqform = function () {
			
			$scope.formquote={}	
		$scope.quote={};
    	$scope.qformmodalInstance.dismiss('cancel');
		
 	 };
  
  
  $scope.flags.qdetails=false;
  $scope.getqdetails=function(qno)
  {
	  $scope.quote={};
				$http({
							method:'POST',
							url: $rootScope.apiurl+'adminapi/getquote',
							data:{qno:qno,Status:"Approved"}					
							}).then(function successCallback(data) {
						console.log(data);
						 $scope.flags.qdetails=true;
					$scope.quote=data.data.allquotes;
$scope.labusers=data.data.labusers;					
								}, function errorCallback(data) {
							console.log(data);
							 $scope.flags.qdetails=false;
							toaster.pop({toasterId:11,type:"error",body:data.data.msg});
							
						}); 
  }
  
   $scope.dateOptions = {
    //customClass: getDayClass,
    minDate: new Date(),
    showWeeks: false
  };
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
 
  
  $scope.savesamples=function(con,log)
  {
	  if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.log=log;
		$scope.issaving=false;
			
			 $scope.cnfrmsampleregmodal = $uibModal.open({
		keyboard:false,
		backdrop:"static",
   	   templateUrl: 'sampleregconfirmModal',
     		 scope: $scope,
     	 
   	 });
			
		}
		else if(con=='approved')
		{
			$scope.issaving=true;
			$http({
		    		 method:'POST',
		       		 url:'admin/adminapi/savesamples/',
					data:log
		   		 }).then(function successCallback(data) {
					  $scope.flags.qdetails=false;
					 console.log(data);
					  $scope.getdata(1);
					// $scope.confirmModal = false;
					// $scope.issaving=false;
					 $scope.closeregconfirmModal();
					 $scope.closeqform();
					 $scope.issaving=false;
					// $scope.getallcats();
					// console.log(data);
					//$rootScope.getallsub();
		        	}, function errorCallback(data) {
						toaster.pop({toasterId:11,type:"error",body:data.data.msg});
		        		console.log(data);
						$scope.issaving=false;
				});
		}

	}

	$scope.closeregconfirmModal=function()
 	{
 		$scope.cnfrmsampleregmodal.dismiss('cancel');
 	}
	

	
	$scope.mailflag={};
   $scope.mailflag.sending=false;
	
	$scope.closesendmailmodal=function()
	{
		 $scope.mailflag.sending=false;
		$scope.emailconfirmmodalInstance.dismiss('cancel');
	}
	
	
	$scope.sendmail=function(rir,param)
	{
		if(param==='confirm')
		{
			
			
			$scope.mailinfo=rir;
			$scope.mailinfo.MailTo=rir.CustEmail;
			 $scope.emailconfirmmodalInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'templates/common/confirmmailmodal.html',
     	 scope: $scope,
   	 });
	 
		}
		else if(param==='send')
		{
			 $scope.mailflag.sending=true;
		$http({		method:'PUT',
				url:'admin/api/sendrirmail/'+rir.Id,
				data:rir
				}).then(function successCallback(data) {
			
				//console.log(data);
				 $scope.mailflag.sending=false;
				toaster.pop({ type: 'success', body: data.data.msg, toasterId: '11' });
				
				
				$scope.closesendmailmodal();
				}, function errorCallback(data) {
					console.log(data);
					 $scope.mailflag.sending=false;
					 	toaster.pop({toasterId:11,type:"error",body:data.data.msg});
		        	
			});
		}
	}

});

App.filter("toArray", function(){
    return function(obj) {
        var result = [];
        angular.forEach(obj, function(val, key) {
            result.push(val);
        });
        return result;
    };
});
App.controller('riraddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,SampleNames,allpredata,configparam)
{
	'use strict';
	$scope.alltests=[];
	$scope.rir={};
	$scope.mdsnos=[];
	$scope.tdsnos=[];
	$scope.batchcodes=[];
	$scope.allcustomers=[];
	$scope.rir.IsMdsTds="mds";
	$scope.rir.selecttest="";
	$scope.editflag=false;
	$scope.flags={};
	$scope.batchrange="";
	$scope.flags.oneclick=false;
	$scope.flags.editflag=false;
	$scope.flags.uniqueheatno=false;
	$scope.notapplicable=true;
	$scope.csubstandards=[];
	$scope.msubstandards=[];
		$scope.heatranges=[];
	
	$scope.exists=$localstorage.getObject(configparam.AppName);
	 $scope.dateOptions = {
    //customClass: getDayClass,
    minDate: new Date(),
    showWeeks: false
  };
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
 
	$scope.getSelectedState = function() {
             $scope.flags.mintestreq=false;
              angular.forEach($scope.rir.alltests, function(key) {
             
                if(key.Applicable==='true') {
                  $scope.flags.mintestreq=true;
                }
              });
            }
			
			
			$scope.resetsubstd=function(test)
			{
				test.SubStdId="";
			}
			
		
		
$scope.getmdstds=function(param)
{
	console.log(param);
	$scope.rir.MdsTdsId=null;
	$scope.rir.applicabletest=[];
	$scope.rir.addtest=null;
	$http({
		
					method:'POST',
					url: $rootScope.apiurl+'api/getmdstds',
					data:{notype:param}
									
					}).then(function successCallback(data) {
				console.log(data);
				$scope.allmdstds=data.data;
			 	
			}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
		
}		
	


	
	$scope.getmdstds($scope.rir.IsMdsTds);
	$scope.subindflag=false;
	$scope.proindflag=false;
	$scope.getsubind=function(indid)
	{
		$scope.alltests=[];
		$scope.rir.addtest=null;
		//$scope.rir.applicabletest=[];
		//console.log(indid);
		var industry=_.findWhere($scope.industries,{Id:indid});
		if(industry.Children.length>0)
		{
			$scope.subindflag=true;
			$scope.subindustries=industry.Children;
		}
		else
		{
			$scope.rir.IndId=indid;
			
			$scope.getinddata(indid);
			$scope.subindflag=false;
			$scope.proindflag=false;
		}
		
		//console.log(industry);
	}
	
	$scope.getcatdata=function(indid)
	{
		var subindustry=_.findWhere($scope.subindustries,{Id:indid});
		
		if(subindustry.Children.length>0)
		{
			$scope.proindflag=true;
			$scope.procategories=subindustry.Children;
		console.log(subindustry);
		}
		else
		{
			$scope.rir.IndId=indid;
			
			$scope.getinddata(indid);
			
			$scope.proindflag=false;
		}
		
	}
	
	
	$scope.getinddata=function(ind)
			{
				
				 $http({
					method:'GET',
					url: $rootScope.apiurl+'api/ririnddata/'+ind,
									
					}).then(function successCallback(data) {
				console.log(data);
				$scope.alltests=[];
			 	
		$scope.allorgtests=data.data.alltests;	
		angular.forEach($scope.allorgtests,function(val){
			$scope.alltests.push(val);
		})
		
		
		
		$scope.testconditions=data.data.testconditions;
		$scope.alllabs=data.data.alllabs;
			angular.forEach($scope.alltests,function(item){
			if(!_.isEmpty(item.ReqDate))
			{
			item.ReqDate=new Date(item.ReqDate);
			}
			else
			{
				item.ReqDate=new Date();
			
		
			}
		});
		
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
			}
	
	
	
	$scope.loadmdsdata=function(mdstds)
	{
		console.log(mdstds);
		$scope.rir.applicabletest=[];
		$scope.rir.addtest=[];
		angular.forEach(mdstds.allmdstdstests,function(val){
				
				$http({
					method:'PUT',
					url: $rootScope.apiurl+'api/testsubstds/'+val.TestId,
					data:{}				
					}).then(function successCallback(data) {
						
						val.allsubstds=data.data.allsubstds;
						val.alltestmethods=data.data.alltestmethods;
						var nextWeek = new Date(new Date().valueOf());
	

    // adding/subtracting 7 for next/previous week. Can be less for your use case
    nextWeek.setDate(nextWeek.getDate() + val.Frequency);

    // update the model value (if this is your approach)
    val.ReqDate = nextWeek;
	
	 var item={	
					TSeq:val.TSeq,IndId:val.IndId,TUID:val.TUID,
					TestId:val.TestId,TestName:val.TestName,LabId:"",
					IsStd:val.IsStd,IsPlan:val.IsPlan,IsTestMethod:val.IsTestMethod,
					SSID:"", 
					TMID:"",
					Price:"",ExtraInfo:"",allsubstds:val.allsubstds,alltestmethods:val.alltestmethods,
					};
						$scope.alltests.push(item);
						$scope.rir.applicabletest.push(val);
						$scope.rir.addtest.push(val.TSeq);
						
					}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
			
			
			
			
		});
		
	
	
	
	}
	
	$scope.getsubstandards=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.substandards,{TestId:type});
		}
	}
	
	$scope.getsubplans=function(substdid)
	{
	//console.log(substdid);
if(angular.isDefined(substdid))
		{
				return _.where($scope.substdplans,{SubStdId:substdid});
		}
		
	}
	
	
	$scope.gettestmethods=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.testmethods,{TestId:type});
		}
	}
	
	
		$scope.additemrow=function(param,idx)
	{
		console.log(idx);
		if(param==='new')
		{
			$scope.qitem={};	
$scope.qitem.index='';			
		}
		else
		{
			$scope.qitem=param;
			$scope.qitem.index=idx;
			$scope.getinddata(param.IndId);
			$scope.getsubind(param.PIndId);
			$scope.getcatdata(param.SubIndId);
		}		
		
		 $scope.quoteitemmodal = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'quoteitemModal.html',
     	 scope: $scope,
   	 });
		
	}
	
	$scope.closeqitemModal=function(){
		$scope.qitem={};
		$scope.quoteitemmodal.dismiss('cancel');
	}
	
	$scope.getsubstandardsbytest=function(par2)
	{
		 $http({
					method:'PUT',
					url: $rootScope.apiurl+'api/testsubstds/'+par2,
					data:{}				
					}).then(function successCallback(data) {
				console.log(data);
			
		
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
				
		
	}
	
	
	$scope.addduplicate=function(par)
	{
		var param=angular.copy(par);
		
		var tcount=_.where($scope.alltests,{TUID:param.TUID}).length;
		var tseq=param.TSeq;
		var arr=tseq.split("-");
		var no=parseInt(tcount)+1;
		
		param.TSeq=arr[0]+'-'+no;
		
		
		var tname=param.TestName;
		var arr2=tname.split("-");
		var no2=parseInt(tcount)+1;
		
		param.TestName=arr2[0]+'-'+no2;
		
		console.log(param);
		$scope.alltests.push(param);
		console.log($scope.alltests);
		 var item={	
					TSeq:param.TSeq,IndId:param.IndId,TUID:param.TUID,
					TestId:param.TestId,TestName:param.TestName,LabId:"",
					IsStd:param.IsStd,IsPlan:param.IsPlan,IsTestMethod:param.IsTestMethod,
					SSID:"", 
					TMID:"",
					Price:"",ExtraInfo:"",allsubstds:param.allsubstds,alltestmethods:param.alltestmethods,
					};
			$scope.rir.addtest.push(param.TSeq);				
					
		$scope.rir.applicabletest.push(item);
	}
	
	
	
	$scope.addwitness=function(par)
	{
		var param=angular.copy(par);
		
		var tcount=_.where($scope.alltests,{TUID:param.TUID}).length;
		
		var tseq=param.TSeq;
		var arr=tseq.split("-");
		var no=parseInt(tcount)+1;
		
		param.TSeq=arr[0]+'-'+no;
		
		
		var tname=param.TestName;
		var arr2=tname.split("-");
		var no2=parseInt(tcount)+1;
		
		param.TestName=arr2[0]+'-'+no2+'W';
		
		console.log(param);
		$scope.alltests.push(param);
		console.log($scope.alltests);
		 var item={	
					TSeq:param.TSeq,IndId:param.IndId,TUID:param.TUID,
					TestId:param.TestId,TestName:param.TestName,LabId:"",
					IsStd:param.IsStd,IsPlan:param.IsPlan,IsTestMethod:param.IsTestMethod,
					SSID:"", 
					TMID:"",
					Price:"",ExtraInfo:"",allsubstds:param.allsubstds,alltestmethods:param.alltestmethods,
					};
			$scope.rir.addtest.push(param.TSeq);				
					
		$scope.rir.applicabletest.push(item);
	}
	
	
	
	$scope.addtoapplicable=function(par1,par2)
	{
		console.log(par1);
		console.log(par2);
		//console.log($scope.alltests);
		var test=_.findWhere($scope.alltests,{TSeq:par2});			
		console.log(test);		
		
			$http({
					method:'PUT',
					url: $rootScope.apiurl+'api/testsubstds/'+par1.TestId,
					data:{}				
					}).then(function successCallback(data) {
				console.log(data);
			 var item={	
					TestCondId:1,TSeq:par1.TSeq,TUID:par1.TUID,
					TestId:par1.TestId,TestName:par1.TestName,LabId:"",
					IsStd:par1.IsStd,IsPlan:par1.IsPlan,IsTestMethod:par1.IsTestMethod,
					SSID:"", baseparams:[],
					TMID:"",
					Price:"",ExtraInfo:"",allsubstds:data.data.allsubstds,alltestmethods:data.data.alltestmethods,
					ReqDate:new Date()
					};
							
					
		$scope.rir.applicabletest.push(item);
		
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
			
		
		
	}
	
	
	
	$scope.removeapptest=function(item,model)
	{
		//$scope.rir.applicabletest.splice(idx,1);
		console.log(item);
		//var idx=_.indexOf($scope.rir.applicabletest,{TestId:item.TestId});
		$scope.rir.applicabletest=_.without($scope.rir.applicabletest, _.findWhere($scope.rir.applicabletest,{TSeq:item.TSeq}));
	}
	
	
	$scope.delrirtest=function(param,idx)
	{
		console.log(param)
	
		
			$scope.rir.addtest=_.without($scope.rir.addtest,param.TSeq);
			$scope.rir.applicabletest=_.without($scope.rir.applicabletest, _.findWhere($scope.rir.applicabletest,{TSeq:param.TSeq}));
			if($scope.rir.applicabletest.length<1)
			{
				$scope.rir.MdsTdsId="";
			}
	}
	

		$scope.getorgdata=function(param)
	{
		console.log(param);
		$scope.alltests=angular.copy($scope.allorgtests);	

	}
	
	
	
	$scope.getrirdet=function(quote)
	{
		$scope.flags.oneclick=true;
			
	}
  
  
  if(angular.isDefined(allpredata.data))
	{
		
		$scope.customers=allpredata.data.customers;
		$scope.suppliers=allpredata.data.suppliers;
		$scope.industries=allpredata.data.industries;
		$scope.labusers=allpredata.data.labusers;
		
		$scope.batchcodes=allpredata.data.batchcodes;
		$scope.batchrange=allpredata.data.batchrange;
		$scope.heatranges=allpredata.data.heatranges;
		
		
		$scope.standards=[];
		
		$scope.allexttests=[];
		$scope.alltests=[];
		$scope.hardtypes=[];
		$scope.hardloads=[];
		$scope.impacttemps=[];
		$scope.testmethods=[];
		$scope.ssthrs=[];
		
		$scope.rir.BCGenerated='true';
		$scope.rir.DR='false';
		$scope.rir.applicabletest=[];
		$scope.rir.PIndId=1;
		
		$scope.getsubind($scope.rir.PIndId);
		
		console.log(allpredata.data);
		
		$scope.getSelectedState();
		angular.forEach($scope.rir.alltests,function(item){
			if(!_.isEmpty(item.ReqDate))
			{
			item.ReqDate=new Date(item.ReqDate);
			}
			else
			{
				item.ReqDate=new Date();
			
		
			}
		});
		
		angular.forEach($scope.rir.applicabletest,function(item){
			if(!_.isEmpty(item.ReqDate))
			{
			item.ReqDate=new Date(item.ReqDate);
			}
			else
			{
				item.ReqDate=new Date();
			
		
			}
		});
	}
	
	
  $scope.rirsave=function(rir)
  {
	  console.log(rir);  
	rir.EnteredBy=$rootScope.dboard.uid;
	rir.BranchId=$scope.exists.Location;
	 console.log(rir);  
	$scope.flags.oneclick=true;
	 $http({
					method:'POST',
					url: $rootScope.apiurl+'api/riradd',
					data:rir					
					}).then(function successCallback(data) {
				console.log(data);
			 	//$scope.getpages();
				$scope.flags.oneclick=false;
				toaster.pop({toasterId:11,type:"success",body:"Successfully Added."});
				$state.go('app.receipt');
			 	
						}, function errorCallback(data) {
					console.log(data);
					$scope.flags.oneclick=false;
					 $scope.flags.secondpart=false;
		toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
	  
  }
  $scope.addaddress=function()
	{
		var address={Name:"",Email:"",ContactNo:"",ContactPerson:"",Address:""};
		
		$scope.customer.Addresses.push(address);
	}
	
	
	$scope.deladdress=function(item,idx)
	{
		//var address={Name:"",Email:"",ContactNo:"",ContactPerson:"",Address:""};
		if(angular.isDefined(item.Id))
		{
			$scope.customer.DelAddresses.push(item);
		}
		$scope.customer.Addresses.splice(idx,1);
	}
  $scope.addnewcust=function()
			{
				
			$rootScope.editflag=false;
			$rootScope.disablebtn=true;
			$scope.customer={}				
		$scope.customer.Addresses=[];
				


	$scope.customermodalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
   	   templateUrl: 'templates/users/customeradd.html',
     		scope:$scope,
     	
   	 });
			}
			
			$scope.custcancel = function () {
			
			$scope.customer={}	
			$scope.issaving=false;
			$rootScope.disablebtn=false;
    	$scope.customermodalInstance.dismiss('cancel');
		
 	 };
 $scope.savecustomer=function(customer)
	{
	$scope.issaving=true;
	
	customer.CreatedBy=$rootScope.dboard.uid;

		$http({		method:'POST',
				url:$rootScope.apiurl+'settingapi/customeradd',	
				data:customer
				}).then(function successCallback(data) {
			
				//console.log(data);
				$scope.rir.CustomerId=data.data.CustomerId;
				$scope.customers=data.data.customers;
			//	$scope.categoryid=angular.fromJson(data);
				toaster.pop('wait', "", "Customer Detail successfully saved.");
				
				$scope.custcancel();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 			
	
	};
	
	
	//---fetching sample names for auto suggestion
	$scope.samplenames=[];
	          SampleNames.fetchSamples().then(function(data){	
		  
			$scope.samplenames=data;
		});
     
       
  
  
 

})



App.controller('rireditController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,$q,
$rootScope,$state,AuthenticationService,$timeout,SampleNames,allpredata)
{
	'use strict';
	//$scope.alltests=[];
	$scope.rir={};
	$scope.standards=[];
	$scope.mdsnos=[];
	$scope.tdsnos=[];
	$scope.csubstandards=[];
	$scope.msubstandards=[];
	$scope.hardtypes=[];
	$scope.hardloads=[];
	$scope.ssthrs=[];
	$scope.batchcodes=[];
	$scope.testmethods=[];
	$scope.impacttemps=[];
	$scope.allexttests=[];
	$scope.flags={};
	$scope.flags.mintestreq=false;
	$scope.editflag=true;
	$scope.flags.oneclick=false;
	$scope.flags.editflag=false;
	
	 $scope.dateOptions = {
    //customClass: getDayClass,
    minDate: new Date(),
    showWeeks: true
  };
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
 
$scope.getSelectedState = function() {
             $scope.flags.mintestreq=false;
              angular.forEach($scope.rir.alltests, function(key) {
             
                if(key.Applicable==='true') {
                  $scope.flags.mintestreq=true;
                }
              });
            }
			
			
			
			$scope.addduplicate=function(par)
	{
		console.log(par);
		var param=angular.copy(par);
		
		var tcount=_.where($scope.alltests,{TUID:param.TUID}).length;
		console.log(tcount);
		var tseq=param.TSeq;
		var arr=tseq.split("-");
		var no=parseInt(tcount)+1;
		
		param.TSeq=arr[0]+'-'+no;
		
		console.log(param.TSeq);
		var tname=param.TestName;
		var arr2=tname.split("-");
		var no2=parseInt(tcount)+1;
		
		param.TestName=arr2[0]+'-'+no2;
		
		console.log(param);
		$scope.alltests.push(param);
		console.log($scope.alltests);
		 var item={	
					TSeq:param.TSeq,IndId:param.IndId,TUID:param.TUID,
					TestId:param.TestId,TestName:param.TestName,LabId:"",
					IsStd:param.IsStd,IsPlan:param.IsPlan,IsTestMethod:param.IsTestMethod,
					SSID:"", 
					TMID:"",
					Price:"",ExtraInfo:"",allsubstds:param.allsubstds,alltestmethods:param.alltestmethods,
					};
			$scope.rir.addtest.push(param.TSeq);				
					
		$scope.rir.applicabletest.push(item);
	}
	
	
  $scope.addwitness=function(par)
	{
		var param=angular.copy(par);
		
		var tcount=_.where($scope.alltests,{TUID:param.TUID}).length;
		
		var tseq=param.TSeq;
		var arr=tseq.split("-");
		var no=parseInt(tcount)+1;
		
		param.TSeq=arr[0]+'-'+no;
		
		
		var tname=param.TestName;
		var arr2=tname.split("-");
		var no2=parseInt(tcount)+1;
		
		param.TestName=arr2[0]+'-'+no2+'W';
		
		console.log(param);
		$scope.alltests.push(param);
		console.log($scope.alltests);
		 var item={	
					TSeq:param.TSeq,IndId:param.IndId,TUID:param.TUID,
					TestId:param.TestId,TestName:param.TestName,LabId:"",
					IsStd:param.IsStd,IsPlan:param.IsPlan,IsTestMethod:param.IsTestMethod,
					SSID:"", 
					TMID:"",
					Price:"",ExtraInfo:"",allsubstds:param.allsubstds,alltestmethods:param.alltestmethods,
					};
			$scope.rir.addtest.push(param.TSeq);				
					
		$scope.rir.applicabletest.push(item);
	}
	
	
	
 
  $scope.getinddata=function(ind)
			{
				
				 $http({
					method:'GET',
					url: $rootScope.apiurl+'api/ririnddata/'+ind,
									
					}).then(function successCallback(data) {
				console.log(data);
			 	$scope.standards=data.data.standards;
		$scope.substandards=data.data.substandards;
		$scope.allorgtests=data.data.alltests;	
		$scope.alltests=angular.copy($scope.allorgtests);	
		$scope.testmethods=data.data.testmethods;
		$scope.testconditions=data.data.testconditions;
		$scope.alllabs=data.data.alllabs;
		$scope.substdplans=data.data.substdplans;
			angular.forEach($scope.alltests,function(item){
			if(!_.isEmpty(item.ReqDate))
			{
			item.ReqDate=new Date(item.ReqDate);
			}
			else
			{
				item.ReqDate=new Date();
			
		
			}
		});
		
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
			}
			
			
			
			$scope.iftestsempty=function()
			{
				
				if($scope.rir.applicabletest.length>0)
				{
					
					return true;
					
				}
				else
				{
					
					return false;
				}
			}
	
	$scope.loadmdsdata = function(mdstds) {
    console.log('Starting loadmdsdata', mdstds);

     $scope.rir.applicabletest=[];
	  $scope.rir.addtest=[];
        angular.forEach(mdstds.allmdstdstests, function(val) {
          
           $http({
                method: 'PUT',
                url: $rootScope.apiurl + 'api/testsubstds/' + val.TestId,
                data: {}
            }).then(function successCallback(data) {              
			
                val.allsubstds = data.data.allsubstds;
                val.alltestmethods = data.data.alltestmethods;
                $scope.rir.applicabletest.push(val);
                $scope.rir.addtest.push(val.TSeq);
            }, function errorCallback(data) {
                console.log('API error for', val.TestId, data);
                //toaster.pop({ toasterId: 11, type: "error", body: data.data });
            });

           
        });

        
    };

	$scope.shouldHideRemoveIcon = true; 
	
	$scope.removeapptest=function(item,model)
	{
		//$scope.rir.applicabletest.splice(idx,1);
		console.log(item);
		console.log(model);
	var gettest = _.find($scope.rir.applicabletest, function(item) {
    return item.TSeq === item.TSeq && item.RTID !== null;
});
if(!_.isEmpty(gettest))
{
	
	if(angular.isDefined(gettest.RTID) && gettest.RTID !='')
		{
			$scope.rir.deltests.push(gettest);
		}
}
		//var idx=_.indexOf($scope.rir.applicabletest,{TestId:item.TestId});
		$scope.rir.applicabletest=_.without($scope.rir.applicabletest, _.findWhere($scope.rir.applicabletest,{TSeq:item.TSeq}));
		if($scope.rir.applicabletest.length<1)
			{
				$scope.rir.MdsTdsId="";
			}
		
		
	}
	
	$scope.delrirtest=function(param,idx)
	{
		console.log(param)
		if(angular.isDefined(param.RTID) && param.RTID !='')
		{
			$scope.rir.deltests.push(param);
		}
		
			$scope.rir.addtest=_.without($scope.rir.addtest,param.TSeq);
			$scope.rir.applicabletest=_.without($scope.rir.applicabletest, _.findWhere($scope.rir.applicabletest,{TSeq:param.TSeq}));
			if($scope.rir.applicabletest.length<1)
			{
				$scope.rir.MdsTdsId="";
			}
	}
	
	

  		
$scope.getmdstds=function(param)
{
	console.log(param);
	$http({
		
					method:'POST',
					url: $rootScope.apiurl+'api/getmdstds',
					data:{notype:param}
									
					}).then(function successCallback(data) {
				console.log(data);
				$scope.allmdstds=data.data;
			 	
			}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
		
}		
			


  $scope.rirsave=function(rir)
  {
	  console.log(rir);  
	rir.EnteredBy=$rootScope.dboard.uid;
	rir.BranchId=1;//$scope.exists.Location;
	$scope.flags.oneclick=true;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'api/rirupdate/'+rir.Id,
					data:rir					
					}).then(function successCallback(data) {
				console.log(data);
			 	//$scope.getpages();
				$scope.flags.oneclick=false;
				
				$state.go('app.receipt');
			 	
						}, function errorCallback(data) {
					console.log(data);
					$scope.flags.oneclick=false;
					 $scope.flags.secondpart=false;
		toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
	  
  }
  
  
  	$scope.subindflag=false;
	$scope.proindflag=false;
	$scope.getsubind=function(indid)
	{
		$scope.alltests=[];
		$scope.rir.addtest=null;
		$scope.rir.applicabletest=[];
		//console.log(indid);
		var industry=_.findWhere($scope.industries,{Id:indid});
		if(industry.Children.length>0)
		{
			$scope.subindflag=true;
			$scope.subindustries=industry.Children;
		}
		else
		{
			$scope.rir.IndId=indid;
			
			$scope.getinddata(indid);
			$scope.subindflag=false;
			$scope.proindflag=false;
		}
		
		//console.log(industry);
	}
	
	$scope.getcatdata=function(indid)
	{
		var subindustry=_.findWhere($scope.subindustries,{Id:indid});
		
		if(subindustry.Children.length>0)
		{
			$scope.proindflag=true;
			$scope.procategories=subindustry.Children;
		console.log(subindustry);
		}
		else
		{
			$scope.rir.IndId=indid;
			
			$scope.getinddata(indid);
			
			$scope.proindflag=false;
		}
		
	}
	
	
	$scope.addtoapplicable=function(par1,par2)
	{
		console.log(par1);
		console.log(par2);
		//console.log($scope.alltests);
		var test=_.findWhere($scope.alltests,{TSeq:par2});			
		console.log(test);		
		
			$http({
					method:'PUT',
					url: $rootScope.apiurl+'api/testsubstds/'+par1.TestId,
					data:{}				
					}).then(function successCallback(data) {
				console.log(data);
			 var item={	
					TestCondId:1,TSeq:par1.TSeq,TUID:par1.TUID,
					TestId:par1.TestId,TestName:par1.TestName,LabId:"",
					IsStd:par1.IsStd,IsPlan:par1.IsPlan,IsTestMethod:par1.IsTestMethod,
					SSID:"", 
					TMID:"",
					Price:"",ExtraInfo:"",allsubstds:data.data.allsubstds,alltestmethods:data.data.alltestmethods,
					ReqDate:new Date()
					};
							
					
		$scope.rir.applicabletest.push(item);
		
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
			
		
		
	}
	
	
	$scope.loadtoapplicable=function(par1,par2)
	{
		
		var test=_.findWhere($scope.alltests,{TSeq:par2});			
			
		
			$http({
					method:'PUT',
					url: $rootScope.apiurl+'api/testsubstds/'+par1.TestId,
					data:{}				
					}).then(function successCallback(data) {
				//console.log(data);
			 var item={	
					TestCondId:1,TSeq:par1.TSeq,TUID:par1.TUID,RTID:par1.RTID,
					TestId:par1.TestId,TestName:par1.TestName,LabId:"",
					IsStd:par1.IsStd,IsPlan:par1.IsPlan,IsTestMethod:par1.IsTestMethod,
					SSID:par1.SSID, 
					TMID:par1.TMID,
					Price:"",ExtraInfo:"",allsubstds:data.data.allsubstds,alltestmethods:data.data.alltestmethods,
					ReqDate:new Date()
					};
							
					console.log(item);
		$scope.rir.applicabletest.push(item);
		
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
			
		
		
	}
	
	
	
	
	$scope.getsubstandards=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.substandards,{TestId:type});
		}
	}
	$scope.getsubplans=function(substdid)
	{
	//console.log(substdid);
if(angular.isDefined(substdid))
		{
				return _.where($scope.substdplans,{SubStdId:substdid});
		}
		
	}
	
	$scope.gettestmethods=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.testmethods,{TestId:type});
		}
	}
	
	$scope.getsubparameters=function(substdid)
	{
		console.log("par");
if(angular.isDefined(substdid))
		{
		return _.where($scope.substandards,{Id:substdid}).Parameters;
		}
		
	}
	
	
	
	$scope.removelabtest=function(item,model)
	{
		console.log(item);
		console.log(model);
		item.DelLabs.push(model);
		console.log(item);
		console.log(model);
	}
  
  if(angular.isDefined(allpredata.data))
	{
		console.log(allpredata);
		$scope.rir=angular.copy(allpredata.data.rir);
			
		$scope.substandards=allpredata.data.predata.substandards;	
		
		$scope.customers=allpredata.data.predata.customers;
		$scope.suppliers=allpredata.data.predata.suppliers;
		$scope.industries=allpredata.data.predata.industries;
		$scope.labusers=allpredata.data.predata.labusers;
			
		$scope.batchcodes=allpredata.data.predata.batchcodes;
		$scope.batchrange=allpredata.data.predata.batchrange;
		$scope.heatranges=allpredata.data.predata.heatranges;		
	
		$scope.alltests=allpredata.data.predata.alltests;
	
	console.log($scope.rir);
	//$scope.getsubind($scope.rir.PIndId);
	//$scope.getcatdata($scope.rir.SubIndId);
	//$scope.getinddata($scope.rir.IndId);
	
	//$scope.addduplicate();
		
		//$scope.loadmdsdata($scope.rir.IsMdsTds);
		$scope.getmdstds($scope.rir.IsMdsTds);
		angular.forEach($scope.rir.rirtests,function(item){
			if(!_.isEmpty(item.ReqDate))
			{
			item.ReqDate=new Date(item.ReqDate);
			}
			else
			{
				item.ReqDate=new Date();		
			}
			$scope.loadtoapplicable(item,item.TSeq);
		//	$scope.getsubstandards(item.TestId);
		});
		
	}
	
	
	$scope.samplenames=[];
	          SampleNames.fetchSamples().then(function(data){		
			$scope.samplenames=data;
		});
     
  
 
  
  
  
  
});

App.directive('validateBatchcode',  function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) {
			
			scope.$watch(function() {
           // element.bind('blur', function (e) {
                if (!ngModel || !element.val()) return;
                var keyProperty = scope.$eval(attrs.wcUnique);
                var currentValue = element.val();
				 //console.log(currentValue);
               var unique= _.find(scope.batchcodes,function(key){
				   
				   if(currentValue===key)
						{
							//console.log(currentValue)
							return key;
						}
				   });
			 
			   if(_.isEmpty(unique))
			   {
				     //console.log(currentValue);
				   ngModel.$setValidity('unique', false);
			   }
			   else
			   {
				    // console.log(currentValue);
				    ngModel.$setValidity('unique', true);
			   }
			   
			   
                   
            });
        }
    }
});


App.controller('invoicesController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$q,$interval,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	
	console.log(alldata);
	$scope.allinvoices=alldata.data.allinvoices;
	$scope.invtypes=alldata.data.invtypes;
	$scope.taxlabel=alldata.data.TaxLabel;
		$scope.customers=alldata.data.customers;
		$scope.pageSize=25;
		$scope.currentPage=1;
		$scope.filter={};
		$scope.totalitems=alldata.data.totalitems;
	$scope.disctypes=angular.copy(alldata.data.disctypes);
		 $scope.dateOptions = {
    //customClass: getDayClass,
    //minDate: new Date(),
    showWeeks: true
  };
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
  
  $scope.popup3 = {
    opened: []
  };
   $scope.open3 = function(index) {
    $scope.popup3.opened[index] = true;
  };
  
  
  $scope.showcustinfo=function(id)
  {
	  $scope.invoice.PoNos=[];
	   $scope.invoice.Details=[];
	   $scope.updatetotal($scope.invoice);
	  $scope.customer=_.findWhere($scope.customers,{Id:id});
	  $scope.getallponos(id);
  }
  
  
  
  
  	$scope.viewinvoice=function(item)
	{
		
		$scope.invoice=angular.copy(item);
		$scope.invoice.InvDate=new Date($scope.invoice.InvDate);
		$scope.invoice.DueDate=new Date($scope.invoice.DueDate);
		 $scope.vieworderinst = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		
   	   templateUrl: 'ordermanage',
     	 scope: $scope,
   	 });
  
	}
	
	
	
	$scope.closemModal=function()
 	{
 		$scope.vieworderinst.dismiss('cancel');
 	}




 $scope.getallponos=function(custid)
  {
	    $http({
		  
					method:'PUT',
					url: $rootScope.apiurl+'adminapi/getponos/'+custid,
					data:{}					
					}).then(function successCallback(data) {
				console.log(data);
				$scope.allpos=data.data.allpos;
			 	
					}, function errorCallback(data) {
					console.log(data);
					$scope.sectionview=false;
					 $scope.flags.loadwait=false;
					 toaster.pop({toasterId:11,type:"error",body:data.data});
		
				}); 
  }
  
  

$scope.ivflags={};
  
$scope.editinvoice=function(param)
	{
		
	 	if(param==='new')
		{
			//$scope.info={};		
			$scope.ivflags.editflag=false;
$scope.invoice={};
$scope.invoice.InvDate=new Date();
$scope.invoice.DueDate=new Date();
    var nextWeek = new Date($scope.invoice.InvDate.valueOf());

    // adding/subtracting 7 for next/previous week. Can be less for your use case
    nextWeek.setDate(nextWeek.getDate() + 7);

    // update the model value (if this is your approach)
    $scope.invoice.DueDate = nextWeek;
//$scope.invoice.DueDate=new Date(7);
$scope.invoice.Details=[];		
$scope.invoice.Total="";
$scope.invoice.Subtotal	="";
$scope.invoice.Discount=0;
$scope.invoice.InvType='B';
$scope.invoice.DiscType='P';
if(alldata.data.IsTax==='1')
{
	$scope.invoice.IsTax=alldata.data.IsTax;
	$scope.invoice.Tax=alldata.data.Tax;
}

		}
		else
		{		
$scope.ivflags.editflag=true;	
			$scope.invoice=angular.copy(param);
			$scope.invoice.InvDate=new Date(param.InvDate);
			$scope.invoice.DueDate=new Date(param.DueDate);
				if($scope.invoice.Tax>0)
				{
					$scope.invoice.IsTax='1';
					//$scope.invoice.Tax=alldata.data.Tax;
				}
			angular.forEach($scope.invoice.Details, function(val){
				val.Qty=parseInt(val.Qty);
			});
			$scope.invoice.DelDetails=[];
			 $scope.showcustinfo($scope.invoice.CustId);
			 $scope.updatetotal($scope.invoice);
		}	


	 $scope.invmodalInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'invoiceModal.html',
     	 scope: $scope,
   	 });
	 
	 
	}
	
	$scope.closeinvoiceModal=function()
 	{
 		$scope.invmodalInstance.dismiss('cancel');
 	}
	
	
	$rootScope.getsubtotal = function(invoice) 
	{
		
        var subtotal = 0;
		var cart = invoice;
        angular.forEach(cart.Details, function(item) {
			if(angular.isDefined(item.Price))
			{
				
            subtotal += (item.Qty) * item.Price;
			console.log(subtotal);
			}
        });
		
		return subtotal;
    };
	
		
	
		   
		   	$rootScope.gettotal = function(invoice) 
	{
		
        var total = 0;    
		
		
			//
			 var temptotal =  $rootScope.getsubtotal(invoice) -  ((invoice.Discount*$rootScope.getsubtotal(invoice))/100) ;
			 var tax=(temptotal*invoice.Tax)/100;
			 invoice.TotTax=tax;
			 total=temptotal+tax;
		
        return total;
    };
	
	
	$scope.resetitems=function(invtype)
	{
		$scope.invoice.CustId="";
		$scope.invoice.Details=[];
		 $scope.customer="";
		 
		 
	}
	
	
	$scope.updatetotal=function(invoice)
	{
		console.log('calutotal');
		$scope.invoice.Subtotal=	$rootScope.getsubtotal(invoice);
	
       $scope.invoice.Total=$rootScope.gettotal(invoice);
	}
	
	
	$scope.getinvoice=function(param)
	{
		$scope.invoice.Details=[];
		var id=$rootScope.dboard.uid;
		  $http({
					method:'PUT',
					url: $rootScope.apiurl+'adminapi/ponoinvoice/'+id,
					data:{qno:param}				
				}).then(function successCallback(data) {
				console.log(data);
				
				
			 	//$scope.getpages();
				angular.forEach(data.data.Details,function(val){
					$scope.invoice.Details.push(val);
					//$scope.updatetotal($scope.invoice);
				});
				
	
			

				//$scope.invoice.CustId=data.data.CustId;
				// $scope.showcustinfo($scope.invoice.CustId);
				//$scope.updatetotal($scope.invoice);
				$scope.invoice.Discount=0;
					$scope.invoice.Tax=data.data.Tax;
				$scope.invoice.Subtotal =	$rootScope.getsubtotal(data.data);					
				$scope.invoice.Total = $rootScope.gettotal(data.data);
				$scope.flags.oneclick=false;
			
			 	
						}, function errorCallback(data) {
					console.log(data);
					$scope.flags.oneclick=false;
		toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
	}
	
	
	$scope.additemrow=function()
	{
		$scope.invoice.Details.push({DType:"N",Details:"",Qty:1,Price:"",Total:""});
		$scope.updatetotal($scope.invoice);
	}
	
	$scope.delitem=function(param,idx)
	{
		if(angular.isDefined(param.Id))
		{
			$scope.invoice.DelDetails.push(param);
		}
		$scope.invoice.Details.splice(idx,1);
			$scope.updatetotal($scope.invoice);
	}
	
	$scope.resetdata=function()
	{
		$scope.filter={};
		$scope.getallinvoices(1);
	}
	
	$scope.getallinvoices=function(pageno)
	{
		var id=$rootScope.dboard.uid;
		console.log($scope.filter);
		$http({	
					method:'PUT',
					url:$rootScope.apiurl+'adminapi/getinvoices/'+id,
					data:{pl:$scope.pageSize,pn:pageno,filter:$scope.filter}
	     	 
	     		}).then(function successCallback(data) {
					console.log(data);
						$scope.allinvoices=data.data.allinvoices;
	$scope.invtypes=data.data.invtypes;
		$scope.customers=data.data.customers;
			     				
	     			}, function errorCallback(data) {
	     			console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
	     		});
	};
	
	
	
	
	$scope.invoicesave=function(invoice)
	{
		$scope.issaving=true;
	console.log(invoice);
	if(angular.isDefined(invoice.Id))
	{

		$http({	method:'PUT',
     		url:$rootScope.apiurl+'adminapi/updinvoice/'+invoice.Id,	
     		data:invoice
     			}).then(function successCallback(data) {
				$scope.closeinvoiceModal();
				$scope.getallinvoices(1);
		
     			}, function errorCallback(data) {
     				console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
     		});
	}
	else
	{	

		$http({		method:'POST',
				url:$rootScope.apiurl+'adminapi/addinvoice',	
				data:invoice
				}).then(function successCallback(data) {
			
				//console.log(data);
			//	$scope.categoryid=angular.fromJson(data);
				
				
				$scope.closeinvoiceModal();
				$scope.getallinvoices(1);
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 }   			
	
	}
  
  $scope.addaddress=function()
	{
		var address={Name:"",Email:"",ContactNo:"",ContactPerson:"",Address:""};
		
		$scope.customer.Addresses.push(address);
	}
	
	
	$scope.deladdress=function(item,idx)
	{
		//var address={Name:"",Email:"",ContactNo:"",ContactPerson:"",Address:""};
		if(angular.isDefined(item.Id))
		{
			$scope.customer.DelAddresses.push(item);
		}
		$scope.customer.Addresses.splice(idx,1);
	}
  
  $scope.addnewcust=function()
			{
				
			$rootScope.editflag=false;
			$rootScope.disablebtn=true;
			$scope.customer={}				
		
				


	$scope.customermodalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
   	   templateUrl: 'templates/users/customeradd.html',
     		scope:$scope,
     	
   	 });
			}
			
			$scope.custcancel = function () {
			
			$scope.customer={}	
			$scope.issaving=false;
			$rootScope.disablebtn=false;
    	$scope.customermodalInstance.dismiss('cancel');
		
 	 };
	 
 $scope.savecustomer=function(customer)
	{
	$scope.issaving=true;
	
	
customer.CreatedBy=$rootScope.dboard.uid;
		$http({		method:'POST',
				url:$rootScope.apiurl+'settingapi/customeradd',	
				data:customer
				}).then(function successCallback(data) {
			
				//console.log(data);
				$scope.invoice.CustId=data.data.CustomerId;
				$scope.customers=data.data.customers;
			//	$scope.categoryid=angular.fromJson(data);
			 $scope.showcustinfo($scope.invoice.CustId);
				toaster.pop('wait', "", "Customer Detail successfully saved.");
				
				$scope.custcancel();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 			
	
	};
	
	
	$scope.mflags={};
	$scope.mflags.oneclick=[];
	$scope.sendmail=function(invoice,idx)
	{
		$scope.mflags.oneclick[idx]=true;
		$http({		method:'PUT',
				url:$rootScope.apiurl+'adminapi/sendinvoice/'+invoice.Id,	
				data:invoice
				}).then(function successCallback(data) {
			
				console.log(data);
				toaster.pop({ type: 'info', body: data.data.msg, toasterId: 'Inv1' });
				$scope.mflags.oneclick[idx]=false;
				
				$scope.custcancel();
				}, function errorCallback(data) {
					console.log(data);
					$scope.mflags.oneclick[idx]=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	}
	
	$scope.closeapprovemodal=function()
	{
		$scope.modalInstance3.dismiss('cancel');
	}
		
$scope.approveinvoice=function(con,log)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			
			$scope.log=log;
			
			$scope.modalInstance3 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'approveModal',
				scope:$scope,
				windowClass:'fade-scale',
     	
				});
		}
		else if(con=='approve')
		{
			$scope.oneclick=true;
			
			$http({
		    		 method:'PUT',
		       		 url:$rootScope.apiurl+'adminapi/approveinvoice/'+log.Id,
					 data:{}
		        
		   		}).then(function successCallback(data) {
					 
					 console.log(data);
					$scope.closeapprovemodal();
					$scope.getallinvoices(1);
					$scope.oneclick=false;
					
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
						toaster.pop({toasterId:11,type:"error",body:data.data});
				});
		}
		
	}
//---	
	var stop=$interval(function() {
		$scope.getallinvoices($scope.currentPage);
		console.log("get calllog"+$scope.currentPage);
	},55000);
	
	$scope.$on('$destroy',function(){
		//console.log(stop);
    if(stop)
        $interval.cancel(stop);   
});

	
});



App.controller('paymentsController', function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata) {
	
	$scope.info={};
	
	//$scope.allemps=[];
	$scope.allinfo=[];
	
	$rootScope.disablebtn=false;
	$scope.pageSize=15;
	$scope.currentPage=1;
	$scope.allpaytypes=[];
	$scope.invoice={};
	//$scope.allmonths=[];
	if(angular.isDefined(alldata.data))
	{
		//$scope.allemps=alldata.data.allemps;
		$scope.allinfo=alldata.data.allinfo;
		$scope.allinvoices=alldata.data.allinvoices;
		//$scope.allmonths=alldata.data.allmonths;
		console.log(alldata.data);
	}
	
	$scope.getinvinfo=function(invid)
	{
		$scope.invoice=_.findWhere($scope.allinvoices,{Id:invid});
	}
	
$scope.open1 = function() {
    $scope.popup1.opened = true;
  };
  $scope.popup1 = {
    opened: false
  };
   $scope.dateOptions = {
    //dateDisabled: disabled,
    formatYear: 'yy',
    maxDate: new Date(),
    minDate:  new Date(1989, 5, 6),
    startingDay: 1
  };
	$scope.addinfo=function(param)
	{
	 	if(param==='new')
		{	$scope.queue=[];
			$rootScope.editflag=false;
			$rootScope.disablebtn=true;
			$scope.info={}	;	
			$scope.info.TransDate=new Date();		
		}
		else
		{	$scope.queue=[];
			$rootScope.editflag=true;
			$scope.loadingFiles = true;
			console.log(param);
			$scope.info=angular.copy(param);
			$scope.info.TransDate=new Date($scope.info.TransDate);
		}	

	$scope.modalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
		templateUrl: 'infoModal',
     	scope:$scope,  
     	
   	 });
	}

	$scope.closeinfoModal=function()
 	{
 		$scope.modalInstance.dismiss('cancel');	
 	}
	
	$scope.deleteinfo=function(con,id)
	{
		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.name=id.Name;
		
			$scope.modalInstance1 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'delModal',
				scope: $scope,
			});
		}
		else if(con=='delete')
		{
			$http({
		    		 method:'DELETE',
		       		 url:'admin/adminapi/delpayment/'+id,		        
		   		 }).then(function successCallback(data) {		 
					$scope.confirmModal = false;
					$scope.closeconfirmModal();
					$scope.getalldata();
		        }, function errorCallback(data) {
		        		console.log(data);
						toaster.pop({toasterId:11,type:"error",body:data.data});
				});
		}	
	}
    
	$scope.closeconfirmModal=function()
 	{
 		$scope.modalInstance1.dismiss('cancel');	
 	}

	$rootScope.getalldata=function()
	{
	var id=$rootScope.dboard.uid;
		console.log(id);
		$http({	
					method:'PUT',
					url:$rootScope.apiurl+'adminapi/getpayments/'+id,
					data:{pl:25,pn:1}
		}).then(function successCallback(data) {
		console.log(data);
		$scope.allinfo=data.data.allinfo;	
		}, function errorCallback(data) {
			console.log(data);
			toaster.pop({toasterId:11,type:"error",body:data.data});
		});
	}

$scope.saveinfo=function(info)
	{
	$scope.issaving=true;
	if(angular.isDefined(info.Id))
	{
		$http({	method:'PUT',
     		url:'admin/adminapi/editpayment/'+info.Id,	
     		data:info
     		}).then(function successCallback(data) {
				toaster.pop('wait', "", "Details successfully updated.");
				$scope.getalldata();
				$scope.issaving=false;
				$scope.closeinfoModal();
     		}, function errorCallback(data) {
     				console.log(data);
					$scope.issaving=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});
     		});
	}
	else
	{	
		$http({	method:'POST',
				url:'admin/adminapi/addpayment',		
				data:info
			}).then(function successCallback(data) {
				toaster.pop('wait', "", "Details successfully saved.");
				$scope.getalldata();
				$scope.issaving=false;
				$scope.closeinfoModal();
			}, function errorCallback(data) {
				console.log(data);
				$scope.issaving=false;
				toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 }   			
	
	};
  
///////////Print Order
$scope.printData=function()
{
	var divToPrint=document.getElementById("printTable");
    newWin= window.open("");
    newWin.document.write(divToPrint.outerHTML);
    newWin.print();
    newWin.close();
}



$scope.printDiv = function(divName) {
  var printContents = document.getElementById(divName).innerHTML;
  var popupWin = window.open('', '', 'width=800,height=600');
  popupWin.document.open();
  popupWin.document.write('<!DOCTYPE html><html><head>  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"><link href=\"css/bootstrap.min.css\" rel=\"stylesheet\"><style>@media print {.mybg{background-color:red;} }</style></head><body onload="window.print()">' + printContents + '</body></html>');
  popupWin.document.close();
  
   return true;
}   

});


App.controller('InventoryCntrl',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$q,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	
	console.log(alldata);
	$scope.allinvents=alldata.data;

		$scope.pageSize=25;
		$scope.currentPage=1;
		
		 $scope.dateOptions = {
    //customClass: getDayClass,
    //minDate: new Date(),
    showWeeks: true
  };
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
  
  $scope.popup3 = {
    opened: []
  };
   $scope.open3 = function(index) {
    $scope.popup3.opened[index] = true;
  };
  
  
  
  
  
  
  	$scope.viewinvoice=function(item)
	{
		
		$scope.quote=angular.copy(item);
		$scope.quote.QDate=new Date($scope.quote.QDate);
		$scope.quote.ValidDate=new Date($scope.quote.ValidDate);
		 $scope.vieworderinst = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		
   	   templateUrl: 'ordermanage',
     	 scope: $scope,
   	 });
  
	}
	
	
	
	$scope.closemModal=function()
 	{
 		$scope.vieworderinst.dismiss('cancel');
 	}
 

$scope.ivflags={};
  
$scope.addinvent=function(param)
	{
		
	 	if(param==='new')
		{
			//$scope.info={};		
			
$scope.info={};
$scope.info.InstallDate=new Date();
$scope.info.NextCalliDate=new Date();
   

		}
		else
		{		

			$scope.info=angular.copy(param);
			$scope.info.InstallDate=new Date(param.InstallDate);
			
			$scope.info.NextCalliDate=new Date(param.NextCalliDate);
			
				
		}	


	 $scope.invmodalInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'inventoryModal',
     	 scope: $scope,
   	 });
	 
	 
	}
	
	$scope.closeinvModal=function()
 	{
 		$scope.invmodalInstance.dismiss('cancel');
 	}
	
	
	
		
	
		   
	
	$scope.getallinvents=function()
	{
		var id=$rootScope.dboard.uid;
		console.log(id);
		$http({	
					method:'PUT',
					url:$rootScope.apiurl+'adminapi/inventories/'+id,
					data:{pl:25,pn:1}
	     	 
	     		}).then(function successCallback(data) {
					console.log(data);
						$scope.allinvents=data.data;
	
			     				
	     			}, function errorCallback(data) {
	     			console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
	     		});
	};
	
	
	$scope.saveinfo=function(info)
	{
		$scope.issaving=true;
	console.log(info);
	if(angular.isDefined(info.Id))
	{

		$http({	method:'PUT',
     		url:$rootScope.apiurl+'adminapi/updinvent/'+info.Id,	
     		data:info
     			}).then(function successCallback(data) {
					$scope.closeinvModal();
				$scope.getallinvents();
     			}, function errorCallback(data) {
     				console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
     		});
	}
	else
	{	

		$http({		method:'POST',
				url:$rootScope.apiurl+'adminapi/addinvent',	
				data:info
				}).then(function successCallback(data) {
			
				//console.log(data);
			//	$scope.categoryid=angular.fromJson(data);
				
				
				$scope.closeinvModal();
				$scope.getallinvents();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 }   			
	
	}
  
  
  $scope.deleteinvent=function(con,item)
  {
	  if(con==='confirm')
	{
		$scope.info=item;
		 $scope.delmodalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'delconfirmModal',
		scope:$scope,
         size: "md",
   
		});
	}
	else
	{
		console.log(item);
		$http({
					method:'DELETE',
					url: $rootScope.apiurl+'adminapi/delinvent/'+item.Id,
					data:item					
					}).then(function successCallback(data) {
				//console.log(data);
			 	//$scope.getpages();
				 $scope.delcancel();
				$scope.getallinvents();
			 	
					}, function errorCallback(data) {
					console.log(data);
		toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
	}
  }
  
  $scope.delcancel=function()
  {
	   $scope.delmodalInstance.dismiss('cancel');
  }
			
		
	
});



App.controller('quotesController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$q,configparam,$interval,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	
	var exists=$localstorage.getObject(configparam.AppName);
	console.log(alldata.data);
	$scope.allquotes=alldata.data.allquotes;
	
	$scope.qtypes=alldata.data.qtypes;
	$scope.taxlabel=alldata.data.TaxLabel;
	$scope.taxvalue=alldata.data.Tax;
	$scope.qvdays=alldata.data.QVDays;
	$scope.customers=alldata.data.customers;
		
	$scope.pageSize=25;
	$scope.currentPage=1;
	$scope.totalitems=alldata.data.totalitems;
	
	
	
		 $scope.dateOptions = {    
    minDate: new Date(),
    showWeeks: false
  };
	
	 $scope.popup2 = {    opened: []  };
  
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
  
  $scope.popup3 = {
    opened: []
  };
   $scope.open3 = function(index) {
    $scope.popup3.opened[index] = true;
  };
  
  
  
  $scope.getNumber = function(num) {
		return new Array(num);   
	}
	
////-----------View Quote--------------------///	
  
  	$scope.viewquote=function(item)
	{
		
		$scope.quote=angular.copy(item);
		
		 $scope.vieworderinst = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		
   	   templateUrl: 'templates/accounts/viewquote.html',
     	 scope: $scope,
   	 });
  
	}
	
	$scope.closemModal=function()
 	{
 		$scope.vieworderinst.dismiss('cancel');
 	}
 
 
	 $scope.searchdata=function(searchtext)
	 {
		 console.log(searchtext);
		 if(!_.isEmpty(searchtext))
		{
		$scope.flags.loadingdata=true;
		var pageno=1;
			//console.log(pageno);
			$http({	
						method:'POST',
						url:$rootScope.apiurl+'adminapi/searchquote/',	
						data:{text:searchtext,pageSize:$scope.pageSize}
				 
						}).then(function successCallback(data) {
						
						console.log(data);
					$scope.allquotes=data.data.allquotes;
			$scope.totalcount=0;//data.totalitems;
			$scope.flags.loadingdata=false;
									
						}, function errorCallback(data) {
						console.log(data);
						$scope.flags.loadingdata=false;
						toaster.pop({toasterId:11,type:"error",body:data.data});
					});
		}
	 }

	$scope.ivflags={};

		
	
	$scope.getallquotes=function(pageno)
	{
		var id=$rootScope.dboard.uid;
		// console.log(id);
		// console.log(pageno);
		$http({	
					method:'PUT',
					url:$rootScope.apiurl+'adminapi/getquotes/'+id,
					data:{pl:$scope.pageSize,pn:pageno}
	     	 
	     		}).then(function successCallback(data) {
					//console.log(data);
						$scope.allquotes=data.data.allquotes;
	$scope.qtypes=data.data.qtypes;
		$scope.customers=data.data.customers;
			     				
	     			}, function errorCallback(data) {
	     			console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
	     		});
	};
	
	
	
	$scope.mailflag={};
   $scope.mailflag.sending=false;
	
	$scope.closesendmailmodal=function()
	{
		 $scope.mailflag.sending=false;
		$scope.emailconfirmmodalInstance.dismiss('cancel');
	}
	
	
	$scope.sendmail=function(quote,param)
	{
		if(param==='confirm')
		{
			
			
			$scope.mailinfo=quote;
			$scope.mailinfo.MailTo=quote.Customer.Email;
			 $scope.emailconfirmmodalInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'templates/common/confirmmailmodal.html',
     	 scope: $scope,
   	 });
	 
		}
		else if(param==='send')
		{
			 $scope.mailflag.sending=true;
		$http({		method:'PUT',
				url:$rootScope.apiurl+'adminapi/sendquote/'+quote.Id,	
				data:quote
				}).then(function successCallback(data) {
			
				//console.log(data);
				 $scope.mailflag.sending=false;
				toaster.pop({ type: 'success', body: data.data.msg, toasterId: '11' });
				
				
				$scope.closesendmailmodal();
				}, function errorCallback(data) {
					console.log(data);
					 $scope.mailflag.sending=false;
					 	toaster.pop({toasterId:11,type:"error",body:data.data.msg});
		        	
			});
		}
	}
	
	
	$scope.closeverifymodal=function()
	{$scope.oneclick=false;
		$scope.verifyconfirmmodalInstance.dismiss('cancel');
	}
	$scope.oneclick=false;
	$scope.verifyquote=function(param,quote)
	{
		$scope.vquote=quote;
	//	$scope.vquote.Title="Verify Quotation";
		if(param==='confirm')
		{
			 $scope.verifyconfirmmodalInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'verifyModal',
     	 scope: $scope,
   	 });
	 
		}
		else if(param==='mail')
		{
			$scope.oneclick=true;
			$http({		method:'PUT',
				url:$rootScope.apiurl+'adminapi/sendverifyquote/'+quote.Id,	
				data:quote
				}).then(function successCallback(data) {
			
				console.log(data);
				$scope.closeverifymodal();
				toaster.pop({ type: 'info', body: data.data.msg });
				$scope.getallquotes(1);
				}, function errorCallback(data) {
					console.log(data);
					$scope.oneclick=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
		}
		else if(param==='verify')
		{
			$scope.oneclick=true;
			
			$http({
		    		 method:'POST',
		       		 url:$rootScope.apiurl+'adminapi/verifyquote',
					 data:{QNO:$scope.vquote.QNo,Status:"Verified"}
		        
		   		}).then(function successCallback(data) {
					 
					 console.log(data);
					$scope.closeverifymodal();
					$scope.getallquotes(1);
					$scope.oneclick=false;
					
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
						toaster.pop({toasterId:11,type:"error",body:data.data});
				});
		}

	}
	

	
	$scope.closeapprovemodal=function()
	{
		$scope.modalInstance3.dismiss('cancel');
	}
		
$scope.approvequote=function(con,log)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			
			$scope.log=log;
			
			$scope.modalInstance3 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'approveModal',
				scope:$scope,
				windowClass:'fade-scale',
     	
				});
		}
		else if(con=='approve')
		{
			$scope.oneclick=true;
			
			$http({
		    		 method:'PUT',
		       		 url:$rootScope.apiurl+'adminapi/approvequote/'+log.Id,
					 data:{}
		        
		   		}).then(function successCallback(data) {
					 
					 console.log(data);
					$scope.closeapprovemodal();
					$scope.getallquotes(1);
					$scope.oneclick=false;
					
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
						toaster.pop({toasterId:11,type:"error",body:data.data});
				});
		}
		
	}
//---	
	var stop=$interval(function() {
		$scope.getallquotes($scope.currentPage);
		console.log("get calllog"+$scope.currentPage);
	},55000);
	
	$scope.$on('$destroy',function(){
		//console.log(stop);
    if(stop)
        $interval.cancel(stop);   
});


});


App.controller('quoteAddCntrl',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$q,configparam,$interval,
$rootScope,$state,$stateParams,AuthenticationService,$timeout,SampleNames,TestPlans,Cart,alldata)
{
	
	var exists=$localstorage.getObject(configparam.AppName);
	console.log(alldata.data);
$scope.qflag={};
$scope.qflag.disabled=false;
$scope.qflag.stype=$stateParams.stype;
		 $scope.dateOptions = {
    //customClass: getDayClass,
    minDate: new Date(),
    showWeeks: false
  };
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
  
  $scope.popup3 = {
    opened: []
  };
   $scope.open3 = function(index) {
    $scope.popup3.opened[index] = true;
  };
  
  
  
  
  $scope.gettax=function(param)
  {if(param)
	  {
	  $scope.quote.Tax=$scope.taxvalue;
	  }
	  else{
		  $scope.quote.Tax=0;
	  }
	  $scope.updatetotal($scope.quote);
  }
  $scope.getNumber = function(num) {
    return new Array(num);   
}
  
  
 

$scope.removeapptest=function(item,model)
	{
		//$scope.rir.applicabletest.splice(idx,1);
		console.log(item);
		//var idx=_.indexOf($scope.rir.applicabletest,{TestId:item.TestId});
		$scope.qitem.applicabletest=_.without($scope.qitem.applicabletest, _.findWhere($scope.qitem.applicabletest,{TSeq:item.TSeq}));
	}
		
	
	$scope.minQuantity=1;
	$scope.maxQuantity=10;
	$scope.quoteflags={};
	
	
	
	
	$scope.additemrow=function(param,idx)
	{ 
		if(angular.isDefined($scope.quote.CustId) && $scope.quote.CustId !='' )
		{
		
			if(param==='new')
			{
				$scope.quoteflags.editflag=false;
				$scope.qitem={};	
				$scope.qitem.index='';	
				$scope.qitem.addtest=[];
					
			}
			else
			{
				$scope.quoteflags.editflag=true;
				$scope.getinddata2(param.IndId);
				$scope.qitem=param;
				$scope.qitem.index=idx;
				$scope.qitem.addtest=[];
				$scope.qitem.applicabletest=[];
				$scope.qitem.addtest.push(param.TestId);
				$scope.loadtoapplicable(param);
				
			}		
		
		if($stateParams.stype==='quote')
		{
			 $scope.quoteitemmodal = $uibModal.open({
			keyboard:false,
			  animation: true,
			  size:'lg',
			backdrop:"static",
		   templateUrl: 'quoteitemModal.html',
			 scope: $scope,
		 });
		}
	}
	else
	{
			toaster.pop({toasterId:11,type:"error",body:"Customer Required"});
	}
		
	}
	
	$scope.closeqitemModal=function(){
		$scope.qitem={};
		$scope.quoteitemmodal.dismiss('cancel');
	}
	
	
	
	$scope.additemrow2=function()
	{ 
	var item={	SampleName:"",SampleWeight:"",TAT:"",TestCondition:"",TestCondId:"",TSeq:"",
					TestId:"",
					SubStdId:"", 
					
					};
	
	$scope.quote.Details.push(item);
		
		
	}
	
	
	
	
	$scope.loadtoapplicable=function(par2)
	{
		console.log(par2);
			var test=_.findWhere($scope.alltests,{Id:par2.TestId});
		
		 var item={	
					TestCondId:par2.TestCondId,
					TestId:par2.TestId,TestName:test.TestName,LabIds:par2.LabIds,
					SubStdId:par2.SubStdId, 
					TestMethodId:par2.TestMethodId,
					PlanId:par2.PlanId,ExtraInfo:par2.ExtraInfo
					};
							
					
		$scope.qitem.applicabletest.push(item);
		
	}
	
	
	$scope.addtoapplicable=function(par1,par2)
	{
		console.log(par1);
		console.log(par2);
			var test=par1;//_.findWhere($scope.alltests,{Id:par2});
			
		console.log(test);
		 var item={	
		 Qty:1,
		 TSeq:par1.TSeq,
					TestCondId:1,IsStd:test.IsStd,IsTestMethod:test.IsTestMethod,
					IsPlan:test.IsPlan,
					TestId:par1.TestId,TestName:test.TestName,LabId:"",
					SubStdId:"", 
					TestMethodId:"",
					Price:"",ExtraInfo:""
					};
							
					
		$scope.qitem.applicabletest.push(item);
		
	}
	
	
		// $scope.getsubind=function(indid)
	// {
		// console.log(indid);		
		// var industry=_.findWhere($scope.allindustries,{Id:indid});
		// $scope.subindustries = industry.Children;
		// console.log(industry);
		// $scope.qitem.SubIndId="";
	// }
	
	$scope.getcatdata=function(indid)
	{
		$scope.qitem.IndId="";
		var subindustry=_.findWhere($scope.subindustries,{Id:indid});
		$scope.procategories=subindustry.Children;
		console.log($scope.procategories);
	}
	
	
		$scope.subindflag=false;
	$scope.proindflag=false;
	$scope.getsubind=function(indid)
	{
		$scope.alltests=[];
		$scope.qitem.addtest=null;
		$scope.qitem.applicabletest=[];
		console.log(indid);
		var industry=_.findWhere($scope.allindustries,{Id:indid});
		if(industry.Children.length>0)
		{
			$scope.subindflag=true;
			$scope.subindustries=industry.Children;
		}
		else
		{
			$scope.qitem.IndId=indid;
			
			$scope.getinddata(indid);
			$scope.subindflag=false;
			$scope.proindflag=false;
		}
		
		//console.log(industry);
	}
	
	$scope.getcatdata=function(indid)
	{
		var subindustry=_.findWhere($scope.subindustries,{Id:indid});
		
		if(subindustry.Children.length>0)
		{
			$scope.proindflag=true;
			$scope.procategories=subindustry.Children;
		console.log(subindustry);
		}
		else
		{
			$scope.qitem.IndId=indid;
			
			$scope.getinddata(indid);
			
			$scope.proindflag=false;
		}
		
	}
	
	
	$scope.getinddata2=function(ind)
			{
				$scope.qitem.applicabletest=[];
				 $http({
					method:'GET',
					url: $rootScope.apiurl+'api/ririnddata/'+ind,
									
					}).then(function successCallback(data) {
				console.log(data);
			
			 	$scope.standards=data.data.standards;
		$scope.substandards=data.data.substandards;
		$scope.alltests=data.data.alltests;	
		$scope.testmethods=data.data.testmethods;
		$scope.alllabs=data.data.alllabs;
		$scope.substdplans=data.data.substdplans;
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
			}
	
	$scope.getinddata=function(ind)
			{
				$scope.qitem.applicabletest=[];
				 $http({
					method:'GET',
					url: $rootScope.apiurl+'api/ririnddata/'+ind,
									
					}).then(function successCallback(data) {
				console.log(data);
				$scope.qitem.SubStdId="";
				$scope.qitem.TestId="";
				$scope.qitem.TestCondId="";
				$scope.qitem.LabIds=[];
				$scope.qitem.TestMethodId="";
			 	$scope.standards=data.data.standards;
		$scope.substandards=data.data.substandards;
		$scope.alltests=angular.copy(data.data.alltests);	
		
		
		$scope.testmethods=data.data.testmethods;
		$scope.alllabs=data.data.alllabs;
		$scope.substdplans=data.data.substdplans;
		
		
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
			}
	
	
	$scope.getsubstandards=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.substandards,{TestId:type});
		}
	}
	
	
	$scope.getsubplans=function(substdid)
	{
	//console.log(substdid);
if(angular.isDefined(substdid))
		{
				return _.where($scope.substdplans,{SubStdId:substdid});
		}
		
	}
	$scope.gettestmethods=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.testmethods,{TestId:type});
		}
	}
	
	$scope.showcustinfo=function(id)
  {
	  $scope.customer=_.findWhere($scope.customers,{Id:id});
	  
	  
  }
	
	 if(angular.isDefined(alldata.data))
 {
	 
	 
	 $scope.customers=alldata.data.customers;
					$scope.allindustries=alldata.data.industries;	 
					$scope.users=alldata.data.users;
					$scope.testconditions=alldata.data.testconditions;
			$scope.taxlabel=alldata.data.TaxLabel;
	$scope.taxvalue=alldata.data.Tax;
	$scope.qvdays=alldata.data.QVDays;	
$scope.receipttypes=alldata.data.receipttypes;	
$scope.drawntypes=alldata.data.drawntypes;	
					$scope.quote={};
$scope.quote.QDate=new Date();
$scope.quote.DueDate=new Date();
$scope.quote.QuoteNote=alldata.data.QuoteNote;
    var nextWeek = new Date($scope.quote.QDate.valueOf());

    // adding/subtracting 7 for next/previous week. Can be less for your use case
    nextWeek.setDate(nextWeek.getDate() + $scope.qvdays);

    // update the model value (if this is your approach)
    $scope.quote.ValidDate = nextWeek;
//$scope.invoice.DueDate=new Date(7);
$scope.quote.Details=[];		
$scope.quote.Total="";
$scope.quote.SubTotal	="";
$scope.quote.Discount=0;

if(alldata.data.IsTax==='1')
{
	$scope.quote.IsTax=alldata.data.IsTax;
	$scope.quote.Tax=alldata.data.Tax;
}
$scope.ivflags={};
$scope.issaving=false;
			$scope.ivflags.editflag=false;
			
	if(exists.roleid===11)
	 {
		 $scope.qflag.disabled=true;
		 var cust=_.findWhere( $scope.customers,{Email:exists.email});
		 console.log(cust);
		 $scope.quote.CustId=cust.Id;	
$scope.quote.IsTax=1;
	$scope.quote.Tax=alldata.data.Tax;		 
	 }
	 
	 if($stateParams.stype==='sample')
	 {
		 $scope.additemrow2();
	 }

 }

 
	
	$scope.qitemsave=function(qitem,idx)
	{
		console.log(qitem);
		var test=_.findWhere($scope.alltests,{TSeq:qitem.TSeq});		
		var price=test.Cost;
		
		var testcond=_.findWhere($scope.testconditions,{Id:qitem.TestCondId});		
		 price=price+testcond.Cost;		
		 
		var testmethod=_.findWhere($scope.testmethods,{Id:qitem.TestMethodId});
		
		var industry=_.findWhere($scope.allindustries,{Id:qitem.PIndId});
		if(qitem.IsStd)
		{
			var std=_.findWhere($scope.getsubstandards(qitem.TestId),{Id:qitem.SubStdId});		
			price=price+std.Cost;
		}
		 
		qitem.Labs=[];
		angular.forEach(qitem.LabIds,function(val){
			var lab=_.findWhere($scope.alllabs,{Id:val});
			 price=price+lab.Cost;
			 qitem.Labs.push(lab.Name);
		})
		var plan=_.findWhere($scope.substdplans,{Id:qitem.PlanId});
		 if(!_.isEmpty(plan))
		 {
			  price=price+plan.Cost;
		 }
		 var parameters=[];
		 if(qitem.IsStd)
		 {
				 parameters=_.isEmpty(plan)?std.Parameters:plan.VParameters; 
		 }
	
		var item={	SampleName:qitem.SampleName,SampleWeight:qitem.SampleWeight,TAT:qitem.TAT,
					TestCondition:testcond.Name,TestCondId:qitem.TestCondId,TSeq:qitem.TSeq,
					TestId:qitem.TestId,TestName:qitem.TestName,
					IsStd:qitem.IsStd,IsTestMethod:qitem.IsTestMethod,IsPlan:qitem.IsPlan,
					SubStdId:qitem.SubStdId, 
					StdName:qitem.IsStd?std.Name:null,PlanId:qitem.PlanId,
					Plan:_.isEmpty(plan)?null:plan.Name,PlanParameters:parameters,
					TestMethod:_.isEmpty(testmethod)?null:testmethod.Method,TestMethodId:qitem.TestMethodId,LabIds:qitem.LabIds,
					PIndId:qitem.PIndId,SubIndId:qitem.SubIndId,labnames:qitem.Labs,
					IndId:qitem.IndId,Industry:industry.PTree,Price:price,Qty:qitem.Qty 
					};
					
	
	
	if( idx === null || idx==='' )
				{
						$scope.quote.Details.push(item);
				}					
				else
				{
					$scope.quote.Details[idx ]=item;					
					
				}
				
		
		$scope.updatetotal($scope.quote);
	}
	
	
	$scope.qitemsaveall=function(qitem)
	{
		console.log(qitem);
		angular.forEach(qitem.applicabletest,function(val){
				val.PIndId=qitem.PIndId;
				val.SubIndId=qitem.SubIndId;
			val.IndId=qitem.IndId;
			val.SampleName=qitem.SampleName;
			val.SampleWeight=qitem.SampleWeight;
			val.TAT=qitem.TAT;
			$scope.qitemsave(val,qitem.index);
			
		})
		
		$timeout(function(){$scope.closeqitemModal();},500);
		//$scope.updatetotal($scope.quote);
	}
	
	
	$scope.resetitems=function(invtype)
	{
		$scope.quote.CustId="";
		$scope.quote.Details=[];
		 $scope.customer="";
		 
		 if(invtype==='C')
		 {
			$scope.quote.Tax=alldata.data.Tax; 
		 }
	}
	
	
	$scope.updatetotal=function(quote)
	{
		
		$scope.quote.SubTotal=	Cart.getsubtotal(quote);				
       $scope.quote.Total=	Cart.gettotal(quote,alldata.data.Tax);
	   
	   console.log($scope.quote);
	}
	

	
	$scope.delitem=function(param,idx)
	{
		if(angular.isDefined(param.Id))
		{
			$scope.quote.DelDetails.push(param);
		}
		
	
		$scope.quote.Details.splice(idx,1);
			$scope.updatetotal($scope.quote);
			
	}
	
	$scope.getallquotes=function(pageno)
	{
		$state.go('app.quotes');
	};
	
	
	$scope.quotesave=function(quote)
	{
		$scope.issaving=true;
			
		quote.CreatedBy=$rootScope.dboard.uid;
		$http({		method:'POST',
				url:$rootScope.apiurl+'adminapi/addquote',	
				data:quote
				}).then(function successCallback(data) {
			
				console.log(data);
			 toaster.pop({toasterId:11,type:"success",body:"Quotation Saved"});
				$scope.getallquotes(1);
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	}
  
  $scope.addaddress=function()
	{
		var address={Name:"",Email:"",ContactNo:"",ContactPerson:"",Address:""};
		
		$scope.customer.Addresses.push(address);
	}
	
	
	$scope.deladdress=function(item,idx)
	{
		//var address={Name:"",Email:"",ContactNo:"",ContactPerson:"",Address:""};
		if(angular.isDefined(item.Id))
		{
			$scope.customer.DelAddresses.push(item);
		}
		$scope.customer.Addresses.splice(idx,1);
	}
  
  $scope.addnewcust=function()
			{
				
				$rootScope.editflag=false;
				$rootScope.disablebtn=true;
				$scope.customer={};
				
				$scope.customermodalInstance = $uibModal.open({
					keyboard:false,
					backdrop:"static",
				   templateUrl: 'templates/users/customeradd.html',
						scope:$scope,
					
				 });
			}
			
			$scope.custcancel = function () {
			
			
				$scope.issaving=false;
				$rootScope.disablebtn=false;
				$scope.customermodalInstance.dismiss('cancel');
		
			};
			
			
 $scope.savecustomer=function(customer)
	{
			$scope.issaving=true;
	
				customer.CreatedBy=$rootScope.dboard.uid;

		$http({		method:'POST',
				url:$rootScope.apiurl+'settingapi/customeradd',	
				data:customer
				}).then(function successCallback(data) {
			
				console.log(data);
				
				$scope.customers=data.data.customers;
				$scope.customer=_.findWhere($scope.customers,{Id:angular.fromJson(data.data.CustomerId)});
				$scope.quote.CustId=angular.fromJson(data.data.CustomerId);
			//	$scope.categoryid=angular.fromJson(data);
			// $scope.showcustinfo($scope.quote.CustId);
			
				toaster.pop({toasterId:11,type:"success",body:"Customer Added Successfully."});
				
				$scope.custcancel();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 			
	
	};
	
	
	$scope.mflags={};
	$scope.mflags.oneclick=[];
	
		$scope.samplenames=[];
	          SampleNames.fetchSamples().then(function(data){		
			$scope.samplenames=data;
		});
     
       

//----PLan Section ----//

	$scope.closesubstdplanModal=function()
  {
	   $scope.substdplanInstance.dismiss('cancel');
  }
  
  $scope.addsubstdplan=function(substdid)
  {
	  

	   $scope.plan={};
	    $scope.plan.SubStdId=substdid;
	   $scope.plan.Parameters=[];
	   $scope.plan.DelParameters=[];
	  $http({
					method:'PUT',
					url: $rootScope.apiurl+'adminapi/substdparams/'+substdid,
					data:{}				
					}).then(function successCallback(data) {
				console.log(data);
			
			 
			 $scope.allparameters=data.data.Parameters;
			if(data.data.Parameters.length>0)
			{
			  $scope.substdplanInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'templates/settings/substdplanaddModal.html',
     	 scope: $scope,
   	 });
			}
			 
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
	 
  }
  
  $scope.removeparameters=function($item,$model)
  {
	  // console.log($item);
	  // console.log($model);
	 // angular.isDefined()
	  $scope.plan.DelParameters.push($model);
	  
	
  }
  
  $scope.editsubstdplan=function(planid)
  {
	  console.log(planid);
  $scope.plan=_.findWhere($scope.substdplans,{Id:planid});
  $scope.plan.Parameters=[];
  $scope.plan.DelParameters=[];
  angular.forEach($scope.plan.VParameters,function(val){
	  
	 $scope.plan.Parameters.push(val.Id);
  })
  
  if(!_.isEmpty($scope.plan))
  {
    $http({
					method:'PUT',
					url: $rootScope.apiurl+'adminapi/substdparams/'+$scope.plan.SubStdId,
					data:{}				
					}).then(function successCallback(data) {
				console.log(data);
			
			 
			 $scope.allparameters=data.data.Parameters;
			
			  $scope.substdplanInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'templates/settings/substdplanaddModal.html',
     	 scope: $scope,
   	 });
  
  }, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
  }
  
	 
  }
  
  
  
  $scope.plansave=function(plan)
  {
	  
	  if(angular.isDefined(plan.Id))
	  {
		    $http({		method:'PUT',
				url:$rootScope.apiurl+'adminapi/updatetestplan/'+plan.Id,	
				data:plan
				}).then(function successCallback(data) {
			
				//console.log(data);
				$scope.substdplans=data.data.substdplans;
				toaster.pop({toasterId:11,type:"success",body:"Successfully Updated"});
				$scope.closesubstdplanModal();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
		  
	  }
	  else
	  {
		   $http({		method:'POST',
				url:$rootScope.apiurl+'adminapi/addtestplan',	
				data:plan
				}).then(function successCallback(data) {
			
				//console.log(data);
				$scope.substdplans=data.data.substdplans;
				toaster.pop({toasterId:11,type:"success",body:"Successfully Added"});
				$scope.closesubstdplanModal();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			}); 
		  
	  }
	
  }
	

});


App.controller('quoteEditCntrl',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$q,configparam,$interval,
$rootScope,$state,AuthenticationService,$timeout,SampleNames,Cart,alldata)
{
	
	var exists=$localstorage.getObject(configparam.AppName);
	
		 $scope.dateOptions = {
    //customClass: getDayClass,
    minDate: new Date(),
    showWeeks: false
  };
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
  
  $scope.popup3 = {
    opened: []
  };
   $scope.open3 = function(index) {
    $scope.popup3.opened[index] = true;
  };
  
  
  $scope.showcustinfo=function(id)
  {
	  $scope.customer=_.findWhere($scope.customers,{Id:id});
  }
  
  $scope.gettax=function(param)
  {if(param)
	  {
	  $scope.quote.Tax=$scope.taxvalue;
	  }
	  else{
		  $scope.quote.Tax=0;
	  }
	  $scope.updatetotal($scope.quote);
  }
  $scope.getNumber = function(num) {
    return new Array(num);   
}
  
  
 
 if(angular.isDefined(alldata.data))
 {
	 console.log(alldata);
	 $scope.customers=alldata.data.customers;
					$scope.allindustries=alldata.data.industries;	 
					$scope.users=alldata.data.users;
					$scope.testconditions=alldata.data.testconditions;
			$scope.taxlabel=alldata.data.TaxLabel;
	$scope.taxvalue=alldata.data.Tax;
	$scope.qvdays=alldata.data.QVDays;
$scope.drawntypes=alldata.data.drawntypes;	
$scope.receipttypes=alldata.data.receipttypes;	
					$scope.quote=alldata.data.quote;
$scope.quote.QDate=new Date($scope.quote.QDate);
			$scope.quote.ValidDate=new Date($scope.quote.ValidDate);
    if($scope.quote.Tax>0)
				{
					$scope.quote.IsTax='1';
					
				}


if(alldata.data.IsTax==='1')
{
	$scope.quote.IsTax=alldata.data.IsTax;
	$scope.quote.Tax=alldata.data.Tax;
}
$scope.ivflags={};
$scope.issaving=false;
			$scope.ivflags.editflag=false;

 }
$scope.quote.DelDetails=[];
			 $scope.showcustinfo($scope.quote.CustId);
			// $scope.updatetotal($scope.quote);
 
	
	$scope.minQuantity=1;
	$scope.maxQuantity=10;
	
	$scope.quoteflags={};
	
	$scope.removeapptest=function(item,model)
	{
		//$scope.rir.applicabletest.splice(idx,1);
		console.log(item);
		//var idx=_.indexOf($scope.rir.applicabletest,{TestId:item.TestId});
		$scope.qitem.applicabletest=_.without($scope.qitem.applicabletest, _.findWhere($scope.qitem.applicabletest,{TSeq:item.TSeq}));
	}
	
	$scope.additemrow=function(param,idx)
	{
		console.log(idx);
		
		console.log(param);
		if(param==='new')
		{
			$scope.qitem={};	
			$scope.qitem.index=null;	
				$scope.qitem.addtest=[];
				$scope.quoteflags.editflag=false;
		}
		else
		{
			
			$scope.qitem=param;
			$scope.qitem.index=idx;
			$scope.getinddata2(param);
			$scope.qitem=param;
			//$scope.getsubind(param.PIndId);
			//$scope.getcatdata(param.SubIndId);
			
			//$scope.qitem.IndId= $scope.customer.IndId;
				
			//
			
			$scope.quoteflags.editflag=true;
			 var item={	
			 Id:param.Id,
			 Qty:param.Qty,
					TestCondId:param.TestCondId,
					TestId:param.TestId,TestName:param.TestName,LabIds:param.LabIds,
					SubStdId:param.SubStdId, PlanId:param.PlanId,
					TestMethodId:param.TestMethodId,
					Price:"",ExtraInfo:""
					};
			$scope.qitem.applicabletest.push(item);
			
		}		
		
		 $scope.quoteitemmodal = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'quoteitemModal.html',
     	 scope: $scope,
   	 });
		
	}
	
	$scope.closeqitemModal=function(){
		$scope.qitem={};
		$scope.quoteitemmodal.dismiss('cancel');
	}
	
	$scope.addtoapplicable=function(par1,par2)
	{
		console.log(par1);
		console.log(par2);
			var test=_.findWhere($scope.alltests,{Id:par2});
			
		console.log(test);
		 var item={	
					TestCondId:3,
					TestId:par2,TestName:test.TestName,LabId:"",
					SubStdId:"", 
					TestMethodId:"",
					Price:"",ExtraInfo:""
					};
							
					
		$scope.qitem.applicabletest.push(item);
		
	}
	
	
		$scope.getsubind=function(indid)
	{
		console.log(indid);
		
		var industry=_.findWhere($scope.industries,{Id:indid});
		$scope.subindustries=industry.Children;
		console.log(industry);
		//$scope.qitem.SubIndId="";
	}
	
	$scope.getcatdata=function(indid)
	{
		console.log(indid);
		//$scope.qitem.IndId="";
		var subindustry=_.findWhere($scope.subindustries,{Id:indid});
		$scope.procategories=subindustry.Children;
		console.log(subindustry);
	}
	$scope.getinddata2=function(param)
			{
				$scope.qitem.applicabletest=[];
				 $http({
					method:'GET',
					url: $rootScope.apiurl+'api/ririnddata/'+param.IndId,
									
					}).then(function successCallback(data) {
				console.log(data);
			
			 	$scope.standards=data.data.standards;
		$scope.substandards=data.data.substandards;
		$scope.alltests=data.data.alltests;	
		$scope.testmethods=data.data.testmethods;
		$scope.alllabs=data.data.alllabs;
			$scope.substdplans=data.data.substdplans;
			
			$scope.getsubplans(param.SubStdId);
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
			}
	
	$scope.getinddata=function(ind)
			{
				$scope.qitem.applicabletest=[];
				 $http({
					method:'GET',
					url: $rootScope.apiurl+'api/ririnddata/'+ind,
									
					}).then(function successCallback(data) {
				console.log(data);
				$scope.qitem.SubStdId="";
				$scope.qitem.TestId="";
				$scope.qitem.TestCondId="";
				$scope.qitem.LabIds=[];
				$scope.qitem.TestMethodId="";
			 	$scope.standards=data.data.standards;
		$scope.substandards=data.data.substandards;
		$scope.alltests=data.data.alltests;	
		$scope.testmethods=data.data.testmethods;
		$scope.alllabs=data.data.alllabs;
		$scope.substdplans=data.data.substdplans;
		
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
			}
	
	
	$scope.getsubstandards=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.substandards,{TestId:type});
		}
	}
	$scope.getsubplans=function(substdid)
	{
	//console.log(substdid);
if(angular.isDefined(substdid))
		{
				return _.where($scope.substdplans,{SubStdId:substdid});
		}
		
	}
	$scope.gettestmethods=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.testmethods,{TestId:type});
		}
	}
	
	
	
	$scope.qitemsave=function(qitem,idx)
	{
		
		
		console.log(qitem);
			console.log(idx);	
		
		var test=_.findWhere($scope.alltests,{TestId:qitem.TestId});		
			var price=test.Cost;		
		var testcond=_.findWhere($scope.testconditions,{Id:qitem.TestCondId});		
		 price=price+testcond.Cost;		
		var testmethod=_.findWhere($scope.testmethods,{Id:qitem.TestMethodId});
		var industry=_.findWhere($scope.allindustries,{Id:qitem.IndId});
		var std=_.findWhere($scope.getsubstandards(qitem.TestId),{Id:qitem.SubStdId});
		price=price+std.Cost;
		qitem.Labs=[];
		
		angular.forEach(qitem.LabIds,function(val){
			var lab=_.findWhere($scope.alllabs,{Id:val});
			 price=price+lab.Cost;
			 qitem.Labs.push(lab.Name);
		})
		
		
		var plan=_.findWhere($scope.substdplans,{Id:qitem.PlanId});
		 if(!_.isEmpty(plan))
		 {
			  price=price+plan.Cost;
		 }
		
		var item={	Id:qitem.Id,	SampleName:qitem.SampleName,SampleWeight:qitem.SampleWeight,TAT:qitem.TAT,
					TestCondition:testcond.Name,TestCondId:qitem.TestCondId,
					TestId:qitem.TestId,TestName:test.TestName,
					SubStdId:qitem.SubStdId, StdName:std.Name,
					PlanId:qitem.PlanId,
					Plan:_.isEmpty(plan)?null:plan.Name,PlanParameters:_.isEmpty(plan)?null:plan.VParameters,
					TestMethod:_.isEmpty(testmethod)?null:testmethod.Method,TestMethodId:qitem.TestMethodId,LabIds:qitem.LabIds,
					PIndId:qitem.PIndId,SubIndId:qitem.SubIndId,sds:std.sds,labnames:qitem.Labs,
					IndId:qitem.IndId,Industry:industry.Name,Price:price,Qty:qitem.Qty
					};
					console.log(item);
					
				if( idx === null )
				{
						$scope.quote.Details.push(item);
				}					
				else
				{
					$scope.quote.Details[idx ]=item;					
					
				}
		
		
		



			
		
		
		$scope.updatetotal($scope.quote);
	}
	
	
	$scope.qitemsaveall=function(qitem)
	{
		console.log(qitem);
		angular.forEach(qitem.applicabletest,function(val){
				val.PIndId=qitem.PIndId;
				val.SubIndId=qitem.SubIndId;
			val.IndId=qitem.IndId;
			val.SampleName=qitem.SampleName;
			val.SampleWeight=qitem.SampleWeight;
			val.TAT=qitem.TAT;
			val.Id=qitem.Id;
			$scope.qitemsave(val,qitem.index);
			
		})
		
		$timeout(function(){$scope.closeqitemModal();},500);
		//$scope.updatetotal($scope.quote);
	}
	$scope.showsds=function(qitem)
	{
		
			var std=_.findWhere($scope.getsubstandards(qitem.TestId),{Id:qitem.SubStdId});
			$scope.qitem.sds=std.sds;
		
	}
	

	$scope.resetitems=function(invtype)
	{
		$scope.quote.CustId="";
		$scope.quote.Details=[];
		 $scope.customer="";
		 
		 if(invtype==='C')
		 {
			$scope.quote.Tax=alldata.data.Tax; 
		 }
	}
	
	
	$scope.updatetotal=function(quote)
	{
		
		$scope.quote.SubTotal=	Cart.getsubtotal(quote);				
       $scope.quote.Total=	Cart.gettotal(quote,alldata.data.Tax);
	   
	}
	

	
	$scope.delitem=function(param,idx)
	{
		if(angular.isDefined(param.Id))
		{
			$scope.quote.DelDetails.push(param);
		}
		
	
		$scope.quote.Details.splice(idx,1);
			$scope.updatetotal($scope.quote);
			
	}
	
	$scope.getallquotes=function(pageno)
	{
		$state.go('app.quotes');
	};
	
	
	$scope.quotesave=function(quote)
	{
		$scope.issaving=true;
	console.log(quote);
	if(angular.isDefined(quote.Id))
	{


quote.CreatedBy=$rootScope.dboard.uid;
		$http({	method:'PUT',
     		url:$rootScope.apiurl+'adminapi/updquote/'+quote.Id,	
     		data:quote
     			}).then(function successCallback(data) {
				
				$scope.getallquotes(1);
		
     			}, function errorCallback(data) {
     				console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
     		});
	}
	else
	{	
		console.log("error");
	 }   			
	
	}
  
  $scope.addaddress=function()
	{
		var address={Name:"",Email:"",ContactNo:"",ContactPerson:"",Address:""};
		
		$scope.customer.Addresses.push(address);
	}
	
	
	$scope.deladdress=function(item,idx)
	{
		//var address={Name:"",Email:"",ContactNo:"",ContactPerson:"",Address:""};
		if(angular.isDefined(item.Id))
		{
			$scope.customer.DelAddresses.push(item);
		}
		$scope.customer.Addresses.splice(idx,1);
	}
  
  $scope.addnewcust=function()
			{
				
			$rootScope.editflag=false;
			$rootScope.disablebtn=true;
			$scope.customer={}				
		
				


	$scope.customermodalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
   	   templateUrl: 'templates/users/customeradd.html',
     		scope:$scope,
     	
   	 });
			}
			
			$scope.custcancel = function () {
			
			$scope.customer={}	
			$scope.issaving=false;
			$rootScope.disablebtn=false;
    	$scope.customermodalInstance.dismiss('cancel');
		
 	 };
 $scope.savecustomer=function(customer)
	{
	$scope.issaving=true;
	
	customer.CreatedBy=$rootScope.dboard.uid;

		$http({		method:'POST',
				url:$rootScope.apiurl+'settingapi/customeradd',	
				data:customer
				}).then(function successCallback(data) {
			
				//console.log(data);
				$scope.quote.CustId=data.data.CustomerId;
				$scope.customers=data.data.customers;
			//	$scope.categoryid=angular.fromJson(data);
			 $scope.showcustinfo($scope.quote.CustId);
				toaster.pop('wait', "", "Customer Detail successfully saved.");
				
				$scope.custcancel();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 			
	
	};
	
	
	$scope.mflags={};
	$scope.mflags.oneclick=[];
	
	
		$scope.samplenames=[];
	          SampleNames.fetchSamples().then(function(data){		
			$scope.samplenames=data;
		});
     
	
//----PLan Section ----//

	$scope.closesubstdplanModal=function()
  {
	   $scope.substdplanInstance.dismiss('cancel');
  }
  
  $scope.addsubstdplan=function(substdid)
  {
	  

	   $scope.plan={};
	    $scope.plan.SubStdId=substdid;
	   $scope.plan.Parameters=[];
	   $scope.plan.DelParameters=[];
	  $http({
					method:'PUT',
					url: $rootScope.apiurl+'adminapi/substdparams/'+substdid,
					data:{}				
					}).then(function successCallback(data) {
				console.log(data);
			
			 
			 $scope.allparameters=data.data.Parameters;
			
			  $scope.substdplanInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'templates/settings/substdplanaddModal.html',
     	 scope: $scope,
   	 });
	 
			 
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
	 
  }
  
  $scope.removeparameters=function($item,$model)
  {
	  // console.log($item);
	  // console.log($model);
	 // angular.isDefined()
	  $scope.plan.DelParameters.push($model);
	  
	
  }
  
  $scope.editsubstdplan=function(planid)
  {
	  console.log(planid);
  $scope.plan=_.findWhere($scope.substdplans,{Id:planid});
    console.log( $scope.plan);
  $scope.plan.Parameters=[];
  $scope.plan.DelParameters=[];
  angular.forEach($scope.plan.VParameters,function(val){
	  
	 $scope.plan.Parameters.push(val.Id);
  })
  
  if(!_.isEmpty($scope.plan))
  {
    $http({
					method:'PUT',
					url: $rootScope.apiurl+'adminapi/substdparams/'+$scope.plan.SubStdId,
					data:{}				
					}).then(function successCallback(data) {
				console.log(data);
			$scope.allparameters=[];
			 
			 $scope.allparameters=data.data.Parameters;
			
			  $scope.substdplanInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'templates/settings/substdplanaddModal.html',
     	 scope: $scope,
   	 });
  
  }, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
  }
  
	 
  }
  
  
  
  $scope.plansave=function(plan)
  {
	  
	  if(angular.isDefined(plan.Id))
	  {
		    $http({		method:'PUT',
				url:$rootScope.apiurl+'adminapi/updatetestplan/'+plan.Id,	
				data:plan
				}).then(function successCallback(data) {
			
				//console.log(data);
				$scope.substdplans=data.data.substdplans;
				
				$scope.closesubstdplanModal();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
		  
	  }
	  else
	  {
		   $http({		method:'POST',
				url:$rootScope.apiurl+'adminapi/addtestplan',	
				data:plan
				}).then(function successCallback(data) {
			
				//console.log(data);
				$scope.substdplans=data.data.substdplans;
				
				$scope.closesubstdplanModal();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			}); 
		  
	  }
	
  }
	

	
});


App.controller('viewquotesCntrl', function($scope,$http, configparam,$rootScope,
$state,$localstorage,toaster,$timeout,$uibModal,$document,$sce,alldata) {
	
 console.log(alldata);
$scope.quote=alldata.data;
	
})



App.controller('custquoteAddCntrl',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$q,configparam,$interval,
$rootScope,$state,AuthenticationService,$timeout,SampleNames,TestPlans,Cart,alldata)
{
	
	var exists=$localstorage.getObject(configparam.AppName);
	console.log(alldata.data);
$scope.qflag={};
$scope.qflag.disabled=false;
		 $scope.dateOptions = {
    //customClass: getDayClass,
    minDate: new Date(),
    showWeeks: false
  };
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
  
  $scope.popup3 = {
    opened: []
  };
   $scope.open3 = function(index) {
    $scope.popup3.opened[index] = true;
  };
  
  
  $scope.showcustinfo=function(id)
  {
	  $scope.customer=_.findWhere($scope.customers,{Id:id});
  }
  
  $scope.gettax=function(param)
  {if(param)
	  {
	  $scope.quote.Tax=$scope.taxvalue;
	  }
	  else{
		  $scope.quote.Tax=0;
	  }
	  $scope.updatetotal($scope.quote);
  }
  $scope.getNumber = function(num) {
    return new Array(num);   
}
  
  
 
 if(angular.isDefined(alldata.data))
 {
	 
	 
	 $scope.customers=alldata.data.customers;
					$scope.allindustries=alldata.data.industries;	 
					$scope.users=alldata.data.users;
					$scope.testconditions=alldata.data.testconditions;
			$scope.taxlabel=alldata.data.TaxLabel;
	$scope.taxvalue=alldata.data.Tax;
	$scope.qvdays=alldata.data.QVDays;	
$scope.receipttypes=alldata.data.receipttypes;	
$scope.drawntypes=alldata.data.drawntypes;	
					$scope.quote={};
$scope.quote.QDate=new Date();
$scope.quote.DueDate=new Date();
    var nextWeek = new Date($scope.quote.QDate.valueOf());

    // adding/subtracting 7 for next/previous week. Can be less for your use case
    nextWeek.setDate(nextWeek.getDate() + $scope.qvdays);

    // update the model value (if this is your approach)
    $scope.quote.ValidDate = nextWeek;
//$scope.invoice.DueDate=new Date(7);
$scope.quote.Details=[];		
$scope.quote.Total="";
$scope.quote.SubTotal	="";
$scope.quote.Discount=0;

if(alldata.data.IsTax==='1')
{
	$scope.quote.IsTax=alldata.data.IsTax;
	$scope.quote.Tax=alldata.data.Tax;
}
$scope.ivflags={};
$scope.issaving=false;
			$scope.ivflags.editflag=false;
			
	if(exists.roleid===11)
	 {
		 $scope.qflag.disabled=true;
		 var cust=_.findWhere( $scope.customers,{Email:exists.email});
		 console.log(cust);
		 $scope.quote.CustId=cust.Id;	
$scope.quote.IsTax=1;
	$scope.quote.Tax=alldata.data.Tax;		 
	 }

 }

 
$scope.removeapptest=function(item,model)
	{
		//$scope.rir.applicabletest.splice(idx,1);
		console.log(item);
		//var idx=_.indexOf($scope.rir.applicabletest,{TestId:item.TestId});
		$scope.qitem.applicabletest=_.without($scope.qitem.applicabletest, _.findWhere($scope.qitem.applicabletest,{TSeq:item.TSeq}));
	}
		
	
	$scope.minQuantity=1;
	$scope.maxQuantity=10;
	$scope.quoteflags={};
	$scope.additemrow=function(param,idx)
	{ 
	if(angular.isDefined($scope.quote.CustId) && $scope.quote.CustId !='' )
	{
		console.log(idx);
		console.log($scope.alllabs);
		console.log(param);
		console.log($scope.customer);
		if(param==='new')
		{$scope.quoteflags.editflag=false;
			$scope.qitem={};	
			$scope.qitem.index='';	
				$scope.qitem.addtest=[];
				//$scope.qitem.IndId= $scope.customer.IndId;
				//$scope.getinddata($scope.qitem.IndId);
		}
		else
		{
			$scope.quoteflags.editflag=true;
			$scope.getinddata2(param.IndId);
			$scope.qitem=param;
			$scope.qitem.index=idx;
			$scope.qitem.addtest=[];
			$scope.qitem.applicabletest=[];
			$scope.qitem.addtest.push(param.TestId);
			$scope.loadtoapplicable(param);
			//$scope.getsubind(param.PIndId);[]
			//$scope.getcatdata(param.SubIndId);
		}		
		
		 $scope.quoteitemmodal = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'quoteitemModal.html',
     	 scope: $scope,
   	 });
	}
	else
	{
			toaster.pop({toasterId:11,type:"error",body:"Customer Required"});
	}
		
	}
	
	$scope.closeqitemModal=function(){
		$scope.qitem={};
		$scope.quoteitemmodal.dismiss('cancel');
	}
	
	$scope.loadtoapplicable=function(par2)
	{
		console.log(par2);
			var test=_.findWhere($scope.alltests,{Id:par2.TestId});
		
		 var item={	
					TestCondId:par2.TestCondId,
					TestId:par2.TestId,TestName:test.TestName,LabIds:par2.LabIds,
					SubStdId:par2.SubStdId, 
					TestMethodId:par2.TestMethodId,
					PlanId:par2.PlanId,ExtraInfo:par2.ExtraInfo
					};
							
					
		$scope.qitem.applicabletest.push(item);
		
	}
	
	
	$scope.addtoapplicable=function(par1,par2)
	{
		console.log(par1);
		console.log(par2);
			var test=par1;//_.findWhere($scope.alltests,{Id:par2});
			
		console.log(test);
		 var item={	
		 Qty:1,
		 TSeq:par1.TSeq,
					TestCondId:1,
					TestId:par1.TestId,TestName:test.TestName,LabId:"",
					SubStdId:"", 
					TestMethodId:"",
					Price:"",ExtraInfo:""
					};
							
					
		$scope.qitem.applicabletest.push(item);
		
	}
	
	
		$scope.getsubind=function(indid)
	{
		console.log(indid);		
		var industry=_.findWhere($scope.allindustries,{Id:indid});
		$scope.subindustries = industry.Children;
		console.log(industry);
		$scope.qitem.SubIndId="";
	}
	
	$scope.getcatdata=function(indid)
	{
		$scope.qitem.IndId="";
		var subindustry=_.findWhere($scope.subindustries,{Id:indid});
		$scope.procategories=subindustry.Children;
		console.log($scope.procategories);
	}
	
	$scope.getinddata2=function(ind)
			{
				$scope.qitem.applicabletest=[];
				 $http({
					method:'GET',
					url: $rootScope.apiurl+'api/ririnddata/'+ind,
									
					}).then(function successCallback(data) {
				console.log(data);
			
			 	$scope.standards=data.data.standards;
		$scope.substandards=data.data.substandards;
		$scope.alltests=data.data.alltests;	
		$scope.testmethods=data.data.testmethods;
		$scope.alllabs=data.data.alllabs;
		$scope.substdplans=data.data.substdplans;
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
			}
	
	$scope.getinddata=function(ind)
			{
				$scope.qitem.applicabletest=[];
				 $http({
					method:'GET',
					url: $rootScope.apiurl+'api/ririnddata/'+ind,
									
					}).then(function successCallback(data) {
				console.log(data);
				$scope.qitem.SubStdId="";
				$scope.qitem.TestId="";
				$scope.qitem.TestCondId="";
				$scope.qitem.LabIds=[];
				$scope.qitem.TestMethodId="";
			 	$scope.standards=data.data.standards;
		$scope.substandards=data.data.substandards;
		$scope.alltests=angular.copy(data.data.alltests);	
		
		
		
		
		$scope.testmethods=data.data.testmethods;
		$scope.alllabs=data.data.alllabs;
		$scope.substdplans=data.data.substdplans;
		
		// forEach($scope.quote.Details,function(val){
			
			// $scope.alltests=_.without($scope.alltests, _.findWhere($scope.alltests, {TSeq: val.TSeq}));
		// });
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
			}
	
	
	$scope.getsubstandards=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.substandards,{TestId:type});
		}
	}
	
	
	$scope.getsubplans=function(substdid)
	{
	//console.log(substdid);
if(angular.isDefined(substdid))
		{
				return _.where($scope.substdplans,{SubStdId:substdid});
		}
		
	}
	$scope.gettestmethods=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.testmethods,{TestId:type});
		}
	}
	
	
	
	$scope.qitemsave=function(qitem,idx)
	{
		console.log(qitem);
		var test=_.findWhere($scope.alltests,{TSeq:qitem.TSeq});		
		var price=test.Cost;		
		var testcond=_.findWhere($scope.testconditions,{Id:qitem.TestCondId});		
		 price=price+testcond.Cost;		 
		var testmethod=_.findWhere($scope.testmethods,{Id:qitem.TestMethodId});
		var industry=_.findWhere($scope.allindustries,{Id:qitem.PIndId});
		var std=_.findWhere($scope.getsubstandards(qitem.TestId),{Id:qitem.SubStdId});		
		 price=price+std.Cost;
		qitem.Labs=[];
		angular.forEach(qitem.LabIds,function(val){
			var lab=_.findWhere($scope.alllabs,{Id:val});
			 price=price+lab.Cost;
			 qitem.Labs.push(lab.Name);
		})
		var plan=_.findWhere($scope.substdplans,{Id:qitem.PlanId});
		 if(!_.isEmpty(plan))
		 {
			  price=price+plan.Cost;
		 }
		
		var item={	SampleName:qitem.SampleName,SampleWeight:qitem.SampleWeight,TAT:qitem.TAT,
					TestCondition:testcond.Name,TestCondId:qitem.TestCondId,TSeq:qitem.TSeq,
					TestId:qitem.TestId,TestName:qitem.TestName,
					SubStdId:qitem.SubStdId, StdName:std.Name,PlanId:qitem.PlanId,
					Plan:_.isEmpty(plan)?null:plan.Name,PlanParameters:_.isEmpty(plan)?std.Parameters:plan.VParameters,
					TestMethod:_.isEmpty(testmethod)?null:testmethod.Method,TestMethodId:qitem.TestMethodId,LabIds:qitem.LabIds,
					PIndId:qitem.PIndId,SubIndId:qitem.SubIndId,sds:std.sds,labnames:qitem.Labs,
					IndId:qitem.IndId,Industry:industry.PTree,Price:price,Qty:qitem.Qty 
					};
					
	
	
	if( idx === null || idx==='' )
				{
						$scope.quote.Details.push(item);
				}					
				else
				{
					$scope.quote.Details[idx ]=item;					
					
				}
				
		
		$scope.updatetotal($scope.quote);
	}
	
	
	$scope.qitemsaveall=function(qitem)
	{
		console.log(qitem);
		angular.forEach(qitem.applicabletest,function(val){
				val.PIndId=qitem.PIndId;
				val.SubIndId=qitem.SubIndId;
			val.IndId=qitem.IndId;
			val.SampleName=qitem.SampleName;
			val.SampleWeight=qitem.SampleWeight;
			val.TAT=qitem.TAT;
			$scope.qitemsave(val,qitem.index);
			
		})
		
		$timeout(function(){$scope.closeqitemModal();},500);
		//$scope.updatetotal($scope.quote);
	}
	
	
	$scope.resetitems=function(invtype)
	{
		$scope.quote.CustId="";
		$scope.quote.Details=[];
		 $scope.customer="";
		 
		 if(invtype==='C')
		 {
			$scope.quote.Tax=alldata.data.Tax; 
		 }
	}
	
	
	$scope.updatetotal=function(quote)
	{
		
		$scope.quote.SubTotal=	Cart.getsubtotal(quote);				
       $scope.quote.Total=	Cart.gettotal(quote,alldata.data.Tax);
	   
	   console.log($scope.quote);
	}
	

	
	$scope.delitem=function(param,idx)
	{
		if(angular.isDefined(param.Id))
		{
			$scope.quote.DelDetails.push(param);
		}
		
	
		$scope.quote.Details.splice(idx,1);
			$scope.updatetotal($scope.quote);
			
	}
	
	$scope.getallquotes=function(pageno)
	{
		$state.go('app.quotes');
	};
	
	
	$scope.quotesave=function(quote)
	{
		$scope.issaving=true;
			
		quote.CreatedBy=$rootScope.dboard.uid;
		$http({		method:'POST',
				url:$rootScope.apiurl+'adminapi/addquote',	
				data:quote
				}).then(function successCallback(data) {
			
				console.log(data);
			 toaster.pop({toasterId:11,type:"success",body:"Quotation Saved"});
				$scope.getallquotes(1);
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	}
  
  $scope.addaddress=function()
	{
		var address={Name:"",Email:"",ContactNo:"",ContactPerson:"",Address:""};
		
		$scope.customer.Addresses.push(address);
	}
	
	
	$scope.deladdress=function(item,idx)
	{
		//var address={Name:"",Email:"",ContactNo:"",ContactPerson:"",Address:""};
		if(angular.isDefined(item.Id))
		{
			$scope.customer.DelAddresses.push(item);
		}
		$scope.customer.Addresses.splice(idx,1);
	}
  
  $scope.addnewcust=function()
			{
				
				$rootScope.editflag=false;
				$rootScope.disablebtn=true;
				$scope.customer={};
				
				$scope.customermodalInstance = $uibModal.open({
					keyboard:false,
					backdrop:"static",
				   templateUrl: 'templates/users/customeradd.html',
						scope:$scope,
					
				 });
			}
			
			$scope.custcancel = function () {
			
			
				$scope.issaving=false;
				$rootScope.disablebtn=false;
				$scope.customermodalInstance.dismiss('cancel');
		
			};
			
			
 $scope.savecustomer=function(customer)
	{
			$scope.issaving=true;
	
				customer.CreatedBy=$rootScope.dboard.uid;

		$http({		method:'POST',
				url:$rootScope.apiurl+'settingapi/customeradd',	
				data:customer
				}).then(function successCallback(data) {
			
				console.log(data);
				
				$scope.customers=data.data.customers;
				$scope.customer=_.findWhere($scope.customers,{Id:angular.fromJson(data.data.CustomerId)});
				$scope.quote.CustId=angular.fromJson(data.data.CustomerId);
			//	$scope.categoryid=angular.fromJson(data);
			// $scope.showcustinfo($scope.quote.CustId);
			
				toaster.pop({toasterId:11,type:"success",body:"Customer Added Successfully."});
				
				$scope.custcancel();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 			
	
	};
	
	
	$scope.mflags={};
	$scope.mflags.oneclick=[];
	
		$scope.samplenames=[];
	          SampleNames.fetchSamples().then(function(data){		
			$scope.samplenames=data;
		});
     
       

//----PLan Section ----//

	$scope.closesubstdplanModal=function()
  {
	   $scope.substdplanInstance.dismiss('cancel');
  }
  
  $scope.addsubstdplan=function(substdid)
  {
	  

	   $scope.plan={};
	    $scope.plan.SubStdId=substdid;
	   $scope.plan.Parameters=[];
	   $scope.plan.DelParameters=[];
	  $http({
					method:'PUT',
					url: $rootScope.apiurl+'adminapi/substdparams/'+substdid,
					data:{}				
					}).then(function successCallback(data) {
				console.log(data);
			
			 
			 $scope.allparameters=data.data.Parameters;
			if(data.data.Parameters.length>0)
			{
			  $scope.substdplanInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'templates/settings/substdplanaddModal.html',
     	 scope: $scope,
   	 });
			}
			 
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
	 
  }
  
  $scope.removeparameters=function($item,$model)
  {
	  // console.log($item);
	  // console.log($model);
	 // angular.isDefined()
	  $scope.plan.DelParameters.push($model);
	  
	
  }
  
  $scope.editsubstdplan=function(planid)
  {
	  console.log(planid);
  $scope.plan=_.findWhere($scope.substdplans,{Id:planid});
  $scope.plan.Parameters=[];
  $scope.plan.DelParameters=[];
  angular.forEach($scope.plan.VParameters,function(val){
	  
	 $scope.plan.Parameters.push(val.Id);
  })
  
  if(!_.isEmpty($scope.plan))
  {
    $http({
					method:'PUT',
					url: $rootScope.apiurl+'adminapi/substdparams/'+$scope.plan.SubStdId,
					data:{}				
					}).then(function successCallback(data) {
				console.log(data);
			
			 
			 $scope.allparameters=data.data.Parameters;
			
			  $scope.substdplanInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'templates/settings/substdplanaddModal.html',
     	 scope: $scope,
   	 });
  
  }, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
  }
  
	 
  }
  
  
  
  $scope.plansave=function(plan)
  {
	  
	  if(angular.isDefined(plan.Id))
	  {
		    $http({		method:'PUT',
				url:$rootScope.apiurl+'adminapi/updatetestplan/'+plan.Id,	
				data:plan
				}).then(function successCallback(data) {
			
				//console.log(data);
				$scope.substdplans=data.data.substdplans;
				toaster.pop({toasterId:11,type:"success",body:"Successfully Updated"});
				$scope.closesubstdplanModal();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
		  
	  }
	  else
	  {
		   $http({		method:'POST',
				url:$rootScope.apiurl+'adminapi/addtestplan',	
				data:plan
				}).then(function successCallback(data) {
			
				//console.log(data);
				$scope.substdplans=data.data.substdplans;
				toaster.pop({toasterId:11,type:"success",body:"Successfully Added"});
				$scope.closesubstdplanModal();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			}); 
		  
	  }
	
  }
	

});


App.controller('custquoteEditCntrl',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$q,configparam,$interval,
$rootScope,$state,AuthenticationService,$timeout,SampleNames,Cart,alldata)
{
	
	var exists=$localstorage.getObject(configparam.AppName);
	
		 $scope.dateOptions = {
    //customClass: getDayClass,
    minDate: new Date(),
    showWeeks: false
  };
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
  
  $scope.popup3 = {
    opened: []
  };
   $scope.open3 = function(index) {
    $scope.popup3.opened[index] = true;
  };
  
  
  $scope.showcustinfo=function(id)
  {
	  $scope.customer=_.findWhere($scope.customers,{Id:id});
  }
  
  $scope.gettax=function(param)
  {if(param)
	  {
	  $scope.quote.Tax=$scope.taxvalue;
	  }
	  else{
		  $scope.quote.Tax=0;
	  }
	  $scope.updatetotal($scope.quote);
  }
  $scope.getNumber = function(num) {
    return new Array(num);   
}
  
  
 
 if(angular.isDefined(alldata.data))
 {
	 console.log(alldata);
	 $scope.customers=alldata.data.customers;
					$scope.allindustries=alldata.data.industries;	 
					$scope.users=alldata.data.users;
					$scope.testconditions=alldata.data.testconditions;
			$scope.taxlabel=alldata.data.TaxLabel;
	$scope.taxvalue=alldata.data.Tax;
	$scope.qvdays=alldata.data.QVDays;
$scope.drawntypes=alldata.data.drawntypes;	
$scope.receipttypes=alldata.data.receipttypes;	
					$scope.quote=alldata.data.quote;
$scope.quote.QDate=new Date($scope.quote.QDate);
			$scope.quote.ValidDate=new Date($scope.quote.ValidDate);
    if($scope.quote.Tax>0)
				{
					$scope.quote.IsTax='1';
					
				}


if(alldata.data.IsTax==='1')
{
	$scope.quote.IsTax=alldata.data.IsTax;
	$scope.quote.Tax=alldata.data.Tax;
}
$scope.ivflags={};
$scope.issaving=false;
			$scope.ivflags.editflag=false;

 }
$scope.quote.DelDetails=[];
			 $scope.showcustinfo($scope.quote.CustId);
			// $scope.updatetotal($scope.quote);
 
	
	$scope.minQuantity=1;
	$scope.maxQuantity=10;
	
	$scope.quoteflags={};
	
	$scope.removeapptest=function(item,model)
	{
		//$scope.rir.applicabletest.splice(idx,1);
		console.log(item);
		//var idx=_.indexOf($scope.rir.applicabletest,{TestId:item.TestId});
		$scope.qitem.applicabletest=_.without($scope.qitem.applicabletest, _.findWhere($scope.qitem.applicabletest,{TSeq:item.TSeq}));
	}
	
	$scope.additemrow=function(param,idx)
	{
		console.log(idx);
		
		console.log(param);
		if(param==='new')
		{
			$scope.qitem={};	
			$scope.qitem.index=null;	
				$scope.qitem.addtest=[];
				$scope.quoteflags.editflag=false;
		}
		else
		{
			
			$scope.qitem=param;
			$scope.qitem.index=idx;
			$scope.getinddata2(param);
			$scope.qitem=param;
			//$scope.getsubind(param.PIndId);
			//$scope.getcatdata(param.SubIndId);
			
			//$scope.qitem.IndId= $scope.customer.IndId;
				
			//
			
			$scope.quoteflags.editflag=true;
			 var item={	
			 Id:param.Id,
			 Qty:param.Qty,
					TestCondId:param.TestCondId,
					TestId:param.TestId,TestName:param.TestName,LabIds:param.LabIds,
					SubStdId:param.SubStdId, PlanId:param.PlanId,
					TestMethodId:param.TestMethodId,
					Price:"",ExtraInfo:""
					};
			$scope.qitem.applicabletest.push(item);
			
		}		
		
		 $scope.quoteitemmodal = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'quoteitemModal.html',
     	 scope: $scope,
   	 });
		
	}
	
	$scope.closeqitemModal=function(){
		$scope.qitem={};
		$scope.quoteitemmodal.dismiss('cancel');
	}
	
	$scope.addtoapplicable=function(par1,par2)
	{
		console.log(par1);
		console.log(par2);
			var test=_.findWhere($scope.alltests,{Id:par2});
			
		console.log(test);
		 var item={	
					TestCondId:3,
					TestId:par2,TestName:test.TestName,LabId:"",
					SubStdId:"", 
					TestMethodId:"",
					Price:"",ExtraInfo:""
					};
							
					
		$scope.qitem.applicabletest.push(item);
		
	}
	
	
		$scope.getsubind=function(indid)
	{
		console.log(indid);
		
		var industry=_.findWhere($scope.industries,{Id:indid});
		$scope.subindustries=industry.Children;
		console.log(industry);
		//$scope.qitem.SubIndId="";
	}
	
	$scope.getcatdata=function(indid)
	{
		console.log(indid);
		//$scope.qitem.IndId="";
		var subindustry=_.findWhere($scope.subindustries,{Id:indid});
		$scope.procategories=subindustry.Children;
		console.log(subindustry);
	}
	$scope.getinddata2=function(param)
			{
				$scope.qitem.applicabletest=[];
				 $http({
					method:'GET',
					url: $rootScope.apiurl+'api/ririnddata/'+param.IndId,
									
					}).then(function successCallback(data) {
				console.log(data);
			
			 	$scope.standards=data.data.standards;
		$scope.substandards=data.data.substandards;
		$scope.alltests=data.data.alltests;	
		$scope.testmethods=data.data.testmethods;
		$scope.alllabs=data.data.alllabs;
			$scope.substdplans=data.data.substdplans;
			
			$scope.getsubplans(param.SubStdId);
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
			}
	
	$scope.getinddata=function(ind)
			{
				$scope.qitem.applicabletest=[];
				 $http({
					method:'GET',
					url: $rootScope.apiurl+'api/ririnddata/'+ind,
									
					}).then(function successCallback(data) {
				console.log(data);
				$scope.qitem.SubStdId="";
				$scope.qitem.TestId="";
				$scope.qitem.TestCondId="";
				$scope.qitem.LabIds=[];
				$scope.qitem.TestMethodId="";
			 	$scope.standards=data.data.standards;
		$scope.substandards=data.data.substandards;
		$scope.alltests=data.data.alltests;	
		$scope.testmethods=data.data.testmethods;
		$scope.alllabs=data.data.alllabs;
		$scope.substdplans=data.data.substdplans;
		
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
			}
	
	
	$scope.getsubstandards=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.substandards,{TestId:type});
		}
	}
	$scope.getsubplans=function(substdid)
	{
	//console.log(substdid);
if(angular.isDefined(substdid))
		{
				return _.where($scope.substdplans,{SubStdId:substdid});
		}
		
	}
	$scope.gettestmethods=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.testmethods,{TestId:type});
		}
	}
	
	
	
	$scope.qitemsave=function(qitem,idx)
	{
		
		
		console.log(qitem);
			console.log(idx);	
		
		var test=_.findWhere($scope.alltests,{TestId:qitem.TestId});		
			var price=test.Cost;		
		var testcond=_.findWhere($scope.testconditions,{Id:qitem.TestCondId});		
		 price=price+testcond.Cost;		
		var testmethod=_.findWhere($scope.testmethods,{Id:qitem.TestMethodId});
		var industry=_.findWhere($scope.allindustries,{Id:qitem.IndId});
		var std=_.findWhere($scope.getsubstandards(qitem.TestId),{Id:qitem.SubStdId});
		price=price+std.Cost;
		qitem.Labs=[];
		
		angular.forEach(qitem.LabIds,function(val){
			var lab=_.findWhere($scope.alllabs,{Id:val});
			 price=price+lab.Cost;
			 qitem.Labs.push(lab.Name);
		})
		
		
		var plan=_.findWhere($scope.substdplans,{Id:qitem.PlanId});
		 if(!_.isEmpty(plan))
		 {
			  price=price+plan.Cost;
		 }
		
		var item={	Id:qitem.Id,	SampleName:qitem.SampleName,SampleWeight:qitem.SampleWeight,TAT:qitem.TAT,
					TestCondition:testcond.Name,TestCondId:qitem.TestCondId,
					TestId:qitem.TestId,TestName:test.TestName,
					SubStdId:qitem.SubStdId, StdName:std.Name,
					PlanId:qitem.PlanId,
					Plan:_.isEmpty(plan)?null:plan.Name,PlanParameters:_.isEmpty(plan)?null:plan.VParameters,
					TestMethod:_.isEmpty(testmethod)?null:testmethod.Method,TestMethodId:qitem.TestMethodId,LabIds:qitem.LabIds,
					PIndId:qitem.PIndId,SubIndId:qitem.SubIndId,sds:std.sds,labnames:qitem.Labs,
					IndId:qitem.IndId,Industry:industry.Name,Price:price,Qty:qitem.Qty
					};
					console.log(item);
					
				if( idx === null )
				{
						$scope.quote.Details.push(item);
				}					
				else
				{
					$scope.quote.Details[idx ]=item;					
					
				}
		
		
		



			
		
		
		$scope.updatetotal($scope.quote);
	}
	
	
	$scope.qitemsaveall=function(qitem)
	{
		console.log(qitem);
		angular.forEach(qitem.applicabletest,function(val){
				val.PIndId=qitem.PIndId;
				val.SubIndId=qitem.SubIndId;
			val.IndId=qitem.IndId;
			val.SampleName=qitem.SampleName;
			val.SampleWeight=qitem.SampleWeight;
			val.TAT=qitem.TAT;
			val.Id=qitem.Id;
			$scope.qitemsave(val,qitem.index);
			
		})
		
		$timeout(function(){$scope.closeqitemModal();},500);
		//$scope.updatetotal($scope.quote);
	}
	$scope.showsds=function(qitem)
	{
		
			var std=_.findWhere($scope.getsubstandards(qitem.TestId),{Id:qitem.SubStdId});
			$scope.qitem.sds=std.sds;
		
	}
	

	$scope.resetitems=function(invtype)
	{
		$scope.quote.CustId="";
		$scope.quote.Details=[];
		 $scope.customer="";
		 
		 if(invtype==='C')
		 {
			$scope.quote.Tax=alldata.data.Tax; 
		 }
	}
	
	
	$scope.updatetotal=function(quote)
	{
		
		$scope.quote.SubTotal=	Cart.getsubtotal(quote);				
       $scope.quote.Total=	Cart.gettotal(quote,alldata.data.Tax);
	   
	}
	

	
	$scope.delitem=function(param,idx)
	{
		if(angular.isDefined(param.Id))
		{
			$scope.quote.DelDetails.push(param);
		}
		
	
		$scope.quote.Details.splice(idx,1);
			$scope.updatetotal($scope.quote);
			
	}
	
	$scope.getallquotes=function(pageno)
	{
		$state.go('app.quotes');
	};
	
	
	$scope.quotesave=function(quote)
	{
		$scope.issaving=true;
	console.log(quote);
	if(angular.isDefined(quote.Id))
	{


quote.CreatedBy=$rootScope.dboard.uid;
		$http({	method:'PUT',
     		url:$rootScope.apiurl+'adminapi/updquote/'+quote.Id,	
     		data:quote
     			}).then(function successCallback(data) {
				
				$scope.getallquotes(1);
		
     			}, function errorCallback(data) {
     				console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
     		});
	}
	else
	{	
		console.log("error");
	 }   			
	
	}
  
  $scope.addaddress=function()
	{
		var address={Name:"",Email:"",ContactNo:"",ContactPerson:"",Address:""};
		
		$scope.customer.Addresses.push(address);
	}
	
	
	$scope.deladdress=function(item,idx)
	{
		//var address={Name:"",Email:"",ContactNo:"",ContactPerson:"",Address:""};
		if(angular.isDefined(item.Id))
		{
			$scope.customer.DelAddresses.push(item);
		}
		$scope.customer.Addresses.splice(idx,1);
	}
  
  $scope.addnewcust=function()
			{
				
			$rootScope.editflag=false;
			$rootScope.disablebtn=true;
			$scope.customer={}				
		$scope.customer.Addresses=[];
				


	$scope.customermodalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
   	   templateUrl: 'templates/users/customeradd.html',
     		scope:$scope,
     	
   	 });
			}
			
			$scope.custcancel = function () {
			
			$scope.customer={}	
			$scope.issaving=false;
			$rootScope.disablebtn=false;
    	$scope.customermodalInstance.dismiss('cancel');
		
 	 };
 $scope.savecustomer=function(customer)
	{
	$scope.issaving=true;
	
	customer.CreatedBy=$rootScope.dboard.uid;

		$http({		method:'POST',
				url:$rootScope.apiurl+'settingapi/customeradd',	
				data:customer
				}).then(function successCallback(data) {
			
				//console.log(data);
				$scope.quote.CustId=data.data.CustomerId;
				$scope.customers=data.data.customers;
			//	$scope.categoryid=angular.fromJson(data);
			 $scope.showcustinfo($scope.quote.CustId);
				toaster.pop('wait', "", "Customer Detail successfully saved.");
				
				$scope.custcancel();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 			
	
	};
	
	
	$scope.mflags={};
	$scope.mflags.oneclick=[];
	
	
		$scope.samplenames=[];
	          SampleNames.fetchSamples().then(function(data){		
			$scope.samplenames=data;
		});
     
	
//----PLan Section ----//

	$scope.closesubstdplanModal=function()
  {
	   $scope.substdplanInstance.dismiss('cancel');
  }
  
  $scope.addsubstdplan=function(substdid)
  {
	  

	   $scope.plan={};
	    $scope.plan.SubStdId=substdid;
	   $scope.plan.Parameters=[];
	   $scope.plan.DelParameters=[];
	  $http({
					method:'PUT',
					url: $rootScope.apiurl+'adminapi/substdparams/'+substdid,
					data:{}				
					}).then(function successCallback(data) {
				console.log(data);
			
			 
			 $scope.allparameters=data.data.Parameters;
			
			  $scope.substdplanInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'templates/settings/substdplanaddModal.html',
     	 scope: $scope,
   	 });
	 
			 
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
	 
  }
  
  $scope.removeparameters=function($item,$model)
  {
	  // console.log($item);
	  // console.log($model);
	 // angular.isDefined()
	  $scope.plan.DelParameters.push($model);
	  
	
  }
  
  $scope.editsubstdplan=function(planid)
  {
	  console.log(planid);
  $scope.plan=_.findWhere($scope.substdplans,{Id:planid});
    console.log( $scope.plan);
  $scope.plan.Parameters=[];
  $scope.plan.DelParameters=[];
  angular.forEach($scope.plan.VParameters,function(val){
	  
	 $scope.plan.Parameters.push(val.Id);
  })
  
  if(!_.isEmpty($scope.plan))
  {
    $http({
					method:'PUT',
					url: $rootScope.apiurl+'adminapi/substdparams/'+$scope.plan.SubStdId,
					data:{}				
					}).then(function successCallback(data) {
				console.log(data);
			$scope.allparameters=[];
			 
			 $scope.allparameters=data.data.Parameters;
			
			  $scope.substdplanInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'templates/settings/substdplanaddModal.html',
     	 scope: $scope,
   	 });
  
  }, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
  }
  
	 
  }
  
  
  
  $scope.plansave=function(plan)
  {
	  
	  if(angular.isDefined(plan.Id))
	  {
		    $http({		method:'PUT',
				url:$rootScope.apiurl+'adminapi/updatetestplan/'+plan.Id,	
				data:plan
				}).then(function successCallback(data) {
			
				//console.log(data);
				$scope.substdplans=data.data.substdplans;
				
				$scope.closesubstdplanModal();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
		  
	  }
	  else
	  {
		   $http({		method:'POST',
				url:$rootScope.apiurl+'adminapi/addtestplan',	
				data:plan
				}).then(function successCallback(data) {
			
				//console.log(data);
				$scope.substdplans=data.data.substdplans;
				
				$scope.closesubstdplanModal();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			}); 
		  
	  }
	
  }
	

	
});


