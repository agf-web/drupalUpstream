# Cyberwoven Alert Bar

This modules provides a simple themeable alert bar.

## Functionality
This module creates a block which can be placed normally. The alert can be managed through
a configuration form found at **Content > Manage alert**.

Out ouf the box, the alert block contains a message, a close button and a "Learn More" link.
When clicked, the close button removes a "show-alert" class from the block, and creates a
session cookie to prevent the "show-alert" class from being added again (for that session).

## Theming
A twig file (cw-alert-bar.html.twig) is provided with the module. It can be overridden
at the theme level. A stylesheet has been included to enable the close button.
