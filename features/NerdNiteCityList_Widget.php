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
		// outputs the content of the widget
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

}

add_action('widgets_init', 'NerdNiteCityList_Init');
