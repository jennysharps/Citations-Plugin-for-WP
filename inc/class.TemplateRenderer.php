<?php
class TemplateRenderer {
    public $path;
    
    public function __construct( $path ) {
        $this->path = $path;
    }

    public function loadTemplate( $type ) {
        /* $fh = fopen($this->config->path . '/' . $this->config->file);
        fwrite($fh, $message . "\n");
        fclose($fh); */
    }
    
    public function renderInput( $type, $options ) {
        
        $variables = extract( $options );
        
        $label = isset( $label ) ? $label : '';
        $instructions = isset( $instructions ) ? $instructions : '';
        $placeholder = isset( $placeholder ) ? $placeholder : '';
        $default = isset( $default ) ? $default : '';
        $current = $current ? $current : $default;
        $size = isset( $size ) ? $size : '';
        
        /* echo "<br />LABEL: $label<br />";
        echo "<br />FIELD_ID: $field_id<br />";
        echo "PLACEHOLDER: $placeholder<br />";
        echo "DEFAULT: $default<br />";
        echo "CURRENT: $current<br />"; */
        
        ob_start();
        require( $this->path . '/input-' . $type . ".php");
        return ob_get_clean();

    }
    
    public function renderInputGroup( $options ) {
        $contents = '';
        $x = 0;
        foreach( $options['fields'] as $key => $field ) {
            $field['field_id'] = "{$options['field_id']}[$key]";
            $contents .= $this->renderInput( $field['type'], $field );
            $x++;
        }
        return $contents;
    }
}