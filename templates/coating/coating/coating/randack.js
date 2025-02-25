var randack=angular.module('randack', ['ngSanitize','ui.router','ngAnimate','ui.bootstrap','ngCookies','toaster','ngIdle','ncy-angular-breadcrumb',
'angularUtils.directives.dirPagination','ui.select','blueimp.fileupload']);

randack.run(
  [          '$rootScope', '$state', '$stateParams','$window','$idle','$cookies','AuthenticationService','$http','$uibModal',
	function ($rootScope,   $state,   $stateParams ,$window,$idle,$cookies,AuthenticationService,$http,$uibModal) {
	   
	$rootScope.apiurl="admin/";
   	$rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;
	$rootScope.loginloadingView = false;
	$rootScope.dboard={};
	$rootScope.dboard=$cookies.getObject('dboard');
	
	var exists=$cookies.getObject('dboard');
	if(!_.isEmpty(exists))
	{
		$rootScope.dboard=$cookies.getObject('dboard');
	}
	
	
	
	
	$rootScope.$on('$stateChangeStart', 
	function(event, toState, toParams, fromState, fromParams){
		$rootScope.loadingView = true;

	 });
	
	$rootScope.$on('$stateChangeSuccess', 
	function(event, toState, toParams, fromState, fromParams){
		$rootScope.loadingView = false;

	 });
	
	function checkCookie()
	{
		var cookieEnabled=(navigator.cookieEnabled)? true : false;
		if (typeof navigator.cookieEnabled=="undefined" && !cookieEnabled){ 
		document.cookie="testcookie";
		cookieEnabled=(document.cookie.indexOf("testcookie")!=-1)? true : false;
		console.log("cookie check");
		}
		return (cookieEnabled)?true:showCookieFail();
	}

	
	function showCookieFail(){
	  // do something here
	console.log("cookie check fail");
	$window.alert("Cookies are disabled.Please enable the cookies" );
	}

	checkCookie();
	
 	var exists=$cookies.getObject('dboard');
	if(angular.isUndefined(exists) && !_.isEmpty(exists))
	{
		$state.go('login', null, { reload: true });	
	}
	else
	{
		$idle.watch();
	}
	
	
}]);

