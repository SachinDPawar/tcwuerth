


App.directive('ngEnter', function () { //a directive to 'enter key press' in elements with the "ng-enter" attribute

        return function (scope, element, attrs) {

            element.bind("keydown keypress", function (event) {
                if (event.which === 13) {
                    scope.$apply(function () {
                        scope.$eval(attrs.ngEnter);
                    });

                    event.preventDefault();
                }
            });
        };
    });

App.directive('emailUnique',  function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) 
        {
		/* element.bind('blur', function (e)*/
		  scope.$watch(function() {
                if (!ngModel || !element.val()) return;
                var keyProperty = scope.$eval(attrs.emailUnique);
                var currentValue = element.val();
           //   console.log(currentValue);
     		//	  console.log(scope.catarray);	
			angular.lowercase = text => text.toLowerCase();
     			  
    			for(var i=0;i<scope.allusers.length;i++)
    			{
						if(angular.lowercase(currentValue)===angular.lowercase(scope.allusers[i].Email))
						{
								if(angular.isUndefined(scope.allusers[i].Id))
								{
									ngModel.$setValidity('unique', false);
								break;
								}					 	
								 else
								 {
								 		ngModel.$setValidity('unique', false);
								break;
								 	}
									
							}    
							else
							{
						//		console.log(scope.storeaddresses[i].StoreAddress);
									ngModel.$setValidity('unique', true);
								
							}
							
    				}
    			
            });
        }
    }
});
App.directive('userUnique',  function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) 
        {
		/* element.bind('blur', function (e)*/
		  scope.$watch(function() {
                if (!ngModel || !element.val()) return;
                var keyProperty = scope.$eval(attrs.userUnique);
                var currentValue = element.val();
           //   console.log(currentValue);
     		//	  console.log(scope.catarray);	
			angular.lowercase = text => text.toLowerCase();
     			 // console.log(scope.allusers);
    			for(var i=0;i<scope.allusers.length;i++)
    			{
						if(angular.lowercase(currentValue)===angular.lowercase(scope.allusers[i].Username))
						{
							 
								if(angular.isUndefined(scope.allusers[i].Id))
								{
									ngModel.$setValidity('unique', false);
								break;
								}					 	
								 else
								 {
								 		ngModel.$setValidity('unique', false);
								break;
								 	}
									
						}    
						else
						{
						//		console.log(scope.storeaddresses[i].StoreAddress);
									ngModel.$setValidity('unique', true);
								
						}
							
    				}
    			
            });
        }
    }
});

App.directive('heatnoUnique',  function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) 
        {
		// element.bind('blur', function (e)
		   scope.$watch(function() 
		  {
                if (!ngModel || !element.val()) return;
                var keyProperty = scope.$eval(attrs.emailUnique);
                var currentValue = element.val();
           //   console.log(currentValue);
     		//	  console.log(scope.catarray);	
			scope.flags.uniqueheatno=false;
								scope.rir.BCGenerated='true';
								scope.flags.uniquebc=[];
     			  
    			for(var i=0;i<scope.heatranges.length;i++)
    			{
					//console.log(scope.heatranges[i].HeatNo);
						if(angular.lowercase(currentValue)===angular.lowercase(scope.heatranges[i].HeatNo))
						{
								ngModel.$setValidity('unique', true);
								scope.flags.uniqueheatno=true;
								scope.flags.uniquebc=scope.heatranges[i].BatchCode;
								//console.log("uniqueheatno");	
								break;
						}    
						// else
						// {
						// //		console.log(scope.storeaddresses[i].StoreAddress);
								// ngModel.$setValidity('unique', true);
								// scope.flags.uniqueheatno=false;
								// scope.rir.BCGenerated='false';
								// scope.flags.uniquebc=="";
								
								// console.log("!uniqueheatno");	
								
						// }
							
    				}
    			
            });
        }
    }
});
App.factory('dataService', ['$http','$rootScope', function ($http,$rootScope) {
        var serviceBase = $rootScope.apiurl+'adminapi/checkunique/',
            dataFactory = {};

        dataFactory.checkUniqueValue = function (id, property, value) {
            if (!id) id = 0;
			var data={table:id,propert:property,value:value};
			console.log(data);
            return $http.post(serviceBase,data).then(
                function (results) {
                    return results.data;
                });
        };

        return dataFactory;

}]);
App.directive('wcUnique', ['dataService','$q', function (dataService,$q) {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) {
			
			
			// element.bind('blur', function (e)		   {
			   
			   ngModel.$asyncValidators.unique = function (modelValue, viewValue) {
                if (!ngModel || !element.val()) return;
                var keyProperty = scope.$eval(attrs.userUnique);
                var currentValue = element.val();
				
				 var deferred = $q.defer(),
                    currentValue =  viewValue,
                    key = scope.$eval(attrs.wcUnique).key,
                    property = scope.$eval(attrs.wcUnique).property;
  
             
                 if (key && property) {
					
                    dataService.checkUniqueValue(key, property, currentValue)
                    .then(function (unique) {
						console.log(angular.fromJson(unique));
						console.log(currentValue);
                        if (angular.fromJson(unique)===currentValue) {
							
							deferred.reject(); //Add unique to $errors
                        }						
						else
						{
							deferred.resolve();
						}
                    });
					  }
                    else {
						
                        deferred.resolve(); //Ensure promise is resolved if we hit this 
                     }
                   return deferred.promise;
      
			   }
       
			
			
        }
    }
}]);



