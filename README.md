# AgFirst Core

This repository is a start state for a Composer-based Drupal workflow with Pantheon.

This project is based on Pantheon's example composer drop 8 project:
https://github.com/pantheon-systems/example-drops-8-composer

## Installation

_This Upstream is for internal use of AgFirst only. It cannot be directly installed unless you are a member of that team
on Pantheon._

The most reliable way to install this upstream is using Terminus:
```terminus site:create --org="AgFirst Farm Credit Bank" agf-test-site "AgFirst Test" agfirst-default-drupal-upstream```

You can try to install it through the web interface, although that hasn't been perfectly reliable due to the number of 
required composer packages.

1. Log into your Pantheon account.
1. Click Create New Site
1. Give the site a name, and assign it to the AgFirst organization (the select field directly below the name).
1. On the next screen you still see your custom upstream "AgFirst Default Drupal Upstream".  Click the deploy button.
1. Pantheon's system will then process for a minute or two while it creates a copy of the repository and sets up their 
environment.
1. When the deployment process completes, you will be invited to go to your dashboard where you will see an install 
button which will trigger Drupal's installation process.
1. The only option you need to select is the language (which defaults to US English).  From there Drupal's installer 
will take over and install your site.

## Updating your site

Sites created from this upstream will not use the standard Pantheon dashboard-based process to update Drupal. Instead, 
you will manage your updates using Composer. Updates can be applied either through Terminus, or on your local machine.

_Note: This process is still evolving._

There are two main sources of changes to sites built with this process: the shared upstream materials, and custom 
development. Changes to the shared modules and versions will be handled through the upstream. As much as possible Drupal 
contrib modules should be handled as additions to the upstream and not as custom additions to a specific site. When 
these updates are released, each site must be placed it git mode on the Pantheon dashboard to merge the changes into
the local repository.  Once that merge is complete you can either use terminus to update and install dependencies or you
can switch the site to SFTP mode and it will run the process automatically.

During regular development you may need to add additional modules or libraries. These should be installed with composer
and handled as dependencies of a custom module or theme of the site itself _not_ of the project's root composer.json. 

Those updates you can in SFTP mode on Pantheon via Terminus or in Git mode locally. For any given site you will likely 
want to use one of these techniques and not the other. Trying to run them in combination is going to cause git conflicts
in your local environment.  That maybe unavoidable from time to time.  Hopefully Pantheon will continue to improve their
support of Composer and allow us to simplify this process over time and better integrate with their platform.

### Update with Terminus

Install [Terminus 1](https://pantheon.io/docs/terminus/) and the 
[Terminus Composer plugin](https://github.com/pantheon-systems/terminus-composer-plugin).  Then, to update your site, 
ensure it is in SFTP mode, and then run:

```terminus composer <sitename>.<dev> update```

Other commands will work as well; for example, you may install new modules using:

```terminus composer <sitename>.<dev> require drupal/pathauto```

### Update on your local machine

You may also place your site in Git mode, clone it locally, and then run composer commands from there.  Commit and push 
your files back up to Pantheon. 


## Ways to improve workflows

Pantheon provides a number of example scripts that can be used as jumping off points for useful workflow integrations 
improvements: https://github.com/pantheon-systems/quicksilver-examples
