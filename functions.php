<?php
/**
 * Sapient Skateboards — BlankSlate Child Theme
 * functions.php
 */

/**
 * Returns spacing modifier classes for a flex layout section.
 * Reads the cloned remove_top_padding / remove_bottom_padding sub-fields.
 * Usage: <section class="my-section <?php echo pt_spacing_classes(); ?>">
 */
function pt_spacing_classes() {
    $classes = [];
    if ( function_exists( 'get_sub_field' ) ) {
        if ( get_sub_field( 'remove_top_padding' ) )    $classes[] = 'no-pt';
        if ( get_sub_field( 'remove_bottom_padding' ) ) $classes[] = 'no-pb';
    }
    return implode( ' ', $classes );
}

// ── Enqueue parent + child styles ────────────────────────────────────────────
/**
 * Inline SVG logo helper
 */
function pt_logo_svg( $height = 32, $class = '' ) {
    $file = get_stylesheet_directory() . '/assets/images/pt-logo.svg';
    if ( ! file_exists( $file ) ) return '';
    $svg = file_get_contents( $file );
    // Inject height and optional class
    $svg = preg_replace( '/height="[^"]*"/', 'height="' . esc_attr( $height ) . '"', $svg );
    if ( $class ) {
        $svg = str_replace( '<svg ', '<svg class="' . esc_attr( $class ) . '" ', $svg );
    }
    return $svg;
}

function pt_logo_svg_mobile( $height = 32, $class = '' ) {
    $file = get_stylesheet_directory() . '/assets/images/mobile-hero-logo-new.svg';
    if ( ! file_exists( $file ) ) return '';
    $svg = file_get_contents( $file );
    // Inject height and optional class
    $svg = preg_replace( '/height="[^"]*"/', 'height="' . esc_attr( $height ) . '"', $svg );
    if ( $class ) {
        $svg = str_replace( '<svg ', '<svg class="' . esc_attr( $class ) . '" ', $svg );
    }
    return $svg;
}

add_action( 'wp_enqueue_scripts', 'pt_enqueue_styles' );
function pt_enqueue_styles() {
    // Parent theme (required for child theme)
    wp_enqueue_style(
        'blankslate-parent',
        get_template_directory_uri() . '/style.css'
    );
    // Compiled SCSS → CSS
    wp_enqueue_style(
        'sapient-skateboards',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        [ 'blankslate-parent' ],
        filemtime( get_stylesheet_directory() . '/assets/css/main.css' )
    );
    // Swiper carousel
    wp_enqueue_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        [],
        '11'
    );
    wp_enqueue_script(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [],
        '11',
        true
    );
    wp_enqueue_script(
        'sapient-skateboards-js',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        [ 'swiper' ],
        filemtime( get_stylesheet_directory() . '/assets/js/main.js' ),
        true
    );
}

// ── Theme supports ────────────────────────────────────────────────────────────
add_action( 'after_setup_theme', 'pt_theme_setup' );
function pt_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'woocommerce' );

    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'sapient-skateboards' ),
        'footer'  => __( 'Footer Navigation',  'sapient-skateboards' ),
    ] );
}

// ── WooCommerce: cart fragment for AJAX cart count ────────────────────────────
add_filter( 'woocommerce_add_to_cart_fragments', 'pt_cart_count_fragment' );
function pt_cart_count_fragment( $fragments ) {
    $count = WC()->cart->get_cart_contents_count();
    $fragments['.cart-count'] = '<span class="cart-count' . ( $count ? ' has-items' : '' ) . '">' . esc_html( $count ) . '</span>';
    return $fragments;
}

// ── WooCommerce: remove default wrapper (our templates handle this) ──────────
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// ── ACF: load/save JSON to theme ──────────────────────────────────────────────
add_filter( 'acf/settings/save_json', 'pt_acf_json_save_point' );
function pt_acf_json_save_point( $path ) {
    return get_stylesheet_directory() . '/acf-json';
}

add_filter( 'acf/settings/load_json', 'pt_acf_json_load_point' );
function pt_acf_json_load_point( $paths ) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
}

