<?php
/**
 * We use WordPress's init hook to make sure
 * our blocks are registered early in the loading
 * process.
 *
 * @link https://developer.wordpress.org/reference/hooks/init/
 */
function register_acf_blocks() {
	$block_directories = glob( __DIR__ . '/blocks/*', GLOB_ONLYDIR );

	if ( false === $block_directories ) {
		return;
	}

	foreach ( $block_directories as $block_directory ) {
		$block_slug      = basename( $block_directory );
		$style_handle    = "bathe-block-{$block_slug}";
		$style_file_path = "assets/css/{$block_slug}.css";
		$style_abs_path  = get_theme_file_path( $style_file_path );
		$script_handle   = "bathe-block-{$block_slug}";
		$script_file     = "assets/js/blocks/{$block_slug}.js";
		$script_abs_path = get_theme_file_path( $script_file );

		if ( file_exists( $style_abs_path ) ) {
			wp_register_style(
				$style_handle,
				get_theme_file_uri( $style_file_path ),
				array(),
				(string) filemtime( $style_abs_path )
			);
		}

		if ( file_exists( $script_abs_path ) ) {
			wp_register_script(
				$script_handle,
				get_theme_file_uri( $script_file ),
				array(),
				(string) filemtime( $script_abs_path ),
				true
			);
		}

		register_block_type( $block_directory );
	}
}
// Here we call our register_acf_blocks() function on init.
add_action( 'init', 'register_acf_blocks' );
