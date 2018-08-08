<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
  <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js'); ?>"></script>
</head>

<body id="body-user-register">
  <?php UserForm::js_validation(); ?>
  <?php osc_current_web_theme_path('header.php'); ?>

  <div id="i-forms" class="content">
    <h2><?php _e('Authenticate', 'veronika'); ?></h2>

    <div id="login" class="box"<?php if(Params::getParam('move') == 'register') { ?> style="display:none"<?php } ?>>
      <div class="user_forms login round3">
        <div class="inner">
          <?php if(class_exists('OSCFacebook')) { ?>
            <?php 
              $user = OSCFacebook::newInstance()->getUser();
              if( !$user or !osc_is_web_user_logged_in() ) {
            ?>
            <a class="external-log fb btn round3 tr1" href="<?php echo OSCFacebook::newInstance()->loginUrl(); ?>"><i class="fa fa-facebook"></i><?php _e('Connect with Facebook', 'veronika'); ?></a>
            <?php } ?>
          <?php } ?>

          <?php if(function_exists('gc_login_button')) { ?>
            <a class="external-log gc btn round3 tr1" href="<?php gc_login_button('link-only'); ?>"><i class="fa fa-google"></i><?php _e('Connect with Google', 'veronika'); ?></a>
          <?php } ?>

          <?php if(class_exists('OSCFacebook') || function_exists('gc_login_button')) { ?>
            <div class="or"><div class="left"></div><span><?php _e('or', 'veronika'); ?></span><div class="right"></div></div>
          <?php } ?>

          <form action="<?php echo osc_base_url(true); ?>" method="post" >
          <input type="hidden" name="page" value="login" />
          <input type="hidden" name="action" value="login_post" />
          <fieldset>
            <label for="email"><span><?php _e('E-mail', 'veronika'); ?></span></label> <span class="input-box"><i class="fa fa-user"></i><?php UserForm::email_login_text(); ?></span>
            <label for="password"><span><?php _e('Password', 'veronika'); ?></span></label> <span class="input-box"><i class="fa fa-lock"></i><?php UserForm::password_login_text(); ?></span>

            <div class="login-line">
              <div class="input-box-check">
                <?php UserForm::rememberme_login_checkbox();?>
                <label for="remember"><?php _e('Remember me', 'veronika'); ?></label>
              </div>

              <a class="more-login tr1" href="<?php echo osc_recover_user_password_url(); ?>"><?php _e("Lost password?", 'veronika'); ?></a>
            </div>

            <button type="submit" id="blue"><?php _e("Log in", 'veronika');?></button>

            <div class="swap">
              <?php _e('Don\'t you have account?', 'veronika'); ?> <a href="#" class="swap-log-reg to-reg"><?php _e('Register now', 'veronika'); ?></a>
            </div>
          </fieldset>
          </form>
        </div>
      </div>
    </div>

    <div id="register" class="box" <?php if(Params::getParam('move') <> 'register') { ?> style="display:none"<?php } ?>>
      <div class="user_forms register round3">
        <div class="inner">          
          <form name="register" id="register" action="<?php echo osc_base_url(true); ?>" method="post" >
          <input type="hidden" name="page" value="register" />
          <input type="hidden" name="action" value="register_post" />
          <fieldset>
            <h1></h1>
            <ul id="error_list"></ul>

            <label for="name"><span><?php _e('Name', 'veronika'); ?></span><span class="req">*</span></label> <span class="input-box"><i class="fa fa-user"></i><?php UserForm::name_text(); ?></span>
            <label for="password"><span><?php _e('Password', 'veronika'); ?></span><span class="req">*</span></label> <span class="input-box"><i class="fa fa-lock"></i><?php UserForm::password_text(); ?></span>
            <label for="password"><span><?php _e('Re-type password', 'veronika'); ?></span><span class="req">*</span></label> <span class="input-box"><i class="fa fa-unlock-alt"></i><?php UserForm::check_password_text(); ?></span>
            <label for="email"><span><?php _e('E-mail', 'veronika'); ?></span><span class="req">*</span></label> <span class="input-box"><i class="fa fa-envelope"></i><?php UserForm::email_text(); ?></span>
            <label for="phone"><?php _e('Mobile Phone', 'veronika'); ?></label> <span class="input-box last"><i class="fa fa-phone"></i><?php UserForm::mobile_text(osc_user()); ?></span>
            <div class="req-what"><div class="req">*</div><div class="small-info"><?php _e('This field is required', 'veronika'); ?></div></div>

            <?php osc_run_hook('user_register_form'); ?>

            <?php veronika_show_recaptcha('register'); ?>

            <button type="submit" id="green"><?php _e('Create account', 'veronika'); ?></button>

            <div class="swap">
              <?php _e('Already registered?', 'veronika'); ?> <a href="#" class="swap-log-reg to-log"><?php _e('Log in', 'veronika'); ?></a>
            </div>
          </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>


  <?php osc_current_web_theme_path('footer.php'); ?>
</body>
</html>