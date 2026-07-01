<?php
/**
 * Sapient Skateboards — BlankSlate Child Theme
 * functions.php
 */

// ── One-time migration (remove after running) ────────────────────────────────
if ( file_exists(__DIR__ . '/sapient-migrate.php') ) {
    require_once __DIR__ . '/sapient-migrate.php';
}

// ── Output buffer — prevent "headers already sent" issues ─────────────────────
if ( ! ob_get_level() ) {
    ob_start();
}

// ── Increase upload size limit (256MB is plenty for product photos + short videos)
@ini_set( 'upload_max_filesize', '256M' );
@ini_set( 'post_max_size',       '256M' );
add_filter( 'upload_size_limit', function() { return 256 * 1024 * 1024; } );

// ── Hide WooCommerce default backorder notice (we show our own "Made to Order" badge)
add_filter( 'woocommerce_product_backorders_notification', '__return_empty_string' );

// ── Disable third-party express checkout buttons (Amazon Pay, Stripe Link, etc.)
add_filter( 'wc_stripe_show_payment_request_on_product_page', '__return_false' );
add_filter( 'wc_stripe_show_payment_request_on_cart', '__return_false' );
add_filter( 'wc_stripe_show_payment_request_on_checkout', '__return_false' );
add_filter( 'woocommerce_amazon_pa_show_express_checkout_on_product_page', '__return_false' );
add_filter( 'woocommerce_amazon_pa_show_express_checkout_on_cart', '__return_false' );

// ═══════════════════════════════════════════════════════════════════
// PERFORMANCE OPTIMIZATIONS
// ═══════════════════════════════════════════════════════════════════

// 1. Resource hints — let the browser open connections early to
//    third-party domains we know we'll hit.
add_filter( 'wp_resource_hints', function( $urls, $relation_type ) {
    if ( $relation_type === 'preconnect' ) {
        $urls[] = [ 'href' => 'https://fonts.googleapis.com', 'crossorigin' => '' ];
        $urls[] = [ 'href' => 'https://fonts.gstatic.com',   'crossorigin' => '' ];
    }
    return $urls;
}, 10, 2 );

// 2. Disable WordPress emoji script (~14 KiB of JS nobody needs).
remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles',     'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles',  'print_emoji_styles' );
remove_filter( 'the_content_feed',    'wp_staticize_emoji' );
remove_filter( 'comment_text_rss',    'wp_staticize_emoji' );
remove_filter( 'wp_mail',             'wp_staticize_emoji_for_email' );

// 3. Disable WP's auto-loaded embed script (oEmbed JS).
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );
add_action( 'init', function() {
    wp_deregister_script( 'wp-embed' );
} );

// 4. Drop jQuery Migrate (legacy compat layer most modern themes don't need).
add_action( 'wp_default_scripts', function( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $deps = $scripts->registered['jquery']->deps;
        $scripts->registered['jquery']->deps = array_diff( $deps, [ 'jquery-migrate' ] );
    }
} );

// 5. Dequeue WooCommerce frontend assets on non-WC pages — this alone
//    can shave ~1MB off the homepage payload.
add_action( 'wp_enqueue_scripts', function() {
    if ( ! function_exists( 'is_woocommerce' ) ) return;
    if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) return;

    // WooCommerce CSS/JS
    wp_dequeue_style( 'wc-blocks-style' );
    wp_dequeue_style( 'wc-block-style' );
    wp_dequeue_style( 'woocommerce-layout' );
    wp_dequeue_style( 'woocommerce-smallscreen' );
    wp_dequeue_style( 'woocommerce-general' );
    wp_dequeue_style( 'woocommerce-inline' );
    wp_dequeue_script( 'wc-cart-fragments' );
    wp_dequeue_script( 'woocommerce' );
    wp_dequeue_script( 'wc-add-to-cart' );
    wp_dequeue_script( 'jquery-blockui' );
    wp_dequeue_script( 'js-cookie' );
}, 99 );

// 6. Defer non-critical JS (skip jQuery + admin-bar so nothing breaks).
add_filter( 'script_loader_tag', function( $tag, $handle ) {
    if ( is_admin() || is_user_logged_in() ) return $tag;
    $skip = [ 'jquery-core', 'jquery', 'admin-bar' ];
    if ( in_array( $handle, $skip, true ) ) return $tag;
    if ( strpos( $tag, ' defer' ) === false && strpos( $tag, ' async' ) === false ) {
        $tag = str_replace( ' src=', ' defer src=', $tag );
    }
    return $tag;
}, 10, 2 );

// 7. Preload the homepage hero image (LCP candidate) — fixes the
//    "LCP request discovery" audit. Only fires on the front page where
//    the content_and_image_ flex layout renders the hero.
add_action( 'wp_head', function() {
    if ( ! is_front_page() ) return;
    if ( ! function_exists( 'have_rows' ) ) return;
    $post_id = (int) get_option( 'page_on_front' );
    if ( ! $post_id ) return;
    if ( have_rows( 'page_sections', $post_id ) ) {
        while ( have_rows( 'page_sections', $post_id ) ) {
            the_row();
            if ( get_row_layout() === 'content_and_image_' ) {
                $img = get_sub_field( 'image' );
                if ( ! empty( $img['ID'] ) ) {
                    $src    = wp_get_attachment_image_url( (int) $img['ID'], 'large' );
                    $srcset = wp_get_attachment_image_srcset( (int) $img['ID'], 'large' );
                    if ( $src ) {
                        echo "<link rel=\"preload\" as=\"image\" href=\"" . esc_url( $src ) . "\"";
                        if ( $srcset ) {
                            echo " imagesrcset=\"" . esc_attr( $srcset ) . "\"";
                            echo " imagesizes=\"(max-width: 720px) 100vw, 720px\"";
                        }
                        echo " fetchpriority=\"high\">\n";
                    }
                }
                break; // only the first cai layout
            }
        }
        // Reset the row iterator so the loop in the template starts fresh.
        if ( function_exists( 'reset_rows' ) ) reset_rows();
    }
}, 2 );

