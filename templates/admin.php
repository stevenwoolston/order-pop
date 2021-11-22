<div class="wrap op-plugin-options">
    <h1>Woolston Web Design Theme Settings</h1>
    <?php settings_errors(); ?>

    <form action="options.php" method="post">
    <?php settings_fields('op-plugin-options'); ?>

        <h2 class="op-nav-tab-wrapper">
            <a href="#tab-1" class="nav-tab nav-tab-active">Manage Settings</a>
            <a href="#tab-2" class="nav-tab">SEO</a>
            <a href="#tab-3" class="nav-tab">About</a>
        </h2>
    
        <div id="tab-1" class="tab-pane active">

        </div>

        <div id="tab-2" class="tab-pane">

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