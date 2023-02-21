https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Extension%21ModuleHandler.php/class/ModuleHandler/10

<?php

\Drupal::service('module_handler');
\Drupal::moduleHandler()->moduleExists();
\Drupal::moduleHandler()->getModule();
\Drupal::moduleHandler()->getName();
\Drupal::moduleHandler()->hasImplementations();
\Drupal::service('module_handler')->getModule('my_module')->getPath();
