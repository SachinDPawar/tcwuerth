App.controller('contsstController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	$scope.allrirs=[];
	$scope.animationsEnabled = true;
	$scope.currentPage=1;
	$scope.pageSize=30;
	$scope.totalitems=0;
	$scope.sst={};
	if(angular.isDefined(alldata.data))
	{
		$scope.allrirs=alldata.data.allrirs;
		$scope.totalitems=alldata.data.totalitems;
		//console.log($scope.allrirs);
	}
	
	$scope.editresult=function(param,id)
	{
		$state.go('app.contsstedit',{id:id});
	}
  
   $scope.showsst = function (size,sst) {
		$scope.sst=sst;   
		$scope.uibModalInstance = $uibModal.open({
		animation: $scope.animationsEnabled,
		templateUrl: 'sstviewContent.html',
		scope:$scope,
		size: size,
     
    });
   }
 
   $scope.cancel = function () {
    $scope.uibModalInstance.dismiss('cancel');
  };
  
   $scope.viewcertificate = function (size,sst) {
		$scope.sstcert=sst;   
		$scope.uibcertModalInstance = $uibModal.open({
		animation: $scope.animationsEnabled,
		templateUrl: 'sstcertContent.html',
		scope:$scope,
		size: size,
     
    });
   }
   
    $scope.cancelcert = function () {
    $scope.uibcertModalInstance.dismiss('cancel');
  };
  
  
   $scope.deletecontsst=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.Test=id.PartName;
			$scope.modalInstance2 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'deleteModal',
				scope:$scope,
     	
				});
		}
		else if(con=='delete')
		{
			$scope.oneclick=true;
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'coatapi/delcontsst/'+id,
		        
		   		}).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance2.dismiss('cancel');
 	}

	// $scope.getalltemp=function()
	// {
			// $http({	
					// method:'GET',
					// url:$rootScope.apiurl+'coatapi/contsstdata',	
	     	 
	     		//}).then(function successCallback(data) {
					// //console.log(data);
					// $scope.allrirs=data;
			     				
	     		// }, function errorCallback(data) {
	     			// console.log(data);
	     		// });
	// }
	
  $scope.closeconfirmModal3=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance3.dismiss('cancel');
 	}
	
$scope.approvecontsst=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.Test=id.PartName;
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
		       		 url:$rootScope.apiurl+'coatapi/approvecontsst/'+id,
					 data:{ApprovedBy:$rootScope.dboard.uid}
		        
		   		}).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal3();
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
				});
		}

	}
	
	
	$scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		//console.log(pageno);
		$http({	
					method:'GET',
					url:$rootScope.apiurl+'coatapi/contsstdata/30/'+pageno,	
	     	 
	     		}).then(function successCallback(data) {
					//console.log(data);
				$scope.allrirs=data.data.allrirs;
		$scope.totalitems=data.data.totalitems;
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			console.log(data);
					$scope.flags.loadingdata=false;
	     		});
		
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
							url:$rootScope.apiurl+'coatapi/searchcontsst/',	
							data:{text:searchtext,pageSize:$scope.pageSize}
					 
						}).then(function successCallback(data) {
							//console.log(data);
						$scope.allrirs=data.data.allrirs;
				$scope.totalitems=0;//data.totalitems;
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
  
});

