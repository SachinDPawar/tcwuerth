/****************Setting Default Details Controller****************/
App.controller('ImportSettingsCntrl',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.setdefault={};
	$scope.oneclick=false;
	$scope.editflag=true;
	if(angular.isDefined(alldata.data))
	{
		$scope.allindustries=alldata.data.Industries;
		//console.log($scope.setdefault);
	}
		
$scope.dbpageSize=25;
			$scope.dbcurrentPage=1;
			$scope.dbtotalitems=0;
			
$scope.resetfile=function()
{
	$state.transitionTo($state. current, {}, { reload: true, inherit: false, notify: false }); 
}


 $scope.SelectFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.UploadSubStd = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessSubStdExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessSubStdExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mysubstddata={};
            $scope.ProcessSubStdExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mysubstddata.heads=cols;
                    $scope.mysubstddata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mysubstddata.IsVisible = 1;
                });
            };
			
			
			$scope.uploadsubstdtodb=function(con,log)
			{
				

				if(con=='confirm')
				{
					$scope.confirmModal = true;
					
					$scope.log=$scope.mysubstddata;
				
					
					 $scope.dbmodalInstance = $uibModal.open({
				keyboard:false,
				backdrop:"static",
			   templateUrl: 'confirmdbModal.html',
					 scope: $scope,
				 
			 });
					
				}
				else if(con=='approved')
				{
					
					console.log(log);
					
					$scope.issaving=true;
					$http({
		    		 method:'POST',
		       		 url:'admin/adminapi/importsubstd/',
					data:log
		   		 }).then(function successCallback(data) {
					$scope.confirmModal = false;
					$scope.issaving=false;
					$scope.mysubstddata={};
					$scope.closedbconfirmModal();
					$scope.closestdimpmodal();
					
					console.log(data);
					//$rootScope.getallsub();
		        	}, function errorCallback(data) {
						toaster.pop({toasterId:11,type:"error",body:data.data.msg});
		        		console.log(data);
						$scope.issaving=false;
				});
		}

	}

		
			
 $scope.SelectTMFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.UploadTM = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessTMExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessTMExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mytmdata={};
            $scope.ProcessTMExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mytmdata.heads=cols;
                    $scope.mytmdata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mytmdata.IsVisible = 1;
                });
            };
			
			
			$scope.uploadtmtodb=function(con,log)
			{
				

				if(con=='confirm')
				{
					$scope.confirmModal = true;
					
					$scope.log=$scope.mytmdata;
				
					
					 $scope.dbmodalInstance = $uibModal.open({
				keyboard:false,
				backdrop:"static",
			   templateUrl: 'confirmdbModal.html',
					 scope: $scope,
				 
			 });
					
				}
				else if(con=='approved')
				{
					
					console.log(log);
					
					$scope.issaving=true;
					$http({
		    		 method:'POST',
		       		 url:'admin/adminapi/importtestmethod/',
					data:log
		   		 }).then(function successCallback(data) {
					$scope.confirmModal = false;
					$scope.issaving=false;
					$scope.mytmdata={};
					$scope.closedbconfirmModal();
					$scope.closestdimpmodal();
					
					console.log(data);
					//$rootScope.getallsub();
		        	}, function errorCallback(data) {
						toaster.pop({toasterId:11,type:"error",body:data.data.msg});
		        		console.log(data);
						$scope.issaving=false;
				});
		}

	}

		
						
		
		
 $scope.SelectMdsFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.UploadMds = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessMDSExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessMDSExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mymdstdsdata={};
            $scope.ProcessMDSExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mymdstdsdata.heads=cols;
                    $scope.mymdstdsdata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mymdstdsdata.IsVisible = 1;
                });
            };
			
			
			$scope.uploadmdstdstodb=function(con,log)
			{
				

				if(con=='confirm')
				{
					$scope.confirmModal = true;
					
					$scope.log=$scope.mymdstdsdata;
				
					
					 $scope.dbmodalInstance = $uibModal.open({
				keyboard:false,
				backdrop:"static",
			   templateUrl: 'confirmmdsdbModal.html',
					 scope: $scope,
				 
			 });
					
				}
				else if(con=='approved')
				{
					
					console.log(log);
					
					$scope.issaving=true;
					$http({
		    		 method:'POST',
		       		 url:'admin/adminapi/importmdstds/',
					data:log
		   		 }).then(function successCallback(data) {
					$scope.confirmModal = false;
					$scope.issaving=false;
					$scope.mysubstddata={};
					$scope.closedbconfirmModal();
					$scope.closestdimpmodal();
					
					console.log(data);
					//$rootScope.getallsub();
		        	}, function errorCallback(data) {
						toaster.pop({toasterId:11,type:"error",body:data.data.msg});
		        		console.log(data);
						$scope.issaving=false;
				});
		}

	}

		
	/****---------*/

 $scope.SelectRIRFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.UploadRIR = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessRIRExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessRIRExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.myrirdata={};
            $scope.ProcessRIRExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.myrirdata.heads=cols;
                    $scope.myrirdata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.myrirdata.IsVisible = 1;
                });
            };
			
			
			$scope.uploadrirtodb=function(con,log)
			{
				

				if(con=='confirm')
				{
					$scope.confirmModal = true;
					
					$scope.log=$scope.myrirdata;
				
					
					 $scope.dbmodalInstance = $uibModal.open({
				keyboard:false,
				backdrop:"static",
			   templateUrl: 'confirmrirdbModal.html',
					 scope: $scope,
				 
			 });
					
				}
				else if(con=='approved')
				{
					
					console.log(log);
					
					$scope.issaving=true;
					$http({
		    		 method:'POST',
		       		 url:'admin/importapi/importsamples/',
					data:log
		   		 }).then(function successCallback(data) {
					 console.log(data);
					$scope.confirmModal = false;
					$scope.issaving=false;
					$scope.myrirdata={};
					$scope.closedbconfirmModal();
					$scope.closestdimpmodal();
					
					console.log(data);
					//$rootScope.getallsub();
		        	}, function errorCallback(data) {
						toaster.pop({toasterId:11,type:"error",body:data.data.msg});
		        		console.log(data);
						$scope.issaving=false;
				});
		}

	}

			
		/****--CHEM-------*/

 $scope.SelectCHEMFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.UploadCHEM = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessCHEMExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessCHEMExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mychemdata={};
         $scope.ProcessCHEMExcel = function (data) {
    // Read the Excel File data.
    var workbook = XLSX.read(data, {
        type: 'binary'
    });

    // Fetch the name of the first sheet.
    var firstSheet = workbook.SheetNames[0];
    var ws = workbook.Sheets[firstSheet];

    // Convert the sheet to an array of arrays (AOA).
    var aoa = XLSX.utils.sheet_to_json(ws, { header: 1, raw: false });

    var cols = [];
    // Clean up the header row: remove empty or space-only headers
    for (var i = 0; i < aoa[0].length; ++i) {
        var header = aoa[0][i] ? aoa[0][i].toString().trim() : '';  // Trim spaces in header
        if (header !== '') { // Only add headers that are not empty
            cols[i] = { field: header };
        }
    }

    // Generate the rest of the data, ignoring null or empty values.
    var dataArray = [];
    for (var r = 1; r < aoa.length; ++r) {
        var rowData = {};
        for (i = 0; i < aoa[r].length; ++i) {
            if (aoa[r][i] == null || aoa[r][i] === '') continue; // Skip null or empty values
            // Only assign values to the headers that are not empty
            if (cols[i] && aoa[0][i]) {
                rowData[cols[i].field] = aoa[r][i];
            }
        }
        dataArray[r - 1] = rowData;  // Store the row data
    }

    // Apply the data to the AngularJS scope
    $scope.$apply(function () {
        $scope.mychemdata.heads = cols;
        $scope.mychemdata.Products = dataArray;
        $scope.dbtotalitems = dataArray.length;
        console.log($scope.mychemdata.Products);
        console.log($scope.dbtotalitems);
        $scope.mychemdata.IsVisible = 1;
    });
};
	
			
			$scope.uploadchemtodb=function(con,log)
			{
				

				if(con=='confirm')
				{
					$scope.confirmModal = true;
					
					$scope.log=$scope.mychemdata;
				
					
					 $scope.dbmodalInstance = $uibModal.open({
				keyboard:false,
				backdrop:"static",
			   templateUrl: 'confirmchemdbModal.html',
					 scope: $scope,
				 
			 });
					
				}
				else if(con=='approved')
				{
					
					console.log(log);
					
					$scope.issaving=true;
					$http({
		    		 method:'POST',
		       		 url:'admin/importapi/importchemicals',
					data:log
		   		 }).then(function successCallback(data) {
					 console.log(data);
					$scope.confirmModal = false;
					$scope.issaving=false;
					$scope.mychemdata={};
					$scope.closedbconfirmModal();
					$scope.closestdimpmodal();
					
					
					//$rootScope.getallsub();
		        	}, function errorCallback(data) {
						console.log(data);
						toaster.pop({toasterId:11,type:"error",body:data.data.msg});
		        		console.log(data);
						$scope.issaving=false;
				});
		}

	}


	/****--TENSILE-------*/

 $scope.SelectTensileFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.UploadTensile = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessTensileExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessTensileExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mytensiledata={};
            $scope.ProcessTensileExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mytensiledata.heads=cols;
                    $scope.mytensiledata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mytensiledata.IsVisible = 1;
                });
            };
			
			
		/****--Impact-------*/

 $scope.SelectImpactFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.UploadImpact = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessImpactExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessImpactExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.myimpactdata={};
            $scope.ProcessImpactExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.myimpactdata.heads=cols;
                    $scope.myimpactdata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.myimpactdata.IsVisible = 1;
                });
            };
			
			
			/****--Proofload-------*/

 $scope.SelectProofloadFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.UploadProofload = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessProofloadExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessProofloadExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.myproofloaddata={};
            $scope.ProcessProofloadExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.myproofloaddata.heads=cols;
                    $scope.myproofloaddata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.myproofloaddata.IsVisible = 1;
                });
            };
			
			
				/****--Hardness-------*/

 $scope.SelecthardFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadhard = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcesshardExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcesshardExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.myharddata={};
            $scope.ProcesshardExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.myharddata.heads=cols;
                    $scope.myharddata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.myharddata.IsVisible = 1;
                });
            };
			
			
					/****--MDCARB-------*/

 $scope.SelectmdcarbFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadmdcarb = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessmdcarbExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessmdcarbExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mymdcarbdata={};
            $scope.ProcessmdcarbExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mymdcarbdata.heads=cols;
                    $scope.mymdcarbdata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mymdcarbdata.IsVisible = 1;
                });
            };
			
			
			
			/****--MCASE-------*/

 $scope.SelectmcaseFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadmcase = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessmcaseExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessmcaseExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mymcasedata={};
            $scope.ProcessmcaseExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mymcasedata.heads=cols;
                    $scope.mymcasedata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mymcasedata.IsVisible = 1;
                });
            };
			
			/****--MCOAT-------*/

 $scope.SelectmcoatFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadmcoat = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessmcoatExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessmcoatExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mymcoatdata={};
            $scope.ProcessmcoatExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mymcoatdata.heads=cols;
                    $scope.mymcoatdata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mymcoatdata.IsVisible = 1;
                });
            };
			
				/****--TORQUE-------*/

 $scope.SelecttorqueFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadtorque = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcesstorqueExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcesstorqueExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mytorquedata={};
            $scope.ProcesstorqueExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mytorquedata.heads=cols;
                    $scope.mytorquedata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mytorquedata.IsVisible = 1;
                });
            };
			
					
				/****--CARB DE CARB-------*/

 $scope.SelectcarbFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadcarb = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcesscarbExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcesscarbExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mycarbdata={};
            $scope.ProcesscarbExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mycarbdata.heads=cols;
                    $scope.mycarbdata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mycarbdata.IsVisible = 1;
                });
            };
			
					/****--CASEDEPTH-------*/

 $scope.SelectcaseFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadcase = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcesscaseExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcesscaseExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mycasedata={};
            $scope.ProcesscaseExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mycasedata.heads=cols;
                    $scope.mycasedata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mycasedata.IsVisible = 1;
                });
            };
			
						/****--MICRO STRUCTURE-------*/

 $scope.SelectmstructFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadmstruct = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessmstructExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessmstructExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mymstructdata={};
            $scope.ProcessmstructExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mymstructdata.heads=cols;
                    $scope.mymstructdata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mymstructdata.IsVisible = 1;
                });
            };
			
				/****--GRAIN Size-------*/

 $scope.SelectgrainFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadgrain = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessgrainExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessgrainExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mygraindata={};
            $scope.ProcessgrainExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mygraindata.heads=cols;
                    $scope.mygraindata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mygraindata.IsVisible = 1;
                });
            };
			
			/****--IRK-------*/

 $scope.SelectirkFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadirk = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessirkExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessirkExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.myirkdata={};
            $scope.ProcessirkExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.myirkdata.heads=cols;
                    $scope.myirkdata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.myirkdata.IsVisible = 1;
                });
            };
								
					/****--IRW-------*/

 $scope.SelectirwFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadirw = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessirwExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessirwExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.myirwdata={};
            $scope.ProcessirwExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.myirwdata.heads=cols;
                    $scope.myirwdata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.myirwdata.IsVisible = 1;
                });
            };
								
		
				/****--Wedge-------*/

 $scope.SelectwedgeFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadwedge = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcesswedgeExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcesswedgeExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mywedgedata={};
            $scope.ProcesswedgeExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mywedgedata.heads=cols;
                    $scope.mywedgedata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mywedgedata.IsVisible = 1;
                });
            };
								
			/****--HET-------*/

 $scope.SelecthetFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadhet = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcesshetExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcesshetExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.myhetdata={};
            $scope.ProcesshetExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.myhetdata.heads=cols;
                    $scope.myhetdata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.myhetdata.IsVisible = 1;
                });
            };
								
								
			/****--SHEAR-------*/

 $scope.SelectshearFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadshear = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessshearExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessshearExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mysheardata={};
            $scope.ProcessshearExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mysheardata.heads=cols;
                    $scope.mysheardata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mysheardata.IsVisible = 1;
                });
            };
					
				/****--THREAD-------*/

 $scope.SelectthreadFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
			
			   $scope.Uploadthread = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessthreadExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessthreadExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mythreaddata={};
            $scope.ProcessthreadExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i)
		  {  cols[i] = { field: aoa[0][i] };}

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mythreaddata.heads=cols;
                    $scope.mythreaddata.Products = data;
					$scope.dbtotalitems=data.length;
					
					console.log($scope.dbtotalitems);
                    $scope.mythreaddata.IsVisible = 1;
                });
            };
								
		
			
			
			
			$scope.uploadtensiletodb=function(con,log,tuid)
			{
				var url="";
				console.log(tuid);
				switch(tuid)
				{
					case 'TENSILE': url='admin/importapi/importtensiles';
					break;
					case 'IMP': url='admin/importapi/importimpacts';
					break;
					case 'PROOF': url='admin/importapi/importproofloads';
					break;
					
					
					case 'HARD': url='admin/importapi/importhardness';
					break;
					
					case 'MDCARB': url='admin/importapi/importmicrodcarb';
					break;
					case 'MCASE': url='admin/importapi/importmicrocase';
					break;
					case 'MCOAT': url='admin/importapi/importmicrocoat';
					break;
					
					case 'TORQ': url='admin/importapi/importtorques';
					break;
					
					case 'CARBDC': url='admin/importapi/importcarb';
					break;
					case 'CASE': url='admin/importapi/importcase';
					break;
					case 'MSTRUCT': url='admin/importapi/importmstruct';
					break;
					case 'GRAIN': url='admin/importapi/importgrain';
					break;
					
					
					case 'IRK': url='admin/importapi/importirk';
					break;
					case 'IRW': url='admin/importapi/importirw';
					break;
					case 'THREAD': url='admin/importapi/importthread';
					break;
					
					case 'WEDGE': url='admin/importapi/importwedge';
					break;
					case 'HET': url='admin/importapi/importhet';
					break;
					case 'SHEAR': url='admin/importapi/importshear';
					break;
				}

				if(con=='confirm')
				{
					$scope.confirmModal = true;
					
					$scope.log=log;
				$scope.tuid=tuid;
					
							 $scope.dbmodalInstance = $uibModal.open({
						keyboard:false,
						backdrop:"static",
					   templateUrl: 'confirmtensiledbModal.html',
							 scope: $scope,
						 
					 });
					
				}
				else if(con=='approved')
				{
					
					console.log(log);
					
					$scope.issaving=true;
					$http({
		    		 method:'POST',
		       		 url:url,
					data:log
		   		 }).then(function successCallback(data) {
					 console.log(data);
					$scope.confirmModal = false;
					$scope.issaving=false;
					$scope.myimpactdata={};
					$scope.mytensiledata={};
					$scope.closedbconfirmModal();
					
					
					
					//$rootScope.getallsub();
		        	}, function errorCallback(data) {
						console.log(data);
						toaster.pop({toasterId:11,type:"error",body:data.data.msg});
		        		
						$scope.issaving=false;
				});
		}

	}		
			
			
	$scope.closedbconfirmModal=function()
 	{
 		$scope.dbmodalInstance.dismiss('cancel');
 	}
	
	
	
	$scope.closedbconfirmModal=function()
 	{
 		$scope.dbmodalInstance.dismiss('cancel');
 	}
	
	  
});


