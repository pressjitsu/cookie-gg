<?php
/**
 * Plugin Name: Cookie.gg
 * Description: A simple and lightweight GDPR consent plugin for WordPress. Requires a free cookie.gg account.
 * Plugin URI: https://cookie.gg
 * Version: 0.1.0
 * License: GPLv3 or later
 */

add_action( 'admin_menu', function() {
	add_submenu_page(
		'options-general.php',
		'Cookie.gg GDPR Cookie',
		'GDPR',
		'manage_options',
		'cookiegg', function() {
			if ( false !== wp_verify_nonce( isset( $_POST['_wpnonce'] ) ? $_POST['_wpnonce'] : null, 'cookiegg' ) ) {
				$id = isset( $_POST['id'] ) ? $_POST['id'] : null;

				if ( preg_match( '#^[a-f0-9]{40}$#', $id ) ) {
					update_option( 'cookiegg_id', $id );
				}
			}

			$id = get_option( 'cookiegg_id' );

			?>
				<div class="wrap">
					<h1>Cookie.gg GDPR ID</h1>

					<p>Cookie.gg is a fast, secure and platform-agnostic hosted GDPR banner. You will need to create a free account over at <a href="cookie.gg">cookie.gg</a> to set your banner up. Every banner you create and configure has an ID that you will need to paste below.</p>

					<form method="post" novalidate="novalidate">
						<table class="form-table" role="presentation">
							<tr>
								<th scope="row"><label for="id">Banner ID</label></th>
								<td>
									<input name="id" type="text" id="id" value="<?php echo esc_attr( $id ); ?>" class="regular-text" />
									<p class="description">Get your unique banner ID from your free account at <a href="https://cookie.gg">cookie.gg</a>. Example: <code>af4f1d6274f33ca0f3a1bcb13fb2a22abb9fbf62</code></p>
								</td>
							</tr>
							<?php wp_nonce_field( 'cookiegg' ); ?>
						</table>
						<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save"></p>
					</form>
				</div>
			<?php
		}
	);
} );

add_action( 'wp_enqueue_scripts', function() {
	if ( preg_match( '#^[a-f0-9]{40}$#', $id = get_option( 'cookiegg_id' ) ) ) {
		wp_enqueue_script( 'cookie-gg', "https://cookie.gg/c/$id/cookie.js" );
	}
} );