App.controller('contsstaddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';
	$scope.sst={};
	$scope.ssthrs=[];
	$scope.sst.basic={};
	$scope.sst.basic.obbasic={};
	$scope.editflag=false;
	$scope.rirs=[];
	$scope.rir="";
	$scope.flags={};
	$scope.flags.observation=true;
	$scope.flags.oneclick=false;
	$scope.remarks=['passed','pending','failed'];
	$scope.statuses=['Done','Pending'];
  $scope.hstep = 1;
  $scope.mstep = 5;
   $scope.ismeridian = false;
	
	if(angular.isDefined(allpredata.data))
	{
		//console.log(allpredata.data);
		$scope.rirs=allpredata.data.allrirs;
		$scope.ssthrs=allpredata.data.ssthrs;
		$scope.sst.basic=allpredata.data.basic;
	$scope.sst.basic.ReportDate=new Date();
	$scope.sst.basic.ReceiptOn=new Date();
	$scope.sst.basic.LoadingDate=new Date();
	$scope.sst.basic.CompleteDate=new Date();
	
	
	}
	
	$scope.loadobservations=function(item)
	{
		
		$scope.sst.basic.observations=[];
		var nr=parseInt(item.Hours)/parseInt(24);
		
		for(var i=1;i<=nr;i++)
		{
			$scope.sst.basic.observations.push({Duration:parseInt(24)*i,OnDate:new Date(),White:false,NoWhite:false,Red:false,NoRed:false,Status:"Pending"});
		}
		if(parseInt(item.Hours)%parseInt(24) !=0)
		{
			//nr=nr+1;
			$scope.sst.basic.observations.push({Duration:item.Hours,OnDate:new Date(),White:false,NoWhite:false,Red:false,NoRed:false,Status:"Pending"});
		}
		
	}
	
	$scope.getbasicsst=function(rir)
	{
		$scope.sst.basic=_.findWhere($scope.rirs,{Id:rir});
		$scope.sst.basic.obbasic={};
		$scope.sst.basic.obbasic.Interval="24";
		
	}
	
  $scope.dateOptions = {
   // minDate: new Date(),
    showWeeks: true
  };
	
	
	 $scope.popup = {
    repdate: false,
	recon:false
	
  };
   $scope.openrep = function() {
    $scope.popup.repdate = true;
  };
 $scope.openrecon = function() {
    $scope.popup.recondate = true;
  };	
	
	$scope.popup2 = {
    opened: []
	  };
	   $scope.open2 = function(index) {
		$scope.popup2.opened[index] = true;
	  };
	  
	$scope.popup1 = {
    opened: []
	  };
	   $scope.open1 = function(index) {
		$scope.popup1.opened[index] = true;
	  };  
 
    $scope.cancelsave=function()
	{
		$state.go('app.contsst');
	}  
   
  $scope.savesst=function(sst)
  {
	console.log(sst);  
		$scope.flags.oneclick=true;
		sst.ModifiedBy=$rootScope.dboard.uid;
	 $http({
					method:'POST',
					url: $rootScope.apiurl+'coatapi/contsstadd',
					data:sst					
				}).then(function successCallback(data) {
				//console.log(data);
			 	$scope.flags.oneclick=false;
				$state.go('app.contsst');
			 	
					}, function errorCallback(data) {
					console.log(data);
					$scope.flags.oneclick=false;
				}); 
  }
  
});


App.controller('contssteditController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';
	$scope.sst={};
	$scope.sst.basic={};
	$scope.editflag=true;
	$scope.flags={};
	$scope.flags.observation=true;
	$scope.flags.oneclick=false;
	$scope.remarks=['pending','passed','failed'];
	$scope.statuses=['Done','Pending'];
  $scope.hstep = 1;
  $scope.mstep = 5;
   $scope.ismeridian = false;
  
	if(angular.isDefined(allpredata.data))
	{
		//console.log(allpredata.data);
		$scope.sst.basic=allpredata.data.basic;
		console.log($scope.sst);
		//$scope.sst.cond=allpredata.data.cond;
		
		if($scope.sst.basic.observations.length<1)
		{
			var interval=$scope.sst.basic.interval;
			
			for(var i=1;i<=$scope.sst.basic.drows;i++)
			{
			$scope.sst.basic.observations.push({SeqNo:i,Duration:i*interval,OnDate:new Date(),White:"false",NoWhite:"false",Red:"false",NoRed:"false",Status:"Pending"});
			}
		}
		else
		{
			if(!_.isEmpty($scope.sst.basic.ReportDate))
			{
			$scope.sst.basic.ReportDate=new Date($scope.sst.basic.ReportDate);
			}
			if(!_.isEmpty($scope.sst.basic.ReceiptOn))
			{
			$scope.sst.basic.ReceiptOn=new Date($scope.sst.basic.ReceiptOn);
			}
			if(!_.isEmpty($scope.sst.basic.LoadingDate))
			{
			$scope.sst.basic.LoadingDate=new Date($scope.sst.basic.LoadingDate);
			}
			if(!_.isEmpty($scope.sst.basic.CompleteDate))
			{
			$scope.sst.basic.CompleteDate=new Date($scope.sst.basic.CompleteDate);
			}
				angular.forEach($scope.sst.basic.observations,function(item){
					if(!_.isEmpty(item.OnDate))
					{
					item.OnDate=new Date(item.OnDate);
					}
				});
		}
		
	}
	
  $scope.dateOptions = {
    //minDate: new Date(),
    showWeeks: true
  };
	 $scope.popup = {
    repdate: false,
	recon:false
	
  };
   $scope.openrep = function() {
    $scope.popup.repdate = true;
  };
 $scope.openrecon = function() {
    $scope.popup.recondate = true;
  };	
	
	$scope.popup2 = {
    opened: []
	  };
	   $scope.open2 = function(index) {
		$scope.popup2.opened[index] = true;
	  };
	  
	$scope.popup1 = {
    opened: []
	  };
	   $scope.open1 = function(index) {
		$scope.popup1.opened[index] = true;
	  };  
 
    $scope.cancelsave=function()
	{
		$state.go('app.contsst');
	}  
   
  $scope.savesst=function(sst)
  {
	console.log(sst);  
		$scope.flags.oneclick=true;
		sst.ModifiedBy=$rootScope.dboard.uid;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'coatapi/contsstupdate/'+sst.basic.Id,
					data:sst					
				}).then(function successCallback(data) {
				//console.log(data);
			 	$scope.flags.oneclick=false;
				$state.go('app.contsst');
			 	
					}, function errorCallback(data) {
					console.log(data);
					$scope.flags.oneclick=false;
				}); 
  }
  
});

