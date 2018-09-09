Steps for updating libraries
----------------------------

  1. Create a ticket in the Webform issue queue
  2. Create a list of all recent releases
  3. Update WebformLibrariesManager
  4. Update webform.libraries.yml
  5. Test changes
  6. Update webform_libraries.module
  7. Update composer.libraries.json


1. Create a ticket in the Webform issue queue
----------------------------------------------

- https://www.drupal.org/node/add/project-issue/webform


2. Create a list of all recent releases
---------------------------------------

- Enable all external libraries (admin/structure/webform/config/libraries)

- Manually check for new releases. Only update to stable releases. 

- Add list of updated external libraries to issue on Drupal.org


3. Update WebformLibrariesManager
---------------------------------

- \Drupal\webform\WebformLibrariesManager::initLibraries


4. Update webform.libraries.yml
---------------------------------

- webform.libraries.yml


5. Test changes
---------------

Check external libraries are loaded from CDN.

    drush webform:libraries:remove

Check external libraries are download.

    drush webform:libraries:download


6. Update webform_libraries.module
----------------------------------

- Zip /libraries
- Copy libraries.zip to webform_libraries.


7. Update composer.libraries.json
----------------------------------

    cd web/modules/sandbox/webform
    drush webform:libraries:composer > composer.libraries.json
