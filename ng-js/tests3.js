
App.controller('grainsizeController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.allrirs=[];
	$scope.animationsEnabled = true;
	 $scope.pageSize=30;
	$scope.currentPage=1;
	if(angular.isDefined(alldata.data))
	{
		$scope.allrirs=alldata.data.allrirs;
		$scope.totalitems=alldata.data.totalitems;
		$scope.allobsunit=alldata.data.allobsunit;
		$scope.getperm=_.findWhere($scope.permissions,{SectionId:'35'});
		////console.log(alldata.data);
	}
	
	
	$scope.showunit=function(id)
	{
		return _.findWhere($scope.allobsunit,{Id:id}).Unit;
	}
	
  $scope.editresult=function(param,id)
  {
	////console.log(param);
		$state.go('app.grainsizeadd',{id:id});
	
  }
  
   $scope.showrir = function (size,rir) {

$scope.grainsize=angular.copy(rir);   
////console.log($scope.grainsize);
   $scope.modalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'templates/tests/grain.html',
      scope:$scope,
      size: size,
     
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
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance2.dismiss('cancel');
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
		       		 url:$rootScope.apiurl+'api/approvetest/'+id,
					 data:{ApprovedBy:$rootScope.dboard.uid,SectionId:4}
		        
		   		 }).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal3();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}
	
	
	$scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		////console.log(pageno);
		$http({	
					method:'GET',
					url:$rootScope.apiurl+'newapi/grainsizedata/30/'+pageno,	
	     	 
	     		}).then(function successCallback(data) {
					////console.log(data);
				$scope.allrirs=data.data.allrirs;
		$scope.totalitems=data.data.totalitems;
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			//console.log(data);
					$scope.flags.loadingdata=false;
	     		});
		
	}

	$scope.searchdata=function(searchtext)
	{
		if(!_.isEmpty(searchtext))
		{
			$scope.flags.loadingdata=true;
			var pageno=1;
				////console.log(pageno);
				$http({	
							method:'POST',
							url:$rootScope.apiurl+'newapi/searchgrainsize/',	
							data:{text:searchtext,pageSize:$scope.pageSize}
					 
						}).then(function successCallback(data) {
							////console.log(data);
							$scope.pageSize=75;
						$scope.allrirs=data.data.allrirs;
				$scope.totalitems=0;//data.totalitems;
				$scope.flags.loadingdata=false;
										
						}, function errorCallback(data) {
							//console.log(data);
							$scope.flags.loadingdata=false;
						});
		}

	}

$scope.refreshdata=function()
{
	$state.reload();//('app.bcregdetails',{});
	
}
  
});

App.controller('grainsizeaddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';

	$scope.grainsize={};
	$scope.editflag=false;
	$scope.flags={};	
	$scope.flags.observation=true;
	$scope.flags.oneclick=false;
	$scope.remarks=['Passed','Failed'];
	$scope.loadingFiles = true;	
	$scope.path='images/testuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	
	 $scope.dateOptions = {
    //customClass: getDayClass,
   // minDate: new Date(),
    showWeeks: true
  };
	
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
	
	
	if(angular.isDefined(allpredata.data))
	{
		$scope.grainsize.basic=allpredata.data.basic;
		$scope.allmangs=allpredata.data.magnifications;
		$scope.alltestmethods=allpredata.data.alltestmethods;
		$scope.allobsunit=allpredata.data.allobsunit;
			//console.log($scope.grainsize.basic);
			
			if(!_.isEmpty($scope.grainsize.basic.TestDate))
			{
			$scope.grainsize.basic.TestDate=new Date($scope.grainsize.basic.TestDate);
			}else
		{
			$scope.grainsize.basic.TestDate=new Date();	
		}
		
		$scope.loadingFiles = true;
		$http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = true;
							 
						var allfiles=response.data.files;
						////console.log(response);
					if(allfiles.length>0)
					{
						
						angular.forEach(allfiles,function(file)
							{
								if(angular.isDefined(file.rirtestid) && parseInt(file.rirtestid)===parseInt($scope.grainsize.basic.Id))
								{
									file.rirtestid=file.rirtestid.toString();
									$scope.queue.push(file);
									
								}
								
							});
							
							
					}
					
										//	//console.log(  $scope.queue);
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );
		
	}
	
	
	$scope.saveupload=function()
	{
		
		var file=$scope.queue[0];
		////console.log(file);
		//var find=_.findWhere();
		if(angular.isUndefined(file.id))
		{
		file=_.extend(file,{"rirtestid":$scope.grainsize.basic.Id});
		var result=file.$submit();
				//	//console.log(result);
		}
		else
		{
			$state.go('app.grainsize');
		}
	}
	
		$scope.$on('fileuploadstop', function()
	{
		$state.go('app.grainsize');
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {rirtestid: data.files[0].rirtestid};
 
});

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
            if (file.url)
                    {
                
                        file.$destroy = function () {
                            state = 'pending';
                            return $http({
                                url: file.deleteUrl,
                                method: file.deleteType
                            }).then(
                               function () {
                                    state = 'resolved';
                                    //$scope.clear(file); 
                                    
                                },
                                function () {
                                    state = 'rejected';
                                //    //console.log(state);
                                }
                            );
                        };
                        file.$destroy();
                        
                    }
	
			$scope.queue.splice(idx,1);
		}
	}
	
	
    $scope.cancelsave=function()
	{
		$state.go('app.grainsize');
	}  
   
   
   
  $scope.savegrainsizetest=function(grainsize)
  {
	//console.log(grainsize);  
		$scope.flags.oneclick=true;
		grainsize.ModifiedBy=$rootScope.dboard.uid;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'newapi/grainsizeupdate/'+grainsize.basic.Id,
					data:grainsize					
				}).then(function successCallback(data) {
				////console.log(data);
			 	$scope.flags.oneclick=false;
				if($scope.queue.length>0)
				{
					$scope.saveupload();
				}
				else
				{
					$state.go('app.grainsize');
				}
				
				
			 	
					}, function errorCallback(data) {
					//console.log(data);
		$scope.flags.oneclick=false;
				}); 
  }
  
});


App.controller('microstructController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.allrirs=[];
	$scope.animationsEnabled = true;
	 $scope.pageSize=30;
	$scope.currentPage=1;
	if(angular.isDefined(alldata.data))
	{
		$scope.allrirs=alldata.data.allrirs;
		$scope.totalitems=alldata.data.totalitems;
		////console.log(alldata.data);
		$scope.getperm=_.findWhere($scope.permissions,{SectionId:'36'});
	}
	
  $scope.editresult=function(param,id)
  {
	////console.log(param);
		$state.go('app.microstructadd',{id:id});
	
  }
  
   $scope.showrir = function (size,rir) {

$scope.microstruct=angular.copy(rir);   
console.log($scope.microstruct);
   $scope.modalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'templates/tests/microstruct.html',
      scope:$scope,
      size: size,
     
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
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance2.dismiss('cancel');
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
		       		 url:$rootScope.apiurl+'api/approvetest/'+id,
					 data:{ApprovedBy:$rootScope.dboard.uid,SectionId:4}
		        
		   		 }).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal3();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}
	
	
	$scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		////console.log(pageno);
		$http({	
					method:'GET',
					url:$rootScope.apiurl+'newapi/microstructdata/30/'+pageno,	
	     	 
	     		}).then(function successCallback(data) {
					////console.log(data);
				$scope.allrirs=data.allrirs;
		$scope.totalitems=data.totalitems;
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			//console.log(data);
					$scope.flags.loadingdata=false;
	     		});
		
	}

	$scope.searchdata=function(searchtext)
	{
		if(!_.isEmpty(searchtext))
		{
			$scope.flags.loadingdata=true;
			var pageno=1;
				////console.log(pageno);
				$http({	
							method:'POST',
							url:$rootScope.apiurl+'newapi/searchmicrostruct/',	
							data:{text:searchtext,pageSize:$scope.pageSize}
					 
						}).then(function successCallback(data) {
							////console.log(data);
							$scope.pageSize=75;
						$scope.allrirs=data.allrirs;
				$scope.totalitems=0;//data.totalitems;
				$scope.flags.loadingdata=false;
										
						}, function errorCallback(data) {
							//console.log(data);
							$scope.flags.loadingdata=false;
						});
		}

	}