App.controller('coatcertController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.allcoatcerts=[];
	$scope.coatcert={};
	$scope.animationsEnabled = true;
	$scope.currentPage=1;
	$scope.pageSize=30;
	if(angular.isDefined(alldata.data))
	{
		$scope.allcoatcerts=alldata.data.allrirs;
		$scope.totalitems=alldata.data.totalitems;
		//console.log($scope.allcoatcerts);
	}
	
  $scope.editcoatcert=function(param,id)
  {
	 	
		$state.go('app.coatcertedit',{id:id});
		  
  }
  
   
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
     	
				});
		}
		else if(con=='delete')
		{
			$scope.oneclick=true;
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'coatapi/dropcoatcert/'+id,
		        
		   		}).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance2.dismiss('cancel');
 	}

	
	
		
	$scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		//console.log(pageno);
		$http({	
					method:'GET',
					url:$rootScope.apiurl+'coatapi/coatcertdata/30/'+pageno,	
	     	 
	     		}).then(function successCallback(data) {
					//console.log(data);
				$scope.allcoatcerts=data.allrirs;
		$scope.totalitems=data.totalitems;
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			console.log(data);
					$scope.flags.loadingdata=false;
	     		});
		
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
							url:$rootScope.apiurl+'coatapi/searchcoatcert/',	
							data:{text:searchtext,pageSize:$scope.pageSize}
					 
						}).then(function successCallback(data) {
							//console.log(data);
						$scope.allrirs=data.allrirs;
				$scope.totalitems=0;//data.totalitems;
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
  
});

