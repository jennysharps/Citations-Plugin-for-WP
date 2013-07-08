<?php
namespace JLS\Citations;

spl_autoload_register(__NAMESPACE__ . '\\autoload');
function autoload( $class ) {
    $cls = ltrim( $class, '\\' );
    if( strpos( $cls, __NAMESPACE__ ) !== 0 )
        return;

    // throw new \Exception( __NAMESPACE__ );
    $cls = str_replace( __NAMESPACE__ .'\\', '', $cls );

    $path = dirname(__FILE__) . "/inc/class.{$cls}.php";

    if( !file_exists( $path ) ) {
        throw new \Exception( "$class not found at expected path: $path." );
    }
    require_once( $path );
}