<?php
/*
Plugin Name: Raneri Purge CPT
Plugin URI: http://www.raneri.it
Description: This plugin cleans the database from all posts and connected meta data for a given custom post type
Version: 1.0
Author: Riccardo Raneri
Author URI: http://www.raneri.it
*/

if(!defined('ABSPATH')) die(__('You are not allowed to call this page directly.'));
 
if ( ! class_exists( 'Raneri_Purge_CPT' ) ) {
    class Raneri_Purge_CPT {

        public function __construct() {
            load_plugin_textdomain( 'raneri-purge-cpt', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

            add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
            add_action( 'admin_enqueue_scripts', array($this, 'js_scripts') );
            add_action( 'wp_ajax_raneri_purge_cpt_posts_summary', array($this, 'ajax_posts_summary') );
            add_action( 'wp_ajax_raneri_purge_cpt_dopurge', array($this, 'ajax_dopurge') );
        }

        function js_scripts(){
            wp_enqueue_script( 'raneri-purge-cpt-common-js', plugin_dir_url( __FILE__ ) . 'js/raneri-purge-cpt-common.js', array('jquery') );
        }

        function plugin_menu() {
            add_options_page( __('Purge CPT', 'raneri-purge-cpt'), __('Purge CPT', 'raneri-purge-cpt'), 'manage_options', 'raneri-cpt-options', array( $this, 'plugin_options' ) );
        }

        function plugin_options() {
            global $wpdb;

            if ( !current_user_can( 'manage_options' ) )  {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
            }
            echo '<div class="wrap">';
            echo '  <h2>' . __('Purge CPT', 'raneri-purge-cpt') . '</h2>';
            echo '  <p>' . __('Choose the Custom Post Type you want to be deleted from the database:', 'raneri-purge-cpt') . '</p>';


            $post_types_native = get_post_types( array( '_builtin' => true ) );
            $sql = "
                SELECT post_type FROM " .$wpdb->posts. " GROUP BY post_type ORDER BY post_type ASC;
            ";
            $post_types_all = $wpdb->get_results( $sql );

            echo '  <select id="post_type_selector" onchange="raneri_cpt_load_cpt_details( jQuery(this).val() );">';
            echo '    <option value="">' .__("Select"). ' ></option>';
            foreach( $post_types_all as $cpt ){
                if( !in_array($cpt->post_type, $post_types_native) ){
                    echo '    <option value="' .$cpt->post_type. '">' .$cpt->post_type. '</option>';
                }
            }
            echo '  </select>';
            echo '  <img id="raneri-purge-cpt-spinner" src="/wp-admin/images/wpspin_light.gif" alt="Loading..." style="display:none; vertical-align:middle" />';
            echo '  <p class="description">' . __('Posts, Pages, Revisions, Attachments and Nav_menu_items are not shown here because they are part of the native set of post types.', 'raneri-purge-cpt') . '</p>';
            echo '  <div id="summary_placeholder" />';
            echo '</div>';
        }

        function ajax_posts_summary() {
            global $wpdb;
            $sql = "SELECT * FROM " .$wpdb->posts. " WHERE post_type = '" . $_POST['post_type'] . "'";
            $posts = $wpdb->get_results( $wpdb->prepare($sql) );

            $total_posts = count($posts);
            $drafts = 0;
            $published = 0;

            foreach($posts as $onePost){
                if( $onePost->post_status == 'publish' ){
                    $published++;
                }
                if( $onePost->post_status == 'draft' ){
                    $drafts++;
                }
            }

            echo '<p>' . __("This is a summary of the current post count in the database, for this Custom Post Type:", 'raneri-purge-cpt') . '</p>';
            echo '<p><strong>' . $_POST['post_type'] . '</strong></p>';
            echo '<ul style="background:#fff; border:1px solid #000; padding:10px;">';
            echo '<li>Total posts: ' . $total_posts . "</li>";
            echo '<li>Published: ' . $published . "</li>";
            echo '<li>Drafts: ' . $drafts . "</li>";
            echo '</ul>';

            $question = str_replace("'", "\'",  __( 'WARNING: Every post of the chosen custom post type will be ERASED from the database, including the connected metadata. Make sure you have an updated backup copy of the database before proceed. Are you sure to continue?', 'raneri-purge-cpt' ) );
            echo '<input id="btn_dopurge" type="button" class="button button-primary button-large" value="' . __('Purge this CPT', 'raneri-purge-cpt') . '" onclick="raneri_cpt_purge(\'' .$_POST['post_type']. '\', \'' .$question. '\')" />';

            exit();
        }

        function ajax_dopurge() {
            global $wpdb;
            
            $sql = "
                DELETE FROM " .$wpdb->postmeta. " WHERE post_id IN
                    (SELECT ID FROM " .$wpdb->posts. " WHERE post_type = '" .$_POST['post_type']. "');
            ";
            $wpdb->query( $wpdb->prepare( $sql ) );

            $sql = "
                DELETE FROM " .$wpdb->posts. " WHERE post_type = '" .$_POST['post_type']. "';
            ";
            $wpdb->query( $wpdb->prepare( $sql ) );

            echo '<p><strong>' .$post_type . " " . __("posts have been successful deleted from the database!", 'raneri-purge-cpt'). '</strong> ' .__("Please take note that this function actually cleans the data, but it doesn't cancel the registration of the Custom Post Type. If you want to de-register it, you have to do it yourself.", 'raneri-purge-cpt'). '</p>';
            exit();
        }


    } // class
}

$raneri_purge_cpt = new Raneri_Purge_CPT();