App.controller('coatcertaddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout)
{
	'use strict';

	$scope.coatcert={};
	
	$scope.loadingFiles = true;	
	$scope.path='images/coatcertuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	$scope.tempqueue=[];	
	$scope.oneclick=false;
	$scope.editflag=false;

	$scope.coatcert.TopCoatDate=new Date();
	$scope.dateOptions = {
 //   minDate: new Date(),
    showWeeks: true
  };
	
	
	 $scope.popup = {
    coatdate: false,
	recon:false
	
  };
   $scope.opencoatdate = function() {
    $scope.popup.coatdate = true;
  };
	//$scope.pdir.basic.observations=[];
	
	
	
    $scope.cancelsave=function()
	{
		$state.go('app.coatcert');
	}  
   
  $scope.savecoatcert=function(coatcert)
  {
	console.log(coatcert);  
	$scope.oneclick=true;
		$http({
				method:'POST',
				url: $rootScope.apiurl+'coatapi/coatcertadd',
				data:coatcert					
			}).then(function successCallback(data) {
			
				$scope.coatcertid=angular.fromJson(data);
				$timeout(function() {
				$scope.saveimage();
				},  1000);
			
				//$state.go('app.pdir');
			
				}, function errorCallback(data) {
				console.log(data);
				$scope.oneclick=false;
				
			}); 
  }
  
/**----------Upload Code----------**/  
	$scope.saveimage=function()
		{
			
			if($scope.queue.length>0)
			{
			
				for(var i=0;i<$scope.queue.length;i++)
				{
					if(angular.isUndefined($scope.queue[i].id))
					{
					//console.log($scope.pdirid);
					var file=$scope.queue[i];
					file=_.extend(file,{"coatcertid":$scope.coatcertid});
					var result=file.$submit();
					console.log(result);
					}
					
								
				}
			}
			else
			{
				
				$scope.oneclick=false;
				$state.go('app.coatcert');
			}
			
		}

		
	$scope.$on('fileuploadsubmit', function (e, data) {
					data.formData = {coatcertid: data.files[0].coatcertid};
 		});
	
	
	$scope.$on('fileuploadstop', function()
	{
		$scope.oneclick=false;
		$state.go('app.coatcert');
	});
	
	$scope.deletefile=function(index,file)
	{
			file.$cancel();
			$scope.number++;
		
	}
	
  
});

App.controller('coatcerteditController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';
	$scope.allrirs=[];
	$scope.coatcert={};
	$scope.editflag=true;
	$scope.oneclick=false;
	$scope.loadingFiles = true;	
	$scope.path='images/coatcertuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	$scope.tempqueue=[];
	
	/************Image load************/	
	  $scope.loadimages=function()
	{
	$scope.loadingFiles = true;
		$http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = true;
							 
						var allfiles=response.data.files;
						//console.log(response);
					if(allfiles.length>0)
					{
						angular.forEach(allfiles,function(file)
							{
								if(angular.isDefined(file.coatcertid) && parseInt(file.coatcertid)===parseInt($scope.coatcert.Id))
								{
									$scope.queue.push(file);
									
								}
								
							});
					}
					
										//	console.log(  $scope.queue);
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );
						
						
	}
	  
/////////////////////	
	
	if(angular.isDefined(allpredata.data))
	{
		$scope.coatcert=allpredata.data;		
		console.log(allpredata.data);
		if(!_.isEmpty($scope.coatcert.TopCoatDate))
			{
			$scope.coatcert.TopCoatDate=new Date($scope.coatcert.TopCoatDate);
			}
		
			$scope.loadimages();
	}
	
	
	$scope.dateOptions = {
   //minDate: new Date(),
    showWeeks: true
  };
	
	
	 $scope.popup = {
    coatdate: false,
	recon:false
	
  };
   $scope.opencoatdate = function() {
    $scope.popup.coatdate = true;
  };
	
	
	
	
  $scope.cancelsave=function()
	{
		$state.go('app.coatcert');
	}  
  
  $scope.savecoatcert=function(coatcert)
  {
	$scope.oneclick=true;
	 $http({
				method:'PUT',
				url: $rootScope.apiurl+'coatapi/coatcertupdate/'+coatcert.Id,
				data:coatcert					
			}).then(function successCallback(data) {
			console.log(data);
				$scope.coatcertid=angular.fromJson(data);
				$timeout(function() {
				$scope.deleteimage();
				},  1000);
				
				}, function errorCallback(data) {
				console.log(data);
				$scope.oneclick=false;
			}); 
  }
  
$scope.saveimage=function()
		{
			
			if($scope.queue.length>0)
			{
			
				for(var i=0;i<$scope.queue.length;i++)
				{
					if(angular.isUndefined($scope.queue[i].id))
					{
					//console.log($scope.pdirid);
					var file=$scope.queue[i];
					file=_.extend(file,{"coatcertid":$scope.coatcertid});
					var result=file.$submit();
					console.log(result);
					}
						else
					{
						$state.go("app.coatcert");
						$scope.oneclick=false;
					}
								
				}
			}
			else
			{
			$state.go("app.coatcert");
			$scope.oneclick=false;
			}
			
		}

	$scope.deleteimage=function()
	{
		//console.log("delete");
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
		
	
	$scope.$on('fileuploadstop', function()
	{
		$scope.oneclick=false;
		$state.go("app.coatcert");
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {coatcertid: data.files[0].coatcertid};
 
});
	
	
	$scope.deletefile=function(index,file)
	{
		if(angular.isUndefined($scope.coatcert.Id))
		{
			file.$cancel();
			$scope.number++;
		}
		else
		{
			$scope.tempqueue.push(file);			
			$scope.queue.splice(index,1);	
			$scope.number++;
		}
	}
	  
  
  
});


