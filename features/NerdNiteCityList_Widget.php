<?php


class NerdNiteCityList_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'NerdNiteCityList', // Base ID
			__( 'Nerd Nite Cities', 'text_domain' ), // Name
			array( 'description' => __( 'A list of all of the Nerd Nite Cities', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		wp_enqueue_script('city-selector');
		wp_enqueue_style('nn-menu-lib');
		wp_enqueue_scripts('city-selector');

		echo $args['before_widget'];
		echo $args['before_title'] . 'Nerd Nite Cities'. $args['after_title'];
		$cities = wp_get_sites();
		?>
		<div><em>Coming Soon:</em> Find the nearest Nerd Nite to you</div>
		<select id="nerdnite-city-selector" data-placeholder="Choose a city...">
			<?php
				foreach ($cities as $city) {
					$blog_details = get_blog_details($city[blog_id]);
					if (preg_match("/.*Test.*/i", $blog_details->blogname)) {
						continue;
					} elseif (preg_match("/^[Nn]erd [Nn]ite (.*)$/", $blog_details->blogname, $matches)) {
						$city_name = $matches[1];
						if(in_array($city_name, ["Template","Aimeeville", "Podcast"])) {
							continue;
						}
						if($blog_details->public != "1") {
							continue;
						}
						echo "<option value='$city[domain]'>$city_name</option>";
					}
				}
			?>
		</select>

		<?php
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}

}

function NerdNiteCityList_Init() {
	register_widget('NerdNiteCityList_Widget');
	wp_register_script('nn-menu-lib', plugins_url('/chosen/chosen.jquery.min.js', __FILE__), array('jquery'));
	wp_register_script('city-selector', plugins_url('/city-selector.js', __FILE__), array('jquery', 'nn-menu-lib'), '2.00');

	wp_register_style('nn-menu-lib', plugins_url('/chosen/chosen.min.css', __FILE__), array());
	wp_register_style('city-selector', plugins_url('/city-selector.css', __FILE__), array());



}

add_action('widgets_init', 'NerdNiteCityList_Init');