App.controller('LabAccreditController', function($scope,$http,$location,$rootScope,$state,toaster,$localstorage,configparam,
$window,$uibModal,AuthenticationService,alldata) {
    
$scope.pageSize=25;
$scope.currentPage=1;

$scope.allvcats=alldata.data.alllabs;

var exists=$localstorage.getObject(configparam.AppName);
$scope.path='images/labaccredit/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
$scope.tempqueue=[];	

$scope.statuses=[{Id:1,Text:"Active"},{Id:0,Text:"Inactive"}];
$scope.anyCategory={Name:"--Any Category--"};
$scope.flags={};

$scope.addcat=function(param)
	{
		$scope.flags.subcatmodal="false";
		
	 	if(param==='new')
		{
			
			$scope.info={}	;		
			$scope.info.Status="1";        
			$scope.queue=[];
			$scope.tempqueue=[];	
			$scope.parentcats=angular.copy($scope.allcats);
			console.log(param);			
			console.log($scope.parentcats);	
		}
		else
		{
			$rootScope.editflag=true;
			
			$scope.queue=[];
			//console.log(param);	
				$scope.loadingFiles = true;
                    $http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = false;
								angular.forEach(response.data.files,function(file)
								{
									//console.log(file);
									if(parseInt(param.Id)===parseInt(file.labid))
									{
										 $scope.queue.push(file);// = response.data.files || [];
									}
									//console.log($scope.queue);
								});
                               
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );						
				$scope.info=angular.copy(param);
			
		}	
				


	 $scope.modalInstance2 = $uibModal.open({
		keyboard:false,
		  animation: true,
		backdrop:"static",
   	   templateUrl: 'categoryModal',
     	 scope: $scope,
   	 });
	 
	 
	}
	
	$scope.closeModal=function()
 	{
 		$scope.modalInstance2.dismiss('cancel');
 	}
	

	$scope.deletecat=function(con,log)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.log=log;
		
			
			 $scope.modalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
   	   templateUrl: 'delcatModal',
     		 scope: $scope,
     	 
   	 });
			
		}
		else if(con=='delete')
		{
			$scope.issaving=true;
			$http({
		    		 method:'DELETE',
		       		 url:'admin/adminapi/dellabaccredit/'+log.Id,
					//data:{}
		   		 }).then(function successCallback(data) {
					$scope.confirmModal = false;
					$scope.issaving=false;
					$scope.closeconfirmModal();
					$scope.getallcats();
					console.log(data);
					//$rootScope.getallsub();
		        	}, function errorCallback(data) {
						toaster.pop({toasterId:11,type:"error",body:data.data.msg});
		        		console.log(data);
						$scope.issaving=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
 		$scope.modalInstance.dismiss('cancel');
 	}

	$rootScope.getallcats=function()
	{
			$http({	
					method:'PUT',
					url:'admin/adminapi/labaccredits/'+exists.uid,
					
	     	 
	     		}).then(function successCallback(data) {
					$scope.issaving=false;
					
					$scope.allvcats=data.data.alllabs;
	     				
	     		}, function errorCallback(data) {
	     			console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
	     		});
	}
	

	 $scope.issaving=false;
	$scope.savecat=function(info)
	{
		$scope.issaving=true;
	//console.log(info);
	if(angular.isDefined($scope.info.Id))
	{
		$scope.labid=angular.fromJson($scope.info.Id);
		$http({	method:'PUT',
     		url:'admin/adminapi/updatelabaccredit/'+$scope.info.Id,	
     		data:info
     		}).then(function successCallback(data) {
				
				toaster.pop({toasterId:11,type:"success",body:"Successfully updated"});
				
				
					$scope.deleteimage();
     		}, function errorCallback(data) {
     				//console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					$scope.issaving=false;
     		});
	}
	else
	{	

		
		$http({		method:'POST',
				url:'admin/adminapi/addlabaccredit',		
				data:info
			}).then(function successCallback(data) {
			
			console.log(data);
				$scope.labid=angular.fromJson(data.data);
			$scope.saveimage();
			toaster.pop({toasterId:11,type:"success",body:"Successfully added"});
				
				// $scope.getallcats();
				// $scope.closeModal();
			}, function errorCallback(data) {
					toaster.pop({toasterId:11,type:"error",body:data.data});
					$scope.issaving=false;
			});
	 }   			
	
	};

  
	$scope.saveimage=function()
		{
			//debugger;
			if($scope.queue.length>0)
			{
			
				for(var i=0;i<$scope.queue.length;i++)
				{
					if(angular.isUndefined($scope.queue[i].id))
					{
					//console.log($scope.productid);
					var file=$scope.queue[i];
					file=_.extend(file,{"labid":$scope.labid});
					var result=file.$submit();
					console.log(result);
					}
					else
					{
						
						$scope.closeModal();
						//$scope.closesubcatModal();
						$scope.getallcats();
					}
								
				}
			}
			else
			{
				
				$scope.closeModal();
				//$scope.closesubcatModal();
				$scope.getallcats();
			}
			
		}

	$scope.deleteimage=function()
	{
		//console.log("delete");
		if($scope.tempqueue.length>0)
			{
				for(var i=0;i<$scope.tempqueue.length;i++)
				{
					if(angular.isDefined($scope.tempqueue[i].labid))
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
								console.log(state);
								//$rootScope.loadingView = false;
									console.log("success 1");
									$scope.saveimage();
									//	$rootScope.savebgimage();
								//$state.go('design', null, { reload: true });
                            },
                            function () {
                                state = 'rejected';
								console.log(state);
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
		//console.log('stop');
		$scope.getallcats();
				$scope.closeModal();
				
			
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {labid: data.files[0].labid};
 
});
	
	


	$scope.deletefile=function(index,file)
	{
		if(angular.isUndefined($scope.info.Id))
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






App.controller('testrateaddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,configparam,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';
	
	$scope.setdefault={};
	$scope.oneclick=false;
	$scope.editflag=true;
	$scope.exists=$localstorage.getObject(configparam.AppName);
	
$scope.path='images/testicons/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
$scope.tempqueue=[];


 $scope.dateOptions = {
    //customClass: getDayClass,
   
    showWeeks: false
  };
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };
  

$scope.info={};
$scope.info.Cost=0;
$scope.info.Status=1;
$scope.info.RevDate=new Date();
			$scope.info.testbasicparams=[];
			$scope.info.testparamcats=[];
			$scope.info.testparams=[];
			$scope.queue=[];
$scope.tempqueue=[];	

$scope.info.deltestbasicparams=[];
			$scope.info.deltestparamcats=[];
			$scope.info.deltestparams=[];
	if(angular.isDefined(allpredata.data))
	{
		
		
		$scope.allindustries=allpredata.data.allindustries;
		$scope.statuses=[{Id:1,Text:"Active"},{Id:0,Text:"Inactive"}];
		$scope.nablopts=[{Id:1,Text:"Yes"},{Id:0,Text:"No"}];
		$scope.alltesttypes=[{Id:'D',Type:"Default"},{Id:'E',Type:"Extra Parameters"},{Id:'C',Type:"Custom"}];
		$scope.alldtypes=allpredata.data.alldtypes;
		$scope.allremtypes=allpredata.data.allremtypes;
			$scope.allpositions=allpredata.data.allpositions;
		$scope.listcategories=allpredata.data.listcategories;
	}
	

	
	
	 $scope.closetestModal=function()
 	{
			var exists=$localstorage.getObject(configparam.AppName);
						console.log(exists);
						var id=exists.uid;
						toaster.pop({toasterId:11,type:"success",body:"Successfully added."});
 		$state.go('app.testrates',{id:id});
 	}
	
	
$scope.getalltestrates=function()
	{
		$state.go('app.testrates', null, { reload: true });
	}
  
  $scope.testratesave=function(range)
	{  	
		
				$http({
					method:'POST',
					url: $rootScope.apiurl+'adminapi/testadd',
					data:range							
					}).then(function successCallback(data) {
						console.log(data);
						
				$scope.testid=angular.fromJson(data.data);
				//$scope.saveimage(angular.fromJson(data.data));
				$scope.getalltestrates();
				toaster.pop({toasterId:11,type:"success",body:data.data});
				$state.go('app.testrates',{id:id});
					}, function errorCallback(data) {
				console.log(data);
				toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
		
		
	}

	
	$scope.addtestparam=function(param)
  {	 
		if(param==='new')
		{
			$scope.testparam={Cost:0,PDType:"T"};			
			$scope.testparamInstance = $uibModal.open({
			keyboard:false,
			  animation: true,
			  size:'md',
			backdrop:"static",
			templateUrl: 'templates/appsetting/testobsparam.html',
			 scope: $scope,
			});
		}
  }
			
	$scope.edittestparam=function(param,idx)
  {	 
		
			$scope.testparam=angular.copy(param);
		$scope.testparam.index=idx;
			
	 $scope.testparamInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'templates/appsetting/testobsparam.html',
     	 scope: $scope,
   	 });
	  
  }
  
  $scope.closetestparamModal=function()
 	{
 		$scope.testparamInstance.dismiss('cancel');
 	}
  
 

  
  $scope.testparamsave=function(parameter)
	{  	
		console.log(parameter);
		if(angular.isDefined(parameter.index))
		{
			$scope.info.testparams[parameter.index]=parameter;
		}
		else
		{
			$scope.info.testparams.push(parameter);	
	toaster.pop({toasterId:11,type:"success",body:"Parameter added."});			
		}
			 $scope.closetestparamModal();	
	}
	
	$scope.importparams=function()
	{
		//$scope.allindustries=alldata.data.allindustries;
		$scope.impinfo={};
		
		$scope.mydata={};
		$scope.impmodalInstance = $uibModal.open({
			keyboard:false,
			backdrop:"static",
		   templateUrl: 'templates/import/imptestparams.html',
				 scope: $scope
			
		 });
	}
	
	$scope.closeparamimpmodal=function()
	{
		$scope.impmodalInstance.dismiss('cancel');
	}
	
	
	
			
            $scope.Upload = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };

$scope.mydata={};
            $scope.ProcessExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i) cols[i] = { field: aoa[0][i] };

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mydata.heads=cols;
                    $scope.mydata.Products = data;
					$scope.dbtotalitems=data.length;
				angular.forEach($scope.mydata.Products,function(val){
						val.Status=1;
						
						if(_.has(val, 'Group') )
						{
							val.PCat={};
							val.PCat.CatName=val.Group;
						}
						
					})
					//console.log($scope.mydata.Products);
                    $scope.mydata.IsVisible = 1;
                });
            };
		
 $scope.SelectFile = function (file) {
			
                $scope.SelectedFile = file;
				if(angular.isDefined($scope.SelectedFile))
				{
					console.log(file);
					  $scope.Upload();
				}
            };
			
	
			$scope.dbpageSize=25;
			$scope.dbcurrentPage=1;
			$scope.dbtotalitems=0;
			
			
			$scope.uploadtodb=function(log)
			{
				

					$scope.log=$scope.mydata;
					angular.forEach($scope.mydata.Products,function(val){
							$scope.info.testparams.push(val);
					})
					
				
		$scope.closeparamimpmodal();
				

			}

	$scope.closedbconfirmModal=function()
 	{
 		$scope.dbmodalInstance.dismiss('cancel');
 	}
	
	
	$scope.deltestparam=function(param,index)
	{
		if(angular.isDefined(param.Id))
		{
			$scope.info.deltestparams.push(param);
		}
		$scope.info.testparams.splice(index,1);	
	}
	
	$scope.viewtestrate=function(param)
  {	 
		
	 
			$scope.info=angular.copy(param);
			$scope.info.deltestparams=[];
	 $scope.testviewInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',		
   	   templateUrl: 'testrateviewModal.html',
     	 scope: $scope,
   	 });
	  
  }
  
  $scope.closetestviewModal=function()
 	{
 		$scope.testviewInstance.dismiss('cancel');
 	}
	
	
	//----Test parameter category ------//
	
		$scope.addtestparamcat=function(param)
  {	 
		if(param==='new')
		{
			$scope.testparamcat={};			
			$scope.testparamcatInstance = $uibModal.open({
			keyboard:false,
			  animation: true,
			  size:'md',
			backdrop:"static",
			templateUrl: 'templates/appsetting/testparamcategory.html',
			 scope: $scope,
			});
		}
		else
		{
			
		}
  }
			
	$scope.edittestparamcat=function(param,idx)
  {	 
		
			$scope.testparamcat=angular.copy(param);
		$scope.testparamcat.index=idx;
			
	 $scope.testparamcatInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'templates/appsetting/testparamcategory.html',
     	 scope: $scope,
   	 });
	  
  }
  
  $scope.closetestparamcatModal=function()
 	{
 		$scope.testparamcatInstance.dismiss('cancel');
 	}
  
 

  
  $scope.testparamcatsave=function(parameter)
	{  	
		console.log(parameter);
		if(angular.isDefined(parameter.index))
		{
			$scope.info.testparamcats[parameter.index]=parameter;
		}
		else
		{
			$scope.info.testparamcats.push(parameter);
			
		}
			 $scope.closetestparamcatModal();	
	}
	

 	//----Test basic parameters ------//
	
		$scope.edittestbasicparam=function(param,idx)
  {	 
		
		if(param==='new')
		{
			$scope.testbasicparams={Parameter:"",PUnit:"",PDType:""};
		}
		else
		{
				$scope.testbasicparams=angular.copy(param);
				$scope.testbasicparams.index=idx;
		}
						
			$scope.testbasicparamInstance = $uibModal.open({
			keyboard:false,
			  animation: true,
			  size:'md',
			backdrop:"static",
			templateUrl: 'templates/appsetting/testbasicparam.html',
			 scope: $scope,
			});
		
  }
			
	
  $scope.closetestbasicparamModal=function()
 	{
 		$scope.testbasicparamInstance.dismiss('cancel');
 	}
  
 

  
  $scope.testbasicparamsave=function(parameter)
	{  	
		console.log(parameter);
		if(angular.isDefined(parameter.index))
		{
			$scope.info.testbasicparams[parameter.index]=parameter;
		}
		else
		{
			$scope.info.testbasicparams.push(parameter);
			
		}
			 $scope.closetestbasicparamModal();	
	}
	 
  
  //---icon/image
  
  	$scope.saveimage=function(tid)
		{
			//debugger;
			if($scope.queue.length>0)
			{
			
				for(var i=0;i<$scope.queue.length;i++)
				{
					if(angular.isUndefined($scope.queue[i].id))
					{
					//console.log($scope.productid);
					var file=$scope.queue[i];
					file=_.extend(file,{"testid":tid});
					var result=file.$submit();
					console.log(result);
					}
					else
					{
						
						$scope.getalltestrates();
$scope.closetestModal();
					}
								
				}
			}
			else
			{
				
				$scope.getalltestrates();
				toaster.pop({toasterId:11,type:"error",body:data.data});
$scope.closetestModal();
			}
			
		}

	$scope.deleteimage=function(testid)
	{
		//console.log("delete");
		if($scope.tempqueue.length>0)
			{
				for(var i=0;i<$scope.tempqueue.length;i++)
				{
					if(angular.isDefined($scope.tempqueue[i].testid))
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
								console.log(state);
								//$rootScope.loadingView = false;
									console.log("success 1");
									$scope.saveimage(testid);
									//	$rootScope.savebgimage();
								//$state.go('design', null, { reload: true });
                            },
                            function () {
                                state = 'rejected';
								console.log(state);
                            }
                        );
                    };
					file.$destroy();
				
				
				
					}
					
				}
			}
			else
			{
				$scope.saveimage(testid);
			}
		
	}
		
	
	$scope.$on('fileuploadstop', function()
	{
		//console.log('stop');
		$scope.getalltestrates();
$scope.closetestModal();
				
			
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {testid: data.files[0].testid};
 
});
	
	


	$scope.deletefile=function(index,file)
	{
		if(angular.isUndefined($scope.info.Id))
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



App.controller('testrateeditController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,configparam,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';
	
	$scope.setdefault={};
	$scope.oneclick=false;
	$scope.editflag=true;
	
	
$scope.path='images/testicons/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
$scope.tempqueue=[];
 $scope.dateOptions = {
    //customClass: getDayClass,
  
    showWeeks: false
  };
	
	 $scope.popup2 = {
    opened: []
  };
   $scope.open2 = function(index) {
    $scope.popup2.opened[index] = true;
  };

	if(angular.isDefined(allpredata.data))
	{
		
		$scope.allindustries=allpredata.data.allindustries;
		$scope.statuses=[{Id:1,Text:"Active"},{Id:0,Text:"Inactive"}];
		$scope.nablopts=[{Id:1,Text:"Yes"},{Id:0,Text:"No"}];
		$scope.alltesttypes=[{Id:'D',Type:"Default"},{Id:'E',Type:"Extra Parameters"},{Id:'C',Type:"Custom"}];
		$scope.alldtypes=allpredata.data.alldtypes;
		$scope.allremtypes=allpredata.data.allremtypes;
			$scope.allpositions=allpredata.data.allpositions;
		$scope.listcategories=allpredata.data.listcategories;
		$scope.queue=[];
$scope.tempqueue=[];
		$scope.info=allpredata.data.test;
		$scope.info.RevDate=new Date($scope.info.RevDate);	
				

$scope.info.deltestbasicparams=[];
			$scope.info.deltestparamcats=[];
			$scope.info.deltestparams=[];
			
			
	}
	

	
	
	 $scope.closetestModal=function()
 	{
			var exists=$localstorage.getObject(configparam.AppName);
						console.log(exists);
						var id=exists.uid;
						toaster.pop({toasterId:11,type:"success",body:"Successfully Updated."});
 		$state.go('app.testrates', null, { reload: true });
 	}
	
	
$scope.getalltestrates=function()
	{
		$state.go('app.testrates', null, { reload: true });
	}
  
  $scope.testratesave=function(range)
	{  	
		if(angular.isDefined(range.Id))
		{
					$http({
				method:'PUT',
				url: $rootScope.apiurl+'adminapi/testupdate/'+range.Id,
					data:range							
					}).then(function successCallback(data) {
						console.log(data);
						$scope.testid=range.Id;
				$scope.deleteimage($scope.testid);
					}, function errorCallback(data) {
				console.log(data);
				toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
				
		}
		else
		{
				$http({
					method:'POST',
					url: $rootScope.apiurl+'adminapi/testadd',
					data:range							
					}).then(function successCallback(data) {
						console.log(data);
						
				$scope.testid=angular.fromJson(data.data);
				$scope.saveimage(angular.fromJson(data.data));
					}, function errorCallback(data) {
				console.log(data);
				toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
		}
		
	}

	
	
	
	
	
		
		
	
	$scope.addtestparam=function(param)
  {	 
		if(param==='new')
		{
			$scope.testparam={};			
			$scope.testparamInstance = $uibModal.open({
			keyboard:false,
			  animation: true,
			  size:'md',
			backdrop:"static",
			templateUrl: 'templates/appsetting/testobsparam.html',
			 scope: $scope,
			});
		}
  }
			
	$scope.edittestparam=function(param,idx)
  {	 
		
			$scope.testparam=angular.copy(param);
		$scope.testparam.index=idx;
			
	 $scope.testparamInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'templates/appsetting/testobsparam.html',
     	 scope: $scope,
   	 });
	  
  }
  
  $scope.closetestparamModal=function()
 	{
 		$scope.testparamInstance.dismiss('cancel');
 	}
  
 

  
  $scope.testparamsave=function(parameter)
	{  	
		console.log(parameter);
		if(angular.isDefined(parameter.index))
		{
			$scope.info.testparams[parameter.index]=parameter;
		}
		else
		{
			$scope.info.testparams.push(parameter);			
		}
			 $scope.closetestparamModal();	
	}
	
	$scope.importparams=function()
	{
		//$scope.allindustries=alldata.data.allindustries;
		$scope.impinfo={};
		
		$scope.mydata={};
		$scope.impmodalInstance = $uibModal.open({
			keyboard:false,
			backdrop:"static",
		   templateUrl: 'templates/import/imptestparams.html',
				 scope: $scope
			
		 });
	}
	
	$scope.closeparamimpmodal=function()
	{
		$scope.impmodalInstance.dismiss('cancel');
	}
	
	
	
			
            $scope.Upload = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };

$scope.mydata={};
            $scope.ProcessExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i) cols[i] = { field: aoa[0][i] };

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mydata.heads=cols;
                    $scope.mydata.Products = data;
					$scope.dbtotalitems=data.length;
				angular.forEach($scope.mydata.Products,function(val){
						val.Status=1;
						
					})
					//console.log($scope.mydata.Products);
                    $scope.mydata.IsVisible = 1;
                });
            };
		
 $scope.SelectFile = function (file) {
			
                $scope.SelectedFile = file;
				if(angular.isDefined($scope.SelectedFile))
				{
					console.log(file);
					  $scope.Upload();
				}
            };
			
	
			$scope.dbpageSize=25;
			$scope.dbcurrentPage=1;
			$scope.dbtotalitems=0;
			
			
			$scope.uploadtodb=function(log)
			{
				

					$scope.log=$scope.mydata;
					angular.forEach($scope.mydata.Products,function(val){
							$scope.info.testparams.push(val);
					})
					
				
		$scope.closeparamimpmodal();
				

			}

	$scope.closedbconfirmModal=function()
 	{
 		$scope.dbmodalInstance.dismiss('cancel');
 	}
	
	
	$scope.deltestparam=function(param,index)
	{
		if(angular.isDefined(param.Id))
		{
			$scope.info.deltestparams.push(param);
		}
		$scope.info.testparams.splice(index,1);	
	}
	
	$scope.deltestbasicparam=function(param,index)
	{
		if(angular.isDefined(param.Id))
		{
			$scope.info.deltestbasicparams.push(param);
		}
		$scope.info.testbasicparams.splice(index,1);	
	}
	
	$scope.viewtestrate=function(param)
  {	 
		
	 
			$scope.info=angular.copy(param);
			$scope.info.deltestparams=[];
	 $scope.testviewInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',		
   	   templateUrl: 'testrateviewModal.html',
     	 scope: $scope,
   	 });
	  
  }
  
  $scope.closetestviewModal=function()
 	{
 		$scope.testviewInstance.dismiss('cancel');
 	}
	
	
	//----Test parameter category ------//
	
		$scope.addtestparamcat=function(param)
  {	 
		if(param==='new')
		{
			$scope.testparamcat={};			
			$scope.testparamcatInstance = $uibModal.open({
			keyboard:false,
			  animation: true,
			  size:'md',
			backdrop:"static",
			templateUrl: 'templates/appsetting/testparamcategory.html',
			 scope: $scope,
			});
		}
  }
			
	$scope.edittestparamcat=function(param,idx)
  {	 
		
			$scope.testparamcat=angular.copy(param);
		$scope.testparamcat.index=idx;
			
	 $scope.testparamcatInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'templates/appsetting/testparamcategory.html',
     	 scope: $scope,
   	 });
	  
  }
  
  $scope.closetestparamcatModal=function()
 	{
 		$scope.testparamcatInstance.dismiss('cancel');
 	}
  
 

  
  $scope.testparamcatsave=function(parameter)
	{  	
		console.log(parameter);
		if(angular.isDefined(parameter.index))
		{
			$scope.info.testparamcats[parameter.index]=parameter;
		}
		else
		{
			$scope.info.testparamcats.push(parameter);
			
		}
			 $scope.closetestparamcatModal();	
	}
	

 	//----Test basic parameters ------//
	
	
			
	$scope.edittestbasicparam=function(param,idx)
  {	 
		
		if(param==='new')
		{
			$scope.testbasicparams={Parameter:"",PUnit:"",PDType:""};
		}
		else
		{
				$scope.testbasicparams=angular.copy(param);
				$scope.testbasicparams.index=idx;
		}
			
			
	 $scope.testbasicparamInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'templates/appsetting/testbasicparam.html',
     	 scope: $scope,
   	 });
	  
  }
  
  $scope.closetestbasicparamModal=function()
 	{
 		$scope.testbasicparamInstance.dismiss('cancel');
 	}
  
 

  
  $scope.testbasicparamsave=function(parameter)
	{  	
		console.log(parameter);
		if(angular.isDefined(parameter.index))
		{
			$scope.info.testbasicparams[parameter.index]=parameter;
		}
		else
		{
			$scope.info.testbasicparams.push(parameter);
			
		}
			 $scope.closetestbasicparamModal();	
	}
	 
  
  //---icon/image
  
  	$scope.saveimage=function(tid)
		{
			//debugger;
			if($scope.queue.length>0)
			{
			
				for(var i=0;i<$scope.queue.length;i++)
				{
					if(angular.isUndefined($scope.queue[i].id))
					{
					//console.log($scope.productid);
					var file=$scope.queue[i];
					file=_.extend(file,{"testid":tid});
					var result=file.$submit();
					console.log(result);
					}
					else
					{
						
						$scope.getalltestrates();
$scope.closetestModal();
					}
								
				}
			}
			else
			{
				
				$scope.getalltestrates();
$scope.closetestModal();
			}
			
		}

	$scope.deleteimage=function(testid)
	{
		//console.log("delete");
		if($scope.tempqueue.length>0)
			{
				for(var i=0;i<$scope.tempqueue.length;i++)
				{
					if(angular.isDefined($scope.tempqueue[i].testid))
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
								console.log(state);
								//$rootScope.loadingView = false;
									console.log("success 1");
									$scope.saveimage(testid);
									//	$rootScope.savebgimage();
								//$state.go('design', null, { reload: true });
                            },
                            function () {
                                state = 'rejected';
								console.log(state);
                            }
                        );
                    };
					file.$destroy();
				
				
				
					}
					
				}
			}
			else
			{
				$scope.saveimage(testid);
			}
		
	}
		
	
	$scope.$on('fileuploadstop', function()
	{
		//console.log('stop');
		$scope.getalltestrates();
$scope.closetestModal();
				
			
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {testid: data.files[0].testid};
 
});
	
	


	$scope.deletefile=function(index,file)
	{
		if(angular.isUndefined($scope.info.Id))
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

$scope.closetestformulaModal=function()
{
$scope.testformulaInstance.dismiss('cancel');	
}

$scope.addtestparamformula=function(param)
{
	
	$scope.formula={};
	$scope.formula.details=[];
	$scope.formparams=_.where($scope.info.testparams,{FormVal:1});
	$scope.testformulaInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'templates/appsetting/testparamformula.html',
     	 scope: $scope,
   	 });
}

