<?php
/**
 * Plugin Name:   Ratburger Second Life Widget
 * Plugin URI:    https://www.ratburger.org/
 * Description:   Shows users present in Ratburger Second Life Clubhouse.
 * Version:       1.0
 * Author:        John Walker
 * Author URI:    https://www.fourmilab.ch/
 */

class RB_second_life_widget extends WP_Widget {

  // Define the widget name and description
  
  public function __construct() {
    $widget_options = array (
        'classname' => 'RB_second_life_widget',
        'description' => 'Shows users in the Ratburger Second Life clubhouse.');
    parent::__construct('RB_second_life_widget',
        'RB Second Life Widget', $widget_options );
  }

  // Create the widget output
  
  public function widget($args, $instance) {
    if (is_user_logged_in()) {
        $first = TRUE;
        $title = apply_filters('widget_title', $instance['title']);

        foreach (file($instance['camfile']) as $l) {
            if ($first) {
                $first = FALSE;
                echo $args['before_widget'] .
                     $args['before_title'] .
                     "<a title=\"Users currently in Second Life clubhouse.\" " .
                        "href=\"/index.php/second-life-clubhouse/\">" .
                     $title .
                     "</a>" .
                     $args['after_title'];
                echo "<p>\n";
            }
            echo sanitize_text_field($l) . "<br />\n";
        }
        if (!$first) {
            echo "</p>\n";
            echo $args['after_widget'];
        }
    }
  }

  // Create the admin area widget settings form

  public function form($instance) {
    $title = !empty($instance['title']) ? $instance['title'] : 'Second Life';
    $camfile = !empty($instance['camfile']) ? $instance['camfile'] :
        '/server/var/SecurityCamera/SLcamera.txt';
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
      <input type="text" id="<?php echo $this->get_field_id('title'); ?>"
          name="<?php echo $this->get_field_name('title'); ?>"
          value="<?php echo esc_attr($title); ?>" /><br />
      <label for="<?php echo $this->get_field_id('camfile'); ?>">File:</label>
      <input type="text" id="<?php echo $this->get_field_id('camfile'); ?>"
          name="<?php echo $this->get_field_name('camfile'); ?>"
          value="<?php echo esc_attr($camfile); ?>" />
    </p><?php
  }

  // Apply settings to the widget instance
  
  public function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['camfile'] = strip_tags($new_instance['camfile']);
//RB_dumpvar("Update", $instance);
    return $instance;
  }
}

// Register the widget

function RB_register_second_life_widget() { 
    register_widget('RB_second_life_widget');
}
add_action('widgets_init', 'RB_register_second_life_widget');


?>
