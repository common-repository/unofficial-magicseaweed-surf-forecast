<?php
/**
 * Plugin Name: Unofficial MagicSeaWeed Surf Forecast	
 * Plugin URI: http://www.maxssite.com/Unofficial-MagicSeaWeed-Surf-Forecast/
 * Description: This is a widget plugin powered by the http://www.magicseaweed.com surf forecast API
 * Version: 1.0
 * Author: Maxwell Schlatter
 * Author URI: http://www.maxssite.com
 * License: GPL2
 */

require_once('includes/jar.php');
require_once('includes/functions.php');
require_once('includes/admin_menu.php');

class Surf_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'surf_widget', // Base ID
			'MagicSeaWeed_Surf_Widget', // Name
			array( 'description' => __( 'This surf forecast widget displays break,swell,and wind data.', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */

	public function widget( $args, $instance ) {
		
		echo '<link href="/wp-content/plugins/unofficial-magicseaweed-surf-forecast/includes/surf_report.css" rel="stylesheet" type="text/css">';
		echo $args['before_widget'];
		echo '<div id="magicSeaweedSurfReport">';
			$title = apply_filters( 'widget_title', $instance['title'] );	
			if ( ! empty( $title ) ){
				echo $args['before_title'] . $title . $args['after_title'];
			}
			//Check MagicSeaWeedAPI opt-in condition.
			//Display of powered-by link is necessary by terms of API agreement.
			//Display MagicSeaWeedLogo
			if ((bool)get_option('use_data')){
				$params = array('swell'=>$instance['swell'],'break'=>$instance['break'],'wind'=>$instance['wind'],'swells'=>$instance['swells'],'barLabels'=>$instance['barLabels'],'timeLabels'=>$instance['timeLabels'],'arrows'=>$instance['arrows']);
				$report = basic_surf_report($instance['spot_id'], $params);	
				echo $report['text'];
			}else{
				echo('You must select for your widget to be powered by Magic Seaweed in order to query their API.');}
		echo '</div>';
		echo $args['after_widget'];
	}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];}
		else {
			$title = __( 'New Surf Spot', 'text_domain' );}
		if ( isset( $instance[ 'spot_id'] ) ) {
			$spot_id = $instance[ 'spot_id' ];}
		else {
			$spot_id = __( '0', 'text_domain' );}
		if ( isset( $instance[ 'break' ] ) ) {
			$break = $instance[ 'break' ];}
		else {
			$break = __('0', 'text_domain');}
		if ( isset( $instance[ 'swell' ] ) ) {
			$swell = $instance[ 'swell' ];}
		else {
			$swell = __('0', 'text_domain');}
		if ( isset( $instance[ 'wind' ] ) ) {
			$wind = $instance[ 'wind' ];}
		else {
			$wind = __('0', 'text_domain');}
		if ( isset( $instance[ 'swells' ] ) ) {
			$swells = $instance[ 'swells' ];}
		else {
			$swells = __('true', 'text_domain');}
		if ( isset( $instance[ 'timeLabels' ] ) ) {
			$timeLabels = $instance[ 'timeLabels' ];}
		else {
			$timeLabels = __('12', 'text_domain');}
		if ( isset( $instance[ 'arrows' ] ) ) {
			$arrows = $instance[ 'arrows' ];}
		else {
			$arrows = __('more', 'text_domain');}
		if ( isset( $instance[ 'barLabels' ] ) ) {
			$barLabels = $instance[ 'barLabels' ];}
		else {
			$barLabels = __('yes', 'text_domain');}

		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Spot Id:' ); ?></label> 		
		<input class="widefat" id="<?php echo $this->get_field_id( 'spot_id' ); ?>" name="<?php echo $this->get_field_name( 'spot_id' ); ?>" type="text" value="<?php echo esc_attr( $spot_id ); ?>" />		
		<h3 style="width:100%;"><?php _e('Graphs');?></h3>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Swell Graph' ); ?></label> 		
		<input id="<?php echo $this->get_field_id( 'swell' ); ?>" name="<?php echo $this->get_field_name( 'swell' ); ?>" type="checkbox" value=1
 <?php checked( '1', $swell ); ?>>			
		</br>
 		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Break Graph' ); ?></label> 		
		<input id="<?php echo $this->get_field_id( 'break' ); ?>" name="<?php echo $this->get_field_name( 'break' ); ?>" type="checkbox" value=1
 <?php checked( '1', $break ); ?>>			
		</br>
 		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Wind Graph' ); ?></label> 		
		<input id="<?php echo $this->get_field_id( 'wind' ); ?>" name="<?php echo $this->get_field_name( 'wind' ); ?>" type="checkbox" value=1
 <?php checked( '1', $wind ); ?>>			
		</br>
 		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Swell Breakdown' ); ?></label> 		
		<input id="<?php echo $this->get_field_id( 'swells' ); ?>" name="<?php echo $this->get_field_name( 'swells' ); ?>" type="checkbox" value=1
 <?php checked( '1', $swells ); ?>>
		</br>
		<h3 style="width:100%;"><?php _e('Detail');?></h3> 
	<div style="width:33%;float:left;">
