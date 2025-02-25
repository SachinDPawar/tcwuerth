<?php

class MyTestUI{
	
	public static function getshearui($rtds,$pdf)
{
	
}
public static function gethetui($rtds,$pdf)
{
	
}

	public static function getdefaultui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="2" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	$testname=strtoupper($rtds->TestName)." REPORT";
	$trn='RFI/'.$rtds->TUID.'/'.$rtds->LabNo.'-'.$rtds->TNo;
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
		
							
						
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		<tr>
		 <td colspan="6"><b>Material /Grade :</b> '.$rtds->MaterialGrade.'</td>
         <td colspan="6"></td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr><td  colspan="12" class="text-center"  style="padding:2px; vertical-align: middle;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="150" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
		
	//----Observations----//		
	$html .= '<tr style="font-size:9pt;"><td colspan="12"><strong>Observations</strong></td></tr>';
 
		
		$groupedObservations = [];
foreach ($rtds->observations as $observation) {
    $groupedObservations[$observation->CatName][] = $observation;
}
		 foreach ($groupedObservations as $category => $observations)
		 {	
if($category)		
{	
    $html .= '    <tr style="font-size:9pt;">
            <td colspan="12"><strong>'.$category.'</strong></td>
        </tr>';
}

     $html .= '    <tr style="font-size:9pt;">
            <td colspan="1">S/n</td>
            <td colspan="5">Parameters</td>
            <td colspan="3">Specification Req.</td>
            <td colspan="3"class="text-center">Results</td>
        </tr>';
				 foreach ($observations as $index => $chem)
				 { 
					 $html .= '<tr style="font-size:9pt;">
								<td colspan="1">'.($index + 1).'</td>
								<td colspan="5">'.$chem->Param . ' ' . $chem->PUnit.'</td>
								<td colspan="3">  '.$chem->SpecMin;
									
									if (!empty($chem->SpecMin))
									{
							   $html .= '  <span>Min</span>';
									}
								  if (!empty($chem->SpecMax))
								  {
							   $html .= ' <span> - '.$chem->SpecMax.' Max</span>';
								  }
									$html .= '</td><td colspan="2" class="p-0">';
											foreach ($chem->Values as $value)
											{
												$html .= $value->Value;
											}
							$html .= '</td></tr>';
		 }
		 }
       

//----Observations----//		

				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="2" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
						
				$html.=$testname.'</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
	
		 $html.='
			
		<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		<tr>
		 <td colspan="6"><b>Material /Grade :</b> '.$rtds->MaterialGrade.'</td>
         <td colspan="6"></td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr style="text-align:center;"><td  colspan="12" class="text-center"  style=" vertical-align: middle;padding:2px;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="150" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
//----Observations----//		
	$html .= '<tr style="font-size:9pt;"><td colspan="12"><strong>Observations</strong></td></tr>';
 
		
		$groupedObservations = [];
foreach ($rtds->observations as $observation) {
    $groupedObservations[$observation->CatName][] = $observation;
}
		 foreach ($groupedObservations as $category => $observations)
		 {	
if($category)		
{	
    $html .= '    <tr style="font-size:9pt;">
            <td colspan="12"><strong>'.$category.'</strong></td>
        </tr>';
}

     $html .= '    <tr style="font-size:9pt;">
            <td colspan="1">S/n</td>
            <td colspan="5">Parameters</td>
            <td colspan="3">Specification Req.</td>
            <td colspan="3"class="text-center">Results</td>
        </tr>';
				 foreach ($observations as $index => $chem)
				 { 
					 $html .= '<tr style="font-size:9pt;">
								<td colspan="1">'.($index + 1).'</td>
								<td colspan="5">'.$chem->Param . ' ' . $chem->PUnit.'</td>
								<td colspan="3">  '.$chem->SpecMin;
									
									if (!empty($chem->SpecMin))
									{
							   $html .= '  <span>Min</span>';
									}
								  if (!empty($chem->SpecMax))
								  {
							   $html .= ' <span> - '.$chem->SpecMax.' Max</span>';
								  }
									$html .= '</td><td colspan="2" class="p-0">';
											foreach ($chem->Values as $value)
											{
												$html .= $value->Value;
											}
							$html .= '</td></tr>';
		 }
		 }
       

//----Observations----//		
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
       
//---FOOTER Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>';

return  $html;


}			

public static function getbendui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="2" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	$testname="BEND TEST REPORT";
	$trn='RFI/BTR/'.$rtds->LabNo.'-'.$rtds->TNo;
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
		
							
						
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		<tr>
		 <td colspan="6"><b>Material /Grade :</b> '.$rtds->MaterialGrade.'</td>
         <td colspan="6"></td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr><td  colspan="12" class="text-center"  style="padding:2px; vertical-align: middle;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="150" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
		
	//----Observations----//		
	$html .= '<tr style="font-size:9pt;"><td colspan="12"><strong>Observations</strong></td></tr>';
 
		
		$groupedObservations = [];
foreach ($rtds->observations as $observation) {
    $groupedObservations[$observation->CatName][] = $observation;
}
		 foreach ($groupedObservations as $category => $observations)
		 {	
if($category)		
{	
    $html .= '    <tr style="font-size:9pt;">
            <td colspan="12"><strong>'.$category.'</strong></td>
        </tr>';
}

     $html .= '    <tr style="font-size:9pt;">
            <td colspan="1">S/n</td>
            <td colspan="5">Parameters</td>
            <td colspan="3">Specification Req.</td>
            <td colspan="3"class="text-center">Results</td>
        </tr>';
				 foreach ($observations as $index => $chem)
				 { 
					 $html .= '<tr style="font-size:9pt;">
								<td colspan="1">'.($index + 1).'</td>
								<td colspan="5">'.$chem->Param . ' ' . $chem->PUnit.'</td>
								<td colspan="3">  '.$chem->SpecMin;
									
									if (!empty($chem->SpecMin))
									{
							   $html .= '  <span>Min</span>';
									}
								  if (!empty($chem->SpecMax))
								  {
							   $html .= ' <span> - '.$chem->SpecMax.' Max</span>';
								  }
									$html .= '</td><td colspan="2" class="p-0">';
											foreach ($chem->Values as $value)
											{
												$html .= $value->Value;
											}
							$html .= '</td></tr>';
		 }
		 }
       

//----Observations----//		

				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="2" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
						
				$html.=$testname.'</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
	
		 $html.='
			
		<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		<tr>
		 <td colspan="6"><b>Material /Grade :</b> '.$rtds->MaterialGrade.'</td>
         <td colspan="6"></td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr style="text-align:center;"><td  colspan="12" class="text-center"  style=" vertical-align: middle;padding:2px;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="150" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
//----Observations----//		
	$html .= '<tr style="font-size:9pt;"><td colspan="12"><strong>Observations</strong></td></tr>';
 
		
		$groupedObservations = [];
foreach ($rtds->observations as $observation) {
    $groupedObservations[$observation->CatName][] = $observation;
}
		 foreach ($groupedObservations as $category => $observations)
		 {	
if($category)		
{	
    $html .= '    <tr style="font-size:9pt;">
            <td colspan="12"><strong>'.$category.'</strong></td>
        </tr>';
}

     $html .= '    <tr style="font-size:9pt;">
            <td colspan="1">S/n</td>
            <td colspan="5">Parameters</td>
            <td colspan="3">Specification Req.</td>
            <td colspan="3"class="text-center">Results</td>
        </tr>';
				 foreach ($observations as $index => $chem)
				 { 
					 $html .= '<tr style="font-size:9pt;">
								<td colspan="1">'.($index + 1).'</td>
								<td colspan="5">'.$chem->Param . ' ' . $chem->PUnit.'</td>
								<td colspan="3">  '.$chem->SpecMin;
									
									if (!empty($chem->SpecMin))
									{
							   $html .= '  <span>Min</span>';
									}
								  if (!empty($chem->SpecMax))
								  {
							   $html .= ' <span> - '.$chem->SpecMax.' Max</span>';
								  }
									$html .= '</td><td colspan="2" class="p-0">';
											foreach ($chem->Values as $value)
											{
												$html .= $value->Value;
											}
							$html .= '</td></tr>';
		 }
		 }
       

//----Observations----//		
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
       
//---FOOTER Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>';

return  $html;


}			



	public static function getmicroetchui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="2" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	$testname="MACRO ETCH TEST REPORT";
	$trn='RFI/MET/'.$rtds->LabNo.'-'.$rtds->TNo;
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
		
							
						
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		<tr>
		 <td colspan="6"><b>Material /Grade :</b> '.$rtds->MaterialGrade.'</td>
         <td colspan="6"></td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr><td  colspan="12" class="text-center"  style="padding:2px; vertical-align: middle;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="150" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
		
	//----Observations----//		
	$html .= '<tr style="font-size:9pt;"><td colspan="12"><strong>Observations</strong></td></tr>';
 
		
		$groupedObservations = [];
foreach ($rtds->observations as $observation) {
    $groupedObservations[$observation->CatName][] = $observation;
}
		 foreach ($groupedObservations as $category => $observations)
		 {	
if($category)		
{	
    $html .= '    <tr style="font-size:9pt;">
            <td colspan="12"><strong>'.$category.'</strong></td>
        </tr>';
}

     $html .= '    <tr style="font-size:9pt;">
            <td colspan="1">S/n</td>
            <td colspan="5">Parameters</td>
            <td colspan="3">Specification Req.</td>
            <td colspan="3"class="text-center">Results</td>
        </tr>';
				 foreach ($observations as $index => $chem)
				 { 
					 $html .= '<tr style="font-size:9pt;">
								<td colspan="1">'.($index + 1).'</td>
								<td colspan="5">'.$chem->Param . ' ' . $chem->PUnit.'</td>
								<td colspan="3">  '.$chem->SpecMin;
									
									if (!empty($chem->SpecMin))
									{
							   $html .= '  <span>Min</span>';
									}
								  if (!empty($chem->SpecMax))
								  {
							   $html .= ' <span> - '.$chem->SpecMax.' Max</span>';
								  }
									$html .= '</td><td colspan="2" class="p-0">';
											foreach ($chem->Values as $value)
											{
												$html .= $value->Value;
											}
							$html .= '</td></tr>';
		 }
		 }
       

//----Observations----//		

				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="2" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
						
				$html.=$testname.'</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
	
		 $html.='
			
		<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		<tr>
		 <td colspan="6"><b>Material /Grade :</b> '.$rtds->MaterialGrade.'</td>
         <td colspan="6"></td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr style="text-align:center;"><td  colspan="12" class="text-center"  style=" vertical-align: middle;padding:2px;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="150" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
//----Observations----//		
	$html .= '<tr style="font-size:9pt;"><td colspan="12"><strong>Observations</strong></td></tr>';
 
		
		$groupedObservations = [];
foreach ($rtds->observations as $observation) {
    $groupedObservations[$observation->CatName][] = $observation;
}
		 foreach ($groupedObservations as $category => $observations)
		 {	
if($category)		
{	
    $html .= '    <tr style="font-size:9pt;">
            <td colspan="12"><strong>'.$category.'</strong></td>
        </tr>';
}

     $html .= '    <tr style="font-size:9pt;">
            <td colspan="1">S/n</td>
            <td colspan="5">Parameters</td>
            <td colspan="3">Specification Req.</td>
            <td colspan="3"class="text-center">Results</td>
        </tr>';
				 foreach ($observations as $index => $chem)
				 { 
					 $html .= '<tr style="font-size:9pt;">
								<td colspan="1">'.($index + 1).'</td>
								<td colspan="5">'.$chem->Param . ' ' . $chem->PUnit.'</td>
								<td colspan="3">  '.$chem->SpecMin;
									
									if (!empty($chem->SpecMin))
									{
							   $html .= '  <span>Min</span>';
									}
								  if (!empty($chem->SpecMax))
								  {
							   $html .= ' <span> - '.$chem->SpecMax.' Max</span>';
								  }
									$html .= '</td><td colspan="2" class="p-0">';
											foreach ($chem->Values as $value)
											{
												$html .= $value->Value;
											}
							$html .= '</td></tr>';
		 }
		 }
       

//----Observations----//		
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
       
//---FOOTER Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>';

