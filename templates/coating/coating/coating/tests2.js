randack.controller('sstController',function($scope,$http,$cookies,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	$scope.allrirs=[];
	$scope.animationsEnabled = true;
	$scope.currentPage=1;
	$scope.pageLimit=15;
	$scope.sst={};
	if(angular.isDefined(alldata.data))
	{
		$scope.allrirs=alldata.data;
	}
	
	$scope.editresult=function(param,id)
	{
		$state.go('dashboard.admin.sstadd',{id:id});
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
  
});

randack.controller('sstaddController',function($scope,$http,$cookies,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';
	$scope.sst={};
	$scope.sst.basic={};
	$scope.editflag=false;
	$scope.flags={};
	$scope.flags.observation=true;
	$scope.flags.oneclick=false;
	$scope.remarks=['Passed','Failed'];
	
	if(angular.isDefined(allpredata.data))
	{
		//console.log(allpredata.data);
		$scope.sst.basic=allpredata.data.basic;
		$scope.sst.cond=allpredata.data.cond;
		
		if($scope.sst.basic.observations.length<1)
		{
			var interval=$scope.sst.basic.interval;
			for(var i=1;i<=$scope.sst.basic.drows;i++)
			{
			$scope.sst.basic.observations.push({SeqNo:i,Duration:i*interval,OnDate:"",White:"false",NoWhite:"false",Red:"false",NoRed:"false"});
			}
		}
		else
		{
			if(!_.isEmpty($scope.sst.basic.obbasic.LoadingDate))
			{
			$scope.sst.basic.obbasic.LoadingDate=new Date($scope.sst.basic.obbasic.LoadingDate);
			}
			if(!_.isEmpty($scope.sst.basic.obbasic.CompleteDate))
			{
			$scope.sst.basic.obbasic.CompleteDate=new Date($scope.sst.basic.obbasic.CompleteDate);
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
    minDate: new Date(),
    showWeeks: true
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
		$state.go('dashboard.admin.sst');
	}  
   
  $scope.savesst=function(sst)
  {
	//console.log(sst);  
		$scope.flags.oneclick=true;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'api/sstupdate/'+sst.basic.Id,
					data:sst					
				}).success(function(data){
				//console.log(data);
			 	$scope.flags.oneclick=false;
				$state.go('dashboard.admin.sst');
			 	
					}).error(function(data){
					console.log(data);
					$scope.flags.oneclick=false;
				}); 
  }
  
});

///////////////////
/****************SST Flake Coating******************/
randack.controller('flakecoatController',function($scope,$http,$cookies,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout)
{
	'use strict';
	$scope.allrirs=[];
	$scope.animationsEnabled = true;
	$scope.currentPage=1;
	$scope.pageLimit=15;
	$scope.sst={};
	// if(angular.isDefined(alldata.data))
	// {
		// $scope.allrirs=alldata.data;
	// }
	
	$scope.editresult=function(param,id)
	{
		$state.go('dashboard.admin.flakecoatadd',{id:id});
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
  
});

randack.controller('flakecoataddController',function($scope,$http,$cookies,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout)
{
	'use strict';
	$scope.flakecoat={};
	$scope.flakecoat.basic={};
	$scope.editflag=false;
	$scope.flags={};
	$scope.flags.observation=true;
	$scope.flags.oneclick=false;
	
	$scope.flakecoat.basic={};
	//$scope.remarks=['Passed','Failed'];
	$scope.alltest=[{id:"1",text:"Coating Thickness"},{id:"2",text:"Adhesion Test"},
	{id:"3",text:"Corrosion Resistance"},{id:"4",text:"Visual Inspection"},
	{id:"5",text:"Fitment"},{id:"4",text:"Response to Go-No Go Gauge"}];
	
  $scope.dateOptions = {
    minDate: new Date(),
    showWeeks: true
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
		$state.go('dashboard.admin.flakecoating');
	}  
   
   
	$scope.flakecoat.basic.observations=[{Test:"",Equipment:"",Standard:"",Requirement:"",A:"",B:"",C:"",D:"",E:"",
	Remark:"",mergeparam:false,mergesamp:false}];

	$scope.addrow=function()
	{
	$scope.flakecoat.basic.observations.push({Test:"",Equipment:"",Standard:"",Requirement:"",A:"",B:"",C:"",D:"",E:"",
	Remark:"",mergeparam:false,mergesamp:false});
	}
	
	$scope.addparamrow=function()
	{
	$scope.flakecoat.basic.observations.push({Test:"",Equipment:"",Standard:"",Requirement:"",A:"",B:"",C:"",D:"",E:"",
	Remark:"",mergeparam:true,mergesamp:true});
	}
   
	$scope.addsamprow=function()
	{
	$scope.flakecoat.basic.observations.push({A:"",B:"",C:"",D:"",E:"",
	Remark:"",mergeparam:false,mergesamp:true});
	}
   
  $scope.savesst=function(sst)
  {
	//console.log(sst);  
		$scope.flags.oneclick=true;
	 $http({
					method:'PUT',
					url: $rootScope.apiurl+'api/sstupdate/'+sst.basic.Id,
					data:sst					
				}).success(function(data){
				//console.log(data);
			 	$scope.flags.oneclick=false;
				$state.go('dashboard.admin.sst');
			 	
					}).error(function(data){
					console.log(data);
					$scope.flags.oneclick=false;
				}); 
  }
  
});


///////////////////////////////////////////////////
/**----------------External CONTROLLER------------------------------------------******/

randack.controller('externalController', function($scope,$http,$location,$rootScope,$state,toaster,
$window,$uibModal,AuthenticationService,alldata) {
	
	$scope.allrirs=[];
	$scope.pageSize=15;
	 $scope.currentPage=1;
	 
	 if(angular.isDefined(alldata.data))
	{
		$scope.allrirs=alldata.data;
		console.log(alldata.data);
	}
	
	$scope.editresult=function(param,id)
  {
		$state.go('dashboard.admin.externaladd',{id:id});
	
  }
	
});

randack.controller('externaladdController', function($scope,$http,$location,$rootScope,$state,toaster,
$window,$uibModal,AuthenticationService,allpredata) {
	
	$scope.rirext={};
	$scope.pageSize=15;
	 $scope.currentPage=1;
	 $scope.loadingFiles = true;	
	$scope.path='images/exttestuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	
	
	 if(angular.isDefined(allpredata.data))
	{
		$scope.rirext=allpredata.data;
		console.log(allpredata.data);
		
		$scope.loadingFiles = true;
		$http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = true;
							 
						var allfiles=response.data.files;
						console.log(response);
					if(allfiles.length>0)
					{
						angular.forEach($scope.rirext.basic.ExternalTests,function(test)
							{
						
						angular.forEach(allfiles,function(file)
							{
								if(angular.isDefined(file.rirexttestid) && parseInt(file.rirexttestid)===parseInt(test.Id))
								{
									file.rirexttestid=file.rirexttestid.toString();
									$scope.queue.push(file);
									
								}
								
							});
							
							});
					}
					
											console.log(  $scope.queue);
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );
	}
	
	 
	
	$scope.saveupload=function(file)
	{
		console.log(file);
		//var find=_.findWhere();
		//file=_.extend(file,{"exttestid":$scope.mdsid});
		var result=file.$submit();
					console.log(result);
	}
	
		$scope.$on('fileuploadstop', function()
	{
		//$scope.flags.oneclick=false;
		//$state.go('dashboard.admin.external');
		//$scope.cancel();
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {rirexttestid: data.files[0].rirexttestid};
 
});
	
});

randack.controller('FileDestroyController', [
            '$scope', '$http',
            function ($scope, $http) {
                var file = $scope.file,
                    state;
                if (file.url) {
                    file.$state = function () {
                        return state;
                    };
                    file.$destroy = function () {
                        state = 'pending';
                        return $http({
                            url: file.deleteUrl,
                            method: file.deleteType
                        }).then(
                            function () {
                                state = 'resolved';
                                $scope.clear(file);
                            },
                            function () {
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
		
//////////---------------PDir----------------//////////

randack.controller('pdirController',function($scope,$http,$cookies,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.allrirs=[];
	$scope.pdir=[];
	$scope.animationsEnabled = true;
	$scope.currentPage=1;
	$scope.pageLimit=15;
	if(angular.isDefined(alldata.data))
	{
		$scope.allrirs=alldata.data.pdir;
		console.log($scope.allrirs);
	}
	
  $scope.editpdir=function(param,id)
  {
	 
	if(param==='new')
	{ 
		$state.go('dashboard.admin.pdiradd',{id:id});
	}
	else
	{
		$state.go('dashboard.admin.pdiredit',{id:id});
	}
	  
	  
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
  
});

randack.controller('pdiraddController',function($scope,$http,$cookies,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout)
{
	'use strict';

	$scope.pdir={};
	
	$scope.editflag=false;
	$scope.pdir.basic={};
	//$scope.pdir.basic.observations=[];
	$scope.pdir.basic.observations=[{Dimension:"",Min:"",Max:"",Samp1:"",Samp2:"",Samp3:"",Samp4:"",Samp5:"",Samp6:"",Samp7:"",Samp8:"",Samp9:"",Samp10:""}];

	$scope.addrow=function()
	{
	$scope.pdir.basic.observations.push({Dimension:"",Min:"",Max:"",Samp1:"",Samp2:"",Samp3:"",Samp4:"",Samp5:"",Samp6:"",Samp7:"",Samp8:"",Samp9:"",Samp10:""});
	}
 
	
    $scope.cancelsave=function()
	{
		$state.go('dashboard.admin.pdir');
	}  
   
  $scope.savepdir=function(pdir)
  {
	console.log(pdir);  
		$http({
				method:'POST',
				url: $rootScope.apiurl+'api/pdiradd',
				data:pdir					
			}).success(function(data){
			//console.log(data);
		
			$state.go('dashboard.admin.pdir');
			
				}).error(function(data){
				console.log(data);
				
			}); 
  }
  
});

randack.controller('pdireditController',function($scope,$http,$cookies,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';
	$scope.allrirs=[];
	$scope.pdir={};
	$scope.editflag=true;
	if(angular.isDefined(allpredata.data))
	{
		$scope.pdir=allpredata.data;		
		console.log(allpredata.data);
	}
	
	$scope.popup2 = {
    opened: []
	  };
	   $scope.open2 = function(index) {
		$scope.popup2.opened[index] = true;
	  };
 
  $scope.cancelsave=function()
	{
		$state.go('dashboard.admin.pdir');
	}  
  
  $scope.savepdir=function(pdir)
  {
	console.log(pdir);  
	 $http({
				method:'PUT',
				url: $rootScope.apiurl+'api/pdireditdata/'+pdir.Id,
				data:pdir					
			}).success(function(data){
			console.log(data);
			//$scope.getpages();
			$state.go('dashboard.admin.pdir');
			
				}).error(function(data){
				console.log(data);
			}); 
  }
});


// /////////////////Config to set extension for uploading/////////////////////////
 randack.config([
            '$httpProvider', 'fileUploadProvider',
            function ($httpProvider, fileUploadProvider) {
                delete $httpProvider.defaults.headers.common['X-Requested-With'];
                fileUploadProvider.defaults.redirect = window.location.href.replace(
                    /\/[^\/]*$/,
                    '/cors/result.html?%s'
                );
               
                  angular.extend(fileUploadProvider.defaults, {
                        // Enable image resizing, except for Android and Opera,
                        // which actually support image resizing, but fail to
                        // send Blob objects via XHR requests:
                        disableImageResize: /Android(?!.*Chrome)|Opera/
                            .test(window.navigator.userAgent),
                        maxFileSize: 3000000,
                        acceptFileTypes: /(\.|\/)(pdf|doc|docx|xls|txt|xlsx|gif|jpe?g|png)$/i
                    });                 
            }
        ]);

