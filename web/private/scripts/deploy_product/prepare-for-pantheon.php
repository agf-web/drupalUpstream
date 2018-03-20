<?php

chdir(getenv('HOME') . '/code');

print "\n====== Running 'composer install' ======\n\n";
passthru('composer install 2>&1');

print "\n====== Running 'composer prepare-for-pantheon' ======\n\n";
passthru('composer prepare-for-pantheon 2>&1');
