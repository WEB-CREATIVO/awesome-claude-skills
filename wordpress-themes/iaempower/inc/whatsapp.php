<?php
/**
 * Floating WhatsApp CTA — a fixed button, visible on every page, that
 * opens a chat with the number configured in the Customizer (Apariencia
 * → Personalizar → Botón flotante de WhatsApp).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Builds the wa.me link from the configured number and predefined
 * message. Non-digit characters (spaces, +, dashes) are stripped from
 * the number, since wa.me only accepts the country code + digits.
 */
function iaempower_whatsapp_url() {
	$number = preg_replace( '/\D/', '', iaempower_mod( 'whatsapp_number' ) );
	$url    = 'https://wa.me/' . $number;

	$message = trim( iaempower_mod( 'whatsapp_message' ) );
	if ( $message !== '' ) {
		$url .= '?text=' . rawurlencode( $message );
	}

	return $url;
}

function iaempower_render_whatsapp_button() {
	if ( ! iaempower_mod( 'whatsapp_enabled' ) ) {
		return;
	}

	$number = preg_replace( '/\D/', '', iaempower_mod( 'whatsapp_number' ) );
	if ( $number === '' ) {
		return;
	}
	?>
	<a class="wa-float" href="<?php echo esc_url( iaempower_whatsapp_url() ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( iaempower_mod( 'whatsapp_aria_label' ) ); ?>">
		<span class="wa-float-ring" aria-hidden="true"></span>
		<svg viewBox="0 0 32 32" fill="currentColor" aria-hidden="true"><path d="M16.001 3C9.096 3 3.5 8.596 3.5 15.5c0 2.42.687 4.68 1.878 6.598L3 29l7.106-2.336a12.42 12.42 0 0 0 5.895 1.503h.005c6.905 0 12.5-5.596 12.5-12.5S22.906 3 16.001 3Zm0 22.77h-.004a10.24 10.24 0 0 1-5.222-1.43l-.375-.223-3.877 1.275 1.298-3.78-.244-.388a10.23 10.23 0 0 1-1.577-5.454c0-5.664 4.61-10.27 10.279-10.27 2.745 0 5.325 1.07 7.267 3.014a10.2 10.2 0 0 1 3.008 7.264c0 5.665-4.61 10.263-10.277 10.263Zm5.633-7.688c-.309-.155-1.827-.902-2.11-1.005-.283-.103-.489-.155-.696.155-.206.31-.797 1.005-.978 1.211-.18.207-.36.232-.669.078-.309-.155-1.304-.48-2.483-1.53-.918-.818-1.538-1.829-1.718-2.138-.18-.31-.02-.478.136-.632.14-.14.309-.361.464-.542.155-.18.206-.31.309-.516.103-.207.051-.387-.026-.542-.078-.155-.696-1.674-.953-2.293-.251-.603-.507-.522-.696-.532l-.593-.01c-.206 0-.541.077-.824.387-.283.31-1.08 1.054-1.08 2.573s1.105 2.986 1.259 3.192c.155.207 2.176 3.32 5.27 4.657.736.318 1.31.508 1.758.65.739.235 1.412.202 1.944.123.593-.089 1.827-.747 2.085-1.468.258-.72.258-1.338.18-1.468-.077-.129-.283-.207-.592-.362Z"></path></svg>
	</a>
	<?php
}
add_action( 'wp_footer', 'iaempower_render_whatsapp_button' );
