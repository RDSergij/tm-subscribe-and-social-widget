<?php
/**
 * Frontend view
 *
 * @package TM_Subscribe_And_Share_Widget
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<!-- Widget -->
<div class="tm-subscribe-and-share-widget">
	<?php if ( 'true' === $subscribe_is ) : ?>
	<!-- Subscribe section -->
	<div class="subscribe">
		<h3>
			<?php echo $subscribe_title ?>
		</h3>
		<div class="description">
			<?php echo $subscribe_description ?>
		</div>
		<div class="form">
			<form>
				<input type="hidden" name="action" value="tm-mailchimp-subscribe">
				<input type="hidden" name="api-key" value="<?php echo $api_key ?>">
				<input type="hidden" name="list-id" value="<?php echo $list_id ?>">
				<input type="email" name="email" placeholder="email@example.com">
				<input type="image" src="<?php echo $subscribe_submit_src ?>" alt="<?php _e( 'Submit', PHOTOLAB_BASE_TM_ALIAS ) ?>">
			</form>
			<div class="message">
				<span class="success"><?php echo $success_message ?></span>
				<span class="failed"><?php echo $failed_message ?></span>
			</div>
		</div>
	</div>
	<!-- End subscribe section -->
	<?php endif; ?>

	<?php if ( 'true' === $social_is ) : ?>
	<!-- Social section -->
	<div class="socials">
		<h3>
			<?php echo $social_title ?>
		</h3>
		<div class="description">
			<?php echo $social_description ?>
		</div>
		<?php if ( ! empty( $social_buttons ) ) : ?>
		<?php foreach ( $social_buttons as $social ) : ?>
		<?php if ( ! empty( $social['url'] ) && ! empty( $social['service'] ) ) : ?>
		<a href="<?php echo $social['url'] ?>">
			<i data-web-icon="true" class="fa fa-<?php echo strtolower( $social['service'] ) ?>"><!--&#128279;--></i>
		</a>
		<?php endif; ?>
		<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<!-- End social section -->
	<?php endif; ?>
</div>
<!-- End widget -->
