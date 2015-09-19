<?php


if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

//self executing anonymous function to prevent global scope assumptions
call_user_func( function() {

	$GLOBALS['wgExtensionCredits']['parserhook'][] = array(
		'path' => __FILE__,
		'name' => 'Gravatar',
		'version' => '0.1',
		'url' => 'https://github.com/SimilisTools/mediawiki-Gravatar',
		'author' => array( 'Toniher' ),
		'descriptionmsg' => 'gravatar-desc',
	);

	$GLOBALS['wgAutoloadClasses']['GravatarMW'] = __DIR__.'/Gravatar_body.php';
	$GLOBALS['wgMessagesDirs']['GravatarMW'] = __DIR__ . '/i18n';
	$GLOBALS['wgExtensionMessagesFiles']['GravatarMW'] = __DIR__ . '/Gravatar.i18n.php';
	$GLOBALS['wgExtensionMessagesFiles']['GravatarMWMagic'] = __DIR__ . '/Gravatar.i18n.magic.php';

	$GLOBALS['wgHooks']['ParserFirstCallInit'][] = 'wfRegisterGravatar';

} );

/**
 * @param $parser Parser
 * @return bool
 */
function wfRegisterGravatar( $parser ) {
	$parser->setFunctionHook( 'gravatar', 'GravatarMW::process_Gravatar', SFH_OBJECT_ARGS );
	return true;
}