return  $html;


}			



	public static function getfullboltui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	
	$testname="FULL BOLT TENSILE TEST REPORT";
	$trn='RFI/FTR/'.$rtds->LabNo.'-'.$rtds->TNo;
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
				$html.=$testname.'					
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
		
							
						
		
        $html.='<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);

 //----Observations----//		
 
 $rowspan = count($rtds->observations[0]->Values) + 1;
 
     $html .= '     <tr>
            <td colspan="2" rowspan="'.$rowspan.'" class="text-center"><b>Observation : </b></td>
            <td colspan="1" class="text-center"><b>S.No</b></td>
            <td colspan="3"><b>Required Ultimate tensile load(KN)</b></td>
            <td colspan="3"><b>Nominal Stres Area mm2</b></td>
            <td colspan="3"><b>Observed Ultimate Tensile Load(Mpa)</b></td>
        </tr>';
		
		 
		foreach ($rtds->observations[0]->Values as $index => $value)
		{
			 $html .= '<tr style="text-align:center;">
			 <td colspan="1" class="text-center"><b>'.($index + 1).'</b></td>';
				 foreach ($rtds->observations as $index2 => $obs)
				 { 
					 $html .= '   <td colspan="3"><b>'.($obs->Values[$index]->Value).'</b></td>';
				 }
				$html .= '</tr>';					
									
				
		 }

//---bottom basic Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
						
					$html.=$testname.'
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
	
							
						
		
        $html.='<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);

 //----Observations----//		
 
$rowspan = count($rtds->observations[0]->Values) + 1;
 
     $html .= '     <tr>
            <td colspan="2" rowspan="'.$rowspan.'" class="text-center"><b>Observation : </b></td>
            <td colspan="1" class="text-center"><b>S.No</b></td>
            <td colspan="3"><b>Required Ultimate tensile load (KN)</b></td>
            <td colspan="3"><b>Nominal Stres Area mm2</b></td>
            <td colspan="3"><b>Observed Ultimate Tensile Load(KN)</b></td>
        </tr>';
		
		 
		foreach ($rtds->observations[0]->Values as $index => $value)
		{
			 $html .= '<tr style="text-align:center;">
			 <td colspan="1" class="text-center"><b>'.($index + 1).'</b></td>';
				 foreach ($rtds->observations as $index2 => $obs)
				 { 
					 $html .= '   <td colspan="3"><b>'.($obs->Values[$index]->Value).'</b></td>';
				 }
				$html .= '</tr>';					
									
				
		 }

//---top basic Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>';

return  $html;


}			

	
	public static function getwedgeui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	
	$testname="WEDGE TEST REPORT";
	$trn='RFI/WTR/'.$rtds->LabNo.'-'.$rtds->TNo;
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
				$html.=$testname.'					
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
		
							
						
		
        $html.='<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);

 //----Observations----//		
 
 $rowspan = count($rtds->observations[0]->Values) + 1;
 
       $html .= '     <tr>
            <td colspan="2" rowspan="'.$rowspan.'" class="text-center"><b>Observation : </b></td>
            <td colspan="1" class="text-center"><b>S.No</b></td>
            <td colspan="3"><b>Required Wedge load (KN)</b></td>
            <td colspan="3"><b>Observed  Wedge load (KN)</b></td>
            <td colspan="3"><b>observed Ultimate Tensile Strength (Mpa) under wedge load</b></td>
        </tr>';
		
		 
		foreach ($rtds->observations[0]->Values as $index => $value)
		{
			 $html .= '<tr style="text-align:center;">
			 <td colspan="1" class="text-center"><b>'.($index + 1).'</b></td>';
			  $html .= '<td colspan="3" class="text-center"><b>'.$rtds->observations[0]->SpecMin.'</b></td>';
				 foreach ($rtds->observations as $index2 => $obs)
				 { 
					 $html .= '   <td colspan="3"><b>'.($obs->Values[$index]->Value).'</b></td>';
				 }
				$html .= '</tr>';					
									
				
		 }

//---bottom basic Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
						
					$html.=$testname.'
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
	
							
						
		
        $html.='<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);

 //----Observations----//		
 
$rowspan = count($rtds->observations[0]->Values) + 1;
 
     $html .= '     <tr>
            <td colspan="2" rowspan="'.$rowspan.'" class="text-center"><b>Observation : </b></td>
            <td colspan="1" class="text-center"><b>S.No</b></td>
            <td colspan="3"><b>Required Wedge load (KN)</b></td>
            <td colspan="3"><b>Observed  Wedge load (KN)</b></td>
            <td colspan="3"><b>observed Ultimate Tensile Strength (Mpa) under wedge load</b></td>
        </tr>';
		
		 
		foreach ($rtds->observations[0]->Values as $index => $value)
		{
			 $html .= '<tr style="text-align:center;">
			 <td colspan="1" class="text-center"><b>'.($index + 1).'</b></td>';
			  $html .= '<td colspan="3" class="text-center"><b>'.$rtds->observations[0]->SpecMin.'</b></td>';
				 foreach ($rtds->observations as $index2 => $obs)
				 { 
					 $html .= '   <td colspan="3"><b>'.($obs->Values[$index]->Value).'</b></td>';
				 }
				$html .= '</tr>';					
									
				
		 }

//---top basic Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>';

return  $html;


}			

	
public static function getirkui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	$testname="INCLUSION RATING BY \"K\" METHOD";
	$trn='RFI/MCH-IR/'.$rtds->LabNo.'-'.$rtds->TNo;
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';		
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	

	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		
		
	$html.='	<tr>
    <td colspan="12" class="text-center" style="padding: 0;">
       ';
            
            // Assuming $info['Imgs'] is an array of image data
           
                foreach ($rtds->Imgs as $i)
				{
                  
               $html.='    
                            <img src="'.htmlspecialchars($i->url).'" height="140" width="140" alt="'.htmlspecialchars($i->name).'"/>';
                            // <br>Location: "'. htmlspecialchars($i['description']).'"';
				}
      $html.='  
    </td>
</tr>';
		
		
		
	}
		
		
		
//----Observations----//	
// $html .= '<tr>
					// <td colspan="6" style="padding:2px;"><b>Observations: </b> </td>
					// <td colspan="6" style="padding:2px;"><b>ORIGINAL MAGNIFICATOINS - :</b> '.$rtds->tobbasic->Magnification->BValue.'</td>
				// </tr>';
				
		$html .= '<tr>
    <td colspan="12" style="padding:0px;">
	
        <table class="table table-bordered" style="margin-bottom:0px;margin:0px;padding:0px;">
           <tr class="">
					<td rowspan="2" colspan="1"  class="text-center " style="max-width:50px;" >Specif. No. </td>
					<td rowspan="2" colspan="1"  class="text-center" style="max-width:100px;" >Type of Inclusion in Diagram Plate No 1 </td>
					<td rowspan="2" colspan="1"  class="text-center " style="max-width:100px;" >Area Polished section evalucated in mm2 </td>
					<td colspan="9" class="text-center ">No of Inclusion satisfied by rating No </td>
					<td colspan="2"  class="text-center " style="max-width:120px;" >Multifiaction & List </td>	
										
				</tr>
				
				<tr class="text-center "  >
				
				
				
				<td  colspan="9" style="padding:0px;">
				<table class="table table-bordered" style="margin-bottom:0px;table-layout: fixed;    ">
				<tr class="">
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">0</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">1</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">2</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">3</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">4</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">5</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">6</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">7</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">8</td>
				</tr>
				
				<tr class="">
				<td colspan="9">Factor</td>
				</tr>
				
				<tr class="">
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">0.05</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">0.1</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">0.2</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">0.5</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">1</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">2</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">5</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">10</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">20</td>
				</tr>
				</table>
				</td>
				<td style="max-width:50px;min-width:50px;height:35px;padding:4px;" colspan="1">S*)</td>
				<td style="max-width:50px;min-width:50px;height:35px;padding:4px;" colspan="1">O*)</td>
				</tr>
				
    ';

$html .= '<tbody>';

if (empty($rtds->observations['Obs'])) {
    $html .= '<tr><td colspan="15" class="text-center">No results</td></tr>';
} else {
	$vidx=0;
    foreach ($rtds->observations['Obs']->Values as $item) {
		$vidx++;
        $html .= '<tr>
            <td colspan="1" style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (int)($vidx ) . '</td>
            <td colspan="1" style="padding:0px;">
                <table class="table table-bordered" style="margin-bottom:0px;">
                    ';
                    foreach ($item->obs as $kv) {
                        $html .= '<tr><td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . htmlspecialchars($kv->TypeOfInc) . '</td></tr>';
                    }
        $html .= '
                </table>
            </td>
            <td colspan="1" style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($item->AreaPol) ? htmlspecialchars($item->AreaPol) : ' ') . '</td>
            <td colspan="9" style="padding:0px;">
                <table class="table table-bordered" style="margin-bottom:0px;table-layout: fixed;">
                    ';
                    foreach ($item->obs as $obs) {
						$html .='<tr>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo0) ? htmlspecialchars($obs->RatNo0) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo1) ? htmlspecialchars($obs->RatNo1) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo2) ? htmlspecialchars($obs->RatNo2) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo3) ? htmlspecialchars($obs->RatNo3) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo4) ? htmlspecialchars($obs->RatNo4) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo5) ? htmlspecialchars($obs->RatNo5) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo6) ? htmlspecialchars($obs->RatNo6) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo7) ? htmlspecialchars($obs->RatNo7) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo8) ? htmlspecialchars($obs->RatNo8) : ' ') . '</td>';
						$html .='</tr>';
                    }
        $html .= '
                </table>
            </td>
            <td colspan="2" style="padding:0px;">
                <table class="table table-bordered" style="margin-bottom:0px;">
                   ';
                    foreach ($item->obs as $obs) {
                        $html .= ' <tr><td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->SStar) ? htmlspecialchars($obs->SStar) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->OStar) ? htmlspecialchars($obs->OStar) : ' ') . '</td></tr>';
                    }
        $html .= '
                </table>
            </td>
        </tr>';
    }

    // Total Row
    $html .= '<tr>
        <td colspan="2" class="text-right">Total:</td>
        <td>' . (isset($rtds->observations['Total']->Values[0]->Value) ? htmlspecialchars($rtds->observations['Total']->Values[0]->Value) : '0') . '</td>
        <td colspan="9" class="text-right">Second Sub Total:</td>
        <td>' . (isset($rtds->observations['SSTotalS']->Values[0]->Value) ? htmlspecialchars($rtds->observations['SSTotalS']->Values[0]->Value) : '0') . '</td>
        <td>' . (isset($rtds->observations['SSTotalO']->Values[0]->Value) ? htmlspecialchars($rtds->observations['SSTotalO']->Values[0]->Value) : '0') . '</td>
    </tr>';

    // Index K3 Row
    $html .= '<tr>
        <td colspan="3"></td>
        <td colspan="9" class="text-right">Total Index K3:</td>
        <td>' . (isset($rtds->observations['TotalK3S']->Values[0]->Value) ? htmlspecialchars($rtds->observations['TotalK3S']->Values[0]->Value) : '0') . '</td>
        <td>' . (isset($rtds->observations['TotalK3O']->Values[0]->Value) ? htmlspecialchars($rtds->observations['TotalK3O']->Values[0]->Value) : '0') . '</td>
    </tr>';

    // Overall total Index K3 Row
    $html .= '<tr>
        <td colspan="3"></td>
        <td colspan="9" class="text-right">Overall total Index K3:</td>
        <td colspan="2">' . (isset($rtds->observations['OverAllTotK3']->Values[0]->Value) ? htmlspecialchars($rtds->observations['OverAllTotK3']->Values[0]->Value) : '0') . '</td>
    </tr>';
}

$html .= '</tbody></table></td></tr>';





	



				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';		
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		
		
	$html.='	<tr>
    <td colspan="12" class="text-center" style="padding: 0;">
      ';
            
            // Assuming $info['Imgs'] is an array of image data
           
                foreach ($rtds->Imgs as $i)
				{
                  
                $html.='    
                            <img src="'.htmlspecialchars($i->url).'" height="140" width="140" alt="'.htmlspecialchars($i->name).'"/>';
                            
				}
      $html.='  
    </td>
