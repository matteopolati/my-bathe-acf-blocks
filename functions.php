<?php
/**
 * Bathe functions
 *
 * @package Bathe
 */

/**
 * Set up theme defaults and registers support for various WordPress feaures.
 */
add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'bathe', get_theme_file_uri( 'languages' ) );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
			)
		);
		add_theme_support(
			'custom-background',
			apply_filters(
				'bathe_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 200,
				'width'       => 50,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'bathe' ),
			)
		);
	}
);

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
add_action(
	'after_setup_theme',
	function () {
		$GLOBALS['content_width'] = apply_filters( 'bathe_content_width', 960 );
	},
	0
);

/**
 * Register widget area.
 */
add_action(
	'widgets_init',
	function () {
		register_sidebar(
			array(
				'name'          => __( 'Sidebar', 'bathe' ),
				'id'            => 'sidebar-1',
				'description'   => '',
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
);

/**
 * Enqueue scripts and styles.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'bathe', get_theme_file_uri( 'assets/css/main.css' ), array(), '3.0.1' );
		wp_enqueue_style( 'tailwind', get_theme_file_uri( 'assets/css/tailwind.css' ), array(), '3.3.2' );

		wp_enqueue_script( 'bathe', get_theme_file_uri( 'assets/js/main.js' ), array(), '3.0.1', true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
);

require get_template_directory() . '/acf-blocks/acf-blocks.php';
add_filter(
	'nav_menu_css_class',
	function ( $classes, $item, $args ) {
		if ( ! isset( $args->theme_location ) || 'primary' !== $args->theme_location ) {
			return $classes;
		}

		$classes[] = 'menu-item';
		$classes[] = 'relative';

		if ( in_array( 'menu-item-has-children', $classes, true ) ) {
			$classes[] = 'group';
		}

		return array_unique( $classes );
	},
	10,
	3
);

add_filter(
	'nav_menu_link_attributes',
	function ( $atts, $item, $args ) {
		if ( ! isset( $args->theme_location ) || 'primary' !== $args->theme_location ) {
			return $atts;
		}

		$link_classes = 'block rounded-md px-3 py-2 text-sm font-medium text-slate-800 transition hover:bg-slate-100';

		if ( ! empty( $item->current ) || ! empty( $item->current_item_ancestor ) ) {
			$link_classes .= ' bg-slate-100';
		}

		$atts['class'] = isset( $atts['class'] ) ? trim( $atts['class'] . ' ' . $link_classes ) : $link_classes;

		return $atts;
	},
	10,
	3
);

add_filter(
	'nav_menu_submenu_css_class',
	function ( $classes, $args ) {
		if ( ! isset( $args->theme_location ) || 'primary' !== $args->theme_location ) {
			return $classes;
		}

		return array(
			'sub-menu',
			'mt-1',
			'space-y-1.5',
			'pl-3',
			'border-l',
			'border-slate-200',
			'lg:invisible',
			'lg:absolute',
			'lg:left-0',
			'lg:top-full',
			'lg:z-30',
			'lg:mt-2',
			'lg:min-w-56',
			'lg:space-y-1',
			'lg:rounded-md',
			'lg:border',
			'lg:border-slate-200',
			'lg:bg-white',
			'lg:p-2',
			'lg:pl-2',
			'lg:opacity-0',
			'lg:shadow-xl',
			'lg:transition',
			'lg:duration-150',
			'lg:group-hover:visible',
			'lg:group-hover:opacity-100',
			'lg:group-focus-within:visible',
			'lg:group-focus-within:opacity-100',
		);
	},
	10,
	2
);

/**
 * Allow SVG uploads in Media Library.
 */
add_filter(
	'upload_mimes',
	function ( $mimes ) {
		if ( current_user_can( 'upload_files' ) ) {
			$mimes['svg']  = 'image/svg+xml';
			$mimes['svgz'] = 'image/svg+xml';
		}

		return $mimes;
	}
);

add_filter(
	'wp_check_filetype_and_ext',
	function ( $data, $file, $filename, $mimes ) {
		$filetype = wp_check_filetype( $filename, $mimes );

		if ( 'svg' === $filetype['ext'] ) {
			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		}

		return $data;
	},
	10,
	4
);

add_filter(
	'wp_prepare_attachment_for_js',
	function ( $response, $attachment ) {
		if ( 'image/svg+xml' !== get_post_mime_type( $attachment ) ) {
			return $response;
		}

		$response['image'] = array(
			'src' => $response['url'],
		);
		$response['thumb'] = array(
			'src' => $response['url'],
		);

		return $response;
	},
	10,
	2
);
