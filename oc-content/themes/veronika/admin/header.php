<?php
  require_once 'functions.php';
  veronika_backoffice_menu(__('Header logo', 'veronika'));
?>


<div class="mb-body">
  <?php if( is_writable( WebThemes::newInstance()->getCurrentThemePath() . "images/") ) { ?>

    <!-- LOGO PREVIEW -->
    <div class="mb-box">
      <div class="mb-head"><i class="fa fa-cog"></i> <?php _e('Logo preview', 'veronika'); ?></div>

      <?php if(file_exists( WebThemes::newInstance()->getCurrentThemePath() . "images/logo.jpg" ) ) { ?>
        <div class="mb-inside">
          <img class="mb-image-preview" border="0" alt="<?php echo osc_esc_html( osc_page_title() ); ?>" src="<?php echo osc_current_web_theme_url('images/logo.jpg');?>" />
        </div>

        <form action="<?php echo osc_admin_render_theme_url('oc-content/themes/' . osc_current_web_theme() . '/admin/header.php');?>" method="post" enctype="multipart/form-data">
          <input type="hidden" name="action_specific" value="remove" />

          <div class="mb-foot">
            <button type="submit" class="mb-button"><?php _e('Remove', 'veronika');?></button>
          </div>
        </form>

      <?php } else { ?>
        <div class="mb-inside">
          <div class="mb-warning">
            <?php _e('No logo has been uploaded yet', 'veronika'); ?>
          </div>
        </div>
      <?php } ?>
    </div>



    <!-- LOGO UPLOAD -->
    <div class="mb-box">
      <div class="mb-head"><i class="fa fa-upload"></i> <?php _e('Logo upload', 'veronika'); ?></div>

      <form action="<?php echo osc_admin_render_theme_url('oc-content/themes/' . osc_current_web_theme() . '/admin/header.php'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action_specific" value="upload_logo" />

        <div class="mb-inside">
          <div class="mb-points">
            <div class="mb-row">- <strong><?php _e('When new logo is uploaded, do not forget to clean your browser cache (CTRL + R or CTRL + F5)', 'veronika'); ?></strong></div>
            <div class="mb-row">- <?php _e('The preferred size of the logo is 230x60px.', 'veronika'); ?></div>
            <div class="mb-row">- <?php _e('Following formats are allowed: png, gif, jpg','veronika'); ?></div>

            <?php if( file_exists( WebThemes::newInstance()->getCurrentThemePath() . "images/logo.jpg" ) ) { ?>
              <div class="mb-row">- <?php _e('Uploading another logo will overwrite the current logo.', 'veronika'); ?></div>
            <?php } ?>
          </div>

          <input type="file" name="logo" id="package" />
        </div>
 
        <div class="mb-foot">
          <button type="submit" class="mb-button"><?php _e('Upload', 'veronika');?></button>
        </div>
      </form>
    <?php } else { ?>
      <div class="mb-warning">
        <div class="mb-row">
          <?php
            $msg  = sprintf(__('The images folder <strong>%s</strong> is not writable on your server', 'veronika'), WebThemes::newInstance()->getCurrentThemePath() ."images/" ) .", ";
            $msg .= __("OSClass can't upload the logo image from the administration panel.", 'veronika') . ' ';
            $msg .= __('Please make the aforementioned image folder writable.', 'veronika') . ' ';
            echo $msg;
          ?>
        </div>

        <div class="mb-row">
          <?php _e('To make a directory writable under UNIX execute this command from the shell:','veronika'); ?>
        </div>

        <div class="mb-row">
          chmod a+w <?php echo WebThemes::newInstance()->getCurrentThemePath() ."images/" ; ?>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<?php echo veronika_footer(); ?>