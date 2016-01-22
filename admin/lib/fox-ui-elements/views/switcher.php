<?php
/**
 * Description: Fox ui-elements
 * Author: Osadchyi Serhii
 * Author URI: https://github.com/RDSergij
 *
 * @package ui_switcher_fox
 *
 * @since 0.2.1
 */
?>

<div <?php echo $attributes ?>>
	<input type="radio" name="<?php echo $name ?>" id="<?php echo $name ?>-<?php echo $value_first['key'] ?>" value="<?php echo $value_first['key'] ?>" 
		<?php if ( $value_first['key'] == $default ) : ?>
		checked="checked"
		<?php endif; ?>
	>
	<label for="<?php echo $name ?>-<?php echo $value_second['key'] ?>"" class="on">
		<?php echo $value_first['value'] ?>
	</label>

	<input type="radio" name="<?php echo $name ?>" id="<?php echo $name ?>-<?php echo $value_second['key'] ?>"" value="<?php echo $value_second['key'] ?>" 
		<?php if ( $value_second['key'] == $default ) : ?>
		checked="checked"
		<?php endif; ?>
	>
	<label for="<?php echo $name ?>-<?php echo $value_first['key'] ?>" class="off">
		<?php echo $value_second['value'] ?>
	</label>
</div>
