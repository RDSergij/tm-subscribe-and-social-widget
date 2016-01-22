<?php
/**
 * Admin view
 *
 * @package TM_Subscribe_And_Share_Widget
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="tm-subscribe-and-share-widget">
	<!-- Subscribe -->
	<br/>
	<div>
		<label>
			<?php _e( 'Show subscribe form', PHOTOLAB_BASE_TM_ALIAS ) ?>
			<?php echo $subscribe_is_html ?>
		</label>
	</div>

	<p>
		<?php echo $subscribe_title_html ?>
	</p>

	<p>
		<?php echo $subscribe_description_html ?>
	</p>

	<p>
		<?php echo $api_key_html ?>
	</p>

	<p>
		<?php echo $list_id_html ?>
	</p>

	<p>
		<?php echo $success_message_html ?>
	</p>

	<p>
		<?php echo $failed_message_html ?>
	</p>
	<!-- End subscribe -->

	<!-- Socials -->
	<br/>
	<div>
		<label>
			<?php _e( 'Show social buttons', PHOTOLAB_BASE_TM_ALIAS ) ?>
			<?php echo $social_is_html ?>
		</label>
	</div>

	<p>
		<?php echo $social_title_html ?>
	</p>

	<p>
		<?php echo $social_description_html ?>
	</p>
	<div class="socials" count="<?php echo count( $social_items ) ?>">
		<?php foreach ( $social_items as $index => $social ) : ?>
		<div class="social-area">
			<i class="fa fa-times delete-social"></i>
			<h4>
				<?php _e( 'Social button #', PHOTOLAB_BASE_TM_ALIAS ) ?><span><?php echo $index + 1 ?></span>
			</h4>
			<p>
				<?php echo $social['service'] ?>
			</p>

			<p>
				<?php echo $social['url'] ?>
			</p>
		</div>
		<?php endforeach; ?>
		<div class="social-new">
			<i class="fa fa-times delete-social"></i>
			<h4>
				<?php _e( 'Social button #', PHOTOLAB_BASE_TM_ALIAS ) ?><span></span>
			</h4>
			<p>
				<?php echo $social_new['service'] ?>
			</p>

			<p>
				<?php echo $social_new['url'] ?>
			</p>
		</div>
		<i class="add-social fa fa-plus-square"> add social</i>
	</div>
	<!-- End socials -->
	<p>&nbsp;</p>
</div>
