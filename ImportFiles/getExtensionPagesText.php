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
$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/../../..';
require_once $basePath . '/maintenance/Maintenance.php';


class SemanticMeetingMinutesGetExtensionPagesText extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->mDescription = "Get the current text of all the pages defined by the extension and save to files.";

		$this->addOption( 'dry-run', 'See what would be changed without making changes' );

	}

 	// initiates or updates extensions
	public function execute() {

		global $egSmmPageFilePath;

		$dryRun = $this->hasOption( 'dry-run' );
		$pages = json_decode( file_get_contents( __DIR__ . "/pages.json" ) );

		foreach( $pages as $pageTitleText => $filePath ) {

			$wikiPage = WikiPage::factory( Title::newFromText( $pageTitleText ) );
			$wikiPageContent = $wikiPage->getContent();
			if ( $wikiPageContent ) {
				$wikiPageText = $wikiPageContent->getNativeData();
			}
			else {
				$wikiPageText = '';
			}

			$filePageContent = file_get_contents( "$egSmmPageFilePath/$filePath" );

			if ( trim( $filePageContent ) !== trim( $wikiPageText )  ) {

				if ( $dryRun ) {
					echo "$pageTitleText would be changed.\n";
					// @todo: show diff?
				}
				else {
					echo "$pageTitleText changed.\n";
					file_put_contents( "$egSmmPageFilePath/$filePath" , $wikiPageText );
				}
			}
			else {
				echo "No change for $pageTitleText\n";
			}
		}

		$this->output( "\n## Finished retrieving Semantic Meeting Minutes pages.\n" );
		$this->output( "\n" );
	}

}

$maintClass = "SemanticMeetingMinutesGetExtensionPagesText";
require_once( DO_MAINTENANCE );