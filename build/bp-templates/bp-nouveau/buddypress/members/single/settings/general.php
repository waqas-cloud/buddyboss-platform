<?php
/**
 * BuddyBoss - Members Settings ( General )
 *
 * @since BuddyPress 3.0.0
 * @version 3.1.0
 * @todo password field lables need thinking, name for password fields do not conform to standards
 */

bp_nouveau_member_hook( 'before', 'settings_template' ); ?>

<h2 class="screen-heading general-settings-screen">
	<?php _e( 'Login Information', 'buddyboss' ); ?>
</h2>

<p class="info email-pwd-info">
	<?php _e( 'Update your email and or password.', 'buddyboss' ); ?>
</p>

<form action="<?php echo esc_url( bp_displayed_user_domain() . bp_get_settings_slug() . '/general' ); ?>" method="post" class="standard-form" id="settings-form">

	<?php if ( ! is_super_admin() ) : ?>

		<label for="pwd"><?php _e( 'Current Password <span>(required to update email or change current password)</span>', 'buddyboss' ); ?></label>
		<input type="password" name="pwd" id="pwd" size="16" value="" class="settings-input small" <?php bp_form_field_attributes( 'password' ); ?>/> &nbsp;<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'buddyboss' ); ?></a>

	<?php endif; ?>

	<label for="email"><?php _e( 'Account Email', 'buddyboss' ); ?></label>
	<input type="email" name="email" id="email" value="<?php echo esc_attr( bp_get_displayed_user_email() ); ?>" class="settings-input" <?php bp_form_field_attributes( 'email' ); ?>/>

	<div class="info bp-feedback">
		<span class="bp-icon" aria-hidden="true"></span>
		<p class="text"><?php esc_html_e( 'Leave password fields blank for no change', 'buddyboss' ); ?></p>
	</div>

	<label for="pass1"><?php esc_html_e( 'Add Your New Password', 'buddyboss' ); ?></label>
	<input type="password" name="pass1" id="pass1" size="16" value="" class="settings-input small password-entry" <?php bp_form_field_attributes( 'password' ); ?>/>

	<label for="pass2" class="repeated-pwd"><?php esc_html_e( 'Repeat Your New Password', 'buddyboss' ); ?></label>
	<input type="password" name="pass2" id="pass2" size="16" value="" class="settings-input small password-entry-confirm" <?php bp_form_field_attributes( 'password' ); ?>/>

	<div id="pass-strength-result"></div>

	<?php bp_nouveau_submit_button( 'members-general-settings' ); ?>

</form>

<?php
bp_nouveau_member_hook( 'after', 'settings_template' );