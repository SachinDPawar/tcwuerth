<?php
/**
 * @abstract This Component Class is created to access TCPDF plugin for generating reports.
 * @example You can refer http://www.tcpdf.org/examples/example_011.phps for more details for this example.
 * @todo you can extend tcpdf class method according to your need here. You can refer http://www.tcpdf.org/examples.php section for 
 *       More working examples.
 * @version 1.0.0
 */
Yii::import('ext.tcpdf.*');
class MYPDF extends tcpdf {
 
    // // Load table data from file
    // public function LoadData($file) {
        // // Read file lines
        // $lines = file($file);
        // $data = array();
        // foreach($lines as $line) {
            // $data[] = explode(';', chop($line));
        // }
        // return $data;
    // }
 
    // Colored table
    // public function ColoredTable($header,$data) {
        // // Colors, line width and bold font
        // $this->SetFillColor(255, 0, 0);
        // $this->SetTextColor(255);
        // $this->SetDrawColor(128, 0, 0);
        // $this->SetLineWidth(0.3);
        // $this->SetFont('', 'B');
        // // Header
        // $w = array(40, 35, 40, 45);
        // $num_headers = count($header);
        // for($i = 0; $i < $num_headers; ++$i) {
            // $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        // }
        // $this->Ln();
        // // Color and font restoration
        // $this->SetFillColor(224, 235, 255);
        // $this->SetTextColor(0);
        // $this->SetFont('');
        // // Data
        // $fill = 0;
        // foreach($data as $row) {
            // $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
            // $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
            // $this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
            // $this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
            // $this->Ln();
            // $fill=!$fill;
        // }
        // $this->Cell(array_sum($w), 0, '', 'T');
    // }
}
?>