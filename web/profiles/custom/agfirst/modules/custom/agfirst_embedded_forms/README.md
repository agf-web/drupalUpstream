## AgFirst Embedded Forms Module

This module is designed to provide for safe embedding of forms from various 3rd parties that AgFirst uses for contact forms and other similar needs.

Each provider is implemented as an independent field type with associated widgets and formatters.  This allows any user with rights to the field to embed a form without needing to be able to embed JavaScript in a page directly.

Any content type that needs to support multiple form types should also include a select field that provides a field dependency to control the fields created by this module.

Support for the following providers is currently planned:
* JotForms
* ClickDimensions
* ShortStack
