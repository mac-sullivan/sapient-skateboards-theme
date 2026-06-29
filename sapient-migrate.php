<?php
/**
 * One-time migration script for Sapient Skateboards.
 * Visit: yoursite.com/wp-content/themes/sapient-skateboards-theme/sapient-migrate.php
 * DELETE THIS FILE after running.
 */
require_once dirname(__FILE__) . '/../../../wp-load.php';

if ( ! current_user_can('manage_options') ) {
    wp_die('You must be logged in as admin.');
}

echo '<pre>';
echo "=== Sapient Migration Script ===\n\n";

// ── 0. List all products on this site ─────────────────────────
echo "── Existing products ──\n";
$all = get_posts([
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => -1,
]);
foreach ( $all as $p ) {
    echo "  ID={$p->ID}  slug={$p->post_name}  title={$p->post_title}\n";
}
echo "\n";

// ── 1. Ensure categories exist ────────────────────────────────
$skateboards_id = term_exists('skateboards', 'product_cat');
if ( ! $skateboards_id ) {
    $skateboards_id = wp_insert_term('Skateboards', 'product_cat', ['slug' => 'skateboards']);
}
$skateboards_id = is_array($skateboards_id) ? $skateboards_id['term_id'] : $skateboards_id;

$apparel_id = term_exists('apparel', 'product_cat');
if ( ! $apparel_id ) {
    $apparel_id = wp_insert_term('Apparel', 'product_cat', ['slug' => 'apparel']);
}
$apparel_id = is_array($apparel_id) ? $apparel_id['term_id'] : $apparel_id;

echo "Categories: Skateboards={$skateboards_id}, Apparel={$apparel_id}\n\n";

// ── 2. Material specs (same for all boards) ───────────────────
$materials = '<details class="product-specs-dropdown">
<summary class="product-specs-toggle">Material Specifications</summary>
<div class="product-specs-content">
<table class="product-specs-table">
<tbody>
<tr><td>Wood</td><td>Great Lakes Veneer Maple Veneer</td></tr>
<tr><td>Glue</td><td>Franklin Adhesives Multibond SK-8</td></tr>
<tr><td>Finish</td><td>Minwax Fast-Drying Polyurethane</td></tr>
<tr><td>Ink</td><td>Nazdar 4700 Series Water-Based Screen Ink</td></tr>
</tbody>
</table>
</div>
</details>';

