https://api.drupal.org/api/drupal/core%21lib%21Drupal.php/function/Drupal%3A%3Adestination/10

<?php

// Get path
\Drupal::destination()->get();
// /web/example

// Get path as array
\Drupal::destination()->getAsArray();
// array([destination] => /web/example)

// Set path
\Drupal::destination()->set('/node/1');
\Drupal::destination()->get();
// /node/1
