README.txt
==========

Allows users to create & display content in an accordion.
------------------------
Requires - Drupal 8


Overview:
--------
Adds a new button to Drupal 8's built in CKEditor which allows the user
to create & display any type of content in an accordion format.

The styling is minimal blue and easily over writeable by developers.

Once the Accordion has been created, "ckeditorAccordionAttached" event
gets triggered on the accordion element.


INSTALLATION:
--------
1. Install & Enable the module
2. Open Administration > Configuration > Content authoring >
   Text formats and editors (admin/config/content/formats)
3. Edit a text format's settings (usually Basic HTML)
4. Drag n Drop the Add Accordion -button to the toolbar to show it to the users
5. Scroll down to the bottom to the input Allowed HTML tags
6. Find and replace <dl> with <dl class>
   This ensures CKEditor doesn't remove the class name that the accordion uses.
7. If you would like the Accordion tabs to be closed by default,
	 you can change this setting at: /admin/config/content/ckeditor-accordion