</tr>';
		
		
		
	}
		
		
		
//----Observations----//	

		

	$html .= '<tr>
    <td colspan="12" style="padding:0px;">
	
        <table class="table table-bordered" style="margin-bottom:0px;">
           <tr class="">
					<td rowspan="2" colspan="1"  class="text-center " style="max-width:50px;" >Specif. No. </td>
					<td rowspan="2" colspan="1"  class="text-center" style="max-width:100px;" >Type of Inclusion in Diagram Plate No 1 </td>
					<td rowspan="2" colspan="1"  class="text-center " style="max-width:100px;" >Area Polished section evalucated in mm2 </td>
					<td colspan="9" class="text-center ">No of Inclusion satisfied by rating No </td>
					<td colspan="2"  class="text-center " style="max-width:120px;" >Multifiaction & List </td>	
										
				</tr>
				
				<tr class="text-center "  >
				
				
				
				<td  colspan="9" style="padding:0px;">
				<table class="table table-bordered" style="margin-bottom:0px;table-layout: fixed;    ">
				<tr class="">
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">0</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">1</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">2</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">3</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">4</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">5</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">6</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">7</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">8</td>
				</tr>
				
				<tr class="">
				<td colspan="9">Factor</td>
				</tr>
				
				<tr class="">
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">0.05</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">0.1</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">0.2</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">0.5</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">1</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">2</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">5</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">10</td>
				<td style="max-width:50px;min-width:50px;height:30px;padding:2px;">20</td>
				</tr>
				</table>
				</td>
				<td style="max-width:50px;min-width:50px;height:35px;padding:4px;" colspan="1">S*)</td>
				<td style="max-width:50px;min-width:50px;height:35px;padding:4px;" colspan="1">O*)</td>
				</tr>
				
    ';

$html .= '<tbody>';

if (empty($rtds->observations['Obs'])) {
    $html .= '<tr><td colspan="15" class="text-center">No results</td></tr>';
} else {
	$vidx=0;
    foreach ($rtds->observations['Obs']->Values as $item) {
		$vidx++;
        $html .= '<tr>
            <td colspan="1" style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (int)($vidx ) . '</td>
            <td colspan="1" style="padding:0px;">
                <table class="table table-bordered" style="margin-bottom:0px;">
                    ';
                    foreach ($item->obs as $kv) {
                        $html .= '<tr><td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . htmlspecialchars($kv->TypeOfInc) . '</td></tr>';
                    }
        $html .= '
                </table>
            </td>
            <td colspan="1" style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($item->AreaPol) ? htmlspecialchars($item->AreaPol) : ' ') . '</td>
            <td colspan="9" style="padding:0px;">
                <table class="table table-bordered" style="margin-bottom:0px;table-layout: fixed;">
                    ';
                    foreach ($item->obs as $obs) {
						$html .='<tr>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo0) ? htmlspecialchars($obs->RatNo0) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo1) ? htmlspecialchars($obs->RatNo1) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo2) ? htmlspecialchars($obs->RatNo2) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo3) ? htmlspecialchars($obs->RatNo3) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo4) ? htmlspecialchars($obs->RatNo4) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo5) ? htmlspecialchars($obs->RatNo5) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo6) ? htmlspecialchars($obs->RatNo6) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo7) ? htmlspecialchars($obs->RatNo7) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->RatNo8) ? htmlspecialchars($obs->RatNo8) : ' ') . '</td>';
						$html .='</tr>';
                    }
        $html .= '
                </table>
            </td>
            <td colspan="2" style="padding:0px;">
                <table class="table table-bordered" style="margin-bottom:0px;">
                   ';
                    foreach ($item->obs as $obs) {
                        $html .= ' <tr><td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->SStar) ? htmlspecialchars($obs->SStar) : ' ') . '</td>';
                        $html .= '<td style="max-width:50px;min-width:50px;height:35px;padding:4px;">' . (isset($obs->OStar) ? htmlspecialchars($obs->OStar) : ' ') . '</td></tr>';
                    }
        $html .= '
                </table>
            </td>
        </tr>';
    }

    // Total Row
    $html .= '<tr>
        <td colspan="2" class="text-right">Total:</td>
        <td>' . (isset($rtds->observations['Total']->Values[0]->Value) ? htmlspecialchars($rtds->observations['Total']->Values[0]->Value) : '0') . '</td>
        <td colspan="9" class="text-right">Second Sub Total:</td>
        <td>' . (isset($rtds->observations['SSTotalS']->Values[0]->Value) ? htmlspecialchars($rtds->observations['SSTotalS']->Values[0]->Value) : '0') . '</td>
        <td>' . (isset($rtds->observations['SSTotalO']->Values[0]->Value) ? htmlspecialchars($rtds->observations['SSTotalO']->Values[0]->Value) : '0') . '</td>
    </tr>';

    // Index K3 Row
    $html .= '<tr>
        <td colspan="3"></td>
        <td colspan="9" class="text-right">Total Index K3:</td>
        <td>' . (isset($rtds->observations['TotalK3S']->Values[0]->Value) ? htmlspecialchars($rtds->observations['TotalK3S']->Values[0]->Value) : '0') . '</td>
        <td>' . (isset($rtds->observations['TotalK3O']->Values[0]->Value) ? htmlspecialchars($rtds->observations['TotalK3O']->Values[0]->Value) : '0') . '</td>
    </tr>';

    // Overall total Index K3 Row
    $html .= '<tr>
        <td colspan="3"></td>
        <td colspan="9" class="text-right">Overall total Index K3:</td>
        <td colspan="2">' . (isset($rtds->observations['OverAllTotK3']->Values[0]->Value) ? htmlspecialchars($rtds->observations['OverAllTotK3']->Values[0]->Value) : '0') . '</td>
    </tr>';
}

$html .= '</tbody></table></td></tr>';






				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='</tbody></table>';

return  $html;


}			



	public static function getirwui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	$testname="WORST FIELD INCLUSION RATING (METHOD A)";
	$trn='RFI/MCH-IR/'.$rtds->LabNo.'-'.$rtds->TNo;
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';		
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	$html.='<tr>
						<td colspan="12" style="text-align:center;margin-top: 5px;margin-bottom: 5px;padding:4px;">
						<b>METALLOGRAPHY OF ROLLED THREAD</b></td>
					</tr>';	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		
		
	$html.='	<tr>
    <td colspan="12" class="text-center" style="padding: 0;">
       ';
            
            // Assuming $info['Imgs'] is an array of image data
           
                foreach ($rtds->Imgs as $i)
				{
                  
               $html.='    
                            <img src="'.htmlspecialchars($i->url).'" height="140" width="140" alt="'.htmlspecialchars($i->name).'"/>';
                            // <br>Location: "'. htmlspecialchars($i['description']).'"';
				}
      $html.='  
    </td>
</tr>';
		
		
		
	}
		
		
		
//----Observations----//	
// $html .= '<tr>
					// <td colspan="6" style="padding:2px;"><b>Observations: </b> </td>
					// <td colspan="6" style="padding:2px;"><b>ORIGINAL MAGNIFICATOINS - :</b> '.$rtds->tobbasic->Magnification->BValue.'</td>
				// </tr>';
				
				

$html .= '<tr>
    <td colspan="12" style="padding:0;">
        <table class="table table-bordered" style="margin-bottom:0px;">
            <thead>
                <tr>
                    <td class="text-center" colspan="1" rowspan="2">No. of Samples/Fields</td>
                    <td class="text-center" colspan="2">Type-A</td>
                    <td class="text-center" colspan="2">Type-B</td>
                    <td class="text-center" colspan="2">Type-C</td>
                    <td class="text-center" colspan="2">Type-D</td>
                </tr>
                <tr>
                    <td class="text-center">Thin</td>
                    <td class="text-center">Thick</td>
                    <td class="text-center">Thin</td>
                    <td class="text-center">Thick</td>
                    <td class="text-center">Thin</td>
                    <td class="text-center">Thick</td>
                    <td class="text-center">Thin</td>
                    <td class="text-center">Thick</td>
                </tr>
            </thead>
            <tbody>';

            // Check if $info->observations is not empty
            if (!empty($rtds->observations)) {
                // Loop through each item in the observations array
                foreach ($rtds->observations as $index => $item) {
                    $html .= '<tr class="text-center">
                        <td>';
                        
                        // If it's the last item, display "Average"
                        if ($index == count($rtds->observations) - 1) {
                            $html .= '<b>Average</b>';
                        }

                    $html .= '</td>
                        <td>' . htmlspecialchars($item->ThinA) . '</td>
                        <td>' . htmlspecialchars($item->ThickA) . '</td>
                        <td>' . htmlspecialchars($item->ThinB) . '</td>
                        <td>' . htmlspecialchars($item->ThickB) . '</td>
                        <td>' . htmlspecialchars($item->ThinC) . '</td>
                        <td>' . htmlspecialchars($item->ThickC) . '</td>
                        <td>' . htmlspecialchars($item->ThinD) . '</td>
                        <td>' . htmlspecialchars($item->ThickD) . '</td>
                    </tr>';
                }
            }

$html .= '</tbody>
        </table>
    </td>
</tr>';



	



				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';		
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		
		
	$html.='	<tr>
    <td colspan="12" class="text-center" style="padding: 0;">
      ';
            
            // Assuming $info['Imgs'] is an array of image data
           
                foreach ($rtds->Imgs as $i)
				{
                  
                $html.='    
                            <img src="'.htmlspecialchars($i->url).'" height="140" width="140" alt="'.htmlspecialchars($i->name).'"/>';
                            
				}
      $html.='  
    </td>
</tr>';
		
		
		
	}
		
		
		
//----Observations----//	


$html .= '<tr>
    <td colspan="12" style="padding:0;">
        <table class="table table-bordered" style="margin-bottom:0px;">
            <thead>
                <tr>
                    <td class="text-center" colspan="1" rowspan="2">No. of Samples/Fields</td>
                    <td class="text-center" colspan="2">Type-A</td>
                    <td class="text-center" colspan="2">Type-B</td>
                    <td class="text-center" colspan="2">Type-C</td>
                    <td class="text-center" colspan="2">Type-D</td>
                </tr>
                <tr>
                    <td class="text-center">Thin</td>
                    <td class="text-center">Thick</td>
                    <td class="text-center">Thin</td>
                    <td class="text-center">Thick</td>
                    <td class="text-center">Thin</td>
                    <td class="text-center">Thick</td>
                    <td class="text-center">Thin</td>
                    <td class="text-center">Thick</td>
                </tr>
            </thead>
            <tbody>';

            // Check if $info->observations is not empty
            if (!empty($rtds->observations)) {
                // Loop through each item in the observations array
                foreach ($rtds->observations as $index => $item) {
                    $html .= '<tr class="text-center">
                        <td>';
                        
                        // If it's the last item, display "Average"
                        if ($index == count($rtds->observations) - 1) {
                            $html .= '<b>Average</b>';
                        }

                    $html .= '</td>
                        <td>' . htmlspecialchars($item->ThinA) . '</td>
                        <td>' . htmlspecialchars($item->ThickA) . '</td>
                        <td>' . htmlspecialchars($item->ThinB) . '</td>
                        <td>' . htmlspecialchars($item->ThickB) . '</td>
                        <td>' . htmlspecialchars($item->ThinC) . '</td>
                        <td>' . htmlspecialchars($item->ThickC) . '</td>
                        <td>' . htmlspecialchars($item->ThinD) . '</td>
                        <td>' . htmlspecialchars($item->ThickD) . '</td>
                    </tr>';
                }
            }

$html .= '</tbody>
        </table>
    </td>
</tr>';



				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='</tbody></table>';

return  $html;


}			

	public static function getthreadui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	$testname="THREAD LAP TEST REPORT";
	$trn='RFI/MCH-TL/'.$rtds->LabNo.'-'.$rtds->TNo;
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';		
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	$html.='<tr>
						<td colspan="12" style="text-align:center;margin-top: 5px;margin-bottom: 5px;padding:4px;">
						<b>METALLOGRAPHY OF ROLLED THREAD</b></td>
					</tr>';	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		
		
	$html.='	<tr>
    <td colspan="12" class="text-center" style="padding: 0;">
       ';
            
            // Assuming $info['Imgs'] is an array of image data
           
                foreach ($rtds->Imgs as $i)
				{
                  
               $html.='    
                            <img src="'.htmlspecialchars($i->url).'" height="140" width="140" alt="'.htmlspecialchars($i->name).'"/>
                            <br>Location: "'. htmlspecialchars($i['description']).'" 
                       ';
				}
      $html.='  
    </td>