// ── ACF: options page ─────────────────────────────────────────────────────────
add_action( 'acf/init', 'pt_acf_options_page' );
function pt_acf_options_page() {
    if ( function_exists( 'acf_add_options_page' ) ) {
        acf_add_options_page( [
            'page_title' => 'Site Options',
            'menu_title' => 'Site Options',
            'menu_slug'  => 'acf-options',
            'capability' => 'manage_options',
            'redirect'   => false,
            'icon_url'   => 'dashicons-admin-settings',
        ] );

        acf_add_options_sub_page( [
            'page_title'  => 'Footer Settings',
            'menu_title'  => 'Footer',
            'menu_slug'   => 'acf-options-footer',
            'parent_slug' => 'acf-options',
            'capability'  => 'manage_options',
        ] );
    }
}

// ── ACF: register flexible content block for homepage ────────────────────────
add_action( 'acf/init', 'pt_register_acf_blocks' );
function pt_register_acf_blocks() {
    if ( ! function_exists( 'acf_register_block_type' ) ) return;
    // Blocks registered via ACF field groups instead — see acf-json/
}

// ── Custom post types ─────────────────────────────────────────────────────────
add_action( 'init', 'pt_register_post_types' );
function pt_register_post_types() {
    // Events
    register_post_type( 'pt_event', [
        'labels'      => [
            'name'          => __( 'Events', 'sapient-skateboards' ),
            'singular_name' => __( 'Event',  'sapient-skateboards' ),
        ],
        'public'      => true,
        'has_archive' => true,
        'supports'    => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'menu_icon'   => 'dashicons-calendar-alt',
        'rewrite'     => [ 'slug' => 'events' ],
    ] );

    // Sponsors
    register_post_type( 'pt_sponsor', [
        'labels'      => [
            'name'          => __( 'Sponsors', 'sapient-skateboards' ),
            'singular_name' => __( 'Sponsor',  'sapient-skateboards' ),
        ],
        'public'      => false,
        'show_ui'     => true,
        'supports'    => [ 'title', 'thumbnail' ],
        'menu_icon'   => 'dashicons-awards',
    ] );
}

// ── Helper: get ACF field with fallback ───────────────────────────────────────
function pt_field( $key, $fallback = '', $post_id = false ) {
    if ( ! function_exists( 'get_field' ) ) return $fallback;
    $val = $post_id ? get_field( $key, $post_id ) : get_field( $key );
    return $val ?: $fallback;
}

// ── Body classes ──────────────────────────────────────────────────────────────
add_filter( 'body_class', 'pt_body_classes' );
function pt_body_classes( $classes ) {
    if ( is_front_page() ) $classes[] = 'is-home';
    return $classes;
}

// ── Allow SVG uploads ─────────────────────────────────────────────────────────
add_filter( 'upload_mimes', 'pt_allow_svg' );
function pt_allow_svg( $mimes ) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}

// Fix SVG display in media library
add_filter( 'wp_check_filetype_and_ext', 'pt_fix_svg_mime', 10, 5 );
function pt_fix_svg_mime( $data, $file, $filename, $mimes, $real_mime ) {
    if ( ! $data['ext'] && ! $data['type'] ) {
        $ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
        if ( $ext === 'svg' || $ext === 'svgz' ) {
            $data['ext']  = $ext;
            $data['type'] = 'image/svg+xml';
        }
    }
    return $data;
}

// Show SVGs as thumbnails in media library
add_action( 'admin_head', 'pt_svg_thumb_css' );
function pt_svg_thumb_css() {
    echo '<style>
        img[src$=".svg"] { width: 100% !important; height: auto !important; }
        .attachment-266x266[src$=".svg"],
        .thumbnail[src$=".svg"] { width: 266px; height: auto; }
    </style>';
}

// ── Force Classic Editor (disable Gutenberg) ──────────────────────────────────
add_filter( 'use_block_editor_for_post', '__return_false', 10 );
add_filter( 'use_block_editor_for_post_type', '__return_false', 10 );

// ── Remove default BlankSlate junk ────────────────────────────────────────────
add_action( 'init', 'pt_cleanup' );
function pt_cleanup() {
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rsd_link' );
}

// ── Newsletter AJAX subscription ──────────────────────────────────────────────
add_action( 'wp_ajax_pt_newsletter_subscribe',        'pt_newsletter_subscribe' );
add_action( 'wp_ajax_nopriv_pt_newsletter_subscribe', 'pt_newsletter_subscribe' );

