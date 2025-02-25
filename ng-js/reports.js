/****************Reports  Controller****************/

App.controller('ReportsCntrl', function($scope,$http,$rootScope,$state,toaster,$q,
$window,$uibModal,AuthenticationService,$timeout) {

	

});


App.controller('SampleReportsCntrl', function($scope,$http,$rootScope,$state,toaster,$q,
$window,$uibModal,AuthenticationService,alldata,$timeout) {

	$scope.disablebtn=false;
	$scope.disableload=false;
	$scope.reg={};
	$scope.pageSize=25;
	$scope.currentPage=1;
	$scope.allinfo=[];
	$scope.reg.Filter="L7D";
	$scope.report={};
	
	
	
	$scope.samplecurrentPage=1;
	$scope.samplepageSize=20;
	
	
	
	$scope.alldatas=[];
 $scope.labels=[];
  $scope.data=[];
  
  $scope.getfiltername=function(pid)
  {
	  var f=_.findWhere($scope.allfilts,{Id:pid});
	  $scope.report.title=f.Name;
  }
  
  $scope.getfiltdata=function(reg)
  {
	  console.log(reg);
	  	$http({
				method:'POST',
				url:'admin/adminapi/getsamplereports',
				data:reg
			}).then(function successCallback(data) {
				console.log(data);
				$scope.allreports=data.data.reports;
				//$scope.getfiltername(data.data.FilterAp);
		$scope.allfilts=angular.copy(data.data.allfilts);
			}, function errorCallback(data){
				toaster.pop("danger","","Error in filtering.");
				$scope.disableload=false;
			});	
  }

	
	
	
	///////////////////////////
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
	
	



if(angular.isDefined(alldata.data))
	{		
		console.log(alldata);
		$scope.allcust=angular.copy(alldata.data);
		$scope.allreports=alldata.data.reports;
		
		$scope.allfilts=angular.copy(alldata.data.allfilts);
		
		
	}
	
	$scope.exportToExcel=function(tableId){ // ex: '#my-table'
            var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
            $timeout(function(){location.href=exportHref;},100); // trigger download
        }
		
		
		 $scope.exportSheetJS = function(mydata,fn) {
			 
			
  
			 var exportdata=JSON.parse(angular.toJson(mydata));
    var ws = XLSX.utils.json_to_sheet(exportdata);
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Presidents");
    XLSX.writeFile(wb, fn+".xlsx");
  };
  
  

});


App.controller('TestsReportsCntrl', function($scope,$http,$rootScope,$state,toaster,$q,
$window,$uibModal,AuthenticationService,alldata,$timeout) {

	$scope.disablebtn=false;
	$scope.disableload=false;
	$scope.reg={};
	$scope.pageSize=25;
	$scope.currentPage=1;
	$scope.allinfo=[];
	$scope.reg.Filter="L7D";
	$scope.report={};
	
	
	
	$scope.samplecurrentPage=1;
	$scope.samplepageSize=20;
	
	
	
	$scope.alldatas=[];
 $scope.labels=[];
  $scope.data=[];
  
  $scope.getfiltername=function(pid)
  {
	  var f=_.findWhere($scope.allfilts,{Id:pid});
	  $scope.report.title=f.Name;
  }
  
  $scope.getfiltdata=function(reg)
  {
	  console.log(reg);
	  	$http({
				method:'POST',
				url:'admin/adminapi/gettestsreports',
				data:reg
			}).then(function successCallback(data) {
				console.log(data);
				$scope.allreports=data.data.reports;
				//$scope.getfiltername(data.data.FilterAp);
		$scope.allfilts=angular.copy(data.data.allfilts);
			}, function errorCallback(data){
				toaster.pop("danger","","Error in filtering.");
				$scope.disableload=false;
			});	
  }

	
	
	
	///////////////////////////
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
	
	



if(angular.isDefined(alldata.data))
	{		
		console.log(alldata);
		
		$scope.allreports=alldata.data.reports;
		$scope.alltests=alldata.data.alltests;
		
		$scope.allfilts=angular.copy(alldata.data.allfilts);
		
		
	}
	
	$scope.exportToExcel=function(tableId){ // ex: '#my-table'
            var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
            $timeout(function(){location.href=exportHref;},100); // trigger download
        }
		
		
		 $scope.exportSheetJS = function(mydata,fn) {
			 
			
  
			 var exportdata=JSON.parse(angular.toJson(mydata));
    var ws = XLSX.utils.json_to_sheet(exportdata);
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Presidents");
    XLSX.writeFile(wb, fn+".xlsx");
  };
  
  

});