App.controller('sstcertController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	$scope.allsstcerts=[];
	$scope.animationsEnabled = true;
	$scope.currentPage=1;
	$scope.pageSize=15;
	$scope.sst={};
	if(angular.isDefined(alldata.data))
	{
		$scope.allsstcerts=alldata.data.allrirs;
		$scope.totalitems=alldata.data.totalitems;
		//console.log($scope.allsstcerts);
	}
	
	$scope.editsstcert=function(param,id)
	{
		$state.go('app.sstcertedit',{id:id});
	}
  
   $scope.showsstcert = function (size,sst) {
		$scope.sstcert=sst;   
		$scope.uibModalInstance = $uibModal.open({
		animation: $scope.animationsEnabled,
		templateUrl: 'sstviewContent.html',
		scope:$scope,
		size: size,
     
    });
   }
 
   $scope.cancel = function () {
    $scope.uibModalInstance.dismiss('cancel');
  };
  
  
   $scope.deletesstcert=function(con,id)
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
     	
				});
		}
		else if(con=='delete')
		{
			$scope.oneclick=true;
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'coatapi/dropsstcert/'+id,
		        
		   		}).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance2.dismiss('cancel');
 	}

	// $scope.getalltemp=function()
	// {
			// $http({	
					// method:'GET',
					// url:$rootScope.apiurl+'coatapi/sstcertdata',	
	     	 
	     		//}).then(function successCallback(data) {
					// //console.log(data);
					// $scope.allsstcerts=data;
			     				
	     		// }, function errorCallback(data) {
	     			// console.log(data);
	     		// });
	// }
	
  $scope.closeconfirmModal3=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance3.dismiss('cancel');
 	}
	
$scope.approvesstcert=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.Test=id.PartName;
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
		       		 url:$rootScope.apiurl+'coatapi/approvesstcert/'+id,
					 data:{ApprovedBy:$rootScope.dboard.uid}
		        
		   		}).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal3();
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
				});
		}

	}
	
	
	$scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		//console.log(pageno);
		$http({	
					method:'GET',
					url:$rootScope.apiurl+'coatapi/sstcertdata/30/'+pageno,	
	     	 
	     		}).then(function successCallback(data) {
					//console.log(data);
				$scope.allsstcerts=data.allrirs;
		$scope.totalitems=data.totalitems;
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			console.log(data);
					$scope.flags.loadingdata=false;
	     		});
		
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
							url:$rootScope.apiurl+'coatapi/searchsstcert/',	
							data:{text:searchtext,pageSize:$scope.pageSize}
					 
						}).then(function successCallback(data) {
							//console.log(data);
						$scope.allrirs=data.allrirs;
				$scope.totalitems=0;//data.totalitems;
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
  
});

App.controller('sstcertaddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout)
{
	'use strict';
	$scope.sstcert={};
	$scope.sstcert.SerialNo="PM-LAM-19";
	$scope.editflag=false;
	$scope.oneclick=false;
		
  $scope.dateOptions = {
   // minDate: new Date(),
    showWeeks: true
  };
		
	$scope.popup = {
		certdate: false,
		loadon: false,
		ondate: false
	  };
   
   $scope.opencertdate = function() {
    $scope.popup.certdate = true;
  };
  
 $scope.openloadon = function() {
    $scope.popup.loadon = true;
  };
  
	 $scope.openondate = function() {
    $scope.popup.ondate = true;
  };	
	
    $scope.cancelsave=function()
	{
		$state.go('app.sstcert');
	}  
   
  $scope.savesstcert=function(sstcert)
  {
	console.log(sstcert);  
		$scope.oneclick=true;
		sstcert.ModifiedBy=$rootScope.dboard.uid;
	 $http({
					method:'POST',
					url: $rootScope.apiurl+'coatapi/sstcertadd',
					data:sstcert					
				}).then(function successCallback(data) {
				//console.log(data);
			 	$scope.oneclick=false;
				$state.go('app.sstcert');
			 	
					}, function errorCallback(data) {
					console.log(data);
					$scope.oneclick=false;
				}); 
  }
  
});


