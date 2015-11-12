<?php

use forxer\Gravatar\Gravatar;

class GravatarMW {
	
	private static $attrs_ref = array( "user", "email", "class", "id" );
	private static $attrs_like = array( "data-" );

	public static function process_gravatar( &$parser, $frame, $args ) {
		
		$attrs = array();
		$text = "";
		$email = null;
		$default = "mm";
		$size = null;
		$rating = "g";
		$extension = null;
		$https = true;
		$output = "text";
		$link = null;
		$exists = "";

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

		if ( isset( $attrs["user"] ) ) {
			
			if ( ! empty( $attrs["user"] ) ) {
				$user = $attrs["user"];
				
				$userObj = User::newFromName( $user );
				if ( $userObj->getId() > 0 ) {
					$email = $userObj->getEmail();
				}
			} 
		}
		
		if ( isset( $attrs["email"] ) ) {
		
			if ( ! empty( $attrs["email"] ) ) {
				$email = $attrs["email"];
			}
		}
				
		if ( isset( $attrs["size"] ) ) {
		
			if ( ! empty( $attrs["size"] ) ) {
				$size = $attrs["size"];
			}
		}
		
		if ( isset( $attrs["default"] ) ) {
		
			if ( ! empty( $attrs["default"] ) ) {
				$default = $attrs["default"];
			}
		}
		
		if ( isset( $attrs["rating"] ) ) {
		
			if ( ! empty( $attrs["rating"] ) ) {
				$rating = $attrs["rating"];
			}
		}
		
		if ( isset( $attrs["extension"] ) ) {
		
			if ( ! empty( $attrs["extension"] ) ) {
				$extension = $attrs["extension"];
			}
		}
		
		if ( isset( $attrs["output"] ) ) {
		
			if ( ! empty( $attrs["output"] ) ) {
				$output = $attrs["output"];
			}
		}

		if ( isset( $attrs["exists"] ) ) {
		
			if ( ! empty( $attrs["exists"] ) ) {
				$exists = $attrs["exists"];
			}
		}

		// If check exists;
		if ( isset( $attrs["exists"] ) ) {

			$jsonurl = Gravatar::profile( $email, 'json' );

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $jsonurl);
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			// Needed User Agent
			curl_setopt($ch, CURLOPT_USERAGENT, "PHP Gravatar Library");

			$response = curl_exec($ch);

			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if($httpCode == 404) {
				/* Handle 404 here. */
			} else {
				$text = $exists;
			}
			curl_close( $ch );

		} else {
			$imgurl = Gravatar::image( $email, $size, $default, $rating, $extension, $https );
	
			if ( $output == 'image' ) {
				
				if ( isset( $attrs["link"] ) ) {
					$link =  $attrs["link"];
				} else {
					$link = Gravatar::profile( $email );
				}
				
				$tagimg = 	Html::element(
				'img',
					array( 'src' => $imgurl )
				);
				
				$out = Html::openElement( "a",
					array( "href" => $link )
				). $tagimg . Html::closeElement( "a" );
				
				$text = $parser->insertStripItem( $out, $parser->mStripState );
				
			} else {
				$text = $imgurl;
			}
		}

		return $text;
	
	}

}