$scope.mapvariable=function()
{
	$scope.formula.details.push({Parameter:"", Variable:""});
}
	
	
	$scope.testparamformulasave=function(formula)
	{
		$scope.info.formulas.push(formula);
		$scope.closetestformulaModal();
	}
			
});






App.controller('testratesController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,configparam,
$rootScope,$state,AuthenticationService,$timeout,allpredata)
{
	'use strict';
	
	$scope.setdefault={};
	$scope.oneclick=false;
	$scope.editflag=true;
	
	$scope.currentPage=1;
	$scope.pageSize=15;
	if(angular.isDefined(allpredata.data))
	{
		$scope.alltests=allpredata.data.alltests;
		$scope.allindustries=allpredata.data.allindustries;
		console.log($scope.alltests);
	}
	
$scope.statuses=[{Id:1,Text:"Active"},{Id:0,Text:"Inactive"}];
$scope.path='images/testicons/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
$scope.tempqueue=[];	
	
	$scope.addtest=function()
	{
			$scope.info={};
			$scope.info.testbasicparams=[];
			$scope.info.testparamcats=[];
			$scope.info.testparams=[];
			$scope.queue=[];
$scope.tempqueue=[];
	 $scope.testrateInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'testrateeditModal.html',
     	 scope: $scope,
   	 });
	}
	
	
			
	$scope.edittestrate=function(param)
  {	 
		
	 $scope.queue=[];
$scope.tempqueue=[];
			$scope.info=angular.copy(param);
			$scope.info.deltestbasicparams=[];
			$scope.info.deltestparamcats=[];
			$scope.info.deltestparams=[];
			$scope.queue=[];
			//console.log(param);	
				$scope.loadingFiles = true;
                    $http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = false;
								angular.forEach(response.data.files,function(file)
								{
									//console.log(file);
									if(parseInt(param.Id)===parseInt(file.testid))
									{
										 $scope.queue.push(file);// = response.data.files || [];
									}
									//console.log($scope.queue);
								});
                               
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );	
			
			
	 $scope.testrateInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'testrateeditModal.html',
     	 scope: $scope,
   	 });
	  
  }
  
  $scope.closetestModal=function()
 	{
 		$scope.testrateInstance.dismiss('cancel');
 	}
  
 
$scope.getalltestrates=function()
	{
		var exists=$localstorage.getObject(configparam.AppName);
						console.log(exists);
						var id=exists.uid;
		$http({
		method:'PUT',
		url:$rootScope.apiurl+'adminapi/testrates/'+id,
		data:{}					
			}).then(function successCallback(data) {
				console.log(data);
			$scope.alltests=data.data.alltests;
			}, function errorCallback(data) {
		console.log(data);
		toaster.pop({toasterId:11,type:"error",body:data.data});
					
		}); 
	}
  
  $scope.testratesave=function(range)
	{  	
		if(angular.isDefined(range.Id))
		{
					$http({
				method:'PUT',
				url: $rootScope.apiurl+'adminapi/testupdate/'+range.Id,
					data:range							
					}).then(function successCallback(data) {
						console.log(data);
						$scope.testid=range.Id;
				$scope.deleteimage($scope.testid);
					}, function errorCallback(data) {
				console.log(data);
				toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
				
		}
		else
		{
				$http({
					method:'POST',
					url: $rootScope.apiurl+'adminapi/testadd',
					data:range							
					}).then(function successCallback(data) {
						console.log(data);
						
				$scope.testid=angular.fromJson(data.data);
				$scope.saveimage(angular.fromJson(data.data));
					}, function errorCallback(data) {
				console.log(data);
				toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
		}
		
	}

	
	
	
		//$scope.allgroups=alldata.data.allgroups;
		$scope.alldtypes=[{Id:'N',Text:"Numeric"},{Id:'T',Text:"Text"}];
		
	
		
		
	
	$scope.addtestparam=function(param)
  {	 
		if(param==='new')
		{
			$scope.testparam={};			
			$scope.testparamInstance = $uibModal.open({
			keyboard:false,
			  animation: true,
			  size:'md',
			backdrop:"static",
			templateUrl: 'testparameditModal.html',
			 scope: $scope,
			});
		}
  }
			
	$scope.edittestparam=function(param,idx)
  {	 
		
			$scope.testparam=angular.copy(param);
		$scope.testparam.index=idx;
			
	 $scope.testparamInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'testparameditModal.html',
     	 scope: $scope,
   	 });
	  
  }
  
  $scope.closetestparamModal=function()
 	{
 		$scope.testparamInstance.dismiss('cancel');
 	}
  
 

  
  $scope.testparamsave=function(parameter)
	{  	
		console.log(parameter);
		if(angular.isDefined(parameter.index))
		{
			$scope.info.testparams[parameter.index]=parameter;
		}
		else
		{
			$scope.info.testparams.push(parameter);			
		}
			 $scope.closetestparamModal();	
	}
	
	$scope.importparams=function()
	{
		//$scope.allindustries=alldata.data.allindustries;
		$scope.impinfo={};
		
		$scope.mydata={};
		$scope.impmodalInstance = $uibModal.open({
			keyboard:false,
			backdrop:"static",
		   templateUrl: 'templates/import/imptestparams.html',
				 scope: $scope
			
		 });
	}
	
	$scope.closeparamimpmodal=function()
	{
		$scope.impmodalInstance.dismiss('cancel');
	}
	
	
	
			
            $scope.Upload = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };

$scope.mydata={};
            $scope.ProcessExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i) cols[i] = { field: aoa[0][i] };

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mydata.heads=cols;
                    $scope.mydata.Products = data;
					$scope.dbtotalitems=data.length;
				angular.forEach($scope.mydata.Products,function(val){
						val.Status=1;
						
					})
					//console.log($scope.mydata.Products);
                    $scope.mydata.IsVisible = 1;
                });
            };
		
 $scope.SelectFile = function (file) {
			
                $scope.SelectedFile = file;
				if(angular.isDefined($scope.SelectedFile))
				{
					console.log(file);
					  $scope.Upload();
				}
            };
			
	
			$scope.dbpageSize=25;
			$scope.dbcurrentPage=1;
			$scope.dbtotalitems=0;
			
			
			$scope.uploadtodb=function(log)
			{
				

					$scope.log=$scope.mydata;
					angular.forEach($scope.mydata.Products,function(val){
							$scope.info.testparams.push(val);
					})
					
				
		$scope.closeparamimpmodal();
				

			}

	$scope.closedbconfirmModal=function()
 	{
 		$scope.dbmodalInstance.dismiss('cancel');
 	}
	
	
	$scope.deltestparam=function(param,index)
	{
		if(angular.isDefined(param.Id))
		{
			$scope.info.deltestparams.push(param);
		}
		$scope.info.testparams.splice(index,1);	
	}
	
	$scope.viewtestrate=function(param)
  {	 
		
	 
			$scope.info=angular.copy(param);
			$scope.info.deltestparams=[];
	 $scope.testviewInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',		
   	   templateUrl: 'testrateviewModal.html',
     	 scope: $scope,
   	 });
	  
  }
  
  $scope.closetestviewModal=function()
 	{
 		$scope.testviewInstance.dismiss('cancel');
 	}
	
	
	//----Test parameter category ------//
	
		$scope.addtestparamcat=function(param)
  {	 
		if(param==='new')
		{
			$scope.testparamcat={};			
			$scope.testparamcatInstance = $uibModal.open({
			keyboard:false,
			  animation: true,
			  size:'md',
			backdrop:"static",
			templateUrl: 'templates/appsetting/testparamcategory.html',
			 scope: $scope,
			});
		}
  }
			
	$scope.edittestparamcat=function(param,idx)
  {	 
		
			$scope.testparamcat=angular.copy(param);
		$scope.testparamcat.index=idx;
			
	 $scope.testparamcatInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'templates/appsetting/testparamcategory.html',
     	 scope: $scope,
   	 });
	  
  }
  
  $scope.closetestparamcatModal=function()
 	{
 		$scope.testparamcatInstance.dismiss('cancel');
 	}
  
 

  
  $scope.testparamcatsave=function(parameter)
	{  	
		console.log(parameter);
		if(angular.isDefined(parameter.index))
		{
			$scope.info.testparamcats[parameter.index]=parameter;
		}
		else
		{
			$scope.info.testparamcats.push(parameter);
			
		}
			 $scope.closetestparamcatModal();	
	}
	

 	//----Test basic parameters ------//
	
		$scope.addtestbasicparam=function(param)
  {	 
		if(param==='new')
		{
			$scope.testbasicparam={};			
			$scope.testbasicparamInstance = $uibModal.open({
			keyboard:false,
			  animation: true,
			  size:'md',
			backdrop:"static",
			templateUrl: 'templates/appsetting/testbasicparam.html',
			 scope: $scope,
			});
		}
  }
			
	$scope.edittestbasicparam=function(param,idx)
  {	 
		
			$scope.testbasicparam=angular.copy(param);
		$scope.testbasicparam.index=idx;
			
	 $scope.testbasicparamInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'templates/appsetting/testbasicparam.html',
     	 scope: $scope,
   	 });
	  
  }
  
  $scope.closetestbasicparamModal=function()
 	{
 		$scope.testbasicparamInstance.dismiss('cancel');
 	}
  
 

  
  $scope.testbasicparamsave=function(parameter)
	{  	
		console.log(parameter);
		if(angular.isDefined(parameter.index))
		{
			$scope.info.testbasicparams[parameter.index]=parameter;
		}
		else
		{
			$scope.info.testbasicparams.push(parameter);
			
		}
			 $scope.closetestbasicparamModal();	
	}
	 
  
  //---icon/image
  
  	$scope.saveimage=function(tid)
		{
			//debugger;
			if($scope.queue.length>0)
			{
			
				for(var i=0;i<$scope.queue.length;i++)
				{
					if(angular.isUndefined($scope.queue[i].id))
					{
					//console.log($scope.productid);
					var file=$scope.queue[i];
					file=_.extend(file,{"testid":tid});
					var result=file.$submit();
					console.log(result);
					}
					else
					{
						
						$scope.getalltestrates();
$scope.closetestModal();
					}
								
				}
			}
			else
			{
				
				$scope.getalltestrates();
$scope.closetestModal();
			}
			
		}

	$scope.deleteimage=function(testid)
	{
		//console.log("delete");
		if($scope.tempqueue.length>0)
			{
				for(var i=0;i<$scope.tempqueue.length;i++)
				{
					if(angular.isDefined($scope.tempqueue[i].testid))
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
								console.log(state);
								//$rootScope.loadingView = false;
									console.log("success 1");
									$scope.saveimage(testid);
									//	$rootScope.savebgimage();
								//$state.go('design', null, { reload: true });
                            },
                            function () {
                                state = 'rejected';
								console.log(state);
                            }
                        );
                    };
					file.$destroy();
				
				
				
					}
					
				}
			}
			else
			{
				$scope.saveimage(testid);
			}
		
	}
		
	
	$scope.$on('fileuploadstop', function()
	{
		//console.log('stop');
		$scope.getalltestrates();
$scope.closetestModal();
				
			
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {testid: data.files[0].testid};
 
});
	
	


	$scope.deletefile=function(index,file)
	{
		if(angular.isUndefined($scope.info.Id))
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



App.controller('IndustryController', function($scope,$http,$location,$rootScope,$state,toaster,$localstorage,configparam,
$window,$uibModal,AuthenticationService,alldata) {
    
$scope.pageSize=25;
$scope.currentPage=1;
$scope.allcats=alldata.data.allcats;
$scope.allvcats=alldata.data.allvcats;

console.log($scope.allcats);
$scope.parentcats=[];
$scope.totalitems=alldata.data.totalitems;
var exists=$localstorage.getObject($scope.appname);
	var exists=$localstorage.getObject(configparam.AppName);
$scope.hassubcats=[{Id:1,Text:"Yes"},{Id:0,Text:"No"}];

$scope.statuses=[{Id:1,Text:"Active"},{Id:0,Text:"Inactive"}];



$scope.flags={};

$scope.addsubcat=function(param)
{
	console.log(param);
	console.log($scope.allcats);
	$scope.flags.subcatmodal="true";
	$scope.parentcats=$scope.allcats;
		console.log($scope.parentcats);	
		$scope.queue=[];
	$scope.info={};
	$scope.info.Status=1;		
	$scope.info.ParentId=param.Id;
	
	 $scope.modalInstance2=$uibModal.open({
		keyboard:false,
		  animation: true,
		backdrop:"static",
   	   templateUrl: 'subcategoryModal',
     	 scope: $scope,
   	 });
}


$scope.editsubcat=function(param)
{
	console.log(param);
	console.log($scope.allcats);
	$scope.flags.subcatmodal="true";
	$scope.parentcats=$scope.allcats;
		console.log($scope.parentcats);	
	$scope.info=param;
$scope.info.Status='1';
	 $scope.modalInstance2=$uibModal.open({
		keyboard:false,
		  animation: true,
		backdrop:"static",
   	   templateUrl: 'subcategoryModal',
     	 scope: $scope,
   	 });
}

$scope.closesubcatModal=function()
 	{
 		$scope.modalInstance2.dismiss('cancel');
 	}
	


$scope.addcat=function(param)
	{
		$scope.flags.subcatmodal="false";
		
	 	if(param==='new')
		{
			
			$scope.info={};			
			$scope.info.Status=1;		
			$scope.parentcats=angular.copy($scope.allcats);
			
		}
		else
		{
			console.log(param);
			$rootScope.editflag=true;
			
				$scope.info=angular.copy(param);
				
		}	
				


	 $scope.modalInstance2 = $uibModal.open({
		keyboard:false,
		  animation: true,
		backdrop:"static",
   	   templateUrl: 'categoryModal',
     	 scope: $scope,
   	 });
	 
	 
	}
	
	$scope.closeModal=function()
 	{
 		$scope.modalInstance2.dismiss('cancel');
 	}
	

	$scope.deletecat=function(con,log)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.log=log;
		
			
			 $scope.modalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
   	   templateUrl: 'delcatModal',
     		 scope: $scope,
     	 
   	 });
			
		}
		else if(con=='delete')
		{
			$scope.issaving=true;
			$http({
		    		 method:'DELETE',
		       		 url:'admin/adminapi/delindustry/'+log.Id,
					//data:{}
		   		 }).then(function successCallback(data) {
					$scope.confirmModal = false;
					$scope.issaving=false;
					$scope.closeconfirmModal();
					$scope.getallcats();
					toaster.pop({toasterId:11,type:"success",body:"Deleted Successfully"});
					console.log(data);
					//$rootScope.getallsub();
		        	}, function errorCallback(data) {
						toaster.pop({toasterId:11,type:"error",body:data.data.msg});
		        		console.log(data);
						$scope.closeconfirmModal();
						$scope.issaving=false;
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
 		$scope.modalInstance.dismiss('cancel');
 	}

	$rootScope.getallcats=function()
	{
		var exists=$localstorage.getObject(configparam.AppName);
		console.log(exists);
			$http({	
					method:'PUT',
					url:'admin/adminapi/industries/'+exists.uid,
					
	     	 
	     		}).then(function successCallback(data) {
					$scope.issaving=false;
					console.log(data);
					$scope.allcats=data.data.allcats;
					$scope.allvcats=data.data.allvcats;
	     				
	     		}, function errorCallback(data) {
	     			console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
	     		});
	}
	

	 $scope.issaving=false;
	$scope.savecat=function(info)
	{
		$scope.issaving=true;
	//console.log(info);
	if(angular.isDefined($scope.info.Id))
	{
		$scope.procatid=angular.fromJson($scope.info.Id);
		$http({	method:'PUT',
     		url:'admin/adminapi/industryupdate/'+$scope.info.Id,	
     		data:info
     		}).then(function successCallback(data) {
				
				toaster.pop({toasterId:11,type:"success",body:"Successfully updated"});
				
					$scope.getallcats();
				$scope.closeModal();
     		}, function errorCallback(data) {
     				console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data});
     		});
	}
	else
	{	

		
		$http({		method:'POST',
				url:'admin/adminapi/industryadd',		
				data:info
			}).then(function successCallback(data) {
			
			console.log(data);
				
			toaster.pop({toasterId:11,type:"success",body:"Successfully added"});
				$scope.getallcats();
				$scope.closeModal();
			}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 }   			
	
	};

  

	
	
	
});