$scope.refreshdata=function()
{
	$state.reload();//('app.bcregdetails',{});
	
}
  
});

App.controller('microstructaddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';

	$scope.microstruct={};
	$scope.editflag=false;
	$scope.flags={};	
	$scope.flags.observation=true;
	$scope.flags.oneclick=false;
	$scope.remarks=['Passed','Failed'];
	$scope.loadingFiles = true;	
	$scope.path='images/testuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	
	 $scope.dateOptions = {
    //customClass: getDayClass,
   // minDate: new Date(),
    showWeeks: true
  };
	
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
	
	
	if(angular.isDefined(allpredata.data))
	{
		$scope.microstruct.basic=allpredata.data.basic;
		$scope.allmangs=allpredata.data.magnifications;
		$scope.alltestmethods=allpredata.data.alltestmethods;
		
			//console.log($scope.microstruct.basic);
			
			if(!_.isEmpty($scope.microstruct.basic.TestDate))
			{
			$scope.microstruct.basic.TestDate=new Date($scope.microstruct.basic.TestDate);
			}else
		{
			$scope.microstruct.basic.TestDate=new Date();	
		}
		
		$scope.loadingFiles = true;
		$http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = true;
							 
						var allfiles=response.data.files;
						////console.log(response);
					if(allfiles.length>0)
					{
						
						angular.forEach(allfiles,function(file)
							{
								if(angular.isDefined(file.rirtestid) && parseInt(file.rirtestid)===parseInt($scope.microstruct.basic.Id))
								{
									file.rirtestid=file.rirtestid.toString();
									$scope.queue.push(file);
									
								}
								
							});
							
							
					}
					
										//	//console.log(  $scope.queue);
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );
		
	}
	
	
	$scope.saveupload=function()
	{
		
		var file=$scope.queue[0];
		////console.log(file);
		//var find=_.findWhere();
		if(angular.isUndefined(file.id))
		{
		file=_.extend(file,{"rirtestid":$scope.microstruct.basic.Id});
		var result=file.$submit();
				//	//console.log(result);
		}
		else
		{
			$state.go('app.microstruct');
		}
	}
	
		$scope.$on('fileuploadstop', function()
	{
		$state.go('app.microstruct');
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {rirtestid: data.files[0].rirtestid};
 
});

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
            if (file.url)
                    {
                
                        file.$destroy = function () {
                            state = 'pending';
                            return $http({
                                url: file.deleteUrl,
                                method: file.deleteType
                            }).then(
                               function () {
                                    state = 'resolved';
                                    //$scope.clear(file); 
                                    
                                },
                                function () {
                                    state = 'rejected';
                                //    //console.log(state);
                                }
                            );
                        };
                        file.$destroy();
                        
                    }
	
			$scope.queue.splice(idx,1);
		}
	}
	
	
    $scope.cancelsave=function()
	{
		$state.go('app.microstruct');
	}  
   
   
   
  $scope.savemicrostructtest=function(microstruct)
  {
	//console.log(microstruct);  
		$scope.flags.oneclick=true;
		microstruct.ModifiedBy=$rootScope.dboard.uid;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'newapi/microstructupdate/'+microstruct.basic.Id,
					data:microstruct					
				}).then(function successCallback(data) {
				////console.log(data);
			 	$scope.flags.oneclick=false;
				if($scope.queue.length>0)
				{
					$scope.saveupload();
				}
				else
				{
					$state.go('app.microstruct');
				}
				
				
			 	
					}, function errorCallback(data) {
					//console.log(data);
		$scope.flags.oneclick=false;
				}); 
  }
  
});



App.controller('microcoatController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.allrirs=[];
	$scope.animationsEnabled = true;
	 $scope.pageSize=30;
	$scope.currentPage=1;
	if(angular.isDefined(alldata.data))
	{
		$scope.allrirs=alldata.data.allrirs;
		$scope.totalitems=alldata.data.totalitems;
		$scope.allobsunit=alldata.data.allobsunit;
		$scope.getperm=_.findWhere($scope.permissions,{SectionId:'41'});
		////console.log(alldata.data);
	}
	
	
	$scope.showunit=function(id)
	{
		return _.findWhere($scope.allobsunit,{Id:id}).Unit;
	}
	
  $scope.editresult=function(param,id)
  {
	////console.log(param);
		$state.go('app.microcoatadd',{id:id});
	
  }
  
   $scope.showrir = function (size,rir) {

$scope.microcoat=angular.copy(rir);   
////console.log($scope.microcoat);
   $scope.modalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'templates/tests/microcoat.html',
      scope:$scope,
      size: size,
     
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
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance2.dismiss('cancel');
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
		       		 url:$rootScope.apiurl+'api/approvetest/'+id,
					 data:{ApprovedBy:$rootScope.dboard.uid,SectionId:4}
		        
		   		 }).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal3();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}
	
	
	$scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		////console.log(pageno);
		$http({	
					method:'GET',
					url:$rootScope.apiurl+'newapi/microcoatdata/30/'+pageno,	
	     	 
	     		}).then(function successCallback(data) {
					////console.log(data);
				$scope.allrirs=data.allrirs;
		$scope.totalitems=data.totalitems;
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			//console.log(data);
					$scope.flags.loadingdata=false;
	     		});
		
	}

	$scope.searchdata=function(searchtext)
	{
		if(!_.isEmpty(searchtext))
		{
			$scope.flags.loadingdata=true;
			var pageno=1;
				////console.log(pageno);
				$http({	
							method:'POST',
							url:$rootScope.apiurl+'newapi/searchmicrocoat/',	
							data:{text:searchtext,pageSize:$scope.pageSize}
					 
						}).then(function successCallback(data) {
							////console.log(data);
							$scope.pageSize=75;
						$scope.allrirs=data.allrirs;
				$scope.totalitems=0;//data.totalitems;
				$scope.flags.loadingdata=false;
										
						}, function errorCallback(data) {
							//console.log(data);
							$scope.flags.loadingdata=false;
						});
		}

	}

$scope.refreshdata=function()
{
	$state.reload();//('app.bcregdetails',{});
	
}
  
});

