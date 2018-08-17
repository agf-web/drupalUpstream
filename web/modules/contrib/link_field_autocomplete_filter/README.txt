CONTENTS OF THIS FILE
---------------------

* Introduction
* Requirements
* Installation
* Configuration


INTRODUCTION
------------

Currently the autocomplete in Link Field widgets
always shows content suggestions from all content (node) types.

This module adds a Link Field configuration for filtering
the suggested content types in the autocomplete field.

REQUIREMENTS
------------

This module requires the following core module:

 * Link


INSTALLATION
------------

 * Install as you would normally install a contributed Drupal module. Visit:
   https://www.drupal.org/docs/8/extending-drupal-8/installing-drupal-8-modules


CONFIGURATION
-------------

 * Simply activate the module, and a series of checkboxes
   (one for each content type) will appear
   in the configuration form of your Link Field field-instances .

 * If you check none, then all content types will appear as suggestions
   in the autocomplete field. Otherwise only those checked will appear.

 * If you change the field settings later,
   the field will be validated for the new allowed content types.
