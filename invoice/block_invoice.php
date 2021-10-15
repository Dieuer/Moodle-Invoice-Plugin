<?php

/**
 * Invoice block.
 *
 * @package    block_invoice
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 require_login();

class block_invoice extends block_base {

    
    public function init() {
        $this->title = get_string('pluginname', 'block_invoice');
    }

    function get_content() {
        global $DB, $USER, $OUTPUT;

        // get user id from url from admin view
        $userid = optional_param('id', 0, PARAM_INT);

        if(!empty($userid && is_siteadmin())){
            // if there is a user id in the url and the current user id admin, get invoices
            $invoices = $DB->get_records('user_invoice', ['userid' => $userid]);

        } else{
            // if there is no id in url, get the id of the current user logged in
            $invoices = '';
            $invoices = $DB->get_records('user_invoice', ['userid' => $USER->id]);
        }

        $data = (object)[
            'invoice_element' => array_values($invoices),
            'course',
            'date',
            'pdfurl' => '/blocks/invoice/generate_pdf.php',
            'image' => '/blocks/invoice/image/pdfimage.png',
        ];

        $this->content = new stdClass;

        if(empty($invoices)){
            $this->content->text = 'Du har ingen faktuarer';
        } else{
             $this->content->text = $OUTPUT->render_from_template('block_invoice/invoicelist', $data);
        }
    
        return $this->content;
    }    
}