App.controller('sstcerteditController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';
	$scope.sstcert={};
	$scope.editflag=true;
	$scope.oneclick=false;
	
	 $scope.dateOptions = {
   // minDate: new Date(),
    showWeeks: true
  };
	$scope.popup = {
		certdate: false,
		loadon: false,
		ondate: false
	  };
   
   $scope.opencertdate = function() {
    $scope.popup.certdate = true;
  };
  
 $scope.openloadon = function() {
    $scope.popup.loadon = true;
  };
  
	 $scope.openondate = function() {
    $scope.popup.ondate = true;
  };	
	
	
	if(angular.isDefined(allpredata.data))
	{
		console.log(allpredata.data);
		$scope.sstcert=allpredata.data;
		
		
			if(!_.isEmpty($scope.sstcert.CertDate))
			{
			$scope.sstcert.CertDate=new Date($scope.sstcert.CertDate);
			}
			if(!_.isEmpty($scope.sstcert.LoadedOn))
			{
			$scope.sstcert.LoadedOn=new Date($scope.sstcert.LoadedOn);
			}
			if(!_.isEmpty($scope.sstcert.OnDate))
			{
			$scope.sstcert.OnDate=new Date($scope.sstcert.OnDate);
			}
		
				
		
	}
	
 
    $scope.cancelsave=function()
	{
		$state.go('app.sstcert');
	}  
   
  $scope.savesstcert=function(sstcert)
  {
	console.log(sstcert);  
		$scope.oneclick=true;
		sstcert.ModifiedBy=$rootScope.dboard.uid;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'coatapi/sstcertupdate/'+sstcert.Id,
					data:sstcert					
				}).then(function successCallback(data) {
				//console.log(data);
			 	$scope.oneclick=false;
				$state.go('app.sstcert');
			 	
					}, function errorCallback(data) {
					console.log(data);
					$scope.oneclick=false;
				}); 
  }
  
});


///////////////////////////////////////////////////

App.controller('FileDestroyController', [
            '$scope', '$http',
            function ($scope, $http) {
				console.log($scope.file);
                var file = $scope.file,
                    state;
                if (file.url) {
                    file.$state = function () {
                        return state;
                    };
                    file.$destroy = function () {
						console.log("delete");
                        state = 'pending';
                        return $http({
                            url: file.deleteUrl,
                            method: file.deleteType
                        }).then(
                            function (data) {
								console.log(data);
                                state = 'resolved';
                                $scope.clear(file);
                            },
                            function (data) {
								console.log(data);
                                state = 'rejected';
                            }
                        );
                    };
                } else if (!file.$cancel && !file._index) {
                    file.$cancel = function () {
                        $scope.clear(file);
                    };
                }
            }
        ]);


App.controller('pdirController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.allpdirs=[];
	$scope.pdir={};
	$scope.animationsEnabled = true;
	$scope.currentPage=1;
	$scope.pageSize=15;
	if(angular.isDefined(alldata.data))
	{
		$scope.allpdirs=alldata.data;
		//console.log($scope.allpdirs);
	}
	
  $scope.editpdir=function(param,id)
  {
	 	
		$state.go('app.pdiredit',{id:id});
		  
  }
  
   $scope.showpdir = function (size,pdir) {

$scope.viewpdir=pdir;   
   $scope.uibModalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'pdirviewContent.html',
      scope:$scope,
      size: size,
     
    });
   }
 
   $scope.cancel = function () {
    $scope.uibModalInstance.dismiss('cancel');
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
     	
				});
		}
		else if(con=='delete')
		{
			$scope.oneclick=true;
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'api/droppdir/'+id,
		        
		   		}).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getalltemp();
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.oneclick=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance2.dismiss('cancel');
 	}

	
	
	$scope.getalltemp=function()
	{
			$http({	
					method:'GET',
					url:$rootScope.apiurl+'api/pdirdata',	
	     	 
	     		}).then(function successCallback(data) {
					//console.log(data);
					$scope.allpdirs=data;
			     				
	     		}, function errorCallback(data) {
	     			console.log(data);
	     		});
	}
});