function pt_newsletter_subscribe() {
    check_ajax_referer( 'pt_newsletter_nonce', 'pt_nonce' );

    $email      = sanitize_email( $_POST['email'] ?? '' );
    $first_name = sanitize_text_field( $_POST['first_name'] ?? '' );

    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => 'Please enter a valid email address.' ] );
    }

    // Try MailPoet API if available
    if ( class_exists( '\MailPoet\API\API' ) ) {
        try {
            $api        = \MailPoet\API\API::MP( 'v1' );
            $subscriber = [ 'email' => $email ];
            if ( $first_name ) $subscriber['first_name'] = $first_name;
            $lists = $api->getLists();
            $list_ids = ! empty( $lists ) ? [ $lists[0]['id'] ] : [];
            $api->addSubscriber( $subscriber, $list_ids );
            wp_send_json_success( [ 'message' => "You're in! Welcome to the community. 🛹" ] );
        } catch ( \Exception $e ) {
            // If already subscribed, that's fine
            if ( strpos( $e->getMessage(), 'already subscribed' ) !== false ) {
                wp_send_json_success( [ 'message' => "You're already on the list — thanks! 🤙" ] );
            }
            wp_send_json_error( [ 'message' => 'Something went wrong. Please try again.' ] );
        }
    }

    // Fallback: save to WP options and email admin
    $subscribers = get_option( 'pt_newsletter_subscribers', [] );
    if ( isset( $subscribers[ $email ] ) ) {
        wp_send_json_success( [ 'message' => "You're already on the list — thanks! 🤙" ] );
    }
    $subscribers[ $email ] = [
        'first_name' => $first_name,
        'date'       => current_time( 'mysql' ),
    ];
    update_option( 'pt_newsletter_subscribers', $subscribers );

    wp_mail(
        get_option( 'admin_email' ),
        'New Newsletter Signup — Sapient Skateboards',
        "New subscriber:\nName: {$first_name}\nEmail: {$email}\nDate: " . current_time( 'mysql' ),
        [ 'Content-Type: text/plain; charset=UTF-8' ]
    );

    wp_send_json_success( [ 'message' => "You're in! Welcome to the community. 🛹" ] );
}

// ── Contact form AJAX ─────────────────────────────────────────
add_action('wp_ajax_pt_contact_submit',        'pt_contact_submit');
add_action('wp_ajax_nopriv_pt_contact_submit', 'pt_contact_submit');

function pt_contact_submit() {
    check_ajax_referer('pt_contact', 'pt_contact_nonce');
    $name    = sanitize_text_field($_POST['name'] ?? '');
    $email   = sanitize_email($_POST['email'] ?? '');
    $subject = sanitize_text_field($_POST['subject'] ?? 'General inquiry');
    $message = sanitize_textarea_field($_POST['message'] ?? '');
    if (!is_email($email) || empty($message)) {
        wp_send_json_error(['message' => 'Please fill in all required fields.']);
    }
    $body = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";
    wp_mail(get_option('admin_email'), "Contact Form: $subject", $body, ["Reply-To: $name <$email>"]);
    wp_send_json_success(['message' => "Thanks $name! We'll get back to you within 1–2 business days. 🤙"]);
}

// ── Shop: force custom template + strip all WooCommerce loop content ──────────
add_filter( 'template_include', function( $template ) {
    if ( ! function_exists('is_woocommerce') ) return $template;

    if ( is_product() ) {
        $custom = get_stylesheet_directory() . '/woocommerce/single-product.php';
        if ( file_exists( $custom ) ) return $custom;
    } elseif ( is_shop() || is_product_category() || is_product_tag() ) {
        $custom = get_stylesheet_directory() . '/woocommerce/archive-product.php';
        if ( file_exists( $custom ) ) return $custom;
    }
    return $template;
}, 999 );

add_action( 'init', function() {
    remove_action( 'woocommerce_shop_loop_item_title',       'woocommerce_template_loop_product_title', 10 );
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price',         10 );
    remove_action( 'woocommerce_after_shop_loop_item',       'woocommerce_template_loop_add_to_cart',   10 );
} );

// ── Product Size field (ACF) ──────────────────────────────────────────────────
add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;
    acf_add_local_field_group( [
        'key'      => 'group_product_sizes',
        'title'    => 'Available Sizes',
        'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'product' ] ] ],
        'fields'   => [ [
            'key'          => 'field_product_sizes',
            'label'        => 'Available Sizes',
            'name'         => 'product_sizes',
            'type'         => 'checkbox',
            'instructions' => 'Check the sizes available for this product.',
            'choices'      => [
                'XS'  => 'XS',
                'S'   => 'Small',
                'M'   => 'Medium',
                'L'   => 'Large',
                'XL'  => 'XL',
                'XXL' => 'XXL',
            ],
            'layout'       => 'horizontal',
            'return_format' => 'value',
        ] ],
    ] );
} );

