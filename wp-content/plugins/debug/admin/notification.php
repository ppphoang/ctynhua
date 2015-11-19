<div class="wrap">
    <h2><?php _e('Email Notification Settings','debug');?></h2>
    <?php debug_email_notification_save_setting(); 
    $debug_settings = debug_get_options();
    ?>
    <form method="post" action="">
        <table class="form-table">
            <tbody>
                <tr>
                    <th><?php _e('Enable Notification','debug');?></th>
                    <td>
                        <label for="enable_notification">
                            <input name="debug_notification[enable]" type="checkbox" id="enable_notification" value="1" <?php if (isset($debug_settings['enable']) && $debug_settings['enable']==1) { ?>checked="checked"<?php } ?>>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th><?php _e('Email Address','debug');?></th>
                    <td>
                        <label for="email_notification">
                            <input name="debug_notification[email]" type="text" id="email_notification" value="<?php if (isset($debug_settings['email'])) { echo $debug_settings['email']; } ?>">
                        </label>
                    </td>
                </tr>
                <tr>
                    <th><?php _e('Email Subject','debug');?></th>
                    <td>
                        <label for="subject_notification">
                            <input name="debug_notification[subject]" type="text" id="subject_notification" value="<?php if (isset($debug_settings['subject'])) { echo $debug_settings['subject']; } ?>">
                        </label>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <p class="submit">
                            <input type="submit" name="debug_notification[submit]" id="submit" class="button button-primary" value="<?php _e('Save Changes','debug');?>">
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <?php echo debug_footer_link();
    ?>
</div>