// END PERFORMANCE OPTIMIZATIONS ════════════════════════════════════

// ── Default social share image. When the URL is pasted into iMessage,
// Slack, Facebook, Twitter/X, LinkedIn, WhatsApp, etc., this is the
// preview thumbnail. Pages that set their own og:image elsewhere (via
// SEO Framework or another plugin) will still take precedence on those
// platforms that respect last-tag-wins; the rest get this fallback.
add_action( 'wp_head', function() {
    $img = content_url( '/uploads/2026/06/sapient-train-share-image.webp' );
    $alt = 'Sapient Skateboards — Handcrafted boards made in Chicago';
    echo "<meta property=\"og:image\" content=\"{$img}\">\n";
    echo "<meta property=\"og:image:secure_url\" content=\"{$img}\">\n";
    echo "<meta property=\"og:image:type\" content=\"image/webp\">\n";
    echo "<meta property=\"og:image:width\" content=\"1993\">\n";
    echo "<meta property=\"og:image:height\" content=\"1122\">\n";
    echo "<meta property=\"og:image:alt\" content=\"" . esc_attr( $alt ) . "\">\n";
    echo "<meta name=\"twitter:card\" content=\"summary_large_image\">\n";
    echo "<meta name=\"twitter:image\" content=\"{$img}\">\n";
    echo "<meta name=\"twitter:image:alt\" content=\"" . esc_attr( $alt ) . "\">\n";
}, 1 );

// ── Favicons + Apple touch icon + PWA icons. Output via wp_head so the
// tags cover every page regardless of which header template is active.
add_action( 'wp_head', function() {
    $u = get_stylesheet_directory_uri() . '/assets/images';
    echo "<link rel=\"icon\" type=\"image/svg+xml\" href=\"{$u}/favicon.svg\">\n";
    echo "<link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"{$u}/favicon-32.png\">\n";
    echo "<link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"{$u}/favicon-16.png\">\n";
    echo "<link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"{$u}/favicon-180.png\">\n";
    echo "<link rel=\"icon\" type=\"image/png\" sizes=\"192x192\" href=\"{$u}/favicon-192.png\">\n";
    echo "<link rel=\"icon\" type=\"image/png\" sizes=\"512x512\" href=\"{$u}/favicon-512.png\">\n";
}, 2 );

// ── Search query: never surface drafts/private/pending posts on the
// frontend, even when the viewer is logged in as admin. WP's default
// includes those statuses for editors/admins, which is what was causing
// in-progress entries (e.g. a placeholder "TEAM MEMBER NAME") to leak
// into search results on the live preview.
add_action( 'pre_get_posts', function( $query ) {
    if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
        return;
    }
    $query->set( 'post_status', 'publish' );
} );

// ── Page fade-in: inline <head> script gates the animation to the first
// navigation in a session. Runs before any paint to avoid a flash.
add_action( 'wp_head', function() {
    echo "<script>(function(){try{if(!sessionStorage.getItem('ssFade')){document.documentElement.classList.add('fade-in');sessionStorage.setItem('ssFade','1');}}catch(e){}})();</script>\n";
}, 1 );

// ── Performance: ensure every <img> ships with loading="lazy" + decoding="async"
// WP-generated images: hook the attachment-image attributes filter.
add_filter( 'wp_get_attachment_image_attributes', function( $attr ) {
    if ( empty( $attr['loading'] ) )  $attr['loading']  = 'lazy';
    if ( empty( $attr['decoding'] ) ) $attr['decoding'] = 'async';
    return $attr;
}, 10, 1 );

// Theme-template <img> tags: post-process the rendered HTML via an output
// buffer (after PHP has been evaluated, so PHP tags don't confuse the regex).
// Skips images that already declare loading / decoding, and skips the header
// site logo + hero images so above-the-fold content stays priority.
add_action( 'template_redirect', function() {
    if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
        return;
    }
    ob_start( function( $html ) {
        return preg_replace_callback(
            '/<img\b([^>]*)>/i',
            function( $m ) {
                $attrs = $m[1];
                // Skip above-the-fold images (LCP) — heuristics: header logo,
                // hero, brand logo, h2 logo, fetchpriority=high already set.
                if ( preg_match( '/class="[^"]*(brand-logo|h2-logo|ss-logo--header|hero-image|hero-overlay|hero-video|fetchpriority)/i', $attrs )
                  || stripos( $attrs, 'fetchpriority="high"' ) !== false ) {
                    return $m[0];
                }
                if ( stripos( $attrs, 'loading=' ) === false )  $attrs .= ' loading="lazy"';
                if ( stripos( $attrs, 'decoding=' ) === false ) $attrs .= ' decoding="async"';
                return "<img{$attrs}>";
            },
            $html
        );
    });
}, 1 );

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

// ── Process page fonts (Bebas Neue + Barlow) ─────────────────────────────────
add_action( 'wp_enqueue_scripts', function() {
    if ( is_page_template( 'page-process.php' ) ) {
        wp_enqueue_style(
            'process-fonts',
            'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;500&display=swap',
            [],
            null
        );
    }
} );

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