</tr>';
		
		
		
	}
		
		
		
//----Observations----//	


$html .= '<tr>
    <td colspan="12" style="padding:0px;">
        <b style="padding:4px;">Observations</b>';

        // Check if $rtds->observations is not empty
        if (!empty($rtds->observations)) {
            // Group by 'CatName'
            $groupedObservations = [];
            foreach ($rtds->observations as $observation) {
                $groupedObservations[$observation->CatName][] = $observation;
            }

            // Loop through grouped observations
            foreach ($groupedObservations as $catName => $tags) {
                $html .= '<table class="table table-bordered table-condensed table-sm mb-0">
                    <tr>
                        <td colspan="5"><strong>' . htmlspecialchars($catName) . '</strong></td>
                    </tr>
                    <tr>
                        <td>S/n</td>
                        <td>Parameters</td>
                        <td class="text-center">Observations</td>
                        <td>Remark</td>
                    </tr>';

                // Loop through each observation in the group
                foreach ($tags as $index => $chem) {
                    $html .= '<tr>
                        <td>' . ($index + 1) . '</td>
                        <td>' . htmlspecialchars($chem->Param) . ' ' . htmlspecialchars($chem->PUnit) . '</td>
                        <td class="p-0">
                            <table class="table table-sm mb-0 p-0 table-bordered" style="font-size:12px;font-weight:bold;">
                                <tr>';

                    // Loop through the 'Values' field to output each value
                    if (!empty($chem->Values)) {
                        foreach ($chem->Values as $value) {
                            $html .= '<td>' . htmlspecialchars($value->Value) . '</td>';
                        }
                    }

                    $html .= '</tr>
                            </table>
                        </td>
                        <td>' . htmlspecialchars($chem->SpecMin) . '</td>
                    </tr>';
                }

                $html .= '</table>';
            }
        }

$html .= '</td>
</tr>';


	



				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';		
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		
		
	$html.='	<tr>
    <td colspan="12" class="text-center" style="padding: 0;">
      ';
            
            // Assuming $info['Imgs'] is an array of image data
           
                foreach ($rtds->Imgs as $i)
				{
                  
                $html.='    
                            <img src="'.htmlspecialchars($i->url).'" height="140" width="140" alt="'.htmlspecialchars($i->name).'"/>
                            <br>Location: "'. htmlspecialchars($i['description']).'" 
                       ';
				}
      $html.='  
    </td>
</tr>';
		
		
		
	}
		
		
		
//----Observations----//	


$html .= '<tr>
    <td colspan="12" style="padding:0px;">
        <b style="padding:4px;">Observations</b>';

        // Check if $rtds->observations is not empty
        if (!empty($rtds->observations)) {
            // Group by 'CatName'
            $groupedObservations = [];
            foreach ($rtds->observations as $observation) {
                $groupedObservations[$observation->CatName][] = $observation;
            }

            // Loop through grouped observations
            foreach ($groupedObservations as $catName => $tags) {
                $html .= '<table class="table table-bordered table-condensed table-sm mb-0">
                    <tr>
                        <td colspan="5"><strong>' . htmlspecialchars($catName) . '</strong></td>
                    </tr>
                    <tr>
                        <td>S/n</td>
                        <td>Parameters</td>
                        <td class="text-center">Observations</td>
                        <td>Remark</td>
                    </tr>';

                // Loop through each observation in the group
                foreach ($tags as $index => $chem) {
                    $html .= '<tr>
                        <td>' . ($index + 1) . '</td>
                        <td>' . htmlspecialchars($chem->Param) . ' ' . htmlspecialchars($chem->PUnit) . '</td>
                        <td class="p-0">
                            <table class="table table-sm mb-0 p-0 table-bordered" style="font-size:12px;font-weight:bold;">
                                <tr>';

                    // Loop through the 'Values' field to output each value
                    if (!empty($chem->Values)) {
                        foreach ($chem->Values as $value) {
                            $html .= '<td>' . htmlspecialchars($value->Value) . '</td>';
                        }
                    }

                    $html .= '</tr>
                            </table>
                        </td>
                        <td>' . htmlspecialchars($chem->SpecMin) . '</td>
                    </tr>';
                }

                $html .= '</table>';
            }
        }

$html .= '</td>
</tr>';


				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='</tbody></table>';

return  $html;


}			

	public static function getmicrocoatui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	$testname="COATING THICKNESS MEASUREMENT TEST REPORT";
	$trn='RFI/MCH-CT/'.$rtds->LabNo.'-'.$rtds->TNo;
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';		
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr><td  colspan="12" class="text-center"  style="padding:2px; height:150pt;vertical-align: middle;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="140" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
		
		
		
//----Observations----//	
	
	$html.='<tr class="text-center">
				<td colspan="4" rowspan="7"  style="padding:2px;vertical-align:middle;text-align:center;"><b>Observations:</b></td>
					<td colspan="4"  style="padding:2px;"><b>Sr.No.</b></td>
					<td  colspan="4"  style="padding:2px;" class="text-center col-md-2"><b>Length (micron)</b></td>
								
			</tr>';
			
			$totalObservations=count($rtds->observations);

// Loop through observations
foreach ($rtds->observations as $index => $observation) {
    $html.='<tr>';

    // Check if it’s the last item
    if ($index !== $totalObservations - 1) {
        // Not the last item
        $html.='<td colspan="4" style="padding:2px;">' . ($index + 1) . '</td>';
		 $html.='<td colspan="4" style="padding:2px;">' . htmlspecialchars($observation->Value, ENT_QUOTES, 'UTF-8') . '</td>';
    } else {
        // Last item
        $html.='<td colspan="4" style="padding:2px;"> Average Coating Thickness: </td>';
		 $html.='<td colspan="4" style="padding:2px;">' .$observation->Value. '</td>';
    }

    // Output the value
  

   $html.='</tr>';
}


				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';		
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr><td  colspan="12" class="text-center"  style="padding:2px; height:150pt;vertical-align: middle;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="140" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
		
		
		
//----Observations----//	
	
	$html.='<tr class="text-center">
				<td colspan="4" rowspan="7"  style="padding:2px;vertical-align:middle;text-align:center;"><b>Observations:</b></td>
					<td colspan="4"  style="padding:2px;"><b>Sr.No.</b></td>
					<td  colspan="4"  style="padding:2px;" class="text-center col-md-2"><b>Length (micron)</b></td>
								
			</tr>';

// Loop through observations
foreach ($rtds->observations as $index => $observation) {
    $html.='<tr>';

    // Check if it’s the last item
    if ($index !== $totalObservations - 1) {
        // Not the last item
        $html.='<td colspan="4" style="padding:2px;">' . ($index + 1) . '</td>';
		 $html.='<td colspan="4" style="padding:2px;">' . htmlspecialchars($observation->Value, ENT_QUOTES, 'UTF-8') . '</td>';
    } else {
        // Last item
        $html.='<td colspan="4" style="padding:2px;">Average  Coating Thickness: </td>';
		 $html.='<td colspan="4" style="padding:2px;">' .$observation->Value. '</td>';
    }

    // Output the value
  

   $html.='</tr>';
}


				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='</tbody></table>';

return  $html;


}			

	public static function getmicrocaseui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	$testname="TOTAL CASE DEPTH TEST REPORT";
	$trn='RFI/MCH-CD/'.$rtds->LabNo.'-'.$rtds->TNo;
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';		
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr><td  colspan="12" class="text-center"  style="padding:2px; height:150pt;vertical-align: middle;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="140" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
		
		
		
//----Observations----//	
	
	$html.='<tr class="text-center">
				<td colspan="4" rowspan="7"  style="padding:2px;vertical-align:middle;text-align:center;"><b>Observations:</b></td>
					<td colspan="4"  style="padding:2px;"><b>Sr.No.</b></td>
					<td  colspan="4"  style="padding:2px;" class="text-center col-md-2"><b>Length (mm)</b></td>
								
			</tr>';
			
			$totalObservations=count($rtds->observations);

// Loop through observations
foreach ($rtds->observations as $index => $observation) {
    $html.='<tr>';

    // Check if it’s the last item
    if ($index !== $totalObservations - 1) {
        // Not the last item
        $html.='<td colspan="4" style="padding:2px;">' . ($index + 1) . '</td>';
		 $html.='<td colspan="4" style="padding:2px;">' . htmlspecialchars($observation->Value, ENT_QUOTES, 'UTF-8') . '</td>';
    } else {
        // Last item
        $html.='<td colspan="4" style="padding:2px;">Average Total Case depth: </td>';
		 $html.='<td colspan="4" style="padding:2px;">' .$observation->Value. '</td>';
    }

    // Output the value
  

   $html.='</tr>';
}


				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';		
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr><td  colspan="12" class="text-center"  style="padding:2px; height:150pt;vertical-align: middle;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="140" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
		
		
		
//----Observations----//	
	
	$html.='<tr class="text-center">
				<td colspan="4" rowspan="7"  style="padding:2px;vertical-align:middle;text-align:center;"><b>Observations:</b></td>
					<td colspan="4"  style="padding:2px;"><b>Sr.No.</b></td>
					<td  colspan="4"  style="padding:2px;" class="text-center col-md-2"><b>Length (mm)</b></td>
								
			</tr>';

// Loop through observations
foreach ($rtds->observations as $index => $observation) {
    $html.='<tr>';

    // Check if it’s the last item
    if ($index !== $totalObservations - 1) {
        // Not the last item
        $html.='<td colspan="4" style="padding:2px;">' . ($index + 1) . '</td>';
		 $html.='<td colspan="4" style="padding:2px;">' . htmlspecialchars($observation->Value, ENT_QUOTES, 'UTF-8') . '</td>';
    } else {
        // Last item
        $html.='<td colspan="4" style="padding:2px;">Average Total Case depth: </td>';
		 $html.='<td colspan="4" style="padding:2px;">' .$observation->Value. '</td>';
    }

    // Output the value
  

   $html.='</tr>';
}


				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='</tbody></table>';

return  $html;


}			


	public static function getmicrodecarbui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	$testname="MICRO DECARB TEST REPORT";
	$trn='RFI/MCH-DC/'.$rtds->LabNo.'-'.$rtds->TNo;
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';		
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr><td  colspan="12" class="text-center"  style="padding:2px; height:150pt;vertical-align: middle;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="140" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
		
		
		
//----Observations----//	
	
	$html.='<tr class="text-center">
				<td colspan="4" rowspan="7"  style="padding:2px;vertical-align:middle;text-align:center;"><b>Observations:</b></td>
					<td colspan="4"  style="padding:2px;"><b>Sr.No.</b></td>
					<td  colspan="4"  style="padding:2px;" class="text-center col-md-2"><b>Length (mm)</b></td>
								
			</tr>';
			
			$totalObservations=count($rtds->observations);

// Loop through observations
foreach ($rtds->observations as $index => $observation) {
    $html.='<tr>';

    // Check if it’s the last item
    if ($index !== $totalObservations - 1) {
        // Not the last item
        $html.='<td colspan="4" style="padding:2px;">' . ($index + 1) . '</td>';
		 $html.='<td colspan="4" style="padding:2px;">' . htmlspecialchars($observation->Value, ENT_QUOTES, 'UTF-8') . '</td>';
    } else {
        // Last item
        $html.='<td colspan="4" style="padding:2px;">Average Total Decarb depth: </td>';
		 $html.='<td colspan="4" style="padding:2px;">' .$observation->Value. '</td>';
    }

    // Output the value
  

   $html.='</tr>';
}


				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';		
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr><td  colspan="12" class="text-center"  style="padding:2px; height:150pt;vertical-align: middle;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="140" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
		
		
		
