<?php
/**
 * This script updates the extensions managed by the it
 *
 * Usage:
 *  no parameters
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @author James Montalvo
 * @ingroup Maintenance
 */

// @todo: does this always work if extensions are not in $IP/extensions ??
// this was what was done by SMW
$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/../..';
require_once $basePath . '/maintenance/Maintenance.php';


class SemanticMeetingMinutesImportExtensionPages extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->mDescription = "Get extension pages from SMM files and push to the wiki.";

		$this->addOption( 'dry-run', 'See what would be changed without making changes' );
	}

 	// initiates or updates extensions
	public function execute() {

		$dryRun = $this->hasOption( 'dry-run' );
		$pages = json_decode( file_get_contents( __DIR__ . "/pages.json" ) );

		foreach( $pages as $pageTitleText => $filePath ) {

			$wikiPage = WikiPage::factory( Title::newFromText( $pageTitleText ) );
			$wikiPageContent = $wikiPage->getContent();

			$filePageContent = file_get_contents( "$egSmmPageFilePath/$filePath" );

			if ( $filePageContent !== $wikiPageContent ) {

				if ( $dryRun ) {
					echo "$pageTitleText would be changed.\n";
					// @todo: show diff?
				}
				else {
					echo "$pageTitleText changed.\n";
					$wikiPage->doEditContent(
						new WikitextContent( $filePageContent ),
						"Updated with content from Extension:SemanticMeetingMinutes version " . SEMANTIC_MEETING_MINUTES_VERSION,
					);
				}
			}
			else {
				echo "No change for $pageTitleText\n";
			}
		}


		$this->output( "\n## Finished retrieving Semantic Meeting Minutes pages.\n" );
		$this->showErrors();
		$this->output( "\n" );
	}

}

$maintClass = "SemanticMeetingMinutesImportExtensionPages";
require_once( DO_MAINTENANCE );
