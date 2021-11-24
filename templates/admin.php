<div class="wrap op-plugin-options">
    <h1>Order Pop Settings</h1>

    <?php settings_errors(); ?>

    <form action="options.php" method="post">
    <?php settings_fields('op-plugin-options'); ?>

        <h2 class="op-nav-tab-wrapper">
            <a href="#tab-1" class="nav-tab nav-tab-active">Manage Settings</a>
            <a href="#tab-2" class="nav-tab">About</a>
            <img src="<?php echo OP_PLUGIN_URL ?>images/logo.png" alt="" />            
        </h2>
    
        <div id="tab-1" class="tab-pane active">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="op-plugin[pop_interval_minutes]">Interval between pops:</label>
                    </th>
                    <td>
                        <input name="op-plugin[pop_interval_minutes]" type="number"
                            value="<?php echo ($options['pop_interval_minutes']) ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="op-plugin[sale_message]">Sale message after order information:</label>
                    </th>
                    <td>
                        <textarea 
                            style="width: 50vw; height: 100px; padding: 10px;"
                            name="op-plugin[sale_message]"><?php echo ($options['sale_message']) ?></textarea>
                    </td>
                </tr>                
            </table>
        </div>

        <div id="tab-2" class="tab-pane">
            <h2>About</h2>
            Steven Woolston<br />
            Woolston Web Design<br />
            Contact: 0407 077 508<br />
            Email: <a href="mailto:design@woolston.comm.au">design@woolston.comm.au</a>
        </div>

        <?php submit_button();  ?>
    </form>    

</div>