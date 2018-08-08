<?php
/*
Plugin Name: Amazon S3
Plugin URI: http://www.osclass.org/
Description: This plugin allows you to upload users' images to Amazon S3 service
Version: 2.0.0
Author: OSClass
Author URI: http://www.osclass.org/
Short Name: amazons3
Plugin update URI: amazon-s3
*/


    // load necessary functions
    require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'aws/aws-autoloader.php';

    function amazon_install() {
        osc_set_preference('bucket', '', 'amazons3', 'STRING');
        osc_set_preference('access_key', '', 'amazons3', 'STRING');
        osc_set_preference('secret_key', '', 'amazons3', 'STRING');
        osc_set_preference('region', '', 'amazons3', 'STRING');
    }

    function amazon_uninstall() {
        osc_delete_preference('bucket', 'amazons3');
        osc_delete_preference('access_key', 'amazons3');
        osc_delete_preference('secret_key', 'amazons3');
        osc_delete_preference('region', 'amazons3');
    }

    function amazon_region() {
        return osc_get_preference('region', 'amazons3');
    }

    function amazon_get_client() {
        $s3 = new Aws\S3\S3Client(
            array('credentials' =>
                array(
                    'key' => osc_get_preference('access_key', 'amazons3'),
                    'secret' => osc_get_preference('secret_key', 'amazons3')
                ),
                'region' => amazon_region(),
                'version' => '2006-03-01'
            )
        );
        return $s3;
    }

    function amazon_upload($resource) {
        $bucket = osc_get_preference('bucket', 'amazons3');
        $s3 = amazon_get_client();
        try {
            $s3->createBucket(array(
                'Bucket' => $bucket,
                'LocationConstraint' => amazon_region(),
                'ACL' => 'public-read'
            ));
        } catch (Exception $e) {
        }
        if(osc_keep_original_image()) {
            $s3->putObject(
                array(
                    'Bucket' => $bucket,
                    'Key' => $resource['pk_i_id'] . '_original.' . $resource['s_extension'],
                    'SourceFile' => osc_base_path() . $resource['s_path'] . $resource['pk_i_id'] . '_original.' . $resource['s_extension'],
                    'ACL' => 'public-read'
                )
            );
        }
        $s3->putObject(
            array(
                'Bucket' => $bucket,
                'Key' => $resource['pk_i_id'] . '.' . $resource['s_extension'],
                'SourceFile' => osc_base_path() . $resource['s_path'] . $resource['pk_i_id'] . '.' . $resource['s_extension'],
                'ACL' => 'public-read'
            )
        );
        $s3->putObject(
            array(
                'Bucket' => $bucket,
                'Key' => $resource['pk_i_id'] . '_preview.' . $resource['s_extension'],
                'SourceFile' => osc_base_path() . $resource['s_path'] . $resource['pk_i_id'] . '_preview.' . $resource['s_extension'],
                'ACL' => 'public-read'
            )
        );
        $s3->putObject(
            array(
                'Bucket' => $bucket,
                'Key' => $resource['pk_i_id'] . '_thumbnail.' . $resource['s_extension'],
                'SourceFile' => osc_base_path() . $resource['s_path'] . $resource['pk_i_id'] . '_thumbnail.' . $resource['s_extension'],
                'ACL' => 'public-read'
            )
        );
        amazon_unlink_resource($resource);
    }
    
    function amazon_resource_path($path) {
        if(amazon_region()!="") {
            return "https://s3." . amazon_region() . ".amazonaws.com/" . osc_get_preference('bucket', 'amazons3') . "/" . str_replace(osc_base_url() . osc_resource_field("s_path"), '', $path);
        }
        return "http://" . osc_get_preference('bucket', 'amazons3') . ".s3.amazonaws.com/" . str_replace(osc_base_url() . osc_resource_field("s_path"), '', $path);
    }
    
    function amazon_regenerate_image($resource) {
        $s3 = amazon_get_client();
        $bucket = osc_get_preference('bucket','amazons3');
        $original_path = osc_base_path() . $resource['s_path'] . $resource['pk_i_id'] . "_original." . $resource['s_extension'];
        $img = false;
        try {
            $path = $resource['pk_i_id'] . "_original." . $resource['s_extension'];
            $img = $s3->getObject(array(
                'Bucket' => $bucket,
                'Key' => $path,
                'SaveAs' => $original_path
            ));
        } catch(Exception $e) {
            try {
                $path = $resource['pk_i_id']. "." . $resource['s_extension'];
                $img = $s3->getObject(array(
                    'Bucket' => $bucket,
                    'Key' => $path,
                    'SaveAs' => $original_path
                ));
            } catch(Exception $e) {
                try {
                    $path = $resource['pk_i_id']. "_preview." . $resource['s_extension'];
                    $img = $s3->getObject(array(
                        'Bucket' => $bucket,
                        'Key' => $path,
                        'SaveAs' => $original_path
                    ));
                } catch(Exception $e) {
                    try {
                        $path = $resource['pk_i_id']. "_thumbnail." . $resource['s_extension'];
                        $img = $s3->getObject(array(
                            'Bucket' => $bucket,
                            'Key' => $path,
                            'SaveAs' => $original_path
                        ));
                    } catch(Exception $e) {
                    }
                }
            }
        }
        amazon_delete_from_bucket($resource);
    }
    
    function amazon_unlink_resource($resource) {
        @unlink(osc_base_path() . $resource['s_path'] . $resource['pk_i_id'] . '_original.' . $resource['s_extension']);
        @unlink(osc_base_path() . $resource['s_path'] . $resource['pk_i_id'] . '.' . $resource['s_extension']);
        @unlink(osc_base_path() . $resource['s_path'] . $resource['pk_i_id'] . '_preview.' . $resource['s_extension']);
        @unlink(osc_base_path() . $resource['s_path'] . $resource['pk_i_id'] . '_thumbnail.' . $resource['s_extension']);
    }
    
    function amazon_delete_from_bucket($resource) {
        $s3 = amazon_get_client();
        $bucket = osc_get_preference('bucket','amazons3');
        try {
            $s3->deleteObject(array(
                'Bucket' => $bucket,
                'Key' => $resource['pk_i_id'] . "_original." . $resource['s_extension']
            ));
        } catch(Exception $e) {
        }
        try {
            $s3->deleteObject(array(
                'Bucket' => $bucket,
                'Key' => $resource['pk_i_id'] . "." . $resource['s_extension']
            ));
        } catch(Exception $e) {
        }
        try {
            $s3->deleteObject(array(
                'Bucket' => $bucket,
                'Key' => $resource['pk_i_id'] . "_preview." . $resource['s_extension']
            ));
        } catch(Exception $e) {
        }
        try {
            $s3->deleteObject(array(
                'Bucket' => $bucket,
                'Key' => $resource['pk_i_id'] . "_thumbnail." . $resource['s_extension']
            ));
        } catch(Exception $e) {
        }
    }
    

    function amazon_admin_menu() {
        osc_add_admin_submenu_divider('plugins', 'Amazon S3 plugin', 'amazons3_divider', 'administrator');
        osc_add_admin_submenu_page('plugins', __('Amazon S3 options', 'amazons3'), osc_route_admin_url('amazons3-admin-conf'), 'amazons3_settings', 'administrator');
    }
    
    function amazon_configure_link() {
        osc_redirect_to(osc_route_admin_url('amazons3-admin-conf'));
    }
    
    osc_add_route('amazons3-admin-conf', 'amazons3/admin/conf', 'amazons3/admin/conf', osc_plugin_folder(__FILE__).'admin/conf.php');

    
    /**
     * ADD HOOKS
     */
    osc_register_plugin(osc_plugin_path(__FILE__), 'amazon_install');
    osc_add_hook(osc_plugin_path(__FILE__)."_uninstall", 'amazon_uninstall');
    osc_add_hook(osc_plugin_path(__FILE__)."_configure", 'amazon_configure_link');

    osc_add_hook('uploaded_file', 'amazon_upload');
    osc_add_filter('resource_path', 'amazon_resource_path');
    osc_add_hook('regenerate_image', 'amazon_regenerate_image');
    osc_add_hook('regenerated_image', 'amazon_upload');
    osc_add_hook('delete_resource', 'amazon_delete_from_bucket');
    if(osc_version()<320) {
        osc_add_hook('admin_menu', 'amazon_admin_menu');
    } else {
        osc_add_hook('admin_menu_init', 'amazon_admin_menu');
    }
    