App.controller('pdiraddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout)
{
	'use strict';

	$scope.pdir={};
	
	$scope.loadingFiles = true;	
	$scope.path='images/pdiruploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	$scope.tempqueue=[];	
	$scope.oneclick=false;
	$scope.editflag=false;
	$scope.pdir.basic={};
	$scope.pdir.basic.PdirDate=new Date();
	$scope.dateOptions = {
  //  minDate: new Date(),
    showWeeks: true
  };
	
	
	 $scope.popup = {
    pdirdate: false,
	recon:false
	
  };
   $scope.openpdir = function() {
    $scope.popup.pdirdate = true;
  };
	//$scope.pdir.basic.observations=[];
	
	
    $scope.cancelsave=function()
	{
		$state.go('app.pdir');
	}  
   
  $scope.savepdir=function(pdir)
  {
	console.log(pdir);  
	$scope.oneclick=true;
		$http({
				method:'POST',
				url: $rootScope.apiurl+'api/pdiradd',
				data:pdir					
			}).then(function successCallback(data) {
			
				$scope.pdirid=angular.fromJson(data);
				$timeout(function() {
				$scope.saveimage();
				},  1000);
			
				//$state.go('app.pdir');
			
				}, function errorCallback(data) {
				console.log(data);
				$scope.oneclick=false;
				
			}); 
  }
  
/**----------Upload Code----------**/  
	$scope.saveimage=function()
		{
			
			if($scope.queue.length>0)
			{
			
				for(var i=0;i<$scope.queue.length;i++)
				{
					if(angular.isUndefined($scope.queue[i].id))
					{
					//console.log($scope.pdirid);
					var file=$scope.queue[i];
					file=_.extend(file,{"pdirid":$scope.pdirid});
					var result=file.$submit();
					console.log(result);
					}
					
								
				}
			}
			else
			{
				
				$scope.oneclick=false;
				$state.go('app.pdir');
			}
			
		}

		
	$scope.$on('fileuploadsubmit', function (e, data) {
					data.formData = {pdirid: data.files[0].pdirid};
 		});
	
	
	$scope.$on('fileuploadstop', function()
	{
		$scope.oneclick=false;
		$state.go('app.pdir');
	});
	
	$scope.deletefile=function(index,file)
	{
			file.$cancel();
			$scope.number++;
		
	}
	
  
});

App.controller('pdireditController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';
	$scope.allrirs=[];
	$scope.pdir={};
	$scope.pdir.delobservations=[];
	$scope.editflag=true;
	$scope.oneclick=false;
	$scope.loadingFiles = true;	
	$scope.path='images/pdiruploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	$scope.tempqueue=[];
	
	/************Image load************/	
	  $scope.loadimages=function()
	{
	$scope.loadingFiles = true;
		$http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = true;
							 
						var allfiles=response.data.files;
						//console.log(response);
					if(allfiles.length>0)
					{
						angular.forEach(allfiles,function(file)
							{
								if(angular.isDefined(file.pdirid) && parseInt(file.pdirid)===parseInt($scope.pdir.basic.Id))
								{
									$scope.queue.push(file);
									
								}
								
							});
					}
					
										//	console.log(  $scope.queue);
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );
						
						
	}
	  
/////////////////////	
	
	if(angular.isDefined(allpredata.data))
	{
		$scope.pdir=allpredata.data;		
		console.log(allpredata.data);
		if(!_.isEmpty($scope.pdir.basic.PdirDate))
			{
			$scope.pdir.basic.PdirDate=new Date($scope.pdir.basic.PdirDate);
			}
			$scope.pdir.delobservations=[];
			$scope.loadimages();
	}
	
	
	$scope.dateOptions = {
    //minDate: new Date(),
    showWeeks: true
  };
	
	
	 $scope.popup = {
    pdirdate: false,
	recon:false
	
  };
   $scope.openpdir = function() {
    $scope.popup.pdirdate = true;
  };
	
	
  $scope.cancelsave=function()
	{
		$state.go('app.pdir');
	}  
  
  $scope.savepdir=function(pdir)
  {
	console.log(pdir);  
	$scope.oneclick=true;
	 $http({
				method:'PUT',
				url: $rootScope.apiurl+'api/pdirupdate/'+pdir.basic.Id,
				data:pdir					
			}).then(function successCallback(data) {
			console.log(data);
				$scope.pdirid=angular.fromJson(data);
				$timeout(function() {
				$scope.deleteimage();
				},  1000);
				
				}, function errorCallback(data) {
				console.log(data);
				$scope.oneclick=false;
			}); 
  }
  
