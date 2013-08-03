<?php
/**
 * Template for Text Inputs
 * @mvc View
 * @var string $label
 * @var string $instructions
 * @var string $field_id
 * @var string $placeholder
 * @var string $current
 * @var string $size
 * @author Jenny Sharps <jsharps85@gmail.com>
 */
?>

<?php if( $label ) { ?><label><?php echo $label; ?></label><?php } ?>

<input type="text" id="<?php echo $field_id; ?>" class="<?php echo $size; ?>" name="<?php echo $field_id; ?>"
       placeholder="<?php echo $placeholder;?>" value="<?php echo $current; ?>">

<?php if( $instructions ) { ?><p class="description"><?php echo $instructions; ?></p><?php } ?>