App.directive('passwordValidate', function() {
    return {
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
            ctrl.$parsers.unshift(function(viewValue) {
					
                scope.pwdValidLength = (viewValue && viewValue.length >= 8 ? 'valid' : undefined);
                scope.pwdHasLetter = (viewValue && /[A-z]/.test(viewValue)) ? 'valid' : undefined;
                scope.pwdHasNumber = (viewValue && /\d/.test(viewValue)) ? 'valid' : undefined;

                if(scope.pwdValidLength && scope.pwdHasLetter && scope.pwdHasNumber) {
                    ctrl.$setValidity('pwd', true);
                    return viewValue;
                } else {
                    ctrl.$setValidity('pwd', false);                    
                    return undefined;
                }

            });
        }
    };
});

App.directive('matchpwd', function () {
        return {
            require: 'ngModel',
            restrict: 'A',
            scope: {
                matchpwd: '='
            },
            link: function(scope, elem, attrs, ctrl) {
                scope.$watch(function() {
                    return (ctrl.$pristine && angular.isUndefined(ctrl.$modelValue)) || scope.matchpwd === ctrl.$modelValue;
                }, function(currentValue) {
                    ctrl.$setValidity('matchpwd', currentValue);
                });
            }
        };
    }); 

//var EMAIL_REGEXP = /^\w+@[a-zA-Z0-9_]+?\.[a-zA-Z]{2,3}$/;
var EMAIL_REGEXP = /^[a-zA-Z\'0-9]+([._-][a-zA-Z\'0-9]+)*@([a-zA-Z0-9]+([._-][a-zA-Z0-9]+))+$/;
 App.directive('emailValidate', function() {
   return {
     require: 'ngModel',
     link: function(scope, elm, attrs, ctrl) {
       ctrl.$parsers.unshift(function(viewValue) {
         if (EMAIL_REGEXP.test(viewValue)) {
          // // it is valid
          ctrl.$setValidity('emailValidate', true);
           return viewValue;
         } else {
          // // it is invalid, return undefined (no model update)
           ctrl.$setValidity('emailValidate', false);
           return undefined;
         }
       });
     }
   };
});


App.directive('bcValidate',  function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) 
        {
		/* element.bind('blur', function (e)*/
		  scope.$watch(function() {
                if (!ngModel || !element.val()) return;
                var keyProperty = scope.$eval(attrs.emailUnique);
                var currentValue = element.val();
          
					
						if(angular.lowercase(currentValue)===angular.lowercase(scope.flags.uniquebc))
						{
								ngModel.$setValidity('bcValidate', true);
									
								//break;
						}    
						else
						{
						
								ngModel.$setValidity('bcValidate', false);
							
						}
							
    				
    			
            });
        }
    }
});




/*-----Directive for numbers only----------------*/