$scope.saveimage=function()
		{
			
			if($scope.queue.length>0)
			{
			
				for(var i=0;i<$scope.queue.length;i++)
				{
					if(angular.isUndefined($scope.queue[i].id))
					{
					//console.log($scope.pdirid);
					var file=$scope.queue[i];
					file=_.extend(file,{"pdirid":$scope.pdirid});
					var result=file.$submit();
					console.log(result);
					}
						else
					{
						$scope.oneclick=false;
						$state.go("app.pdir");
					}
								
				}
			}
			else
			{
				$scope.oneclick=false;
			$state.go("app.pdir");
			}
			
		}

	$scope.deleteimage=function()
	{
		//console.log("delete");
		if($scope.tempqueue.length>0)
			{
				for(var i=0;i<$scope.tempqueue.length;i++)
				{
					if(angular.isDefined($scope.tempqueue[i].pdirid))
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
		
	
	$scope.$on('fileuploadstop', function()
	{
		$scope.oneclick=false;
		$state.go("app.pdir");
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {pdirid: data.files[0].pdirid};
 
});
	
	
	$scope.deletefile=function(index,file)
	{
		if(angular.isUndefined($scope.pdir.basic.Id))
		{
			file.$cancel();
			$scope.number++;
		}
		else
		{
			$scope.tempqueue.push(file);			
			$scope.queue.splice(index,1);	
			$scope.number++;
		}
	}
	  
  
  
});


/****************All Data****************/
App.controller('bcregdetailController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata,CSV,$q)
{
	'use strict';
	$scope.allrirs=[];
	$scope.animationsEnabled = true;
	$scope.currentPage=1;
	$scope.pageSize=30;
	$scope.flags={};
	$scope.flags.loadingdata=false;
	
	$scope.separator=",";
	$scope.getHeader1=[];
	$scope.head={};
	
	$scope.filename = "test";
	//var csvdata = [];
	
	if(angular.isDefined(alldata.data))
	{
		$scope.allrirs=alldata.data.allbcs;
		$scope.totalitems=alldata.data.totalitems;
		var  exportdata =alldata.data.allbcs;
		console.log(alldata.data);
		$scope.head=exportdata[0];
		
	
	}
	
	
	$scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		//console.log(pageno);
		$http({	
					method:'GET',
					url:$rootScope.apiurl+'api/bcregdetails/30/'+pageno,	
	     	 
	     		}).then(function successCallback(data) {
					//console.log(data);
				$scope.allrirs=data.allbcs;
		$scope.totalitems=data.totalitems;
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			console.log(data);
					$scope.flags.loadingdata=false;
	     		});
		
	}
	
$scope.getHeader=function()
{
	//console.log($scope.head);
	return _.keys($scope.head);
}


$scope.exportArray=function(){
	$scope.flags.loadingdata=true;
	 var deferred = $q.defer();
        $http({method: 'GET',	url:$rootScope.apiurl+'api/bcregdetails/'}).then(function (data) {
			//console.log(data);
			var temp=data.data.allbcs;
			//console.log(data.data.allbcs);
			$scope.flags.loadingdata=false;
                deferred.resolve(temp);
            }, function (errorData) {
			//	console.log(errorData);
			$scope.flags.loadingdata=false;
            deferred.reject(errorData);
        });
        return deferred.promise;
	
	
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
					url:$rootScope.apiurl+'api/searchbcreg/',	
					data:{text:searchtext}
	     	 
	     		}).then(function successCallback(data) {
					//console.log(data);
				$scope.allrirs=data.allrirs;
		$scope.totalitems=0;//data.totalitems;
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
   
});