App.controller('testconditionsController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	$scope.alltestconditions=[];
	$scope.animationsEnabled = true;
	
	if(angular.isDefined(alldata.data))
		{		
		$scope.alltestconditions=alldata.data;			
	}
	
$scope.editstd=function(param,id)
	{
		
	 	if(param==='new')
		{
			$scope.info={};				
		}
		else
		{		console.log(param);		
			$scope.info=angular.copy(param);							
		}	


	 $scope.testmodalInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'sm -',
		backdrop:"static",
   	   templateUrl: 'infoaddModal.html',
     	 scope: $scope,
   	 });
	 
	 
	}
	
	$scope.closeinfoModal=function()
 	{
 		$scope.testmodalInstance.dismiss('cancel');
 	}
	
	$scope.testcondsave=function(std)
	{  	
		if(angular.isDefined(std.Id))
		{
					$http({
				method:'PUT',
				url: $rootScope.apiurl+'settingapi/testcondupdate/'+std.Id,
				data:std					
					}).then(function successCallback(data) {
				$scope.alltestconditions=data.data;	
$scope.closeinfoModal();
	toaster.pop({toasterId:11,type:"success",body:"Successfully Updated."});
					}, function errorCallback(data) {
				console.log(data);
				toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
		}
		else
		{
					$http({
				method:'POST',
				url: $rootScope.apiurl+'settingapi/testcondadd',
				data:std					
			}).then(function successCallback(data) {
					$scope.alltestconditions=data.data;	
$scope.closeinfoModal();
	toaster.pop({toasterId:11,type:"success",body:"Successfully Added."});
				}, function errorCallback(data) {
					console.log(data);
				toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
			
		}
	}
  
  
  $scope.deletestd=function(con,item)
  {
	  if(con==='confirm')
	{
		$scope.info=item;
		 $scope.delmodalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'confirmModal.html',
		scope:$scope,
         size: "md",
   
		});
	}
	else
	{
		console.log(item);
		$http({
					method:'DELETE',
					url: $rootScope.apiurl+'settingapi/delstd/'+item.Id,
					data:item					
					}).then(function successCallback(data) {
				//console.log(data);
			 	//$scope.getpages();
				 $scope.delcancel();
				$state.go('app.standards');
			 	
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




App.controller('LogController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	$scope.alltestconditions=[];
	$scope.animationsEnabled = true;
	
	if(angular.isDefined(alldata.data))
		{		
		$scope.alllogs=alldata.data.alllogs;
$scope.totalitems=alldata.data.totalitems;		
	}
	

});




/****************Setting Default Details Controller****************/
App.controller('defaultsettingController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,configparam)
{
	'use strict';
	var exists=$localstorage.getObject(configparam.AppName);
	
	
	  
});

App.controller('firmsettingController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,allpredata,configparam)
{
	'use strict';
	var exists=$localstorage.getObject(configparam.AppName);
	$scope.setdefault={};
	$scope.setdefault.account={};
	$scope.flags={};
	$scope.flags.oneclick=false;
	$scope.ds={}
	
	$scope.ds.firmeditflag=true;
	
	
	$scope.path='images/applogos/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	$scope.tempqueue=[];
	

	
	if(angular.isDefined(allpredata.data))
	{
		console.log(allpredata.data);
		// $scope.setdefault=allpredata.data.appsets[0];
		// $scope.setdefault.account=allpredata.data.account;
		
		$scope.branchid=allpredata.data.branchid
		$scope.firmset=allpredata.data.firmset;
		
		$scope.alllocs=allpredata.data.alllocs;
		
		
		$scope.queue=[];
			//console.log(param);	
				$scope.loadingFiles = true;
                    $http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = false;
								console.log(response)
								angular.forEach(response.data.files,function(file)
								{
									//console.log(file);
									if(parseInt($scope.branchid)===parseInt(file.setid))
									{
										 $scope.queue.push(file);// = response.data.files || [];
									}
									//console.log($scope.queue);
								});
                               
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );	

					
		//console.log($scope.setdefault);
	}
			
	
	
	
	$scope.savefirminfo=function(firmset)
	{
		console.log(firmset);
		$scope.flags.oneclick=true;
		$http({
		method:'PUT',
		url: $rootScope.apiurl+'settingapi/firmsetupdate/'+exists.uid,
		data:firmset					
			}).then(function successCallback(data) {
		$scope.flags.oneclick=false;
		console.log(data);
		console.log("updated");
			$scope.deleteimage();
			toaster.pop({toasterId:11,type:"success",body:"Settings updated"});
			//$state.go('app.defaultsetting', null, { reload: true });
		
			}, function errorCallback(data) {
		console.log(data);
		$scope.flags.oneclick=false;
		toaster.pop({toasterId:11,type:"error",body:data.data});
					
		}); 
	}
	
	
	
	$scope.saveimage=function()
		{
			//debugger;
			console.log("save image");
			if($scope.queue.length>0)
			{
			
				for(var i=0;i<$scope.queue.length;i++)
				{
					if(angular.isUndefined($scope.queue[i].id))
					{
					//console.log($scope.productid);
					var file=$scope.queue[i];
					file=_.extend(file,{"setid":$scope.branchid});
					var result=file.$submit();
					console.log(result);
					}
					else
					{
						
					
						//$scope.closesubcatModal();
						$state.reload() ;
					}
								
				}
			}
			else
			{
				
				
				//$scope.closesubcatModal();
				$state.reload() ;
			}
			
		}

	$scope.deleteimage=function()
	{
		//console.log("delete");
		if($scope.tempqueue.length>0)
			{
				for(var i=0;i<$scope.tempqueue.length;i++)
				{
					if(angular.isDefined($scope.tempqueue[i].setid))
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
								console.log(state);
								//$rootScope.loadingView = false;
									console.log("success 1");
									$scope.saveimage();
									//	$rootScope.savebgimage();
								//$state.go('design', null, { reload: true });
                            },
                            function () {
                                state = 'rejected';
								console.log(state);
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
		//console.log('stop');
	$state.reload() ;
				
			
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {setid: data.files[0].setid};
 
});
	
	


	$scope.deletefile=function(index,file)
	{
		console.log(file);
		if(angular.isUndefined(file.id))
		{
			console.log("remove");
			file.$cancel();
			
		}
		else
		{
			console.log("tenp remove");
			$scope.tempqueue.push(file);			
			$scope.queue.splice(index,1);	
			
		}
	}


	  
});


App.controller('quotesettingController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,allpredata,configparam)
{
	'use strict';
	var exists=$localstorage.getObject(configparam.AppName);
	$scope.setdefault={};
	$scope.setdefault.account={};
	$scope.flags={};
	$scope.flags.oneclick=false;
	$scope.ds={}
	
	$scope.ds.accounteditflag=true;
	
	$scope.path='images/quoteattachments/index.php';
	$scope.quoteoptions = { url:$scope.path};
	$scope.queue=[];
	$scope.tempqueue=[];
	

	
	if(angular.isDefined(allpredata.data))
	{
		console.log(allpredata.data);
		// $scope.setdefault=allpredata.data.appsets[0];
		// $scope.setdefault.account=allpredata.data.account;
		
		$scope.branchid=allpredata.data.branchid
		
		$scope.account=allpredata.data.account;
		$scope.labset=allpredata.data.labset;
		
		console.log($scope.setdefault.account.BankName);
		$scope.alllocs=allpredata.data.alllocs;
		$scope.allcurrency=allpredata.data.allcurrency;
		
		
		$scope.queue=[];
			//console.log(param);	
				$scope.loadingFiles = true;
                    $http.get($scope.path)
                        .then(
                            function (response) {
                                $scope.loadingFiles = false;
								console.log(response)
								angular.forEach(response.data.files,function(file)
								{
									//console.log(file);
									
										 $scope.queue.push(file);// = response.data.files || [];
									
									//console.log($scope.queue);
								});
                               
                            },
                            function () {
                                $scope.loadingFiles = false;
                            }
                        );	

					
		//console.log($scope.setdefault);
	}
			
	
	
	$scope.savefirminfo=function(firmset)
	{
		console.log(firmset);
		$scope.flags.oneclick=true;
		$http({
		method:'PUT',
		url: $rootScope.apiurl+'settingapi/firmsetupdate/'+exists.uid,
		data:firmset					
			}).then(function successCallback(data) {
		$scope.flags.oneclick=false;
		console.log(data);
		console.log("updated");
			$scope.deleteimage();
			toaster.pop({toasterId:11,type:"success",body:"Settings updated"});
			//$state.go('app.defaultsetting', null, { reload: true });
		
			}, function errorCallback(data) {
		console.log(data);
		$scope.flags.oneclick=false;
		toaster.pop({toasterId:11,type:"error",body:data.data});
					
		}); 
	}
	
	
	
	$scope.saveaccountinfo=function(account)
	{
		console.log(account);
		$scope.flags.oneclick=true;
		$http({
		method:'PUT',
		url: $rootScope.apiurl+'settingapi/accountsetupdate/'+exists.uid,
		data:account					
			}).then(function successCallback(data) {
		$scope.flags.oneclick=false;
		console.log(data);
		console.log("updated");
		$scope.deleteimage();
			// $scope.ds.accounteditflag=true;
			// toaster.pop({toasterId:11,type:"success",body:"Settings updated"});
			// //$state.go('app.defaultsetting', null, { reload: true });
		
			}, function errorCallback(data) {
		console.log(data);
		$scope.flags.oneclick=false;
		toaster.pop({toasterId:11,type:"error",body:data.data});
					
		}); 
	}
	
	
	$scope.saveimage=function()
		{
			//debugger;
			console.log("save image");
			if($scope.queue.length>0)
			{
			
				for(var i=0;i<$scope.queue.length;i++)
				{
					if(angular.isUndefined($scope.queue[i].id))
					{
					//console.log($scope.productid);
					var file=$scope.queue[i];
					file=_.extend(file,{"isman":1});
					var result=file.$submit();
					console.log(result);
					}
					else
					{
						
					
						//$scope.closesubcatModal();
						$state.go('app.defaultsetting', null, { reload: true });
					}
								
				}
			}
			else
			{
				
				
				//$scope.closesubcatModal();
				$state.go('app.defaultsetting', null, { reload: true });
			}
			
		}

	$scope.deleteimage=function()
	{
		//console.log("delete");
		if($scope.tempqueue.length>0)
			{
				for(var i=0;i<$scope.tempqueue.length;i++)
				{
					if(angular.isDefined($scope.tempqueue[i].setid))
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
								console.log(state);
								//$rootScope.loadingView = false;
									console.log("success 1");
									$scope.saveimage();
									//	$rootScope.savebgimage();
								//$state.go('design', null, { reload: true });
                            },
                            function () {
                                state = 'rejected';
								console.log(state);
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
		//console.log('stop');
	$state.go('app.defaultsetting', null, { reload: true });
				
			
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {isman: data.files[0].isman};
 
});
	
	


	$scope.deletefile=function(index,file)
	{
		console.log(file);
		if(angular.isUndefined(file.id))
		{
			console.log("remove");
			file.$cancel();
			
		}
		else
		{
			console.log("tenp remove");
			$scope.tempqueue.push(file);			
			$scope.queue.splice(index,1);	
			
		}
	}


	  
});


App.controller('labsettingController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,allpredata,configparam)
{
	'use strict';
	var exists=$localstorage.getObject(configparam.AppName);
	$scope.setdefault={};
	$scope.setdefault.account={};
	$scope.flags={};
	$scope.flags.oneclick=false;
	$scope.ds={}
	
	$scope.ds.labseteditflag=true;
	


	
	if(angular.isDefined(allpredata.data))
	{
		console.log(allpredata.data);
		// $scope.setdefault=allpredata.data.appsets[0];
		// $scope.setdefault.account=allpredata.data.account;
		
		$scope.branchid=allpredata.data.branchid
		
		$scope.labset=allpredata.data.labset;
		
		console.log($scope.setdefault.account.BankName);
		$scope.alllocs=allpredata.data.alllocs;
		$scope.allcurrency=allpredata.data.allcurrency;
		
					
		//console.log($scope.setdefault);
	}
			
	
	
	
	$scope.savelabinfo=function(labset)
	{
		console.log(labset);
		$scope.flags.oneclick=true;
		$http({
		method:'PUT',
		url: $rootScope.apiurl+'settingapi/labsetupdate/'+exists.uid,
		data:labset					
			}).then(function successCallback(data) {
		$scope.flags.oneclick=false;
		console.log(data);
		console.log("updated");
			$scope.ds.labseteditflag=true;
			toaster.pop({toasterId:11,type:"success",body:"Settings updated"});
			//$state.go('app.defaultsetting', null, { reload: true });
		
			}, function errorCallback(data) {
		console.log(data);
		$scope.flags.oneclick=false;
		toaster.pop({toasterId:11,type:"error",body:data.data});
					
		}); 
	}
  
    
});



App.controller('certsettingController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,allpredata,configparam)
{
	'use strict';
	var exists=$localstorage.getObject(configparam.AppName);
	$scope.setdefault={};
	$scope.setdefault.account={};
	$scope.flags={};
	$scope.flags.oneclick=false;
	$scope.ds={}
	
	$scope.ds.labseteditflag=true;
	


	
	if(angular.isDefined(allpredata.data))
	{
		console.log(allpredata.data);
		// $scope.setdefault=allpredata.data.appsets[0];
		// $scope.setdefault.account=allpredata.data.account;
		
		$scope.branchid=allpredata.data.branchid
		
		$scope.certset=allpredata.data.certset;
		
		console.log($scope.setdefault.account.BankName);
		$scope.alllocs=allpredata.data.alllocs;
		$scope.allcurrency=allpredata.data.allcurrency;
		
					
		//console.log($scope.setdefault);
	}
			
	
	
	
	$scope.savecertinfo=function(certset)
	{
		console.log(certset);
		$scope.flags.oneclick=true;
		$http({
		method:'PUT',
		url: $rootScope.apiurl+'settingapi/certsetupdate/'+exists.uid,
		data:certset					
			}).then(function successCallback(data) {
		$scope.flags.oneclick=false;
		console.log(data);
		console.log("updated");
			$scope.ds.labseteditflag=true;
			toaster.pop({toasterId:11,type:"success",body:"Settings updated"});
			//$state.go('app.defaultsetting', null, { reload: true });
		
			}, function errorCallback(data) {
		console.log(data);
		$scope.flags.oneclick=false;
		toaster.pop({toasterId:11,type:"error",body:data.data});
					
		}); 
	}
  
    
});



App.controller('accountsettingController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,allpredata,configparam)
{
	'use strict';
	var exists=$localstorage.getObject(configparam.AppName);
	$scope.setdefault={};
	$scope.setdefault.account={};
	$scope.flags={};
	$scope.flags.oneclick=false;
	$scope.ds={}
	$scope.ds.vaulteditflag=true;
	

	if(angular.isDefined(allpredata.data))
	{
		console.log(allpredata.data);
		
		$scope.branchid=allpredata.data.branchid
		
		$scope.vault=allpredata.data.vault;
		
		
		console.log($scope.setdefault.account.BankName);
		$scope.alllocs=allpredata.data.alllocs;
		
		
	
	}
			
	
	
	$scope.savevaultinfo=function(vault)
	{
		console.log(vault);
		$scope.flags.oneclick=true;
		$http({
		method:'PUT',
		url: $rootScope.apiurl+'settingapi/vaultsetupdate/'+exists.uid,
		data:vault					
			}).then(function successCallback(data) {
		$scope.flags.oneclick=false;
		console.log(data);
		console.log("updated");
			$scope.ds.vaulteditflag=true;
			toaster.pop({toasterId:11,type:"success",body:"Settings updated"});
			//$state.go('app.defaultsetting', null, { reload: true });
		
			}, function errorCallback(data) {
		console.log(data);
		$scope.flags.oneclick=false;
		toaster.pop({toasterId:11,type:"error",body:data.data});
					
		}); 
	}
	
	 
});



App.controller('stdController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,
$rootScope,$state,AuthenticationService,$timeout,configparam,alldata)
{
	'use strict';
	$scope.allstds=[];
	$scope.animationsEnabled = true;
	$scope.pageSize=25;
	$scope.currentPage=1;
	$scope.searchstring="";
	
	if(angular.isDefined(alldata.data))
		{	
	
		$scope.allstds=alldata.data.allstds;		
		$scope.allindustries=alldata.data.allindustries;
	}
	
	

	$scope.getallstds=function(pageno)
	{
		var exists=$localstorage.getObject(configparam.AppName);
		$http({
		method:'PUT',
		url: $rootScope.apiurl+'settingapi/stddata/'+exists.uid,
		data:{pl:25,pn:pageno}					
			}).then(function successCallback(data) {
		$scope.allstds=data.data.allstds;
			}, function errorCallback(data) {
		console.log(data);
		toaster.pop({toasterId:11,type:"error",body:data.data});
					
		}); 
	}
	
	$scope.refreshdata=function()
	{
		$scope.searchstring="";
		$scope.getallstds(1);
	}
	
$scope.editstd=function(param,id)
	{
		
	 	if(param==='new')
		{
			$scope.info={};
$scope.info.IndId=$scope.allindustries[0].Id;		
		}
		else
		{		console.log(param);		
			$scope.info=angular.copy(param);							
		}	


	 $scope.stdmodalInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'md',
		backdrop:"static",
   	   templateUrl: 'stdaddModal.html',
     	 scope: $scope,
   	 });
	 
	 
	}
	
	$scope.closestdModal=function()
 	{
 		$scope.stdmodalInstance.dismiss('cancel');
 	}
	
  
	$scope.showstd = function (size,std) 
	{
		$scope.viewstd=std;   
		$scope.modalInstance = $uibModal.open({
		animation: $scope.animationsEnabled,
		templateUrl: 'stdviewContent.html',
		scope:$scope,
		size: size,
		});
	}
 
	$scope.cancel = function() 
	{
		$scope.modalInstance.dismiss('cancel');
	};
	
	
	$scope.stdsave=function(std)
	{  	
		if(angular.isDefined(std.Id))
		{
					$http({
				method:'PUT',
				url: $rootScope.apiurl+'settingapi/stdedit/'+std.Id,
				data:std					
					}).then(function successCallback(data) {
				$scope.getallstds(1);
$scope.closestdModal();
toaster.pop({toasterId:11,type:"success",body:"Successfully Updated"});
			
					}, function errorCallback(data) {
				console.log(data);
				toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
		}
		else
		{
					$http({
				method:'POST',
				url: $rootScope.apiurl+'settingapi/stdadddata',
				data:std					
			}).then(function successCallback(data) {
					toaster.pop({toasterId:11,type:"success",body:"Successfully Added"});
			
$scope.closestdModal();
$scope.getallstds(1);
				}, function errorCallback(data) {
					console.log(data);
				toaster.pop({toasterId:11,type:"error",body:data.data});
				}); 
			
		}
	}
  
  
  $scope.deletestd=function(con,item)
  {
	  if(con==='confirm')
	{
		$scope.info=item;
		 $scope.delmodalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'confirmModal.html',
		scope:$scope,
         size: "md",
   
		});
	}
	else
	{
		console.log(item);
		$http({
					method:'DELETE',
					url: $rootScope.apiurl+'settingapi/delstd/'+item.Id,
					data:item					
					}).then(function successCallback(data) {
				//console.log(data);
			 	//$scope.getpages();
				toaster.pop({toasterId:11,type:"error",body:"Successfully Deleted"});
				 $scope.delcancel();
			//	$scope.closestdModal();
$scope.getallstds(1);
			 	
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






App.controller('substandardController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,configparam,
$rootScope,$state,AuthenticationService,SubStdNames,$timeout,alldata)
{
	'use strict';
	
	$scope.allsubdata=[];
	$scope.animationsEnabled = true;
var exists=$localstorage.getObject(configparam.AppName);
	$scope.pageSize=25;
	$scope.currentPage=1;
	  $scope.getstdsandtest=function(param)
	{
		$scope.allstds=_.where(alldata.data.allstds,{IndId:param});
		
		var ind=_.findWhere($scope.allindustries,{Id:param});
		$scope.alltests=ind.alltests;
		$scope.info={};
		$scope.info.StdId="";
		$scope.info.TestId="";
		 $scope.info.Parameters=[];
	}
	if(angular.isDefined(alldata.data))
	{
		$scope.allindustries=alldata.data.allindustries;
		$scope.allsubdata=alldata.data.allsubdata;
		$scope.totalitems=alldata.data.totalitems;
		console.log(alldata.data);
		//$scope.allstds=alldata.data.allstds;
		$scope.alltypes=alldata.data.alltypes;
		$scope.allparams=[];
		 $scope.sparams=[];
		
		$scope.alltests=[];
		
		$scope.getstdsandtest($scope.allindustries[0].Id);
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
					url:$rootScope.apiurl+'settingapi/searchsubstd/',	
					data:{text:searchtext,pageSize:$scope.pageSize}
	     	 
	     			}).then(function successCallback(data) {
					$scope.pageSize=25;
					console.log(data);
				$scope.allsubdata=data.data.allsubdata;
		$scope.totalitems=0;//data.totalitems;
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
	
		$scope.getstdsandtestedit=function(param)
	{
		$scope.allstds=_.where(alldata.data.allstds,{IndId:param});
		
		var ind=_.findWhere($scope.allindustries,{Id:param});
		$scope.alltests=ind.alltests;
		console.log($scope.alltests);
	}
	// $scope.gettests=function(stdid)
	// {
		// var std=_.findWhere($scope.allstds,{Id:stdid});
		// $scope.alltests=std.alltests;
	// }
	
	
	$scope.loadparams=function(indid,testids,parameters)
	{
		console.log(testids);
		console.log(parameters);
		$http({
					method:'PUT',
					url: $rootScope.apiurl+'settingapi/indparamdata/'+indid,
					data:{TestIds:testids}				
					}).then(function successCallback(data) {
				console.log(data);
				$scope.allparams=data.data.allparams;
				$scope.info.Parameters=data.data.allparams;
				if(!_.isEmpty(parameters))
				{
					angular.forEach($scope.info.Parameters,function(val){
						var op=_.findWhere(parameters,{Parameter:val.Parameter});
						if(!_.isEmpty(op))
						{
							val.Id=op.Id;
							val.SpecMin=op.SpecMin;
							val.SpecMax=op.SpecMax;
							val.PermMin=op.PermMin;
							val.PermMax=op.PermMax;
						}
					})
					
					
				}
				
				$scope.alltestmethods=data.data.alltestmethods;
		
						}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
	}
	
	
	 $scope.getLimits = function (array) {
		 if(array.length<2)
		 {
			  return [
                Math.floor(array.length )];
		 }
		 else
		 {
            return [
                Math.floor(array.length / 2) , -Math.floor(array.length / 2)
            ];
		 }
        };
	
  $scope.editconcnt=function(param,id)
  {	 
		
		//$state.go('app.addsubstd', null, { reload: true });
	 	if(param==='new')
		{
			$scope.info={};	
$scope.info.Cost=0;	
$scope.info.IndId=$scope.allindustries[0].Id;		
			
			//$scope.info.Parameters=alldata.data.allelements;			
		}
		else
		{		
console.log(param);
			$scope.info=angular.copy(param);
$scope.getstdsandtestedit($scope.info.IndId);		
$scope.loadparams($scope.info.IndId,$scope.info.TestIds,$scope.info.Parameters);	
 
 //$scope.info.Parameters=param.Parameters;
			// $scope.sparams= $scope.getLimits( $scope.info.Parameters);		
		}	

//$state.go('app.addsubstd');
	 $scope.stdchemmodalInstance = $uibModal.open({
		keyboard:false,
		  animation: true,
		  size:'lg',
		backdrop:"static",
   	   templateUrl: 'stdchembasicaddModal.html',
     	 scope: $scope,
   	 });
	  
  }
  
  $scope.closechemstdModal=function()
 	{
 		$scope.stdchemmodalInstance.dismiss('cancel');
 	}
  
  $scope.showranges=function(param)
  {
	  //console.log(id);
	  $scope.range=param;
		$scope.showpanel=true;
	  //console.log($scope.range);
	   $scope.modalInstance1 = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'viewModal.html',
		scope:$scope,
         size: "lg",
   
		});
  }
  
  $scope.deleteconcnt=function(con,range)
  {
	if(con==='confirm')
	{
		 $scope.modalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'confirmModal.html',
		scope:$scope,
         size: "md",
   
		});
	}
	else
	{
		//console.log(range);
		$http({
					method:'DELETE',
					url: $rootScope.apiurl+'settingapi/concntdelete/'+range.Id,
					data:range					
					}).then(function successCallback(data) {
				//console.log(data);
			 	//$scope.getpages();
				$scope.cancel();
				$state.go('app.concentrate');
			 	
					}, function errorCallback(data) {
					console.log(data);
		toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
	}
	  
  }
  
  
  $scope.cancel=function()
{
 $scope.modalInstance.close();
}
   $scope.cancel1 = function () {
    $scope.modalInstance1.dismiss('cancel');
  };
  

// $scope.getParameters=function(param)
// {
	// var vparam=[];
	// angular.forEach(param,function(val){
		// if(val.IsMajor===')
	// });
// }
$scope.getallstds=function(pageno)
	{
		$http({
		method:'PUT',
		url: $rootScope.apiurl+'settingapi/substddata/'+exists.uid,
		data:{pl:25,pn:pageno}					
			}).then(function successCallback(data) {
			$scope.allsubdata=data.data.allsubdata;
		$scope.allstds=data.data.allstds;
		$scope.alltypes=alldata.data.alltypes;
			}, function errorCallback(data) {
		console.log(data);
		toaster.pop({toasterId:11,type:"error",body:data.data});
					
		}); 
	}
  
  $scope.rangesave=function(range)
	{  	
		if(angular.isDefined(range.Id))
		{
			console.log(range);
					$http({
				method:'PUT',
				url: $rootScope.apiurl+'settingapi/substdupdate/'+range.Id,
					data:range							
					}).then(function successCallback(data) {
						console.log(data);
				$scope.getallstds(1);
$scope.closechemstdModal();
	toaster.pop({toasterId:11,type:"success",body:"Successfully Updated."});
					}, function errorCallback(data) {
				console.log(data);
				toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
		}
		else
		{
					$http({
				method:'POST',
				url: $rootScope.apiurl+'settingapi/substdadd',
					data:range					
			}).then(function successCallback(data) {
				console.log(data);
					$scope.getallstds(1);
$scope.closechemstdModal();
toaster.pop({toasterId:11,type:"success",body:"Successfully Added."});
				}, function errorCallback(data) {
					console.log(data);
				toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
			
		}
	}
  
  
  
  
  //---fetching sample names for auto suggestion
	$scope.substdnames=[];
	          SubStdNames.fetchNames().then(function(data){	
		  
			$scope.substdnames=data;
		});
		
		
});



App.controller('RolesCntrl', function($scope,$http,$location,$rootScope,$state,toaster,$localstorage,configparam,
$window,$uibModal,AuthenticationService,alldata) {
	
	
	$scope.allroles=[];
	
	$scope.pageSize=15;
	$scope.currentPage=1;
console.log(alldata);
	if(angular.isDefined(alldata.data))
	{
		$scope.allroles=alldata.data.allroles;
	}
	
	$scope.editrole=function(param)
	{
		//console.log(param);
	 	if(param==='new')
		{
			$rootScope.editflag=false;
			$rootScope.disablebtn=true;
			$scope.role={}	;
			$scope.role.Appsections=alldata.data.Appsections;	
		}
		else
		{
			$rootScope.editflag=true;			
			$scope.role=angular.copy(param);
					
		}	
				
		$scope.rolemodalInstance = $uibModal.open({
			keyboard:false,
			backdrop:"static",
		   templateUrl: 'addroleModal',
		   size:'lg',
				 scope: $scope
			
		 });
	}


$scope.closerolemodal=function()
{
	$scope.rolemodalInstance.dismiss('cancel');
}

$scope.che=0;
$scope.selectall=function(param)
{
	$scope.che=param;
	angular.forEach($scope.role.Appsections,function(val,key){
		val.C=$scope.che;
			val.R=$scope.che;
				val.U=$scope.che;
					val.D=$scope.che;
					val.A=$scope.che;
					val.Ch=$scope.che;
		val.Print=$scope.che;
		val.SM=$scope.che;
	});
}
	$scope.issaving=false;
	$scope.saverole=function(param)
	{
		$scope.issaving=true;
		if(angular.isDefined(param.Id))
		{
			$http({
		    		 method:'PUT',
		       		 url:$rootScope.apiurl+'adminapi/updaterole/'+param.Id,
		        data:param
		   			}).then(function successCallback(data) {
					 $scope.issaving=false;
					$scope.closerolemodal();
					$scope.getallroles();
					toaster.pop({toasterId:11,type:"success",body:"Successfully Updated"});
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.issaving=false;
						toaster.pop({toasterId:11,type:"error",body:data.data});
					
				});
		}
		else
		{
			$http({
		    		 method:'POST',
		       		 url:$rootScope.apiurl+'adminapi/addrole',
					 data:param
		        
		   			}).then(function successCallback(data) {
					 $scope.issaving=false;
					$scope.closerolemodal();
					$scope.getallroles();
					toaster.pop({toasterId:11,type:"success",body:"Successfully added."});
		        	}, function errorCallback(data) {
		        		console.log(data);
						$scope.issaving=false;
						toaster.pop({toasterId:11,type:"error",body:data.data});
					
				});
		}
	}

	$scope.deleterole=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.name=id.FName;
			
			 $scope.modalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
   	   templateUrl: 'deluserModal',
     		 scope: $scope,
     	 
   	 });
			
		}
		else if(con=='delete')
		{
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'adminapi/delrole/'+id,
		        
		   			}).then(function successCallback(data) {
					 
					$scope.closeconfirmModal();
					$scope.getallroles();
		        	}, function errorCallback(data) {
		        		console.log(data);
						toaster.pop({toasterId:11,type:"error",body:data.data});
					
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
 		$scope.modalInstance.dismiss('cancel');
 	}

	$scope.getallroles=function()
	{
		var exists=$localstorage.getObject(configparam.AppName);
		var id=exists.uid;
			$http({	
					method:'PUT',
					url:$rootScope.apiurl+'adminapi/roles/'+id,	
					data:{}
	     	 
	     			}).then(function successCallback(data) {
		console.log(data);
			$scope.allroles=data.data.allroles;
		 				
	     			}, function errorCallback(data) {
	     			console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
	     		});
	}
	
	///////////User View
	$scope.showrole = function (size,id) 
	{
		$scope.role=id;   
		$scope.modalInstance = $uibModal.open({
		animation: $scope.animationsEnabled,
		templateUrl: 'roleviewContent.html',
		scope:$scope,
		size: size,
		});
	}

		$scope.cancel = function() 
	{
		$scope.modalInstance.dismiss('cancel');
	};
  
  
  $scope.refreshdata=function()
  {
	  $state.reload();
  }

});




App.controller('userController', function($scope,$http,$location,$rootScope,$state,toaster,$localstorage,
$window,$uibModal,AuthenticationService,alldata) {
	
	$scope.user={};
	$rootScope.users=[];
	$rootScope.disablebtn=false;
	$scope.pageSize=15;
	$scope.currentPage=1;
console.log(alldata);
	if(angular.isDefined(alldata.data))
	{
		$rootScope.users=alldata.data;
	}
	
	

	$scope.deleteuser=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.name=id.FName;
			
			 $scope.modalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
   	   templateUrl: 'deluserModal',
     		 scope: $scope,
     	 
   	 });
			
		}
		else if(con=='delete')
		{
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'settingapi/deluser/'+id,
		        
		   			}).then(function successCallback(data) {
					 
					$scope.closeconfirmModal();
					$rootScope.getallsub();
		        	}, function errorCallback(data) {
		        		console.log(data);
						toaster.pop({toasterId:11,type:"error",body:data.data});
					
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
 		$scope.modalInstance.dismiss('cancel');
 	}

	$rootScope.getallsub=function()
	{
		
			$http({	
					method:'GET',
					url:$rootScope.apiurl+'settingapi/users/',	
	     	 
	     			}).then(function successCallback(data) {
		//console.log(data);
			$rootScope.users=data.data;
		 				
	     			}, function errorCallback(data) {
	     			console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
	     		});
	}
	
	///////////User View
	$scope.showuser = function (size,id) 
	{
		$scope.viewuser=id;   
		$scope.modalInstance = $uibModal.open({
		animation: $scope.animationsEnabled,
		templateUrl: 'userviewContent.html',
		scope:$scope,
		size: size,
		});
	}

		$scope.cancel = function() 
	{
		$scope.modalInstance.dismiss('cancel');
	};
  
    $scope.refreshdata=function()
  {
	  $state.reload();
  }

});


App.controller('adduserController', function($scope,$http,$location,$rootScope,$state,toaster,$localstorage,
$window,$uibModal,AuthenticationService,alldata,$timeout) {
	
	$scope.user={};
	$scope.roles=alldata.data.Roles;
	$scope.editflag=false;
	$scope.issaving=false;
	
	$scope.path='images/signuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	
	$scope.imageflags={};
	$scope.showpwd=false;
	console.log(alldata);
	if(angular.isDefined(alldata.data))
	{
	$scope.allusers=alldata.data.Users;
	$scope.user.Appsections=alldata.data.Appsections;
	$scope.allroles=alldata.data.Roles;
	$scope.allbranches=alldata.data.allbranches;
		
	}
	
	
	
	$scope.imageerror=function(file)
	{
		console.log(file);
		console.log(file.name); 
		console.log(file.error);
		if(angular.isDefined(file.error))
		{
			//console.log("invalid file");
			$scope.imageflags.error=true;
			toaster.pop('error', "", "File size too large or not a valid file type. Only JPG & PNG files allowed.");
			file.$cancel();

		}
	}		
		
		
			$scope.getrole=function(rid)
	{
		var role=_.findWhere($scope.allroles,{Id:rid});
		console.log(role);
		angular.forEach($scope.user.Appsections,function(val,key){
			
			
			var sec=_.findWhere(role.Appsections,{SectionId:val.SectionId});
			
			if(!_.isEmpty(sec))
			{
				
				console.log($scope.user.Appsections[key]);
				console.log(sec);
					$scope.user.Appsections[key].C=sec.C;
					$scope.user.Appsections[key].R=sec.R;
					$scope.user.Appsections[key].U=sec.U;
					$scope.user.Appsections[key].D=sec.D;
					$scope.user.Appsections[key].A=sec.A;
					$scope.user.Appsections[key].Ch=sec.Ch;
					$scope.user.Appsections[key].Print=sec.Print;
					$scope.user.Appsections[key].SM=sec.SM;
					
			}
			
	
		
	});
	}
	
	
	
	$scope.saveuser=function(user)
	{
			$scope.issaving=true;
			//console.log(user);
	
	

		$http({		method:'POST',
				url:$rootScope.apiurl+'settingapi/adduser',	
				data:user
				}).then(function successCallback(data) {
				console.log(data);
			
			if(_.isEmpty($scope.queue))
			{
				console.log("iamge");
				$state.go("app.users",{},{ reload: true });
				//toaster.pop('success', "", "Data added successfully.");
				toaster.pop({toasterId:11,type:"success",body:"Successfully added."});
			}
			else
			{
				console.log("image");
				$scope.userid=angular.fromJson(data.data);
				$timeout(function() {$scope.saveimage();}, 2000);
				//toaster.pop('success', "", "Data added successfully.");
				toaster.pop({toasterId:11,type:"success",body:"Successfully added."});
			}
			
				}, function errorCallback(data) {
					console.log(data);
					$scope.issaving=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
			});
	   			
	
	};
	
	 $scope.cancelsave=function()
	{
		$state.go('app.users');
	}

	
	$scope.saveimage=function()
	{
		
		if($scope.queue.length>0)
		{
		
			for(var i=0;i<$scope.queue.length;i++)
			{
				if(angular.isUndefined($scope.queue[i].id))
				{
				//console.log($scope.userid);
				var file=$scope.queue[i];
				file=_.extend(file,{"userid":$scope.userid});
				$scope.imageflags.error=false;
				var result=file.$submit();
				//console.log(result);
				}
					else
				{
					$state.go('app.users',{},{ reload: true });
				}
							
			}
		}
		else
		{
		//$scope.flags.oneclick=false;
		$state.go('app.users');
		//$scope.cancel();
		}
		
	}

	
	
	$scope.$on('fileuploadstop', function()
	{
		//$scope.flags.oneclick=false;
		$state.go('app.users',{},{ reload: true });
		//$scope.cancel();
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
	data.formData = {userid: data.files[0].userid};
 	});
	
	


	$scope.deletefile=function(index,file)
	{
			file.$cancel();
			$scope.number++;	
	}


});


App.controller('edituserController', function($scope,$http,$location,$rootScope,$state,toaster,$localstorage,
$window,$uibModal,AuthenticationService,alldata,$timeout) {
	
	
	
	$scope.issaving=false;
	$scope.editflag=true;
	$scope.path='images/signuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	$scope.tempqueue=[];
	
	$scope.imageflags={};
	$scope.imageerror=function(file)
	{
		console.log(file);
		console.log(file.name); 
		console.log(file.error);
		if(angular.isDefined(file.error))
		{
			//console.log("invalid file");
			$scope.imageflags.error=true;
			toaster.pop('error', "", "File size too large or not a valid file type. Only JPG & PNG files allowed.");
			file.$cancel();

		}
	}		
	
	$scope.loadimages=function()
	{
	$scope.loadingFiles = true;
		$http.get($scope.path).then(function(response){
						$scope.loadingFiles = true;
					 
					 console.log(response);
					var allfiles=response.data.files;
				
						angular.forEach(allfiles,function(file)
							{
								if(angular.isDefined(file.userid) && parseInt(file.userid)===parseInt($scope.user.Id))
								{
									$scope.queue.push(file);
									
								}
								
							});
					
			
								
					},function(){
					$scope.loadingFiles = false;
				});
				
						
	}
	
	
	if(angular.isDefined(alldata.data))
	{
		console.log(alldata);
		$scope.allusers=alldata.data.Users;
		$scope.user=alldata.data.User;
		$scope.allroles=alldata.data.Roles;
		$scope.allbranches=alldata.data.allbranches;
		$scope.loadimages();
	}
	
	
	$scope.removebranch=function(param)
	{
		$scope.user.DelBranches.push(param);
	}
	
	$scope.getrole=function(rid)
	{
		var role=_.findWhere($scope.allroles,{Id:rid});
		console.log(role);
		angular.forEach($scope.user.Appsections,function(val,key){
			
			
			var sec=_.findWhere(role.Appsections,{SectionId:val.SectionId});
			
			if(!_.isEmpty(sec))
			{
				
				console.log($scope.user.Appsections[key]);
				console.log(sec);
					$scope.user.Appsections[key].C=sec.C;
					$scope.user.Appsections[key].R=sec.R;
					$scope.user.Appsections[key].U=sec.U;
					$scope.user.Appsections[key].D=sec.D;
					$scope.user.Appsections[key].A=sec.A;
					$scope.user.Appsections[key].Ch=sec.Ch;
					$scope.user.Appsections[key].Print=sec.Print;
					$scope.user.Appsections[key].SM=sec.SM;
					
			}
			
	
		
	});
	}
	
		
	$scope.saveuser=function(user)
	{
	$scope.issaving=true;
	console.log(user);
	

		$http({	method:'PUT',
     		url:$rootScope.apiurl+'settingapi/edituser/'+$scope.user.Id,	
     		data:user
     			}).then(function successCallback(data) {
//console.log(data);
				$scope.userid=angular.fromJson(data.data);
				$timeout(function() {$scope.deleteimage();}, 2000);
				
     			}, function errorCallback(data) {
     				console.log(data);
					$scope.issaving=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});
     		});
	
	
	};
	
	 $scope.cancelsave=function()
	{
		$state.go('app.users');
	}

	/**********Edit Sign Image***********/
	  
		$scope.saveimage=function()
		{
			
			if($scope.queue.length>0)
			{
			
				for(var i=0;i<$scope.queue.length;i++)
				{
					if(angular.isUndefined($scope.queue[i].id))
					{
					//console.log($scope.userid);
					var file=$scope.queue[i];
					file=_.extend(file,{"userid":$scope.userid});
					$scope.imageflags.error=false;
					var result=file.$submit();
					console.log(result);
					}
					else
					{
						toaster.pop({toasterId:11,type:"success",body:"Successfully Updated."});
						$state.go('app.users',{},{ reload: true });
					}
								
				}
			}
			else
			{
				
				toaster.pop({toasterId:11,type:"success",body:"Successfully Updated"});
				//$scope.flags.oneclick=false;
			$state.go('app.users',{},{ reload: true });
			
			}
			
		}

		$scope.deleteimage=function()
		{
			//console.log("delete");
			if($scope.tempqueue.length>0)
				{
					for(var i=0;i<$scope.tempqueue.length;i++)
					{
						if(angular.isDefined($scope.tempqueue[i].userid))
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
										//console.log("success 1");
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
			//$scope.flags.oneclick=false;
			toaster.pop('success', "", "User information updated successfully.");
			$state.go('app.users',{},{ reload: true });
			//$scope.cancel();
		});
		

		$scope.$on('fileuploadsubmit', function (e, data) {
	  data.formData = {userid: data.files[0].userid};
	 
	});
		
		


		$scope.deletefile=function(index,file)
		{
			
			if(angular.isUndefined($scope.user.Id))
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
///////////////////DropdownTemp


App.controller('methodsController', function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,alldata) {
	
	$scope.method={};
	$scope.methods=[];
	$scope.tests=[];
	$scope.types=[];
	$scope.disablebtn=false;
	$scope.pageSize=15;
	
	$scope.currentPage=1;
	$scope.issaving=false;
	$scope.indtests=[];
	
	if(angular.isDefined(alldata.data))
	{
		$scope.methods=alldata.data.methods;
		//$scope.tests=alldata.data.tests;
		$scope.allindustries=alldata.data.allindustries;
		//$scope.types=alldata.data.types;
		
	}
	
	
	$scope.getindtests=function(indid)
	{
		$http({
		    		 method:'GET',
		       		 url:'admin/settingapi/getindtests/'+indid,
					
		   		 }).then(function successCallback(data) {
					$scope.confirmModal = false;
					$scope.indtests=data.data.tests;
					//$rootScope.getallsub();
		        	}, function errorCallback(data) {
						toaster.pop({toasterId:11,type:"error",body:data.data.msg});
		        		console.log(data);
						$scope.issaving=false;
				});
	}
	
	$scope.importtest=function()
	{
		$scope.allindustries=alldata.data.allindustries;
		$scope.impinfo={};
		$scope.mydata={};
		$scope.impmodalInstance = $uibModal.open({
			keyboard:false,
			backdrop:"static",
		   templateUrl: 'templates/import/imptestmethods.html',
				 scope: $scope
			
		 });
	}
	
	$scope.closeimpmodal=function()
	{
		$scope.impmodalInstance.dismiss('cancel');
	}
	
	
	 $scope.SelectFile = function (file) {
			console.log(file);
                $scope.SelectedFile = file;
            };
			
            $scope.Upload = function () {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            reader.onload = function (e) {
                                $scope.ProcessExcel(e.target.result);
                            };
                            reader.readAsBinaryString($scope.SelectedFile);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }
                                $scope.ProcessExcel(data);
                            };
                            reader.readAsArrayBuffer($scope.SelectedFile);
                        }
                    } else {
                        $window.alert("This browser does not support HTML5.");
                    }
                } else {
                    $window.alert("Please upload a valid Excel file.");
                }
            };


$scope.mydata={};
            $scope.ProcessExcel = function (data) {
                //Read the Excel File data.
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });

                //Fetch the name of First Sheet.
                var firstSheet = workbook.SheetNames[0];

   // var bstr = e.target.result;
          // var wb = XLSX.read(bstr, {type:'binary'});

          // /* grab first sheet */
          // var wsname = wb.SheetNames[0];
          var ws = workbook.Sheets[firstSheet];
                //Read all rows from First Sheet into an JSON array.
               // var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				
				var aoa = XLSX.utils.sheet_to_json(ws, {header:1, raw:false});
          var cols = [];
          for(var i = 0; i < aoa[0].length; ++i) cols[i] = { field: aoa[0][i] };

          /* generate rest of the data */
          var data = [];
          for(var r = 1; r < aoa.length; ++r) {
            data[r-1] = {};
            for(i = 0; i < aoa[r].length; ++i) {
              if(aoa[r][i] == null) continue;
              data[r-1][aoa[0][i]] = aoa[r][i]
            }
          }
 $scope.$apply(function () {
					$scope.mydata.heads=cols;
                    $scope.mydata.Products = data;
					$scope.dbtotalitems=data.length;
					angular.forEach($scope.mydata.Products,function(val){
						val.IndId=$scope.impinfo.IndId;
					})
					//console.log($scope.mydata.Products);
                    $scope.mydata.IsVisible = 1;
                });
            };
			
			$scope.dbpageSize=25;
			$scope.dbcurrentPage=1;
			$scope.dbtotalitems=0;
			
			
			$scope.uploadtodb=function(con,log)
			{
				

				if(con=='confirm')
				{
					$scope.confirmModal = true;
					
					$scope.log=$scope.mydata;
				
					
					 $scope.dbmodalInstance = $uibModal.open({
				keyboard:false,
				backdrop:"static",
			   templateUrl: 'templates/import/confirmdbModal.html',
					 scope: $scope,
				 
			 });
					
				}
				else if(con=='approved')
				{
					$scope.issaving=true;
					$http({
		    		 method:'POST',
		       		 url:'admin/adminapi/importtestmethod/',
					data:log
		   		 }).then(function successCallback(data) {
					$scope.confirmModal = false;
					$scope.issaving=false;
					$scope.closedbconfirmModal();
					$scope.closeimpmodal();
					$rootScope.getallmethod();
					console.log(data);
					//$rootScope.getallsub();
		        	}, function errorCallback(data) {
						toaster.pop({toasterId:11,type:"error",body:data.data.msg});
		        		console.log(data);
						$scope.issaving=false;
				});
		}

	}

	$scope.closedbconfirmModal=function()
 	{
 		$scope.dbmodalInstance.dismiss('cancel');
 	}
	
			
			
	$scope.addmethod=function(param)
	{
		console.log(param);
	 	if(param==='new')
		{
			$rootScope.editflag=false;
			$rootScope.disablebtn=true;
			$scope.method={IndId:$scope.allindustries[0].Id,TestId:"",Method:""}	;
$scope.indtests=[];		
$scope.getindtests($scope.allindustries[0].Id);	
		}
		else
		{
			$rootScope.editflag=true;
			
			$scope.method=angular.copy(param);
			$scope.getindtests($scope.method.IndId);
		}	
				
		$scope.modalInstance = $uibModal.open({
			keyboard:false,
			backdrop:"static",
		   templateUrl: 'addmethodModal',
				 scope: $scope
			
		 });
	}

	$scope.deletemethod=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.name=id.Method;
			
					$scope.modalInstance2 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
			   templateUrl: 'delmethodModal',
					 scope: $scope,
				
			 });
		}
		else if(con=='delete')
		{
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'settingapi/method/'+id,
		        
		   			}).then(function successCallback(data) {
				
					$rootScope.getallmethod();
						$scope.closeconfirmModal();
						toaster.pop({toasterId:11,type:"success",body:"Deleted successfully."});
		        		}, function errorCallback(data) {
		        		console.log(data);
						toaster.pop({toasterId:11,type:"error",body:data.data});
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
 		$scope.modalInstance2.dismiss('cancel');
 	}

	$rootScope.getallmethod=function()
	{
	 	$http({	
		method:'GET',
	     	url:$rootScope.apiurl+'settingapi/methods',	
	     	 
	     			}).then(function successCallback(data) {
		$scope.methods=data.data.methods;
		$scope.tests=data.data.tests;
	     				
	     			}, function errorCallback(data) {
	     			console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
	     		});
	}
	
	$scope.savemethod=function(method)
	{
$scope.issaving=true;
	//console.log(method);
	if(angular.isDefined($scope.method.Id))
	{

		$http({	method:'PUT',
     		url:$rootScope.apiurl+'settingapi/methodupdate/'+method.Id,	
     		data:method
     			}).then(function successCallback(data) {
					
				toaster.pop({toasterId:11,type:"success",body:"Successfully Updated."});
				$rootScope.getallmethod();
				$scope.cancel();
     			}, function errorCallback(data) {
     				console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
     		});
	}
	else
	{	


		$http({		method:'POST',
				url:$rootScope.apiurl+'settingapi/methodadd',	
				data:method
				}).then(function successCallback(data) {
				
				toaster.pop({toasterId:11,type:"success",body:"Successfully Added."});
				$rootScope.getallmethod();
				$scope.cancel();
				
				}, function errorCallback(data) {
					console.log(data);
			});
	 }   			
	
	};
	
	
	$scope.cancel = function () {
			
			$scope.method={};
			$scope.issaving=false;
			$rootScope.disablebtn=false;
    	$scope.modalInstance.dismiss('cancel');
 	 };


});


