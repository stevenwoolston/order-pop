<?php
if (wp_verify_nonce('order-pop-config-none')) {
    add_settings_error(
        'order-pop-settings-error',
        esc_attr( 'settings_updated' ),
        'Something went wrong. Try saving again.',
        'error'
    );
} else {
    if ('POST' == $_SERVER['REQUEST_METHOD']) {
        delete_transient('order_pop_cached_orders');
    }    
}

?>
<div class="wrap op-plugin-options">
    <h1>Order Pop Settings</h1>

    <?php settings_errors(); ?>

    <form id="order-pop-config" action="options.php" method="post">
    <?php wp_nonce_field('order-pop-config-nonce'); ?>        
    <?php settings_fields('op-plugin-options'); ?>

        <h2 class="op-nav-tab-wrapper">
            <a href="#tab-1" class="nav-tab nav-tab-active">Manage Settings</a>
            <a href="#tab-2" class="nav-tab">Custom CSS</a>
            <a href="#tab-3" class="nav-tab">Product Categories</a>
            <a href="#tab-4" class="nav-tab d-none">About</a>
        </h2>
    
        <div id="tab-1" class="tab-pane active">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="op-plugin[stop_notifications]">Stop all notifications:</label>
                    </th>
                    <td>
                        <input name="op-plugin[stop_notifications]" type="checkbox" class="form-control"
                            <?php echo (array_key_exists('stop_notifications', $options) ? ' checked="checked" ' : '') ?>
                            value="1" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="op-plugin[pop_background_colour]">Pop background colour:</label>
                    </th>
                    <td>
                        <div>
                            <input name="op-plugin[pop_background_colour]" type="color"
                                class="form-control"
                                value="<?php echo ($options['pop_background_colour']) ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="op-plugin[pop_font_colour]">Pop text colour:</label>
                    </th>
                    <td>
                        <div>
                            <input name="op-plugin[pop_font_colour]" type="color"
                                class="form-control"
                                value="<?php echo ($options['pop_font_colour']) ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="op-plugin[pop_last_order_count]">Last orders to pop:</label>
                    </th>
                    <td>
                        <div>
                            <input name="op-plugin[pop_last_order_count]" type="number"
                                class="form-control"
                                value="<?php echo ($options['pop_last_order_count']) ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="op-plugin[pop_interval_between_pop_refresh_seconds]">
                            Interval (seconds) between pop product refresh:<br />
                            <small>Product details are displayed for this amount of time before the next random product is displayed.</small>
                        </label>
                    </th>
                    <td>
                        <div>
                            <input name="op-plugin[pop_interval_between_pop_refresh_seconds]" type="number"
                                class="form-control"
                                value="<?php echo ($options['pop_interval_between_pop_refresh_seconds']) ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="op-plugin[pop_interval_between_pops_after_dismissed_minutes]">
                            Interval (minutes) before pop resumes after being dismissed:<br />
                            <small>The customer can dismiss and the pop will not be displayed again until after this interval elapses (or the customer clears their browser cache).</small>
                        </label>
                    </th>
                    <td>
                        <div>
                            <input name="op-plugin[pop_interval_between_pops_after_dismissed_minutes]" type="number"
                                class="form-control"
                                value="<?php echo ($options['pop_interval_between_pops_after_dismissed_minutes']) ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="op-plugin[anonomise_customer]">
                            Anonomise Customer Name: <br>
                            <small>(will use: 'Someone' instead of customer information)</small>
                        </label>
                    </th>
                    <td>
                        <div>
                            <input name="op-plugin[anonomise_customer]" type="checkbox" class="form-control"
                                <?php echo (array_key_exists('anonomise_customer', $options) ? ' checked="checked" ' : '') ?>
                                value="1" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="op-plugin[utm_code]">
                            UTM Code: <br>
                            <small>This text will be appended to the product URL.</small>
                        </label>
                    </th>
                    <td>
                        <div>
                            <input name="op-plugin[utm_code]" type="text"
                                class="form-control" style="width: 100%;"
                                value="<?php echo ($options['utm_code']) ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="op-plugin[debug_active]">Enable debugging:</label>
                    </th>
                    <td>
                        <div>
                            <input name="op-plugin[debug_active]" type="checkbox" class="form-control"
                                <?php echo (array_key_exists('debug_active', $options) ? ' checked="checked" ' : '') ?>
                                value="1" />
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div id="tab-2" class="tab-pane">
            <h2>Configure customised CSS</h2>
            <p>All selectors must be prefixed with the <code>.op-popper</code> selector to ensure scope.</p>
            <textarea name="op-plugin[custom_css]" class="form-control"
                style="height: 50vh"><?php echo $options['custom_css']; ?></textarea>
        </div>

        <div id="tab-3" class="tab-pane">
            <h2>Configure the product categories to pop</h2>
            <p>Bear in mind that de-selecting Categories could result in no orders being found.</p>
            <table class="table mt-3">
                <thead>
                    <tr class="bg-light text-dark">
                        <td style="width: 100px">Do Not Pop</td>
                        <td>Category Name</td>
                    </tr>
                </thead>
<?php
    $orderby = 'name';
    $order = 'asc';
    $hide_empty = false ;
    $cat_args = array(
        'orderby'    => $orderby,
        'order'      => $order,
        'hide_empty' => $hide_empty,
    );
    
    $product_categories = get_terms('product_cat', $cat_args);
    if (empty($product_categories)) {
        echo '<tr><td class="text-center">There are no categories.</td></tr>';
    } else {
        foreach($product_categories as $key => $category) {
            $category_name = $category->name;
            $term_id = $category->term_id;
            $term_slug = $category->slug;
            echo '<tr>';
            echo '<td class="text-center bg-light">';
            echo '<input name="op-plugin[excluded_categories][]" type="checkbox" ';
            echo 'value="' .$term_slug. '"';
            echo (isset($options['excluded_categories']) && in_array($term_slug, $options['excluded_categories']) ? 'checked="checked"' : ''). '/></td>';
            echo '<td>' .$category_name. '</td>';
            echo '</tr>';
        }
    }
?>
            </table>
        </div>

        <div id="tab-4" class="tab-pane d-none">
            <h2>About</h2>
            Steven Woolston<br />
            Woolston Web Design<br />
            Contact: 0407 077 508<br />
            Email: <a href="mailto:design@woolston.com.au">design@woolston.com.au</a>
        </div>

        <?php submit_button();  ?>
    </form>    

</div>