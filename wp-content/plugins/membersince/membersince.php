<?php
/*
Plugin Name: Member Since
Plugin URI:  http://www.wpbeginner.com
Description: Adds registration date on edit user profile screen. 
Version:     1.1
Author:      WPBeginner, Chef@Ratburger.org
*/
 
 
namespace ShowMemberSince;
add_action( 'plugins_loaded', 'ShowMemberSince\init' );
/**
 * Adding needed action hooks
*/
function init(){
  foreach( array( 'show_user_profile', 'edit_user_profile' ) as $hook )
        add_action( $hook, 'ShowMemberSince\add_custom_user_profile_fields', 10, 1 );
}
/**
 * Output table
 * @param object $user User object
 */
function add_custom_user_profile_fields( $user ){
    $table =
    '<h3>%1$s</h3>
    <table class="form-table">
        <tr>
            <th>
                %1$s
            </th>
            <td>
                <p>Member since: %2$s</p>
            </td>
        </tr>
    </table>';
    $udata = get_userdata( $user->ID );
    $registered = $udata->user_registered;
    printf(
        $table,
        'Registered',
        date( "Y-m-d", strtotime( $registered ))
    );
}
?>
