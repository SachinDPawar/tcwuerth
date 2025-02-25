App.config(['$translateProvider', function ($translateProvider) {
  
  $translateProvider.translations('en', {	
	"Username":"Username",
	"Password":"Password",
	
	"DASHBOARD":"Dashboard",
    "ACCOUNTS": "Accounts",
	"Quotations": "Quotations",
	
	
    "SAMPLES": "Samples",
	"ADDSAMPLE":"New Sample",
	"LOADQUOTE":"Load Quotation",
	"MDSTDS":"Mds/Tds",
	"MDS":"Mds",
	"LabNo":"LabNo",
	"SampleCode":"Sample Code",
	
	"CUSTOMER":"Customer ",
	"SampleDetails":"Sample Details",
	"RefPO/PODate":" Ref PO / PO Date",
	"INDUSTRY":"Industry",
	"Tests":"Tests",
	
	"SampleInfo":" Sample  Information",
	
	
	"Actions":"Actions",
	"Reset": "Reset",
	"NewQuote":"New Quotation",
	
	"Industry":"Industry",
   "Industries":"Industries",
	"AddIndustry":"Add Industry",
	"SubIndustries":"Sub Industries",
	"SubIndustry":"Sub Industry",
	"MandatoryMsg":"All * mark feilds are mandatory",
	
	"Accreditation":"Regulation body",
});
 
  $translateProvider.preferredLanguage('eh');
   $translateProvider.useSanitizeValueStrategy('escapeParameters');
}]);