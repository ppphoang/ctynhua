<?php

//read file content
function debug_file_read($fileName) {
    $filePath = get_home_path() . $fileName;
    if (file_exists($filePath)) {
        return file_get_contents($filePath);
    }
    return false;
}

// write file content
function debug_file_write($content, $fileName) {
    $output = error_log('/*test*/', '3', get_home_path() . $fileName);
    if ($output) {
        unlink(get_home_path() . $fileName);
        error_log($content, '3', get_home_path() . $fileName);
        chmod(get_home_path() . $fileName, 0600);
    }
    return $output;
}

// unlink debug.log file
function debug_clearlog() {
    $filePath = get_home_path() . 'wp-content/debug.log';
    $result['class'] = 'error';
    $result['message'] = __('File debug.log not Removed.', 'debug');
    if (file_exists($filePath)) {
        $status = unlink($filePath);
        if ($status) {
            $result['class'] = 'updated';
            $result['message'] = __('debug.log file Remove successfully.', 'debug');
        }
    }
    return $result;
}

//save debug setting from UI
function debug_save_setting() {
    if (isset($_POST['debugsetting']) && !empty($_POST['debugsetting'])) {
        $error_reporting = isset($_POST['error_reporting']) ? trim($_POST['error_reporting']) : '0';
        $error_log = isset($_POST['error_log']) ? trim($_POST['error_log']) : '0';
        $display_error = isset($_POST['display_error']) ? trim($_POST['display_error']) : '0';
        $error_script = isset($_POST['error_script']) ? trim($_POST['error_script']) : '0';
        $error_savequery = isset($_POST['error_savequery']) ? trim($_POST['error_savequery']) : '0';
        $fileName = 'wp-config.php';
        $fileContent = debug_file_read($fileName);

        $fileContent = debug_add_option($error_reporting, 'WP_DEBUG', $fileContent);
        $fileContent = debug_add_option($error_log, 'WP_DEBUG_LOG', $fileContent);
        $fileContent = debug_add_option($display_error, 'WP_DEBUG_DISPLAY', $fileContent);
        $fileContent = debug_add_option($error_script, 'SCRIPT_DEBUG', $fileContent);
        $fileContent = debug_add_option($error_savequery, 'SAVEQUERIES', $fileContent);

        if (debug_file_write($fileContent, $fileName)) {
            ?>
            <script>
                window.location = '<?php echo admin_url('admin.php?page=' . trim($_GET['page']) . '&update=1'); ?>';
            </script>
            <?php
        } else {
            echo '<div class="error settings-error">';
            echo '<p><strong>' . __('Your wp-config file not updated. Copy and paste following code in your wp-config.php file.', 'debug') . '</strong></p>';
            echo '</div>';
            echo '<textarea style="width:100%; height:400px">' . htmlentities($fileContent) . '</textarea>';
        }
    } elseif (isset($_GET['update']) && $_GET['update'] == 1 && $_GET['page'] == 'debug_settings') {
        ?>
        <div class="updated settings-error"> 
            <p><strong><?php _e('wp-config.php file update successfully.', 'debug'); ?></strong></p>
        </div>
        <?php
    }
}

// modify content of wp-config.php file and add debug variable
function debug_add_option($option, $define, $fileContent) {
    if ($option == 1) {
        $fileContent = str_replace(array("define('" . $define . "', true);", "define('" . $define . "', false);"), "define('" . $define . "', true);", $fileContent, $count);
        if ($count == 0) {
            $fileContent = str_replace('$table_prefix', "define('" . $define . "', true);" . "\r\n" . '$table_prefix', $fileContent);
        }
    } else {
        $fileContent = str_replace(array("define('" . $define . "', true);", "define('" . $define . "', false);"), "define('" . $define . "', false);", $fileContent);
    }
    return $fileContent;
}

