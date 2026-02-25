<?php
/**
 * The header
 *
 * @package Bathe
 */

$custom_logo_id = get_theme_mod( 'custom_logo' );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php wp_head(); ?>
</head>

<body <?php body_class( 'antialiased flex flex-col min-h-screen' ); ?>>
<?php wp_body_open(); ?>

<header class="header sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
	<div class="mx-auto flex w-full max-w-7xl items-center justify-between gap-4">
		<a class="inline-flex items-center text-lg font-bold text-slate-900 hover:underline" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php if ( $custom_logo_id ) : ?>
				<?php echo wp_get_attachment_image( $custom_logo_id, 'full', false, array( 'class' => 'h-20 w-auto' ) ); ?>
				<span class="sr-only"><?php bloginfo( 'name' ); ?></span>
			<?php else : ?>
				<?php bloginfo( 'name' ); ?>
			<?php endif; ?>
		</a>

		<button
			type="button"
			class="inline-flex items-center justify-center rounded-md border border-slate-300 p-2 text-slate-800 transition hover:bg-slate-100 lg:hidden"
			aria-controls="mobile-primary-menu"
			aria-expanded="false"
			aria-label="<?php esc_attr_e( 'Open menu', 'bathe' ); ?>"
			data-menu-toggle
		>
			<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
				<path fill-rule="evenodd" d="M2.5 5.75A.75.75 0 013.25 5h13.5a.75.75 0 010 1.5H3.25a.75.75 0 01-.75-.75zm0 4.25a.75.75 0 01.75-.75h13.5a.75.75 0 010 1.5H3.25a.75.75 0 01-.75-.75zm.75 3.5a.75.75 0 000 1.5h13.5a.75.75 0 000-1.5H3.25z" clip-rule="evenodd" />
			</svg>
		</button>

		<nav class="hidden lg:block" aria-label="<?php esc_attr_e( 'Primary Menu', 'bathe' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'flex items-center gap-1',
					'fallback_cb'    => 'wp_page_menu',
				)
			);
			?>
		</nav>
	</div>
</header>

<div id="mobile-primary-menu" class="fixed inset-0 z-50 pointer-events-none lg:hidden" data-mobile-menu>
	<div class="absolute inset-0 bg-slate-950/40 opacity-0 transition-opacity duration-300" data-mobile-menu-backdrop></div>
	<nav
		class="absolute inset-y-0 left-0 w-[84vw] max-w-sm -translate-x-full overflow-y-auto bg-white px-4 py-6 shadow-xl transition-transform duration-300"
		aria-label="<?php esc_attr_e( 'Mobile Primary Menu', 'bathe' ); ?>"
		data-mobile-menu-panel
	>
		<div class="mb-5 border-b border-slate-200 pb-4">
			<a class="inline-flex items-center text-lg font-bold text-slate-900 hover:underline" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php if ( $custom_logo_id ) : ?>
					<?php echo wp_get_attachment_image( $custom_logo_id, 'full', false, array( 'class' => 'h-10 w-auto' ) ); ?>
					<span class="sr-only"><?php bloginfo( 'name' ); ?></span>
				<?php else : ?>
					<?php bloginfo( 'name' ); ?>
				<?php endif; ?>
			</a>
			<p class="mt-3 text-xs font-semibold uppercase tracking-wide text-slate-500"><?php esc_html_e( 'Menu', 'bathe' ); ?></p>
		</div>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'space-y-1',
				'fallback_cb'    => 'wp_page_menu',
			)
		);
		?>
	</nav>
</div>

<div class="lg:flex grow">
	<main id="primary" class="grow p-8" role="main">
