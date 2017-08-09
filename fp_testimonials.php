<?php
/*
Plugin Name: FP Testimonials
Plugin URI: http://flourishpixel.com/
Description: FP Testimonials allows you to create testimonials and display the associated testimonials on your sidebar using widgets. You can use shortcode ([textimonial limit=5]) for showing in page, here 5 is for example, you can place yours, if you don't want to place limit then just place [testimonial], it will display 10 posts by default.
Author: Moshiur Rahman Mehedi
Version: 1.0.7
Author URI: http://www.flourishpixel.com/
*/

/* Register a Custom Post Type (Testimonial) */
wp_enqueue_script('jquery');
wp_enqueue_script('my_script', plugins_url('/js/jquery.bxSlider.min.js',__FILE__) );
wp_enqueue_style('testi_css', plugins_url('/css/testimonial.css',__FILE__) );
add_action('init', 'testimonial_init');
function testimonial_init() {
	$labels = array(
		'name' => _x('FP Testimonials', 'post type general name'),
		'singular_name' => _x('Testimonial', 'post type singular name'),
		'add_new' => _x('Add New', 'testimonial'),
		'add_new_item' => __('Add New Testimonial'),
		'edit_item' => __('Edit Testimonial'),
		'new_item' => __('New Testimonial'),
		'view_item' => __('View Testimonial'),
		'search_items' => __('Search Testimonials'),
		'not_found' => __('No Testimonials found yet.'),
		'not_found_in_trash' => __('No Testimonials found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => 'FP Testimonials'
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => null,
		//'menu_icon' => plugins_url( '', __FILE__ ).'/images/testimonial.png', // 16px16
		'supports' => array('thumbnail','title', 'editor')
	); 
	register_post_type('testimonial', $args);
}

/* Update Testimonial Messages */
add_filter('post_updated_messages', 'testimonial_updated_messages');
function testimonial_updated_messages($messages) {
	global $post, $post_ID;
	$messages['testimonial'] = array(
		0 => '',
		1 => sprintf(__('Testimonial updated.'), esc_url(get_permalink($post_ID))),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Testimonial updated.'),
		5 => isset($_GET['revision']) ? sprintf(__('Testimonial restored to revision from %s'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
		6 => sprintf(__('Testimonial published.'), esc_url(get_permalink($post_ID))),
		7 => __('Testimonial saved.'),
		8 => sprintf(__('Testimonial submitted.'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
		9 => sprintf(__('Testimonial scheduled for: <strong>%1$s</strong>. '), date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
		10 => sprintf(__('Testimonial draft updated.'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
	);
	return $messages;
}

/* Update Testimonial Help */
add_action('contextual_help', 'testimonial_help_text', 10, 3);
function testimonial_help_text($contextual_help, $screen_id, $screen) {
	if ('testimonial' == $screen->id) {
		$contextual_help =
		'<p>' . __('Things to remember when adding a Testimonial:') . '</p>' .
		'<ul>' .
		'<li>' . __('Give the testimonial a title. The title will be used as the testimonial\'s headline.') . '</li>' .
		'<li>' . __('Attach a Featured Image for the person who gives Testimonial.') . '</li>' .
		'<li>' . __('Enter text into the Visual or HTML area. The text will appear within each Testimonial during transitions.') . '</li>' .
		'</ul>';
	}
	elseif ('edit-testimonial' == $screen->id) {
		$contextual_help = '<p>' . __('A list of all Testimonial appears below. To edit a Testimonial, click on the Testimonial\'s title.') . '</p>';
	}
	return $contextual_help;
}

// Styling for the testimonial post type icon
add_action( 'admin_head', 'testimonial_icons' );
function testimonial_icons() {
	$path=plugins_url( '', __FILE__ );
    ?>
<style type="text/css" media="screen">
#menu-posts-testimonial .wp-menu-image {
 background: url(<?php echo $path; ?>/images/testimonial.png) no-repeat 6px 6px !important;
}
#menu-posts-testimonial:hover .wp-menu-image, #menu-posts-testimonial.wp-has-current-submenu .wp-menu-image {
	opacity:0.6 !important;
}
#icon-edit.icon32-posts-testimonial {
background: url(<?php echo $path; ?>/images/testimonial-icon.png) no-repeat 0px 2px;
}
p.fp_label input.custom {
	width:24%;
}
p.fp_label label{
	font-size:11px;
}
</style>
    <?php }


class TestimonailWidget extends WP_Widget
{
  function TestimonailWidget()
  {
    $widget_ops = array('classname' => 'TestimonailWidget', 'description' => 'Displays testimonials with effects' );
    $this->WP_Widget('TestimonailWidget', 'FP Testimonial', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '','slide_title' => 'testimonial', 'speed' =>'500', 'direction' =>'horizontal', 'control' =>'true', 'delay'=>'3000','autohover'=>'true', 'showtext'=>'excerpt', 'title_link'=>'true', 'pager'=>'true', 'play'=>'true', 'limit'=>'5', 'show_image'=>'yes', 'image_width'=>'60' ) );
    $title = $instance['title'];
	$slide_title = $instance['slide_title'];
	$speed = $instance['speed'];
	$direction = $instance['direction'];
	$control = $instance['control'];
	$delay = $instance['delay'];
	$autohover = $instance['autohover'];
	$pager = $instance['pager'];
	$play = $instance['play'];
	$showtext = $instance['showtext'];
	$title_link = $instance['title_link'];
	$limit = $instance['limit'];
	$show_image = $instance['show_image'];
	$image_width = $instance['image_width'];
?>
<p class="fp_label">
  <label for="<?php echo $this->get_field_id('title'); ?>">Title:
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
  </label>
</p>
<p class="fp_label">
  <label for="<?php echo $this->get_field_id('slide_title'); ?>">Slide ID (Doesn't allow space):
    <input class="widefat" id="<?php echo $this->get_field_id('slide_title'); ?>" name="<?php echo $this->get_field_name('slide_title'); ?>" type="text" value="<?php echo attribute_escape($slide_title); ?>" />
  </label>
</p>
<p class="fp_label">
  <label for="<?php echo $this->get_field_id('speed'); ?>">Speed:
    <input class="custom" id="<?php echo $this->get_field_id('speed'); ?>" name="<?php echo $this->get_field_name('speed'); ?>" type="text" value="<?php echo attribute_escape($speed); ?>" />
  </label>
  <label for="<?php echo $this->get_field_id('delay'); ?>">Delay Time:
    <input class="custom" id="<?php echo $this->get_field_id('delay'); ?>" name="<?php echo $this->get_field_name('delay'); ?>" type="text" value="<?php echo attribute_escape($delay); ?>" />
  </label>
</p>
<p class="fp_label">
  <label for="<?php echo $this->get_field_id('showtext'); ?>">Show:
    <select name="<?php echo $this->get_field_name('showtext'); ?>" id="<?php echo $this->get_field_id('showtext'); ?>">
      <option value="full_text" <?php if(attribute_escape($showtext) == 'full_text'){echo 'selected';}?>>Full Text</option>
      <option value="excerpt" <?php if(attribute_escape($showtext) == 'excerpt'){echo 'selected';}?>>Excerpt</option>
    </select>
  </label>
  <label for="<?php echo $this->get_field_id('play'); ?>">Auto Play:
    <select name="<?php echo $this->get_field_name('play'); ?>" id="<?php echo $this->get_field_id('play'); ?>">
      <option value="true" <?php if(attribute_escape($play) == 'true'){echo 'selected';}?>>true</option>
      <option value="false" <?php if(attribute_escape($play) == 'false'){echo 'selected';}?>>false</option>
    </select>
  </label>
  </p>
  <p class="fp_label">
  <label for="<?php echo $this->get_field_id('title_link'); ?>">Link Title:
    <select name="<?php echo $this->get_field_name('title_link'); ?>" id="<?php echo $this->get_field_id('title_link'); ?>">
      <option value="true" <?php if(attribute_escape($title_link) == 'true'){echo 'selected';}?>>true</option>
      <option value="false" <?php if(attribute_escape($title_link) == 'false'){echo 'selected';}?>>false</option>
    </select>
  </label>
  <label for="<?php echo $this->get_field_id('limit'); ?>">Slide Limit:
    <select name="<?php echo $this->get_field_name('limit'); ?>" id="<?php echo $this->get_field_id('limit'); ?>">
      <option value="2" <?php if(attribute_escape($limit) == '2'){echo 'selected';}?>>2</option>
      <option value="3" <?php if(attribute_escape($limit) == '3'){echo 'selected';}?>>3</option>
      <option value="4" <?php if(attribute_escape($limit) == '4'){echo 'selected';}?>>4</option>
      <option value="5" <?php if(attribute_escape($limit) == '5'){echo 'selected';}?>>5</option>
      <option value="6" <?php if(attribute_escape($limit) == '6'){echo 'selected';}?>>6</option>
      <option value="7" <?php if(attribute_escape($limit) == '7'){echo 'selected';}?>>7</option>
      <option value="8" <?php if(attribute_escape($limit) == '8'){echo 'selected';}?>>8</option>
      <option value="9" <?php if(attribute_escape($limit) == '9'){echo 'selected';}?>>9</option>
      <option value="10" <?php if(attribute_escape($limit) == '10'){echo 'selected';}?>>10</option>
      <option value="15" <?php if(attribute_escape($limit) == '15'){echo 'selected';}?>>15</option>
      <option value="20" <?php if(attribute_escape($limit) == '20'){echo 'selected';}?>>20</option>
    </select>
  </label>
</p>
<p class="fp_label">
  <label for="<?php echo $this->get_field_id('direction'); ?>">Direction:
    <select name="<?php echo $this->get_field_name('direction'); ?>" id="<?php echo $this->get_field_id('direction'); ?>">
      <option value="horizontal" <?php if(attribute_escape($direction) == 'horizontal'){echo 'selected';}?>>horizontal</option>
      <option value="vertical" <?php if(attribute_escape($direction) == 'vertical'){echo 'selected';}?>>vertical</option>
      <option value="fade" <?php if(attribute_escape($direction) == 'fade'){echo 'selected';}?>>fade</option>
    </select>
  </label>
  <label for="<?php echo $this->get_field_id('pager'); ?>">Pager:
    <select name="<?php echo $this->get_field_name('pager'); ?>" id="<?php echo $this->get_field_id('pager'); ?>">
      <option value="true" <?php if(attribute_escape($pager) == 'true'){echo 'selected';}?>>true</option>
      <option value="false" <?php if(attribute_escape($pager) == 'false'){echo 'selected';}?>>false</option>
    </select>
  </label>
</p>
  <p class="fp_label">
  <label for="<?php echo $this->get_field_id('control'); ?>">Control:
    <select name="<?php echo $this->get_field_name('control'); ?>" id="<?php echo $this->get_field_id('control'); ?>">
      <option value="true" <?php if(attribute_escape($control) == 'true'){echo 'selected';}?>>true</option>
      <option value="false" <?php if(attribute_escape($control) == 'false'){echo 'selected';}?>>false</option>
    </select>
  </label>
  <label for="<?php echo $this->get_field_id('autohover'); ?>">Hover:
    <select name="<?php echo $this->get_field_name('autohover'); ?>" id="<?php echo $this->get_field_id('autohover'); ?>">
      <option value="true" <?php if(attribute_escape($autohover) == 'true'){echo 'selected';}?>>true</option>
      <option value="false" <?php if(attribute_escape($autohover) == 'false'){echo 'selected';}?>>false</option>
    </select>
  </label>
  </p>
  <p class="fp_label">

  <label for="<?php echo $this->get_field_id('show_image'); ?>">Image:
    <select name="<?php echo $this->get_field_name('show_image'); ?>" id="<?php echo $this->get_field_id('show_image'); ?>">
      <option value="yes" <?php if(attribute_escape($show_image) == 'yes'){echo 'selected';}?>>Yes</option>
      <option value="no" <?php if(attribute_escape($show_image) == 'no'){echo 'selected';}?>>No</option>
    </select>
  </label>
    <label for="<?php echo $this->get_field_id('image_width'); ?>">Image size:
    <input class="custom" id="<?php echo $this->get_field_id('image_width'); ?>" name="<?php echo $this->get_field_name('image_width'); ?>" type="text" value="<?php echo attribute_escape($image_width); ?>" />px
  </label>
  </p>
  

<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['slide_title'] = $new_instance['slide_title'];
	$instance['speed'] = $new_instance['speed'];
	$instance['direction'] = $new_instance['direction'];
	$instance['control'] = $new_instance['control'];
	$instance['pager'] = $new_instance['pager'];
	$instance['play'] = $new_instance['play'];
	$instance['delay'] = $new_instance['delay'];
	$instance['autohover'] = $new_instance['autohover'];
	$instance['showtext'] = $new_instance['showtext'];
	$instance['title_link'] = $new_instance['title_link'];
	$instance['limit'] = $new_instance['limit'];
	$instance['show_image'] = $new_instance['show_image'];
	$instance['image_width'] = $new_instance['image_width'];
    return $instance;
	
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
	$slide_title = empty($instance['slide_title']) ? ' ' : apply_filters('widget_slide_title', $instance['slide_title']);
	$speed = empty($instance['speed']) ? ' ' : apply_filters('widget_speed', $instance['speed']);
	$direction = empty($instance['direction']) ? ' ' : apply_filters('widget_direction', $instance['direction']);
	$control = empty($instance['control']) ? ' ' : apply_filters('widget_control', $instance['control']);
	$delay = empty($instance['delay']) ? ' ' : apply_filters('widget_delay', $instance['delay']);
	$autohover = empty($instance['autohover']) ? ' ' : apply_filters('widget_autohover', $instance['autohover']);
	$pager = empty($instance['pager']) ? ' ' : apply_filters('widget_pager', $instance['pager']);
	$play = empty($instance['play']) ? ' ' : apply_filters('widget_play', $instance['play']);
	$showtext = empty($instance['showtext']) ? ' ' : apply_filters('widget_showtext', $instance['showtext']);
	$title_link = empty($instance['title_link']) ? ' ' : apply_filters('widget_title_link', $instance['title_link']);
	$limit = empty($instance['limit']) ? ' ' : apply_filters('widget_limit', $instance['limit']);
	$show_image = empty($instance['show_image']) ? ' ' : apply_filters('widget_show_image', $instance['show_image']);
	$image_width = empty($instance['image_width']) ? ' ' : apply_filters('widget_image_width', $instance['image_width']);
	
 	add_image_size( 'testimonial', $image_width,$image_width, true);
	
	
    if (!empty($title))
      echo $before_title . $title . $after_title;

?>
<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery('#<?php echo $slide_title; ?>').bxSlider({
		adaptiveHeight: true,
		adaptiveHeightSpeed: 500,
		mode: '<?php echo $direction; ?>',
		pager: <?php echo $pager; ?>,
		auto: <?php echo $play; ?>,
		controls: <?php echo $control; ?>,
		speed:<?php echo $speed; ?>,
		pause:<?php echo $delay; ?>,
		touchEnabled: true,
		autoHover: <?php echo $autohover; ?>
  });
});
</script>
<?php
	// WIDGET CODE GOES HERE
	query_posts('post_type=testimonial&posts_per_page='.$limit);
	if (have_posts()) : 
		echo "<div class='textwidget'><div id='$slide_title'>";
		while (have_posts()) : the_post(); 
			echo "<div>";
			if($instance['showtext']=="full_text") {
				echo the_content();
  			} else {
				echo the_excerpt();
 			}
			if($instance['show_image']=="yes") {
			the_post_thumbnail('testimonial');
			echo "<div style='clear:both;'></div>";
			}
			if($instance['title_link']=="true") {
			echo "<a href='".get_permalink()."' class='testi_name'>".get_the_title()."</a>";
  			} else {
				echo "<span class='testi_name'>".get_the_title()."</span>";
 			}
			echo "</div>";
	 
		endwhile;
		echo "</div></div>";
	endif; 
	wp_reset_query();
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("TestimonailWidget");') );

//Pagination Function
function fp_pagination($pages = '', $range = 4)
{ 
     $showitems = ($range * 2)+1; 
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }  
 
     if(1 != $pages)
     {
         echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>"; 
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         echo "</div>\n";
     }
}


//for using shortcode [textimonial limit=2]
add_shortcode('testimonial', 'show_testimonial');


function show_testimonial($params = array()){
	extract(shortcode_atts(array(
		'title' => 'testimonial',
		'id' => 'testimonial',
	    'limit' => 10
	), $params));
	$limit=$params["limit"];
	add_image_size( 'testimonial_page', 60,60, true);
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	query_posts('post_type=testimonial&posts_per_page='.$limit.'&caller_get_posts=1&paged='. $paged );
	if (have_posts()) : 
		echo "<div id='testimonial_details'>";
		while (have_posts()) : the_post(); 
			echo "<div class='testi'>";
			echo the_excerpt();
			echo the_post_thumbnail('testimonial_page');
			echo "<div style='clear:both;'></div>";
 			echo "<a href='".get_permalink()."' class='testi_name'>".get_the_title()."</a>";
			echo "</div>";
	 
		endwhile;
		echo "</div>";
		if (function_exists("fp_pagination")) {
    		fp_pagination($additional_loop->max_num_pages);
		}
	endif; 
	wp_reset_query();
}

?>