// ── Size add-on: save to cart ─────────────────────────────────────────────────
add_filter( 'woocommerce_add_cart_item_data', function( $cart_item_data, $product_id, $variation_id ) {
    if ( ! empty( $_POST['sapient_size'] ) ) {
        $cart_item_data['sapient_size'] = sanitize_text_field( $_POST['sapient_size'] );
    }
    return $cart_item_data;
}, 10, 3 );

add_filter( 'woocommerce_get_item_data', function( $item_data, $cart_item ) {
    if ( ! empty( $cart_item['sapient_size'] ) ) {
        $item_data[] = [ 'key' => 'Size', 'value' => wc_clean( $cart_item['sapient_size'] ) ];
    }
    return $item_data;
}, 10, 2 );

add_action( 'woocommerce_checkout_create_order_line_item', function( $item, $cart_item_key, $values, $order ) {
    if ( ! empty( $values['sapient_size'] ) ) {
        $item->add_meta_data( 'Size', $values['sapient_size'], true );
    }
}, 10, 4 );

// ── Griptape add-on: save to cart item data ───────────────────────────────────
add_filter( 'woocommerce_add_cart_item_data', function( $cart_item_data, $product_id, $variation_id ) {
    if ( ! empty( $_POST['sapient_griptape'] ) ) {
        $cart_item_data['sapient_griptape'] = sanitize_text_field( $_POST['sapient_griptape'] );
    }
    return $cart_item_data;
}, 10, 3 );

// Display griptape option in cart and order
add_filter( 'woocommerce_get_item_data', function( $item_data, $cart_item ) {
    if ( ! empty( $cart_item['sapient_griptape'] ) ) {
        $item_data[] = [
            'key'   => 'Griptape',
            'value' => wc_clean( $cart_item['sapient_griptape'] ),
        ];
    }
    return $item_data;
}, 10, 2 );

// Save griptape to order meta
add_action( 'woocommerce_checkout_create_order_line_item', function( $item, $cart_item_key, $values, $order ) {
    if ( ! empty( $values['sapient_griptape'] ) ) {
        $item->add_meta_data( 'Griptape', $values['sapient_griptape'], true );
    }
}, 10, 4 );

// ── Intro Page ACF Fields ─────────────────────────────────────
add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;
    acf_add_local_field_group( [
        'key'      => 'group_intro_page',
        'title'    => 'Intro Page Settings',
        'location' => [ [ [
            'param'    => 'page_template',
            'operator' => '==',
            'value'    => 'page-intro.php',
        ] ] ],
        'fields' => [
            [
                'key'           => 'field_intro_video',
                'label'         => 'Background Video',
                'name'          => 'intro_video',
                'type'          => 'file',
                'return_format' => 'url',
                'library'       => 'all',
                'instructions'  => 'Upload an MP4 video file. Displayed full-screen behind the button.',
            ],
            [
                'key'          => 'field_intro_video_poster',
                'label'        => 'Video Poster (Fallback Image)',
                'name'         => 'intro_video_poster',
                'type'         => 'image',
                'return_format' => 'url',
                'preview_size' => 'medium',
                'instructions' => 'Shown while video loads or on mobile.',
            ],
            [
                'key'           => 'field_intro_logo',
                'label'         => 'Logo Overlay',
                'name'          => 'intro_logo_image',
                'type'          => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'instructions'  => 'Logo displayed above the content. Use a white/transparent PNG or SVG for best results on dark video.',
            ],
            [
                'key'           => 'field_intro_content',
                'label'         => 'Video Content',
                'name'          => 'intro_content',
                'type'          => 'wysiwyg',
                'toolbar'       => 'full',
                'media_upload'  => 0,
                'instructions'  => 'Text displayed over the video. Use headings, paragraphs, etc.',
            ],
            [
                'key'           => 'field_intro_button',
                'label'         => 'Enter Button',
                'name'          => 'intro_button',
                'type'          => 'link',
                'return_format' => 'array',
                'instructions'  => 'Sets the button label, URL, and whether it opens in a new tab.',
            ],
        ],
    ] );
} );