<label style="text-align:center;" for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Time Axis:' ); ?></label></br>	
		<input type="radio" name="<?php echo $this->get_field_name( 'timeLabels' ); ?>" value="6" <?php checked( '6', $timeLabels );?>><?php _e('6 Hours');?><br>
		<input type="radio" name="<?php echo $this->get_field_name( 'timeLabels' ); ?>" value="12" <?php checked( '12', $timeLabels );?>><?php _e('12 Hours');?><br>
		<input type="radio" name="<?php echo $this->get_field_name( 'timeLabels' ); ?>" value="24" <?php checked( '24', $timeLabels );?>><?php _e('24 Hours');?><br>
	</div>
	<div style="width:33%;float:left;">
<label style="text-align:center;" for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Arrows:' ); ?></label> </br>	
		<input type="radio" name="<?php echo $this->get_field_name( 'arrows' ); ?>" value="less" <?php checked( 'less', $arrows );?>><?php _e('Less');?>;<br>
		<input type="radio" name="<?php echo $this->get_field_name( 'arrows' ); ?>" value="more" <?php checked( 'more', $arrows );?>><?php _e('More');?><br>
	</div>
	<div style="width:33%;float:left;">
<label style="text-align:center;" for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Bar Labels:' ); ?></label>	</br>
		<input type="radio" name="<?php echo $this->get_field_name( 'barLabels' ); ?>" value="yes" <?php checked( 'yes', $barLabels );?>><?php _e('Yes'); ?><br>
		<input type="radio" name="<?php echo $this->get_field_name( 'barLabels' ); ?>" value="no" <?php checked( 'no', $barLabels );?>><?php _e('No') ;?><br>
	</div>
	<div style="width:100%;height:10px;float:left;"></div>
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['spot_id'] = ( ! empty( $new_instance['spot_id'] ) ) ? strip_tags( 
$new_instance['spot_id'] ) : '';
		$instance['swell'] = ( ! empty( $new_instance['swell'] ) ) ? strip_tags( $new_instance['swell'] ) : '0';
		$instance['break'] = ( ! empty( $new_instance['break'] ) ) ? strip_tags( $new_instance['break'] ) : '0';
		$instance['wind'] = ( ! empty( $new_instance['wind'] ) ) ? strip_tags( $new_instance['wind'] ) : '0';
		$instance['swells'] = ( ! empty( $new_instance['swells'] ) ) ? strip_tags( $new_instance['swells'] ) : '0';
		$instance['timeLabels'] = ( ! empty( $new_instance['timeLabels'] ) ) ? strip_tags( $new_instance['timeLabels'] ) : '12';
		$instance['arrows'] = ( ! empty( $new_instance['arrows'] ) ) ? strip_tags( $new_instance['arrows'] ) : 'more';
		$instance['barLabels'] = ( ! empty( $new_instance['barLabels'] ) ) ? strip_tags( $new_instance['barLabels'] ) : 'yes';
		return $instance;
	}

} // class Foo_Widget

add_action( 'widgets_init', function(){
     register_widget( 'surf_widget' );
});
?>
