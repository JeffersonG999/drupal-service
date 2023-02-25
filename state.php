https://api.drupal.org/api/drupal/core!lib!Drupal!Core!State!State.php/class/State
https://www.drupal.org/docs/8/api/state-api/overview
https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21State%21StateInterface.php/interface/StateInterface/10
https://antistatique.net/en/blog/drupal-8-differences-between-configuration-api-state-api

<?php

\Drupal::time()->getRequestTime())

// Return last cron
\Drupal::state()->get('system.cron_last');

// Set jefferson = goven and fetch = jefferson
\Drupal::state()->set('jefferson', 'goven');
\Drupal::state()->get('jefferson');

// Set jefferson = NULL and fetch = NULL
\Drupal::state()->set('jefferson', NULL);
\Drupal::state()->get('jefferson');

// Set jefferson = goven, delete and fetch = NULL
\Drupal::state()->set('jefferson', 'goven');
\Drupal::state()->delete('jefferson');
\Drupal::state()->get('jefferson');

//
\Drupal::state()->setMultiple(array('jeff' => 'jeffgoven', 'jefferson' => 'jeffersongoven'));
\Drupal::state()->get('jeff');
\Drupal::state()->get('jefferson');
\Drupal::state()->getMultiple(array('jeff', 'jefferson'));

\Drupal::state()->resetCache();
\Drupal::state()->get('jeff');
\Drupal::state()->get('jefferson');
\Drupal::state()->getMultiple(array('jeff', 'jefferson'));

\Drupal::state()->deleteMultiple(array('jeff', 'jefferson'));
\Drupal::state()->get('jeff');
\Drupal::state()->get('jefferson');
\Drupal::state()->getMultiple(array('jeff', 'jefferson'));