//----Observations----//	
	
	$html.='<tr class="text-center">
				<td colspan="4" rowspan="7"  style="padding:2px;vertical-align:middle;text-align:center;"><b>Observations:</b></td>
					<td colspan="4"  style="padding:2px;"><b>Sr.No.</b></td>
					<td  colspan="4"  style="padding:2px;" class="text-center col-md-2"><b>Length (mm)</b></td>
								
			</tr>';

// Loop through observations
foreach ($rtds->observations as $index => $observation) {
    $html.='<tr>';

    // Check if it’s the last item
    if ($index !== $totalObservations - 1) {
        // Not the last item
        $html.='<td colspan="4" style="padding:2px;">' . ($index + 1) . '</td>';
		 $html.='<td colspan="4" style="padding:2px;">' . htmlspecialchars($observation->Value, ENT_QUOTES, 'UTF-8') . '</td>';
    } else {
        // Last item
        $html.='<td colspan="4" style="padding:2px;">Average Total Decarb depth: </td>';
		 $html.='<td colspan="4" style="padding:2px;">' .$observation->Value. '</td>';
    }

    // Output the value
  

   $html.='</tr>';
}


				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='</tbody></table>';

return  $html;


}			

		public static function getgrainsizeui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="2" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	$testname="GRAIN SIZE MEASUREMENT TEST REPORT";
	$trn='RFI/MCH-GS/'.$rtds->LabNo.'-'.$rtds->TNo;
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
				$html.=$testname.'						
						</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
		
							
						
		
        $html.='
			<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		<tr>
		 <td colspan="6"><b>Material /Grade :</b> '.$rtds->MaterialGrade.'</td>
         <td colspan="6"></td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr><td  colspan="12" class="text-center"  style="padding:2px; vertical-align: middle;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="150" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
		
		
		// Function to generate rows for different segments
// function generateRows($items, $start, $limit) {
    // $mhtml = '';
    // $chunk = array_slice($items, $start, $limit);
    // if (count($chunk) > 0) {
        // $mhtml .= '<tr style="font-size:9pt;font-weight:bold;"><td colspan="2"><b style="font-size:8pt;">Number Of Feilds</b></td>';
        // foreach ($chunk as $index=>$item) {
            // $mhtml .= '<td><b>Field No.'.($index+1).'</b></td>';
        // }
        // $mhtml .= '</tr>';
        
        // $mhtml .= '<tr style="font-size:9pt;"><td style="font-size:8pt;" colspan="2"><b>Observations</b></td>';
        // foreach ($chunk as $item) {
            // $mhtml .= '<td>' . $item->Value . '</td>';
        // }
        // $mhtml .= '</tr>';
      
    // }
    // return $mhtml;
// }

// // Generate table content for different chunks
// $html .= generateRows($rtds->observations['Obs']->Values, 0, 5);

//----Observations----//		
	$html.='<tr class="text-center">
				<td colspan="4" rowspan="6"  style="padding:2px;"><b>Observations:</b></td>
					<td colspan="4"  style="padding:2px;"><b>Number of fields</b></td>
					<td  colspan="4"  style="padding:2px;" class="text-center col-md-2"><b>ASTM grain size no.</b></td>
								
			</tr>';
			
			foreach($rtds->observations['Obs']->Values as $index=>$value)
			{
			$html.='	<tr class="text-center"  >
					
					<td colspan="4"  style="padding:2px;">Field No.'.($index+1).'</td>
					<td colspan="4" style="padding:2px;">'.$value->Value.'</td>
				
				</tr>';
			}

				
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="2" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 2px;"><b>';
						
				$html.=$testname.'</b></h4><br><b>METALLOGRAPHY REPORT</b></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
	
		 $html.='
			
		<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		<tr>
		 <td colspan="6"><b>Material /Grade :</b> '.$rtds->MaterialGrade.'</td>
         <td colspan="6"></td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr style="text-align:center;"><td  colspan="12" class="text-center"  style=" vertical-align: middle;padding:2px;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="150" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
		
//----Observations----//		
 
 	$html.='<tr class="text-center">
				<td colspan="4" rowspan="6"  style="padding:2px;"><b>Observations:</b></td>
					<td colspan="4"  style="padding:2px;"><b>Number of fields</b></td>
					<td  colspan="4"  style="padding:2px;" class="text-center col-md-2"><b>ASTM grain size no.</b></td>
								
			</tr>';
			
			foreach($rtds->observations['Obs']->Values as $index=>$value)
			{
			$html.='	<tr class="text-center"  >
					
					<td colspan="4"  style="padding:2px;">Field No.'.($index+1).'</td>
					<td colspan="4" style="padding:2px;">'.$value->Value.'</td>
				
				</tr>';
			}
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
       
//---FOOTER Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>';

return  $html;


}			

	public static function getmicrostructui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
				$html.='MICROSTRUCTURE TEST REPORT						
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
		
							$trn='RFI/MCH/'.$rtds->LabNo.'-'.$rtds->TNo;
						
		
        $html.='
			<tr><td colspan="12" style="text-align:center;margin-top: 5px;margin-bottom: 5px;">
						<b>METALLOGRAPHY REPORT</b></td>						
					</tr>
		<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		<tr>
		 <td colspan="6"><b>Material /Grade :</b> '.$rtds->MaterialGrade.'</td>
         <td colspan="6"></td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr><td  colspan="12" class="text-center"  style="padding:2px; vertical-align: middle;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="150" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
		
//----Observations----//		
 
 $html .= ' <tr><td colspan="12" style="padding:2px;" ><b>Observation :</b>
					'.$rtds->observations[0]->Values[0]->Value.'</td>
				</tr>';
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
	
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
						
				$html.=' MICROSTRUCTURE TEST REPORT						
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
	
		 $html.='
			<tr>
						<td colspan="12" style="text-align:center;margin-top: 5px;margin-bottom: 5px;">
						<b>METALLOGRAPHY REPORT</b></td>
						
					</tr>
		<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b>'.$rtds->HTBatchNo.' </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>
		<tr>
		 <td colspan="6"><b>Material /Grade :</b> '.$rtds->MaterialGrade.'</td>
         <td colspan="6"></td>           
        </tr>
		';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
	
	//---imagesavealpha
	if(count($rtds->Imgs)>0)
	{
		$html.='<tr style="text-align:center;"><td  colspan="12" class="text-center"  style=" vertical-align: middle;padding:2px;text-align:center;" >';
	
			foreach($rtds->Imgs as $img)
			{
				$html.='<img src="'.$img->url.'" height="150" width="auto" alt="'.$img->name.'"/>';
			}
			
			$html.='</td></tr>';
	}
		
//----Observations----//		
 
 $html .= ' <tr><td colspan="12" style="padding:2px;" ><b>Observation :</b>
					'.$rtds->observations[0]->Values[0]->Value.'</td>
				</tr>';
	//---bottom basic Parameters
	$html.=MyTestUI::getpdfbottombasic($rtds);
			
       
//---FOOTER Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>';

return  $html;


}			

	public static function getcaseui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
				$html.='CASE DEPTH BY MICRO VICKERS HARDNESS TEST REPORT						
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
		
							$trn='RFI/ECD/'.$rtds->LabNo.'-'.$rtds->TNo;
						
		
        $html.='<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
 
//----Observations----//		
 
 $html .= ' <tr class="text-center">
			<td colspan="12"><b>Observations:</b></td></tr>
				<tr class="text-center">				
					<td colspan="2" ><b>S/n</b></td>
					<td  colspan="5" class="text-center "><b>Distance</b></td>
					<td  colspan="5" class="text-center "><b>Hardness in HV</b></td>			
				</tr>
		';
		
		 // Loop through the values and output rows
        foreach ($rtds->observations[0]->Values as $index => $value) 
		{
            $serialNumber = $index + 1;
          
       
         $html .= '    <tr style="text-align:center;">
                <td colspan="2">'. $serialNumber.'</td>
                <td colspan="5">'. $value->Distance.'</td>
                <td colspan="5">'.$value->Hardness.'</td>
            </tr>';
         } 
       
 
//---footer ui-----------//
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
						
				$html.=' CASE DEPTH BY MICRO VICKERS HARDNESS TEST REPORT
						
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
	
							
						
		
        $html.='<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);

//----Observations----//		
 
 $html .= ' <tr class="text-center">
			<td colspan="12"><b>Observations:</b></td></tr>
				<tr class="text-center">				
					<td colspan="2" ><b>S/n</b></td>
					<td  colspan="5" class="text-center "><b>Distance</b></td>
					<td  colspan="5" class="text-center "><b>Hardness in HV</b></td>			
				</tr>
		';
		
		 // Loop through the values and output rows
        foreach ($rtds->observations[0]->Values as $index => $value) 
		{
            $serialNumber = $index + 1;
          
       
         $html .= '    <tr style="text-align:center;">
                <td colspan="2">'. $serialNumber.'</td>
                <td colspan="5">'. $value->Distance.'</td>
                <td colspan="5">'.$value->Hardness.'</td>
            </tr>';
         } 
       
//---FOOTER Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>';

return  $html;


}			

	public static function getcarbdecarbui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
				$html.='CARBURIZATION & DECARBURIZATION TEST REPORT						
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
		
							$trn='RFI/CDT/'.$rtds->LabNo.'-'.$rtds->TNo;
						
		
        $html.='<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
 
//----Observations----//		
 
 $html .= ' <tr style="text-align:center;">
            <td colspan="12" class="text-center">                
                    <b>CARBURIZATION & DECARBURIZATION BY MICRO HARDNESS</b><br>  
				<b><u>Hardness In HV</u></b>					
            </td>
        </tr>
		
        <tr style="text-align:center;">
            <th colspan="3" class="text-center">S/n</th>
            <th colspan="3" class="text-center">Location</th>
            <th colspan="3" class="text-center">Value</th>
            <th colspan="3" rowspan="4" style="vertical-align: middle;" class="text-left">Remark</th>
        </tr>
		';
		
		 // Loop through the values and output rows
        foreach ($rtds->observations[0]->Values as $index => $value) 
		{
            $serialNumber = $index + 1;
            $location = "HV" . ($index + 1);
            $valueText = htmlspecialchars($value->Value);
       
         $html .= '    <tr style="text-align:center;">
                <td colspan="3">'. $serialNumber.'</td>
                <td colspan="3">'. $location.'</td>
                <td colspan="3">'.$valueText.'</td>
            </tr>';
         } 
       
     $html .= '     <tr>
            <td colspan="12"><b>Carburization as per ISO 898-1 HV(3) ≤ HV(1) + 30</b></td>
        </tr>
        <tr>
            <td colspan="12"><b>Decarburization as per ISO 898-1 HV(2) ≥ HV(1) - 30</b></td>
        </tr>';
		
		

//---top basic Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
						
				$html.=' CARBURIZATION & DECARBURIZATION TEST REPORT
						
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
	
							
						
		
        $html.='<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);

//----Observations----//		
 
 $html .= ' <tr style="text-align:center;">
            <td colspan="12" class="text-center">                
                    <b>CARBURIZATION & DECARBURIZATION BY MICRO HARDNESS</b><br>  
				<b><u>Hardness In HV</u></b>					
            </td>
        </tr>
		
        <tr style="text-align:center;">
            <th colspan="3" class="text-center">S/n</th>
            <th colspan="3" class="text-center">Location</th>
            <th colspan="3" class="text-center">Value</th>
            <th colspan="3" rowspan="4" style="vertical-align: middle;" class="text-left">Remark</th>
        </tr>
		';
		
		 // Loop through the values and output rows
        foreach ($rtds->observations[0]->Values as $index => $value) 
		{
            $serialNumber = $index + 1;
            $location = "HV" . ($index + 1);
            $valueText = htmlspecialchars($value->Value);
       
         $html .= '    <tr style="text-align:center;">
                <td colspan="3">'. $serialNumber.'</td>
                <td colspan="3">'. $location.'</td>
                <td colspan="3">'.$valueText.'</td>
            </tr>';
         } 
       
     $html .= '     <tr>
            <td colspan="12"><b>Carburization as per ISO 898-1 HV(3) &le HV(1) + 30</b></td>
        </tr> 
        <tr>
            <td colspan="12"><b>Decarburization as per ISO 898-1 HV(2) ≥ HV(1) - 30</b></td>
        </tr>';
		