randack.config(
  [ '$stateProvider', '$urlRouterProvider','$locationProvider',
    function ($stateProvider,   $urlRouterProvider,$locationProvider) {

    $urlRouterProvider
	.when('/dashboard/admin','/dashboard/admin/home')
.when('/dashboard/','/dashboard/admin/home')
	.otherwise('/login');

     // Use $stateProvider to configure your states.
    $stateProvider
		
		.state('login', {
            url: '/login',
            templateUrl: 'templates/auth/login.html',
            controller: 'loginController'
        })
		
		.state('dashboard', {
            url: '/dashboard',
            templateUrl: 'templates/dashboard.html',
            controller: 'dashboardController',
			 ncyBreadcrumb: {
    skip: true // Never display this state in breadcrumb.
  }
        })
		
		.state('dashboard.admin', {
            url: '/admin',
            templateUrl: 'templates/dashboard.admin.html',
            //controller: 'loginController'
			ncyBreadcrumb: {  label: 'Home'}
        })
		
		.state('dashboard.admin.home', {
            url: '/admin',
            templateUrl: 'templates/dashboard.admin.home.html',
            //controller: 'loginController'
			 ncyBreadcrumb: {  label: 'Home'}
        })
		
	
/*------Settings---------------------------------------------------*/
	
		.state('dashboard.admin.users', {
            url: '/users',
            templateUrl: 'templates/users/users.html',
            controller: 'userController',
			 access: { requiredAuthentication: true },
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/users'}).then(function(data) {
                            console.log(data);
							deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
				 ncyBreadcrumb: {  label: 'Users'}
        })
		
		.state('dashboard.admin.adduser', {
            url: '/adduser',
            templateUrl: 'templates/users/add_user.html',
            controller: 'adduserController',
			 access: { requiredAuthentication: true },
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/preuserdata'}).then(function(data) {
                            console.log(data);
							deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
				 ncyBreadcrumb: {  parent:'dashboard.admin.users',label: 'Add User'}
        })
		
		
		.state('dashboard.admin.edituser', {
            url: '/edituser/:id',
            templateUrl: 'templates/users/add_user.html',
            controller: 'edituserController',
			 access: { requiredAuthentication: true },
				resolve: {
					  alldata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/edituser/'+id}).then(function(data) {
                            console.log(data);
							deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
				 ncyBreadcrumb: {  parent:'dashboard.admin.users',label: 'Edit User'}
        })

		
		.state('dashboard.admin.settings', {
            url: '/settings',
            templateUrl: 'templates/settings/settings.html',
            //controller: 'loginController'
			 ncyBreadcrumb: {  label: 'Settings'}
        })

		.state('dashboard.admin.customer', {
            url: '/customerinfo',
            templateUrl: 'templates/users/customer.html',
          	access: { requiredAuthentication: true },
			  controller: 'customerController',
			 resolve: {
					  allcustdata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/customer'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
					
			// ncyBreadcrumb: { parent:'dashboard.admin.certificates', label: 'Customer Info'}
        })



		.state('dashboard.admin.standard', {
            url: '/standard',
            templateUrl: 'templates/settings/standard.html',
             controller: 'stdController',
			 access: { requiredAuthentication: true },
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/stddata'}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: { parent:'dashboard.admin.settings', label: 'Standards'}
        })
		
		.state('dashboard.admin.standardadd', {
            url: '/standardadd',
            templateUrl: 'templates/settings/standard.add.html',
          	 access: { requiredAuthentication: true },
			  controller: 'stdaddController',
		 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/stdpredata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: { parent:'dashboard.admin.standard', label: 'Add Standards '}
        })
		
		.state('dashboard.admin.standardedit', {
            url: '/standardedit/:id',
            templateUrl: 'templates/settings/standard.add.html',
          	access: { requiredAuthentication: true },
			  controller: 'stdeditController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/stdedit/'+id}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'dashboard.admin.standard', label: 'Edit Standard '}
        })
			
		
		.state('dashboard.admin.concentrate', {
            url: '/concentrate',
            templateUrl: 'templates/settings/concentrate.html',
             controller: 'concentrateController',
			 access: { requiredAuthentication: true },
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/concntdata'}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: { parent:'dashboard.admin.settings', label: 'Chemical composition %  '}
        })
		
		.state('dashboard.admin.concentrateadd', {
            url: '/concentrateadd',
            templateUrl: 'templates/settings/concentrate.add.html',
          	 access: { requiredAuthentication: true },
			  controller: 'concentrateaddController',
			  	resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/concntpredata'}).then(function(data) {
							  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
		
			 ncyBreadcrumb: { parent:'dashboard.admin.concentrate', label: 'Add Chemical composition %   '}
        })
		
		.state('dashboard.admin.concentrateedit', {
            url: '/concentrateedit/:id',
            templateUrl: 'templates/settings/concentrate.add.html',
          	access: { requiredAuthentication: true },
			  controller: 'concentrateeditController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/concntedit/'+id}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'dashboard.admin.concentrate', label: 'Edit Chemical composition %   '}
        })
		
		
		.state('dashboard.admin.mech', {
            url: '/mech',
            templateUrl: 'templates/settings/mech.html',
             controller: 'mechController',
			 access: { requiredAuthentication: true },
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/mechdata'}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: { parent:'dashboard.admin.settings', label: 'Mechanical Properties  '}
        })
		
		.state('dashboard.admin.mechadd', {
            url: '/mechadd',
            templateUrl: 'templates/settings/mech.add.html',
          	 access: { requiredAuthentication: true },
			  controller: 'mechaddController',
			  	resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/mechpredata'}).then(function(data) {
							  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
		
			 ncyBreadcrumb: { parent:'dashboard.admin.mech', label: 'Mechanical Properties   '}
        })
		
		.state('dashboard.admin.mechedit', {
            url: '/mechedit/:id',
            templateUrl: 'templates/settings/mech.add.html',
          	access: { requiredAuthentication: true },
			  controller: 'mecheditController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/mechedit/'+id}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'dashboard.admin.mech', label: 'Edit Mechanical Properties   '}
        })
		
		
		.state('dashboard.admin.dropdowntemp', {
            url: '/dropdowntemp',
            templateUrl: 'templates/settings/dropdowntemp.html',
          	access: { requiredAuthentication: true },
			  controller: 'tempController',
			 resolve: {
					  alltempdata: function ($q, $http,$cookieStore,$rootScope) {
						
						var deferred = $q.defer();
						
						$http({method: 'GET',	url:$rootScope.apiurl+'settingapi/droptemp',}).then(function(data,response) {
					
						  deferred.resolve(data);
						});
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'dashboard.admin.settings', label: 'Temperature'}
        })
		
		.state('dashboard.admin.ssthrs', {
            url: '/ssthr',
            templateUrl: 'templates/settings/ssthr.html',
          	access: { requiredAuthentication: true },
			  controller: 'ssthrsController',
			 resolve: {
					  alltempdata: function ($q, $http,$cookieStore,$rootScope) {
						
						var deferred = $q.defer();
						
						$http({method: 'GET',	url:$rootScope.apiurl+'settingapi/ssthr',}).then(function(data,response) {
					
						  deferred.resolve(data);
						});
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'dashboard.admin.settings', label: 'SST Hours'}
        })
				
		.state('dashboard.admin.dropdownload', {
            url: '/dropdownload',
            templateUrl: 'templates/settings/dropdownload.html',
          	access: { requiredAuthentication: true },
			  controller: 'loadController',
			 resolve: {
					  allloaddata: function ($q, $http,$cookieStore,$rootScope) {
						
						var deferred = $q.defer();
						
						$http({method: 'GET',	url:$rootScope.apiurl+'settingapi/dropload',}).then(function(data,response) {
					
						  deferred.resolve(data);
						});
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'dashboard.admin.settings', label: 'Load'}
        })



.state('dashboard.admin.methods', {
            url: '/methods',
            templateUrl: 'templates/settings/testmethods.html',
          	access: { requiredAuthentication: true },
			  controller: 'methodsController',
			 resolve: {
					  alldata: function ($q, $http,$cookieStore,$rootScope) {
						
						var deferred = $q.defer();
						
						$http({method: 'GET',	url:$rootScope.apiurl+'settingapi/methods',}).then(function(data,response) {
					
						  deferred.resolve(data);
						});
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'dashboard.admin.settings', label: 'Test Methods'}
        })
		
		.state('dashboard.admin.mds', {
            url: '/Mds',
            templateUrl: 'templates/settings/mds.html',
             controller: 'mdsController',
			 access: { requiredAuthentication: true },
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/mdsdata'}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: { parent:'dashboard.admin.settings', label: 'MDS'}
        })
		
		.state('dashboard.admin.mdsadd', {
            url: '/mdsadd',
            templateUrl: 'templates/settings/mds.add.html',
          	 access: { requiredAuthentication: true },
			  controller: 'mdsaddController',
				
			 ncyBreadcrumb: { parent:'dashboard.admin.mds', label: 'MDS-Form'}
        })
		
		.state('dashboard.admin.mdsedit', {
            url: '/mdsedit/:id',
            templateUrl: 'templates/settings/mds.add.html',
          	access: { requiredAuthentication: true },
			  controller: 'mdseditController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/mdsedit/'+id}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'dashboard.admin.mds', label: 'Edit MDS-Form '}
        })
		
		
		.state('dashboard.admin.tds', {
            url: '/Tds',
            templateUrl: 'templates/settings/tds.html',
             controller: 'tdsController',
			 access: { requiredAuthentication: true },
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/tdsdata'}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: { parent:'dashboard.admin.settings', label: 'TDS'}
        })
		
		.state('dashboard.admin.tdsadd', {
            url: '/tdsadd',
            templateUrl: 'templates/settings/tds.add.html',
          	 access: { requiredAuthentication: true },
			  controller: 'tdsaddController',
				
			 ncyBreadcrumb: { parent:'dashboard.admin.tds', label: 'TDS-Form'}
        })
		
		.state('dashboard.admin.tdsedit', {
            url: '/tdsedit/:id',
            templateUrl: 'templates/settings/tds.add.html',
          	access: { requiredAuthentication: true },
			  controller: 'tdseditController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/tdsedit/'+id}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'dashboard.admin.tds', label: 'Edit TDS-Form '}
        })
		
		
