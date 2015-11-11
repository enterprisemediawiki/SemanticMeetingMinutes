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


class SemanticMeetingMinutesGetExtensionPagesText extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->mDescription = "Get the current text of all the pages defined by the extension and save to files.";

		$this->addOption( 'dry-run', 'Parse dump without actually importing pages' );

	}

 	// initiates or updates extensions
	public function execute() {

		$dryRun = $this->hasOption( 'dry-run' );
		$pageGroups = json_decode( file_get_contents( __DIR__ . "/pages.json" ) );

		foreach( $pageGroups as $namespace => $pages ) {
			foreach( $pages as $page ) {
				$wikiPageContent = WikiPage::factory( Title::newFromText( "$namespace:$page" ) )->getContent();
				$filePageContent = file_get_contents( __DIR__ . "/$namespace/$page" );

				if ( $filePageContent !== $wikiPageContent ) {

					if ( $dryRun ) {
						echo "$namespace:$page would be changed.\n";
						// @todo: show diff?
					}
					else {
						echo "$namespace:$page changed.\n";
						file_put_contents( __DIR__ . "$namespace/$page" , $wikiPageContent );
					}
				}
				else {
					echo "No change for $namespace:$page\n";
				}
			}
		}

		$this->output( "\n## Finished retrieving Semantic Meeting Minutes pages.\n" );
		$this->showErrors();
		$this->output( "\n" );
	}

}

$maintClass = "SemanticMeetingMinutesGetExtensionPagesText";
require_once( DO_MAINTENANCE );