//---FOOTER Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>';

return  $html;


}			

	public static function gettorqueui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
				$html.='TORQUE TENSION TEST REPORT						
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
		
							$trn='RFI/TQR/'.$rtds->LabNo.'-'.$rtds->TNo;
						
		
        $html.='<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);
	
 
//----Observations----//		
 
 $rowspan = count($rtds->observations[0]['Torque']) + 1;
 
     $html .= '     <tr>
            <td colspan="2" rowspan="'.$rowspan.'" class="text-center"><b>Observation : </b></td>
            <td  colspan="1"><b>S/n</b></td>
			<td  colspan="3"><b>Applied Torque in Nm </b></td>
			<td  colspan="3"><b>Force in KN</b></td>
			<td  colspan="3"><b>Coefficient of Friction(μ)</b></td>
        </tr>';	
		 
		foreach ($rtds->observations[0]['Torque'] as $index => $torque)
		{
			 $html .= '<tr style="text-align:center;">
			 <td colspan="1" class="text-center"><b>'.($index + 1).'</b></td>
			<td  colspan="3">'.$torque->Value.'</td>
			<td  colspan="3">'.$rtds->observations[0]['Force'][$index]->Value.'</td>
			<td  colspan="3">'.$rtds->observations[0]['Friction'][$index]->Value.' </td>
			</tr>';
		}
			

//---top basic Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
						
				$html.=' TORQUE TENSION TEST REPORT
						
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
	
							
						
		
        $html.='<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);

 //----Observations----//		
 
 $rowspan = count($rtds->observations[0]['Torque']) + 1;
 
     $html .= '     <tr>
            <td colspan="2" rowspan="'.$rowspan.'" class="text-center"><b>Observation : </b></td>
            <td  colspan="1"><b>S/n</b></td>
			<td  colspan="3"><b>Applied Torque in Nm </b></td>
			<td  colspan="3"><b>Force in KN</b></td>
			<td  colspan="3"><b>Coefficient of Friction(μ)</b></td>
        </tr>';	
		 
		foreach ($rtds->observations[0]['Torque'] as $index => $torque)
		{
			 $html .= '<tr style="text-align:center;">
			 <td colspan="1" class="text-center"><b>'.($index + 1).'</b></td>
			<td  colspan="3">'.$torque->Value.'</td>
			<td  colspan="3">'.$rtds->observations[0]['Force'][$index]->Value.'</td>
			<td  colspan="3">'.$rtds->observations[0]['Friction'][$index]->Value.' </td>
			</tr>';
		}
			 

//---top basic Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>';

return  $html;


}			

	public static function getproofloadui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
				$html.=' PROOF LOAD TEST REPORT						
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
		
							$trn='RFI/PLR/'.$rtds->LabNo.'-'.$rtds->TNo;
						
		
        $html.='<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);

 //----Observations----//		
 
 $rowspan = count($rtds->observations[0]->Values) + 1;
 
     $html .= '     <tr>
            <td colspan="2" rowspan="'.$rowspan.'" class="text-center"><b>Observation : </b></td>
            <td colspan="1" class="text-center"><b>#</b></td>
            <td colspan="3"><b>Proof load Required(KN)</b></td>
            <td colspan="3"><b>Proof load Applied(KN)</b></td>
            <td colspan="3"><b>Extension(mm) Permisible: ±0.0125mm</b></td>
        </tr>';
		
		 
		foreach ($rtds->observations[0]->Values as $index => $value)
		{
			 $html .= '<tr style="text-align:center;">
			 <td colspan="1" class="text-center"><b>'.($index + 1).'</b></td>';
				 foreach ($rtds->observations as $index2 => $obs)
				 { 
					 $html .= '   <td colspan="3"><b>'.($obs->Values[$index]->Value).'</b></td>';
				 }
				$html .= '</tr>';					
									
				
		 }

//---top basic Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
						
				$html.=' PROOF LOAD TEST REPORT
						
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
	
							$trn='RFI/PLR/'.$rtds->LabNo.'-'.$rtds->TNo;
						
		
        $html.='<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);

 //----Observations----//		
 
 $rowspan = count($rtds->observations[0]->Values) + 1;
 
     $html .= '     <tr>
            <td colspan="2" rowspan="'.$rowspan.'" class="text-center"><b>Observation : </b></td>
            <td colspan="1" class="text-center"><b>#</b></td>
            <td colspan="3"><b>Proof load Required(KN)</b></td>
            <td colspan="3"><b>Proof load Applied(KN)</b></td>
            <td colspan="3"><b>Extension(mm) Permisible: ±0.0125mm</b></td>
        </tr>';
		
		 
		foreach ($rtds->observations[0]->Values as $index => $value)
		{
			 $html .= '<tr style="text-align:center;">
			 <td colspan="1" class="text-center"><b>'.($index + 1).'</b></td>';
				 foreach ($rtds->observations as $index2 => $obs)
				 { 
					 $html .= '   <td colspan="3"><b>'.($obs->Values[$index]->Value).'</b></td>';
				 }
				$html .= '</tr>';					
									
				
		 }

//---top basic Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>';

return  $html;


}			

	public static function gethardnessui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
						if($rtds->TUID==='BHARD')
						{
							$html.='BRINELL';
						}
						if($rtds->TUID==='VHARD')
						{
							$html.='VICKERS';
						}
						if($rtds->TUID==='MVHARD')
						{
							$html.='MICRO VICKERS';
						}
						if($rtds->TUID==='RCHARD' || $rtds->TUID==='RBHARD' )
						{
							$html.='ROCKWELL';
						}
						

				$html.=' HARDNESS TEST REPORT
						
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
		if($rtds->TUID==='BHARD')
						{
							$trn='RFI/BHR/'.$rtds->LabNo.'-'.$rtds->TNo;
						}
						if($rtds->TUID==='VHARD')
						{
							$trn='RFI/VHR/'.$rtds->LabNo.'-'.$rtds->TNo;
						}
						if($rtds->TUID==='MVHARD')
						{
							$trn='RFI/MVH/'.$rtds->LabNo.'-'.$rtds->TNo;
						}
						if($rtds->TUID==='RCHARD' || $rtds->TUID==='RBHARD' )
						{
							$trn='RFI/RHR/'.$rtds->LabNo.'-'.$rtds->TNo;
						}
		
        $html.='<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);

 //----Observations----//		
		$groupedObservations = [];
foreach ($rtds->observations as $observation) {
    $groupedObservations[$observation->CatName][] = $observation;
}
		 foreach ($groupedObservations as $category => $observations)
		 {	
if($category)		
{	
    $html .= '    <tr >
            <td colspan="12"><strong>'.$category.'</strong></td>
        </tr>';
}

     $html .= '    <tr style="text-align:center;" >
           
            <td colspan="3"><b>Parameters</b></td>
            <td colspan="3"><b>Specification Req.</b></td>
            <td colspan="6"class="text-center"><b>Results</b></td>
        </tr>';
				 foreach ($observations as $index => $chem)
				 { 
					 $html .= '<tr style="text-align:center;">
							
								<td colspan="3">Observations <br>'.$chem->Param . ' ' . $chem->PUnit.'</td>
								<td colspan="3">  '.$chem->SpecMin;
									
									if (!empty($chem->SpecMin))
									{
							   $html .= '  <span>Min</span>';
									}
								  if (!empty($chem->SpecMax))
								  {
							   $html .= ' <span> - '.$chem->SpecMax.' Max</span>';
								  }
									$html .= '</td><td colspan="6" class="p-0">';
									
									$html .= '<table cellpadding="4" style="width:100%;" class="p-0 mb-0">
						<tr>
						 <td >S/n</td>
						<td style="text-align:center;">Surface</td>
						<td style="text-align:center;">Core</td>
						</tr>';
						
						
						
											foreach ($chem->Values as $index=>$value)
											{
												$html .= '<tr >
													<td colspan="1">'.($index + 1).'</td>
						<td style="text-align:center;">'.$value->SValue.'</td>
						<td style="text-align:center;">'.$value->CValue.'</td>
						</tr>';
											}
							$html .= '</table></td></tr>';
				}
		 }

//---top basic Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	//---header
	$html.=MyTestUI::getpdfnablhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            	<td colspan="8" rowspan="2" style="text-align:center;padding:4px;">
						<h4 style="margin-top: 5px;margin-bottom: 5px;"><b>';
						if($rtds->TUID==='BHARD')
						{
							$html.='BRINELL';
						}
						if($rtds->TUID==='VHARD')
						{
							$html.='VICKERS';
						}
						if($rtds->TUID==='MVHARD')
						{
							$html.='MICRO VICKERS';
						}
						if($rtds->TUID==='RCHARD' || $rtds->TUID==='RBHARD' )
						{
							$html.='ROCKWELL';
						}
						

				$html.=' HARDNESS TEST REPORT
						
						</b></h4></td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>';
		if($rtds->TUID==='BHARD')
						{
							$trn='RFI/BHR/'.$rtds->LabNo.'-'.$rtds->TNo;
						}
						if($rtds->TUID==='VHARD')
						{
							$trn='RFI/VHR/'.$rtds->LabNo.'-'.$rtds->TNo;
						}
						if($rtds->TUID==='MVHARD')
						{
							$trn='RFI/MVH/'.$rtds->LabNo.'-'.$rtds->TNo;
						}
						if($rtds->TUID==='RCHARD' || $rtds->TUID==='RBHARD' )
						{
							$trn='RFI/RHR/'.$rtds->LabNo.'-'.$rtds->TNo;
						}
		
        $html.='<tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b>'.$trn.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>';
      $html.='  <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
		
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>           
        </tr>';
		//---top basic Parameters
	$html.=MyTestUI::getpdftopbasic($rtds);

 //----Observations----//		
		$groupedObservations = [];
foreach ($rtds->observations as $observation) {
    $groupedObservations[$observation->CatName][] = $observation;
}
		 foreach ($groupedObservations as $category => $observations)
		 {	
if($category)		
{	
    $html .= '    <tr >
            <td colspan="12"><strong>'.$category.'</strong></td>
        </tr>';
}

     $html .= '    <tr style="text-align:center;" >
           
            <td colspan="3"><b>Parameters</b></td>
            <td colspan="3"><b>Specification Req.</b></td>
            <td colspan="6"class="text-center"><b>Results</b></td>
        </tr>';
				 foreach ($observations as $index => $chem)
				 { 
					 $html .= '<tr style="text-align:center;">
							
								<td colspan="3">Observations <br>'.$chem->Param . ' ' . $chem->PUnit.'</td>
								<td colspan="3">  '.$chem->SpecMin;
									
									if (!empty($chem->SpecMin))
									{
							   $html .= '  <span>Min</span>';
									}
								  if (!empty($chem->SpecMax))
								  {
							   $html .= ' <span> - '.$chem->SpecMax.' Max</span>';
								  }
									$html .= '</td><td colspan="6" class="p-0">';
									
									$html .= '<table cellpadding="4" style="width:100%;" class="p-0 mb-0">
						<tr>
						 <td >#</td>
						<td style="text-align:center;">Surface</td>
						<td style="text-align:center;">Core</td>
						</tr>';
						
						
						
											foreach ($chem->Values as $index=>$value)
											{
												$html .= '<tr >
													<td colspan="1">'.($index + 1).'</td>
						<td style="text-align:center;">'.$value->SValue.'</td>
						<td style="text-align:center;">'.$value->CValue.'</td>
						</tr>';
											}
							$html .= '</table></td></tr>';
				}
		 }



//---top basic Parameters
	$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
    </tbody>
</table>';

