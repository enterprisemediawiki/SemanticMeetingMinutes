<?php
/**
 * <INSERT DESCRIPTION>.
 * 
 * Documentation: http://???
 * Support:       http://???
 * Source code:   http://???
 *
 * @addtogroup Extensions
 * @author James Montalvo
 * @copyright © 2014 by James Montalvo
 * @licence GNU GPL v3+
 */

// FIXME: all function documentation is very generic...add specifics.
 
namespace MeetingMinutes;

class Setup {

	/**
	* Handler for ParserFirstCallInit hook; sets up parser functions.
	* @see http://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	* @param $parser Parser object
	* @param $frame FIXME: what does frame really do?
	* @param $args array of arguments passed to parser function
	* @return bool true in all cases
	*/
	static function setupParserFunctions ( &$parser ) {
	
		// setup #synopsize parser function
		$parser->setFunctionHook(
			'synopsize', 
			array(
				'MeetingMinutes\Setup',
				'renderSynopsizeParserFunction' 
			),
			SFH_OBJECT_ARGS
		);
		
		// setup #meetingminutestemplate parser function (just adds module to
		// meeting minutes and meeting template)
		$parser->setFunctionHook(
			'meetingminutestemplate', 
			array(
				'MeetingMinutes\Setup',
				'renderMeetingMinutesTemplateParserFunction' 
			),
			SFH_OBJECT_ARGS
		);

		// setup #meetingminutesform parser function (just adds module to
		// meeting minutes form)
		$parser->setFunctionHook(
			'meetingminutesform', 
			array(
				'MeetingMinutes\Setup',
				'renderMeetingMinutesFormParserFunction' 
			),
			SFH_OBJECT_ARGS
		);
		
		return true;

	}
	
	
	
	/**
	* Handler for synopsize parser function.
	* @see http://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	* @param $parser Parser object
	* @param $frame FIXME: what does frame really do?
	* @param $args array of arguments passed to parser function
	* @return bool true in all cases
	*/
	static function renderSynopsizeParserFunction ( &$parser, $frame, $args ) {

		$args = self::processArgs( $frame, $args, array("", 255, 1) );
			
		$full_text  = $args[0];
		$max_length = $args[1];
		$max_lines  = $args[2];
		
		$needle = "\n";
		for($i=0; $i<$max_lines; $i++) {
			if ($newline_pos)
				$offset = $newline_pos + strlen($needle);
			else
				$offset = 0;
			$newline_pos = strpos($full_text, $needle, $offset);
		}

		if ($newline_pos) {
			// trim to specified number of newlines
			$synopsis = substr($full_text, 0, $newline_pos);
		}
		else {
			$synopsis = $full_text;
		}
		
		// trim at max characters
		if (strlen($synopsis) > $max_length) {
			$synopsis = substr($synopsis, 0, $max_length);
			$last_space = strrpos($synopsis, ' ');
			$synopsis = substr($synopsis, 0, $last_space) . ' ...';
		}

		return $synopsis;
	}
	
	/**
	* Processes arguments to parser function, setting defaults where required.
	* @see http://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	* @param $frame FIXME: what does frame really do?
	* @param $args array of arguments passed to parser function
	* @param $defaults array of default values for arguments
	* @return bool true in all cases
	*/
	static function processArgs( $frame, $args, $defaults ) {
		$new_args = array();
		$num_args = count($args);
		$num_defaults = count($defaults);
		$count = ($num_args > $num_defaults) ? $num_args : $num_defaults;
		
		for ($i=0; $i<$count; $i++) {
			if ( isset($args[$i]) )
				$new_args[$i] = trim( $frame->expand($args[$i]) );
			else
				$new_args[$i] = $defaults[$i];
		}
		return $new_args;
	}
	
	/**
	* Handler for BeforePageDisplay hook. Adds required modules for MeetingMinutes
	* @see http://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	* @param $out OutputPage object
	* @param $skin Skin being used.
	* @return bool true in all cases
	*/
	// static function onBeforePageDisplay( $out, $skin ) {
		// $out->addModules( array( 'ext.meetingminutes.base', 'ext.meetingminutes.form' ) );
	// }

	/**
	* Handler for meetingminutesform parser function.
	* @see http://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	* @param $parser Parser object
	* @param $frame FIXME: what does frame really do?
	* @param $args array of arguments passed to parser function
	* @return bool true in all cases
	*/
	static function renderMeetingMinutesFormParserFunction ( &$parser, $frame, $args ) {
		global $wgOut;
		$wgOut->addModules( array( 'ext.meetingminutes.form' ) );
		return '';
	}
	
	/**
	* Handler for meetingminutesform parser function.
	* @see http://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	* @param $parser Parser object
	* @param $frame FIXME: what does frame really do?
	* @param $args array of arguments passed to parser function
	* @return bool true in all cases
	*/
	static function renderMeetingMinutesTemplateParserFunction ( &$parser, $frame, $args ) {
		global $wgOut;
		$wgOut->addModules( array( 'ext.meetingminutes.template' ) );
		return '';
	}

}