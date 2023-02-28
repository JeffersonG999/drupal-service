<?php
// The cache keys associated with the state of the config factory. Array([0] => global_overrides, [1] => fr)
\Drupal::configFactory()->getCacheKeys();
// Gets configuration object names starting with a given prefix. Array([0] => system.site)
\Drupal::configFactory()->listAll('system.site');
// Get config name by key
\Drupal::configFactory()->get('system.site')->get('name');
// Get config name by key
\Drupal::configFactory()->getEditable('system.site')->get('name');
// Set new config name by key and value
\Drupal::configFactory()->getEditable('system.site')->set('name', 'Jefferson')->save();
print_r(\Drupal::configFactory()->get('system.site')->get('name'));
// Get multiple config object
$configs = \Drupal::configFactory()->loadMultiple(array('system.site'));
foreach ($configs as $config) {
  print_r($config->getRawData());
}
// Rename config
\Drupal::configFactory()->rename('system.site', 'system.jefferson');
print_r(\Drupal::configFactory()->get('system.jefferson')->get('name'));
// Rename to first version
\Drupal::configFactory()->rename('system.jefferson', 'system.site');
print_r(\Drupal::configFactory()->get('system.site')->get('name'));
// Reset config
\Drupal::configFactory()->reset('system.site');
print_r(\Drupal::configFactory()->get('system.site')->get('name'));