// ── Add aria-current="page" to active nav menu links for accessibility ───────
add_filter( 'nav_menu_link_attributes', function( $atts, $item ) {
    if ( in_array( 'current-menu-item', (array) $item->classes, true ) ) {
        $atts['aria-current'] = 'page';
    }
    return $atts;
}, 10, 2 );

// ── Highlight Team nav item when on team pages ──────────────────────────────
add_filter( 'nav_menu_css_class', function( $classes, $item ) {
    if ( is_singular( 'team' ) || is_post_type_archive( 'team' ) || is_page( 'crew' ) ) {
        if ( stripos( $item->title, 'team' ) !== false || strpos( $item->url, '/team' ) !== false || strpos( $item->url, '/crew' ) !== false ) {
            $classes[] = 'current-menu-item';
        }
    }
    // Highlight Products nav on shop/product pages
    if ( is_shop() || is_product() || is_product_category() ) {
        if ( $item->object_id == get_option( 'woocommerce_shop_page_id' ) ) {
            $classes[] = 'current-menu-item';
        }
    }
    return $classes;
}, 10, 2 );

// ── Body class on Events page so we can scope page-specific styles ──────────
add_filter( 'body_class', function( $classes ) {
    if ( is_page( 'events' ) ) {
        $classes[] = 'page-events';
    }
    return $classes;
} );

// ── WooCommerce: cart fragment for AJAX cart count ────────────────────────────
add_filter( 'woocommerce_add_to_cart_fragments', 'pt_cart_count_fragment' );
function pt_cart_count_fragment( $fragments ) {
    $count = WC()->cart->get_cart_contents_count();
    $fragments['.cart-count'] = '<span class="cart-count' . ( $count ? ' has-items' : '' ) . '">' . esc_html( $count ) . '</span>';
    $fragments['.h2-cart-count'] = '<span class="h2-cart-count' . ( $count ? ' has-items' : '' ) . '" id="header-cart-count">' . esc_html( $count ) . '</span>';
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
    // Events (CPT kept for future use, but URL slug freed up so the
    // /events/ page (post ID 270) can render the landing page).
    register_post_type( 'pt_event', [
        'labels'      => [
            'name'          => __( 'Events', 'sapient-skateboards' ),
            'singular_name' => __( 'Event',  'sapient-skateboards' ),
        ],
        'public'      => true,
        'has_archive' => false,
        'supports'    => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'menu_icon'   => 'dashicons-calendar-alt',
        'rewrite'     => [ 'slug' => 'event' ],
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

// ── Allow SVG + video uploads ─────────────────────────────────────────────────
add_filter( 'upload_mimes', 'pt_allow_svg' );
function pt_allow_svg( $mimes ) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    $mimes['mp4']  = 'video/mp4';
    $mimes['m4v']  = 'video/mp4';
    $mimes['mov']  = 'video/quicktime';
    $mimes['webm'] = 'video/webm';
    return $mimes;
}

// ── Allow video MIME type verification to pass ────────────────────────────────
add_filter( 'wp_check_filetype_and_ext', 'pt_fix_video_mime', 10, 5 );
function pt_fix_video_mime( $data, $file, $filename, $mimes, $real_mime ) {
    $video_exts = [ 'mp4', 'm4v', 'mov', 'webm' ];
    $ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
    if ( in_array( $ext, $video_exts, true ) ) {
        $data['ext']  = $ext;
        $data['type'] = $ext === 'webm' ? 'video/webm' : ( $ext === 'mov' ? 'video/quicktime' : 'video/mp4' );
    }
    return $data;
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
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
}

// ── Performance & Accessibility ───────────────────────────────────────────────

// Force lazy loading on all WP-generated images not already marked eager
add_filter( 'wp_lazy_loading_enabled', '__return_true' );

// Performance: decoding=async + lazy load on all WP attachment images
add_filter( 'wp_get_attachment_image_attributes', function( $attr, $attachment, $size ) {
    // Always async decode
    $attr['decoding'] = 'async';

    // Lazy load everything except explicitly eager-flagged images
    if ( empty( $attr['loading'] ) ) {
        $attr['loading'] = 'lazy';
    }

    return $attr;
}, 10, 3 );

// Auto-generate WebP versions on upload (WP 6.1+)
add_filter( 'wp_upload_image_mime_transforms', function( $transforms ) {
    $transforms['image/jpeg'][] = 'image/webp';
    $transforms['image/png'][]  = 'image/webp';
    return $transforms;
} );

// Serve WebP if browser supports it + file exists
add_filter( 'wp_get_attachment_image_src', function( $image ) {
    if ( ! $image ) return $image;
    $webp = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $image[0] );
    if ( $webp !== $image[0] && file_exists( str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $webp ) ) ) {
        $image[0] = $webp;
    }
    return $image;
} );

// Ensure alt text fallback — use attachment title if alt is empty
add_filter( 'wp_get_attachment_image_attributes', function( $attr, $attachment ) {
    if ( empty( $attr['alt'] ) ) {
        $attr['alt'] = trim( strip_tags( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ) ) );
        if ( empty( $attr['alt'] ) ) {
            $attr['alt'] = trim( strip_tags( $attachment->post_title ) );
        }
    }
    return $attr;
}, 20, 2 );

// Remove WooCommerce bloat scripts we don't use
add_action( 'wp_enqueue_scripts', function() {
    if ( ! is_checkout() && ! is_cart() ) {
        wp_dequeue_style( 'wc-blocks-style' );
    }
}, 100 );

// Add theme-color meta for mobile browsers
add_action( 'wp_head', function() {
    echo '<meta name="theme-color" content="#141414">' . "\n";
    echo '<meta name="color-scheme" content="light">' . "\n";
}, 1 );