App.controller('mdstdsController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,configparam,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.allsubdata=[];
	$scope.animationsEnabled = true;
var exists=$localstorage.getObject(configparam.AppName);
	$scope.pageSize=25;
	$scope.currentPage=1;
	  
	if(angular.isDefined(alldata.data))
	{
		
		$scope.totalcount=alldata.data.totalitems;
		$scope.allmdstds=alldata.data.allmdstds;
		
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
					url:$rootScope.apiurl+'settingapi/searchmdstds/',	
					data:{text:searchtext,pageSize:$scope.pageSize}
	     	 
	     			}).then(function successCallback(data) {
					$scope.pageSize=25;
					console.log(data);
				$scope.allmdstds=data.data.allmdstds;
		$scope.totalcount=data.data.totalitems;
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

  $scope.editconcnt=function(param,id)
  {	 
		
		//$state.go('app.addsubstd', null, { reload: true });
	 	if(param==='new')
		{
		
$state.go("app.addmdstds");		
					
		}
		else
		{		
		$state.go("app.editmdstds",{id:id});		
		}	


	  
  }
  
  
  $scope.showmdstds=function(param)
  {
	  //console.log(id);
	  $scope.info=angular.copy(param);
		$scope.showpanel=true;
	  //console.log($scope.range);
	   $scope.modalInstance1 = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'viewmdstdsModal.html',
		scope:$scope,
         size: "lg",
   
		});
  }
  
  $scope.deleteconcnt=function(con,range)
  {
	if(con==='confirm')
	{
		 $scope.modalInstance = $uibModal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'confirmModal.html',
		scope:$scope,
         size: "md",
   
		});
	}
	else
	{
		//console.log(range);
		$http({
					method:'DELETE',
					url: $rootScope.apiurl+'settingapi/concntdelete/'+range.Id,
					data:range					
					}).then(function successCallback(data) {
				//console.log(data);
			 	//$scope.getpages();
				$scope.cancel();
				$state.go('app.concentrate');
			 	
					}, function errorCallback(data) {
					console.log(data);
		toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
	}
	  
  }
  
  
  $scope.cancel=function()
{
 $scope.modalInstance.close();
}
   $scope.cancel1 = function () {
    $scope.modalInstance1.dismiss('cancel');
  };
  


$scope.getallmdstds=function(pageno)
	{
		$http({
		method:'PUT',
		url: $rootScope.apiurl+'settingapi/mdstdsdata/'+exists.uid,
		data:{pl:25,pn:pageno}					
			}).then(function successCallback(data) {
		$scope.totalcount=data.data.totalitems;
		$scope.allmdstds=data.data.allmdstds;
			}, function errorCallback(data) {
		console.log(data);
		toaster.pop({toasterId:11,type:"error",body:data.data});
					
		}); 
	}

});