/*-----RECEIPT TEST------------------------------------------*/

		
		.state('dashboard.admin.receipt', {
            url: '/receipt',
            templateUrl: 'templates/rir/receipt.html',
            controller: 'rirController',
			 access: { requiredAuthentication: true },
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/rirdata'}).then(function(data) {
                            console.log(data);
							deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
				 ncyBreadcrumb: {  label: 'Receipt Inspection Report'}
        })
		
		.state('dashboard.admin.receiptadd', {
            url: '/receiptadd',
            templateUrl: 'templates/rir/receipt.add.html',
          	 access: { requiredAuthentication: true },
			  controller: 'riraddController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/rirpredata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: { parent:'dashboard.admin.receipt', label: 'Add Receipt '}
        })
		
		.state('dashboard.admin.receiptedit', {
            url: '/receiptedit/:id',
            templateUrl: 'templates/rir/receipt.add.html',
          	
			  controller: 'rireditController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/riredit/'+id}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
					 access: { requiredAuthentication: true },
			 ncyBreadcrumb: { parent:'dashboard.admin.receipt', label: 'Add Receipt '}
        })
		

		.state('dashboard.admin.tests', {
            url: '/tests',
            templateUrl: 'templates/tests/tests.html',
              	 access: { requiredAuthentication: true },
			 
			 ncyBreadcrumb: {  label: 'Tests'}
        })
		
		
	
		.state('dashboard.admin.chemical', {
            url: '/chemical',
            templateUrl: 'templates/tests/chemical.html',
              	 access: { requiredAuthentication: true },
			  controller: 'chemicalController',
			 resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/chemicaldata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tests', label: 'Chemical test '}
        })
		
		
		.state('dashboard.admin.chemicaladd', {
            url: '/chemicaladd/:id',
            templateUrl: 'templates/tests/chemicaladd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'chemicaladdController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/chemicaleditdata/'+id}).then(function(data) {
						//	  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.chemical', label: 'Add Chemical test result '}
        })
		
	
		
		
		.state('dashboard.admin.hardness', {
            url: '/hardness',
            templateUrl: 'templates/tests/hardness.html',
              	 access: { requiredAuthentication: true },
			  controller: 'hardnessController',
			 resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/hardnessdata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tests', label: 'Hardness test '}
        })
		
		
		.state('dashboard.admin.hardnessadd', {
            url: '/hardnessadd/:id',
            templateUrl: 'templates/tests/hardnessadd.html',
              	 access: { requiredAuthentication: true },
			controller: 'hardnessaddController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/hardnesseditdata/'+id}).then(function(data) {
						//	  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.hardness', label: 'Add/Edit Hardness  result '}
        })
		
		
		
		
		.state('dashboard.admin.tensile', {
            url: '/tensile',
            templateUrl: 'templates/tests/tensile.html',
              	 access: { requiredAuthentication: true },
			  controller: 'tensileController',
			 resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/tensiledata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tests', label: 'Universal Testing Machine  '}
        })
		
		
		.state('dashboard.admin.tensileadd', {
            url: '/tensileadd/:id',
            templateUrl: 'templates/tests/tensileadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'tensileaddController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/tensilepredata/'+id}).then(function(data) {
						//	  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tensile', label: 'Add Tensile result '}
        })
		
		
		.state('dashboard.admin.impact', {
            url: '/impact',
            templateUrl: 'templates/tests/impact.html',
              	 access: { requiredAuthentication: true },
			  controller: 'impactController',
			 resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/impactdata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tests', label: 'Impact test  '}
        })
		
		
		.state('dashboard.admin.impactadd', {
            url: '/impactadd/:id',
            templateUrl: 'templates/tests/impactadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'impactaddController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/impacteditdata/'+id}).then(function(data) {
						//	  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.impact', label: 'Add Impact result '}
        })
		
		
		.state('dashboard.admin.tension', {
            url: '/tension',
            templateUrl: 'templates/tests/tension.html',
              	 access: { requiredAuthentication: true },
			  controller: 'torqtensController',
			 resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/tensiondata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tests', label: 'Torque-Tension'}
        })
		
		
		.state('dashboard.admin.tensionadd', {
            url: '/tensionadd/:id',
            templateUrl: 'templates/tests/tensionadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'torqtensaddController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/tensioneditdata/'+id}).then(function(data) {
						//	  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tension', label: 'Add Tension result '}
        })
		
	
		
		
		
		.state('dashboard.admin.proofload', {
            url: '/proofload',
            templateUrl: 'templates/tests/proofload.html',
              	 access: { requiredAuthentication: true },
			  controller: 'proofloadController',
			 resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/proofloaddata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tests', label: 'proofload'}
        })
		
		
		.state('dashboard.admin.proofloadadd', {
            url: '/proofloadadd/:id',
            templateUrl: 'templates/tests/proofloadadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'proofloadaddController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/proofeditdata/'+id}).then(function(data) {
						//	  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.proofload', label: 'Add proofload result '}
        })
		
		.state('dashboard.admin.external', {
            url: '/external',
            templateUrl: 'templates/tests/external.html',
              	 access: { requiredAuthentication: true },
			  controller: 'externalController',
			 resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/externaldata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tests', label: 'External Upload'}
        })
		
		.state('dashboard.admin.externaladd', {
            url: '/externaladd/:id',
            templateUrl: 'templates/tests/externaladd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'externaladdController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/externaleditdata/'+id}).then(function(data) {
						//	  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.external', label: 'Add External result '}
        })
	
