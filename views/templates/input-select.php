<?php
/**
 * Template for 'Select' Dropdown
 * @mvc View
 * @var string $label
 * @var string $instructions
 * @var string $field_id
 * @var string $placeholder
 * @var string $default
 * @var string $selected
 * @var array(assoc.) $options 
 * @author Jenny Sharps <jsharps85@gmail.com>
 */
?>

<label><?php echo $label; ?></label>

<select id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>">
    <?php if( $placeholder ) { ?>
    <option<?php if( !$selected ) {?> selected="selected"<?php } ?> disabled="disabled" value=""><?php echo $placeholder; ?></option>
<?php } ?>
<?php foreach( $options as $key => $value ) {
    $select = $selected == $value ? ' selected="selected"' : ''; ?>
    <option<?php echo $select; ?> value="<?php echo $value; ?>"><?php echo $key; ?></option>
<?php } ?>
</select>
