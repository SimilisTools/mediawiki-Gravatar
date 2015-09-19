<?php

use forxer\Gravatar\Gravatar;

class GravatarMW {
    
    private static $attrs_ref = array( "user", "email", "class", "id" );
	private static $attrs_like = array( "data-" );

    public static function process_gravatar( &$parser, $frame, $args ) {
        
		$attrs = array();
		$text = "";
		
		foreach ( $args as $arg ) {
			$arg_clean = trim( $frame->expand( $arg ) );
			$arg_proc = explode( "=", $arg_clean, 2 );
			
			if ( count( $arg_proc ) == 1 ){
				$text = trim( $arg_proc[0] );
			} else {
			
				if ( in_array( trim( $arg_proc[0] ), self::$attrs_ref ) ) {
					$attrs[ trim( $arg_proc[0] ) ] = trim( $arg_proc[1] );
				}
				
				foreach ( self::$attrs_like as $attr_like ) {
					if ( strpos( $arg_proc[0], $attr_like ) == 0 ) {
						$attrs[ trim( $arg_proc[0] ) ] = trim( $arg_proc[1] );
					}
				}
			}
		}
    
		if ( isset( $attrs["email"] ) ) {

            if ( ! empty( $attrs["email"] ) ) {
                $text = Gravatar::image( $attrs["email"] );
            }
        
		}
        
        return $text;
    
    }
    
}