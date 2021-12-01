<div class="wrap op-plugin-options">
    <h1>Order Pop Settings</h1>

    <?php settings_errors(); ?>

    <form action="options.php" method="post">
    <?php settings_fields('op-plugin-options'); ?>

        <h2 class="op-nav-tab-wrapper">
            <a href="#tab-1" class="nav-tab nav-tab-active">Manage Settings</a>
            <a href="#tab-2" class="nav-tab">Product Categories</a>
            <a href="#tab-3" class="nav-tab">About</a>
        </h2>
    
        <div id="tab-1" class="tab-pane active">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="op-plugin[stop_notifications]">Stop all notifications:</label>
                    </th>
                    <td>
                        <input name="op-plugin[stop_notifications]" type="checkbox" class="form-control"
                            <?php echo ($options['stop_notifications'] ? ' checked="checked" ' : '') ?>
                            value="1" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="op-plugin[pop_interval_minutes]">Interval between pops:</label>
                    </th>
                    <td>
                        <div class="w-25">
                            <input name="op-plugin[pop_interval_minutes]" type="number"
                                class="form-control"
                                value="<?php echo ($options['pop_interval_minutes']) ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="op-plugin[pop_last_order_count]">Last orders to pop:</label>
                    </th>
                    <td>
                        <div class="w-25">
                            <input name="op-plugin[pop_last_order_count]" type="number"
                                class="form-control"
                                value="<?php echo ($options['pop_last_order_count']) ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="op-plugin[pop_background_colour]">Pop background colour:</label>
                    </th>
                    <td>
                        <div class="w-25">
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
                        <div class="w-25">
                            <input name="op-plugin[pop_font_colour]" type="color"
                                class="form-control"
                                value="<?php echo ($options['pop_font_colour']) ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="op-plugin[sale_message]">Sale message after order information:</label>
                    </th>
                    <td>
                        <textarea class="form-control"
                            name="op-plugin[sale_message]"><?php echo ($options['sale_message']) ?></textarea>
                            <div class="py-2">
                                These are your available tags. Click in the field above where you want to put the tag, then 
                                click the tag link below to insert it.
                            </div>
                        <span class="available-tags">
                            <a href="#"><pre><code>FirstName</code></pre></a>
                            <a href="#"><pre><code>LastName</code></pre></a>
                            <a href="#"><pre><code>ProductName</code></pre></a>
                            <a href="#"><pre><code>ProductCategoryName</code></pre></a>
                            <a href="#"><pre><code>OrderDateFullDD-MMM-YYYY</code></pre></a>
                            <a href="#"><pre><code>OrderDateDay</code></pre></a>
                            <a href="#"><pre><code>OrderDateMonthNumber</code></pre></a>
                            <a href="#"><pre><code>OrderDateMonthName</code></pre></a>
                            <a href="#"><pre><code>OrderDateYear</code></pre></a>
                        </span>
                    </td>
                </tr>                
            </table>
        </div>

        <div id="tab-2" class="tab-pane">
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
            echo '<tr>';
            echo '<td class="text-center bg-light">';
            echo '<input name="op-plugin[excluded_categories][]" type="checkbox" ';
            echo 'value="' .$term_id. '"';
            echo (isset($options['excluded_categories']) && in_array($term_id, $options['excluded_categories']) ? 'checked="checked"' : ''). '/></td>';
            echo '<td>' .$category_name. '</td>';
            echo '</tr>';
        }
    }
?>
            </table>
        </div>

        <div id="tab-3" class="tab-pane">
            <h2>About</h2>
            Steven Woolston<br />
            Woolston Web Design<br />
            Contact: 0407 077 508<br />
            Email: <a href="mailto:design@woolston.comm.au">design@woolston.comm.au</a>
        </div>

        <?php submit_button();  ?>
    </form>    

</div>