App.controller('microcoataddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';

	$scope.microcoat={};
	$scope.editflag=false;
	$scope.flags={};	
	$scope.flags.observation=true;
	$scope.flags.oneclick=false;
	$scope.remarks=['Passed','Failed'];
	$scope.loadingFiles = true;	
	$scope.path='images/testuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	
	 $scope.dateOptions = {
    //customClass: getDayClass,
   // minDate: new Date(),
    showWeeks: true
  };
	
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
	
	
	if(angular.isDefined(allpredata.data))
	{
		$scope.microcoat.basic=allpredata.data.basic;
		$scope.allmangs=allpredata.data.magnifications;
		$scope.alltestmethods=allpredata.data.alltestmethods;
		$scope.allobsunit=allpredata.data.allobsunit;
			//console.log($scope.microcoat.basic);
			
			if(!_.isEmpty($scope.microcoat.basic.TestDate))
			{
			$scope.microcoat.basic.TestDate=new Date($scope.microcoat.basic.TestDate);
			}else
		{
			$scope.microcoat.basic.TestDate=new Date();	
		}
		
		$scope.loadingFiles = true;
		$http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = true;
							 
						var allfiles=response.data.files;
						////console.log(response);
					if(allfiles.length>0)
					{
						
						angular.forEach(allfiles,function(file)
							{
								if(angular.isDefined(file.rirtestid) && parseInt(file.rirtestid)===parseInt($scope.microcoat.basic.Id))
								{
									file.rirtestid=file.rirtestid.toString();
									$scope.queue.push(file);
									
								}
								
							});
							
							
					}
					
										//	//console.log(  $scope.queue);
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );
		
	}
	
	$scope.getaverage=function()
	{
		var obs1=0;
		var obs2=0;
		var obs3=0;
		var obs4=0;
		var obs5=0;
		if(!_.isEmpty($scope.microcoat.basic.obbasic.Obs1))
		{
			obs1=parseFloat($scope.microcoat.basic.obbasic.Obs1);
		}
		
		if(!_.isEmpty($scope.microcoat.basic.obbasic.Obs2))
		{
			obs2=parseFloat($scope.microcoat.basic.obbasic.Obs2);
		}
		if(!_.isEmpty($scope.microcoat.basic.obbasic.Obs3))
		{
			obs3=parseFloat($scope.microcoat.basic.obbasic.Obs3);
		}
		if(!_.isEmpty($scope.microcoat.basic.obbasic.Obs4))
		{
			obs4=parseFloat($scope.microcoat.basic.obbasic.Obs4);
		}
		
		if(!_.isEmpty($scope.microcoat.basic.obbasic.Obs5))
		{
			obs5=parseFloat($scope.microcoat.basic.obbasic.Obs5);
		}
		
		var avg=(parseFloat(obs1)+parseFloat(obs2)+parseFloat(obs3)+parseFloat(obs4)+parseFloat(obs5))/5;
		$scope.microcoat.basic.obbasic.ObsAvg=avg;
		return avg;
		
	}
	
	
	$scope.saveupload=function()
	{
		
		var file=$scope.queue[0];
		////console.log(file);
		//var find=_.findWhere();
		if(angular.isUndefined(file.id))
		{
		file=_.extend(file,{"rirtestid":$scope.microcoat.basic.Id});
		var result=file.$submit();
				//	//console.log(result);
		}
		else
		{
			$state.go('app.microcoat');
		}
	}
	
		$scope.$on('fileuploadstop', function()
	{
		$state.go('app.microcoat');
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {rirtestid: data.files[0].rirtestid};
 
});

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
            if (file.url)
                    {
                
                        file.$destroy = function () {
                            state = 'pending';
                            return $http({
                                url: file.deleteUrl,
                                method: file.deleteType
                            }).then(
                               function () {
                                    state = 'resolved';
                                    //$scope.clear(file); 
                                    
                                },
                                function () {
                                    state = 'rejected';
                                //    //console.log(state);
                                }
                            );
                        };
                        file.$destroy();
                        
                    }
	
			$scope.queue.splice(idx,1);
		}
	}
	
	
    $scope.cancelsave=function()
	{
		$state.go('app.microcoat');
	}  
   
   
   
  $scope.savemicrocoattest=function(microcoat)
  {
	//console.log(microcoat);  
		$scope.flags.oneclick=true;
		microcoat.ModifiedBy=$rootScope.dboard.uid;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'newapi/microcoatupdate/'+microcoat.basic.Id,
					data:microcoat					
				}).then(function successCallback(data) {
				////console.log(data);
			 	$scope.flags.oneclick=false;
				if($scope.queue.length>0)
				{
					$scope.saveupload();
				}
				else
				{
					$state.go('app.microcoat');
				}
				
				
			 	
					}, function errorCallback(data) {
					//console.log(data);
		$scope.flags.oneclick=false;
				}); 
  }
  
});



App.controller('microcasedepthController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.allrirs=[];
	$scope.animationsEnabled = true;
	 $scope.pageSize=30;
	$scope.currentPage=1;
	if(angular.isDefined(alldata.data))
	{
		$scope.allrirs=alldata.data.allrirs;
		$scope.totalitems=alldata.data.totalitems;
		$scope.allobsunit=alldata.data.allobsunit;
		$scope.getperm=_.findWhere($scope.permissions,{SectionId:'40'});
		////console.log(alldata.data);
	}
	
	
	$scope.showunit=function(id)
	{
		return _.findWhere($scope.allobsunit,{Id:id}).Unit;
	}
	
  $scope.editresult=function(param,id)
  {
	////console.log(param);
		$state.go('app.microcasedepthadd',{id:id});
	
  }
  
   $scope.showrir = function (size,rir) {

$scope.microcasedepth=angular.copy(rir);   
////console.log($scope.microcasedepth);
   $scope.modalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'templates/tests/microcasedepth.html',
      scope:$scope,
      size: size,
     
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
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance2.dismiss('cancel');
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
		       		 url:$rootScope.apiurl+'api/approvetest/'+id,
					 data:{ApprovedBy:$rootScope.dboard.uid,SectionId:4}
		        
		   		 }).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal3();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}
	
	
	$scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		////console.log(pageno);
		$http({	
					method:'GET',
					url:$rootScope.apiurl+'newapi/microcasedepthdata/30/'+pageno,	
	     	 
	     		}).then(function successCallback(data) {
					////console.log(data);
				$scope.allrirs=data.allrirs;
		$scope.totalitems=data.totalitems;
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			//console.log(data);
					$scope.flags.loadingdata=false;
	     		});
		
	}

	$scope.searchdata=function(searchtext)
	{
		if(!_.isEmpty(searchtext))
		{
			$scope.flags.loadingdata=true;
			var pageno=1;
				////console.log(pageno);
				$http({	
							method:'POST',
							url:$rootScope.apiurl+'newapi/searchmicrocasedepth/',	
							data:{text:searchtext,pageSize:$scope.pageSize}
					 
						}).then(function successCallback(data) {
							////console.log(data);
							$scope.pageSize=75;
						$scope.allrirs=data.allrirs;
				$scope.totalitems=0;//data.totalitems;
				$scope.flags.loadingdata=false;
										
						}, function errorCallback(data) {
							//console.log(data);
							$scope.flags.loadingdata=false;
						});
		}

	}

$scope.refreshdata=function()
{
	$state.reload();//('app.bcregdetails',{});
	
}
  
});

