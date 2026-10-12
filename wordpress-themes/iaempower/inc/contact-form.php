<?php
/**
 * Real contact-form handling (no JavaScript fake success message here —
 * this actually sends mail via wp_mail(), which works out of the box on
 * most shared hosting through PHP's mail()).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function iaempower_handle_contact_form() {
	if ( ! isset( $_POST['iaempower_contact_nonce'] ) || ! wp_verify_nonce( $_POST['iaempower_contact_nonce'], 'iaempower_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'iaempower_contact', 'error', wp_get_referer() ? wp_get_referer() : home_url( '/' ) ) );
		exit;
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$company = isset( $_POST['company'] ) ? sanitize_text_field( wp_unslash( $_POST['company'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	$consent = isset( $_POST['consent'] );

	$redirect_base = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$redirect_base = remove_query_arg( array( 'iaempower_contact' ), $redirect_base );

	if ( ! $name || ! is_email( $email ) || ! $message || ! $consent ) {
		wp_safe_redirect( add_query_arg( 'iaempower_contact', 'error', $redirect_base ) . '#contacto' );
		exit;
	}

	$to = iaempower_mod( 'contact_notify_email', get_option( 'admin_email' ) );
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}

	$subject = sprintf( 'Nueva solicitud de diagnóstico — %s', $name );

	$body   = array();
	$body[] = 'Nombre: ' . $name;
	$body[] = 'Email: ' . $email;
	if ( $company ) {
		$body[] = 'Empresa: ' . $company;
	}
	if ( $phone ) {
		$body[] = 'Teléfono: ' . $phone;
	}
	$body[] = '';
	$body[] = 'Proceso a automatizar:';
	$body[] = $message;

	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

	$sent = wp_mail( $to, $subject, implode( "\n", $body ), $headers );

	wp_safe_redirect( add_query_arg( 'iaempower_contact', $sent ? 'success' : 'error', $redirect_base ) . '#contacto' );
	exit;
}
add_action( 'admin_post_nopriv_iaempower_contact', 'iaempower_handle_contact_form' );
add_action( 'admin_post_iaempower_contact', 'iaempower_handle_contact_form' );
