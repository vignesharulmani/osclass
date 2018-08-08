<?php if (!defined('OC_ADMIN') || OC_ADMIN!==true) exit('Access is not allowed.');

    if(Params::getParam('plugin_action')=='done') {
        osc_set_preference('bucket', trim(Params::getParam('bucket')), 'amazons3', 'STRING');
        osc_set_preference('access_key', trim(Params::getParam('access_key')), 'amazons3', 'STRING');
        osc_set_preference('secret_key', trim(Params::getParam('secret_key')), 'amazons3', 'STRING');
        osc_set_preference('region', trim(Params::getParam('region')), 'amazons3', 'STRING');
        if(osc_version()<320) {
            echo '<div style="text-align:center; font-size:22px; background-color:#00bb00;"><p>' . __('Congratulations. The plugin is now configured', 'amazons3') . '.</p></div>' ;
            osc_reset_preferences();
        } else {
            // HACK : This will make possible use of the flash messages ;)
            ob_get_clean();
            osc_add_flash_ok_message(__('Congratulations, the plugin is now configured', 'amazons3'), 'admin');
            osc_redirect_to(osc_route_admin_url('amazons3-admin-conf'));
        }
    }
?>
<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
    <div style="padding: 20px;">
        <div style="float: left; width: 100%;">
            <fieldset>
                <legend><?php _e('Amazon S3 Settings', 'amazons3'); ?></legend>
                <form name="amazons3_form" id="amazons3_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" enctype="multipart/form-data" >
                    <div style="float: left; width: 100%;">
                    <input type="hidden" name="page" value="plugins" />
                    <input type="hidden" name="action" value="renderplugin" />
                    <?php if(osc_version()<320) { ?>
                        <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>conf.php" />
                    <?php } else { ?>
                        <input type="hidden" name="route" value="amazons3-admin-conf" />
                    <?php }; ?>
                    <input type="hidden" name="plugin_action" value="done" />
                        <label for="bucket"><?php _e('Name of the bucket (it should be a worldwide-unique name)', 'amazons3'); ?></label>
                        <br/>
                        <input type="text" name="bucket" id="bucket" value="<?php echo osc_get_preference('bucket', 'amazons3'); ?>"/>
                        <br/>
                        <label for="access_key"><?php _e('Access key', 'amazons3'); ?></label>
                        <br/>
                        <input type="text" name="access_key" id="access_key" value="<?php echo osc_get_preference('access_key', 'amazons3'); ?>"/>
                        <br/>
                        <label for="secret_key"><?php _e('Secret key', 'amazons3'); ?></label>
                        <br/>
                        <input type="text" name="secret_key" id="secret_key" value="<?php echo osc_get_preference('secret_key', 'amazons3'); ?>"/>
                        <br/>
                        <label for="region"><?php _e('Region', 'amazons3'); ?></label>
                        <br/>
                        <input type="text" name="region" id="region" value="<?php echo osc_get_preference('region', 'amazons3'); ?>"/>
                        <span>Valid Values: eu-west-1 | us-west-1 | us-west-2 | us-east-1 | ap-southeast-1 | ap-southeast-2 | ap-northeast-1 | sa-east-1 | cn-north-1 | eu-central-1 </span>
                        <br/>
                        <?php printf(__("You need an Amazon S3 account. More information on %s",'amazons3k'), '<a href="http://aws.amazon.com/s3/">http://aws.amazon.com/s3/</a>'); ?>
                        <br/>
                        <span style="float:right;"><button type="submit" style="float: right;"><?php _e('Update', 'amazons3');?></button></span>
                    </div>
                    <br/>
                    <div style="clear:both;"></div>
                </form>
            </fieldset>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
