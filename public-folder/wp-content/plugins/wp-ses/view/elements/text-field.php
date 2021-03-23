<?php
$type     = ( isset( $type ) ) ? $type : 'text';
$class    = ( isset( $class ) ) ? 'class="' . $class . '"' : '';
$disabled = ( isset( $disabled ) && $disabled ) ? ' disabled' : '';
$prefix   = $this->get_plugin_prefix_slug();
$value    = isset( $value ) ? $value : '';
$saved    = $this->settings->get_setting( $key, false );
$value    = $saved ? $saved : $value;
?>

<input name="<?php echo $key; ?>" type="<?php echo $type; ?>" class="<?php echo $class; ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo $disabled; ?> />