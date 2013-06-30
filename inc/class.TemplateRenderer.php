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
        // var_dump($options);
        
        $variables = extract( $options );
        
        /* echo "<br />LABEL: $label<br />";
        echo "FIELD_ID: $field_id<br />";
        echo "PLACEHOLDER: $placeholder<br />";
        echo "DEFAULT: $default<br />";
        echo "SELECTED: $selected<br />";*/
        
        ob_start();
        require( $this->path . '/input-' . $type . ".php");
        $contents = ob_get_contents(); 
        ob_end_clean();
        return $contents;

    }
}