// ── 3. Per-product dimensions (keyed by TITLE, case-insensitive) ──
$products = [
    'P800' => [
        'cat' => 'board',
        'dims' => [
            'Width' => '8.00"', 'Length' => '32.00"', 'Thickness' => '0.400"',
            'Wheelbase' => '14.375"', 'Nose Length' => '6.875"', 'Tail Length' => '6.50"',
            'Nose Angle' => '25.4°', 'Tail Angle' => '24.3°', 'Flat Width' => '2.125"',
            'Concave Radius' => '9.90"', 'Concave Angle' => '13.0°',
            'Nose Concave Radius' => '80.1"', 'Tail Concave Radius' => '80.1"',
        ],
    ],
    'P825' => [
        'cat' => 'board',
        'dims' => [
            'Width' => '8.25"', 'Length' => '32.00"', 'Thickness' => '0.400"',
            'Wheelbase' => '14.375"', 'Nose Length' => '6.875"', 'Tail Length' => '6.50"',
            'Nose Angle' => '25.4°', 'Tail Angle' => '24.3°', 'Flat Width' => '2.125"',
            'Concave Radius' => '9.90"', 'Concave Angle' => '13.0°',
            'Nose Concave Radius' => '80.1"', 'Tail Concave Radius' => '80.1"',
        ],
    ],
    'P850' => [
        'cat' => 'board',
        'dims' => [
            'Width' => '8.50"', 'Length' => '32.00"', 'Thickness' => '0.400"',
            'Wheelbase' => '14.375"', 'Nose Length' => '6.875"', 'Tail Length' => '6.50"',
            'Nose Angle' => '25.4°', 'Tail Angle' => '24.3°', 'Flat Width' => '2.125"',
            'Concave Radius' => '9.90"', 'Concave Angle' => '13.0°',
            'Nose Concave Radius' => '80.1"', 'Tail Concave Radius' => '80.1"',
        ],
    ],
    'P875' => [
        'cat' => 'board',
        'dims' => [
            'Width' => '8.75"', 'Length' => '32.00"', 'Thickness' => '0.400"',
            'Wheelbase' => '14.375"', 'Nose Length' => '6.875"', 'Tail Length' => '6.50"',
            'Nose Angle' => '25.4°', 'Tail Angle' => '24.3°', 'Flat Width' => '2.125"',
            'Concave Radius' => '9.90"', 'Concave Angle' => '13.0°',
            'Nose Concave Radius' => '80.1"', 'Tail Concave Radius' => '80.1"',
        ],
    ],
    'P900' => [
        'cat' => 'board',
        'dims' => [
            'Width' => '9.00"', 'Length' => '32.00"', 'Thickness' => '0.400"',
            'Wheelbase' => '14.375"', 'Nose Length' => '6.875"', 'Tail Length' => '6.50"',
            'Nose Angle' => '25.4°', 'Tail Angle' => '24.3°', 'Flat Width' => '2.125"',
            'Concave Radius' => '9.90"', 'Concave Angle' => '13.0°',
            'Nose Concave Radius' => '80.1"', 'Tail Concave Radius' => '80.1"',
        ],
    ],
    'PXL' => [
        'cat' => 'board',
        'dims' => [
            'Width' => '9.50"', 'Length' => '32.00"', 'Thickness' => '0.400"',
            'Wheelbase' => '14.375"', 'Nose Length' => '6.875"', 'Tail Length' => '6.50"',
            'Nose Angle' => '25.4°', 'Tail Angle' => '24.3°', 'Flat Width' => '2.125"',
            'Concave Radius' => '9.90"', 'Concave Angle' => '13.0°',
            'Nose Concave Radius' => '80.1"', 'Tail Concave Radius' => '80.1"',
        ],
    ],
    'PXXL' => [
        'cat' => 'board',
        'dims' => [
            'Width' => '10.00"', 'Length' => '32.00"', 'Thickness' => '0.400"',
            'Wheelbase' => '14.375"', 'Nose Length' => '6.875"', 'Tail Length' => '6.50"',
            'Nose Angle' => '25.4°', 'Tail Angle' => '24.3°', 'Flat Width' => '2.125"',
            'Concave Radius' => '9.90"', 'Concave Angle' => '13.0°',
            'Nose Concave Radius' => '80.1"', 'Tail Concave Radius' => '80.1"',
        ],
    ],
    'S850' => [
        'cat' => 'board',
        'dims' => [
            'Width' => '8.50"', 'Length' => '31.75"', 'Thickness' => '0.400"',
            'Wheelbase' => '14.50"', 'Nose Length' => '6.50"', 'Tail Length' => '6.50"',
            'Nose Angle' => '24.2°', 'Tail Angle' => '24.2°', 'Flat Width' => '2.125"',
            'Concave Angle' => '14.3°',
            'Nose Concave Radius' => '45.4"', 'Tail Concave Radius' => '45.4"',
        ],
    ],
    'S900' => [
        'cat' => 'board',
        'dims' => [
            'Width' => '9.00"', 'Length' => '31.75"', 'Thickness' => '0.400"',
            'Wheelbase' => '14.50"', 'Nose Length' => '6.50"', 'Tail Length' => '6.50"',
            'Nose Angle' => '24.2°', 'Tail Angle' => '24.2°', 'Flat Width' => '2.125"',
            'Concave Angle' => '15.3°',
            'Nose Concave Radius' => '45.4"', 'Tail Concave Radius' => '45.4"',
        ],
    ],
    'G' => [
        'cat' => 'board',
        'dims' => [
            'Width' => '10.00"', 'Length' => '32.125"', 'Thickness' => '0.400"',
            'Wheelbase' => '14.50"', 'Nose Length' => '6.875"', 'Tail Length' => '6.50"',
            'Nose Angle' => '24.5°', 'Tail Angle' => '24.2°', 'Flat Width' => '2.125"',
            'Concave Angle' => '16.2°',
            'Nose Concave Radius' => '45.4"', 'Tail Concave Radius' => '45.4"',
        ],
    ],
    'BASHER' => [
        'cat' => 'board',
        'dims' => [
            'Width' => '10.00"', 'Length' => '29.875"', 'Thickness' => '0.400"',
            'Wheelbase' => '14.625"/15.0"', 'Nose Length' => '4.0"', 'Tail Length' => '6.50"',
            'Nose Angle' => '22.6°', 'Tail Angle' => '24.9°', 'Flat Width' => '2.125"',
            'Concave Radius' => '9.90"', 'Concave Angle' => '13.0°',
            'Nose Concave Radius' => '67.8"', 'Tail Concave Radius' => '67.8"',
        ],
    ],
];

