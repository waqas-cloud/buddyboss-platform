<?php

define ('BP_PS_FORM', 'bp_profile_search');

include 'bps-admin.php';
include 'bps-directory.php';
include 'bps-fields.php';
include 'bps-form.php';
include 'bps-search.php';
include 'bps-templates47.php';
include 'bps-xprofile.php';
include 'bps-widget.php';

function bp_ps_meta ( $form ) {
	static $options;
	if (isset ($options[$form]))  return $options[$form];

	$default = array ();
	$default['field_code'] = array ();
	$default['field_label'] = array ();
	$default['field_desc'] = array ();
	$default['field_mode'] = array ();
	$default['method'] = 'POST';
	$default['action'] = 0;
	$default['directory'] = 'No';

	$meta = get_post_meta ($form);
	$options[$form] = isset ($meta['bp_ps_options'])? unserialize ($meta['bp_ps_options'][0]): $default;

	return $options[$form];
}

add_action ('init', 'bp_profile_search_register_post_type');
function bp_profile_search_register_post_type (){
	$args = array (
		'labels' => array (
			'name' => __('Profile Search Forms', 'buddyboss'),
			'singular_name' => __('Profile Search Form', 'buddyboss'),
			'all_items' => __('Profile Search', 'buddyboss'),
			'add_new' => __('Add New', 'buddyboss'),
			'add_new_item' => __('Add New Form', 'buddyboss'),
			'edit_item' => __('Profile Search', 'buddyboss'),
			'not_found' => __('No forms found.', 'buddyboss'),
			'not_found_in_trash' => __('No forms found in Trash.', 'buddyboss'),
		),
		'show_ui' => true,
		'show_in_menu' => 'users.php',
		'supports' => array ( 'title' ),
		'rewrite' => false,
		'map_meta_cap' => true,
		'capability_type' => 'bp_ps_form',
		'query_var' => false,
	);

	register_post_type ( 'bp_ps_form', $args );

	$form_caps = array (
		'administrator' => array (
            'edit_bp_ps_forms',
			'edit_others_bp_ps_forms',
			'edit_published_bp_ps_forms',
		)
	);

	$form_caps = apply_filters ( 'bp_ps_form_caps', $form_caps );
	foreach ( $form_caps as $key => $caps ) {
		$role = get_role ($key);
		foreach ( $caps as $cap ) {
			if (! $role->has_cap ( $cap ) ) {
                $role->add_cap ($cap);
            }
        }
	}
}

function bp_profile_search_prevent_delete () {
    global $wp_roles;
    $wp_roles->remove_cap( 'administrator', 'delete_bp_ps_forms' );
    $wp_roles->remove_cap( 'administrator', 'delete_published_bp_ps_forms' );
    $wp_roles->remove_cap( 'editor', 'delete_bp_ps_forms' );
    $wp_roles->remove_cap( 'editor', 'delete_published_bp_ps_forms' );
}

add_action( 'init', 'bp_profile_search_prevent_delete' );

/******* post.php, post-new.php */

add_filter ( 'post_updated_messages', 'bp_profile_search_form_updated_messages');
function bp_profile_search_form_updated_messages ( $messages ) {
	$messages['bp_ps_form'] = array (
		 0 => 'message 0',
		 1 => __('Form updated.', 'buddyboss'),
		 2 => 'message 2',
		 3 => 'message 3',
		 4 => 'message 4',
		 5 => 'message 5',
		 6 => __('Form created.', 'buddyboss'),
		 7 => 'message 7',
		 8 => 'message 8',
		 9 => 'message 9',
		10 => 'message 10',
	);
	return $messages;
}

/******* common */

function bp_profile_search_screen () {
	global $current_screen;
	return isset ( $current_screen->post_type ) && $current_screen->post_type == 'bp_ps_form';
}

add_action ( 'admin_head', 'bp_profile_search_admin_head' );
function bp_profile_search_admin_head () {
	global $current_screen;
	if ( !bp_profile_search_screen () )  return;

	if ( $current_screen->id == 'bp_ps_form')  _bp_profile_search_admin_js ();
    ?>
	<style type="text/css">
		.search-box, .actions, .view-switch {display: none;}
		.bulkactions {display: block;}
		#minor-publishing {display: none;}
		.fixed .column-fields {width: 8%;}
		.fixed .column-template {width: 15%;}
		.fixed .column-action {width: 12%;}
		.fixed .column-directory {width: 12%;}
		.fixed .column-widget {width: 12%;}
		.fixed .column-shortcode {width: 15%;}
		.bp_ps_col1 {display: inline-block; width: 2%; cursor: move;}
		.bp_ps_col2 {display: inline-block; width: 20%;}
		.bp_ps_col3 {display: inline-block; width: 16%;}
		.bp_ps_col4 {display: inline-block; width: 32%;}
		.bp_ps_col5 {display: inline-block; width: 16%;}
		a.delete {color: #aa0000;}
		a.delete:hover {color: #ff0000;}
	</style>
    <?php
}

function _bp_profile_search_admin_js () {
	$translations = array (
		'drag' => __('drag to reorder fields', 'buddyboss'),
		'field' => __('select field', 'buddyboss'),
		'remove' => __('Remove', 'buddyboss'),
	);
	wp_enqueue_script ( 'bp-profile-search-admin', buddypress()->plugin_url . 'bp-core/profile-search/bp-ps-admin.js', array ('jquery-ui-sortable'), bp_get_version() );
	wp_localize_script ( 'bp-profile-search-admin', 'bp_ps_strings', $translations );
}


function bp_profile_search_main_form () {
    return (int) bp_get_option( 'bp_profile_search_main_form' );
}

function bp_profile_search_add_main_form () {
    $post_args = array(
        'post_title'    => __( 'Search Members', 'buddyboss' ),
        'post_type'     => 'bp_ps_form',
        'post_status'   => 'publish',
    );
    
    $post_id = wp_insert_post( $post_args, true );
    if ( !is_wp_error( $post_id ) ) {
        bp_update_option ( 'bp_profile_search_main_form', $post_id );
    }
}
