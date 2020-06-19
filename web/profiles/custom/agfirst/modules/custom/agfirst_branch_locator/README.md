# AgFirst Branch Locator Module

The AgFirst Branch Locator Module unifies the following:

1. `getfarmcredit_client` module - the 'app' and its block.
2. `agfirst_locations` module - provides a data block for detail pages.
3. `branch` Content Type - previously named `location` and was manually created for each site.

**This module aims to solve the issue of stitching all these things together to work with consistent naming/structure/workflow.**

## Post Install Instructions
  1. Place the `AgFirst Branch Locator Block` into a region of your choice under `Structure -> Block Layout`.

## Sub-module Usage Instructions
  1. Install sub-module `agfirst_branch_locator_details`. This will install a **Branch** content type.
  2. Create a new **Branch**.
  3. Copy the `nid` of the location from **GetFarmCredit.com**.
  4. Paste into the field `Get Farm Credit ID`.
  5. Save.

## Theming Branch Detail
You'll need theme debugging turned on. Override by copying `node.html.twig` from `classy` core theme into your custom theme folder, and renaming to `node--branch.html.twig`.

## Notes
This module is not compatible with the following:

* `getfarmcredit_client`
* `agfirst_locations`
* `locations` Content Type on existing AgFirst Sites

----------------------------------------------------------------------------------------------------

# Breakdown
Breakdown of this module and sub-modules as follows:

## `agfirst_branch_locator`
  * Primary module that gets installed (this module).
  * **Requires placing block into region.**
  * Previously `getfarmcredit_client`. Initial changes include:
    * `AgfirstBranchLocatorBlock`: no longer using twig file, rendering an `inline_template` instead.

## `agfirst_branch_locator_details`
  * Sub-module, only shows up after installing the main module.
  * Will install `branch` content type.
  * Previously `agfirst_locations`. Initial Changes include:
    * `AgfirstLocationsExtrasBlock`: renamed to `AgfirstBranchLocatorDetailsClientAppDataBlock`.
    * `AgfirstLocationsExtrasBlock`: looks for `branch` content instead of `location`.
    * `AgfirstLocationsExtrasBlock`: no longer using twig file, rendering `html_tag` of `script`.

## Content type: `branch`
  * fields:
    * Get Farm Credit Id (machine name: `field_get_farm_credit_id`).
    * Body (OOTB, disabled)

----------------------------------------------------------------------------------------------------

# Module Info

## CW TSD Notes (Technical Specifications Document)

* AgFirst Branch Locator App (Custom Block)
  * Requires `Block Layout` placement into region.
  * Settings Configuration Screen in admin
* AgFirst Branch Locator Detail (optional sub-module)
  * Content Type: `branch`
    * Required Field - `Branch` - Text Field
    * Required Field - `Get Farm Credit ID` - Number field
    * Detail page does not have any visible fields from this content type.
    * Detail page pulls in Google Map and Branch Details (address, etc) from GetFarmCredit.com using field `Get Farm Credit ID`.
  * Map Block
    * Pulled from field `Get Farm Credit ID`, injected into detail page.
  * Data Block
    * Pulled from field `Get Farm Credit ID`, injected into detail page.

## Repo
[https://bitbucket.org/cyberwoven/agfirst_branch_locator/src](https://bitbucket.org/cyberwoven/agfirst_branch_locator/src)

## TODO
scope | description
------|-------------
`agfirst_branch_locator` | have remove JSON endpoint be configurable. would help testing changes to the API.
`agfirst_branch_locator_details` | refactor `AgfirstBranchLocatorDetailsMapBlock` to use `html_tag` of `iframe`. see `AgfirstBranchLocatorDetailsClientAppDataBlock`.
`agfirst_branch_locator_details` | when saving content type, check if valid `nid` by making a ajax call to GetFarmCredit.com. (or cron job pull all nid's?). when saving could also get the Branch Name from the ajax call and populate the title in `branch` instance?
`branch_locator` | add update hook to grab defaults of configs and merge with existing configs. this will improve maintenance stability and alleviate post-module updates (missing config values, etc).
