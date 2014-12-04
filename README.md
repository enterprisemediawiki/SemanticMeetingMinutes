Semantic Meeting Minutes
========================

This extension provides Javascript and CSS helpers to enable a slick method of entering meeting minutes into MediaWiki using Semantic Forms.

Installation
------------

The recommended way to install Semantic Meeting Minutes (SMM) is with [Composer](composer) using
[MediaWiki 1.22 built-in support for Composer](mwcomposer). MediaWiki versions prior to 1.22 can use Composer via the [Extension Installer](extensioninstaller) extension. Either way, you will first need to [install Composer](composerinstall).


### Step 1: Install SMM

Open a terminal window, and change directory to your wiki. Then run the following command to install SMM and several of its dependencies. Note that one of the dependencies is Semantic MediaWiki, which has additional requirements for install. See its [install instructions](smwinstall) for more info.

    php composer.phar require mediawiki/semantic-meeting-minutes: ~0.1

### Step 2: Install Non-Composer Dependencies

Ideally all extensions would support Composer, but at this time not all do. You will need to install the ParserFunctions, Variables and Semantic Forms extensions for SMM to work. You can install the latest versions by running the following three commands from your wiki's extensions directory.

    git clone git clone https://git.wikimedia.org/git/mediawiki/extensions/ParserFunctions.git
    git clone git clone https://git.wikimedia.org/git/mediawiki/extensions/Variables.git
    git clone git clone https://git.wikimedia.org/git/mediawiki/extensions/SemanticForms.git

### Step 3: Import forms, templates, categories and properties

SMM comes with many pre-built forms, templates, categories and properties. To create these on your wiki, use the importDump.php script from your wiki's install directory:

    php maintenance/importDump.php --conf LocalSettings.php /extensions/SemanticMeetingMinutes/ImportFiles/import.xml

### Step 4: Verify imported pages did not overwrite existing ones

It is possible that the SMM pages you imported in step 3 could have overwritten existing pages with the same names. Go to your Recent Changes page and review the changes that were made.
	
### Step 5: Optional steps

It is highly recommended that you make two further changes to get the most out of Semantic Meeting Minutes.

1. Update your _Mediawiki:Sidebar_ page and add the following link:

    Special:FormEdit/Meeting Minutes|Meeting Minutes

2. Create a footer for all pages in the Main namespace, using Extension:HeaderFooter (which was installed automatically when you installed SMM). To do this, go to the page _Mediawiki:Hf-nsfooter-_ and add the following:

```
{{#ask: [[Topic from meeting::+]][[Related article::{{FULLPAGENAME}}]]
|mainlabel=-
|? From page
|? Has date
|? Has topic title
|? Synopsis
|? Related article
|link = none
|format = template
|template = Meeting references row
|intro = <h2>Meeting References</h2>
|offset = 0
|limit = 10
|sort = Has date
|order = desc
|searchlabel = <br /><br /><br />Click to browse earlier meeting references
}}
```

[composer]: https://getcomposer.org/
[mwcomposer]: https://www.mediawiki.org/wiki/Composer
[extensioninstaller]: https://github.com/JeroenDeDauw/ExtensionInstaller/blob/master/README.md
[composerinstall]: https://getcomposer.org/doc/00-intro.md
[smwinstall]: https://github.com/SemanticMediaWiki/SemanticMediaWiki/blob/master/docs/INSTALL.md