App.controller('CustReportsCntrl', function($scope,$http,$rootScope,$state,toaster,$q,
$window,$uibModal,AuthenticationService,alldata,Excel,$timeout) {

	$scope.disablebtn=false;
	$scope.disableload=false;
	$scope.reg={};
	$scope.pageSize=25;
	$scope.currentPage=1;
	$scope.allinfo=[];
	$scope.reg.Filter="L7D";
	$scope.report={};
	
	$scope.qcurrentPage=1;
	$scope.qpageSize=5;
	
	
	$scope.icurrentPage=1;
	$scope.ipageSize=5;
	
	$scope.prcurrentPage=1;
	$scope.prpageSize=5;
	
	$scope.custcurrentPage=1;
	$scope.custpageSize=5;
	
	$scope.samplecurrentPage=1;
	$scope.samplepageSize=5;
	
	$scope.testcurrentPage=1;
	$scope.testpageSize=5;
	
	$scope.alldatas=[];
 $scope.labels=[];
  $scope.data=[];
  
  $scope.getfiltername=function(pid)
  {
	  var f=_.findWhere($scope.allfilts,{Id:pid});
	  $scope.report.title=f.Name;
  }
  
  $scope.getfiltdata=function(reg)
  {
	  console.log(reg);
	  	$http({
				method:'POST',
				url:'admin/adminapi/gettodayreport',
				data:reg
			}).then(function successCallback(data) {
				console.log(data);
				$scope.info=data.data.reports;
				$scope.getfiltername(data.data.FilterAp);
		$scope.allfilts=angular.copy(data.data.allfilts);
			}, function errorCallback(data){
				toaster.pop("danger","","Error in filtering.");
				$scope.disableload=false;
			});	
  }
/***************Date Filter*************/
	$scope.datefilter=function(reg)
	{
		$scope.disableload=true;
		 $scope.labels=[];
  $scope.data=[];
		$http({
				method:'POST',
				url:'admin/adminapi/custreport',
				data:reg
			}).then(function successCallback(data) {
			//	console.log(data);
				$scope.disableload=false;
				$scope.allinfos=angular.copy(data.data);
			angular.forEach($scope.allinfos,function(val){
				 $scope.labels.push(val.TestName);
				 $scope.data.push( val.Count);
			});
			}, function errorCallback(data){
				toaster.pop("danger","","Error in filtering.");
				$scope.disableload=false;
			});	
	}	
	
	
	$scope.refreshcdata=function()
	{
		$scope.allcorders=[];
        $scope.ctotalitems=0;
	}
	///////////////////////////
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
	
	//////////View Detail Report
	$scope.closeModal=function()
 	{
 		$scope.modalInstance.dismiss('cancel');
 	}

	$scope.vieworder = function (info) {
		$scope.order=info;   
		$scope.modalInstance = $uibModal.open({
		animation: $scope.animationsEnabled,
		templateUrl: 'detailModal',
		scope:$scope,
		size: 'lg',		 
	});
   }
   
   ////////////Export Order
	$scope.separator=",";
	$scope.getHeader1=[];
	$scope.filename = "ordersreport";
	$scope.head=["OrderNo","CName","Amount","OrderDate","Status"];
	$scope.flags={};
	$scope.flags.loadingdata=false;
	
	$scope.exportArray=function(reg)
	{
		$scope.flags.loadingdata=true;
	 	var deferred = $q.defer();
     	$http({method: 'POST',	url:'admin/adminapi/ordreport',
		data:reg
		}).then(function (data) {
		//	console.log(data);

			var temp1=angular.copy(data.data.allorders);
			 var temp=[];
			angular.forEach(temp1,function(val){
			//	console.log(val);
		//	val=_.sortBy(val,'Id');
		var details="";
		angular.forEach(val.OrderDetails,function(item)
		{
			details +="#"+ item.Qnty +" "+item.ProName + " ,";
		});
		var address=val.Address.Address +" " +val.Address.City+" "+val.Address.AddExtra+" "+val.Address.PinCode;
			//console.log(val);
				var tempobj={OrderNo:val.OrderNo,CName:val.Customer,Amount:val.Total,OrderDate:val.OrderDate,Status:val.Status}
							// angular.forEach(val,function(key){
								// //console.log(key);
								// if(key.hasOwnProperty("OrderNo"))
								// {
									// tempobj["OrderNo"]=key.OrderNo;
									
									// if(!$scope.head.includes("OrderNo"))
									// {
										// $scope.head.push("OrderNo");
									// }
									
								// }
								// // else
								// // {
									// // tempobj[key.Customer]=key.Status;
									// // if(!$scope.head.includes(key.Customer))
									// // {
										// // $scope.head.push(key.Customer);
									// // }
								// // }
				
				
				
							// });
					
						temp.push(tempobj);
				
			});
			$scope.head=_.keys(temp[0]);
		//	console.log($scope.head);
		//	console.log(temp);
			$scope.flags.loadingdata=false;
                deferred.resolve(temp);
            }, function (errorData) {
		//		console.log(errorData);
			$scope.flags.loadingdata=false;
            deferred.reject(errorData);
        });
        return deferred.promise;
	//  }
    // else
    // {
    	// toaster.pop("warning","","No Data available for export for these dates.");
    // }
	}
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

if(angular.isDefined(alldata.data))
	{		
console.log(alldata);
		$scope.allcust=angular.copy(alldata.data);
		$scope.info=alldata.data.reports;
		$scope.FilterAp=alldata.data.FilterAp;
		$scope.allfilts=angular.copy(alldata.data.allfilts);
		
		 $scope.getfiltername($scope.FilterAp);
	}
	
	$scope.exportToExcel=function(tableId){ // ex: '#my-table'
            var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
            $timeout(function(){location.href=exportHref;},100); // trigger download
        }
		
		
		 $scope.exportSheetJS = function(mydata,fn) {
			 
			
  
			 var exportdata=JSON.parse(angular.toJson(mydata));
    var ws = XLSX.utils.json_to_sheet(exportdata);
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Presidents");
    XLSX.writeFile(wb, fn+".xlsx");
  };
  
  
  // $scope.invoiceexport=function()
  // {
	  // var invdata=
	  
  // }

});


App.factory('Excel',function($window){
        var uri='data:application/vnd.ms-excel;base64,',
            template='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
            base64=function(s){return $window.btoa(unescape(encodeURIComponent(s)));},
            format=function(s,c){return s.replace(/{(\w+)}/g,function(m,p){return c[p];})};
        return {
            tableToExcel:function(tableId,worksheetName){
                var table=$(tableId),
                    ctx={worksheet:worksheetName,table:table.html()},
                    href=uri+base64(format(template,ctx));
                return href;
            }
        };
    })
	
	App.directive("importSheetJs", [SheetJSImportDirective]);
function SheetJSImportDirective() {
  return {
    scope: false,
    link: function ($scope, $elm) {
      $elm.on('change', function (changeEvent) {
        var reader = new FileReader();
        reader.onload = function (e) {
          var wb = XLSX.read(e.target.result);
          $scope.$apply(function() {
            $scope.data = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]]);
          });
        };
        reader.readAsArrayBuffer(changeEvent.target.files[0]);
      });
    }
  };
}