App.directive('numbersOnly', function(){
   return {
     require: 'ngModel',
     link: function(scope, element, attrs, modelCtrl) {
       modelCtrl.$parsers.push(function (inputValue) {
           // this next if is necessary for when using ng-required on your input.
           // In such cases, when a letter is typed first, this parser will be called
           // again, and the 2nd time, the value will be undefined
           if (inputValue == undefined) return ''
           var transformedInput = inputValue.replace(/[^0-9]/g, '');
           if (transformedInput!=inputValue) {
              modelCtrl.$setViewValue(transformedInput);
              modelCtrl.$render();
           }         

           return transformedInput;         
       });
     }
   };
});

 App.directive('capitalize', function() {
    return {
      require: 'ngModel',
      link: function(scope, element, attrs, modelCtrl) {
        var capitalize = function(inputValue) {
          if (inputValue == undefined) inputValue = '';
          var capitalized = inputValue.toUpperCase();
          if (capitalized !== inputValue) {
            modelCtrl.$setViewValue(capitalized);
            modelCtrl.$render();
          }
          return capitalized;
        }
        modelCtrl.$parsers.push(capitalize);
        capitalize(scope[attrs.ngModel]); // capitalize initial value
      }
    };
  });
  
  App.directive('validNumber', function() {
      return {
        require: '?ngModel',
        link: function(scope, element, attrs, ngModelCtrl) {
          if(!ngModelCtrl) {
            return; 
          }

          ngModelCtrl.$parsers.push(function(val) {
            if (angular.isUndefined(val)) {
                var val = '';
            }
            
            var clean = val.replace(/[^-0-9\.]/g, '');
            var negativeCheck = clean.split('-');
			var decimalCheck = clean.split('.');
            if(!angular.isUndefined(negativeCheck[1])) {
                negativeCheck[1] = negativeCheck[1].slice(0, negativeCheck[1].length);
                clean =negativeCheck[0] + '-' + negativeCheck[1];
                if(negativeCheck[0].length > 0) {
                	clean =negativeCheck[0];
                }
                
            }
              
            if(!angular.isUndefined(decimalCheck[1])) {
                decimalCheck[1] = decimalCheck[1].slice(0,4);
                clean =decimalCheck[0] + '.' + decimalCheck[1];
            }

            if (val !== clean) {
              ngModelCtrl.$setViewValue(clean);
              ngModelCtrl.$render();
            }
            return clean;
          });

          element.bind('keypress', function(event) {
            if(event.keyCode === 32) {
              event.preventDefault();
            }
          });
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
				//console.log(scope.$eval(attr.ngMin));
                var min = scope.$eval(attr.ngMin) || 0;
				//console.log(min);
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


App.directive('ngMmin', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, elem, attr, ctrl) {
            scope.$watch(attr.ngMmin, function () {
                ctrl.$setViewValue(ctrl.$viewValue);
            });
            var minValidator = function (value) {
				//console.log(scope.$eval(attr.ngMin));
                var min = scope.$eval(attr.ngMmin) || 0;
				//console.log(min);
                if (!_.isEmpty(value) && value < min) {
					
                    ctrl.$setValidity('ngMmin', false);
                    return value;
                } else {
                    ctrl.$setValidity('ngMmin', true);
                    return value;
                }
            };

            ctrl.$parsers.push(minValidator);
            ctrl.$formatters.push(minValidator);
        }
    };
});

App.directive('ngMmax', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, elem, attr, ctrl) {
            scope.$watch(attr.ngMmax, function () {
                ctrl.$setViewValue(ctrl.$viewValue);
            });
            var maxValidator = function (value) {
				
                var max = scope.$eval(attr.ngMmax) || Infinity;
                if (!_.isEmpty(value) && value > max) {
                    ctrl.$setValidity('ngMmax', false);
                    return value;
                } else {
                    ctrl.$setValidity('ngMmax', true);
                    return value;
                }
            };

            ctrl.$parsers.push(maxValidator);
            ctrl.$formatters.push(maxValidator);
        }
    };
});

App.filter('cut', function () {
        return function (value, wordwise, max, tail) {
            if (!value) return '';

            max = parseInt(max, 10);
            if (!max) return value;
            if (value.length <= max) return value;

            value = value.substr(0, max);
            if (wordwise) {
                var lastspace = value.lastIndexOf(' ');
                if (lastspace != -1) {
                  //Also remove . and , so its gives a cleaner result.
                  if (value.charAt(lastspace-1) == '.' || value.charAt(lastspace-1) == ',') {
                    lastspace = lastspace - 1;
                  }
                  value = value.substr(0, lastspace);
                }
            }

            return value + (tail || ' …');
        };
    });