return  $html;


}			

			
public static function getimpactui($rtds,$pdf)
{

	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="2" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    //--header
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            <td colspan="8" rowspan="2" style="text-align:center;vertical-align:middle;">
             <b style="font-size:12pt;">CHARPY \'V\' NOTCH IMPACT TEST REPORT</b>
            </td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b> RFI/ITR/'.$rtds->LabNo.'-'.$rtds->TNo.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>
           
        </tr>';
		
		//----Basic top parameters
		
		$html .= MyTestUI::getpdftopbasic($rtds);
		
		//----observations
		
			$html .= '<tr><td colspan="12"><b>CHARPY IMPACT TEST RESULT</b></td></tr>';
 
		
 $html .= '<tr class="text-center">
					<td colspan="5" ><b>Specimen</b></td>
					<td  colspan="1"><b>1</b></td>
					<td  colspan="1"><b>2</b></td>
					<td  colspan="1"><b>3</b></td>
					<td  colspan="2"><b>Average</b></td>
					<td  colspan="2"><b>Req.Value as per Standard</b></td>
				</tr>';
						
	
			
				foreach($rtds->observations as $index =>$ob)
				{
					
						$html .= '		<tr class="text-center" ng-repeat="item in info.observations">
					<td colspan="5"><b>'.$ob->Param.' '.$ob->PUnit.'</b></td>';
					
					 foreach ($ob->Values as $value) {
        $html .= '<td colspan="1"><span class="text-info">' . $value->Value. '</span></td>';
    }
					
					  if ($index === 0) {
        $html .= '<td colspan="2"><span>' . number_format($ob->Avg, 4) . '</span></td>';
    } else {
        $html .= '<td colspan="2"></td>';
    }
					
					
					// Add specifications
    if ($ob->IsSpec) {
        $specMin = $ob->SpecMin;
        $specMax = htmlspecialchars($ob->SpecMax);
        $html .= '<td colspan="2"><span><b>Average: ' . $specMin . ' - ' . $specMax . '</b></span></td>';
    } else {
        $html .= '<td colspan="2"></td>';
    }
    
    $html .= '</tr>';
	
	
				}
				

 //--footer
	$html.=MyTestUI::getpdffooter($rtds);

		 $html.='
		    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="2" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
  $html.=MyTestUI::getpdfnablhead($rtds);
	
  
  $html.='  <tbody style="font-size:10pt;">
        <tr>
            <td colspan="8" rowspan="2" style="text-align:center;vertical-align:middle;">
             <b style="font-size:12pt;">CHARPY \'V\' NOTCH IMPACT TEST REPORT</b>
            </td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b> RFI/ITR/'.$rtds->LabNo.'-'.$rtds->TNo.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>
           
        </tr>';
		
		//----Basic top parameters
		
		$html .= MyTestUI::getpdftopbasic($rtds);
		
		//----observations
		
			$html .= '<tr><td colspan="12"><b>CHARPY IMPACT TEST RESULT</b></td></tr>';
 
		
 $html .= '<tr class="text-center">
					<td colspan="5" ><b>Specimen</b></td>
					<td  colspan="1"><b>1</b></td>
					<td  colspan="1"><b>2</b></td>
					<td  colspan="1"><b>3</b></td>
					<td  colspan="2"><b>Average</b></td>
					<td  colspan="2"><b>Req.Value as per Standard</b></td>
				</tr>';
		foreach($rtds->observations as $index =>$ob)
				{
					
						$html .= '		<tr class="text-center" ng-repeat="item in info.observations">
					<td colspan="5"><b>'.$ob->Param.' '.$ob->PUnit.'</b></td>';
					
					 foreach ($ob->Values as $value) {
        $html .= '<td colspan="1"><span class="text-info">' . $value->Value. '</span></td>';
    }
					
					  if ($index === 0) {
        $html .= '<td colspan="2"><span>' . number_format($ob->Avg, 4) . '</span></td>';
    } else {
        $html .= '<td colspan="2"></td>';
    }
					
					
					// Add specifications
    if ($ob->IsSpec) {
        $specMin = $ob->SpecMin;
        $specMax = htmlspecialchars($ob->SpecMax);
        $html .= '<td colspan="2"><span><b>Average: ' . $specMin . ' - ' . $specMax . '</b></span></td>';
    } else {
        $html .= '<td colspan="2"></td>';
    }
    
    $html .= '</tr>';
	
	
				}
				


 //--footer
	$html.=MyTestUI::getpdffooter($rtds);

   $html.=' </tbody>
</table>';

return  $html;


}			
	

	
	
	public static function gettensileui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="2" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    
	$html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:9pt;">
        <tr>
            <td colspan="8" rowspan="2" style="text-align:center;vertical-align:middle;">
             <b style="font-size:12pt;">TENSILE TEST REPORT</b>
            </td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b> RFI/TTR/'.$rtds->LabNo.'-'.$rtds->TNo.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>
           
        </tr>';
		
		//----Basic top parameters
		
		$html .= MyTestUI::getpdftopbasic($rtds);

  $html .= '<tr style="font-size:9pt;"><td colspan="12"><strong>Observations</strong></td></tr>';
 
		
		$groupedObservations = [];
foreach ($rtds->observations as $observation) {
    $groupedObservations[$observation->CatName][] = $observation;
}
		 foreach ($groupedObservations as $category => $observations)
		 {	
if($category)		
{	
    $html .= '    <tr style="font-size:9pt;">
            <td colspan="12"><strong>'.$category.'</strong></td>
        </tr>';
}

     $html .= '    <tr style="font-size:9pt;">
            <td colspan="1">S/n</td>
            <td colspan="5">Parameters</td>
            <td colspan="3">Specification Req.</td>
            <td colspan="3"class="text-center">Results</td>
        </tr>';
				 foreach ($observations as $index => $chem)
				 { 
					 $html .= '<tr style="font-size:9pt;">
								<td colspan="1">'.($index + 1).'</td>
								<td colspan="5">'.$chem->Param . ' ' . $chem->PUnit.'</td>
								<td colspan="3">  '.$chem->SpecMin;
									
									if (!empty($chem->SpecMin))
									{
							   $html .= '  <span>Min</span>';
									}
								  if (!empty($chem->SpecMax))
								  {
							   $html .= ' <span> - '.$chem->SpecMax.' Max</span>';
								  }
									$html .= '</td><td colspan="2" class="p-0">';
											foreach ($chem->Values as $value)
											{
												$html .= $value->Value;
											}
							$html .= '</td></tr>';
		 }
		 }
       
// Generate table content for different chunks

	
		$html .= MyTestUI::getpdffooter($rtds);
		
		 $html.='
        
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="2" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
  $html.=MyTestUI::getpdfnablhead($rtds);
	
  $html.='  <tbody style="font-size:9pt;">
        <tr>
            <td colspan="8" rowspan="2" style="text-align:center;vertical-align:middle;">
             <b style="font-size:12pt;">TENSILE TEST REPORT</b>
            </td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b> RFI/TTR/'.$rtds->LabNo.'-'.$rtds->TNo.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			<td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			 <td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
         <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>
           
        </tr>';
		
	//----Basic top parameters
		
		$html .= MyTestUI::getpdftopbasic($rtds);
		
	//---observations----//	
  $html .= '<tr style="font-size:9pt;"><td colspan="12"><strong>Observations</strong></td></tr>';
 
		
		$groupedObservations = [];
foreach ($rtds->observations as $observation) {
    $groupedObservations[$observation->CatName][] = $observation;
}
		 foreach ($groupedObservations as $category => $observations)
		 {	
if($category)		
{	
    $html .= '    <tr style="font-size:9pt;">
            <td colspan="12"><strong>'.$category.'</strong></td>
        </tr>';
}

     $html .= '    <tr style="font-size:9pt;">
            <td colspan="1">S/n</td>
            <td colspan="5">Parameters</td>
            <td colspan="3">Specification Req.</td>
            <td colspan="3"class="text-center">Results</td>
        </tr>';
				 foreach ($observations as $index => $chem)
				 { 
					 $html .= '<tr style="font-size:9pt;">
								<td colspan="1">'.($index + 1).'</td>
								<td colspan="5">'.$chem->Param . ' ' . $chem->PUnit.'</td>
								<td colspan="3">  '.$chem->SpecMin;
									
									if (!empty($chem->SpecMin))
									{
							   $html .= '  <span>Min</span>';
									}
								  if (!empty($chem->SpecMax))
								  {
							   $html .= ' <span> - '.$chem->SpecMax.' Max</span>';
								  }
									$html .= '</td><td colspan="2" class="p-0">';
											foreach ($chem->Values as $value)
											{
												$html .= $value->Value;
											}
							$html .= '</td></tr>';
		 }
		 }
       
// Generate table content for different chunks
//----footer
		
		$html .= MyTestUI::getpdffooter($rtds);

		 $html.='
		 </tbody>
</table>';

return  $html;


}			
	


public static function getchemicalui($rtds,$pdf)
{
	
	  $html = '<style> td, th {
    padding: 5px;
    background: #fff;
    border: 1px solid #eee;
}
p{padding-bottom:2px;margin-bottom:0px;width:100%;}
</style>
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';

    $html.=MyTestUI::getpdfhead($rtds);
	
	$bn="";
	if(!is_null($rtds->BatchNo))
	{
			$bn="-".$rtds->BatchNo;
	}

  $html.='  <tbody style="font-size:10pt;">
        <tr>
            <td colspan="8" rowspan="2" style="text-align:center;vertical-align:middle;">
             <b style="font-size:13pt;">CHEMICAL TEST REPORT</b>
            </td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b> RFI/CTR/'.$rtds->LabNo.'-'.$rtds->TNo.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			 <td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
           
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			<td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
      
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
            <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>
        </tr>
		  <tr>
            <td colspan="12"><b>Part Name :</b>'.$rtds->SampleName.'</td>
            
        </tr>
		
		';
		
		
	//----Basic top parameters
		
		$html .= MyTestUI::getpdftopbasic($rtds);
		
 $html.='<tr>
            <td colspan="12" style="text-align:left;"><b>Observations </b></td>
        </tr>';
		
		//----Observations

// Function to generate rows for different segments
function generateRows($items, $start, $limit) {
    $mhtml = '';
    $chunk = array_slice($items, $start, $limit);
    if (count($chunk) > 0) {
        $mhtml .= '<tr style="font-size:9pt;font-weight:bold;"><td><b style="font-size:8pt;">Element</b></td>';
        foreach ($chunk as $item) {
            $mhtml .= '<td><b>' . $item->PSymbol . '</b></td>';
        }
        $mhtml .= '</tr>';
        
        $mhtml .= '<tr style="font-size:9pt;"><td style="font-size:8pt;"><b>Min %</b></td>';
        foreach ($chunk as $item) {
            $mhtml .= '<td>' . $item->SpecMin . '</td>';
        }
        $mhtml .= '</tr>';
        
        $mhtml .= '<tr style="font-size:9pt;"><td style="font-size:8pt;"><b>Max %</b></td>';
        foreach ($chunk as $item) {
            $mhtml .= '<td>' . $item->SpecMax . '</td>';
        }
        $mhtml .= '</tr>';
        
        $mhtml .= '<tr style="font-size:9pt;"><td style="font-size:8pt;"><b>Value %</b></td>';
        foreach ($chunk as $item) {
            $mhtml .= '<td>' . $item->Values[0]->Value . '</td>';
        }
        $mhtml .= '</tr>';
    }
    return $mhtml;
}

// Generate table content for different chunks
$html .= generateRows($rtds->observations, 0, 11);
$html .= generateRows($rtds->observations, 11, 11);
$html .= generateRows($rtds->observations, 22, 11);


$html.=MyTestUI::getpdffooter($rtds);

		 $html.='
         </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--stainless steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';

$html.=MyTestUI::getpdfnablhead($rtds);
    
  $html.='  <tbody style="font-size:10pt;">
        <tr>
            <td colspan="8" rowspan="2" style="text-align:center;vertical-align:middle;">
             <b style="font-size:13pt;">CHEMICAL TEST REPORT</b>
            </td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b> RFI/CTR/'.$rtds->LabNo.'-'.$rtds->TNo.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			 <td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
           
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			<td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
      
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
            <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>
        </tr>
		  <tr>
            <td colspan="12"><b>Part Name :</b>'.$rtds->SampleName.'</td>
            
        </tr>
		
		';
		
		
	//----Basic top parameters
		
		$html .= MyTestUI::getpdftopbasic($rtds);
		
 $html.='<tr>
            <td colspan="12" style="text-align:left;"><b>Observations </b></td>
        </tr>';
		
		//----Observations

		

