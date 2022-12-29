<?php
/**
* Plugin Name: [Forminator Pro] - Forminator insert into database after form submission 
* Description: Charge dealer dependant on the amount of booking he made 
* Author: Matthias McCarthy
* License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// No need to do anything if the request is via WP-CLI.
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	return;
}

if ( ! class_exists( 'Forminator_Post_Insert_External_table' ) ) {
    
    class Forminator_Post_Insert_External_table {

        // Source Form
        //Enter your form id and chosen field
        private $s_form_id = your form id;
        private $t_number_field = 'Field of choice';

        private static $_instance = null;

        public static function get_instance() {

            if( is_null( self::$_instance ) ){
                self::$_instance = new Forminator_Post_Insert_External_table();
            }

            return self::$_instance; 
        }

        private function __construct() {    
            $this->init();
        }

        public function init(){
            add_action( 'forminator_form_after_save_entry', array( $this, 'insert_into_table' ) );
        }

        public function insert_into_table( $form_id ){           
            
             if( $this->s_form_id != $form_id ){
                return;
             }
            
             $entries = Forminator_API::get_entries( $form_id );

             global $wpdb;

            
            if( is_array( $entries ) ){
                $entry = $entries[0];
                if( $entry instanceof Forminator_Form_Entry_Model ){
                    $code = $entry->get_meta( $this->t_number_field );
                }
            }

            if( ! isset( $code ) ){
                return;
            }
            //Enter the table and value you need for this 
            $wpdb->query("INSERT INTO tablename (value1, value2, value3) VALUES('value1', 'value2','value3' )"); 

            wp_send_json_success( 
                array(
                    'success' => true,
                    'message' => "Your Booking has been Succesfully made.  ",
                    'behav'   => 'behaviour-thankyou'
                )
            );

        }

    }

    add_action( 'plugins_loaded', function(){
        return Forminator_Post_Insert_External_table::get_instance();
    });

}