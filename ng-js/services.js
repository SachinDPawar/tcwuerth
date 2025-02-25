
App.factory('Cart', ['$localstorage','$http','$rootScope','$q',function ($localstorage,$http,$rootScope,$q) {

  var svc = {};	
 
  svc.getsubtotal = function(param){

	
		var subtotal = 0;
		var cart = param;
		//console.log(cart);
        angular.forEach(cart.Details, function(item) {
			if(item.Price !='')
			{
            subtotal += parseFloat(item.Qty) * parseFloat(item.Price);
			}
        });
	    
		  return subtotal;						
   	
  };
  
  
  
   svc.gettotal = function(param,tax){

var quote = param;
		 var total = 0;    
		if(quote.IsTax==='1')
		{
			if(quote.Tax==='')
			{
				quote.Tax=tax;
			}
			//
			 var temptotal =  svc.getsubtotal(param) -  ((quote.Discount*svc.getsubtotal(quote))/100) ;
			 var tax=(temptotal*quote.Tax)/100;
			 quote.TotTax=tax;
			 total=temptotal+tax;
		}
		else
		{
        total =  svc.getsubtotal(quote) -  ((quote.Discount*svc.getsubtotal(quote))/100) ;
		 quote.Tax=0;
		  quote.TotTax=0;
			 total=total;
		}
        return total;
	  					
   	
  };

  return svc;

}]);

App.factory('SampleNames', ['$localstorage','$http','$rootScope','$q',function ($localstorage,$http,$rootScope,$q) {

  var svc = {};	
 
  svc.fetchSamples = function(){

	  var deferred = $q.defer();   
	   
          $http({
           method: 'post',
           url: 'admin/adminapi/getpartnames',
           data: {}
         }).then(function successCallback(response) {
			
		 deferred.resolve(response.data);
         });
		 
		  return deferred.promise;						
   	
  };

  return svc;

}]);

App.factory('SubStdNames', ['$localstorage','$http','$rootScope','$q',function ($localstorage,$http,$rootScope,$q) {

  var svc = {};	
 
  svc.fetchNames = function(){

	  var deferred = $q.defer();   
	   
          $http({
           method: 'post',
           url: 'admin/adminapi/getsubstdnames',
           data: {}
         }).then(function successCallback(response) {
			
		 deferred.resolve(response.data);
         });
		 
		  return deferred.promise;						
   	
  };

  return svc;

}]);

App.factory('MdsTdsNames', ['$localstorage','$http','$rootScope','$q',function ($localstorage,$http,$rootScope,$q) {

  var svc = {};	
 
  svc.fetchNames = function(){

	  var deferred = $q.defer();   
	   
          $http({
           method: 'post',
           url: 'admin/adminapi/getmdstdsnames',
           data: {}
         }).then(function successCallback(response) {
			
		 deferred.resolve(response.data);
         });
		 
		  return deferred.promise;						
   	
  };

  return svc;

}]);


App.factory('NotifyService', ['$localstorage','$http','$rootScope','$q',function ($localstorage,$http,$rootScope,$q) {

  var svc = {};
	
 
  svc.loadNotis = function(param){
	   var deferred = $q.defer();
						
   	 $http({
		method:'GET',
		url:'admin/settingapi/getnotifications/'+param,
						
			}).then(function successCallback(data) {
				//console.log(data);
			var allnotis=data.data.allnotis;
			 deferred.resolve(data.data.allnotis);
			
			}, function errorCallback(data) {
		console.log(data);
	
		}); 
   return deferred.promise;
  };


	svc.readnotice=function(param){
		
		
		   var deferred = $q.defer();
						
   	 $http({
		method:'PUT',
		url:'admin/settingapi/updatenotification/'+param,
						
			}).then(function successCallback(data) {
				console.log(data);
			var allnotis=data.data.allnotis;
			 deferred.resolve(data.data.allnotis);
			
			}, function errorCallback(data) {
		console.log(data);
		//toaster.pop('error', "Shopogiri", "You are not authorized to view category Please Login");
		}); 
   return deferred.promise;
	}
 


svc.readallnotice=function(param){
		
		
		   var deferred = $q.defer();
						
   	 $http({
		method:'PUT',
		url:'admin/settingapi/updateallnotification/'+param,
			data:{UserId:param}			
			}).then(function successCallback(data) {
				console.log(data);
			var allnotis=data.data;
			 deferred.resolve(data.data);
			
			}, function errorCallback(data) {
		console.log(data);
		//toaster.pop('error', "Shopogiri", "You are not authorized to view category Please Login");
		}); 
   return deferred.promise;
	}
	
	
  return svc;

}])
;


App.factory('TestPlans', ['$localstorage','$http','$rootScope','$q',function ($localstorage,$http,$rootScope,$q,$uibModal) {

  var svc = {};
	
 
  svc.addPlan = function(substdid){
	   var deferred = $q.defer();
	   
	     $http({
					method:'PUT',
					url: $rootScope.apiurl+'adminapi/substdparams/'+substdid,
					data:{}				
					}).then(function successCallback(data) {
						
					  $scope.substdplanInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'templates/settings/substdplanaddModal.html',
     	 scope: $scope,
   	 });
	  deferred.resolve(data.data.Parameters);
			}, function errorCallback(data) {
		console.log(data);
	
		}); 
	
   return deferred.promise;
  };


  return svc;

}])
;
