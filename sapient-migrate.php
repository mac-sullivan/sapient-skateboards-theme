<?php
/**
 * One-time migration — run from WP admin via query string:
 * yoursite.com/wp-admin/?sapient_migrate=1
 */
add_action('admin_init', function() {
    if ( empty($_GET['sapient_migrate']) ) return;
    if ( ! current_user_can('manage_options') ) wp_die('Admin only.');

    global $wpdb;
    header('Content-Type: text/plain');

    echo "=== Sapient Migration ===\n\n";

    // Get ALL products directly from DB
    $rows = $wpdb->get_results("SELECT ID, post_title, post_name FROM {$wpdb->posts} WHERE post_type='product' AND post_status='publish'");
    echo "Found " . count($rows) . " products:\n";
    foreach ($rows as $r) {
        echo "  ID={$r->ID}  slug={$r->post_name}  title={$r->post_title}\n";
    }
    echo "\n";

    // Build title lookup (lowercase)
    $by_title = [];
    foreach ($rows as $r) {
        $by_title[strtolower(trim($r->post_title))] = $r;
    }

    // Ensure categories
    $sk = term_exists('skateboards','product_cat');
    if (!$sk) $sk = wp_insert_term('Skateboards','product_cat',['slug'=>'skateboards']);
    $sk_id = is_array($sk) ? $sk['term_id'] : $sk;

    $ap = term_exists('apparel','product_cat');
    if (!$ap) $ap = wp_insert_term('Apparel','product_cat',['slug'=>'apparel']);
    $ap_id = is_array($ap) ? $ap['term_id'] : $ap;

    echo "Categories: Skateboards={$sk_id}, Apparel={$ap_id}\n\n";

    $mat = '<details class="product-specs-dropdown">
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

    $boards = [
        'P800'   => ['Width'=>'8.00"','Length'=>'32.00"','Thickness'=>'0.400"','Wheelbase'=>'14.375"','Nose Length'=>'6.875"','Tail Length'=>'6.50"','Nose Angle'=>'25.4°','Tail Angle'=>'24.3°','Flat Width'=>'2.125"','Concave Radius'=>'9.90"','Concave Angle'=>'13.0°','Nose Concave Radius'=>'80.1"','Tail Concave Radius'=>'80.1"'],
        'P825'   => ['Width'=>'8.25"','Length'=>'32.00"','Thickness'=>'0.400"','Wheelbase'=>'14.375"','Nose Length'=>'6.875"','Tail Length'=>'6.50"','Nose Angle'=>'25.4°','Tail Angle'=>'24.3°','Flat Width'=>'2.125"','Concave Radius'=>'9.90"','Concave Angle'=>'13.0°','Nose Concave Radius'=>'80.1"','Tail Concave Radius'=>'80.1"'],
        'P850'   => ['Width'=>'8.50"','Length'=>'32.00"','Thickness'=>'0.400"','Wheelbase'=>'14.375"','Nose Length'=>'6.875"','Tail Length'=>'6.50"','Nose Angle'=>'25.4°','Tail Angle'=>'24.3°','Flat Width'=>'2.125"','Concave Radius'=>'9.90"','Concave Angle'=>'13.0°','Nose Concave Radius'=>'80.1"','Tail Concave Radius'=>'80.1"'],
        'P875'   => ['Width'=>'8.75"','Length'=>'32.00"','Thickness'=>'0.400"','Wheelbase'=>'14.375"','Nose Length'=>'6.875"','Tail Length'=>'6.50"','Nose Angle'=>'25.4°','Tail Angle'=>'24.3°','Flat Width'=>'2.125"','Concave Radius'=>'9.90"','Concave Angle'=>'13.0°','Nose Concave Radius'=>'80.1"','Tail Concave Radius'=>'80.1"'],
        'P900'   => ['Width'=>'9.00"','Length'=>'32.00"','Thickness'=>'0.400"','Wheelbase'=>'14.375"','Nose Length'=>'6.875"','Tail Length'=>'6.50"','Nose Angle'=>'25.4°','Tail Angle'=>'24.3°','Flat Width'=>'2.125"','Concave Radius'=>'9.90"','Concave Angle'=>'13.0°','Nose Concave Radius'=>'80.1"','Tail Concave Radius'=>'80.1"'],
        'PXL'    => ['Width'=>'9.50"','Length'=>'32.00"','Thickness'=>'0.400"','Wheelbase'=>'14.375"','Nose Length'=>'6.875"','Tail Length'=>'6.50"','Nose Angle'=>'25.4°','Tail Angle'=>'24.3°','Flat Width'=>'2.125"','Concave Radius'=>'9.90"','Concave Angle'=>'13.0°','Nose Concave Radius'=>'80.1"','Tail Concave Radius'=>'80.1"'],
        'PXXL'   => ['Width'=>'10.00"','Length'=>'32.00"','Thickness'=>'0.400"','Wheelbase'=>'14.375"','Nose Length'=>'6.875"','Tail Length'=>'6.50"','Nose Angle'=>'25.4°','Tail Angle'=>'24.3°','Flat Width'=>'2.125"','Concave Radius'=>'9.90"','Concave Angle'=>'13.0°','Nose Concave Radius'=>'80.1"','Tail Concave Radius'=>'80.1"'],
        'S850'   => ['Width'=>'8.50"','Length'=>'31.75"','Thickness'=>'0.400"','Wheelbase'=>'14.50"','Nose Length'=>'6.50"','Tail Length'=>'6.50"','Nose Angle'=>'24.2°','Tail Angle'=>'24.2°','Flat Width'=>'2.125"','Concave Angle'=>'14.3°','Nose Concave Radius'=>'45.4"','Tail Concave Radius'=>'45.4"'],
        'S900'   => ['Width'=>'9.00"','Length'=>'31.75"','Thickness'=>'0.400"','Wheelbase'=>'14.50"','Nose Length'=>'6.50"','Tail Length'=>'6.50"','Nose Angle'=>'24.2°','Tail Angle'=>'24.2°','Flat Width'=>'2.125"','Concave Angle'=>'15.3°','Nose Concave Radius'=>'45.4"','Tail Concave Radius'=>'45.4"'],
        'G'      => ['Width'=>'10.00"','Length'=>'32.125"','Thickness'=>'0.400"','Wheelbase'=>'14.50"','Nose Length'=>'6.875"','Tail Length'=>'6.50"','Nose Angle'=>'24.5°','Tail Angle'=>'24.2°','Flat Width'=>'2.125"','Concave Angle'=>'16.2°','Nose Concave Radius'=>'45.4"','Tail Concave Radius'=>'45.4"'],
        'BASHER' => ['Width'=>'10.00"','Length'=>'29.875"','Thickness'=>'0.400"','Wheelbase'=>'14.625"/15.0"','Nose Length'=>'4.0"','Tail Length'=>'6.50"','Nose Angle'=>'22.6°','Tail Angle'=>'24.9°','Flat Width'=>'2.125"','Concave Radius'=>'9.90"','Concave Angle'=>'13.0°','Nose Concave Radius'=>'67.8"','Tail Concave Radius'=>'67.8"'],
    ];

    foreach ($boards as $title => $dims) {
        $key = strtolower(trim($title));
        $post = $by_title[$key] ?? null;
        if (!$post) { echo "SKIP: {$title} — not found\n"; continue; }

        wp_set_object_terms($post->ID, [(int)$sk_id], 'product_cat', true);

        $trs = '';
        foreach ($dims as $k=>$v) $trs .= "<tr><td>{$k}</td><td>{$v}</td></tr>\n";

        $content = '<details class="product-specs-dropdown">
<summary class="product-specs-toggle">Dimensions</summary>
<div class="product-specs-content">
<table class="product-specs-table">
<tbody>
'.$trs.'</tbody>
</table>
</div>
</details>

'.$mat;

        $wpdb->update($wpdb->posts, ['post_content'=>$content], ['ID'=>$post->ID]);
        clean_post_cache($post->ID);
        echo "OK: {$title} (ID {$post->ID}) — specs + category\n";
    }

    // Apparel
    $apparel_words = ['t-shirt','tee','shirt','hoodie','hat','cap'];
    foreach ($rows as $r) {
        $t = strtolower($r->post_title);
        foreach ($apparel_words as $w) {
            if (strpos($t,$w) !== false) {
                wp_set_object_terms($r->ID, [(int)$ap_id], 'product_cat', true);
                echo "OK: {$r->post_title} (ID {$r->ID}) → apparel\n";
                break;
            }
        }
    }

    // Menu: Team → Crew
    $menu_items = $wpdb->get_results("SELECT p.ID, p.post_title FROM {$wpdb->posts} p WHERE p.post_type='nav_menu_item'");
    foreach ($menu_items as $mi) {
        if (strtolower(trim($mi->post_title)) === 'team') {
            $wpdb->update($wpdb->posts, ['post_title'=>'Crew'], ['ID'=>$mi->ID]);
            update_post_meta($mi->ID, '_menu_item_url', site_url('/team/'));
            echo "\nMENU: Team → Crew (ID {$mi->ID})\n";
        }
    }

    echo "\n=== DONE. Remove sapient-migrate.php from theme. ===\n";
    exit;
});