App.controller('microcasedepthaddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';

	$scope.microcasedepth={};
	$scope.editflag=false;
	$scope.flags={};	
	$scope.flags.observation=true;
	$scope.flags.oneclick=false;
	$scope.remarks=['Passed','Failed'];
	$scope.loadingFiles = true;	
	$scope.path='images/testuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	
	 $scope.dateOptions = {
    //customClass: getDayClass,
   // minDate: new Date(),
    showWeeks: true
  };
	
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
	
	
	if(angular.isDefined(allpredata.data))
	{
		$scope.microcasedepth.basic=allpredata.data.basic;
		$scope.allmangs=allpredata.data.magnifications;
		$scope.alltestmethods=allpredata.data.alltestmethods;
		$scope.allobsunit=allpredata.data.allobsunit;
			//console.log($scope.microcasedepth.basic);
			
			if(!_.isEmpty($scope.microcasedepth.basic.TestDate))
			{
			$scope.microcasedepth.basic.TestDate=new Date($scope.microcasedepth.basic.TestDate);
			}else
		{
			$scope.microcasedepth.basic.TestDate=new Date();	
		}
		
		$scope.loadingFiles = true;
		$http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = true;
							 
						var allfiles=response.data.files;
						////console.log(response);
					if(allfiles.length>0)
					{
						
						angular.forEach(allfiles,function(file)
							{
								if(angular.isDefined(file.rirtestid) && parseInt(file.rirtestid)===parseInt($scope.microcasedepth.basic.Id))
								{
									file.rirtestid=file.rirtestid.toString();
									$scope.queue.push(file);
									
								}
								
							});
							
							
					}
					
										//	//console.log(  $scope.queue);
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );
		
	}
	
	$scope.getaverage=function()
	{
		var obs1=0;
		var obs2=0;
		var obs3=0;
		var obs4=0;
		var obs5=0;
		if(!_.isEmpty($scope.microcasedepth.basic.obbasic.Obs1))
		{
			obs1=parseFloat($scope.microcasedepth.basic.obbasic.Obs1);
		}
		
		if(!_.isEmpty($scope.microcasedepth.basic.obbasic.Obs2))
		{
			obs2=parseFloat($scope.microcasedepth.basic.obbasic.Obs2);
		}
		if(!_.isEmpty($scope.microcasedepth.basic.obbasic.Obs3))
		{
			obs3=parseFloat($scope.microcasedepth.basic.obbasic.Obs3);
		}
		if(!_.isEmpty($scope.microcasedepth.basic.obbasic.Obs4))
		{
			obs4=parseFloat($scope.microcasedepth.basic.obbasic.Obs4);
		}
		
		if(!_.isEmpty($scope.microcasedepth.basic.obbasic.Obs5))
		{
			obs5=parseFloat($scope.microcasedepth.basic.obbasic.Obs5);
		}
		
		var avg=(parseFloat(obs1)+parseFloat(obs2)+parseFloat(obs3)+parseFloat(obs4)+parseFloat(obs5))/5;
		$scope.microcasedepth.basic.obbasic.ObsAvg=avg;
		return avg;
		
	}
	
	
	
	$scope.saveupload=function()
	{
		
		var file=$scope.queue[0];
		////console.log(file);
		//var find=_.findWhere();
		if(angular.isUndefined(file.id))
		{
		file=_.extend(file,{"rirtestid":$scope.microcasedepth.basic.Id});
		var result=file.$submit();
				//	//console.log(result);
		}
		else
		{
			$state.go('app.microcasedepth');
		}
	}
	
		$scope.$on('fileuploadstop', function()
	{
		$state.go('app.microcasedepth');
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {rirtestid: data.files[0].rirtestid};
 
});

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
            if (file.url)
                    {
                
                        file.$destroy = function () {
                            state = 'pending';
                            return $http({
                                url: file.deleteUrl,
                                method: file.deleteType
                            }).then(
                               function () {
                                    state = 'resolved';
                                    //$scope.clear(file); 
                                    
                                },
                                function () {
                                    state = 'rejected';
                                //    //console.log(state);
                                }
                            );
                        };
                        file.$destroy();
                        
                    }
	
			$scope.queue.splice(idx,1);
		}
	}
	
	
    $scope.cancelsave=function()
	{
		$state.go('app.microcasedepth');
	}  
   
   
   
  $scope.savemicrocasedepthtest=function(microcasedepth)
  {
	//console.log(microcasedepth);  
		$scope.flags.oneclick=true;
		microcasedepth.ModifiedBy=$rootScope.dboard.uid;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'newapi/microcasedepthupdate/'+microcasedepth.basic.Id,
					data:microcasedepth					
				}).then(function successCallback(data) {
				////console.log(data);
			 	$scope.flags.oneclick=false;
				if($scope.queue.length>0)
				{
					$scope.saveupload();
				}
				else
				{
					$state.go('app.microcasedepth');
				}
				
				
			 	
					}, function errorCallback(data) {
					//console.log(data);
		$scope.flags.oneclick=false;
				}); 
  }
  
});



App.controller('microdecarbController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.allrirs=[];
	$scope.animationsEnabled = true;
	 $scope.pageSize=30;
	$scope.currentPage=1;
	if(angular.isDefined(alldata.data))
	{
		$scope.allrirs=alldata.data.allrirs;
		$scope.totalitems=alldata.data.totalitems;
		$scope.allobsunit=alldata.data.allobsunit;
		$scope.getperm=_.findWhere($scope.permissions,{SectionId:'39'});
		////console.log(alldata.data);
	}
	
	
	$scope.showunit=function(id)
	{
		return _.findWhere($scope.allobsunit,{Id:id}).Unit;
	}
	
  $scope.editresult=function(param,id)
  {
	////console.log(param);
		$state.go('app.microdecarbadd',{id:id});
	
  }
  
   $scope.showrir = function (size,rir) {

$scope.microdecarb=angular.copy(rir);   
////console.log($scope.microdecarb);
   $scope.modalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'templates/tests/microdecarb.html',
      scope:$scope,
      size: size,
     
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
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance2.dismiss('cancel');
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
		       		 url:$rootScope.apiurl+'api/approvetest/'+id,
					 data:{ApprovedBy:$rootScope.dboard.uid,SectionId:4}
		        
		   		 }).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal3();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}
	
	
	$scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		////console.log(pageno);
		$http({	
					method:'GET',
					url:$rootScope.apiurl+'newapi/microdecarbdata/30/'+pageno,	
	     	 
	     		}).then(function successCallback(data) {
					////console.log(data);
				$scope.allrirs=data.allrirs;
		$scope.totalitems=data.totalitems;
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			//console.log(data);
					$scope.flags.loadingdata=false;
	     		});
		
	}

	$scope.searchdata=function(searchtext)
	{
		if(!_.isEmpty(searchtext))
		{
			$scope.flags.loadingdata=true;
			var pageno=1;
				////console.log(pageno);
				$http({	
							method:'POST',
							url:$rootScope.apiurl+'newapi/searchmicrodecarb/',	
							data:{text:searchtext,pageSize:$scope.pageSize}
					 
						}).then(function successCallback(data) {
							////console.log(data);
							$scope.pageSize=75;
						$scope.allrirs=data.allrirs;
				$scope.totalitems=0;//data.totalitems;
				$scope.flags.loadingdata=false;
										
						}, function errorCallback(data) {
							//console.log(data);
							$scope.flags.loadingdata=false;
						});
		}

	}

$scope.refreshdata=function()
{
	$state.reload();//('app.bcregdetails',{});
	
}
  
});

