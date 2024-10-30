<?php
/**
 * @package CPW Custom Post Widget
 */
/*
Plugin Name: CPW Custom Post Widget
Plugin URI: https://facebook.com/cpw
Description: CPW-Custom Post Widget adds custom widget to widget area where you can customize posts shown to sidebar.
Version: 1.0
Author: Mehran Khan
Author URI: https://facebook.com/mehranumr
License: GPLv2 or later
Text Domain: cpw
*/
/*
 *===============================================================
 *							CPW-Custom Post Widget v1.0
 *===============================================================
 */
/* initialization
 * Sets up and add cpw widget
 */
add_image_size( 'slider_thumb', 80, 80 );
add_action( 'widgets_init', function(){
register_widget( 'cpw_widget' );
});

/* cpw_widget
 * cpw_widget class
 */

class cpw_widget extends WP_Widget{
/**
 * Sets up the widgets name etc
 */
public function __construct() {
	$widget_ops = array( 
		'classname' => 'cpw_widget',
		'description' => 'CPW widget is awesome',
		);
	parent::__construct( 'cpw_widget', 'CPW Widget', $widget_ops );

}
/**
 * Outputs the content of the widget
 *
 * @param array $args
 * @param array $instance
 */
public function widget( $args, $instance ) {
	// outputs the content of the widget
	$cpw_title = ! empty( $instance['cpw_title'] ) ? $instance['cpw_title'] : esc_html__( 'New title', 'text_domain' );
	$cpw_posts_count = ! empty( $instance['cpw_posts_count'] ) ? $instance['cpw_posts_count'] :'';
	$cpw_category = ! empty( $instance['cpw_category'] ) ? $instance['cpw_category'] :'';
	$cpw_ch_thumb = $instance['cpw_ch_thumb'] ;
	$cpw_ch_date = $instance['cpw_ch_date'] ;

	$cpw_back_color=$instance['cpw_back_color'] ;
	$cpw_link_color=$instance['cpw_link_color'] ;
	$cpw_span_color=$instance['cpw_span_color'] ;
	$cpw_link_hover_color=$instance['cpw_link_hover_color'] ;
	$cpw_thumb_radius=$instance['cpw_thumb_radius'] ;

	//calling style with some args
	$this->cpw_styles($instance);

	?>
	<aside id="cpw-widget-area" class="widget">
		<h2 class="widget-title"><?php echo $cpw_title; ?></h2>	
		<?php 
		$this->cpw_display_posts($cpw_posts_count,'slider_thumb',$cpw_category,$cpw_ch_date,$cpw_ch_thumb); 
		?>
	</aside>
	<?php
}
/**
 * Outputs the options form on admin
 *
 * @param array $instance The widget options
 */
public function form( $instance ) {

	// outputs the options form on admin
	$cpw_title = ! empty( $instance['cpw_title'] ) ? $instance['cpw_title'] : esc_html__( 'New title', 'text_domain' );
	$cpw_posts_count = ! empty( $instance['cpw_posts_count'] ) ? $instance['cpw_posts_count'] :'';
	$cpw_category = ! empty( $instance['cpw_category'] ) ? $instance['cpw_category'] :'';
	$cpw_ch_date= ! $instance['cpw_ch_date'] ? 'true' : 'false';
	$cpw_ch_thumb= ! $instance['cpw_ch_thumb'] ? 'true' : 'false';


	$cpw_back_color=$instance['cpw_back_color'] ;
	$cpw_link_color=$instance['cpw_link_color'] ;
	$cpw_span_color=$instance['cpw_span_color'] ;
	$cpw_link_hover_color=$instance['cpw_link_hover_color'] ;
	$cpw_thumb_radius=$instance['cpw_thumb_radius'] ;
	?>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'cpw_title' ) ); ?>">Title</label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cpw_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cpw_title' ) ); ?>" type="text" value="<?php echo esc_attr( $cpw_title ); ?>"><br><br>

		<label for="<?php echo esc_attr( $this->get_field_id( 'cpw_category' ) ); ?>">Posts From Category</label><br>
		<?php
		$args = array(
			'name' => esc_attr( $this->get_field_name( 'cpw_category' ) ),
			'class'=>'widefat',
			'selected' => $cpw_category
			); ?>
			<?php wp_dropdown_categories( $args  ); ?>
			<br><br>

			<label for="<?php echo esc_attr( $this->get_field_id( 'cpw_posts_count' ) ); ?>">How many Posts?</label>

			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cpw_posts_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cpw_posts_count' ) ); ?>" type="text" value="<?php echo esc_attr( $cpw_posts_count ); ?>">
			<br><br>

			<input class="checkbox" type="checkbox" <?php checked( $instance[ 'cpw_ch_thumb' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'cpw_ch_thumb' ); ?>" name="<?php echo $this->get_field_name( 'cpw_ch_thumb' ); ?>" /> 

			<label for="cpw-widget">Show Thumbnail</label><br><br>

			<label for="<?php echo esc_attr( $this->get_field_id( 'cpw_posts_count' ) ); ?>">Border Radius</label>

			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cpw_thumb_radius' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cpw_thumb_radius' ) ); ?>" type="text" value="<?php echo esc_attr( $cpw_thumb_radius ); ?>" placeholder="0">
			<br><br>

			<input class="checkbox" type="checkbox" <?php checked( $instance[ 'cpw_ch_date' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'cpw_ch_date' ); ?>" name="<?php echo $this->get_field_name( 'cpw_ch_date' ); ?>" />

			<label for="cpw-widget">Display post date?</label><br><br>

			<label for="<?php echo esc_attr( $this->get_field_id( 'cpw_back_color' ) ); ?>">Background Color</label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cpw_back_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cpw_back_color' ) ); ?>" type="text" value="<?php echo esc_attr( $cpw_back_color ); ?>" placeholder="#fff"><br><br>

			<label for="<?php echo esc_attr( $this->get_field_id( 'cpw_link_color' ) ); ?>">Link Color</label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cpw_link_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cpw_link_color' ) ); ?>" type="text" value="<?php echo esc_attr( $cpw_link_color ); ?>" placeholder="#000"><br><br>

			<label for="<?php echo esc_attr( $this->get_field_id( 'cpw_link_hover_color' ) ); ?>">Link hover Color</label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cpw_link_hover_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cpw_link_hover_color' ) ); ?>" type="text" value="<?php echo esc_attr( $cpw_link_hover_color ); ?>" placeholder="#000"><br><br>

			<label for="<?php echo esc_attr( $this->get_field_id( 'cpw_span_color' ) ); ?>">Span Color</label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cpw_span_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cpw_span_color' ) ); ?>" type="text" value="<?php echo esc_attr( $cpw_span_color ); ?>" placeholder="#000"><br><br>

		</p>
		<?php
	}