App.controller('mdstdsaddController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,configparam,
$rootScope,$state,AuthenticationService,MdsTdsNames,$timeout,alldata)
{
	'use strict';
	
$scope.path='images/mdstdsuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	
	if(angular.isDefined(alldata.data))
	{
		
		$scope.info={};
		$scope.info.allmdstdstests=[];
		$scope.alltests=angular.copy(alldata.data.alltests);
		$scope.alltypes=alldata.data.alltypes;
		$scope.stds=[];
		 console.log($scope.alltests);
	}
	
	
	$scope.closeqitemModal=function()
	{
		$scope.quoteitemmodal.dismiss('cancel');
	}
	
	
	$scope.addtest=function(param,idx)
	{
			if(param==='new')
			{
			$scope.qselect={};
				
				$scope.qtest={};	
				$scope.qtest.index='';	
					
				
					
			}
			else
			{			
				
				$scope.qtest=param;
				$scope.qtest.index=idx;				
			}		
			
			
			
			 $scope.quoteitemmodal = $uibModal.open({
			keyboard:false,
			  animation: true,
			  size:'lg',
			backdrop:"static",
		   templateUrl: 'templates/settings/mdstestadd.html',
			 scope: $scope,
		 });
		
	}
	
	$scope.deltest=function(param,idx)
	{
		$scope.info.allmdstdstests.splice(idx,1);			
	}
	
	
	$scope.gettestdet=function(par1,testid)
	{
		console.log(testid);
		console.log(par1);
		
		var test=_.findWhere(alldata.data.alltests,{TUID:testid});
			test.SSDID="";
		test.TMID="";
		$scope.qtest=angular.copy(test);
		console.log($scope.qtest);
		
		$http({
					method:'GET',
					url: $rootScope.apiurl+'settingapi/getteststds/'+testid,
								
					}).then(function successCallback(data) {
		console.log(testid);
		console.log(data);
		
		
		$scope.teststds=data.data.allsubstds;
			$scope.alltestmethods=data.data.alltestmethods;
		console.log($scope.stds); 
	
		//$scope.info.allmdstdstests.push(test);
						}, function errorCallback(data) {
					console.log(data);
					
					
				}); 
					
	}
	
	
	$scope.getstdparams=function(ssid)
	{
		$http({
					method:'GET',
					url: $rootScope.apiurl+'settingapi/substdparams/'+ssid,
								
					}).then(function successCallback(data) {
	
		console.log(data.data.testparams);
		angular.forEach($scope.qtest.tobsparams,function(val) {
			
			var par1=_.findWhere(data.data.testparams,{PSymbol:val.PSymbol});
			console.log(par1);
			if(!_.isEmpty(par1))
			{
				val.SpecMin=par1.SpecMin;
				val.SpecMax=par1.SpecMax;
			}
			
			
		});
		
	
	
		//$scope.info.allmdstdstests.push(test);
						}, function errorCallback(data) {
					console.log(data);
					
					
				}); 
	}
	
	
	$scope.getsubstandards=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.teststds,{Id:type});
		}
	}
	
	
	$scope.gettestmethod=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.alltestmethods,{Id:type});
		}
	}
	
	
	$scope.qitemsave=function(qitem,idx)
	{
		console.log(qitem);
		console.log(idx);
		if(qitem.IsStd)
		{
			var std=_.findWhere($scope.teststds,{Id:qitem.SSDID});		
			
		}
		
		if(qitem.IsTM)
		{
			var testmethod=_.findWhere($scope.alltestmethods,{Id:qitem.TMID});		
			
		}
		 
		 
			
			
			var item={	
					
					TID:qitem.TID,TestName:qitem.TestName,TUID:qitem.TUID,
					IsStd:qitem.IsStd,IsTestMethod:qitem.IsTestMethod,
					SSDID:qitem.SSDID,StdName:qitem.IsStd?std.Name:null,
					TMID:qitem.TMID,TestMethod:_.isEmpty(testmethod)?null:testmethod.Method,
					Frequency:qitem.Frequency,tbaseparams:qitem.tbaseparams,tobsparams:qitem.tobsparams,
					
					 
					};
					
					if( idx === null || idx==='' || angular.isUndefined(idx) )
				{
						$scope.info.allmdstdstests.push(item);
				}					
				else
				{
					$scope.info.allmdstdstests[idx]=item;	
				
				}
		 
		
		$scope.closeqitemModal();
	}
	
	
	//---fetching sample names for auto suggestion
	$scope.mdstdsnames=[];
	          MdsTdsNames.fetchNames().then(function(data){	
		  
			$scope.mdstdsnames=data;
		});
	
	$scope.gettest=function(par1,testid)
	{
		$http({
					method:'GET',
					url: $rootScope.apiurl+'settingapi/getteststds/'+testid,
								
					}).then(function successCallback(data) {
		console.log(testid);
		console.log(data);
		var test=_.findWhere(alldata.data.alltests,{TID:testid});
		$scope.stds[test.TUID]=[];
		$scope.stds[test.TUID]=data.data.allsubstds;
		console.log($scope.stds);
		test.SSDID="";
		test.TMID="";
		$scope.info.allmdstdstests.push(test);
						}, function errorCallback(data) {
					console.log(data);
					
					
				}); 
					
	}
	
	$scope.removeapptest=function(model,testid)
	{
		$scope.info.allmdstdstests=_.without($scope.info.allmdstdstests, _.findWhere($scope.info.allmdstdstests,{TID:testid}));
	}
	
	
	 

  $scope.mdssave=function(range)
	{  	
		console.log(range);
		
					$http({
				method:'POST',
				url: $rootScope.apiurl+'settingapi/mdstdsadd',
					data:range					
			}).then(function successCallback(data) {
				console.log(data);
				$scope.mdtdid=angular.fromJson(data.data);
				$scope.saveimage();
					// $scope.getallstds(1);
// $scope.closechemstdModal();
// toaster.pop({toasterId:11,type:"success",body:"Successfully Added."});
				}, function errorCallback(data) {
					console.log(data);
				toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
			
		
	}
  
  
	$scope.saveimage=function()
		{
			//debugger;
			console.log("save image");
			if($scope.queue.length>0)
			{
			
				for(var i=0;i<$scope.queue.length;i++)
				{
					if(angular.isUndefined($scope.queue[i].id))
					{
					//console.log($scope.productid);
					var file=$scope.queue[i];
					file=_.extend(file,{"mdtdid":$scope.mdtdid});
					var result=file.$submit();
					console.log(result);
					}
					else
					{
						$state.go('app.mdstds') ;
					}
								
				}
			}
			else
			{
				$state.go('app.mdstds') ;
			}
			
		}

	
	$scope.$on('fileuploadstop', function()
	{
	$state.go('app.mdstds') ;
			
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {mdtdid: data.files[0].mdtdid};
 
});
	
	


	$scope.deletefile=function(index,file)
	{
		console.log(file);
		if(angular.isUndefined(file.id))
		{
			console.log("remove");
			file.$cancel();
			
		}
		
	}
	
	
	


  
});