.state('dashboard.admin.casedepth', {
            url: '/casedepth',
            templateUrl: 'templates/tests/casedepth.html',
              	access: { requiredAuthentication: true },
				controller: 'casedepthController',
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/casedepthdata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tests', label: 'Case Depth Test'}
        })
		
		.state('dashboard.admin.casedepthadd', {
            url: '/casedepthadd/:id',
            templateUrl: 'templates/tests/casedepthadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'casedepthaddController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/casedeptheditdata/'+id}).then(function(data) {
						//	  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.casedepth', label: 'Add Case Depth  test '}
        })	

		
		.state('dashboard.admin.hydro', {
            url: '/hydro',
            templateUrl: 'templates/tests/hydro.html',
              	access: { requiredAuthentication: true },
				controller: 'hydroController',
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/hydrodata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tests', label: 'Hydrogen Embrittlment Test'}
        })
		
		.state('dashboard.admin.hydroadd', {
            url: '/hydroadd/:id',
            templateUrl: 'templates/tests/hydroadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'hydroaddController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/hydroeditdata/'+id}).then(function(data) {
						//	  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.hydro', label: 'Add Hydrogen  test '}
        })	
		
		.state('dashboard.admin.shear', {
            url: '/shear',
            templateUrl: 'templates/tests/shear.html',
              	access: { requiredAuthentication: true },
				controller: 'shearController',
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/sheardata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tests', label: 'Shear Strength Test'}
        })
		
		.state('dashboard.admin.shearadd', {
            url: '/shearadd/:id',
            templateUrl: 'templates/tests/shearadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'shearaddController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/sheareditdata/'+id}).then(function(data) {
						//	  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.shear', label: 'Add Shear Strength  test '}
        })	
		
		.state('dashboard.admin.wedge', {
            url: '/wedge',
            templateUrl: 'templates/tests/wedge.html',
              	access: { requiredAuthentication: true },
				controller: 'wedgeController',
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/wedgedata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tests', label: 'Wedge Test'}
        })
		
		.state('dashboard.admin.wedgeadd', {
            url: '/wedgeadd/:id',
            templateUrl: 'templates/tests/wedgeadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'wedgeaddController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/wedgeeditdata/'+id}).then(function(data) {
						//	  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.wedge', label: 'Add Wedge  test '}
        })	
		
		.state('dashboard.admin.carb', {
            url: '/carb',
            templateUrl: 'templates/tests/carb.html',
              	access: { requiredAuthentication: true },
				controller: 'carbController',
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/carbdata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tests', label: 'Carb-de-carb Test'}
        })
		
		.state('dashboard.admin.carbadd', {
            url: '/carbadd/:id',
            templateUrl: 'templates/tests/carbadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'carbaddController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/carbeditdata/'+id}).then(function(data) {
						//	  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.carb', label: 'Add Carb-de-Carb  test '}
        })	
		
		.state('dashboard.admin.sst', {
            url: '/sst',
            templateUrl: 'templates/coating/sst.html',
              	access: { requiredAuthentication: true },
				controller: 'sstController',
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/sstdata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.tests', label: 'Salt-Spray Test'}
        })
		
		.state('dashboard.admin.sstadd', {
            url: '/sstadd/:id',
            templateUrl: 'templates/coating/sstadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'sstaddController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/ssteditdata/'+id}).then(function(data) {
						//	  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.sst', label: 'Add Salt Spray test '}
        })	
		
		//Zn-Al Flake Coating
		.state('dashboard.admin.flakecoating', {
            url: '/flakecoating',
            templateUrl: 'templates/coating/flakecoating.html',
              	access: { requiredAuthentication: true },
				controller: 'flakecoatController',
				// resolve: {
					  // alldata: function ($q, $http,$rootScope) {
						
						// var deferred = $q.defer();
                        
                          // $http({method: 'GET',	url:$rootScope.apiurl+'api/flakecoatdata'}).then(function(data) {
                            // deferred.resolve(data);
                          // });
                         
						// return deferred.promise;
					  // }
					// },
			 ncyBreadcrumb: {  parent:'dashboard.admin.tests', label: 'Zn-Al Flake Coating'}
        })
		
		.state('dashboard.admin.flakecoatadd', {
            url: '/flakecoatadd/:id',
            templateUrl: 'templates/coating/flakecoatadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'flakecoataddController',
			 // resolve: {
					  // allpredata: function ($q, $http,$rootScope,$stateParams) {
						// var id=$stateParams.id;
						// var deferred = $q.defer();
                        
                          // $http({method: 'GET',	url:$rootScope.apiurl+'api/ssteditdata/'+id}).then(function(data) {
						// //	  console.log(data);
                            // deferred.resolve(data);
                          // });
                         
						// return deferred.promise;
					  // }
					// },
			 ncyBreadcrumb: {  parent:'dashboard.admin.flakecoating', label: 'Add Flake Coating Values'}
        })	
		/************************Setting Controller*****************/		
