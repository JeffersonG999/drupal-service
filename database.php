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

// Static queries
// Should be used for simple SELECT query
$database = \Drupal::database();
$query = $database->query("SELECT * FROM {node}");
$result = $query->fetchAll();

$database = \Drupal::database();
$query = $database->query("SELECT * FROM {node} WHERE nid = :nid", array(
  ':nid' => 5,
));
$result = $query->fetchAll();

$database = \Drupal::database();
$query = $database->query("SELECT * FROM {node} WHERE nid IN (:nids[])", array(
  ':nids[]' => array(3, 4, 5)
));
$result = $query->fetchAll();

$database = \Drupal::database();
$query = $database->query("SELECT * FROM {node} WHERE nid IN (:nid1, :nid2, :nid3)", array(
  ':nid1' => 3,
  ':nid2' => 4,
  ':nid3' => 5,
));
$result = $query->fetchAll();

// Result is array
$database = \Drupal::database();
$query = $database->query("SELECT * FROM {node} WHERE nid IN (:nid1, :nid2, :nid3)", array(
  ':nid1' => 3,
  ':nid2' => 4,
  ':nid3' => 5,
));
if ($result) {
  while ($row = $result->fetchAssoc()) {
    $row['nid'];
    $row['type'];
  }
}

// Dynamic queries
// Should be used for INSERT, UPDATE, or DELETE queries.
$query = \Drupal::database()->select('node', 'n')
->condition('n.nid', 3, '=')
->fields('n', ['nid', 'vid', 'type', 'langcode'])
->range(0, 50);
$result = $query->execute();
foreach ($result as $record) {
}

// Debug
echo $query;
print_r((string) $query);
print_r($query->arguments());

// Conditions
$select = \Drupal::database()->select('node', 'n');
$select->addExpression('MAX(nid)');
$result = $select->execute()->fetchField();

$select = \Drupal::database()->select('node', 'n');
$select->addExpression('COUNT(nid)');
$result = $select->execute()->fetchField();
