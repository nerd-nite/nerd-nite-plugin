<?php


class NerdNiteCityList_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'NerdNiteCityList', // Base ID
			__( 'Nerd Nite Cities', 'text_domain' ), // Name
			array( 'description' => __( 'A list of all of the Nerd Nite Cities. Only nerdnite.com should use this', 'text_domain' ), ) // Args
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
		wp_enqueue_script('city-map');
		wp_enqueue_style('nn-menu-lib');
		wp_enqueue_style('city-selector');
		wp_enqueue_style('jquery-ui-lightness');

		echo $args['before_widget'];
		echo $args['before_title'] . 'Nerd Nite Cities'. $args['after_title'];
		$cities = wp_get_sites();
		?>
		<select id="nerdnite-city-selector" data-placeholder="Choose a city...">
			<option value="""></option>
			<?php
				$cityList = array();
				foreach ($cities as $city) {
					$blog_details = get_blog_details($city[blog_id]);
					if (preg_match("/.*Test.*/i", $blog_details->blogname)) {
						continue;
					} elseif (preg_match("/^[Nn]erd [Nn]ite (.*)$/", $blog_details->blogname, $matches)) {
						$city_name = ucfirst($matches[1]);
						if(in_array($city_name, ["Template","Aimeeville", "Podcast"])) {
							continue;
						}
						if($city['public'] != "1" || $city['archived'] == "1" || $city['deleted'] == "1") {
							continue;
						}
						array_push($cityList,array("domain" => $city[domain], "name" => $city_name));
					}
				}
				function citySort($a, $b) {
					return strnatcmp($a['name'], $b['name']);
				}
				usort($cityList, "citySort");
				foreach($cityList as $city) {
					echo "<option value='$city[domain]'>$city[name]</option>";
				}
			?>
		</select>
		<span id="nn-city-map-display">&middot;</span>

		<style>
			#nn-map-of-cities-dialog {
				display: none;
				width: 750px;
				height: 550px;
			}
			#nn-map-of-cities {
				width: 700px;
				height: 500px;
				padding: 25px;
			}
		</style>
		<div id="nn-map-of-cities-dialog">
			<div id="nn-map-of-cities"></div>
		</div>
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
	wp_register_script('nn-menu-lib', plugins_url('/ui/chosen/chosen.jquery.min.js', __FILE__), array('jquery'));
	wp_register_script('city-selector', plugins_url('/ui/city-selector/city-selector.js', __FILE__), array('jquery', 'nn-menu-lib'), '2.00');
	wp_register_script('googlemaps', '//maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_V3_API_KEY . '&sensor=false', false, '3');
	wp_register_script('city-map', plugins_url('/ui/city-map/city-map.js', __FILE__),
		array('jquery-ui-dialog', 'googlemaps'), '1.00');

	wp_register_style('nn-menu-lib', plugins_url('/ui/chosen/chosen.min.css', __FILE__), array());
	wp_register_style('city-selector', plugins_url('/ui/city-selector/city-selector.css', __FILE__), array());
	wp_register_style('jquery-ui-lightness', '//code.jquery.com/ui/1.11.2/themes/ui-lightness/jquery-ui.css', array());

}

add_action('widgets_init', 'NerdNiteCityList_Init');
