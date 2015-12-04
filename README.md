Semantic Meeting Minutes
========================

This extension provides Javascript and CSS helpers to enable a slick method of entering meeting minutes into MediaWiki using Semantic Forms.

Installation
------------

The recommended way to install Semantic Meeting Minutes (SMM) is with [Composer](composer) using
[MediaWiki 1.22 built-in support for Composer](mwcomposer). MediaWiki versions prior to 1.22 can use Composer via the [Extension Installer](extensioninstaller) extension. Either way, you will first need to [install Composer](composerinstall).


### Step 1: Install SMM

Open a terminal window, and change directory to your wiki. Then run the following command to install SMM and several of its dependencies.

    php composer.phar require mediawiki/semantic-meeting-minutes ~0.3

### Step 1.1: Enable Semantic MediaWiki

If you didn't previously have Semantic MediaWiki installed (the Composer command above will install it if you did't have it already) make sure to add the following to LocalSettings.php:

```php
enableSemantics( 'name-of-your-wiki' );
```

Then run the following command from your MediaWiki install path:

    php maintenance/update.php

For additional info on installing SMW, see its [install instructions](smwinstall).

### Step 2: Install Non-Composer Dependencies

Ideally all extensions would support Composer, but at this time not all do. You will need to install the ParserFunctions, Variables and Semantic Forms extensions for SMM to work. You can install the latest versions by running the following three commands from your wiki's extensions directory.

    git clone https://git.wikimedia.org/git/mediawiki/extensions/ParserFunctions.git
    git clone https://git.wikimedia.org/git/mediawiki/extensions/Variables.git
    git clone https://git.wikimedia.org/git/mediawiki/extensions/SemanticForms.git

Then add the extensions to your LocalSettings.php file:

```php
require_once "$IP/extensions/ParserFunctions/ParserFunctions.php";
require_once "$IP/extensions/Variables/Variables.php";
require_once "$IP/extensions/SemanticForms/SemanticForms.php";
```

### Step 3: Import forms, templates, categories and properties

SMM comes with many pre-built forms, templates, categories and properties. To create these on your wiki, use the importDump.php script from your wiki's install directory..

    php ./extensions/SemanticMeetingMinutes/ImportFiles/importExtensionPages.php

### Step 4: Verify imported pages did not overwrite existing ones

It is possible that the SMM pages you imported in step 3 could have overwritten existing pages with the same names. Go to your Recent Changes page and review the changes that were made.

### Step 5: Optional steps

It is highly recommended that you add the Semantic Meeting Minutes form to your _Mediawiki:Sidebar_ page and add the following link:

    Special:FormEdit/Meeting Minutes|Meeting Minutes

Also, to get a footer on each page marked as a "related article" in meeting minutes, add the following to the `[[Mediawiki:Hf-nsfooter-]]` page:

```
__NOTOC__<br style="clear:both;" />{{#ask: [[Has topic title::+]][[Related article::{{PAGENAME}}]]
|mainlabel=-
|? From page
|? Has date
|? Has topic title
|? Synopsis
|? Related article
|link = none
|format = template
|template = Meeting references row
|intro = <h1>Meeting References</h1>
|offset = 0
|limit = 10
|sort = Has date
|order = desc
|searchlabel = <br /><br /><br />Click to browse earlier meeting references
}}
<headertabs />
```


[composer]: https://getcomposer.org/
[mwcomposer]: https://www.mediawiki.org/wiki/Composer
[extensioninstaller]: https://github.com/JeroenDeDauw/ExtensionInstaller/blob/master/README.md
[composerinstall]: https://getcomposer.org/doc/00-intro.md
[smwinstall]: https://github.com/SemanticMediaWiki/SemanticMediaWiki/blob/master/docs/INSTALL.md