// SEO: canonical URLs (lightweight, no plugin needed)
add_action( 'wp_head', function() {
    if ( is_singular() ) {
        echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";
    } elseif ( is_front_page() ) {
        echo '<link rel="canonical" href="' . esc_url( home_url( '/' ) ) . '">' . "\n";
    }
}, 5 );

// Preconnect to Google Fonts (process page loads them)
add_action( 'wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 2 );

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

// ── Product Size field (ACF repeater) ────────────────────────────────────────
add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;
    acf_add_local_field_group( [
        'key'      => 'group_product_sizes',
        'title'    => 'Available Sizes',
        'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'product' ] ] ],
        'fields'   => [ [
            'key'          => 'field_product_sizes',
            'label'        => 'Sizes',
            'name'         => 'product_sizes',
            'type'         => 'repeater',
            'instructions' => 'Add sizes in order. Boards: 8.0, 8.25, 8.5 etc. Apparel: XS, S, M, L, XL.',
            'button_label' => 'Add Size',
            'layout'       => 'table',
            'sub_fields'   => [ [
                'key'          => 'field_size_value',
                'label'        => 'Size',
                'name'         => 'size_value',
                'type'         => 'text',
                'placeholder'  => 'e.g. 8.25 or M',
                'column_width' => '100',
            ] ],
        ] ],
    ] );
} );

// ── Process Page ACF fields ───────────────────────────────────────────────────
add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;
    acf_add_local_field_group( [
        'key'      => 'group_process_page',
        'title'    => 'Process Page',
        'location' => [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-process.php' ] ] ],
        'fields'   => [
            [
                'key'   => 'field_process_hero_image',
                'label' => 'Hero Image',
                'name'  => 'process_hero_image',
                'type'  => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'instructions'  => 'Full-viewport hero — wide warehouse/industrial shot.',
            ],
            [
                'key'         => 'field_process_hero_headline',
                'label'       => 'Hero Headline',
                'name'        => 'process_hero_headline',
                'type'        => 'text',
                'default_value' => 'Built here. No exceptions.',
                'instructions'  => 'Large overlaid headline on the hero image.',
            ],
            [
                'key'          => 'field_process_hero_sub',
                'label'        => 'Hero Subtext',
                'name'         => 'process_hero_sub',
                'type'         => 'textarea',
                'rows'         => 3,
                'default_value'=> 'We don\'t outsource the hard part.',
            ],
            [
                'key'          => 'field_process_steps_repeater',
                'label'        => 'Process Steps',
                'name'         => 'process_steps_repeater',
                'type'         => 'repeater',
                'button_label' => 'Add Step',
                'layout'       => 'block',
                'sub_fields'   => [
                    [
                        'key'           => 'field_ps_number',
                        'label'         => 'Step Number',
                        'name'          => 'step_number',
                        'type'          => 'text',
                        'placeholder'   => '01',
                        'wrapper'       => [ 'width' => '15' ],
                    ],
                    [
                        'key'           => 'field_ps_eyebrow',
                        'label'         => 'Eyebrow',
                        'name'          => 'step_eyebrow',
                        'type'          => 'text',
                        'placeholder'   => 'The Wood',
                        'wrapper'       => [ 'width' => '85' ],
                    ],
                    [
                        'key'           => 'field_ps_headline',
                        'label'         => 'Headline',
                        'name'          => 'step_headline',
                        'type'          => 'text',
                        'placeholder'   => 'It starts in the Midwest.',
                    ],
                    [
                        'key'           => 'field_ps_body',
                        'label'         => 'Body',
                        'name'          => 'step_body',
                        'type'          => 'wysiwyg',
                        'tabs'          => 'visual',
                        'toolbar'       => 'basic',
                        'media_upload'  => 0,
                    ],
                    [
                        'key'           => 'field_ps_image',
                        'label'         => 'Image',
                        'name'          => 'step_image',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'medium',
                        'instructions'  => 'Raw, industrial — see copy doc for image notes.',
                    ],
                    [
                        'key'           => 'field_ps_image_caption',
                        'label'         => 'Image Caption',
                        'name'          => 'step_image_caption',
                        'type'          => 'text',
                        'placeholder'   => 'Optional caption shown below image',
                    ],
                ],
            ],
        ],
    ] );
} );

// ── Hide Griptape toggle (ACF) ────────────────────────────────────────────────
add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;
    acf_add_local_field_group( [
        'key'      => 'group_product_griptape',
        'title'    => 'Griptape Add-on',
        'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'product' ] ] ],
        'fields'   => [ [
            'key'          => 'field_hide_griptape',
            'label'        => 'Hide Griptape Option',
            'name'         => 'hide_griptape',
            'type'         => 'true_false',
            'instructions' => 'Check to remove the griptape selector from this product page.',
            'ui'           => 1,
            'default_value'=> 0,
        ] ],
    ] );
} );

// ── Product Color field (ACF repeater) ───────────────────────────────────────
add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;
    acf_add_local_field_group( [
        'key'      => 'group_product_colors',
        'title'    => 'Available Colors',
        'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'product' ] ] ],
        'fields'   => [ [
            'key'          => 'field_product_colors',
            'label'        => 'Colors',
            'name'         => 'product_colors',
            'type'         => 'repeater',
            'instructions' => 'Add, reorder, or remove color options for this product.',
            'button_label' => 'Add Color',
            'layout'       => 'table',
            'sub_fields'   => [
                [
                    'key'           => 'field_color_name',
                    'label'         => 'Name',
                    'name'          => 'color_name',
                    'type'          => 'text',
                    'placeholder'   => 'e.g. Midnight Black',
                    'column_width'  => '50',
                ],
                [
                    'key'           => 'field_color_hex',
                    'label'         => 'Color',
                    'name'          => 'color_hex',
                    'type'          => 'color_picker',
                    'default_value' => '#000000',
                    'column_width'  => '50',
                ],
            ],
        ] ],
    ] );
} );