App.controller('microdecarbaddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';

	$scope.microdecarb={};
	$scope.editflag=false;
	$scope.flags={};	
	$scope.flags.observation=true;
	$scope.flags.oneclick=false;
	$scope.remarks=['Passed','Failed'];
	$scope.loadingFiles = true;	
	$scope.path='images/testuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	
	 $scope.dateOptions = {
    //customClass: getDayClass,
   // minDate: new Date(),
    showWeeks: true
  };
	
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
	
	
	if(angular.isDefined(allpredata.data))
	{
		$scope.microdecarb.basic=allpredata.data.basic;
		$scope.allmangs=allpredata.data.magnifications;
		$scope.alltestmethods=allpredata.data.alltestmethods;
		$scope.allobsunit=allpredata.data.allobsunit;
			//console.log($scope.microdecarb.basic);
			
			if(!_.isEmpty($scope.microdecarb.basic.TestDate))
			{
			$scope.microdecarb.basic.TestDate=new Date($scope.microdecarb.basic.TestDate);
			}else
		{
			$scope.microdecarb.basic.TestDate=new Date();	
		}
		
		$scope.loadingFiles = true;
		$http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = true;
							 
						var allfiles=response.data.files;
						////console.log(response);
					if(allfiles.length>0)
					{
						
						angular.forEach(allfiles,function(file)
							{
								if(angular.isDefined(file.rirtestid) && parseInt(file.rirtestid)===parseInt($scope.microdecarb.basic.Id))
								{
									file.rirtestid=file.rirtestid.toString();
									$scope.queue.push(file);
									
								}
								
							});
							
							
					}
					
										//	//console.log(  $scope.queue);
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );
		
	}
	
	$scope.getaverage=function()
	{
		var obs1=0;
		var obs2=0;
		var obs3=0;
		var obs4=0;
		var obs5=0;
		if(!_.isEmpty($scope.microdecarb.basic.obbasic.Obs1))
		{
			obs1=parseFloat($scope.microdecarb.basic.obbasic.Obs1);
		}
		
		if(!_.isEmpty($scope.microdecarb.basic.obbasic.Obs2))
		{
			obs2=parseFloat($scope.microdecarb.basic.obbasic.Obs2);
		}
		if(!_.isEmpty($scope.microdecarb.basic.obbasic.Obs3))
		{
			obs3=parseFloat($scope.microdecarb.basic.obbasic.Obs3);
		}
		if(!_.isEmpty($scope.microdecarb.basic.obbasic.Obs4))
		{
			obs4=parseFloat($scope.microdecarb.basic.obbasic.Obs4);
		}
		
		if(!_.isEmpty($scope.microdecarb.basic.obbasic.Obs5))
		{
			obs5=parseFloat($scope.microdecarb.basic.obbasic.Obs5);
		}
		
		var avg=(parseFloat(obs1)+parseFloat(obs2)+parseFloat(obs3)+parseFloat(obs4)+parseFloat(obs5))/5;
		$scope.microdecarb.basic.obbasic.ObsAvg=avg;
		return avg;
		
	}
	
	
	$scope.saveupload=function()
	{
		
		var file=$scope.queue[0];
		////console.log(file);
		//var find=_.findWhere();
		if(angular.isUndefined(file.id))
		{
		file=_.extend(file,{"rirtestid":$scope.microdecarb.basic.Id});
		var result=file.$submit();
				//	//console.log(result);
		}
		else
		{
			$state.go('app.microdecarb');
		}
	}
	
		$scope.$on('fileuploadstop', function()
	{
		$state.go('app.microdecarb');
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {rirtestid: data.files[0].rirtestid};
 
});

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
            if (file.url)
                    {
                
                        file.$destroy = function () {
                            state = 'pending';
                            return $http({
                                url: file.deleteUrl,
                                method: file.deleteType
                            }).then(
                               function () {
                                    state = 'resolved';
                                    //$scope.clear(file); 
                                    
                                },
                                function () {
                                    state = 'rejected';
                                //    //console.log(state);
                                }
                            );
                        };
                        file.$destroy();
                        
                    }
	
			$scope.queue.splice(idx,1);
		}
	}
	
	
    $scope.cancelsave=function()
	{
		$state.go('app.microdecarb');
	}  
   
   
   
  $scope.savemicrodecarbtest=function(microdecarb)
  {
	//console.log(microdecarb);  
		$scope.flags.oneclick=true;
		microdecarb.ModifiedBy=$rootScope.dboard.uid;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'newapi/microdecarbupdate/'+microdecarb.basic.Id,
					data:microdecarb					
				}).then(function successCallback(data) {
				////console.log(data);
			 	$scope.flags.oneclick=false;
				if($scope.queue.length>0)
				{
					$scope.saveupload();
				}
				else
				{
					$state.go('app.microdecarb');
				}
				
				
			 	
					}, function errorCallback(data) {
					//console.log(data);
		$scope.flags.oneclick=false;
				}); 
  }
  
});



App.controller('kinclratingController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.allrirs=[];
	$scope.animationsEnabled = true;
	 $scope.pageSize=30;
	$scope.currentPage=1;
	if(angular.isDefined(alldata.data))
	{
		$scope.allrirs=alldata.data.allrirs;
		$scope.totalitems=alldata.data.totalitems;
		$scope.getperm=_.findWhere($scope.permissions,{SectionId:'37'});
		//$scope.allobsunit=alldata.data.allobsunit;
		////console.log(alldata.data);
	}
	
	
	// $scope.showunit=function(id)
	// {
		// return _.findWhere($scope.allobsunit,{Id:id}).Unit;
	// }
	
  $scope.editresult=function(param,id)
  {
	////console.log(param);
		$state.go('app.kinclratingadd',{id:id});
	
  }
  
   $scope.showrir = function (size,rir) {

$scope.kinclrating=angular.copy(rir);   
////console.log($scope.kinclrating);
   $scope.modalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'templates/tests/kinclrating.html',
      scope:$scope,
      size: size,
     
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
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance2.dismiss('cancel');
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
		       		 url:$rootScope.apiurl+'api/approvetest/'+id,
					 data:{ApprovedBy:$rootScope.dboard.uid,SectionId:4}
		        
		   		 }).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal3();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}
	
	
	$scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		////console.log(pageno);
		$http({	
					method:'GET',
					url:$rootScope.apiurl+'newapi/kinclratingdata/30/'+pageno,	
	     	 
	     		}).then(function successCallback(data) {
					////console.log(data);
				$scope.allrirs=data.allrirs;
		$scope.totalitems=data.totalitems;
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			//console.log(data);
					$scope.flags.loadingdata=false;
	     		});
		
	}

	$scope.searchdata=function(searchtext)
	{
		if(!_.isEmpty(searchtext))
		{
			$scope.flags.loadingdata=true;
			var pageno=1;
				////console.log(pageno);
				$http({	
							method:'POST',
							url:$rootScope.apiurl+'newapi/searchkinclrating/',	
							data:{text:searchtext,pageSize:$scope.pageSize}
					 
						}).then(function successCallback(data) {
							////console.log(data);
							$scope.pageSize=75;
						$scope.allrirs=data.allrirs;
				$scope.totalitems=0;//data.totalitems;
				$scope.flags.loadingdata=false;
										
						}, function errorCallback(data) {
							//console.log(data);
							$scope.flags.loadingdata=false;
						});
		}

	}

