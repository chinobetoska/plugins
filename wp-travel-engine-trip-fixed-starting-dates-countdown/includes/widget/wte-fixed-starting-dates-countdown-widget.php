<?php
// Register and load the widget
function wte_fixed_starting_dates_countdown_load_widget() {
	 register_widget( 'wte_fixed_starting_dates_countdown_widget' );
}
add_action( 'widgets_init', 'wte_fixed_starting_dates_countdown_load_widget' );

// Creating the widget
class wte_fixed_starting_dates_countdown_widget extends WP_Widget {


	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'wte_fixed_starting_dates_countdown_widget',
			// Widget name will appear in UI
			__( 'Trip Starting Countdown', 'wte-fixed-starting-dates-countdown' ),
			// Widget description
			array( 'description' => __( 'Fixed Starting Dates Countdown Widget for Trips', 'wte-fixed-starting-dates-countdown' ) )
		);
	}

	// Creating widget front-end

	/**
	 *
	 * @since 2.1.0
	 */
	public function widget_output( $departure ) {
		if ( isset( $departure ) && ! empty( $departure ) ) {
			// before and after widget arguments are defined by themes
			// echo $args['before_widget'];
			// if ( ! empty( $title ) ) {
			// echo $args['before_title'] . $title . $args['after_title'];
			// }

			echo '<div id="departure-dates-countdown">
		  <div class="departure-time-holder">
			<div id="days"></div>
			<div class="departure-countdown-title">';
			echo esc_html_e( 'Days', 'wte-fixed-starting-dates-countdown' );
			echo '</div>
		  </div>

		  <div class="departure-seperator"><i class="fas fa-circle"></i><i class="fas fa-circle"></i></div>

		  <div class="departure-time-holder">
			<div id="hours"></div>
			<div class="departure-countdown-title">';
			echo esc_html_e( 'Hours', 'wte-fixed-starting-dates-countdown' );
			echo '</div>
		  </div>

		  <div class="departure-seperator"><i class="fas fa-circle"></i><i class="fas fa-circle"></i></div>

		  <div class="departure-time-holder">
			<div id="minutes"></div>
			<div class="departure-countdown-title">';
			echo esc_html_e( 'Minutes', 'wte-fixed-starting-dates-countdown' );
			echo '</div>
		  </div>

		  <div class="departure-seperator"><i class="fas fa-circle"></i><i class="fas fa-circle"></i></div>

		  <div class="departure-time-holder">
			<div id="seconds"></div>
			<div class="departure-countdown-title">';
			echo esc_html_e( 'Seconds', 'wte-fixed-starting-dates-countdown' );
			echo '</div>
		  </div>
		  </div>';

			$script =
				'<script>
		  function countdown(endDate) {
			let days, hours, minutes, seconds;

			endDate = new Date(endDate).getTime();

			if (isNaN(endDate)) {
			  return;
			}

			setInterval(calculate, 1000);

			function calculate() {
			  let startDate = new Date();
			  startDate = startDate.getTime();

			  let timeRemaining = parseInt((endDate - startDate) / 1000);

			  if (timeRemaining >= 0) {
				days = parseInt(timeRemaining / 86400);
				timeRemaining = (timeRemaining % 86400);

				hours = parseInt(timeRemaining / 3600);
				timeRemaining = (timeRemaining % 3600);

				minutes = parseInt(timeRemaining / 60);
				timeRemaining = (timeRemaining % 60);

				seconds = parseInt(timeRemaining);

				document.getElementById("days").innerHTML = parseInt(days, 10);
				document.getElementById("hours").innerHTML = ("0" + hours).slice(-2);
				document.getElementById("minutes").innerHTML = ("0" + minutes).slice(-2);
				document.getElementById("seconds").innerHTML = ("0" + seconds).slice(-2);
			  } else {
				return;
			  }
			}
		  }

		  (function () {
			countdown("' . $departure . '");
		  }());
		  </script>';
			$obj    = new WTE_Fixed_Starting_Dates_Countdown_Functions();
			echo $obj->wte_countdown_minify_js( $script );

			// This is where you run the code and display the output
			// echo $args['after_widget'];
		}
	}

	public function widget( $args, $instance ) {
		if ( ! is_singular( 'trip' ) ) {
			return;
		}
		$trip_id                = get_the_ID();
		$wte_countdown_settings = get_post_meta( $trip_id, 'wte_countdown_settings', true );
		$today                  = strtotime( date( 'Y-m-d' ) ) * 1000;

		if ( isset( $wte_countdown_settings['hide'] ) && $wte_countdown_settings['hide'] == '1' ) {
			return;
		}

		$fsds = apply_filters( 'trip_card_fixed_departure_dates', $trip_id );
		if ( $fsds != $trip_id && is_array( $fsds ) ) {
			$timestamp = ! empty( $wte_countdown_settings['date'] ) ? strtotime( $wte_countdown_settings['date'] ) : '';
			if ( $timestamp && $today < ( $timestamp * 1000 ) && isset( $fsds[ $timestamp ] ) ) {
				$departure = $wte_countdown_settings['date'];
			} else {
				ksort( $fsds );
				foreach ( $fsds as $_timestamp => $_args ) {
					if ( ( $_timestamp * 1000 ) > $today ) {
						$departure = $_args['start_date'];
						break;
					}
				}
			}
			if ( ! empty( $departure ) ) {
				$title = apply_filters( 'widget_title', $instance['title'] );
				echo $args['before_widget'];
				if ( ! empty( $title ) ) {
					echo $args['before_title'] . $title . $args['after_title'];
				}
				$this->widget_output( $departure, $trip_id );
				echo $args['after_widget'];
			}
			return;
		}

		$title                                   = apply_filters( 'widget_title', $instance['title'] );
		$WTE_Fixed_Starting_Dates_setting        = get_post_meta( $trip_id, 'WTE_Fixed_Starting_Dates_setting', true );
		$wp_travel_engine_setting_option_setting = get_option( 'wp_travel_engine_settings', true );
		$sortable_settings                       = get_post_meta( $trip_id, 'list_serialized', true );
		$wp_travel_engine_setting                = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );

		if ( ! is_array( $sortable_settings ) ) {
			$sortable_settings = json_decode( $sortable_settings );
		}

		if ( isset( $WTE_Fixed_Starting_Dates_setting ) && $WTE_Fixed_Starting_Dates_setting != '' && isset( $sortable_settings ) && ! empty( $sortable_settings ) ) {
			$today = strtotime( date( 'Y-m-d' ) ) * 1000;

			foreach ( $sortable_settings as $content ) {
				if ( $today < strtotime( $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][ $content->id ] ) * 1000 ) {
					$arr[] = $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][ $content->id ];

					if ( isset( $wte_countdown_settings['date'] ) && $wte_countdown_settings['date'] == $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][ $content->id ] ) {
						$flag = 1;
					}
				}
			}

			if ( isset( $arr ) && is_array( $arr ) ) {
				$departure = min( $arr );
			}

			if ( isset( $wte_countdown_settings['date'] ) && isset( $flag ) && ( $flag == '1' ) && $today < strtotime( $wte_countdown_settings['date'] ) * 1000 ) {
				$departure = $wte_countdown_settings['date'];
			}
		}

		if ( isset( $departure ) && ! empty( $departure ) ) {
			// before and after widget arguments are defined by themes
			echo $args['before_widget'];
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			$this->widget_output( $departure );

			// This is where you run the code and display the output
			echo $args['after_widget'];
		}
	}

	// Widget Backend
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Trip Starting Countdown', 'wte-fixed-starting-dates-countdown' );
		}
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wte-fixed-starting-dates-countdown' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<span class="wte-countdown-note">
				<?php _e( 'Note: Select the Trip Starting Date on individual trip post, else the upcoming Fixed Starting Trip will be shown automatically in the widget.', 'wte-fixed-starting-dates-countdown' ); ?>
			</span>
		</p>
		<?php
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} // Class wte_fixed_departure_countdown_Widget ends here