///////////-----Standard-------------///////////
		
////////////*************Certificates*************/////////////		
		
		.state('dashboard.admin.certificates', {
            url: '/certificates',
            templateUrl: 'templates/certificate/certificates.html',
			access: { requiredAuthentication: true },
		    controller: 'certificateController',
			 // resolve: {
					  // allpredata: function ($q, $http,$rootScope,$stateParams) {
						// var id=$stateParams.id;
						// var deferred = $q.defer();
                        
                          // $http({method: 'GET',	url:$rootScope.apiurl+'api/concntedit/'+id}).then(function(data) {
                            // deferred.resolve(data);
                          // });
                         
						// return deferred.promise;
					  // }
					// },
					
			 ncyBreadcrumb: {  label: 'Certificates '}
        })
		
		.state('dashboard.admin.certificateadd', {
            url: '/certificateadd',
            templateUrl: 'templates/certificate/certificate.add.html',
          	 access: { requiredAuthentication: true },
			  controller: 'certificateaddController',
			  	// resolve: {
					  // alldata: function ($q, $http,$rootScope) {
						
						// var deferred = $q.defer();
                        
                          // $http({method: 'GET',	url:$rootScope.apiurl+'api/concntpredata'}).then(function(data) {
							  
                            // deferred.resolve(data);
                          // });
                         
						// return deferred.promise;
					  // }
					// },
		
			 ncyBreadcrumb: { parent:'dashboard.admin.certificates', label: 'Add Certificate'}
        })
		

