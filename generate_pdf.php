<?php
/**
 * Strings for component 'block_test', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package   block_invoice
 * 
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
define('CLI_SCRIPT', true);

require('fpdf/fpdf.php');

require_login();

class invoice_handler{

    function generatePDF($invoiceid){

        global $DB;

        //get specific invoice by id
        $invoice = '';
        $invoice = $DB->get_record('user_invoice', ['id' => $invoiceid]);

        //get specific user by id from invoice
        $user = '';
        $user = $DB->get_record('user', ['id' => $invoice->userid]);
        
        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B',16);
        $pdf->Cell(80,10,'Faktura');
        $pdf-> insertdata($invoice, $user);
        $pdf->Output('pdf_file-php', 'I');
        
    }
}

class PDF extends FPDF{

    //insert data into cells 
    function insertdata($invoice, $user){

        $this->SetFont('Times','',12);

        $this->Cell(10,10,'Faktura ID:' . ' ' . $invoice->id, 0, 2);
        $this->Cell(10,10,'Navn:' . ' ' . $user->firstname . ' ' . $user->lastname, 0, 2);
        $this->Cell(10,10,'Adresse:' . ' ' . $user->address, 0, 2);
        $this->Cell(10,10, 'Dato:' . ' ' . $invoice->date, 0, 2);
        $this->Cell(10,10, 'Kursus:' . ' ' . $invoice->course, 0, 2);
        $this->Cell(10,10,'Kursuspris :' . ' ' . $invoice->price . 'kr.', 0, 2);
    }
}

// get id from templte 
$invoiceid = optional_param('invoiceid', null, PARAM_INT);

$invoice_handler = new invoice_handler();
$invoice_handler->generatePDF($invoiceid);