// ── FAQ Custom Post Type ──────────────────────────────────────
add_action( 'init', function() {
    register_post_type( 'faq', [
        'labels' => [
            'name'               => 'FAQs',
            'singular_name'      => 'FAQ',
            'add_new'            => 'Add FAQ',
            'add_new_item'       => 'Add New FAQ',
            'edit_item'          => 'Edit FAQ',
            'new_item'           => 'New FAQ',
            'view_item'          => 'View FAQ',
            'search_items'       => 'Search FAQs',
            'not_found'          => 'No FAQs found',
            'not_found_in_trash' => 'No FAQs in trash',
            'menu_name'          => 'FAQs',
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-editor-help',
        'menu_position' => 6,
        'supports'      => [ 'title', 'revisions' ],
    ] );
} );

// ── FAQ ACF Fields ────────────────────────────────────────────
add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;
    acf_add_local_field_group( [
        'key'      => 'group_faq_item',
        'title'    => 'FAQ Fields',
        'location' => [ [ [
            'param'    => 'post_type',
            'operator' => '==',
            'value'    => 'faq',
        ] ] ],
        'fields' => [
            [
                'key'   => 'field_faq_answer',
                'label' => 'Answer',
                'name'  => 'faq_answer',
                'type'  => 'wysiwyg',
                'toolbar'      => 'basic',
                'media_upload' => 0,
            ],
        ],
    ] );
} );

// ── Team Custom Post Type ─────────────────────────────────────
add_action( 'init', function() {
    register_post_type( 'team', [
        'labels' => [
            'name'               => 'Team',
            'singular_name'      => 'Team Member',
            'add_new'            => 'Add Member',
            'add_new_item'       => 'Add New Team Member',
            'edit_item'          => 'Edit Team Member',
            'new_item'           => 'New Team Member',
            'view_item'          => 'View Team Member',
            'search_items'       => 'Search Team',
            'not_found'          => 'No team members found',
            'not_found_in_trash' => 'No team members in trash',
            'menu_name'          => 'Team',
        ],
        'public'        => true,
        'has_archive'   => false,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-groups',
        'menu_position' => 5,
        'supports'      => [ 'title', 'thumbnail', 'revisions' ],
        'rewrite'       => [ 'slug' => 'team' ],
    ] );
} );

// ── Team ACF Fields ───────────────────────────────────────────
add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'      => 'group_team_member',
        'title'    => 'Team Member Fields',
        'location' => [ [ [
            'param'    => 'post_type',
            'operator' => '==',
            'value'    => 'team',
        ] ] ],
        'fields' => [
            [
                'key'   => 'field_team_position',
                'label' => 'Position / Role',
                'name'  => 'team_position',
                'type'  => 'text',
            ],
            [
                'key'          => 'field_team_description',
                'label'        => 'Description',
                'name'         => 'team_description',
                'type'         => 'wysiwyg',
                'toolbar'      => 'basic',
                'media_upload' => 0,
            ],
            [
                'key'           => 'field_team_image',
                'label'         => 'Photo',
                'name'          => 'team_image',
                'type'          => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
            ],

            [
                'key'        => 'field_team_socials',
                'label'      => 'Social Media',
                'name'       => 'team_socials',
                'type'       => 'repeater',
                'layout'     => 'table',
                'button_label' => 'Add Link',
                'sub_fields' => [
                    [
                        'key'     => 'field_team_social_platform',
                        'label'   => 'Platform',
                        'name'    => 'platform',
                        'type'    => 'select',
                        'choices' => [
                            'instagram' => 'Instagram',
                            'tiktok'    => 'TikTok',
                            'youtube'   => 'YouTube',
                            'twitter'   => 'Twitter / X',
                            'facebook'  => 'Facebook',
                            'website'   => 'Website',
                        ],
                    ],
                    [
                        'key'   => 'field_team_social_url',
                        'label' => 'URL',
                        'name'  => 'url',
                        'type'  => 'url',
                    ],
                ],
            ],
        ],
    ] );
} );

// ── WooCommerce: show product_cat in Quick Edit ───────────────
add_action( 'registered_taxonomy', function( $taxonomy ) {
    if ( $taxonomy !== 'product_cat' ) return;
    global $wp_taxonomies;
    if ( isset( $wp_taxonomies['product_cat'] ) ) {
        $wp_taxonomies['product_cat']->show_in_quick_edit = true;
    }
}, 99 );