$scope.refreshdata=function()
{
	$state.reload();//('app.bcregdetails',{});
	
}
  
});

App.controller('kinclratingaddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';

	$scope.kinclrating={};
	$scope.editflag=false;
	$scope.flags={};	
	$scope.flags.observation=true;
	$scope.flags.oneclick=false;
	$scope.remarks=['Passed','Failed'];
	$scope.loadingFiles = true;	
	$scope.path='images/testuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	
	 $scope.dateOptions = {
    //customClass: getDayClass,
   // minDate: new Date(),
    showWeeks: true
  };
	
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
	
	
	if(angular.isDefined(allpredata.data))
	{
		$scope.kinclrating.basic=allpredata.data.basic;
		$scope.allmangs=allpredata.data.magnifications;
		$scope.alltestmethods=allpredata.data.alltestmethods;
		$scope.allobsunit=allpredata.data.allobsunit;
		 $scope.kinclrating.basic.delobs=[];
			//console.log($scope.kinclrating.basic);
			
			if(!_.isEmpty($scope.kinclrating.basic.TestDate))
			{
			$scope.kinclrating.basic.TestDate=new Date($scope.kinclrating.basic.TestDate);
			}else
		{
			$scope.kinclrating.basic.TestDate=new Date();	
		}
		
		
		
		$scope.loadingFiles = true;
		$http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = true;
							 
						var allfiles=response.data.files;
						////console.log(response);
					if(allfiles.length>0)
					{
						
						angular.forEach(allfiles,function(file)
							{
								if(angular.isDefined(file.rirtestid) && parseInt(file.rirtestid)===parseInt($scope.kinclrating.basic.Id))
								{
									file.rirtestid=file.rirtestid.toString();
									$scope.queue.push(file);
									
								}
								
							});
							
							
					}
					
										//	//console.log(  $scope.queue);
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );
		
	}
	
	
	$scope.saveupload=function()
	{
		
		var file=$scope.queue[0];
		////console.log(file);
		//var find=_.findWhere();
		if(angular.isUndefined(file.id))
		{
		file=_.extend(file,{"rirtestid":$scope.kinclrating.basic.Id});
		var result=file.$submit();
				//	//console.log(result);
		}
		else
		{
			$state.go('app.kinclrating');
		}
	}
	
		$scope.$on('fileuploadstop', function()
	{
		$state.go('app.kinclrating');
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {rirtestid: data.files[0].rirtestid};
 
});

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
            if (file.url)
                    {
                
                        file.$destroy = function () {
                            state = 'pending';
                            return $http({
                                url: file.deleteUrl,
                                method: file.deleteType
                            }).then(
                               function () {
                                    state = 'resolved';
                                    //$scope.clear(file); 
                                    
                                },
                                function () {
                                    state = 'rejected';
                                //    //console.log(state);
                                }
                            );
                        };
                        file.$destroy();
                        
                    }
	
			$scope.queue.splice(idx,1);
		}
	}
	
	
    $scope.cancelsave=function()
	{
		$state.go('app.kinclrating');
	}  
   
   
   
  $scope.savekinclratingtest=function(kinclrating)
  {
	//console.log(kinclrating);  
		$scope.flags.oneclick=true;
		kinclrating.ModifiedBy=$rootScope.dboard.uid;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'newapi/kinclratingupdate/'+kinclrating.basic.Id,
					data:kinclrating					
				}).then(function successCallback(data) {
				////console.log(data);
			 	$scope.flags.oneclick=false;
				if($scope.queue.length>0)
				{
					$scope.saveupload();
				}
				else
				{
					$state.go('app.kinclrating');
				}
				
				
			 	
					}, function errorCallback(data) {
					//console.log(data);
		$scope.flags.oneclick=false;
				}); 
  }
  
  
  
  $scope.closeaddrowModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalnewrow.dismiss('cancel');
 	}
	
  
  $scope.addobs=function(param)
  {
	  //console.log(param);
	   $scope.row={
	   SpecNo:"",AreaPol:"",
	   obs:[
		{TypeOfInc:"SS",RatNo0:0,RatNo1:0,RatNo2:0,RatNo3:0,RatNo4:0,RatNo5:0,RatNo6:0,RatNo7:0,RatNo8:0,SStar:0},
		{TypeOfInc:"OA",RatNo0:0,RatNo1:0,RatNo2:0,RatNo3:0,RatNo4:0,RatNo5:0,RatNo6:0,RatNo7:0,RatNo8:0,OStar:0},
		{TypeOfInc:"OS",RatNo0:0,RatNo1:0,RatNo2:0,RatNo3:0,RatNo4:0,RatNo5:0,RatNo6:0,RatNo7:0,RatNo8:0,OStar:0},
		{TypeOfInc:"OG",RatNo0:0,RatNo1:0,RatNo2:0,RatNo3:0,RatNo4:0,RatNo5:0,RatNo6:0,RatNo7:0,RatNo8:0,OStar:0},
	   ]
	   
	   };
	  if(param==='new')
	  {
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
		  $scope.row=angular.copy(param);
		  //console.log($scope.row);
	  }
	 
	 
  }
  
  
  $scope.saveobs=function(row,param)
  {
	  //console.log(row);
	  if(param==='new')
	  {
		  row.SpecNo=$scope.kinclrating.basic.obbasic.Observations.length+1;
		  
		  $scope.kinclrating.basic.obbasic.Observations.push(row);
		   $scope.areapoltotal();
		$scope.closeaddrowModal();
	  }
	  else
	  {
		  var i=angular.copy(row.SpecNo-1);
		  $scope.kinclrating.basic.obbasic.Observations[i]=row;
		   $scope.areapoltotal();
		  $scope.closeaddrowModal();
	  }
	  
  }
  
  
  $scope.removeobs=function(item)
  {
	  //console.log(item);
	  if(angular.isDefined(item.Id))
	  {
		  $scope.kinclrating.basic.delobs.push(item);
	  }
	   var i=angular.copy(item.SpecNo-1);
	  $scope.kinclrating.basic.obbasic.Observations.splice(i,1);
	  var j=1;
	  angular.forEach( $scope.kinclrating.basic.obbasic.Observations,function(val){
		  
		  val.SpecNo=j;
		  j++;
	  });
  }
  
  $scope.areapoltotal=function()
  {
	  
	  var areatotal=0;
	  var stotal=0;
	  var ototal=0;
	    angular.forEach( $scope.kinclrating.basic.obbasic.Observations,function(val){
		  
		  areatotal=parseFloat(val.AreaPol)+parseFloat(areatotal);
		  
		  angular.forEach(val.obs,function(item){
			  
			  if(item.TypeOfInc==='SS')
			  {
				  stotal=parseFloat(item.SStar)+parseFloat(stotal);
			  }
			   if(item.TypeOfInc==='OA' || item.TypeOfInc==='OS' || item.TypeOfInc==='OG')
			  {
				  ototal=parseFloat(item.OStar)+parseFloat(ototal);
			  }
			   
		  });
		  
		 
	  });
	  $scope.kinclrating.basic.obbasic.Total=areatotal;
	  $scope.kinclrating.basic.obbasic.SSTotalS=stotal.toFixed(4);
	  $scope.kinclrating.basic.obbasic.SSTotalO=ototal.toFixed(4);
	  
	  var totalk3s=(parseFloat(stotal)*1000)/parseFloat(areatotal);
	    var totalk3o=(parseFloat(ototal)*1000)/parseFloat(areatotal);
	  
		$scope.kinclrating.basic.obbasic.TotalK3S=totalk3s.toFixed(4);
	    $scope.kinclrating.basic.obbasic.TotalK3O=totalk3o.toFixed(4);
		$scope.kinclrating.basic.obbasic.OverAllTotK3=parseFloat(totalk3o+totalk3s).toFixed(4);
	 // //console.log(stotal);
	  return areatotal;
	  
  }
  
});


