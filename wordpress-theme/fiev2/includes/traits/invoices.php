<?php

trait Invoices{
    
    /**
     * Get annotations of passed patient_id from passed doctor_id
     * 
     * @param integer $doctor_id
     * @param integer $patient_id
     * @return array
     * 
     */
    public static function getInvoices($doctor_id, $patient_id) {
        global $wpdb;
        $invoices_table = $wpdb->prefix . 'invoices';
        
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM $invoices_table
			WHERE payer_patient_id = %d;
		", $doctor_id, $patient_id ));
    }
    
    /**
     * Get annotation of passed id
     * 
     * @param integer $id annotation id to get
     * @return array
     * 
     */
    public static function getInvoice($id) {
        global $wpdb;
        $invoices_table = $wpdb->prefix . 'invoices';
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM $invoices_table
			WHERE id = %d;
		", $id ));
    }
    
    /**
     * Inserts a record into invoices table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function insertInvoiceRecord($data) {
        global $wpdb;
        $invoices_table = $wpdb->prefix . 'invoices';
        
        return $wpdb->insert($invoices_table, [
            //'id' => '', // auto
            'consultation_id' => @$data['consultation_id'],
            'payer_patient_id' => @$data['payer_patient_id'],
            'total' => @$data['total'],
            'subtotal' => @$data['subtotal'],
            'taxes' => @$data['taxes'],
            
            'gateway' => @$data['gateway'],
            'currency' => @$data['currency'],
            
            'status' => @$data['status'],
            
            'created_at' => current_time('mysql', 1),
            'updated_at' => current_time('mysql', 1)
                ], ['%d', '%d', '%f', '%f', '%f',    
                    '%s', '%s',     
                    '%d',
                    '%s', '%s',
                    ]);
    }
    
    /**
     * Modify a record into annotations table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function modifyInvoiceRecord($data) {
        global $wpdb;
        $invoices_table = $wpdb->prefix . 'invoices';
        
        return $wpdb->update(
                $invoices_table, 
                [
                    //'id' => '', // auto
                'consultation_id' => @$data['consultation_id'],
                'payer_patient_id' => @$data['payer_patient_id'],
                'total' => @$data['total'],
                'subtotal' => @$data['subtotal'],
                'taxes' => @$data['taxes'],

                'gateway' => @$data['gateway'],
                'currency' => @$data['currency'],
                'fe_tipo_de_comprobante' => @$data['fe_tipo_de_comprobante'],
                'fe_status' => @$data['fe_status'],

                'fe_folio_fiscal' => @$data['fe_folio_fiscal'],

                'fe_serie_fiscal' => @$data['fe_serie_fiscal'],
                'fe_sello' => @$data['fe_sello'],

                'fe_ano_aprobacion' => @$data['fe_ano_aprobacion'],

                'fe_no_aprobacion' => @$data['fe_no_aprobacion'],
                'fe_no_certificado' => @$data['fe_no_certificado'],
                'fe_fecha_hora_expedicion' => @$data['fe_fecha_hora_expedicion'],
                'fe_regimen' => @$data['fe_regimen'],
                'ws_sent' => @$data['ws_sent'],
                'ws_response' => @$data['ws_response'],

                'status' => @$data['status'],
                    
                'updated_at' => current_time('mysql', 1)
                        ], 
                
                array('id' => $data['id']),
                
                ['%d', '%d', '%f', '%f', '%f',    
                    '%s','%s','%s','%s',    
                    '%d',     
                    '%s', '%s',     
                    '%d',   
                    '%s','%s','%s','%s','%s','%s',      
                    '%d',     
                    '%s',]);
    }
    
    /**
     * Deletes a record into annotations table
     * 
     * @global type $wpdb
     * @param int $id the ID to delete
     * @return type
     */
    public static function deleteInvoiceRecord($id) {
        global $wpdb;
        $invoices_table = $wpdb->prefix . 'invoices';
        
        return $wpdb->delete( $invoices_table, array( 
            'id' => $id
            ), array( '%d' ) );
    }
    
}