var App=angular.module('InfoApp', ['ngSanitize','ui.router','toaster','ngAnimate','ui.bootstrap','ngIdle','ncy-angular-breadcrumb','chart.js','ngFileUpload','angular-echarts','ngSlimScroll','pascalprecht.translate','ja.qr',
'angularUtils.directives.dirPagination','ui.select','blueimp.fileupload','xeditable', 'ngCsv','moment-picker','ui.tree',
'angular-nicescroll','textAngular','angular.filter',]);
App.constant('configparam', {

	
	'AppName':"tclimswuerth", //..used for localStorage app settings
	'Locstorename':"tclimswuerth", //..used for localStorage app authorization settings
	'Descriptivename':"tclimswuerthpanel",
	'Description':"TC LIMS WUERTH",
	
    
  })
  
  



App.run(
  [          '$rootScope', '$state', '$stateParams','$window','$localstorage','AuthenticationService','$http','$uibModal','configparam','$breadcrumb','$transitions',
	function ($rootScope,   $state,   $stateParams ,$window,$localstorage,AuthenticationService,$http,$uibModal,configparam,$breadcrumb,$transitions) {
	   
	$rootScope.apiurl="admin/";
   	$rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;
	$rootScope.loginloadingView = false;
	
	
	$transitions.onEnter({}, function(transition, state) {
 // console.log('Transition #' + transition.$id + ' Entered ' + state.name);
});

	$transitions.onError({}, function(transition) {
  //console.log("Error while leaving 'home' state: " + transition.error());
 // $state.go('app.permdenied');
});


$transitions.onBefore({}, function(transition) {
	//console.log(transition);
	//console.log('Transition #' + transition.$id + ' Entered ' );
});

	$transitions.onStart({}, function(transition) {
		//console.log(transition);
		
		 if (transition.to().name === 'unreachable') {
			// console.log(transition);
    return false;
  }
  		$rootScope.loadingView2 = true;
});
	

	
	
	$transitions.onSuccess({}, function() {
 // console.log("statechange success");
 
		$rootScope.loadingView2 = false;
  //window.scrollTo(0,0);
});
	
	
	 
	
	
}]);


App.config(function() {
  angular.lowercase = angular.$$lowercase;  
});

App.directive('scrollable', function() {
  'use strict';
  return {
    restrict: 'EA',
    link: function(scope, elem, attrs) {
      var defaultHeight = 285;

      attrs.height = attrs.height || defaultHeight;

      elem.slimScroll(attrs);

    }
  };
});
App.factory('$localstorage', ['$window', function($window) {
  return {
    set: function(key, value) {
      $window.localStorage[key] = value;
    },
    get: function(key, defaultValue) {
      return $window.localStorage[key] || defaultValue;
    },
    setObject: function(key, value) {
      $window.localStorage[key] = JSON.stringify(value);
    },
    getObject: function(key) {
      return JSON.parse($window.localStorage[key] || '{}');
    },
	 getArray: function(key) {
            return JSON.parse($window.localStorage[key] || '[]');
        },
        destroy: function(key) {
            $window.localStorage.removeItem(key);
        },
		 remove: function(key) {
            $window.localStorage.removeItem(key);
        },
  }
}]);

 // App.run(function ($rootScope, $templateCache) {
    // $rootScope.$on('$viewContentLoaded', function () {
        // $templateCache.removeAll();
    // });
// });