App.controller('winclratingController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.allrirs=[];
	$scope.animationsEnabled = true;
	 $scope.pageSize=30;
	$scope.currentPage=1;
	if(angular.isDefined(alldata.data))
	{
		$scope.allrirs=alldata.data.allrirs;
		$scope.totalitems=alldata.data.totalitems;
		$scope.allobsunit=alldata.data.allobsunit;
		$scope.getperm=_.findWhere($scope.permissions,{SectionId:'38'});
		////console.log(alldata.data);
	}
	
	
	$scope.showunit=function(id)
	{
		return _.findWhere($scope.allobsunit,{Id:id}).Unit;
	}
	
  $scope.editresult=function(param,id)
  {
	////console.log(param);
		$state.go('app.winclratingadd',{id:id});
	
  }
  
   $scope.showrir = function (size,rir) {

$scope.winclrating=angular.copy(rir);   
////console.log($scope.winclrating);
   $scope.modalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'templates/tests/winclrating.html',
      scope:$scope,
      size: size,
     
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
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance2.dismiss('cancel');
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
		       		 url:$rootScope.apiurl+'api/approvetest/'+id,
					 data:{ApprovedBy:$rootScope.dboard.uid,SectionId:4}
		        
		   		 }).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal3();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}
	
	
	$scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		////console.log(pageno);
		$http({	
					method:'GET',
					url:$rootScope.apiurl+'newapi/winclratingdata/30/'+pageno,	
	     	 
	     		}).then(function successCallback(data) {
					////console.log(data);
				$scope.allrirs=data.allrirs;
		$scope.totalitems=data.totalitems;
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			//console.log(data);
					$scope.flags.loadingdata=false;
	     		});
		
	}

	$scope.searchdata=function(searchtext)
	{
		if(!_.isEmpty(searchtext))
		{
			$scope.flags.loadingdata=true;
			var pageno=1;
				////console.log(pageno);
				$http({	
							method:'POST',
							url:$rootScope.apiurl+'newapi/searchwinclrating/',	
							data:{text:searchtext,pageSize:$scope.pageSize}
					 
						}).then(function successCallback(data) {
							////console.log(data);
							$scope.pageSize=75;
						$scope.allrirs=data.allrirs;
				$scope.totalitems=0;//data.totalitems;
				$scope.flags.loadingdata=false;
										
						}, function errorCallback(data) {
							//console.log(data);
							$scope.flags.loadingdata=false;
						});
		}

	}

$scope.refreshdata=function()
{
	$state.reload();//('app.bcregdetails',{});
	
}
  
});

App.controller('winclratingaddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';

	$scope.winclrating={};
	$scope.editflag=false;
	$scope.flags={};	
	$scope.flags.observation=true;
	$scope.flags.oneclick=false;
	$scope.remarks=['Passed','Failed'];
	$scope.loadingFiles = true;	
	$scope.path='images/testuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	
	 $scope.dateOptions = {
    //customClass: getDayClass,
   // minDate: new Date(),
    showWeeks: true
  };
	
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
	
	
	if(angular.isDefined(allpredata.data))
	{
		$scope.winclrating.basic=allpredata.data.basic;
		$scope.allmangs=allpredata.data.magnifications;
		$scope.alltestmethods=allpredata.data.alltestmethods;
		$scope.allobsunit=allpredata.data.allobsunit;
			console.log($scope.winclrating.basic);
			
			if(!_.isEmpty($scope.winclrating.basic.TestDate))
			{
			$scope.winclrating.basic.TestDate=new Date($scope.winclrating.basic.TestDate);
			}else
		{
			$scope.winclrating.basic.TestDate=new Date();	
		}
		
		$scope.loadingFiles = true;
		$http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = true;
							 
						var allfiles=response.data.files;
						////console.log(response);
					if(allfiles.length>0)
					{
						
						angular.forEach(allfiles,function(file)
							{
								if(angular.isDefined(file.rirtestid) && parseInt(file.rirtestid)===parseInt($scope.winclrating.basic.Id))
								{
									file.rirtestid=file.rirtestid.toString();
									$scope.queue.push(file);
									
								}
								
							});
							
							
					}
					
										//	//console.log(  $scope.queue);
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );
		
	}
	
	
	$scope.saveupload=function()
	{
		
		var file=$scope.queue[0];
		////console.log(file);
		//var find=_.findWhere();
		if(angular.isUndefined(file.id))
		{
		file=_.extend(file,{"rirtestid":$scope.winclrating.basic.Id});
		var result=file.$submit();
				//	//console.log(result);
		}
		else
		{
			$state.go('app.winclrating');
		}
	}
	
		$scope.$on('fileuploadstop', function()
	{
		$state.go('app.winclrating');
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {rirtestid: data.files[0].rirtestid};
 
});

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
            if (file.url)
                    {
                
                        file.$destroy = function () {
                            state = 'pending';
                            return $http({
                                url: file.deleteUrl,
                                method: file.deleteType
                            }).then(
                               function () {
                                    state = 'resolved';
                                    //$scope.clear(file); 
                                    
                                },
                                function () {
                                    state = 'rejected';
                                //    //console.log(state);
                                }
                            );
                        };
                        file.$destroy();
                        
                    }
	
			$scope.queue.splice(idx,1);
		}
	}
	
	
    $scope.cancelsave=function()
	{
		$state.go('app.winclrating');
	}  
   
   
   
  $scope.savewinclratingtest=function(winclrating)
  {
	//console.log(winclrating);  
		$scope.flags.oneclick=true;
		winclrating.ModifiedBy=$rootScope.dboard.uid;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'newapi/winclratingupdate/'+winclrating.basic.Id,
					data:winclrating					
				}).then(function successCallback(data) {
				////console.log(data);
			 	$scope.flags.oneclick=false;
				if($scope.queue.length>0)
				{
					$scope.saveupload();
				}
				else
				{
					$state.go('app.winclrating');
				}
				
				
			 	
					}, function errorCallback(data) {
					//console.log(data);
		$scope.flags.oneclick=false;
				}); 
  }

	 
  $scope.addobs=function(param)
  {
	 if(param==='new')
	  {
		  
		  var row={Idx:"",ThinA:0,ThickA:0,ThinB:0,ThickB:0,ThinC:0,ThickC:0,ThinD:0,ThickD:0};
		  row.Idx=$scope.winclrating.basic.obbasic.Observations.length+1;
		  
		  $scope.winclrating.basic.obbasic.Observations.push(row);
		 
		
	  }
	  else
	  {
		  var i=angular.copy(row.Idx-1);
		  $scope.winclrating.basic.obbasic.Observations[i]=row;
		  
		  
	  }
	 
	 
  }
  
  
  
  $scope.removeobs=function(item)
  {
	  //console.log(item);
	  if(angular.isDefined(item.Id))
	  {
		  $scope.winclrating.basic.delobs.push(item);
	  }
	   var i=angular.copy(item.Idx-1);
	  $scope.winclrating.basic.obbasic.Observations.splice(i,1);
	  var j=1;
	  angular.forEach( $scope.winclrating.basic.obbasic.Observations,function(val){
		  
		  val.Idx=j;
		  j++;
	  });
  }
  
  
});


