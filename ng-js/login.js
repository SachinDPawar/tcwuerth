/********----------Authentication & Authorization code for login-----------********/
App.config(function ($httpProvider) {
  $httpProvider.interceptors.push('authInterceptor');
});
App.run(function($http,$localstorage,configparam,$window) {
	
	var ed=$localstorage.getObject(configparam.AppName);
	if ($window.sessionStorage.token) {
        $http.defaults.headers.common.Authorization = 'Bearer ' + $window.sessionStorage.token;
      }
	  else
	  {
		  $http.defaults.headers.common.Authorization = 'Bearer ' + ed.myworld;
	  }
 
});



App.factory('AuthenticationService', function() {

	
var auth = {
isAuthenticated: false,
isAdmin: false
}
return auth;
}); 




App.factory('authInterceptor', function ($rootScope, $q, $window,AuthenticationService) {
  return {
    request: function (config) {
      config.headers = config.headers || {};
      if ($window.sessionStorage.token) {
        config.headers.Authorization = 'Bearer ' + $window.sessionStorage.token;
      }
      return config;
    },
    response: function (response) {
      if (response.status === 401) {
        // handle the case where the user is not authenticated
        delete $window.sessionStorage.token;
AuthenticationService.isAuthenticated = false;
      }
      return response || $q.when(response);
    }
  };
});


App.controller('loginController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,configparam,
$rootScope,$state,AuthenticationService,$timeout)
{
	'use strict';
	$scope.user={};
	$rootScope.loginloadingView = false;
	
	var exists=$localstorage.getObject(configparam.AppName);
	
	if(angular.isDefined(exists) && !_.isEmpty(exists))
	{
		
		console.log(exists);
		$state.go('app.home',{});
	}
 	
	
 toaster.pop('success', "", "Login successfully");
	$scope.gologin=function(user)
	{
		$rootScope.loginloadingView = true;
		// check to make sure the form is completely valid
		$http({
		method:'POST',
		url:$rootScope.apiurl+'api/loginuser',
		data:user
		}).then(function successCallback(data) {
			//console.log(data);
			
			if(angular.fromJson(data.data.number) ===204)
			{	
					$scope.user=user;
				$scope.viewlogininst = $uibModal.open({
					keyboard:false,
					animation: true,
					size:'sm',
				backdrop:"static",
					templateUrl: 'alreadylogin',
					scope: $scope,
				});

			}
			else
			{					
				toaster.pop('success', "", "Login successfully");
				$scope.saveflag=true;
				$localstorage.setObject(configparam.AppName,angular.fromJson(data.data));
				$window.sessionStorage.token = data.data.myworld;						 	
				AuthenticationService.isAuthenticated = true;	
				
				if(data.data.roleid===3)
				{//console.log(data.data.roleid);
					$state.go('app.custdash', null, { reload: true });
				$rootScope.loginloadingView = false;
				}
				else
				{
				//	console.log(data.data.roleid);
				$state.go('app.home', null, { reload: true });
				$rootScope.loginloadingView = false;
				}
				
			}


			
		}, function errorCallback(data) {			
			
			
			delete $window.sessionStorage.token;
			AuthenticationService.isAuthenticated = false;
			$rootScope.loginloadingView = false;
			$localstorage.remove(configparam.AppName);
			user={};
			toaster.pop('error', "", "Username or password incorrect.Please Try Again?");
			console.log(data);
		});
	}
	
	
	$scope.closeloginmodal=function()
	{
		$scope.user={};
		$rootScope.loginloadingView = false;
		$scope.viewlogininst.dismiss('cancel')
	}
	 
	 $scope.continuelogin=function(user)
	{
		$rootScope.loginloadingView = true;
		// check to make sure the form is completely valid
		$http({
		method:'POST',
		url:$rootScope.apiurl+'api/forcelogin',
		data:user
		}).then(function successCallback(data) {
			//console.log(data);
								
				toaster.pop('success', "", "Login successfully");
				$scope.saveflag=true;
				$localstorage.setObject(configparam.AppName,angular.fromJson(data.data));
				$window.sessionStorage.token = data.data.myworld;						 	
				AuthenticationService.isAuthenticated = true;	
			if(data.data.roleid===3)
				{//console.log(data.data.roleid);
					$state.go('app.custdash', null, { reload: true });
				$rootScope.loginloadingView = false;
				}
				else
				{
					//console.log(data.data.roleid);
				$state.go('app.home', null, { reload: true });
				$rootScope.loginloadingView = false;
				}
				
			
			
		}, function errorCallback(data) {			
			
			
			delete $window.sessionStorage.token;
			AuthenticationService.isAuthenticated = false;
			$rootScope.loginloadingView = false;
			$localstorage.remove(configparam.AppName);
			user={};
			toaster.pop('error', "", "Username or password incorrect.Please Try Again?");
			console.log(data);
		});
	}
	 
	 
});



App.controller('forgotpwdController',function($state,$timeout,$scope,$http,$localstorage, $rootScope,toaster)
{

$scope.sendingflag=false;
$scope.user={};
$scope.submitForm = function(user) {
console.log(user);
$scope.sendingflag=true;

//toaster.pop('success', "sada", "Mail sent successfully");

$http({
					method:'POST',
					url:$rootScope.apiurl+'api/forgotpwd',
					data:{Email:$scope.user.Email,Url:$scope.user.userAddress}
  				}).then(function successCallback(data) {
				console.log(data);
					toaster.pop('success', "", "Mail sent successfully");
				$scope.user.Email="";
				$scope.sendingflag=false;

				$timeout(function() 
				{
					$state.go('login', null);
	       	
				}, 2000);

				}, function errorCallback(data) {
				$scope.sendingflag=false;
				toaster.pop('error', "", "The email is not registered.");
				});

}
});


App.controller('changepwdController', function($scope,$http,$location,$rootScope,$state,toaster,$localstorage,$timeout,
$window,$uibModal,AuthenticationService) {
	
	$scope.newpass={};
	$scope.oneclick=false;
console.log($scope.dboard.Password);
	$scope.savepassword=function(newpass,passwordform)
	{
		$scope.oneclick=true;
		if(angular.isDefined(newpass.NewPassword))
		{
			
			
			$http({
			method:'PUT',
			url:$rootScope.apiurl+'settingapi/pswdupdate/'+$rootScope.dboard.uid,
			data:newpass
  			}).success(function(data){
			//console.log(data);
			toaster.pop('success', "", "Password successfully Updated.");
			
			$scope.newpass={};
			$scope.oneclick=false;
			$timeout(function() {
			delete $window.sessionStorage.token;
			AuthenticationService.isAuthenticated = false;
			$localstorage.remove(configparam.AppName);
			$state.go('login', null, { reload: true });
			},2000);
			
			}).error(function(data){
			$scope.oneclick=false;
			//$scope.newpass={};
			toaster.pop('error',"", data);
			
			});
				
				
				
		}
		
		
			
	}
	
	
	$scope.cancelupdate=function()
	{
		$state.go('dashboard.admin.home');
	}  
  
});

