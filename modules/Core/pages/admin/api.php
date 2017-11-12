<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Admin API settings page
 */

// Can the user view the AdminCP?
if ($user->isLoggedIn()) {
    if (!$user->canViewACP()) {
        // No
        Redirect::to(URL::build('/'));
        die();
    } else {
        // Check the user has re-authenticated
        if (!$user->isAdmLoggedIn()) {
            // They haven't, do so now
            Redirect::to(URL::build('/admin/auth'));
            die();
        } else {
            if(!$user->hasPermission('admincp.core.api')){
                require('404.php');
                die();
            }
        }
    }
} else {
    // Not logged in
    Redirect::to(URL::build('/login'));
    die();
}

$page = 'admin';
$admin_page = 'api';

if(isset($_GET['action']) && $_GET['action'] == 'api_regen'){
    // Regenerate new API key
    // Generate new key
    $new_api_key = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32);

    $plugin_api = $queries->getWhere('settings', array('name', '=', 'mc_api_key'));
    $plugin_api = $plugin_api[0]->id;

    // Update key
    $queries->update('settings', $plugin_api, array(
        'value' => $new_api_key
    ));

    // Cache
    file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('apicache') . '.cache', $new_api_key);

    // Redirect
    Redirect::to(URL::build('/admin/api'));
    die();
}

if(Input::exists()){
    if(Token::check(Input::get('token'))){
        $plugin_id = $queries->getWhere('settings', array('name', '=', 'use_api'));
        $plugin_id = $plugin_id[0]->id;
        $queries->update('settings', $plugin_id, array(
            'value' => Input::get('enable_api')
        ));

        $legacy_plugin_id = $queries->getWhere('settings', array('name', '=', 'use_legacy_api'));
        $legacy_plugin_id = $legacy_plugin_id[0]->id;
        $queries->update('settings', $legacy_plugin_id, array(
            'value' => Input::get('enable_legacy_api')
        ));
    } else {
        $error = $language->get('general', 'invalid_token');
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo(defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <?php
    $title = $language->get('admin', 'admin_cp');
    require('core/templates/admin_header.php');
    ?>

    <link rel="stylesheet"
          href="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.css">
</head>
<body>
<?php require('modules/Core/pages/admin/navbar.php'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php require('modules/Core/pages/admin/sidebar.php'); ?>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-block">
                    <h3><?php echo $language->get('admin', 'api'); ?></h3>
                    <div class="alert alert-info"><?php echo $language->get('admin', 'api_info'); ?></div>

                    <?php if(isset($error)){ ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php } ?>

                    <?php
                    // Is the API enabled?
                    $api_enabled = $queries->getWhere('settings', array('name', '=', 'use_api'));
                    if(count($api_enabled)){
                        $api_enabled = $api_enabled[0]->value;
                    } else {
                        $queries->create('settings', array(
                            'name' => 'use_api',
                            'value' => 0
                        ));
                        $api_enabled = '0';
                    }

                    // Is the legacy API enabled?
                    $legacy_api_enabled = $queries->getWhere('settings', array('name', '=', 'use_legacy_api'));
                    if(count($legacy_api_enabled)){
                        $legacy_api_enabled = $legacy_api_enabled[0]->value;
                    } else {
                        $queries->create('settings', array(
                            'name' => 'use_legacy_api',
                            'value' => 0
                        ));
                        $legacy_api_enabled = '0';
                    }

                    // Get API key
                    $plugin_api = $queries->getWhere('settings', array('name', '=', 'mc_api_key'));
                    ?>

                    <form action="" method="post">
                        <div class="form-group">
                            <label for="enable_api"><?php echo $language->get('admin', 'enable_api'); ?></label>
                            <input type="hidden" name="enable_api" value="0">
                            <input id="enable_api" name="enable_api" type="checkbox"
                                   class="js-switch"
                                   value="1"<?php if ($api_enabled == '1') { ?> checked<?php } ?> />
                        </div>
                        <div class="form-group">
                            <label for="enable_api"><?php echo $language->get('admin', 'enable_legacy_api'); ?></label> <span class="badge badge-info" data-toggle="popover" data-content="<?php echo $language->get('admin', 'legacy_api_info'); ?>"><i class="fa fa-question"></i></span>
                            <input type="hidden" name="enable_legacy_api" value="0">
                            <input id="enable_api" name="enable_legacy_api" type="checkbox"
                                   class="js-switch"
                                   value="1"<?php if ($legacy_api_enabled == '1') { ?> checked<?php } ?> />
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                            <input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="InputAPIKey">API Key</label>
                            <div class="input-group">
                                <input type="text" name="api_key" id="InputAPIKey" class="form-control" readonly value="<?php echo htmlspecialchars($plugin_api[0]->value); ?>">
                                <span class="input-group-btn"><a href="<?php echo URL::build('/admin/api/', 'action=api_regen'); ?>" onclick="return confirm('<?php echo $language->get('admin', 'confirm_api_regen'); ?>');" class="btn btn-info"><?php echo $language->get('general', 'change'); ?></span></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('modules/Core/pages/admin/footer.php'); ?>
<?php require('modules/Core/pages/admin/scripts.php'); ?>

<script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.js"></script>

<script>
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function (html) {
        var switchery = new Switchery(html);
    });
</script>
</body>
</html>
