<?php
/** Step 2 (from text above). */
add_action( 'admin_menu', 'surf_forecast_menu' );

/** Step 1. */
function surf_forecast_menu() {
	add_plugins_page( 'Surf Forecast Options', 'Surf Forecast', 'manage_options', 'surf-forecast-options', 'surf_forecast_options' );
}

/** Step 3. */
function surf_forecast_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	    // variables for the field and option names 
		
	$opt_name_1 = 'api_key';
    $hidden_field_1 = 'key_hidden';
    $data_field_1 = 'key';
	$key_vars=array($opt_name_1,$hidden_field_1,$data_field_1);
	
	$opt_name_2 = 'api_secret';
    $hidden_field_2 = 'secret_hidden';
    $data_field_2 = 'secret';
	$secret_vars = array($opt_name_2,$hidden_field_2,$data_field_2);
	
	$opt_name_3 = 'use_data';
    $hidden_field_3 = 'use_hidden';
    $data_field_3 = 'use';
	$use_vars = array($opt_name_3,$hidden_field_3,$data_field_3);
	
	$options = array('key'=>$key_vars,'secret'=>$secret_vars,'use'=>$use_vars);
	
	
    // Read in existing option value from database
	$opt_val=array();
	foreach ($options as $option){
    $opt_val[$option[0]] = get_option( $option[0] );
    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $option[1] ]) && $_POST[ $option[1] ] == 'Y' ) {
        // Read their posted value
        $opt_val[$option[0]] = $_POST[ $option[2] ];

		 // Put an settings updated message on the screen
		if($_POST[ $option[2]]!=get_option($option[0])){
		?>
		<div class="updated"><p><strong><?php _e('setting '.$option[0].' saved', 'menu-test' ) ; ?></strong></p></div>
		<?php	
        // Save the posted value in the database
		if($option[0]=='api_secret'){
		update_option( $option[0], base64__encode($opt_val[$option[0]]));
		}else{
		update_option( $option[0], $opt_val[$option[0]] );
		}
		}
		 
	}
	}


    // Now display the settings editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'Msw Surf Forecast Plugin Settings', 'menu-test' ) . "</h2>";

    // settings form
    
    ?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_1; ?>" value="Y">
<p><?php _e("API_KEY:", 'menu-test' ); ?> 
<input type="text" name="<?php echo $data_field_1; ?>" value="<?php echo $opt_val[$opt_name_1]; ?>" size="20">
</p><hr />

<input type="hidden" name="<?php echo $hidden_field_2; ?>" value="Y">
<p><?php _e("API_SECRET:", 'menu-test' ); ?> 
<input type="text" name="<?php echo $data_field_2; ?>" value="<?php echo $opt_val[$opt_name_2]; ?>" size="20">
</p><hr />

<input type="hidden" name="<?php echo $hidden_field_3; ?>" value="Y">
<p><?php _e("Use MSW API Data", 'menu-test' ); ?> 
<input type="checkbox" name="<?php echo $data_field_3; ?>"  value=1
<?php checked( '1', $opt_val[$opt_name_3] ); ?>>
</p>
<p><?php _e("*Note: Each widget will display a small powered-by MSW logo.", 'menu-test' ); ?> </p>


<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>

<?php
}
?>