// ── Hide product attributes/meta from cart/checkout order summary ────────────
add_filter( 'woocommerce_display_item_meta', '__return_empty_string', 10, 3 );

// Hide attributes from block-based checkout via Store API
add_filter( 'woocommerce_get_item_data', function( $item_data, $cart_item ) {
    $keep = [];
    foreach ( $item_data as $data ) {
        $key = strtolower( $data['key'] ?? '' );
        if ( in_array( $key, [ 'color', 'size', 'griptape' ], true ) ) {
            $keep[] = $data;
        }
    }
    return $keep;
}, 999, 2 );

// Strip attributes and short description from Store API cart responses
add_filter( 'rest_request_after_callbacks', function( $response, $handler, $request ) {
    if ( ! ( $response instanceof WP_REST_Response ) ) return $response;
    $route = $request->get_route();
    if ( strpos( $route, 'wc/store' ) === false ) return $response;

    // Never cache Store API cart responses
    $response->header( 'Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0' );

    $data = $response->get_data();
    // Strip only visual metadata, preserve item_data for checkout functionality
    if ( isset( $data['items'] ) && is_array( $data['items'] ) ) {
        foreach ( $data['items'] as &$item ) {
            $item['short_description'] = '';
            $item['description'] = '';
            // Keep item_data — checkout needs it for cart validation
            if ( isset( $item['extensions'] ) ) {
                $item['extensions'] = (object) [];
            }
        }
        $response->set_data( $data );
    }
    return $response;
}, 10, 3 );

// CSS to hide metadata/attributes in block checkout but keep product name visible
add_action( 'wp_head', function() {
    if ( is_checkout() || is_cart() ) {
        echo '<style>
            .wc-block-components-product-metadata,
            .wc-block-components-order-summary-item__individual-prices { display: none !important; }
            .wc-block-components-order-summary-item__description { display: block !important; }
            .wc-block-components-product-name { display: block !important; }
            .wc-block-checkout,
            .wc-block-checkout input,
            .wc-block-checkout select,
            .wc-block-checkout textarea,
            .wc-block-checkout label,
            .wc-block-checkout .wc-block-components-text-input input,
            .wc-block-checkout .wc-block-components-combobox input { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important; }
            .wc-block-components-checkout-place-order-button { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important; font-size: 18px !important; }
        </style>';
    }
} );

// Force Size label to match Availability/Quantity heading style on product pages
add_action( 'wp_head', function() {
    if ( is_product() ) {
        echo '<style>
            .product-single-details .variations td.label,
            .product-single-details .variations td.label label,
            .product-single-details label[for^="pa_"] {
                font-family: "Engravers Gothic BT", Futura, "Century Gothic", Helvetica, sans-serif !important;
                font-size: 1.125rem !important;
                font-weight: 700 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.12em !important;
                color: rgba(0,0,0,0.4) !important;
                text-align: left !important;
                display: block !important;
            }
        </style>';
    }
} );

// ── Color add-on: save to cart ────────────────────────────────────────────────
add_filter( 'woocommerce_add_cart_item_data', function( $cart_item_data, $product_id, $variation_id ) {
    if ( ! empty( $_POST['sapient_color'] ) ) {
        $cart_item_data['sapient_color'] = sanitize_text_field( $_POST['sapient_color'] );
    }
    return $cart_item_data;
}, 10, 3 );

add_filter( 'woocommerce_get_item_data', function( $item_data, $cart_item ) {
    if ( ! empty( $cart_item['sapient_color'] ) ) {
        $item_data[] = [ 'key' => 'Color', 'value' => wc_clean( $cart_item['sapient_color'] ) ];
    }
    return $item_data;
}, 10, 2 );

add_action( 'woocommerce_checkout_create_order_line_item', function( $item, $cart_item_key, $values, $order ) {
    if ( ! empty( $values['sapient_color'] ) ) {
        $item->add_meta_data( 'Color', $values['sapient_color'], true );
    }
}, 10, 4 );

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

// ── Team Custom Post Type + Category Taxonomy ─────────────────
add_action( 'init', function() {
    // Team Category taxonomy (for filtering crew grid)
    register_taxonomy( 'team_category', 'team', [
        'labels' => [
            'name'              => 'Team Categories',
            'singular_name'     => 'Team Category',
            'search_items'      => 'Search Team Categories',
            'all_items'         => 'All Team Categories',
            'parent_item'       => 'Parent Category',
            'parent_item_colon' => 'Parent Category:',
            'edit_item'         => 'Edit Team Category',
            'update_item'       => 'Update Team Category',
            'add_new_item'      => 'Add New Team Category',
            'new_item_name'     => 'New Team Category Name',
            'menu_name'         => 'Categories',
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'team-category' ],
    ] );
} );

// ── Redirect single team posts → crew page ────────────────────
add_action( 'template_redirect', function() {
    if ( is_singular( 'team' ) ) {
        $crew_page = get_page_by_path( 'crew' ) ?: get_page_by_path( 'about/crew' );
        $redirect  = $crew_page ? get_permalink( $crew_page ) : home_url( '/about/crew/' );
        wp_redirect( $redirect, 301 );
        exit;
    }
} );

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
        'supports'      => [ 'title', 'thumbnail', 'revisions', 'page-attributes' ],
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