// Function to generate rows for different segments
function generateStainRows($items) {
    $mhtml = '';
    $chunk = array_slice($items, 0, 32);
    if (count($chunk) > 0) {
        $mhtml .= '<tr style="font-size:9pt;font-weight:bold;"><td><b style="font-size:8pt;">Element</b></td>';
        foreach ($chunk as $item) {
            $mhtml .= '<td><b>' . $item->PSymbol . '</b></td>';
        }
        $mhtml .= '</tr>';
        
        $mhtml .= '<tr style="font-size:9pt;"><td style="font-size:8pt;"><b>Min %</b></td>';
        foreach ($chunk as $item) {
            $mhtml .= '<td>' . $item->SpecMin . '</td>';
        }
        $mhtml .= '</tr>';
        
        $mhtml .= '<tr style="font-size:9pt;"><td style="font-size:8pt;"><b>Max %</b></td>';
        foreach ($chunk as $item) {
            $mhtml .= '<td>' . $item->SpecMax . '</td>';
        }
        $mhtml .= '</tr>';
        
        $mhtml .= '<tr style="font-size:9pt;"><td style="font-size:8pt;"><b>Value %</b></td>';
        foreach ($chunk as $item) {
            $mhtml .= '<td>' . $item->Values[0]->Value . '</td>';
        }
        $mhtml .= '</tr>';
    }
    return $mhtml;
}



function eleinscope($items) {
	
	// Example usage:
$chemscopes = [
   (object) ['Id' => 1, 'Elements' => ['B','C','Cr','Cu','Mn','Mo','Ni','P','Si','S']],
   (object) ['Id' => 2, 'Elements' => ['C','Cr','Mn','Mo','Ni','P','Si','S']],
    // Add more scopes as needed
];

 $Elements=['B','C','Cr','Cu','Mn','Mo','Ni','P','Si','S'];
    // Get the first matched scope (since array_filter returns an array of matches)
   
    
    // Check if the element's symbol is in the Elements array of the scope
$arr=[];
	
foreach($items as $i)
{
	if(in_array($i->PSymbol, $Elements))
	{
		$arr[]= $i;
	}
}



	return $arr;
	
	
}





$stainobs= eleinscope($rtds->observations);
// Generate table content for different chunks
$html .= generateStainRows($stainobs);


$html.=MyTestUI::getpdffooter($rtds);

		 $html.='       
    </tbody>
</table>
<br pagebreak="true" />';


//-----NABL--Lowcarbon steel----//
  $html .= '
<table border="1" cellpadding="3" cellspacing="0" style="width:100%; border:0px solid #000; border-collapse:collapse;">';
    $html.=MyTestUI::getpdfnablhead($rtds);
	
	
    $html.='  <tbody style="font-size:10pt;">
        <tr>
            <td colspan="8" rowspan="2" style="text-align:center;vertical-align:middle;">
             <b style="font-size:13pt;">CHEMICAL TEST REPORT</b>
            </td>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>ULR No.:</b> '.$rtds->ULRNo.'</td>
        </tr>
        <tr>
            <td colspan="4" style="padding:2px;font-size:9pt;text-align:left;"><b>Issue Date:</b> '.$rtds->ApprovedDate.'</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:left;"><b>Test Report No. :</b> RFI/CTR/'.$rtds->LabNo.'-'.$rtds->TNo.'</td>
            <td colspan="6" style="text-align:left;"><b>Test Date :</b> '.$rtds->TestDate.'</td>
        </tr>
        <tr>
            
            <td colspan="6" style="text-align:left;"><b>Lab No. :</b> '.$rtds->LabNo.'</td>
			 <td colspan="6" style="text-align:left;"><b>BatchCode :</b> '.$rtds->BatchCode.''.$bn.'</td>
        </tr>
        <tr>
           
            <td colspan="6" style="text-align:left;"><b>Heat No. :</b> '.$rtds->HeatNo.'</td>
			<td colspan="6" style="text-align:left;"><b>HT.Batch No :</b> </td>
        </tr>
        <tr>
            <td colspan="6"><b>Sample Receipt On :</b> '.$rtds->ReceiptOn.'</td>
			<td colspan="6"><b>Customer :</b> '.$rtds->Customer.'</td>
           
        </tr>
      
        <tr>
		 <td colspan="6"><b>Reference Std. :</b> '.$rtds->Standard.'</td>
            <td colspan="6"><b>Test Method :</b> '.$rtds->TestMethod.'</td>
        </tr>
		  <tr>
            <td colspan="12"><b>Part Name :</b>'.$rtds->SampleName.'</td>
            
        </tr>
		
		';
		
		
			//----Basic top parameters
		
		$html .= MyTestUI::getpdftopbasic($rtds);
		

		//----Observations

 $html.='<tr>
            <td colspan="12" style="text-align:left;"><b>Observations </b></td>
        </tr>';
		
function eleinscope2($items) {
	


 $Elements=['C','Cr','Mn','Mo','Ni','P','Si','S'];
    // Get the first matched scope (since array_filter returns an array of matches)
   
    
    // Check if the element's symbol is in the Elements array of the scope
$arr=[];
	
foreach($items as $i)
{
	if(in_array($i->PSymbol, $Elements))
	{
		$arr[]= $i;
	}
}



	return $arr;
	
	
}


$alloyobs= eleinscope2($rtds->observations);
// Generate table content for different chunks
$html .= generateStainRows($alloyobs);

$html.=MyTestUI::getpdffooter($rtds);
		 $html.='
        
       
    </tbody>
</table>';

return  $html;


}			


public static function getpdfhead($rtds)
	{
		$appset=Settingsfirm::model()->find();
		 $applogo=Firmlogos::model()->find();
		 $applogo=empty($applogo)?null:$applogo->url;
		$head=' <thead>
        <tr style="background:#fff;">
            <th colspan="3" rowspan="4" style="vertical-align:middle;padding:4px;">               
                <img src="'.$applogo.'" alt="logo" height="50" width="50" style="padding:4px;">
            </th>
            <th colspan="6" rowspan="4" style="background:#fff;text-align:center;padding:2x;">                
                    <span style="font-size:11pt;padding:5px;font-weight:bold;">'.$appset->Name.'</span><br>
                    <span style="font-size:9pt;">'.$appset->Address.'</span>                
            </th>
            <th colspan="3"  style="padding:2px;font-size:8pt;text-align:left;">Format No: '.$rtds->FormatNo.'</th></tr>
		<tr><th colspan="3" style="padding:2px;font-size:8pt;text-align:left;">Rev No. : '.$rtds->RevNo.'</th></tr>
		<tr><th colspan="3" style="padding:2px;font-size:8pt;text-align:left;">Rev Date:  '.$rtds->RevDate .'</th></tr>
		<tr><th colspan="3" style="padding:2px;font-size:8pt;text-align:left;">PageNo:- 01 of 01</th></tr>
		</thead>';
		
		return $head;
	}
	
	public static function getpdfnablhead($rtds)
	{
		$appset=Settingsfirm::model()->find();
		 $applogo=Firmlogos::model()->find();
		 $applogo=empty($applogo)?null:$applogo->url;
		 $nabllogo=Labaccreditlogos::model()->find();
	 $nabllogo=empty($nabllogo)?null:$nabllogo->url;
	$head = ' 
<thead>
    <tr style="background:#fff;">
        <th colspan="3" rowspan="4" style="vertical-align:middle; text-align:center; padding:4px;">    
            <img src="'.$nabllogo.'" alt="NABL Logo" height="125" width="90" style="padding:4px;">
        </th>
        <th colspan="6" rowspan="4" style="background:#fff; text-align:center; padding:4px;">
            <img src="'.$applogo.'" alt="logo" height="40" width="40" style="padding:4px;">
            <div style="font-size:11pt; font-weight:bold;">'.$appset->Name.'</div>
            <div style="font-size:9pt;">'.$appset->Address.'</div>
        </th>
        <th colspan="3" style="padding:2px; font-size:8pt; text-align:left;">Format No: '.$rtds->FormatNo.'</th>
    </tr>
    <tr>
        <th colspan="3" style="padding:2px; font-size:8pt; text-align:left;">Rev No. : '.$rtds->RevNo.'</th>
    </tr>
    <tr>
        <th colspan="3" style="padding:2px; font-size:8pt; text-align:left;">Rev Date:  '.$rtds->RevDate .'</th>
    </tr>
    <tr>
        <th colspan="3" style="padding:2px; font-size:8pt; text-align:left;">Page No: 01 of 01</th>
    </tr>
</thead>';

		
		return $head;
	}
		
public static function getpdftopbasic($rtds)
{
	$items = $rtds->tobbasic;
		$totalItems = count($items);
		$halfLength = ceil($totalItems / 2);
		
		$html ="";
			for ($i = 0; $i < $halfLength; $i++) 
			{
				$html .= '<tr >';

				// First cell
				if (isset($items[$i * 2])) {
					$param1 = $items[$i * 2]->Parameter;
					$value1 = $items[$i * 2]->BValue;
					$unit1 = $items[$i * 2]->PUnit;
					$html .= '<td colspan="6"><b>'.$param1.':</b> '.$value1.' '.$unit1.'</td>';
				} else {
					$html .= '<td colspan="6"></td>'; // Empty cell if not enough items
				}

				// Second cell
				if (isset($items[$i * 2 + 1])) {
					$param2 = $items[$i * 2 + 1]->Parameter;
					$value2 = $items[$i * 2 + 1]->BValue;
					$unit2 = $items[$i * 2 + 1]->PUnit;
					$html .= '<td colspan="6"><b>'.$param2.':</b> <span>'.$value2.' '.$unit2.'</span></td>';
				} else {
					$html .= '<td colspan="6"></td>'; // Empty cell if not enough items
				}

				$html .= '</tr>';
			}
			
			return $html;

}		

	
	public static function getpdfbottombasic($rtds)
{
	$items = $rtds->dobbasic;
		$totalItems = count($items);
		$halfLength = ceil($totalItems / 2);
		
		$html ="";
			for ($i = 0; $i < $halfLength; $i++) 
			{
				$html .= '<tr >';

				// First cell
				if (isset($items[$i * 2])) {
					$param1 = $items[$i * 2]->Parameter;
					$value1 = $items[$i * 2]->BValue;
					$unit1 = $items[$i * 2]->PUnit;
					$html .= '<td colspan="6"><b>'.$param1.':</b> '.$value1.' '.$unit1.'</td>';
				} else {
					$html .= '<td colspan="6"></td>'; // Empty cell if not enough items
				}

				// Second cell
				if (isset($items[$i * 2 + 1])) {
					$param2 = $items[$i * 2 + 1]->Parameter;
					$value2 = $items[$i * 2 + 1]->BValue;
					$unit2 = $items[$i * 2 + 1]->PUnit;
					$html .= '<td colspan="6"><b>'.$param2.':</b> <span>'.$value2.' '.$unit2.'</span></td>';
				} else {
					$html .= '<td colspan="6"></td>'; // Empty cell if not enough items
				}

				$html .= '</tr>';
			}
			
			return $html;

}		



public static function getpdffooter($rtds)
{
	$html='';
	if(!is_null($rtds->TNote) && $rtds->TNote != '')
	{
		$html.='<tr><td colspan="12"><b>Remark :</b>'.$rtds->TNote.'</td></tr>'; 
	}
	$html.='	<tr>
            <td colspan="12" style="text-align:center;padding:0px;border-width:0pt;">
                <span style="font-weight:bold;padding:2px;">**** END OF REPORT****</span>              
            </td>
        </tr>
        <tr>
            <td colspan="12" style="text-align:left;padding:2px;font-size:8pt;">                
<b>NOTE : </b> '.$rtds->Note.'
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:center;padding:0px;">
                <b>Tested By</b><br/>
                <img src="'.$rtds->TestSign.'" height="30" width="100" alt=""/><br/>
                <span style="font-size:8pt;"> '.$rtds->TestedBy.'</span>
            </td>
            <td colspan="6" style="text-align:center;padding:0px;">
                <b>Authorised Signature</b>
               <br/> <img src="'.$rtds->ApprovedSign.'"  height="30" width="100" alt=""/><br/>
                <span style="font-size:8pt;">'.$rtds->ApprovedBy.'</span>
            </td>
        </tr>';	
			return $html;

}		



	
}