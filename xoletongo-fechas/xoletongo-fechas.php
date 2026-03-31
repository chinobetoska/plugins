<?php
/*
Plugin Name: Xoletongo - Sistema de Reservas Pro
Description: Recrea la funcionalidad de fechas fijas y se integra con el botón de reserva.
Version: 2.0
Author: Beto
*/

// 1. Crear la caja en el editor (Metabox)
add_action( 'add_meta_boxes', 'xole_pro_agregar_caja' );
function xole_pro_agregar_caja() {
    add_meta_box('xole_fechas_id', '📅 Fechas de Salida Disponibles', 'xole_pro_diseno_caja', 'trip', 'normal', 'high');
}

function xole_pro_diseno_caja( $post ) {
    $fechas = get_post_meta( $post->ID, '_xole_fechas_lista', true );
    wp_nonce_field( 'xole_pro_guardar', 'xole_pro_nonce' );
    ?>
    <p>Escribe una fecha por línea (Ej: 15 de Abril 2026):</p>
    <textarea name="xole_fechas_lista" rows="5" style="width:100%;"><?php echo esc_textarea( $fechas ); ?></textarea>
    <?php
}

// 2. Guardar los datos
add_action( 'save_post', 'xole_pro_guardar_datos' );
function xole_pro_guardar_datos( $post_id ) {
    if ( ! isset( $_POST['xole_pro_nonce'] ) || ! wp_verify_nonce( $_POST['xole_pro_nonce'], 'xole_pro_guardar' ) ) return;
    if ( isset( $_POST['xole_fechas_lista'] ) ) {
        update_post_meta( $post_id, '_xole_fechas_lista', sanitize_textarea_field( $_POST['xole_fechas_lista'] ) );
    }
}

/**
 * 3. INYECCIÓN EN EL FORMULARIO DE RESERVA
 * Esto hace que la fecha aparezca en el proceso de compra de WP Travel Engine
 */
add_action( 'wp_travel_engine_booking_form_fields', 'xole_inyectar_selector_fechas', 10 );
function xole_inyectar_selector_fechas() {
    $viaje_id = get_the_ID();
    $fechas_raw = get_post_meta( $viaje_id, '_xole_fechas_lista', true );

    if ( ! empty( $fechas_raw ) ) {
        $fechas_array = explode( "\n", str_replace( "\r", "", $fechas_raw ) );
        ?>
        <div class="wte-booking-field-wrapper" style="margin-bottom: 20px;">
            <label style="font-weight:bold; display:block; margin-bottom:5px;">Selecciona tu fecha de salida:</label>
            <select name="xole_fecha_seleccionada" id="xole_fecha_seleccionada" class="wte-form-control" required style="width:100%; padding:10px; border:1px solid #ccc;">
                <option value="">-- Elige una fecha --</option>
                <?php foreach ( $fechas_array as $fecha ) : 
                    $fecha = trim($fecha);
                    if ( empty($fecha) ) continue;
                    ?>
                    <option value="<?php echo esc_attr( $fecha ); ?>"><?php echo esc_html( $fecha ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
}

/**
 * 4. GUARDAR LA FECHA EN LA RESERVA (LOG)
 * Esto hace que la fecha aparezca en el panel de "Bookings" de WP Travel Engine
 */
add_filter( 'wp_travel_engine_booking_save_data', 'xole_guardar_fecha_en_reserva', 10, 1 );
function xole_guardar_fecha_en_reserva( $data ) {
    if ( isset( $_POST['xole_fecha_seleccionada'] ) ) {
        // Añadimos la fecha a las notas del cliente o un campo personalizado
        $fecha = sanitize_text_field( $_POST['xole_fecha_seleccionada'] );
        $data['customer_notes'] .= "\n[FECHA DE SALIDA SELECCIONADA: " . $fecha . "]";
    }
    return $data;
}