/*****************************************************/		
		// .state('dashboard.admin.certificateedit', {
            // url: '/certificateedit/:id',
            // templateUrl: 'templates/certificate/certificate.add.html',
          	// access: { requiredAuthentication: true },
			  // controller: 'certificateeditController',
			 // resolve: {
					  // allpredata: function ($q, $http,$rootScope,$stateParams) {
						// var id=$stateParams.id;
						// var deferred = $q.defer();
                        
                          // $http({method: 'GET',	url:$rootScope.apiurl+'api/concntedit/'+id}).then(function(data) {
                            // deferred.resolve(data);
                          // });
                         
						// return deferred.promise;
					  // }
					// },
					
			 // ncyBreadcrumb: { parent:'dashboard.admin.concentrate', label: 'Edit Chemical composition %   '}
        // })
		
/**********************PDIR***********************/		
		.state('dashboard.admin.pdir', {
            url: '/pdir',
            templateUrl: 'templates/pdir/pdir.html',
              	access: { requiredAuthentication: true },
				controller: 'pdirController',
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/pdirdata'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin', label: 'Pre-Dispatch Ispection Report'}
        })
		
		.state('dashboard.admin.pdiradd', {
            url: '/pdiradd/',
            templateUrl: 'templates/pdir/pdiradd.html',
            access: { requiredAuthentication: true },
			controller: 'pdiraddController',
			ncyBreadcrumb: {  parent:'dashboard.admin.pdir', label: 'PDIR Add Details'}
		})
		
		.state('dashboard.admin.pdiredit', {
            url: '/pdiredit/:id',
            templateUrl: 'templates/pdir/pdiradd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'pdireditController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/pdireditdata/'+id}).then(function(data) {
							  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'dashboard.admin.pdir', label: 'PDIR Edit Details'}
        })	
		
		
		$locationProvider.html5Mode(false);
		
}]);