// ── Header Style Settings ─────────────────────────────────────
function sapient_get_active_header() {
    return get_option( 'sapient_header_style', 'one' ) === 'two' ? 'two' : '';
}

add_action( 'admin_menu', function() {
    add_submenu_page(
        'themes.php',
        'Header Style',
        'Header Style',
        'manage_options',
        'sapient-header-style',
        'sapient_header_style_page'
    );
} );

function sapient_header_style_page() {
    if ( isset( $_POST['sapient_header_style'] ) && check_admin_referer( 'sapient_header_style_save' ) ) {
        update_option( 'sapient_header_style', sanitize_text_field( $_POST['sapient_header_style'] ) );
        echo '<div class="updated"><p>Header style saved.</p></div>';
    }
    $current = get_option( 'sapient_header_style', 'one' );
    ?>
    <div class="wrap">
        <h1>Header Style</h1>
        <p style="color:#666;margin-bottom:1.5rem;">Switch between the two header layouts sitewide.</p>
        <form method="post">
            <?php wp_nonce_field( 'sapient_header_style_save' ); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Active Header</th>
                    <td>
                        <label style="display:flex;align-items:center;gap:10px;margin-bottom:12px;font-size:14px;">
                            <input type="radio" name="sapient_header_style" value="one" <?php checked( $current, 'one' ); ?>>
                            <strong>Header One</strong> — Logo top, navigation row below
                        </label>
                        <label style="display:flex;align-items:center;gap:10px;font-size:14px;">
                            <input type="radio" name="sapient_header_style" value="two" <?php checked( $current, 'two' ); ?>>
                            <strong>Header Two</strong> — Single row: logo left, nav center, search + cart right
                        </label>
                    </td>
                </tr>
            </table>
            <?php submit_button( 'Save Header Style' ); ?>
        </form>
    </div>
    <?php
}

// ── Suppliers Page ACF Fields ─────────────────────────────────
add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'      => 'group_suppliers_page',
        'title'    => 'Suppliers',
        'location' => [ [ [
            'param'    => 'page_template',
            'operator' => '==',
            'value'    => 'page-suppliers.php',
        ] ] ],
        'fields' => [
            [
                'key'          => 'field_suppliers_list',
                'label'        => 'Suppliers',
                'name'         => 'suppliers',
                'type'         => 'repeater',
                'layout'       => 'block',
                'button_label' => 'Add Supplier',
                'sub_fields'   => [
                    [
                        'key'   => 'field_supplier_name',
                        'label' => 'Store Name',
                        'name'  => 'supplier_name',
                        'type'  => 'text',
                    ],
                    [
                        'key'         => 'field_supplier_address',
                        'label'       => 'Address',
                        'name'        => 'supplier_address',
                        'type'        => 'text',
                        'placeholder' => '123 Main St, Chicago, IL 60601',
                    ],
                    [
                        'key'           => 'field_supplier_image',
                        'label'         => 'Store Photo',
                        'name'          => 'supplier_image',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'medium',
                    ],
                    [
                        'key'         => 'field_supplier_website',
                        'label'       => 'Website URL',
                        'name'        => 'supplier_website',
                        'type'        => 'url',
                        'placeholder' => 'https://',
                    ],
                ],
            ],
        ],
    ] );
} );

