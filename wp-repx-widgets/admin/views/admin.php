<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   WP_Repx_Widgets
 * @author    Steven Nance <steven@devtrw.com>
 * @license   MIT
 * @link      http://example.com
 * @copyright 2014 Steven Nance <steven@devtrw.com>
 */
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form method="POST" action="options.php">
		<?php
			settings_fields( 'wp-repx-widgets' );
			do_settings_sections( 'wp-repx-widgets' );
			submit_button();
		?>
	</form>
	<!-- @TODO: Provide markup for your options page here. -->

</div>