randack.config(function(uiSelectConfig) {
  uiSelectConfig.theme = 'bootstrap';
});

randack.config(function(paginationTemplateProvider) {
    paginationTemplateProvider.setPath('js/dirPagination.tpl.html');
});

randack.controller('dashboardController',function($scope,$http,$cookies,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout)
{
	$scope.logout=function()
	{
		
		delete $window.sessionStorage.token;
			AuthenticationService.isAuthenticated = false;
			$rootScope.loginloadingView = false;
			$cookies.remove('dboard');
			$state.go('login');
	}
});
/*---------------------------Directive----------------------*/

randack.directive('numbersOnly', function () {
        return {
            require: 'ngModel',
            link: function (scope, element, attr, ngModelCtrl) {
                function fromUser(text) {
                    if (text) {
                        var transformedInput = text.replace(/[^0-9-.]/g, '');
                        if (transformedInput !== text) {
                            ngModelCtrl.$setViewValue(transformedInput);
                            ngModelCtrl.$render();
                        }
                        return transformedInput;
                    }
                    return undefined;
                }
                ngModelCtrl.$parsers.push(fromUser);
            }
        };
    });
	
randack.directive('ngMin', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, elem, attr, ctrl) {
            scope.$watch(attr.ngMin, function () {
                ctrl.$setViewValue(ctrl.$viewValue);
            });
            var minValidator = function (value) {
                var min = scope.$eval(attr.ngMin) || 0;
                if (!_.isEmpty(value) && value < min) {
                    ctrl.$setValidity('ngMin', false);
                    return undefined;
                } else {
                    ctrl.$setValidity('ngMin', true);
                    return value;
                }
            };

            ctrl.$parsers.push(minValidator);
            ctrl.$formatters.push(minValidator);
        }
    };
});

randack.directive('ngMax', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, elem, attr, ctrl) {
            scope.$watch(attr.ngMax, function () {
                ctrl.$setViewValue(ctrl.$viewValue);
            });
            var maxValidator = function (value) {
                var max = scope.$eval(attr.ngMax) || Infinity;
                if (!_.isEmpty(value) && value > max) {
                    ctrl.$setValidity('ngMax', false);
                    return undefined;
                } else {
                    ctrl.$setValidity('ngMax', true);
                    return value;
                }
            };

            ctrl.$parsers.push(maxValidator);
            ctrl.$formatters.push(maxValidator);
        }
    };
});