App.controller('mdstdseditController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,configparam,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
$scope.path='images/mdstdsuploads/index.php';
	$scope.options = { url:$scope.path};
	$scope.queue=[];
	$scope.tempqueue=[];
	

$scope.closeqitemModal=function()
	{
		$scope.quoteitemmodal.dismiss('cancel');
	}
	
	
	$scope.addtest=function(param,idx)
	{
			if(param==='new')
			{
			$scope.qselect={};
				
				$scope.qtest={};	
				$scope.qtest.index='';	
					
				
					
			}
			else
			{		
		console.log(param);
		console.log(param.TUID);
		$scope.qselect={};	
		$scope.qselect.TestId=param.TUID;	
				var test=_.findWhere(alldata.data.alltests,{TUID:param.TUID});
				$scope.gettestdet(test,param.TUID);	
				$scope.qtest=param;
				$scope.qtest.index=idx;	
					
			
			}		
			
			
			
			 $scope.quoteitemmodal = $uibModal.open({
			keyboard:false,
			  animation: true,
			  size:'lg',
			backdrop:"static",
		   templateUrl: 'templates/settings/mdstestadd.html',
			 scope: $scope,
		 });
		
	}
	
	$scope.deltest=function(param,idx)
	{
		if(angular.isDefined(param.Id))
		{
			$scope.info.delmdstdstest.push(param);
		}
		$scope.info.allmdstdstests.splice(idx,1);	
		
	}
	
	$scope.gettestdet=function(par1,testid)
	{
		console.log(testid);
		console.log(par1);
		
		var test=_.findWhere(alldata.data.alltests,{TUID:testid});
			test.SSDID="";
		test.TMID="";
		$scope.qtest=angular.copy(test);
		console.log($scope.qtest);
		
		$http({
					method:'GET',
					url: $rootScope.apiurl+'settingapi/getteststds/'+testid,
								
					}).then(function successCallback(data) {
		console.log(testid);
		console.log(data);
		
		
		$scope.teststds=data.data.allsubstds;
			$scope.alltestmethods=data.data.alltestmethods;
		console.log($scope.stds); 
	
		//$scope.info.allmdstdstests.push(test);
						}, function errorCallback(data) {
					console.log(data);
					
					
				}); 
					
	}
	
	
	$scope.getsubstandards=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.teststds,{Id:type});
		}
	}
	
	
	$scope.gettestmethod=function(type)
	{
		if(angular.isDefined(type))
		{
		return _.where($scope.alltestmethods,{Id:type});
		}
	}
	
	
	$scope.qitemsave=function(qitem,idx)
	{
		console.log(qitem);
		console.log(idx);
		if(qitem.IsStd)
		{
			var std=_.findWhere($scope.teststds,{Id:qitem.SSDID});		
			
		}
		
		if(qitem.IsTM)
		{
			var testmethod=_.findWhere($scope.alltestmethods,{Id:qitem.TMID});		
			
		}
		 
		 var item={};
			if(angular.isDefined(qitem.Id))
			{
				var item={	
					
					TID:qitem.TID,TestName:qitem.TestName,TUID:qitem.TUID,
					IsStd:qitem.IsStd,IsTestMethod:qitem.IsTestMethod,Id:qitem.Id,
					SSDID:qitem.SSDID,StdName:qitem.IsStd?std.Name:null,
					TMID:qitem.TMID,TestMethod:_.isEmpty(testmethod)?null:testmethod.Method,
					Frequency:qitem.Frequency,tbaseparams:qitem.tbaseparams,tobsparams:qitem.tobsparams,
					
					 
					};
			}
			else
			{
				var item={	
					
					TID:qitem.TID,TestName:qitem.TestName,TUID:qitem.TUID,
					IsStd:qitem.IsStd,IsTestMethod:qitem.IsTestMethod,
					SSDID:qitem.SSDID,StdName:qitem.IsStd?std.Name:null,
					TMID:qitem.TMID,TestMethod:_.isEmpty(testmethod)?null:testmethod.Method,
					Frequency:qitem.Frequency,tbaseparams:qitem.tbaseparams,tobsparams:qitem.tobsparams,
					
					 
					};
			}
			
			
					
				if( idx === null || idx==='' || angular.isUndefined(idx) || angular.isUndefined(qitem.Id) )
				{
						$scope.info.allmdstdstests.push(item);
				}					
				else
				{
					$scope.info.allmdstdstests[idx]=item;	
				
				}
		 
		
		$scope.closeqitemModal();
	}
	
	
	
	$scope.gettest=function(par1,testid)
	{
		$http({
					method:'GET',
					url: $rootScope.apiurl+'settingapi/getteststds/'+testid,
								
					}).then(function successCallback(data) {
		console.log(testid);
		console.log(data);
		var test=_.findWhere(alldata.data.alltests,{TID:testid});
		$scope.stds[test.TUID]=[];
		$scope.stds[test.TUID]=data.data.allsubstds;
		console.log($scope.stds);
		test.SSDID="";
		test.TMID="";
		$scope.info.allmdstdstests.push(test);
						}, function errorCallback(data) {
					console.log(data);
					
					
				}); 
					
	}
	
	$scope.getteststd=function(param)
	{
		$http({
					method:'GET',
					url: $rootScope.apiurl+'settingapi/getteststds/'+param.TID,
								
					}).then(function successCallback(data) {
		console.log(param.TID);
		console.log(data);
		var test=_.findWhere(alldata.data.alltests,{TID:param.TID});
		$scope.stds[test.TUID]=[];
		$scope.stds[test.TUID]=data.data.allsubstds;
		console.log($scope.stds);
	
	
						}, function errorCallback(data) {
					console.log(data);
					
					
				}); 
					
	}
	
	
	if(angular.isDefined(alldata.data))
	{
		
		$scope.info={};
		$scope.info.allmdstdstests=[];
		$scope.alltests=angular.copy(alldata.data.alltests);
		$scope.alltypes=alldata.data.alltypes;
		$scope.stds=[];
		$scope.info=alldata.data.mdstds;
		 console.log($scope.alltests);
		 console.log($scope.info);
		 
		 // angular.forEach($scope.info.allmdstdstests,function(val){
			 // $scope.getteststd(val);
		 // })
		 
		// $scope.loadimages($scope.info.Id);
		
	}
	
	// $scope.getstds=function(tuid)
	// {console.log(tuid);
		// var stt=$scope.stds[tuid];
	// console.log(stt);
		// return stt;
	// }
	
	
	
	$scope.removeapptest=function(model,testid)
	{
		console.log(model);
		console.log(testid);
		$scope.info.allmdstdstests=_.without($scope.info.allmdstdstests, _.findWhere($scope.info.allmdstdstests,{TID:testid}));
	}
	
	
	 

  $scope.mdssave=function(range)
	{  	
		console.log(range);
		
					$http({
				method:'PUT',
				url: $rootScope.apiurl+'settingapi/mdstdsupdate/'+range.Id,
					data:range					
			}).then(function successCallback(data) {
				console.log(data);
				$scope.mdtdid=angular.fromJson(data.data.MdsTdsId);
				$scope.deleteimage();
					// $scope.getallstds(1);
// $scope.closechemstdModal();
// toaster.pop({toasterId:11,type:"success",body:"Successfully Added."});
				}, function errorCallback(data) {
					console.log(data);
				toaster.pop({toasterId:11,type:"error",body:data.data});
					
				}); 
			
		
	}
  
  
	$scope.saveimage=function()
		{
			//debugger;
			console.log("save image");
			if($scope.queue.length>0)
			{
			
				for(var i=0;i<$scope.queue.length;i++)
				{
					if(angular.isUndefined($scope.queue[i].id))
					{
					//console.log($scope.productid);
					var file=$scope.queue[i];
					file=_.extend(file,{"mdtdid":$scope.mdtdid});
					var result=file.$submit();
					console.log(result);
					}
					else
					{
						$state.go('app.mdstds') ;
					}
								
				}
			}
			else
			{
				$state.go('app.mdstds') ;
			}
			
		}

	
	$scope.$on('fileuploadstop', function()
	{
	$state.go('app.mdstds') ;
			
	});
	

	$scope.$on('fileuploadsubmit', function (e, data) {
  data.formData = {mdtdid: data.files[0].mdtdid};
 
});
	
	


	$scope.deleteimage=function()
	{
		
				$scope.saveimage();
			
		
	}
		
	
	

	
	
	


	$scope.deletefile=function(index,file)
	{
		console.log(file);
		if(angular.isUndefined(file.id))
		{
			console.log("remove");
			file.$cancel();
			
		}
		else
		{
			console.log("tenp remove");
			$scope.tempqueue.push(file);	
$scope.info.delfiles=$scope.tempqueue;			
			$scope.info.files.splice(index,1);	
			
		}
	}

  
});

App.filter('chunk', function() {
    return function(input, size) {
        if (!input || !input.length) return [];
        const result = [];
        for (let i = 0; i < input.length; i += size) {
            result.push(input.slice(i, i + size));
        }
        return result;
    };
});


/**----------------Customer CONTROLLER------------------------------------------******/
App.controller('customerController', function($scope,$http,$location,$rootScope,$state,toaster,
$window,$uibModal,AuthenticationService,allcustdata) {
	
	$scope.loadingFiles = true;	
	
	$scope.queue=[];
	$rootScope.allcustomer=[];
	$rootScope.disablebtn=false;
	$scope.pageSize=15;
	$scope.currentPage=1;

	if(angular.isDefined(allcustdata.data))
	{
		console.log(allcustdata);
		$rootScope.allcustomer=allcustdata.data.customers;
		$scope.allindustries=allcustdata.data.industries;
		$scope.allusers=allcustdata.data.allusers;
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
	

		$scope.addcustomer=function(param)
	{
		
	 	if(param==='new')
		{
			$rootScope.editflag=false;
			$rootScope.disablebtn=true;
			$scope.customer={}	;
$scope.customer.Addresses=[];
		
		}
		else
		{
			$rootScope.editflag=true;
			$scope.customer=angular.copy(param);
				
		}	
				


	$scope.modalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
   	   templateUrl: 'templates/users/customeradd.html',
     		scope:$scope,
     	
   	 });
	}

	$scope.deletecust=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.Name=id.Name;
			$scope.modalInstance2 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'delcustModal',
				scope:$scope,
     	
				});
		}
		else if(con=='delete')
		{
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'settingapi/customer/'+id,
		        
		   			}).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getallcust();
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		console.log(data);
						toaster.pop({toasterId:11,type:"error",body:data.data});
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
 		$scope.modalInstance2.dismiss('cancel');
 	}

	$scope.getallcust=function()
	{
			$http({	
					method:'GET',
					url:$rootScope.apiurl+'settingapi/getcustomers',	
	     	 
	     		}).then(function successCallback(data) {
					//console.log(data);
					$scope.allcustomer=data.data.customers;
					$scope.allindustries=data.data.industries;
			     				
	     			}, function errorCallback(data) {
	     			console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
	     		});
	}
	
	$scope.custcancel = function () {
			
			$scope.customer={}	
			$scope.issaving=false;
			$rootScope.disablebtn=false;
    	$scope.modalInstance.dismiss('cancel');
		
 	 };
	 
	 
	 
	 $scope.issaving=false;
	$scope.savecustomer=function(customer)
	{
	$scope.issaving=true;
	
	if(angular.isDefined($scope.customer.Id))
	{

		$http({	method:'PUT',
     		url:$rootScope.apiurl+'settingapi/updcustomer/'+$scope.customer.Id,	
     		data:customer
     			}).then(function successCallback(data) {
				toaster.pop({toasterId:11,type:"success",body:"Successfully Updated."});
				$scope.getallcust();
				$scope.custcancel();
		
     			}, function errorCallback(data) {
     				console.log(data);
					 $scope.issaving=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});
     		});
	}
	else
	{	
customer.CreatedBy=$rootScope.dboard.uid;
		$http({		method:'POST',
				url:$rootScope.apiurl+'settingapi/customeradd',	
				data:customer
				}).then(function successCallback(data) {
			
				//console.log(data);
			//	$scope.categoryid=angular.fromJson(data);
					toaster.pop({toasterId:11,type:"success",body:"Successfully Added."});
				$scope.getallcust();
				$scope.custcancel();
				}, function errorCallback(data) {
					console.log(data);
					 $scope.issaving=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 }   			
	
	};

});