//add thank you link on admin pages
function debug_footer_link() {
    $text = '<div class="alignright">' . __('Thank you for Debugging your wordpress with ', 'debug') . '<a href="http://www.soninow.com" target="_blank">SoniNow</a></div>';
    ?>
    <script>
        jQuery(document).ready(function () {
            jQuery('#footer-thankyou').html('<?php echo $text; ?>');
            jQuery('#footer-upgrade').html('Current Version <?php echo DEBUG_PLUGIN_VERSION; ?>');
            <?php if(isset($_GET['page']) && $_GET['page'] == 'debug'){?>
                if(typeof jQuery('#debug-log')[0] != 'undefined'){
                    jQuery('#debug-log').animate({scrollTop: jQuery('#debug-log')[0].scrollHeight}, 800);
                }
            <?php }?>
        });
    </script>
    <?php
}

function debug_file_download($path) {
    $content = debug_file_read($path);
    header('Content-type: application/octet-stream', true);
    header('Content-Disposition: attachment; filename="' . basename($path) . '"', true);
    header("Pragma: no-cache");
    header("Expires: 0");
    echo $content;
    exit();
}

function debug_downloadlog() {
    debug_file_download('wp-content/debug.log');
}

function debug_downloadconfig() {
    debug_file_download('wp-config.php');
}

function debug_email_notification_save_setting() {
    if (isset($_POST['debug_notification']) && !empty($_POST['debug_notification'])) {
        $setting = $_POST['debug_notification'];
        if (isset($setting['enable']) && !empty($setting['enable'])) {
            if (!isset($setting['email']) || empty($setting['email'])) {
                $status = 'error';
                $message = 'Email address is required field.';
            } elseif (!is_email($setting['email'])) {
                $status = 'error';
                $message = 'Please enter a valid email address.';
            } elseif (!isset($setting['subject']) || empty($setting['subject'])) {
                $status = 'error';
                $message = 'Email subject is required field.';
            } else {
                $_POST['debugsetting'] = $_POST['error_reporting'] = $_POST['error_log'] = $_POST['error_script'] = $_POST['error_savequery'] = '1';
                $_POST['display_error'] = '0';
                debug_save_setting();
            }
        } else {
            $status = 'updated';
            $message = 'Notification setting save successfully.';
        }
        update_option('debug_notification', $setting);
    }
    if (isset($_GET['update']) && $_GET['update'] == 1 && $_GET['page'] == 'debug_notification') {
        $status = 'updated';
        $message = 'Notification setting save successfully.';
    }
    if (isset($message) && !empty($message)) {
        echo '<div class="' . $status . ' settings-error"><p><strong>' . __($message, 'debug') . '</strong></p></div>';
    }
}

function debug_get_options() {
    return get_option('debug_notification');
}

function debug_create_table_format($array) {
    if (is_array($array) && count($array) > 0) {
        $errorContent = "<table border = 1><tr><td>";
        foreach ($array as $key => $val) {
            $errorContent .= $key . "</td><td>";
            if (is_array($val) && count($val) > 0) {
                $errorContent .= debug_create_table_format(json_decode(json_encode($val), true));
            } else {
                $errorContent .= print_r($val, true);
            }
        }
        $errorContent .= "</td></tr></table>";
        return $errorContent;
    }
    return '';
}

function debug_error_handler($errorNumber, $errorString, $errorFile, $errorLine, $errorContext) {
    $debug_setting = debug_get_options();

    $emailMessage = '<h2>' . __('Error Reporting on', 'debug') . ' :- </h2>[' . date("Y-m-d h:i:s", time()) . ']<br>';
    $emailMessage .= '<h2>' . __('Error Number', 'debug') . ' :- </h2>' . print_r($errorNumber, true) . '<br>';
    $emailMessage .= '<h2>' . __('Error String', 'debug') . ' :- </h2>' . print_r($errorString, true) . '<br>';
    $emailMessage .= '<h2>' . __('Error File', 'debug') . ' :- </h2>' . print_r($errorFile, true) . '<br>';
    $emailMessage .= '<h2>' . __('Error Line', 'debug') . ' :- </h2>' . print_r($errorLine, true) . '<br>';
    $emailMessage .= '<h2>' . __('Error Context', 'debug') . ' :- </h2>' . debug_create_table_format($errorContext);

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    wp_mail($debug_setting['email'], $debug_setting['subject'], $emailMessage, $headers);
}