// ── Contact Us Page ACF Fields ────────────────────────────────
add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'      => 'group_contact_page',
        'title'    => 'Contact Us — Page Content',
        'location' => [ [ [
            'param'    => 'post_type',
            'operator' => '==',
            'value'    => 'page',
        ], [
            'param'    => 'post',
            'operator' => '==',
            'value'    => 72,
        ] ] ],
        'menu_order' => 0,
        'position'   => 'normal',
        'description'=> 'Editable content for the Contact Us page. Renders inside the Contact Form layout.',
        'fields' => [
            [
                'key'         => 'field_contact_title',
                'label'       => 'Title',
                'name'        => 'contact_title',
                'type'        => 'text',
                'placeholder' => 'Contact Us',
                'instructions'=> 'Heading shown next to the form. Defaults to "Contact Us" if blank.',
            ],
            [
                'key'         => 'field_contact_content',
                'label'       => 'Content (next to form)',
                'name'        => 'contact_content',
                'type'        => 'wysiwyg',
                'tabs'        => 'all',
                'toolbar'     => 'full',
                'media_upload'=> 0,
                'delay'       => 0,
                'instructions'=> 'Main content block shown next to the contact form (intro paragraph, additional info, etc.).',
            ],
            [
                'key'          => 'field_contact_email',
                'label'        => 'Email Address',
                'name'         => 'contact_email',
                'type'         => 'email',
                'wrapper'      => [ 'width' => '50' ],
                'instructions' => 'Public contact email. Leave blank to hide the email row.',
            ],
            [
                'key'          => 'field_contact_phone_display',
                'label'        => 'Phone (display)',
                'name'         => 'contact_phone_display',
                'type'         => 'text',
                'wrapper'      => [ 'width' => '50' ],
                'instructions' => 'How the phone number is displayed (e.g. (630) 624-2595).',
            ],
            [
                'key'          => 'field_contact_phone_link',
                'label'        => 'Phone (link)',
                'name'         => 'contact_phone_link',
                'type'         => 'text',
                'wrapper'      => [ 'width' => '50' ],
                'instructions' => 'Digits-only for tel: link (e.g. +16306242595). Blank = display only, no link.',
            ],
            [
                'key'          => 'field_contact_location',
                'label'        => 'Address / Location',
                'name'         => 'contact_location',
                'type'         => 'wysiwyg',
                'tabs'         => 'visual',
                'toolbar'      => 'basic',
                'media_upload' => 0,
                'delay'        => 0,
                'instructions' => 'Address, city, or location. Rich text supported.',
            ],
            [
                'key'          => 'field_contact_form_shortcode',
                'label'        => 'Form Shortcode',
                'name'         => 'contact_form_shortcode',
                'type'         => 'text',
                'placeholder'  => '[gravityform id="1" title="false" description="false"]',
                'instructions' => 'Paste any form shortcode here — Gravity Forms, Contact Form 7, Fluent Forms, etc. Leave blank to use Contact Form 7 (id=122).',
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


// ── Cart added lightbox ──────────────────────────────────────
add_action( 'wp_footer', function() {
    $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart');
    ?>
    <div id="cart-lightbox" class="cart-lightbox" role="dialog" aria-modal="true" aria-label="Item added to cart">
      <div class="cart-lightbox-overlay"></div>
      <div class="cart-lightbox-box">
        <button class="cart-lightbox-close" aria-label="Close">&times;</button>
        <p class="cart-lightbox-msg">Item added to your cart</p>
        <div class="cart-lightbox-actions">
          <a href="<?php echo esc_url( $cart_url ); ?>" class="cart-lightbox-btn cart-lightbox-view">VIEW CART</a>
          <button class="cart-lightbox-btn cart-lightbox-continue">CONTINUE SHOPPING</button>
        </div>
      </div>
    </div>
    <?php
} );

// ── AJAX add to cart (single product page) ────────────────────
add_action( 'wp_ajax_nopriv_sapient_add_to_cart', 'sapient_ajax_add_to_cart' );
add_action( 'wp_ajax_sapient_add_to_cart',        'sapient_ajax_add_to_cart' );
function sapient_ajax_add_to_cart() {
    if ( ! check_ajax_referer( 'sapient_cart', 'nonce', false ) ) {
        wp_send_json_error( [ 'message' => 'Security check failed.' ], 403 );
    }

    // Ensure WC session cookie is set (critical for Block checkout / Store API)
    if ( WC()->session && ! WC()->session->has_session() ) {
        WC()->session->set_customer_session_cookie( true );
    }

    $product_id   = intval( $_POST['product_id'] ?? 0 );
    $quantity     = max( 1, intval( $_POST['quantity'] ?? 1 ) );
    $variation_id = intval( $_POST['variation_id'] ?? 0 );
    $variation    = [];

    foreach ( $_POST as $key => $val ) {
        if ( strpos( $key, 'attribute_' ) === 0 ) {
            $variation[ sanitize_text_field( $key ) ] = sanitize_text_field( $val );
        }
    }

    $added = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );

    if ( $added ) {
        WC()->cart->calculate_totals();
        WC()->cart->maybe_set_cart_cookies();
        // Force session write so Block checkout (Store API) picks it up
        if ( WC()->session ) {
            WC()->session->save_data();
        }
        do_action( 'woocommerce_ajax_added_to_cart', $product_id );

        $count      = WC()->cart->get_cart_contents_count();
        $cart_items = WC()->cart->get_cart();

        // Build cart preview HTML
        ob_start();
        if ( empty( $cart_items ) ) {
            echo '<p class="cart-preview-empty">Your cart is empty.</p>';
        } else {
            echo '<table class="cart-preview-table"><thead><tr>';
            echo '<th class="cpt-img"></th><th class="cpt-name">Product</th><th class="cpt-qty">Qty</th><th class="cpt-price">Price</th>';
            echo '</tr></thead><tbody>';
            foreach ( $cart_items as $item ) {
                $p       = $item['data'];
                $img_id  = $p->get_image_id();
                $img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : wc_placeholder_img_src();
                echo '<tr class="cpt-row">';
                echo '<td class="cpt-img"><img src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $p->get_name() ) . '"></td>';
                echo '<td class="cpt-name">' . esc_html( $p->get_name() ) . '</td>';
                echo '<td class="cpt-qty">' . esc_html( $item['quantity'] ) . '</td>';
                echo '<td class="cpt-price">' . wc_price( $p->get_price() ) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
            echo '<div class="cart-preview-footer">';
            echo '<span class="cart-preview-total">Total: ' . WC()->cart->get_cart_total() . '</span>';
            echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="btn-primary cart-preview-btn">View Cart</a>';
            echo '</div>';
        }
        $preview_html = ob_get_clean();

        wp_send_json_success( [
            'count'        => $count,
            'cart_hash'    => WC()->cart->get_cart_hash(),
            'preview_html' => $preview_html,
        ] );
    } else {
        wp_send_json_error( [ 'message' => 'Could not add item to cart.' ] );
    }
}

// ── AJAX update cart quantity ─────────────────────────────────
add_action( 'wp_ajax_nopriv_sapient_update_cart', 'sapient_ajax_update_cart' );
add_action( 'wp_ajax_sapient_update_cart',        'sapient_ajax_update_cart' );
function sapient_ajax_update_cart() {
    if ( ! check_ajax_referer( 'sapient_cart', 'nonce', false ) ) {
        wp_send_json_error( [ 'message' => 'Security check failed.' ], 403 );
    }

    if ( WC()->session && ! WC()->session->has_session() ) {
        WC()->session->set_customer_session_cookie( true );
    }

    $cart_key = sanitize_text_field( $_POST['cart_key'] ?? '' );
    $quantity = intval( $_POST['quantity'] ?? 1 );

    if ( ! $cart_key ) {
        wp_send_json_error( [ 'message' => 'Invalid cart item.' ] );
    }

    if ( $quantity <= 0 ) {
        WC()->cart->remove_cart_item( $cart_key );
    } else {
        WC()->cart->set_quantity( $cart_key, $quantity );
    }

    WC()->cart->calculate_totals();
    WC()->cart->maybe_set_cart_cookies();
    if ( WC()->session ) {
        WC()->session->save_data();
    }

    $count = WC()->cart->get_cart_contents_count();
    $cart  = WC()->cart;

    wp_send_json_success( [
        'count'    => $count,
        'subtotal' => $cart->get_cart_subtotal(),
        'total'    => $cart->get_cart_total(),
        'empty'    => $count === 0,
    ] );
}

// Localise AJAX url for front-end (priority 20 so script is already registered)
add_action( 'wp_enqueue_scripts', function() {
    wp_localize_script( 'sapient-skateboards-js', 'sapientAjax', [
        'url'              => admin_url( 'admin-ajax.php' ),
        'newsletter_nonce' => wp_create_nonce( 'sapient_newsletter' ),
        'cart_nonce'       => wp_create_nonce( 'sapient_cart' ),
    ] );
}, 20 );

// ── Hide shipping from cart (show only at checkout) ───────────
add_filter( 'woocommerce_shipping_show_delivery_times',  '__return_false' );
remove_action( 'woocommerce_cart_totals_after_order_total', 'woocommerce_shipping_calculator' );
add_action( 'wp_head', function() {
    if ( is_cart() ) {
        echo '<style>.cart-collaterals .shipping-calculator-form, .cart_totals .shipping, tr.shipping { display:none !important; }</style>';
    }
} );

// ── Conditional shipping: boards vs apparel ───────────────────
// Board in cart  → show "Standard Shipping (Boards)" $20, hide apparel rate.
// Apparel only   → show "Flat Rate (Apparel Only)" $6.50, hide boards rate.
// Board + shirt  → show boards rate only (shirt ships with the board).
add_filter( 'woocommerce_package_rates', function( $rates, $package ) {
    $has_board   = false;
    $has_apparel = false;

    foreach ( $package['contents'] as $item ) {
        $product = $item['data'];
        $class   = $product->get_shipping_class();
        if ( $class === 'boards' )  $has_board   = true;
        if ( $class === 'apparel' ) $has_apparel = true;
    }

    if ( $has_board ) {
        // Board in cart — remove the apparel-only rate.
        unset( $rates['flat_rate:7'] );
    } else if ( $has_apparel ) {
        // Apparel only — remove the boards rate.
        unset( $rates['flat_rate:3'] );
    }

    return $rates;
}, 10, 2 );

// ── Newsletter signup handler ──────────────────────────────────
add_action( 'wp_ajax_sapient_newsletter',        'sapient_newsletter_handler' );
add_action( 'wp_ajax_nopriv_sapient_newsletter', 'sapient_newsletter_handler' );

function sapient_newsletter_handler() {
    if ( ! check_ajax_referer( 'sapient_newsletter', 'nonce', false ) ) {
        wp_send_json_error( [ 'message' => 'Security check failed.' ], 403 );
    }

    $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
    $phone = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';

    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => 'Please enter a valid email address.' ] );
    }

    // Store signups as a WP option (array)
    $signups = get_option( 'sapient_newsletter_signups', [] );
    foreach ( $signups as $existing ) {
        if ( $existing['email'] === $email ) {
            wp_send_json_success( [ 'message' => "You're already subscribed!" ] );
        }
    }

    $signups[] = [
        'email' => $email,
        'phone' => $phone,
        'date'  => current_time( 'Y-m-d H:i:s' ),
    ];
    update_option( 'sapient_newsletter_signups', $signups );

    // Notify admin
    wp_mail(
        get_option( 'admin_email' ),
        'New Sapient Newsletter Signup',
        "Email: {$email}\nPhone: {$phone}"
    );

    wp_send_json_success( [ 'message' => "You're in! Thanks for signing up." ] );
}

