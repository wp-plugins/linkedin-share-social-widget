<?php
/*
Plugin Name: LinkedIn Share Social Widget
Plugin URI: http://www.marijnrongen.com/wordpress-plugins/linkedin-share-social-widget/
Description: Place a LinkedIn Share button as a widget and/or shortcode, enables visitors to share the page via LinkedIn.
Version: 1.0
Author: Marijn Rongen
Author URI: http://www.marijnrongen.com
*/

class MR_LinkedIn_Share_Widget extends WP_Widget {
	function MR_LinkedIn_Share_Widget() {
		$widget_ops = array( 'classname' => 'MR_LinkedIn_Share_Widget', 'description' => 'Place a LinkedIn Share button as a widget.' );
		$control_ops = array( 'id_base' => 'mr-linkedin-share-widget' );
		$this->WP_Widget( 'mr-linkedin-share-widget', 'LinkedIn Share Social Widget', $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance) {
		extract( $args );
		$layout = empty($instance['layout']) ? 'none' : $instance['layout'];
		echo $before_widget;
		echo "\n	<script src=\"http://platform.linkedin.com/in.js\" type=\"text/javascript\"></script><script type=\"IN/Share\"";
		if (!empty($instance['url'])) {
			echo ' data-url="'.$instance['url'].'"';
		}
		if ($instance['layout'] != 'none') {
			echo ' data-counter="'.$instance['layout'].'"';
		}
		echo '></script>';
		echo $after_widget;
	}
	
	function shortcode_handler( $atts, $content=null, $code="" ) {
		extract( shortcode_atts( array(
			'mode' => '',
			'url' => ''
		), $atts ) );
		if ($mode != 'top' && $mode != 'right') {
			$mode = '';
		}
		if ($url != '') {
			$url = urlencode($url);
		} else {
			$url = get_permalink();
		}
		$retval = '<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script><script type="IN/Share" data-url="'.$url.'"';
		if ($mode != '') {
			$retval .= ' data-counter="'.$mode.'"';
		}
		$retval .= '></script>';
		return $retval;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['layout'] = $new_instance['layout'];
		$instance['url'] = strip_tags($new_instance['url']);
		return $instance;
	}
	
	function form($instance) {
		$instance = wp_parse_args((array) $instance, array( 'layout' => 'none', 'url' => ''));
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'layout' ); ?>">Button style:</label>
			<select id="<?php echo $this->get_field_id( 'layout' ); ?>" name="<?php echo $this->get_field_name( 'layout' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( "right" == $instance['layout'] ) echo 'selected="selected"'; ?> value="right">Horizontal count</option>
				<option <?php if ( "top" == $instance['layout'] ) echo 'selected="selected"'; ?> value="top">Vertical count</option>
				<option <?php if ( "none" == $instance['layout'] ) echo 'selected="selected"'; ?> value="none">No count</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'url' ); ?>">URL to share (<b>Optional</b>, leave empty for the URL of the page the button is on):</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" value="<?php echo $instance['url']; ?>" />
		</p>	
		<?php
	}
}
add_shortcode( 'share', array('MR_LinkedIn_Share_Widget', 'shortcode_handler') );
add_action('widgets_init', create_function('', 'return register_widget("MR_LinkedIn_Share_Widget");'));
?>