/*
 * Processing widget options on save
 * Sanitize widget form values as they are saved.
 */

public function update( $new_instance, $old_instance ) {
	$instance = array();

	$instance['cpw_title'] = ( ! empty( $new_instance['cpw_title'] ) ) ? strip_tags( $new_instance['cpw_title'] ) : '';

	$instance['cpw_posts_count'] = ( ! empty( $new_instance['cpw_posts_count'] ) ) ? strip_tags( $new_instance['cpw_posts_count'] ) : '';

	$instance['cpw_category'] = ( ! empty( $new_instance['cpw_category'] ) ) ? strip_tags( $new_instance['cpw_category'] ) : '';

	$instance['cpw_ch_date'] = $new_instance['cpw_ch_date'] ;
	$instance['cpw_ch_thumb'] = $new_instance['cpw_ch_thumb'] ;

	//Css Params

	$instance['cpw_back_color'] =$new_instance['cpw_back_color'] ;
	$instance['cpw_link_color'] =$new_instance['cpw_link_color'] ;
	$instance['cpw_span_color']=$new_instance['cpw_span_color'] ;
	$instance['cpw_link_hover_color']=$new_instance['cpw_link_hover_color'] ;
	$instance['cpw_thumb_radius'] =$new_instance['cpw_thumb_radius'] ;

	return $instance;
}

/*
 * cpw_display_posts
 * Display Posts into CPW Widget.
 */
function cpw_display_posts($num_posts,$thumb_size,$cat,$cpw_ch_date,$cpw_ch_thumb){
	rewind_posts();
	$args = array (
		'cat' => $cat,
 'orderby' => 'date' //we can specify more filters to get the data 
 );
	$cat_posts = new WP_query($args);

	if ($cat_posts->have_posts()) {
		query_posts('cat=$cat');
		$post_counter=0;
		while ( $cat_posts->have_posts() && $post_counter < $num_posts) {
			$cat_posts->the_post();
     		// display content
			?>
			<div class="smazing-side-post">
				<a href="<?php the_permalink();?>">
					<?php if($cpw_ch_thumb): ?>
					<div class="thumb">
						<?php the_post_thumbnail( $thumb_size ); ?>
					</div>
					<?php endif; ?>
					<?php the_title(); ?>
					<?php if($cpw_ch_date): ?> 
						<br><div class="small-caption"><?php the_date(); ?></div>
					<?php endif; ?>
				</a>
			</div>
			<?php
			++$post_counter;
		}
	} else {
 	// display when no posts found
		echo "No Posts for this bar!";
	}
}
/*
 * cpw_styles
 * Add Css Tags to Generate Custom Style.
 */
function cpw_styles($instance) {
	//calling styling params
	$cpw_back_color=$instance['cpw_back_color'] ;
	$cpw_link_color=$instance['cpw_link_color'] ;
	$cpw_span_color=$instance['cpw_span_color'] ;
	$cpw_link_hover_color=$instance['cpw_link_hover_color'] ;
	$cpw_thumb_radius=$instance['cpw_thumb_radius'] ;
	?>
	<style>
		.smazing-side-post{
			min-width: 100%;
			float: left;
			background-color:<?php echo $cpw_back_color; ?>;
			padding: 5px;
		}
		.smazing-side-post a{
			text-decoration: inherit;
			color: <?php echo $cpw_link_color; ?>;
			line-height: normal;
			font-family: Candara,Calibri,Segoe,Segoe UI,Optima,Arial,sans-serif;
		}
		.smazing-side-post a:hover{
			text-decoration: none;
			color: <?php echo $cpw_link_hover_color; ?>;
		}
		.smazing-side-post img{
			border-radius: <?php echo $cpw_thumb_radius; ?>px;
			margin-right: 5px;
			float: left;
			clear: left;
		}
		.smazing-side-post .small-caption{
			color:<?php echo $cpw_span_color; ?>;
			margin-right: 10px;
			font-size: 0.8em;
			float: right;
		}
	</style>
	<?php
}
}

?>