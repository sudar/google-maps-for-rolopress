<?php
/**
 * Google Maps Widget
 *
 * Create the actual widget
 *
 * @package RoloPress
 * @subpackage Widget
 */

// Get the address
function rolo_contact_address($contact_id) { // if contact get address
    if (!$contact_id) {
        return false;
    }

    $contact = get_post_meta($contact_id, 'rolo_contact');
    $contact = $contact[0];

    return $contact['rolo_contact_address'] . " " . rolo_get_term_list($contact_id, 'city') . " " . rolo_get_term_list($contact_id, 'state') . " " . rolo_get_term_list($contact_id, 'country')  . " " . rolo_get_term_list($contact_id, 'zip');
} 

function rolo_company_address($company_id) { // if company get address
    if (!$company_id) {
        return false;
    }

    $company = get_post_meta($company_id, 'rolo_company');
    $company = $contact[0];

    return $company['rolo_company_address'] . " " . rolo_get_term_list($company_id, 'city') . " " . rolo_get_term_list($company_id, 'state') . " " . rolo_get_term_list($company_id, 'country')  . " " . rolo_get_term_list($company_id, 'zip');
} 

/**
 * Map Widget
 */
class Rolo_Gmap extends WP_Widget {

    function Rolo_Gmap() {
        $widget_ops = array('classname' => 'rolo_gmap_widget', 'description' => __( "Display a Google Map for your Contact and Company addresses") );
        $this->WP_Widget('gmap', __('Google Maps'), $widget_ops);
    }

    function widget( $args, $instance ) {
        if (is_single() ) { // only display on single pages
            extract( $args );
            if ( rolo_type_is( 'contact' ) )  $address = ( rolo_contact_address(get_the_ID())) ;
            if ( rolo_type_is( 'company' ) )  $address = ( rolo_company_address(get_the_ID())) ;

            $title = apply_filters('widget_title', empty($instance['title']) ? __('', 'rolopress') : $instance['title']);
            echo $before_widget;

            if ( $title )
                echo "\n\t\t\t" . $before_title . $title . $after_title;

            echo '<img src="http://maps.google.com/maps/api/staticmap?markers='.$address.'&zoom='.$instance['zoom'].'&maptype='.$instance['maptype'].'&size='.$instance['width'].'x'.$instance['height'].'&sensor=false" />';

            echo $after_widget;


        }
    }



    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
        $instance['width'] = strip_tags(stripslashes($new_instance['width']));
        $instance['height'] = strip_tags(stripslashes($new_instance['height']));
        $instance['zoom'] = $new_instance['zoom'];
        $instance['maptype'] = $new_instance['maptype'];

        return $instance;
    }

    function form( $instance ) {
    //Defaults
        $instance = wp_parse_args( (array) $instance, array('account' => '', 'title' => '', 'width' => '250', 'height' => '250', 'zoom' => '15', 'maptype' => 'roadmap' ) );

        $title = esc_attr($instance['title']);
        $width = (int)$instance['width'];
        $height = (int) $instance['height'];
        $zoom = $instance['zoom'];
        $maptype = $instance['maptype'];

        ?>

<div style="float:left;width:98%;">
    <p><img class="rolo_widget_icon" src= <?php echo ROLOPRESS_IMAGES  . '/admin/rolopress-icon.gif' ?> />
		Display a Google Map.<br/><em>This is a Smart Widget, which means it only displays when it is supposed to: when you view an individual company or contact page.</em>
    </p>
</div>

<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'rolopress'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo $title ?>"/>
</label>
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e('Width (in px):', 'rolopress'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('width');?>" name="<?php echo $this->get_field_name('width');?>" type="number" value="<?php echo $width ?>"/>
</label>
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e('Height (in px):', 'rolopress'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('height');?>" name="<?php echo $this->get_field_name('height');?>" type="number" value="<?php echo $height ?>"/>
</label>
</p>
<p>
    <label for="<?php echo $this->get_field_id('zoom'); ?>"><?php _e('Zoom Level:', 'rolopress'); ?></label>
    <select id="<?php echo $this->get_field_id('zoom'); ?>" name="<?php echo $this->get_field_name('zoom');?>">
                <?php for ( $i = 0; $i <= 21; ++$i )
                    echo "<option value='$i' " . ( $zoom == $i ? "selected='selected'" : '' ) . ">$i</option>";?>
    </select>
</label>
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'maptype' ); ?>"><?php _e('Map Type:', 'rolopress'); ?></label>
    <select id="<?php echo $this->get_field_id( 'maptype' ); ?>" name="<?php echo $this->get_field_name( 'maptype' ); ?>" class="widefat" style="width:100%;">
        <option <?php if ( 'roadmap' == $instance['maptype'] ) echo 'selected="selected"'; ?>>roadmap</option>
        <option <?php if ( 'satellite' == $instance['maptype'] ) echo 'selected="selected"'; ?>>satellite</option>
        <option <?php if ( 'terrain' == $instance['maptype'] ) echo 'selected="selected"'; ?>>terrain</option>
        <option <?php if ( 'hybrid' == $instance['maptype'] ) echo 'selected="selected"'; ?>>hybrid</option>
    </select>
</select>
</p>

    <?php }


}

add_action( 'widgets_init', 'rolo_gmap_init' );
function rolo_gmap_init() {
    register_widget('Rolo_Gmap');
}