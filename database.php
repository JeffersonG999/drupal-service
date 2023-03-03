https://www.drupal.org/docs/drupal-apis/database-api
You should almost never be making database calls directly unless you are developing core APIs.

<?php

use \Drupal\Core\Database\Database;

// In settings.php $databases['default']['default']

// To access (and open if necessary) a connection object, use:
$database = \Drupal::database();
$database = \Drupal::service('database');

// To access the currently active connection, use
$conn = Database::getConnection();

// To set the active connection, use:
$conn = Database::setActiveConnection('external');

// In settings.php $databases['other_database']['default']
$connection = Database::getConnection('default', 'other_database');

// To set the active connection and return it use the following:
Database::setActiveConnection('other_database');
$database = Database::getConnection();
