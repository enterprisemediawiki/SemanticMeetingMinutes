<?php
/**
 * The MeetingMinutes extension provides JS and CSS to enable recording meeting
 * minutes in SMW. See README.md.
 *
 * Documentation: https://github.com/enterprisemediawiki/SemanticMeetingMinutes
 * Support:       https://github.com/enterprisemediawiki/SemanticMeetingMinutes
 * Source code:   https://github.com/enterprisemediawiki/SemanticMeetingMinutes
 *
 * @file MeetingMinutes.php
 * @addtogroup Extensions
 * @author James Montalvo
 * @copyright © 2014 by James Montalvo
 * @licence GNU GPL v3+
 */

# Not a valid entry point, skip unless MEDIAWIKI is defined
if ( ! defined( 'MEDIAWIKI' ) ) {
	die( 'MeetingMinutes extension' );
}

define( 'SEMANTIC_MEETING_MINUTES_VERSION', '0.4.0' );

$GLOBALS['wgExtensionCredits']['semantic'][] = array(
	'path'           => __FILE__,
	'name'           => 'Semantic Meeting Minutes',
	'url'            => 'http://github.com/enterprisemediawiki/SemanticMeetingMinutes',
	'author'         => 'James Montalvo',
	'descriptionmsg' => 'meetingminutes-desc',
	'version'        => SEMANTIC_MEETING_MINUTES_VERSION
);

$GLOBALS['wgMessagesDirs']['MeetingMinutes'] = __DIR__ . '/i18n';
$GLOBALS['wgExtensionMessagesFiles']['MeetingMinutesMagic'] = __DIR__ . '/Magic.php';

// Autoload setup class (location of parser function definitions)
$GLOBALS['wgAutoloadClasses']['MeetingMinutes\Setup'] = __DIR__ . '/Setup.php';

// Setup parser functions
$GLOBALS['wgHooks']['ParserFirstCallInit'][] = 'MeetingMinutes\Setup::setupParserFunctions';
$GLOBALS['wgHooks']['BeforePageDisplay'][] = 'MeetingMinutes\Setup::onBeforePageDisplay';

$GLOBALS['egSmmPageFilePath'] = __DIR__ . "/ImportFiles";

$ExtensionMeetingMinutesResourceTemplate = array(
	'localBasePath' => __DIR__ . '/modules',
	'remoteExtPath' => 'SemanticMeetingMinutes/modules',
);

// check pcre.backtrack_limit to be large enough; set to 10 million if not
if ( ini_get( 'pcre.backtrack_limit' ) < 10000000 ) {
	ini_set( 'pcre.backtrack_limit', 10000000 );
}


$GLOBALS['wgResourceModules'] += array(

	'ext.meetingminutes.form' => $ExtensionMeetingMinutesResourceTemplate + array(
		'styles' => 'form/meeting-minutes.css',
		'scripts' => array( 'form/SF_MultipleInstanceRefire.js', 'form/meeting-minutes.js' ),
		// 'dependencies' => array( 'mediawiki.Uri' ),
		'position' => 'top',
	),

	'ext.meetingminutes.template' => $ExtensionMeetingMinutesResourceTemplate + array(
		'styles' => 'template/template.css',
		'position' => 'top',
	),

);
