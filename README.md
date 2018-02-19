# AgFirst Core

This repository is a start state for a Composer-based Drupal workflow with Pantheon. 

## Installation



## Updating your site

Sites created from this upstream will not use the Pantheon dashboard to update Drupal. Instead, you will manage your updates using Composer. Updates can be applied either through Terminus, or on your local machine.

#### Update with Terminus

Install [Terminus 1](https://pantheon.io/docs/terminus/) and the [Terminus Composer plugin](https://github.com/pantheon-systems/terminus-composer-plugin).  Then, to update your site, ensure it is in SFTP mode, and then run:
```
terminus composer <sitename>.<dev> update
```
Other commands will work as well; for example, you may install new modules using `terminus composer <sitename>.<dev> require drupal/pathauto`.

#### Update on your local machine

You may also place your site in Git mode, clone it locally, and then run composer commands from there.  Commit and push your files back up to Pantheon as usual.


