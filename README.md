MeetingMinutes
==============

This extension provides Javascript and CSS helpers to enable a slick method of entering meeting minutes into MediaWiki using Semantic Forms.

Dependencies
------------
* Extension:NumerAlpha
* Probably HeaderFooter
* Suggested MasonryMainPage


Pages you must create or import
-------------------------------
The following pages must exist on your wiki for this extension to work. See the XML import file in /ImportFiles directory for the version used by the EVA Wiki.

The major templates:
* Template:Meeting
* Template:Meeting minutes
* Template:Topic from meeting

The major forms:
* Form:Meeting
* Form:Meeting Minutes

The categories:
* Category:Meeting
* Category:Meeting Minutes

For displaying on Main Page or elsewhere:
* Template:Meeting Minutes Block 
* Template:Meeting Minutes Block info for table row
* Template:Meeting Minutes Block info for table row highlighted

Properties for meetings:
* Property:Call in number
* Property:Call in password
* Property:Managed by cadre
* Property:Standard representative
* Property:Standard day
* Property:Standard time

Properties for meeting minutes:
* Property:Meeting date
* Property:Meeting type
* Property:Notes taken by
* Property:Start time

Properties for meeting minutes topic subobjects:
* Property:From page
* Property:Has date
* Property:Has topic title
* Property:Index
* Property:Related article
* Property:Synopsis