// ── Sort Order meta box on product edit page ────────────────────────────────
add_action( 'add_meta_boxes', function() {
    add_meta_box(
        'sapient_sort_order',
        'Shop Display Order',
        function( $post ) {
            $order = get_post_meta( $post->ID, '_sapient_sort_order', true );
            if ( $order === '' ) $order = $post->menu_order;
            wp_nonce_field( 'sapient_sort_order', 'sapient_sort_nonce' );
            echo '<p><label for="sapient_sort_order_field">Position number (lower = shows first):</label></p>';
            echo '<input type="number" id="sapient_sort_order_field" name="sapient_sort_order" value="' . esc_attr( $order ) . '" style="width:100px;font-size:16px;padding:6px" min="0">';
            echo '<p class="description">Set the display order for the products page.</p>';
        },
        'product',
        'side',
        'high'
    );
} );

add_action( 'save_post_product', function( $post_id ) {
    if ( ! isset( $_POST['sapient_sort_nonce'] ) || ! wp_verify_nonce( $_POST['sapient_sort_nonce'], 'sapient_sort_order' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( isset( $_POST['sapient_sort_order'] ) ) {
        $order = intval( $_POST['sapient_sort_order'] );
        update_post_meta( $post_id, '_sapient_sort_order', $order );
        // Also update menu_order directly via DB to avoid WooCommerce overwriting it
        global $wpdb;
        $wpdb->update( $wpdb->posts, [ 'menu_order' => $order ], [ 'ID' => $post_id ] );
    }
} );
