<?php

// Get site name
\Drupal::config('system.site')->get('name');
// Get site name with default value if NULL
\Drupal::config('system.site')->get('name', 'Jefferson');
// Dump all config data
\Drupal::config('system.site')->getRawData();
// Set config data and overide original value. Ex: Test, Jefferson, Jefferson
\Drupal::config('system.site')->get('name');
\Drupal::config('system.site')->initWithData(array('name' => 'Jefferson');
\Drupal::config('system.site')->get('name');
\Drupal::config('system.site')->getOriginal('name');
\Drupal::config('system.site')->hasOverrides('name');
// Set config data and dont overide original value. Ex: Test, Jefferson, Test
\Drupal::config('system.site')->get('name');
\Drupal::config('system.site')->setData(array('name' => 'Jefferson');
\Drupal::config('system.site')->get('name');
\Drupal::config('system.site')->getOriginal('name');
\Drupal::config('system.site')->hasOverrides('name');
// Set module overide for this config
\Drupal::config('system.site')->setModuleOverride(array('name' => 'Jefferson'));
// Set settings.php overide for tbis config
\Drupal::config('system.site')->setSettingsOverride(array('name' => 'Jefferson'));