App.controller('supplierController', function($scope,$http,$location,$rootScope,$state,toaster,
$window,$uibModal,AuthenticationService,alldata) {
	
	$scope.loadingFiles = true;	
	
	$scope.queue=[];
	$rootScope.allsuppliers=[];
	$rootScope.disablebtn=false;
	$scope.pageSize=15;
	$scope.currentPage=1;

	if(angular.isDefined(alldata.data))
	{
		console.log(alldata);
		$rootScope.allsuppliers=alldata.data;
	}
	

		$scope.addsupplier=function(param)
	{
		
	 	if(param==='new')
		{
			$rootScope.editflag=false;
			$rootScope.disablebtn=true;
			$scope.supplier={}				
		}
		else
		{
			$rootScope.editflag=true;
			$scope.supplier={Id:param.Id,Name:param.Name,Email:param.Email,Address:param.Address,Landmark:param.Landmark,ContactNo:param.ContactNo}
				
		}	
				


	$scope.modalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
   	   templateUrl: 'supplierModal',
     		scope:$scope,
     	
   	 });
	}

	$scope.deletesupplier=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.Name=id.Name;
			$scope.modalInstance2 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'delcustModal',
				scope:$scope,
     	
				});
		}
		else if(con=='delete')
		{
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'settingapi/delsupplier/'+id,
		        
		   			}).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getallsupplier();
					$scope.closeconfirmModal();
		        	}, function errorCallback(data) {
		        		console.log(data);
						toaster.pop({toasterId:11,type:"error",body:data.data});
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
 		$scope.modalInstance2.dismiss('cancel');
 	}

	$scope.getallsupplier=function()
	{
			$http({	
					method:'GET',
					url:$rootScope.apiurl+'settingapi/getsuppliers',	
	     	 
	     		}).then(function successCallback(data) {
					//console.log(data);
					$scope.allsuppliers=data.data;
			     				
	     			}, function errorCallback(data) {
	     			console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
	     		});
	}
	
	$scope.cancel = function () {
			
			$scope.supplier={}	
			$scope.issaving=false;
			$rootScope.disablebtn=false;
    	$scope.modalInstance.dismiss('cancel');
		
 	 };
	 
	 
	 
	 $scope.issaving=false;
	$scope.savesupplier=function(supplier)
	{
	$scope.issaving=true;
	
	if(angular.isDefined($scope.supplier.Id))
	{

		$http({	method:'PUT',
     		url:$rootScope.apiurl+'settingapi/updsupplier/'+$scope.supplier.Id,	
     		data:{Name:supplier.Name,Email:supplier.Email,Address:supplier.Address,Landmark:supplier.Landmark,ContactNo:supplier.ContactNo}
     			}).then(function successCallback(data) {
				toaster.pop('wait', "", "supplier Detail successfully Updated.");
				$scope.getallsupplier();
				$scope.cancel();
		
     			}, function errorCallback(data) {
     				console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
     		});
	}
	else
	{	

		$http({		method:'POST',
				url:$rootScope.apiurl+'settingapi/addsupplier',	
				data:supplier
				}).then(function successCallback(data) {
			
				//console.log(data);
			//	$scope.categoryid=angular.fromJson(data);
				toaster.pop('wait', "", "supplier Detail successfully saved.");
				$scope.getallsupplier();
				$scope.cancel();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 }   			
	
	};

});



//----------Drop down list------------//
App.controller('dropdownController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,alldata)
{
	'use strict';
	
	$scope.dropdown={};
	$scope.dropdowns=[];
	$scope.pageSize=15;
	$scope.currentPage=1;
	
	$rootScope.disablebtn=false;
	
	if(angular.isDefined(alldata.data))
	{
		$scope.page={};
		$scope.dropdowns=alldata.data.dropdowns;
		$scope.page.categories=alldata.data.categories;
		//console.log($scope.temperatures);
	}
	
	
	
		$scope.adddropdown=function(param)
	{
		
	 	if(param==='new')
		{
			$rootScope.editflag=false;
			$rootScope.disablebtn=true;
			$scope.dropdown={}				
		}
		else
		{
			$rootScope.editflag=true;
			$scope.dropdown=param;
				
		}	
				


	$scope.modalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
   	   templateUrl: 'dropdownModal',
     		scope:$scope,
     	
   	 });
	}

	$scope.deldropdown=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.Temp=id.Temp;
			$scope.modalInstance2 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'deltempModal',
				scope:$scope,
     	
				});
		}
		else if(con=='delete')
		{
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'settingapi/deldropdown/'+id,
		        
		   			}).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getalltemp();
					$scope.closeconfirmModal();
		        		}, function errorCallback(data) {
		        		console.log(data);
						toaster.pop({toasterId:11,type:"error",body:data.data});
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
 		$scope.modalInstance2.dismiss('cancel');
 	}

	$scope.getalltemp=function()
	{
			$http({	
					method:'GET',
					url:$rootScope.apiurl+'settingapi/dropdowns',	
	     	 
	     			}).then(function successCallback(data) {
					//console.log(data);
					$scope.dropdowns=data.data.dropdowns;
			     				
	     			}, function errorCallback(data) {
	     			console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
	     		});
	}
	
$scope.cancel = function () {
			
			$scope.dropdown={}	
			$scope.issaving=false;
			$rootScope.disablebtn=false;
    	$scope.modalInstance.dismiss('cancel');
		
 	 };
	 
	 
	 
	 $scope.issaving=false;
	$scope.savedropdown=function(dropdown)
	{
$scope.issaving=true;
	console.log(dropdown);
	if(angular.isDefined(dropdown.Id))
	{

		$http({	method:'PUT',
     		url:$rootScope.apiurl+'settingapi/updatedropdown/'+dropdown.Id,	
     		data:dropdown
     		}).then(function successCallback(data) {
				
				$scope.getalltemp();
				$scope.cancel();
		
     			}, function errorCallback(data) {
     				console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
     		});
	}
	else
	{	

		$http({		method:'POST',
				url:$rootScope.apiurl+'settingapi/adddropdown',	
				data:dropdown
				}).then(function successCallback(data) {
			
			//console.log(data);
			//	$scope.categoryid=angular.fromJson(data);
				toaster.pop('wait', "", "Temperature successfully saved.");
				$scope.getalltemp();
				$scope.cancel();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 }   			
	
	};


$scope.addcategory=function()
{
	
	$scope.lcategory={};
	$scope.catmodalinst = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'templates/appsetting/category.html',
				scope:$scope,
     	
				});
}

$scope.closecatModal=function()
{
		$scope.catmodalinst.dismiss('cancel');
}

$scope.categorysave=function(param)
{ console.log(param);
		$http({		method:'POST',
				url:$rootScope.apiurl+'adminapi/addcategory',	
				data:param
				}).then(function successCallback(data) {
					console.log(data);
			$scope.page.categories=[];
			$scope.page.categories=data.data.categories;
				
				$scope.closecatModal();
			
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
}

});



//----------External Test------------//
App.controller('extestController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,alltempdata)
{
	'use strict';
	
	$scope.extest={};
	$scope.extests=[];
	$scope.pageSize=15;
	$scope.currentPage=1;
	
	$rootScope.disablebtn=false;
	
	if(angular.isDefined(alltempdata.data))
	{
		$scope.extests=alltempdata.data;
		//console.log($scope.temperatures);
	}
	
	
	
		$scope.addextest=function(param)
	{
		
	 	if(param==='new')
		{
			$rootScope.editflag=false;
			$rootScope.disablebtn=true;
			$scope.extest={}				
		}
		else
		{
			$rootScope.editflag=true;
			$scope.extest={Id:param.Id,TestName:param.TestName}
				
		}	
				


	$scope.modalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
   	   templateUrl: 'temperatureModal',
     		scope:$scope,
     	
   	 });
	}

	$scope.deletetemp=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.Temp=id.Temp;
			$scope.modalInstance2 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'deltempModal',
				scope:$scope,
     	
				});
		}
		else if(con=='delete')
		{
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'settingapi/droptemp/'+id,
		        
		   			}).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getalltemp();
					$scope.closeconfirmModal();
		        		}, function errorCallback(data) {
		        		console.log(data);
						toaster.pop({toasterId:11,type:"error",body:data.data});
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
 		$scope.modalInstance2.dismiss('cancel');
 	}

	$scope.getalltemp=function()
	{
			$http({	
					method:'GET',
					url:$rootScope.apiurl+'settingapi/droptemp',	
	     	 
	     			}).then(function successCallback(data) {
					//console.log(data);
					$scope.temperatures=data.data;
			     				
	     			}, function errorCallback(data) {
	     			console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
	     		});
	}
	
$scope.cancel = function () {
			
			$scope.dropdowntemp={}	
			$scope.issaving=false;
			$rootScope.disablebtn=false;
    	$scope.modalInstance.dismiss('cancel');
		
 	 };
	 
	 
	 
	 $scope.issaving=false;
	$scope.savetemp=function(dropdowntemp)
	{
$scope.issaving=true;
	//console.log(dropdowntemp);
	if(angular.isDefined($scope.dropdowntemp.Id))
	{

		$http({	method:'PUT',
     		url:$rootScope.apiurl+'settingapi/droptemp/'+$scope.dropdowntemp.Id,	
     		data:{Temp:dropdowntemp.Temp}
     		}).then(function successCallback(data) {
				toaster.pop('wait', "", "Temperature successfully saved.");
				$scope.getalltemp();
				$scope.cancel();
		
     			}, function errorCallback(data) {
     				console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
     		});
	}
	else
	{	

		$http({		method:'POST',
				url:$rootScope.apiurl+'settingapi/droptemp',	
				data:dropdowntemp
				}).then(function successCallback(data) {
			
			//console.log(data);
			//	$scope.categoryid=angular.fromJson(data);
				toaster.pop('wait', "", "Temperature successfully saved.");
				$scope.getalltemp();
				$scope.cancel();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 }   			
	
	};

});


App.controller('attachcategoryController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,allextdata)
{
	'use strict';
	
	$scope.exttest={};
	$scope.externaltests=[];
	$scope.pageSize=15;
	$scope.currentPage=1;
	
	$rootScope.disablebtn=false;
	
	if(angular.isDefined(allextdata.data))
	{
		$scope.externaltests=allextdata.data;
		
	}
	
	
	
		$scope.addtestname=function(param)
	{
		
	 	if(param==='new')
		{
			$rootScope.editflag=false;
			$rootScope.disablebtn=true;
			$scope.exttest={}				
		}
		else
		{
			$rootScope.editflag=true;
			$scope.exttest={Id:param.Id,Name:param.Name,Keyword:param.Keyword}
				
		}	
				


	$scope.modalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
		templateUrl: 'AttcategoryModal',
     	scope:$scope,
     	
   	 });
	}

	$scope.deletetest=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.TestName=id.TestName;
			$scope.modalInstance2 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'delextModal',
				scope:$scope,
     	
				});
		}
		else if(con=='delete')
		{
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'settingapi/attcatdel/'+id,
		        
		   			}).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getallext();
					$scope.closeconfirmModal();
		        		}, function errorCallback(data) {
		        		console.log(data);
						toaster.pop({toasterId:11,type:"error",body:data.data});
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
 		$scope.modalInstance2.dismiss('cancel');
 	}

	$scope.getallext=function()
	{
			$http({	
					method:'GET',
					url:$rootScope.apiurl+'settingapi/attachcategory',	
	     	 
	     			}).then(function successCallback(data) {
					//console.log(data);
					$scope.externaltests=data.data;
			     				
	     			}, function errorCallback(data) {
	     			console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
	     		});
	}
	
$scope.cancel = function () {
			
			$scope.exttest={}	
			$scope.issaving=false;
			$rootScope.disablebtn=false;
    	$scope.modalInstance.dismiss('cancel');
		
 	 };
	 
	 
	 $scope.issaving=false;
	
	$scope.saveexttest=function(exttest)
	{
	$scope.issaving=true;
	
	if(angular.isDefined($scope.exttest.Id))
	{

		$http({	method:'PUT',
     		url:$rootScope.apiurl+'settingapi/attachcategory/'+$scope.exttest.Id,	
     		data:{Name:exttest.Name,Keyword:exttest.Keyword}
     			}).then(function successCallback(data) {
				toaster.pop('wait', "", "Category updated successfully.");
				$scope.getallext();
				$scope.cancel();
		
     			}, function errorCallback(data) {
     				console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
     		});
	}
	else
	{	

		$http({		method:'POST',
				url:$rootScope.apiurl+'settingapi/attachcategory',	
				data:exttest
				}).then(function successCallback(data) {
			//console.log(data);
			
				toaster.pop('wait', "", "Category added successfully.");
				$scope.getallext();
				$scope.cancel();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 }   			
	
	};

});

App.controller('externaltestController',function($scope,$http,$localstorage,toaster,$uibModal,$window,$location,$filter,
$rootScope,$state,AuthenticationService,$timeout,allextdata)
{
	'use strict';
	
	$scope.exttest={};
	$scope.externaltests=[];
	$scope.pageSize=15;
	$scope.currentPage=1;
	
	$rootScope.disablebtn=false;
	
	if(angular.isDefined(allextdata.data))
	{
		$scope.externaltests=allextdata.data;
		
	}
	
	
	
		$scope.addtestname=function(param)
	{
		
	 	if(param==='new')
		{
			$rootScope.editflag=false;
			$rootScope.disablebtn=true;
			$scope.exttest={}				
		}
		else
		{
			$rootScope.editflag=true;
			$scope.exttest={Id:param.Id,TestName:param.TestName,Keyword:param.Keyword}
				
		}	
				


	$scope.modalInstance = $uibModal.open({
		keyboard:false,
		backdrop:"static",
		templateUrl: 'ExternalModal',
     	scope:$scope,
     	
   	 });
	}

	$scope.deletetest=function(con,id)
	{

		if(con=='confirm')
		{
			$scope.confirmModal = true;
			$scope.did=id.Id;
			$scope.TestName=id.TestName;
			$scope.modalInstance2 = $uibModal.open({
				keyboard:false,
				backdrop:"static",
				templateUrl: 'delextModal',
				scope:$scope,
     	
				});
		}
		else if(con=='delete')
		{
			$http({
		    		 method:'DELETE',
		       		 url:$rootScope.apiurl+'settingapi/exttestdel/'+id,
		        
		   			}).then(function successCallback(data) {
					 
					$scope.confirmModal = false;
					$scope.getallext();
					$scope.closeconfirmModal();
		        		}, function errorCallback(data) {
		        		console.log(data);
						toaster.pop({toasterId:11,type:"error",body:data.data});
				});
		}

	}

	$scope.closeconfirmModal=function()
 	{
 		$scope.modalInstance2.dismiss('cancel');
 	}

	$scope.getallext=function()
	{
			$http({	
					method:'GET',
					url:$rootScope.apiurl+'settingapi/exttestsetting',	
	     	 
	     			}).then(function successCallback(data) {
					//console.log(data);
					$scope.externaltests=data.data;
			     				
	     			}, function errorCallback(data) {
	     			console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
	     		});
	}
	
$scope.cancel = function () {
			
			$scope.exttest={}	
			$scope.issaving=false;
			$rootScope.disablebtn=false;
    	$scope.modalInstance.dismiss('cancel');
		
 	 };
	 
	 
	 $scope.issaving=false;
	
	$scope.saveexttest=function(exttest)
	{
	$scope.issaving=true;
	
	if(angular.isDefined($scope.exttest.Id))
	{

		$http({	method:'PUT',
     		url:$rootScope.apiurl+'settingapi/exttestsetting/'+$scope.exttest.Id,	
     		data:{TestName:exttest.TestName,Keyword:exttest.Keyword}
     			}).then(function successCallback(data) {
				toaster.pop('wait', "", "Test updated successfully.");
				$scope.getallext();
				$scope.cancel();
		
     			}, function errorCallback(data) {
     				console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
     		});
	}
	else
	{	

		$http({		method:'POST',
				url:$rootScope.apiurl+'settingapi/exttestsetting',	
				data:exttest
				}).then(function successCallback(data) {
			
			//console.log(data);
			
				toaster.pop('wait', "", "Test added successfully.");
				$scope.getallext();
				$scope.cancel();
				}, function errorCallback(data) {
					console.log(data);
					toaster.pop({toasterId:11,type:"error",body:data.data});
			});
	 }   			
	
	};

});


App.controller('MailSettingsCntrl', function($scope,$http,$location,$rootScope,$localstorage,$state,toaster,$interval,
$window,$uibModal,AuthenticationService,configparam,alldata) {

$scope.pageSize=5;
$scope.currentPage=1;
$scope.info={};
$scope.info=angular.copy(alldata.data);
	var exists=$localstorage.getObject(configparam.AppName);
$scope.flags={};
$scope.oneclick=false;
$scope.flags.editflag=false;

$scope.editset=function()
{
	$scope.flags.editflag=true;
}

	$scope.saveset=function(info)
	{
		$scope.oneclick=true;
		$scope.flags.editflag=false;
		//console.log(order);
		$http({	
					method:'PUT',
					url:'admin/settingapi/updatemailset/'+exists.uid,
					data:info	     	 
	     		}).then(function successCallback(data) {
					console.log(data);
					$scope.info=data.data;
					$scope.oneclick=false;
					$scope.flags.editflag=false;
						toaster.pop({toasterId:11,type:"success",body:"successfully updated"});
					//toaster.pop('success', "", "Settings successfully updated.");
	     		}, function errorCallback(data) {
	     			console.log(data);
					$scope.oneclick=false;
					$scope.flags.editflag=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
					
	     		});
	}
	
	
	$scope.sendtestmail=function(info)
	{
		$scope.oneclick=true;
		$http({	
					method:'PUT',
					url:'admin/settingapi/sendtestmail/'+exists.uid,
					data:info	     	 
	     		}).then(function successCallback(data) {
					console.log(data.data.msg);
					$scope.oneclick=false;
		toaster.pop({toasterId:11,type:"success",body:"Mail sent"});
					//toaster.pop('success', "", "Mail Send");
	     		}, function errorCallback(data) {
	     			console.log(data);
					$scope.oneclick=false;
					toaster.pop({toasterId:11,type:"error",body:data.data});
					
					
	     		});
	}
	

});


