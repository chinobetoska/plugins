<?php
class WTE_Fixed_Starting_Dates_Countdown_MetaBox {


	function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'wte_countdown_add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'wte_countdown_save_meta_box_data' ) );
	}

	/**
	 * The function responsible for creating the actual meta box.
	 *
	 * @since    1.0.0
	 */
	public function wte_countdown_add_meta_boxes() {
		$post_type = array( 'trip' );

		add_meta_box(
			'wte_countdown_meta_id',  // Unique ID
			__( 'Trip Countdown Configuration', 'wte-fixed-starting-dates-countdown' ),           // Box title
			array( $this, 'display_wte_countdown_options_meta_box' ),   // Content callback, must be of type callable
			$post_type,             // Post type
			'side',
			'high'
		);
	}

	/**
	 *
	 * @since 2.1.0
	 */
	public function options_meta_box( $fsds, $trip_id ) {
		$wte_countdown_settings = get_post_meta( $trip_id, 'wte_countdown_settings', true );
		if ( empty( $wte_countdown_settings ) ) {
			$wte_countdown_settings = array();
		}
		?>
		<div class="wte-countdown-settings">
			<label for="wte_countdown_settings[hide]" class="countdown-checkbox-label">
				<h4><?php _e( 'Hide Countdown Widget : ', 'wte-fixed-starting-dates-countdown' ); ?></h4>
				<input type="checkbox" id="wte_countdown_settings[hide]" name="wte_countdown_settings[hide]" value="1"
				<?php
				if ( isset( $wte_countdown_settings['hide'] ) && $wte_countdown_settings['hide'] != '' ) {
					echo 'checked';}
				?>
				>
				<span class="checkbox-label"></span>
			</label>
		</div>

		<div class="wte-countdown-settings">
			<h4> <?php _e( 'Choose a Trip Fixed Starting date to display in Countdown Widget.', 'wte-fixed-starting-dates-countdown' ); ?> </h4>
			<select class="trip-date-select" name="wte_countdown_settings[date]" data-placeholder="<?php _e( 'Choose a date&hellip;', 'wte-fixed-starting-dates-countdown' ); ?>">

				<option value=""><?php _e( 'Choose a date&hellip;', 'wte-fixed-starting-dates-countdown' ); ?></option>
				<?php
				foreach ( $fsds as $key => $value ) {
					$value = isset( $value['start_date'] ) && ! is_string( $value ) ? $value['start_date'] : $value;
					echo '<option value="' . $value . '"' . ( isset( $wte_countdown_settings['date'] ) && $wte_countdown_settings['date'] == $value ? ' selected' : '' ) . '>' . esc_attr( $value ) . '</option>';
				}
				?>
			</select>
		</div>
		<?php
	}

	/**
	 * Renders the content of the meta box.
	 *
	 * @since    1.0.0
	 */
	public function display_wte_countdown_options_meta_box() {
		$trip_id = get_the_ID();
		$fsds    = apply_filters( 'trip_card_fixed_departure_dates', $trip_id );
		if ( $fsds != $trip_id && is_array( $fsds ) ) {
			$this->options_meta_box( (array) $fsds, $trip_id );
			return;
		}

		$trip_version                            = get_post_meta( $trip_id, 'trip_version', true );
		$WTE_Fixed_Starting_Dates_setting        = get_post_meta( $trip_id, 'WTE_Fixed_Starting_Dates_setting', true );
		$wte_countdown_settings                  = get_post_meta( $trip_id, 'wte_countdown_settings', true );
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
				}
			}

			if ( isset( $arr ) && is_array( $arr ) ) {
				$this->options_meta_box( $arr, $trip_id );
			} else {
				echo '<h4>' . __( 'No upcoming Fixed Starting Trips available.', 'wte-fixed-starting-dates-countdown' ) . '</h4>';
			}
		} else {
			echo '<h4>' . __( 'No Fixed Starting Trips available. Set Fixed Starting Dates to display in Countdown Widget.', 'wte-fixed-starting-dates-countdown' ) . '</h4>';
		}
		?>
		<?php

	}

	/**
	 * Sanitizes and serializes the information associated with this post.
	 *
	 * @since    1.0.0
	 *
	 * @param    int $post_id    The ID of the post that's currently being edited.
	 */
	public function wte_countdown_save_meta_box_data( $post_id ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! empty( $_POST['wte_countdown_settings'] ) ) {

			// We'll remove all white space, HTML tags, and encode the information to be saved
			$countdowndetails = $_POST['wte_countdown_settings'];
			update_post_meta( $post_id, 'wte_countdown_settings', $countdowndetails );
		} else {

			if ( '' !== get_post_meta( $post_id, 'wte_countdown_settings', true ) ) {
				delete_post_meta( $post_id, 'wte_countdown_settings' );
			}
		}
	}
}
new WTE_Fixed_Starting_Dates_Countdown_MetaBox();