App.config(
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
		
		.state('forgotpwd', {
            url: '/forgotpwd',
            templateUrl: 'templates/auth/recover.html',
            controller: 'forgotpwdController'
        })
		
		.state('app', {
            url: '/app',
            templateUrl: 'templates/app.html',
            controller: 'appController',
			cache: false,
			resolve: {
					  allpredata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams,NotifyService) {
						var exists=$localstorage.getObject(configparam.AppName);
						//console.log(exists);
						var deferred = $q.defer();
                       // console.log(exists);
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/getpermission/'+exists.uid}).then(function(data) {
							//console.log(data);
								var adata=data;
								var notis=NotifyService.loadNotis(exists.uid).then(function(data){
									//console.log(data);
									
									var rdata={data:adata.data,allnotis:data};
								deferred.resolve(rdata);
								});
								}, function (error) {
									console.log(error);
								
                          });
                         
						return deferred.promise;
					  }
					},
					 
			 ncyBreadcrumb: {
    skip: true // Never display this state in breadcrumb.
  }
        })
		
		.state('app.permdenied', {
            url: '/permdenied',
            templateUrl:'templates/appsetting/permdenied.html',			
        cache: false,
			
			ncyBreadcrumb: { parent:'app.home', label: 'Permission Denied', icon:'fa fa-home'}
        })
		
		.state('app.error', {
            url: '/error',
            templateUrl:'templates/appsetting/error.html',	
  controller: 'ErrorController',			
        cache: false,
			
			ncyBreadcrumb: { parent:'app.home', label: 'Error', icon:'fa fa-home'}
        })
		
		/*-------Dashboard-----------*/
		
		.state('app.custdash', {
            url: '/custdash',
            templateUrl: 'templates/cust_home.html',
            controller: 'custdashboardController',
			cache: false,
			   	access: { requiredAuthentication: true },
			  resolve: {
					  allpredata: function ($q, $http,$localstorage,configparam) {
						var exists=$localstorage.getObject(configparam.AppName);
						console.log(exists);
						var deferred = $q.defer();
                       
						console.log(exists);
                          $http({method: 'PUT',	url:'admin/adminapi/custdashinfo/'+exists.uid,data:{pl:15,pn:1,Filter:""}}).then(function(data) {
								console.log(data);
								deferred.resolve(data);
								}, function (error) {
									console.log(error);
                deferred.reject({redirectTo: 'login'});
                          });
                         
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: {
    skip: true // Never display this state in breadcrumb.
  }
			// ncyBreadcrumb: {  label: 'Home', icon:'fa fa-home'}
        })		
		
		
		.state('app.addcustquote', {
            url: '/addcustquote',
            templateUrl: 'templates/custportal/addquote.html',
            controller: 'custquoteAddCntrl',
			cache: false,
			   
			  resolve: {
					  alldata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'adminapi/getquotepredata/'+exists.uid,data:{}}).then(function(data) {
								console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {
    skip: true // Never display this state in breadcrumb.
  }
			
        })
		
		
		.state('app.contsst', {
            url: '/contsst',
            templateUrl: 'templates/coating/contsst.html',
              	access: { requiredAuthentication: true },
				controller: 'contsstController',
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'coatapi/contsstdata/30/1'}).then(function(data) {
							  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.home', label: 'Salt-Spray Test'}
        })
		
			.state('app.contsstadd', {
            url: '/contsstadd',
            templateUrl: 'templates/coating/contsstadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'contsstaddController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'coatapi/contsstpredata'}).then(function(data) {
						//	  //console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.contsst', label: 'Add Salt Spray test '}
        })	
		
		
		.state('app.contsstedit', {
            url: '/contsstedit/:id',
            templateUrl: 'templates/coating/contsstadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'contssteditController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'coatapi/contssteditdata/'+id}).then(function(data) {
						//	  //console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.contsst', label: 'Add Salt Spray test '}
        })	
		
		.state('app.coatcert', {
            url: '/coatcert',
            templateUrl: 'templates/coating/coatcert.html',
              	access: { requiredAuthentication: true },
				controller: 'coatcertController',
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'coatapi/coatcertdata/30/1'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.coating', label: 'Coating certificate'}
        })
		
		.state('app.coatcertadd', {
            url: '/coatcertadd/',
            templateUrl: 'templates/coating/coatcertadd.html',
            access: { requiredAuthentication: true },
			controller: 'coatcertaddController',
			ncyBreadcrumb: {  parent:'app.coatcert', label: 'Coating certificate Add'}
		})
		
		.state('app.coatcertedit', {
            url: '/coatcertedit/:id',
            templateUrl: 'templates/coating/coatcertadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'coatcerteditController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'coatapi/coatcerteditdata/'+id}).then(function(data) {
							  //console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.coatcert', label: 'Coating Certificate Edit'}
        })	
		
		.state('app.sstcert', {
            url: '/sstcert',
            templateUrl: 'templates/coating/sstcert.html',
              	access: { requiredAuthentication: true },
				controller: 'sstcertController',
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'coatapi/sstcertdata/30/1'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.coating', label: 'Salt-Spray Test'}
        })
		
		
		.state('app.sstcertadd', {
            url: '/sstcertadd',
            templateUrl: 'templates/coating/sstcertadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'sstcertaddController',
			
			 ncyBreadcrumb: {  parent:'app.sstcert', label: 'Add Salt Spray test '}
        })	
		
		
		.state('app.sstcertedit', {
            url: '/sstcertedit/:id',
            templateUrl: 'templates/coating/sstcertadd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'sstcerteditController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'coatapi/sstcerteditdata/'+id}).then(function(data) {
						//	  //console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.sstcert', label: 'Add Salt Spray test '}
        })	
		
		
		.state('app.pdir', {
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
		
		.state('app.pdiradd', {
            url: '/pdiradd/',
            templateUrl: 'templates/pdir/pdiradd.html',
            access: { requiredAuthentication: true },
			controller: 'pdiraddController',
			ncyBreadcrumb: {  parent:'app.pdir', label: 'PDIR Add Details'}
		})
		
		.state('app.pdiredit', {
            url: '/pdiredit/:id',
            templateUrl: 'templates/pdir/pdiradd.html',
              	 access: { requiredAuthentication: true },
			  controller: 'pdireditController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/pdireditdata/'+id}).then(function(data) {
							  //console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.pdir', label: 'PDIR Edit Details'}
        })	
		
		
		.state('app.home', {
            url: '/home',
            templateUrl: 'templates/app_home.html',
            controller: 'dashboardController',
			cache: false,
			   	access: { requiredAuthentication: true },
			  resolve: {
					  allpredata: function ($q, $http,$localstorage,configparam) {
						var exists=$localstorage.getObject(configparam.AppName);
						console.log(exists);
						var deferred = $q.defer();
                       
						console.log(exists);
                          $http({method: 'PUT',	url:'admin/adminapi/getdashinfo/'+exists.uid,data:{Filter:'L7D'}}).then(function(data) {
								console.log(data);
								deferred.resolve(data);
								}, function (error) {
									console.log(error);
                deferred.reject({redirectTo: 'login'});
                          });
                         
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: {
    skip: true // Never display this state in breadcrumb.
  }
			// ncyBreadcrumb: {  label: 'Home', icon:'fa fa-home'}
        })
		
		/*-------Settings 1-----------*/
		
		.state('app.industry', {
            url: '/industry',
            templateUrl:'templates/appsetting/industry.html',
			controller:'IndustryController',
        cache: false,
			resolve:  {
            // YOUR CUSTOM RESOLVES HERE
			alldata: function ($q, $http,$rootScope,$localstorage,configparam) {					
		
var exists=$localstorage.getObject(configparam.AppName);		
                var deferred = $q.defer();                      
                $http({method: 'PUT',	url:'admin/adminapi/industries/'+exists.uid,data:{}}).then(function(data) {
                  console.log(data);
                  deferred.resolve(data);
                });
                return deferred.promise;
            	}
            },
			ncyBreadcrumb: { parent:'app.home', label: 'Industry', icon:'fa fa-home'}
        })
		
		
		.state('app.roles', {
            url: '/roles',
            templateUrl:'templates/appsetting/roles.html',
			controller:'RolesCntrl',
        cache: false,
			resolve:  {
            // YOUR CUSTOM RESOLVES HERE
			alldata: function ($q, $http,$rootScope,$localstorage,configparam) {					
		
var exists=$localstorage.getObject(configparam.AppName);					
                var deferred = $q.defer();                      
                $http({method: 'PUT',	url:'admin/adminapi/roles/'+exists.uid,data:{}}).then(function(data) {
                  console.log(data);
                  deferred.resolve(data);
                });
                return deferred.promise;
            	}
            },
			ncyBreadcrumb: { parent:'app.home', label: 'Roles', icon:'fa fa-home'}
        })
			
		.state('app.labaccredit', {
            url: '/labaccredit',
            templateUrl:'templates/appsetting/labaccredit.html',
			controller:'LabAccreditController',
        cache: false,
			resolve:  {
            // YOUR CUSTOM RESOLVES HERE
				alldata: function ($q, $http,$rootScope,$localstorage,configparam) {					
		
var exists=$localstorage.getObject(configparam.AppName);					
                var deferred = $q.defer();                      
                $http({method: 'PUT',	url:'admin/adminapi/labaccredits/'+exists.uid,data:{}}).then(function(data) {
                  console.log(data);
                  deferred.resolve(data);
                });
                return deferred.promise;
            	}
            },
			ncyBreadcrumb: { parent:'app.home', label: 'Lab Accreditation', icon:'fa fa-home'}
        })
		
		
		.state('app.mailsettings', {
            url: '/mailsettings',
            templateUrl:'templates/appsetting/mailsettings.html',
			controller:'MailSettingsCntrl',
        cache: false,
			resolve:  {
            // YOUR CUSTOM RESOLVES HERE
				alldata: function ($q, $http,$rootScope,$localstorage,configparam) {					
		
var exists=$localstorage.getObject(configparam.AppName);				
                var deferred = $q.defer();                      
                $http({method: 'PUT',	url:'admin/settingapi/mailsettings/'+exists.uid,data:{}}).then(function(data) {
                  console.log(data);
                  deferred.resolve(data);
                });
                return deferred.promise;
            	}
            }
        })
		
			.state('app.defaultsetting', {
            url: '/defaultsetting',
            templateUrl: 'templates/appsetting/defaultsetting.html',
            
			controller: 'defaultsettingController',
			cache: false,
			
			ncyBreadcrumb: {  parent:'app.home', label: 'Settings'}
		})
		
		.state('app.defaultsetting.firmset', {
            url: '/firmset/:id',
            templateUrl: 'templates/appsetting/settings/firmsetting.html',
             
			controller: 'firmsettingController',
			cache: false,
			resolve: {
					  allpredata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);
						//console.log(exists);
						var id=exists.uid;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/getsettings/'+id}).then(function(data) {
								console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: {  parent:'app.defaultsetting', label: 'Firm Setting'}
		})
		.state('app.defaultsetting.quoteset', {
            url: '/quoteset/:id',
            templateUrl: 'templates/appsetting/settings/quotesetting.html',
             
			controller: 'quotesettingController',
			cache: false,
			resolve: {
					  allpredata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);
						//console.log(exists);
						var id=exists.uid;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/getsettings/'+id}).then(function(data) {
								//////console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: {  parent:'app.defaultsetting', label: 'Quote Setting'}
		})
		
		.state('app.defaultsetting.labset', {
            url: '/labset/:id',
            templateUrl: 'templates/appsetting/settings/labsetting.html',
             
			controller: 'labsettingController',
			cache: false,
			resolve: {
					  allpredata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);
						//console.log(exists);
						var id=exists.uid;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/getsettings/'+id}).then(function(data) {
								//////console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: {  parent:'app.defaultsetting', label: 'Lab Settings'}
		})
		
		
		.state('app.defaultsetting.certset', {
            url: '/certset/:id',
            templateUrl: 'templates/appsetting/settings/certsetting.html',
             
			controller: 'certsettingController',
			cache: false,
			resolve: {
					  allpredata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);
						//console.log(exists);
						var id=exists.uid;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/getsettings/'+id}).then(function(data) {
								//////console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: {  parent:'app.defaultsetting', label: 'Lab Settings'}
		})
		
		.state('app.defaultsetting.accountset', {
            url: '/accountset/:id',
            templateUrl: 'templates/appsetting/settings/accountsetting.html',
             
			controller: 'accountsettingController',
			cache: false,
			resolve: {
					  allpredata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);
						//console.log(exists);
						var id=exists.uid;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/getsettings/'+id}).then(function(data) {
								//////console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: {  parent:'app.defaultsetting', label: 'Account Settings'}
		})
		
		.state('app.defaultsetting.mailset', {
            url: '/mailset/:id',
            templateUrl: 'templates/appsetting/settings/mailsettings.html',
             
			controller: 'MailSettingsCntrl',
			cache: false,
			resolve: {
					  alldata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);
						//console.log(exists);
						var id=exists.uid;
						var deferred = $q.defer();
                        
                         $http({method: 'PUT',	url:'admin/settingapi/mailsettings/'+exists.uid,data:{}}).then(function(data) {
								//////console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: {  parent:'app.defaultsetting', label: 'Email Settings'}
		})
		
		
		.state('app.testrates', {
            url: '/testrates',
            templateUrl: 'templates/appsetting/testrates.html',
             
			controller: 'testratesController',
			cache: false,
			resolve: {
					  allpredata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);
						console.log(exists);
						var id=exists.uid;
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'adminapi/testrates/'+id, data:{}}).then(function(data) {
								////console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: {  parent:'app.home', label: 'Test Master Setting'}
		})
		
			.state('app.addtestrate', {
            url: '/addtestrate',
            templateUrl: 'templates/appsetting/testrateadd.html',
             
			controller: 'testrateaddController',
			cache: false,
			resolve: {
					  allpredata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);
						console.log(exists);
						var id=exists.uid;
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'adminapi/testpredata/'+id, data:{}}).then(function(data) {
								////console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: {  parent:'app.testrates', label: 'TestMaster Add'}
		})
		
			.state('app.edittestrate', {
            url: '/edittestrate/:id',
            templateUrl: 'templates/appsetting/testrateadd.html',
             
			controller: 'testrateeditController',
			cache: false,
			resolve: {
					  allpredata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
					
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'adminapi/testeditdata/'+id, data:{}}).then(function(data) {
								////console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: {  parent:'app.testrates', label: 'TestMaster Edit'}
		})
	
		
		/*-------Settings 2-----------*/
		
		.state('app.importsettings', {
            url: '/importsettings',
            templateUrl:'templates/appsetting/importsettings.html',
			controller:'ImportSettingsCntrl',
        cache: false,
			resolve:  {
            // YOUR CUSTOM RESOLVES HERE
			alldata: function ($q, $http,$rootScope,$localstorage,configparam) {					
			var exists=$localstorage.getObject(configparam.AppName);
var id=exists.uid;			
                var deferred = $q.defer();                      
                $http({method: 'PUT',	url:'admin/settingapi/importsettings/'+id,data:{}}).then(function(data) {
                  console.log(data);
                  deferred.resolve(data);
                });
                return deferred.promise;
            	}
            }
        })
		
		
/*-------Account-----------*/
		
		
		.state('app.quotes', {
            url: '/quotes',
            templateUrl: 'templates/accounts/quotes.html',
            controller: 'quotesController',
			cache: false,
			   
			  resolve: {
					  alldata: function ($q, $http,$localstorage,$rootScope,configparam,$stateParams) {
						var exists=$localstorage.getObject(configparam.AppName);
						var deferred = $q.defer();
                        console.log(exists);
                          $http({method: 'PUT',	url:$rootScope.apiurl+'adminapi/getquotes/'+exists.uid,data:{pl:25,pn:1}}).then(function(data) {
								console.log(data);
								deferred.resolve(data);
                           }, function (error) {
                deferred.reject({redirectTo: 'login'});
				
            });
                         
						return deferred.promise;
					  }
					},
			  ncyBreadcrumb: {  parent:'app.home', label: 'Quote', icon:'fa fa-home'}
			// ncyBreadcrumb: {  label: 'Home', icon:'fa fa-home'}
        })
			.state('app.addquote', {
            url: '/addquote/:stype',
            templateUrl: 'templates/accounts/addquote.html',
            controller: 'quoteAddCntrl',
			cache: false,
			   
			  resolve: {
					  alldata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'adminapi/getquotepredata/'+exists.uid,data:{}}).then(function(data) {
								console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {
    skip: true // Never display this state in breadcrumb.
  }
			
        })
		
		.state('app.editquote', {
            url: '/editquote/:id',
            templateUrl: 'templates/accounts/addquote.html',
            controller: 'quoteEditCntrl',
			cache: false,
			   
			  resolve: {
					  alldata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'adminapi/getquoteeditdata/'+id,data:{}}).then(function(data) {
								console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {
    skip: true // Never display this state in breadcrumb.
  }
			
        })
		
		.state('app.viewquote', {
            url: '/viewquote/:qno',
            templateUrl: 'templates/accounts/viewquote.html',
            controller: 'viewquotesCntrl',
			cache: false,
			   
			  resolve: {
					  alldata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.qno;
						var deferred = $q.defer();
                        
                          $http({method: 'POST',	url:$rootScope.apiurl+'adminapi/getquote',data:{qno:id}}).then(function(data) {
								console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {
    skip: true // Never display this state in breadcrumb.
  }
			// ncyBreadcrumb: {  label: 'Home', icon:'fa fa-home'}
        })
		
		
		
		.state('app.invoices', {
            url: '/invoices',
            templateUrl: 'templates/accounts/invoices.html',
            controller: 'invoicesController',
			cache: false,
			   
			  resolve: {
					alldata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);
						
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'adminapi/getinvoices/'+exists.uid,data:{pl:25,pn:1,filter:{}}}).then(function(data) {
								////console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {
    skip: true // Never display this state in breadcrumb.
  }
			// ncyBreadcrumb: {  label: 'Home', icon:'fa fa-home'}
        })
		
		.state('app.expenses', {
            url: '/expenses',
            templateUrl: 'templates/accounts/expenses.html',
            controller: 'expensesController',
			cache: false,
			   
			  resolve: {
					alldata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'adminapi/getexpenses/'+exists.uid,data:{pl:25,pn:1}}).then(function(data) {
								////console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {
    skip: true // Never display this state in breadcrumb.
  }
			// ncyBreadcrumb: {  label: 'Home', icon:'fa fa-home'}
        })
		
		.state('app.payments', {
            url: '/payments',
            templateUrl: 'templates/accounts/payments.html',
            controller: 'paymentsController',
			cache: false,
			   
			  resolve: {
					 alldata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'adminapi/getpayments/'+exists.uid,data:{pl:25,pn:1}}).then(function(data) {
								////console.log(data);
								deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {
    skip: true // Never display this state in breadcrumb.
  }
			// ncyBreadcrumb: {  label: 'Home', icon:'fa fa-home'}
        })
		
		
		
		.state('app.inventory', {
            url: '/inventory',
            templateUrl:'templates/accounts/inventory.html',
			controller:'InventoryCntrl',
        cache: false,
			resolve:  {
            // YOUR CUSTOM RESOLVES HERE
			alldata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);				
                var deferred = $q.defer();                      
                $http({method: 'PUT',	url:'admin/adminapi/inventories/'+exists.uid,data:{}}).then(function(data) {
                  console.log(data);
                  deferred.resolve(data);
                });
                return deferred.promise;
            	}
            }
        })
		
		
/*-------Sample-----------*/
		
		
/*-------Certificate-----------*/

/*----Reports-----------*/


/*----Reports-----------*/
		
			.state('app.reports', {
            url: '/reports',
            templateUrl:'templates/reports/reports.html',
			controller:'ReportsCntrl',
        cache: false,
			
			ncyBreadcrumb: { parent:'app.home', label: 'Reports', icon:'fa fa-home'}
			})
			
			.state('app.reports.quote', {
            url: '/quotereports',
            templateUrl:'templates/reports/quotereports.html',
			controller:'ReportsCntrl',
        cache: false,
			resolve:  {
            // YOUR CUSTOM RESOLVES HERE
			alldata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);				
                var deferred = $q.defer();                      
                $http({method: 'POST',	url:'admin/adminapi/gettodayreport', data:{Filter:'L7D'}}).then(function(data) {
                 
                  deferred.resolve(data);
                });
                return deferred.promise;
            	}
            },
			ncyBreadcrumb: { parent:'app.home', label: 'Reports', icon:'fa fa-home'}
			})
			
			.state('app.reports.samples', {
            url: '/samplereports',
            templateUrl:'templates/reports/samplereports.html',
			controller:'SampleReportsCntrl',
        cache: false,
			resolve:  {
            // YOUR CUSTOM RESOLVES HERE
			alldata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);				
                var deferred = $q.defer();                      
                $http({method: 'POST',	url:'admin/adminapi/getsamplereports', data:{Filter:'L7D'}}).then(function(data) {
                 
                  deferred.resolve(data);
                });
                return deferred.promise;
            	}
            },
			ncyBreadcrumb: { parent:'app.home', label: 'Reports', icon:'fa fa-home'}
			})
				.state('app.reports.tests', {
            url: '/testsreports',
            templateUrl:'templates/reports/testsreports.html',
			controller:'TestsReportsCntrl',
        cache: false,
			resolve:  {
            // YOUR CUSTOM RESOLVES HERE
			alldata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);				
                var deferred = $q.defer();                      
                $http({method: 'POST',	url:'admin/adminapi/gettestsreports', data:{Filter:'L7D'}}).then(function(data) {
                 
                  deferred.resolve(data);
                });
                return deferred.promise;
            	}
            },
			ncyBreadcrumb: { parent:'app.home', label: 'Reports', icon:'fa fa-home'}
			})
		
			.state('app.custreports', {
            url: '/custreports',
            templateUrl:'templates/reports/custreports.html',
			controller:'CustReportsCntrl',
        cache: false,
			resolve:  {
            // YOUR CUSTOM RESOLVES HERE
			alldata: function ($q, $http,$rootScope,$localstorage,configparam,$stateParams) {
						
						var exists=$localstorage.getObject(configparam.AppName);				
                var deferred = $q.defer();                      
                $http({method: 'POST',	url:'admin/adminapi/gettodayreport', data:{Filter:'L7D'}}).then(function(data) {
                 
                  deferred.resolve(data);
                });
                return deferred.promise;
            	}
            },
			ncyBreadcrumb: { parent:'app.home', label: 'Reports', icon:'fa fa-home'}
			})
			
			
		
/*----------------------------------*/		
		
		
		
		
		

		
		
		
		

		
		
		
		
		
		
		
		
		/****************Default Setting****************/
		
/*------Settings---------------------------------------------------*/
	
		.state('app.users', {
            url: '/users',
            templateUrl: 'templates/users/users.html',
            controller: 'userController',
			cache: false,
			  
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/users'}).then(function(data) {
                           console.log(data);
							deferred.resolve(data);
                          },  function (error) {
                deferred.reject(error);
            });
                         
						return deferred.promise;
					  }
					},
				 ncyBreadcrumb: {  label: 'Users'}
        })
		
		.state('app.adduser', {
            url: '/adduser',
            templateUrl: 'templates/users/add_user.html',
            controller: 'adduserController',
			cache: false,
			  
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/preuserdata'}).then(function(data) {
                           // //console.log(data);
							deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
				 ncyBreadcrumb: {  parent:'app.users',label: 'Add User'}
        })
		
		
		.state('app.edituser', {
            url: '/edituser/:id',
            templateUrl: 'templates/users/add_user.html',
            controller: 'edituserController',
			cache: false,
			  
				resolve: {
					  alldata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/getuser/'+id}).then(function(data) {
                           // //console.log(data);
							deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
				 ncyBreadcrumb: {  parent:'app.users',label: 'Edit User'}
        })

		
		.state('app.settings', {
            url: '/settings',
            templateUrl: 'templates/settings/settings.html',
            //controller: 'loginController'
			 ncyBreadcrumb: {  label: 'Settings'}
        })
		
		
		
		
		.state('app.exttestsetting', {
            url: '/externaltest',
            templateUrl: 'templates/settings/externaltestadd.html',
          	 
			  controller: 'externaltestController',
			 resolve: {
					  allextdata: function ($q, $http,$localstorage,$rootScope) {
						
						var deferred = $q.defer();
						
						$http({method: 'GET',	url:$rootScope.apiurl+'settingapi/exttestsetting',}).then(function(data,response) {
					
						  deferred.resolve(data);
						});
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'app.settings', label: 'External Test'}
        })
		
		.state('app.attachcategory', {
            url: '/attachcategory',
            templateUrl: 'templates/settings/attachcategory.html',
          	 
			  controller: 'attachcategoryController',
			 resolve: {
					  allextdata: function ($q, $http,$localstorage,$rootScope) {
						
						var deferred = $q.defer();
						
						$http({method: 'GET',	url:$rootScope.apiurl+'settingapi/attachcategory',}).then(function(data,response) {
					
						  deferred.resolve(data);
						});
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'app.settings', label: 'External Test'}
        })

		.state('app.customer', {
            url: '/customerinfo',
            templateUrl: 'templates/users/customer.html',
          	 
			  controller: 'customerController',
			  cache: false,
			 resolve: {
					  allcustdata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/getcustomers'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
					
			// ncyBreadcrumb: { parent:'app.certificates', label: 'Customer Info'}
        })

.state('app.suppliers', {
            url: '/suppliers',
            templateUrl: 'templates/users/suppliers.html',
          	 
			  controller: 'supplierController',
			  cache: false,
			 resolve: {
					  alldata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/getsuppliers'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
					
			// ncyBreadcrumb: { parent:'app.certificates', label: 'Customer Info'}
        })

		.state('app.standard', {
            url: '/standard',
            templateUrl: 'templates/settings/standard.html',
             controller: 'stdController',
			 cache: false,
			  
				resolve: {
					  alldata: function ($q, $http,$rootScope,configparam,$localstorage) {
						var exists=$localstorage.getObject(configparam.AppName);
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'settingapi/stddata/'+exists.uid,data:{pl:25,pn:1}}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: { parent:'app.settings', label: 'Standards'}
        })
		
		
		.state('app.substandard', {
            url: '/substandard',
            templateUrl: 'templates/settings/substandard.html',
             controller: 'substandardController',
			 cache: false,
			  
				resolve: {
					  alldata: function ($q, $http,$rootScope,configparam,$localstorage) {
						var exists=$localstorage.getObject(configparam.AppName);
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'settingapi/substddata/'+exists.uid,data:{pl:25,pn:1}}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: { parent:'app.settings', label: 'Sub Standards '}
        })
		
		
		
		
		.state('app.mdstds', {
            url: '/mdstds',
            templateUrl: 'templates/settings/mdstds.html',
             controller: 'mdstdsController',
			 cache: false,
			  
				resolve: {
					  alldata: function ($q, $http,$rootScope,configparam,$localstorage) {
						var exists=$localstorage.getObject(configparam.AppName);
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'settingapi/mdstdsdata/'+exists.uid,data:{pl:25,pn:1}}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: { parent:'app.settings', label: 'Mds/Tds Lists '}
        })
		
		.state('app.addmdstds', {
            url: '/addmdstds',
            templateUrl: 'templates/settings/mdstdsadd.html',
             controller: 'mdstdsaddController',
			 cache: false,
			  
				resolve: {
					  alldata: function ($q, $http,$rootScope,configparam,$localstorage) {
						var exists=$localstorage.getObject(configparam.AppName);
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'settingapi/mdstdspredata/'+exists.uid,data:{pl:25,pn:1}}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: { parent:'app.mdstds', label: 'Add Mds/Tds '}
        })
		
		.state('app.editmdstds', {
            url: '/editmdstds/:id',
            templateUrl: 'templates/settings/mdstdsadd.html',
             controller: 'mdstdseditController',
			 cache: false,
			  
				resolve: {
					  alldata: function ($q, $http,$rootScope,configparam,$localstorage,$stateParams) {
						var exists=$localstorage.getObject(configparam.AppName);
						var deferred = $q.defer();
                        var id=$stateParams.id;
                          $http({method: 'PUT',	url:$rootScope.apiurl+'settingapi/mdstdsedit/'+exists.uid,data:{id:id}}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: { parent:'app.mdstds', label: 'Edit Mds/Tds '}
        })
		.state('app.dropdowns', {
            url: '/dropdowns',
            templateUrl: 'templates/settings/dropdownlist.html',
          	 
			  controller: 'dropdownController',
			 resolve: {
					  alldata: function ($q, $http,$localstorage,$rootScope) {
						
						var deferred = $q.defer();
						
						$http({method: 'GET',	url:$rootScope.apiurl+'settingapi/dropdowns',}).then(function(data,response) {
					
						  deferred.resolve(data);
						});
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'app.settings', label: 'Dropdown list'}
        })
		
		.state('app.dropdowntemp', {
            url: '/dropdowntemp',
            templateUrl: 'templates/settings/dropdowntemp.html',
          	 
			  controller: 'tempController',
			 resolve: {
					  alltempdata: function ($q, $http,$localstorage,$rootScope) {
						
						var deferred = $q.defer();
						
						$http({method: 'GET',	url:$rootScope.apiurl+'settingapi/droptemp',}).then(function(data,response) {
					
						  deferred.resolve(data);
						});
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'app.settings', label: 'Temperature'}
        })
		
		
		
		
		
				
		.state('app.dropdownload', {
            url: '/dropdownload',
            templateUrl: 'templates/settings/dropdownload.html',
          	 
			  controller: 'loadController',
			 resolve: {
					  allloaddata: function ($q, $http,$localstorage,$rootScope) {
						
						var deferred = $q.defer();
						
						$http({method: 'GET',	url:$rootScope.apiurl+'settingapi/dropload',}).then(function(data,response) {
					
						  deferred.resolve(data);
						});
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'app.settings', label: 'Load'}
        })



.state('app.methods', {
            url: '/methods',
            templateUrl: 'templates/settings/testmethods.html',
          	 
			  controller: 'methodsController',
			  cache: false,
			 resolve: {
					  alldata: function ($q, $http,$localstorage,$rootScope) {
						
						var deferred = $q.defer();
						
						$http({method: 'GET',	url:$rootScope.apiurl+'settingapi/methods',}).then(function(data,response) {
					
						  deferred.resolve(data);
						});
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'app.settings', label: 'Test Methods'}
        })
		
		
		
		
		
		
		.state('app.changepassword', {
            url: '/changepassword',
            templateUrl: 'templates/settings/changepassword.html',
          	 
			controller: 'changepwdController',
			
						
			 ncyBreadcrumb: { parent:'app', label: 'Change Password'}
        })
/*---------------------Batch code registers info----------------------*/		
		.state('app.bcregdetails', {
            url: '/bcregdetails/',
            templateUrl: 'templates/common/bcregdetails.html',
             
			controller: 'bcregdetailController',
			  	resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/bcregdetails/30/1'}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: {label: 'All Details'}
		})
/*-----RECEIPT TEST------------------------------------------*/

		
		.state('app.receipt', {
            url: '/receipt',
            templateUrl: 'templates/rir/receipt.html',
            controller: 'rirController',
			cache: false,
			  
				resolve: {
					  alldata: function ($q, $http,$rootScope,$localstorage,configparam) {
						var exists=$localstorage.getObject(configparam.AppName);
						var filter=$localstorage.getObject('rirfilter2');
							if(_.isEmpty(filter))
							{
								filter={Industry:0,User:0,Status:0,Searchtxt:"",sortdesc:false,sortfor:""}
								
								$localstorage.setObject('rirfilter2',filter);
								
							}
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'api/rirfiltdata/'+exists.uid, data:{pn:1,pl:30}}).then(function(data) {
                            //console.log(data);
							deferred.resolve(data);
							}, function (error) {
									console.log(error);
                deferred.reject({redirectTo: 'app.permdenied'});
                          });
                         
						return deferred.promise;
					  }
					},
				 ncyBreadcrumb: { parent:'app.home', label: 'Sample Registeration'}
        })
		
		.state('app.receiptadd', {
            url: '/receiptadd',
            templateUrl: 'templates/rir/receipt.add.html',
          	  
			  controller: 'riraddController',
			  cache: false,
			 resolve: {
					  allpredata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/rirpredata'}).then(function(data) {
							 // console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: { parent:'app.receipt', label: 'New Sample Registeration'}
        })
		
		
		
		.state('app.receiptedit', {
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
					  
			 ncyBreadcrumb: { parent:'app.receipt', label: 'Edit Sample '}
        })
		

			.state('app.tests', {
            url: '/tests/:tid/:sid',
            templateUrl: 'templates/tests/tests.html',
              	 
				controller: 'testsController',
				cache: false,
				resolve: {
					  alldata: function ($q, $http,$stateParams,$rootScope,configparam,$localstorage) {
						var exists=$localstorage.getObject(configparam.AppName);
						console.log(exists);
						var uid=exists.uid;
						var tid=$stateParams.tid;
						var sid=$stateParams.sid;
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'api/testdata/'+uid,
						  data:{TestId:tid,SecId:sid,pl:30,pn:1}}).then(function(data) {
                            console.log(data);
							deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.home', label: 'Tests {{getperm.Section}}'}
        })
		
		.state('app.testobsadd', {
            url: '/testobsadd/:id',
            templateUrl: 'templates/tests/testobsadd.html',
              	  
			  controller: 'testobsaddController',
			  cache: false,
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/testobseditdata/'+id}).then(function(data) {
						//	  //console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:"app.tests({tid:tid,sid:46})", label: 'Add/Edit Test report '}
        })	
		
		
		
	

		.state('app.activitylog', {
            url: '/activitylog',
            templateUrl: 'templates/appsetting/log.html',
              	 
				controller: 'LogController',
				cache: false,
				resolve: {
					  alldata: function ($q, $http,$stateParams,$rootScope,configparam,$localstorage) {
						var exists=$localstorage.getObject(configparam.AppName);
						console.log(exists);
						var uid=exists.uid;
						
						var deferred = $q.defer();
                        
                          $http({method: 'PUT',	url:$rootScope.apiurl+'adminapi/activitylog/'+uid,data:{pl:30,pn:1}}).then(function(data) {
                            console.log(data);
							deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.home', label: 'Recent Activity Log'}
        })
		
		
		
		.state('app.external', {
            url: '/external',
            templateUrl: 'templates/tests/external.html',
              	  
			  controller: 'externalController',
			 resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/externaldata/30/1'}).then(function(data) {
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.home', label: 'External Upload'}
        })
		
		.state('app.externaladd', {
            url: '/externaladd/:id',
            templateUrl: 'templates/tests/externaladd.html',
              	  
			  controller: 'externaladdController',
			 resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'api/externaleditdata/'+id}).then(function(data) {
						//	  //console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.external', label: 'Add External result '}
        })

	
		
.state('app.testconditions', {
            url: '/testconditions',
            templateUrl: 'templates/appsetting/testconditions.html',
              	 
				controller: 'testconditionsController',
				cache: false,
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/testconditions'}).then(function(data) {
							  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.tests', label: 'Test Conditions'}
        })
		
		
		
		

/*-------Admin System-------*/

		.state('app.testparams', {
            url: '/testparams',
            templateUrl: 'templates/appsetting/testparams.html',
              	 
				controller: 'testparamsController',
				cache: false,
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/testparams'}).then(function(data) {
							  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.tests', label: 'Test Parameters'}
        })
		
		.state('app.paramcats', {
            url: '/paramcats',
            templateUrl: 'templates/appsetting/paramcats.html',
              	 
				controller: 'paramcatsController',
				cache: false,
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'settingapi/paramcats'}).then(function(data) {
							  console.log(data);
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			 ncyBreadcrumb: {  parent:'app.tests', label: 'Parameters Category'}
        })
	
		

		
		
		
		
		.state('app.certificates', {
            url: '/certificates',
            templateUrl: 'templates/certificate/certificates.html',
			 
		    controller: 'certificateController',
			cache: false,
			 resolve: {
					  alldata: function ($q, $http,$rootScope) {
										var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'certapi/cert/25/1'}).then(function(data) {
                           console.log(data);
							deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: { parent:'app.home', label: 'Certificates '}
        })
		
		.state('app.certificateadd', {
            url: '/certificateadd',
            templateUrl: 'templates/certificate/certificate.add.html',
          	  
			  controller: 'certificateaddController',
			  	resolve: {
					  allpredata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'certapi/certpredata'}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
		
			 ncyBreadcrumb: { parent:'app.certificates', label: 'Add Certificate'}
        })
		
		.state('app.certificateedit', {
            url: '/certificateedit/:id',
            templateUrl: 'templates/certificate/certificate.add.html',
          	  
			  controller: 'certificateeditController',
			  	resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'certapi/certeditpredata/'+id}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
		
			 ncyBreadcrumb: { parent:'app.certificates', label: 'Add Certificate'}
        })
		
		
		.state('app.certformats', {
            url: '/certformats',
            templateUrl: 'templates/certificate/certformats.html',
             controller: 'CertformatsController',
			  
				resolve: {
					  alldata: function ($q, $http,$rootScope) {
						
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'certapi/certformats'}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
			ncyBreadcrumb: { parent:'app.settings', label: 'Standards'}
        })


		
		.state('app.newcertificates', {
            url: '/newcertificates',
            templateUrl: 'templates/certificate/newcertificates.html',
			 
		    controller: 'newcertificateController',
			 resolve: {
					  alldata: function ($q, $http,$rootScope) {
										var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'certapi/cert/25/1'}).then(function(data) {
                           console.log(data);
							deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
					
			 ncyBreadcrumb: {  label: 'New Certificates '}
        })
		
	
		
		.state('app.newcertificateedit', {
            url: '/newcertificateedit/:id',
            templateUrl: 'templates/certificate/newcertificate.add.html',
          	  
			  controller: 'newcertificateeditController',
			  	resolve: {
					  allpredata: function ($q, $http,$rootScope,$stateParams) {
						var id=$stateParams.id;
						var deferred = $q.defer();
                        
                          $http({method: 'GET',	url:$rootScope.apiurl+'certapi/certeditpredata/'+id}).then(function(data) {
							  
                            deferred.resolve(data);
                          });
                         
						return deferred.promise;
					  }
					},
		
			 ncyBreadcrumb: { parent:'app.newcertificates', label: 'Add Certificate'}
        })
		
		
		$locationProvider.html5Mode(true);
		
}]);

App.config(function(uiSelectConfig) {
  uiSelectConfig.theme = 'bootstrap';
});

App.config(function(paginationTemplateProvider) {
    paginationTemplateProvider.setPath('js/dirPagination.tpl.html');
});

// /////////////////Config to set extension for uploading/////////////////////////
 App.config([
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
                        disableImageResize: false,
                            maxFileSize: 300000000,
                        acceptFileTypes: /(\.|\/)(pdf|doc|docx|xls|txt|xlsx|gif|jpe?g|png)$/i
                    });                 
            }
        ]);
		
App.controller('pagesetcntrl',function($scope,$http,$localstorage,$window,$location,
$rootScope,$state,AuthenticationService,)
{

	$scope.layout={};
	$scope.layout.fluid="container-fluid";
  	
	$rootScope.appsetconfig=$localstorage.getObject('appsetconfig');
	if(_.isEmpty($scope.appsetconfig))
	{
		var appsetconfig={IsQuote: 1};
		$localstorage.setObject('appsetconfig',appsetconfig);
		$rootScope.appsetconfig=appsetconfig;
	}
	
	
	$scope.saveconfig=function(param)
	{
		$localstorage.setObject('appsetconfig',param);
			$rootScope.appsetconfig=$localstorage.getObject('appsetconfig');
			$state.reload();
	}
});
	

App.controller('ErrorController',function($scope,$http,$localstorage,$window,$location,
$rootScope,$state,AuthenticationService,)
{

	$scope.layout={};
	$scope.layout.fluid="container-fluid";
	
	var exists=$localstorage.getObject(configparam.AppName);
	
	if(angular.isUndefined(exists) || _.isEmpty(exists))
	{
		console.log("Error");
		$state.go('login');	
		
	}
	
  
});
	
		
App.controller('appController',function($scope,$http,$localstorage,toaster,$idle,$uibModal,$translate,$window,$location,$interval,configparam,$browser,
$rootScope,$state,AuthenticationService,$timeout,allpredata,NotifyService)
{
	//console.log(allpredata);
	
	$rootScope.appsetconfig=$localstorage.getObject('appsetconfig');
	
	$scope.permissions=allpredata.data.permissions;
	$scope.groups=allpredata.data.groups;
	$scope.pagetests=allpredata.data.tests;
	$scope.mynot={};
	$scope.mynot.allnotis=allpredata.allnotis;
	//console.log($scope.mynot.allnotis);
	 $translate.use('en');
	//console.log($scope.permissions);
	
	$scope.app={};
	$scope.app.year=  ((new Date()).getFullYear());
	$scope.app.name=  "Infocodec";
	$scope.appset=allpredata.data.appset;
	$scope.app.isindustry=false;
	$scope.userexists=$localstorage.getObject(configparam.AppName);
	$rootScope.msearch={};
	$rootScope.msearch.mystext="";
	

	
	
	
	
	
	  var exists=$localstorage.getObject(configparam.AppName);
	$rootScope.dboard=exists;
	if(angular.isUndefined(exists) || _.isEmpty(exists))
	{
		//console.log("Error");
		$state.go('app.error');	
		
	}
	
	$scope.$on('IdleStart', function() {
		console.log(" the user appears to have gone idle");
	});
	
	
	  function closeModals() {
                if($scope.warning) {
                    $scope.warning.close();
                    $scope.warning = null;
                }
                if($scope.timedout) {
                    $scope.timedout.close();
                    $scope.timedout = null;
                }
            }
			
			 $scope.$on('IdleStart', function() {
                closeModals();
                $scope.warning = $uibModal.open({
                    templateUrl: 'templates/common/warning-dialog.html',
                    windowClass: 'modal-warning'
                });
            });
            $scope.$on('IdleEnd', function() {
                closeModals();
            });
            $scope.$on('IdleTimeout', function() {
                closeModals();
				$scope.logout();
                $scope.timedout = $uibModal.open({
                    templateUrl: 'templates/common/timedout-dialog.html',
                    windowClass: 'modal-danger'
                });
            });
            $scope.start = function() {
                console.log('start');
                closeModals();
                Idle.watch();
                $scope.started = true;
            };
            $scope.stop = function() {
                console.log('stop');
                closeModals();
                Idle.unwatch();
                $scope.started = false;
            };
	
function chunk(arr, size) {
  var newArr = [];
  for (var i=0; i<arr.length; i+=size) {
    newArr.push(arr.slice(i, i+size));
  }
  return newArr;
}
//console.log(_.toArray(allpredata.data.tests));
$scope.chunkedData = chunk(_.toArray(allpredata.data.tests), _.toArray(allpredata.data.tests).length/2);
	console.log($scope.chunkedData);
	$scope.searchrirdata=function(searchtext)
	{
		$state.go('app.searchrir',{searchtext:searchtext});
	}
	
	$scope.getallnotis=function()
	{
		var id=$scope.userexists.uid;
		NotifyService.loadNotis(id).then(function(data){
			//console.log(data);
			var oldlength=$scope.mynot.allnotis.length;
			if(data.length>oldlength)
			{
				//pop new toaster 
				console.log("Pop toaster");
				var diff = _.difference(_.pluck(data, "Id"), _.pluck($scope.mynot.allnotis, "Id"));
				var d3= _.filter(data, function(obj) { return diff.indexOf(obj.Id) >= 0; });;
				
				if(!_.isEmpty(d3))
				{
	   $scope.toastermodalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'templates/notification.html',
		scope:$scope,
         size: "md",
   
		});
				}
				
				$scope.mynot.allnotis=angular.copy(data);
			}
			else
			{
				 //toaster.pop({ type: 'info', body: 'Pop No toaster', toasterId: 11 });
				console.log("Pop No toaster");
			}
			
		})
	}
	
	
	
	$scope.markallread=function()
	{
		var id=$scope.userexists.uid;
		NotifyService.readallnotice(id).then(function(data){
			console.log(data);
			$scope.mynot.allnotis=data;
		})
	}
	
	
	var stop=$interval(function() {
		$scope.getallnotis();
		
	},5500);
	
	$scope.$on('$destroy',function(){
		//console.log(stop);
    if(stop)
        $interval.cancel(stop);   
});

		$scope.logout=function()
	{
		var tok=$localstorage.getObject(configparam.AppName);
		$http({
				method:'POST',
				url:'admin/api/logout',
				data:{token:tok.myworld}
			}).then(function successCallback(data) {
		
		delete $window.sessionStorage.token;
			AuthenticationService.isAuthenticated = false;
			$rootScope.loginloadingView = false;
			$localstorage.remove(configparam.AppName);
			$state.go('login');
			
				}, function errorCallback(data){
					toaster.pop({toasterId:11,type:"error",body:data.data});
						delete $window.sessionStorage.token;
			AuthenticationService.isAuthenticated = false;
			$rootScope.loginloadingView = false;
			$localstorage.remove(configparam.AppName);
			$state.go('login');
				$scope.disableload=false;
			});	
	}
	
	$scope.printDiv = function(divName) {
		
  var printContents = document.getElementById(divName).innerHTML;
  var popupWin = window.open('', '_blank', 'width=800,height=600');
   popupWin.window.focus();
  popupWin.document.open();
  popupWin.document.write('<!DOCTYPE html><html><head> <base href="'+$browser.baseHref()+'"><link href=\"css/bootstrap.min.css\" rel=\"stylesheet\"> <link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\" /><link rel=\"stylesheet\" type=\"text/css\" href=\"css/mystyle.css\" /></head><body onload="window.print()" >' + printContents + '</body></html>');
  popupWin.document.close();
  setTimeout(function () {  popupWin.close();  }, 200);
  //window.onfocus=function(){ window.close();}
  //popupWin.close();
  
   return true;
}   

 $scope.flags={};
	 $scope.flags.loadingdata=false;
	 
	 	
	 //  <link rel=\"stylesheet\" type=\"text/css\" href=\"css/mystyle.css\" /><style>@media print {.mybg{background-color:#f2f45f;}thead{  display:table-header-group;   max-height:250px;/*repeat table headers on each page*/}tbody{  display:table-row-group;}tfoot{  display:table-footer-group;/*repeat table footers on each page*/} }</style>
	 
	
	
			
  
});
/*---------------------------Directive----------------------*/
		
		
App.controller('searchController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	console.log(allpredata);
	
	$rootScope.msearch.mystext="";
	$scope.pageSize=25;
	$scope.currentPage=1;
	$scope.issearchflag=true;
	
	$scope.allsearchrirs=allpredata.data.allrirs;
$scope.totalcount=allpredata.data.totalcount;
	// $scope.searchrirdata=function(searchtext)
// {
	// if(!_.isEmpty(searchtext))
	// {
	// $scope.flags.loadingdata=true;
	// var pageno=1;
		// //console.log(pageno);
		// $http({	
					// method:'POST',
					// url:$rootScope.apiurl+'api/searchrir/',	
					// data:{text:searchtext,pageSize:10}
	     	 
	     			// }).then(function successCallback(data) {
					
				
		// $scope.totalcount=0;//data.totalitems;
		// $scope.flags.loadingdata=false;
			     				
	     			// }, function errorCallback(data) {
	     			// console.log(data);
					// $scope.flags.loadingdata=false;
	     		// });
	// }
	
//}

	
	
  
});

	App.config(function (ChartJsProvider) {
    // Configure all charts
    ChartJsProvider.setOptions({
      colors: ['#97BBCD', '#DCDCDC', '#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'],
	  responsive: true
    });
    // Configure all doughnut charts
    ChartJsProvider.setOptions('doughnut', {
      cutoutPercentage: 60
    });
  
  });	
 
App.controller('custdashboardController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	console.log(allpredata);
$scope.reg={};
$scope.reg.Filter="L7D";
$scope.reg.Locations=0;
	$scope.allrirs=allpredata.data.allrirs;
	$scope.allquotes=allpredata.data.allquotes;
	
	$scope.currentPage=1;
	$scope.pageSize=15;
	 $scope.getpdfview=function(pdf)
	{
		$scope.pdf=pdf;
			$scope.tempurl='templates/common/pdfview.html';
						$scope.fullviewpdfInstance = $uibModal.open({
						keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
						templateUrl: 'templates/common/pdfview.html',
						scope:$scope,
						
						
						});
			     				
		
	}
	
	
	
	$scope.closepdfModal=function()
	{
		$scope.fullviewpdfInstance.dismiss('cancel');
	}
	
	
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
 
 
	
  
});
  
  
App.controller('dashboardController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	console.log(allpredata);
$scope.reg={};
$scope.reg.Filter="L7D";
$scope.reg.Locations=0;
$scope.polarlabels=[];
$scope.polardata =[];
$scope.indwiselabels=[];
$scope.indwise={};
$scope.polarlabels30=[];
$scope.polardata30=[];	 
$scope.revinddata=[];
	 $scope.colors = ['#45b7cd', '#ff6384', '#ff8e72'];
	 $scope.backgroundColors=  [
       '#803690', '#00ADF9', '#DCDCDC', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'
    ];
	
	
	
	
	$scope.mybackgroundColors=function(idx)
	{
		return $scope.myObj = {     
        "background-color" : $scope.backgroundColors[idx],      
			}
	}
	
	
	$scope.certcount=allpredata.data.certcount;
	$scope.custcount=allpredata.data.custcount;
	$scope.certapcount=allpredata.data.certapcount;
	$scope.rir=allpredata.data.rir;
	$scope.alltests=allpredata.data.alltest;
	$scope.alltests2=allpredata.data.alltest2;
	$scope.allindtests=allpredata.data.allindtests;
	$scope.FilterAp=allpredata.data.FilterAp;
	$scope.allfilts=angular.copy(allpredata.data.allfilts);		
	$scope.revenuedata=allpredata.data.revenuedata;
	$scope.qdates=allpredata.data.qdates;
	 $scope.polarlabels30=_.pluck($scope.qdates,'SDate');
	  $scope.polardata30=_.pluck($scope.qdates,'STotal');
	$scope.currency=allpredata.data.currency;
	 $scope.alllocations=allpredata.data.branches;
	
	 $scope.polarlabels=_.keys($scope.alltests);
	 $scope.revindlabels=_.keys($scope.alltests);

	
	 
$scope.loaddash=function()
{
	  angular.forEach($scope.alltests,function(val,key)
	  {
		 
		  $scope.indwise[key]={};
		  $scope.indwise[key].labels=[];
		   $scope.indwise[key].ldata=[];
		     angular.forEach(val,function(t){
			$scope.indwise[key].labels.push(t.name);
				$scope.indwise[key].ldata.push(t.tot);
			  
		  })
		  
		  $scope.showgraph($scope.indwise[key]);
		
	  });
	  
	    
	 angular.forEach($scope.polarlabels,function(val)
	  {
		  var inds=$scope.alltests[val];
		 
		  var tot=0
		  
		  angular.forEach(inds,function(t){
			
			  tot= tot+ parseInt(t.tot);
		  })
		$scope.polardata.push(tot);
	});
	
	
}
	
	
	
	
	
	// for(var i=0; i<$scope.alltests.length;i++)
	// {
		
		 // $scope.polardata.push($scope.alltests[i].tot);
	// }
	
	//console.log($scope.polarlabels);
	$scope.polarlabels3=['Total Invoice','Payement received', 'Quotation','Inventory'];
	$scope.polardata3 =allpredata.data.polardata2;
	
	 $scope.polarlabels2 = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
    $scope.series = ['This Week'];

    $scope.polardata2 = [
      [65, 59, 80, 81, 56, 55, 44]
     
    ];
	
	$scope.myvalue=[];
	$scope.showgraph=function(value)
	{
		//console.log(value);
		$scope.myvalue=value;
	}
	
	$scope.revinddata=[];
	 angular.forEach($scope.revindlabels,function(val)
	  {
		  var inds=$scope.allindtests[val];
		  //console.log(inds);
		  var tot=0
		  
		  angular.forEach(inds,function(t){
			//  $scope.indwise[val].indwiselabels.push(t.name);
			  tot= tot+ parseInt(t.tot);
		  })
		$scope.revinddata.push(tot);
		});
		

	
	$scope.loaddash();
	
	$scope.dash={};
	$scope.dash.DDate=new Date();
	$scope.chart={};
	$scope.colors=['#009688','#ffc107','#e91e63'];
	
$scope.options = {
    tooltipEvents: [],
    showTooltips: true,
    tooltipCaretSize: 1,
	legend: { display: false,position:'bottom',align:'center'},	
	title: {
                display: false,
                text: 'Tests Industry Wise'
            },
    onAnimationComplete: function () {
        this.showTooltip(this.segments, true);
    },
};
$scope.options3 = {
    tooltipEvents: [],
    showTooltips: true,
    tooltipCaretSize: 1,
	legend: { display: true,position:'right',align:'center'},	
	title: {
                display: true,
                text: ''
            },
    onAnimationComplete: function () {
        this.showTooltip(this.segments, true);
    },
};
var labels = "";//Utils.months({count: 7});

$scope.options2 = {
    tooltipEvents: [],
    showTooltips: true,
    tooltipCaretSize: 0,
	legend: { display: false,position:'right',align:'center'},
	title: {
                display: false,
                text: '',
				fullSize:true
            },
			 scales: {
				 xAxes:[
				 {nameGap: 50,
          nameLocation: 'center',
				 offset: 0,
				   boundaryGap: false,
				   axisPointer: {
            lineStyle: {
              color: '300',
              type: 'dashed'
            }
          },
          splitLine: {
            show: false
          },
          axisLine: {
            lineStyle: {
              color: '#000',
              type: 'dashed'
            }
          },
          axisTick: {
            show: false
          },
		   axisLabel: {
            //color: utils.getGrays()['400'],
            formatter: function formatter(value) {
              var date = new Date(value);
              return "".concat(date.getDate(), " ").concat(months[date.getMonth()], " , 21");
            },
            margin: 20
          },
		  gridLines: {
		   display: false,}
		  },
		   ],
        yAxes: [
		{
        // type: 'value',
          //name: 'Closed Amount',
          nameGap: 85,
          nameLocation: 'middle',          
         splitNumber: 3,
          axisPointer: {
            show: false
          },         
          boundaryGap: false,
          axisLabel: {
            show: true,           
            margin: 5
          },
          axisTick: {
            show: false
          },
          axisLine: {
            show: false
          },
		   gridLines: {
		   display: false,}
		  
		}
        ]		
      },
	   
    onAnimationComplete: function () {
        this.showTooltip(this.segments, true);
    },
};
	console.log($scope.groups['Rir']);

	
	

 $scope.flags={};
	 $scope.flags.loadingdata=false;
	 
	 
	 
	 $scope.fetchinfo=function(reg)
	 { var id=$rootScope.dboard.uid;
		 	$http({
				method:'PUT',
				url:'admin/adminapi/getdashinfo/'+id,
				data:reg
			}).then(function successCallback(data) {
				console.log(data);
				allpredata=data;
					$scope.certcount=allpredata.data.certcount;
	$scope.custcount=allpredata.data.custcount;
	$scope.certapcount=allpredata.data.certapcount;
	$scope.rir=allpredata.data.rir;
	$scope.alltests=allpredata.data.alltest;
	$scope.allindtests=allpredata.data.allindtests;
	$scope.FilterAp=allpredata.data.FilterAp;
	$scope.currency=allpredata.data.currency;
		$scope.allfilts=angular.copy(allpredata.data.allfilts);
		$scope.polarlabels=[];
	 $scope.polardata =[];
	  $scope.indwiselabels=[];
	  $scope.polarlabels30=[];
	   $scope.polardata30=[];
	 $scope.indwise={};
	 $scope.revinddata=[];
	
	$scope.revenuedata=allpredata.data.revenuedata;
	$scope.qdates=allpredata.data.qdates;
	 $scope.polarlabels30=_.pluck($scope.qdates,'SDate');
	  $scope.polardata30=_.pluck($scope.qdates,'STotal');
	  

	 $scope.polarlabels=_.keys($scope.alltests);
	  $scope.revindlabels=_.keys($scope.alltests);
	 
	   angular.forEach($scope.alltests,function(val,key)
	  {
		  //console.log(key);
		  $scope.indwise[key]={};
		  $scope.indwise[key].labels=[];
		   $scope.indwise[key].ldata=[];
		     angular.forEach(val,function(t){
			$scope.indwise[key].labels.push(t.name);
				$scope.indwise[key].ldata.push(t.tot);
			
		  })
		
	  });
	 angular.forEach($scope.polarlabels,function(val)
	  {
		  var inds=$scope.alltests[val];
		  //console.log(inds);
		  var tot=0
		  
		  angular.forEach(inds,function(t){
			//  $scope.indwise[val].indwiselabels.push(t.name);
			  tot= tot+ parseInt(t.tot);
		  })
		$scope.polardata.push(tot);
		});
	
	//console.log($scope.indwise);
	for(var i=0; i<$scope.alltests.length;i++)
	{
		//$scope.polarlabels.push($scope.alltests[i].name);
		 $scope.polardata.push($scope.alltests[i].tot);
	}
	
	 angular.forEach($scope.revindlabels,function(val)
	  {
		  var inds=$scope.allindtests[val];
		  //console.log(inds);
		  var tot=0
		  
		  angular.forEach(inds,function(t){
			//  $scope.indwise[val].indwiselabels.push(t.name);
			  tot= tot+ parseInt(t.tot);
		  })
		$scope.revinddata.push(tot);
		});
		

	$scope.showgraph($scope.indwise['Environmental']);
	
	
			}, function errorCallback(data){
				toaster.pop("danger","","Error in filtering.");
				$scope.disableload=false;
			});	
	 }
	 
	 
	$scope.getfiltdata=function(reg)
  {
	 
	  console.log(reg);
	  
	  if(reg.Filter==='CD')
	  {
		  reg.From=new Date();
		   reg.To=new Date();
	  }
	  else
	  {
		   $scope.fetchinfo(reg)
	  }
	  
	  
  } 
	
	$scope.open1 = function() {
		$scope.popup1.opened = true;
	};

	$scope.open2 = function() {
		$scope.popup2.opened = true;
	};
	
	$scope.popup1 = {
		opened: false
	};

	$scope.popup2 = {
		opened: false
	};
	  
	 $scope.dateOptions = {
    //customClass: getDayClass,
    maxDate: new Date(),
    showWeeks: false
  };
	

   $scope.today = function() {
		$scope.dt = new Date();
	};

	$scope.refreshdata=function()
	{
		$state.reload();	
	}

  
});
/*---------------------------Directive----------------------*/

App.directive('numbersOnly', function () {
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
	
App.directive('ngMin', function () {
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

App.directive('ngMax', function () {
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
App.config(function ($qProvider) {
    $qProvider.errorOnUnhandledRejections(false);
});

App.run(function(editableOptions,editableThemes) {
	 editableThemes.bs3.inputClass = 'input-sm';
  editableThemes.bs3.buttonsClass = 'btn-sm';
  editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
});

// App.config(function(pdfjsViewerConfigProvider) {

  // pdfjsViewerConfigProvider.setWorkerSrc("/pdf.js/build/pdf.worker.js");
  // pdfjsViewerConfigProvider.setCmapDir("/pdf.js/web/cmaps");
  // pdfjsViewerConfigProvider.setImageDir("/pdf.js/web/images");
// console.log(pdfjsViewerConfigProvider);
  // pdfjsViewerConfigProvider.disableWorker();
  // pdfjsViewerConfigProvider.setVerbosity("infos");  // "errors", "warnings" or "infos"
// });