// Also match apparel by partial title
$apparel_keywords = ['t-shirt', 'tee', 'shirt', 'hoodie', 'hat', 'cap'];

// Build a title→post lookup from all products
$title_map = [];
foreach ( $all as $p ) {
    $title_map[ strtolower( trim($p->post_title) ) ] = $p;
}

foreach ( $products as $title => $data ) {
    $key = strtolower( trim($title) );
    $post = $title_map[$key] ?? null;

    if ( ! $post ) {
        echo "SKIP: {$title} — not found\n";
        continue;
    }

    // Assign category
    $cat_id = $data['cat'] === 'board' ? $skateboards_id : $apparel_id;
    wp_set_object_terms( $post->ID, [(int)$cat_id], 'product_cat', true );
    echo "CAT:  {$title} (ID {$post->ID}) → {$data['cat']}\n";

    // Update description with spec accordions (boards only)
    if ( $data['dims'] ) {
        $rows = '';
        foreach ( $data['dims'] as $label => $value ) {
            $rows .= "<tr><td>{$label}</td><td>{$value}</td></tr>\n";
        }
        $content = '<details class="product-specs-dropdown">
<summary class="product-specs-toggle">Dimensions</summary>
<div class="product-specs-content">
<table class="product-specs-table">
<tbody>
' . $rows . '</tbody>
</table>
</div>
</details>

' . $materials;

        wp_update_post([
            'ID' => $post->ID,
            'post_content' => $content,
        ]);
        echo "SPEC: {$title} — description updated\n";
    }
}

// ── Assign any remaining apparel products ─────────────────────
foreach ( $all as $p ) {
    $t = strtolower($p->post_title);
    foreach ( $apparel_keywords as $kw ) {
        if ( strpos($t, $kw) !== false ) {
            wp_set_object_terms( $p->ID, [(int)$apparel_id], 'product_cat', true );
            echo "CAT:  {$p->post_title} (ID {$p->ID}) → apparel (auto)\n";
            break;
        }
    }
}

// ── 4. Update Team menu item to Crew ──────────────────────────
$menus = wp_get_nav_menus();
foreach ( $menus as $menu ) {
    $items = wp_get_nav_menu_items( $menu->term_id );
    if ( ! $items ) continue;
    foreach ( $items as $item ) {
        if ( strtolower($item->title) === 'team' ) {
            wp_update_post([
                'ID' => $item->ID,
                'post_title' => 'Crew',
            ]);
            update_post_meta( $item->ID, '_menu_item_url', site_url('/team/') );
            echo "\nMENU: Renamed 'Team' to 'Crew' (ID {$item->ID})\n";
        }
    }
}

echo "\n=== Migration complete! DELETE THIS FILE NOW. ===\n";
echo '</pre>';