App.controller('threadController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.allrirs=[];
	$scope.animationsEnabled = true;
	 $scope.pageSize=30;
	$scope.currentPage=1;
	if(angular.isDefined(alldata.data))
	{
		$scope.allrirs=alldata.data.allrirs;
		$scope.totalitems=alldata.data.totalitems;
		$scope.allobsunit=alldata.data.allobsunit;
		$scope.getperm=_.findWhere($scope.permissions,{SectionId:'42'});
		console.log(alldata.data);
	}
	
	
	$scope.showunit=function(id)
	{
		return _.findWhere($scope.allobsunit,{Id:id}).Unit;
	}
	
  $scope.editresult=function(param,id)
  {
	////console.log(param);
		$state.go('app.threadadd',{id:id});
	
  }
  
   $scope.showrir = function (size,rir) {

$scope.thread=angular.copy(rir);   
////console.log($scope.grainsize);
   $scope.modalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'templates/tests/thread.html',
      scope:$scope,
      size: size,
     
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
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
		$scope.oneclick=false;
 		$scope.modalInstance2.dismiss('cancel');
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
		       		 url:$rootScope.apiurl+'api/approvetest/'+id,
					 data:{ApprovedBy:$rootScope.dboard.uid,SectionId:4}
		        
		   		 }).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getdata($scope.currentPage);
					$scope.closeconfirmModal3();
		        	}, function errorCallback(data) {
		        		//console.log(data);
						$scope.oneclick=false;
				});
		}

	}
	
	
	$scope.getdata=function(pageno)
	{
		$scope.flags.loadingdata=true;
		////console.log(pageno);
		$http({	
					method:'GET',
					url:$rootScope.apiurl+'newapi/threaddata/30/'+pageno,	
	     	 
	     		}).then(function successCallback(data) {
					////console.log(data);
				$scope.allrirs=data.allrirs;
		$scope.totalitems=data.totalitems;
		$scope.flags.loadingdata=false;
			     				
	     		}, function errorCallback(data) {
	     			//console.log(data);
					$scope.flags.loadingdata=false;
	     		});
		
	}

	$scope.searchdata=function(searchtext)
	{
		if(!_.isEmpty(searchtext))
		{
			$scope.flags.loadingdata=true;
			var pageno=1;
				////console.log(pageno);
				$http({	
							method:'POST',
							url:$rootScope.apiurl+'newapi/searchthread/',	
							data:{text:searchtext,pageSize:$scope.pageSize}
					 
						}).then(function successCallback(data) {
							////console.log(data);
							$scope.pageSize=75;
						$scope.allrirs=data.allrirs;
				$scope.totalitems=0;//data.totalitems;
				$scope.flags.loadingdata=false;
										
						}, function errorCallback(data) {
							//console.log(data);
							$scope.flags.loadingdata=false;
						});
		}

	}

$scope.refreshdata=function()
{
	$state.reload();//('app.bcregdetails',{});
	
}
  
});

App.controller('threadaddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';

	$scope.thread={};
	$scope.editflag=false;
	$scope.flags={};	
	$scope.flags.observation=true;
	$scope.flags.oneclick=false;
	$scope.remarks=['Passed','Failed'];
	$scope.loadingFiles = true;	
	$scope.path='images/testuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	
	 $scope.dateOptions = {
    //customClass: getDayClass,
   // minDate: new Date(),
    showWeeks: true
  };
	
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
	
	
	if(angular.isDefined(allpredata.data))
	{
		$scope.thread.basic=allpredata.data.basic;
		$scope.allmangs=allpredata.data.magnifications;
		$scope.alltestmethods=allpredata.data.alltestmethods;
		$scope.allobsunit=allpredata.data.allobsunit;
			console.log($scope.thread.basic);
			
			if(!_.isEmpty($scope.thread.basic.TestDate))
			{
			$scope.thread.basic.TestDate=new Date($scope.thread.basic.TestDate);
			}else
		{
			$scope.thread.basic.TestDate=new Date();	
		}
		
		$scope.loadingFiles = true;
		$http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = true;
							 
						var allfiles=response.data.files;
						////console.log(response);
					if(allfiles.length>0)
					{
						
						angular.forEach(allfiles,function(file)
							{
								if(angular.isDefined(file.rirtestid) && parseInt(file.rirtestid)===parseInt($scope.thread.basic.Id))
								{
									file.rirtestid=file.rirtestid.toString();
									
									$scope.queue.push(file);
									
								}
								
							});
							
							
					}
					
										//	//console.log(  $scope.queue);
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );
		
	}
	
	
	$scope.addnewobs=function()
	{
		var nrow={SrNo:"",Parameter:"",Observation:"",Remark:""};
		$scope.thread.basic.obbasic.Thobs.push(nrow);
	}
	
	$scope.delobs=function(index)
	{
		$scope.thread.basic.obbasic.Thobs.splice(index,1);
	}
	
	$scope.saveupload=function()
	{
		
		//var file=$scope.queue[0];
		angular.forEach($scope.queue,function(file){
			//console.log(file);
			if(angular.isUndefined(file.id))
			{
				file=_.extend(file,{"rirtestid":$scope.thread.basic.Id});
				console.log(file);
				var result=file.$submit();
				//	//console.log(result);
			}
			
		})
		var activeUploads = $('#fileupload').fileupload('active');
if(activeUploads<1)
{
$state.go('app.thread');
}
		
		// else
		// {
			// $state.go('app.thread');
		// }
	}
	
		$scope.$on('fileuploadstop', function()
	{
		$state.go('app.thread');
	});
	
		// $scope.$on('fileuploaddone', function()
	// {
		// $state.go('app.thread');
	// });

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {rirtestid: data.files[0].rirtestid,description:data.files[0].description};
 
});

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
            if (file.url)
                    {
                
                        file.$destroy = function () {
                           var state = 'pending';
                            return $http({
                                url: file.deleteUrl,
                                method: file.deleteType
                            }).then(
                               function () {
                                    state = 'resolved';
                                    //$scope.clear(file); 
                                    
                                },
                                function () {
                                    state = 'rejected';
                                //    //console.log(state);
                                }
                            );
                        };
                        file.$destroy();
                        
                    }
	
			$scope.queue.splice(idx,1);
		}
	}
	
	
    $scope.cancelsave=function()
	{
		$state.go('app.thread');
	}  
   
   
   
  $scope.savethreadtest=function(thread)
  {
	//console.log(thread);  
		$scope.flags.oneclick=true;
		thread.ModifiedBy=$rootScope.dboard.uid;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'newapi/threadupdate/'+thread.basic.Id,
					data:thread					
				}).then(function successCallback(data) {
				////console.log(data);
			 	$scope.flags.oneclick=false;
				if($scope.queue.length>0)
				{
					$scope.saveupload();
				}
				else
				{
					$state.go('app.thread');
				}
				
				
			 	
					}, function errorCallback(data) {
					//console.log(data);
		$scope.flags.oneclick=false;
				}); 
  }
  
});






