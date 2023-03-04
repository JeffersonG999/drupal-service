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
// Should be used for INSERT, UPDATE, or DELETE queries, Or Complex SELECT query

// Multiple field
$query = \Drupal::database()->select('node', 'n')
->condition('n.nid', 3, '=')
->fields('n', ['nid', 'vid', 'type', 'langcode'])
->range(0, 50);
$result = $query->execute();

$query = \Drupal::database()->select('node', 'n')
->condition('n.nid', 3, '=')
->fields('n')
->range(0, 50);
$result = $query->execute();

// Single Field
$query = \Drupal::database()->select('node', 'n');
$query->condition('n.nid', 1, '=');
$query->addField('n', 'type');
$result = $query->execute();
$result->fetchField();

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

// Joins
// To join against another table, use the join(), innerJoin(), leftJoin(), or addJoin()
$query = \Drupal::database()->select('node', 'n');
$query->condition('n.nid', 1, '=');
$query->fields('n');
$query->join('users', 'u', 'n.uuid = u.uid AND u.uid = :uid', array(':uid' => 1));
$result = $query->execute()->fetchAll();

$query =\Drupal::database()->select('node', 'n', $options);
$query->join('node_field_data', 'nfd', 'n.nid = nfd.nid');
$query
  ->fields('n', array('nid'))
  ->fields('nfd', array('title'))
  ->condition('nfd.type', 'page')
  ->condition('nfd.status', '1')
  ->orderBy('nfd.created', 'DESC')
  ->addTag('node_access');

// Count Queries
// Get number of rows
$query = \Drupal::database()->select('node', 'n');
$query->condition('n.nid', 1, '=');
$query->fields('n');
$result = $query->countQuery()->execute()->fetchField();

// Distinct
$query = \Drupal::database()->select('node', 'n');
$query->condition('n.nid', 1, '=');
$query->fields('n');
$result = $query->distinct()->execute()->fetchAll();

// Group By
$query = \Drupal::database()->select('node', 'n')
->fields('n', ['uid']);
$query->addExpression('count(uid)', 'uid_node_count');
$query->groupBy("n.uid");
$result = $query->execute()->fetchAll();

// Having by
$query = $connection->select('node', 'n')
->fields('n',['uid']);
$query->addExpression('count(uid)', 'uid_node_count');
$query->groupBy("n.uid");
$query->having('COUNT(uid) >= :matches', [':matches' => 2]);
$results = $query->execute();

// Conditions
// Supported Operators
// '=', '<>', '<', '<=', '>', '>='
// 'IN', 'NOT IN'
// 'BETWEEN', 'NOT BETWEEN'
// 'IS NULL', 'IS NOT NULL', 'EXISTS', 'NOT EXISTS'
$query = \Drupal::database()->select('node', 'n');
$query->condition('n.nid', 0, '>');
$query->condition('n.type', array('page', 'article'), 'IN');
$query->condition('n.langcode', 'fr', '=');
$query->fields('n');
$result = $query->execute()->fetchAll();

// $query->isNull($field);
// $query->isNotNull($field);
// $query->exists($field);
// $query->notExists($field);
$query = \Drupal::database()->select('node', 'n');
$query->isNotNull('n.nid');
$query->condition('n.type', array('page', 'article'), 'IN');
$query->condition('n.langcode', 'fr', '=');
$query->fields('n');
$result = $query->execute()->fetchAll();

// Condition Groups
$query = \Drupal::database()->select('node', 'n');

$orGroup = $query->orConditionGroup()
->condition('type', 'page', '=')
->condition('type', 'article', '=');
$query->condition($orGroup);

$andGroup = $query->andConditionGroup()
->condition('nid', 0, '>')
->condition('nid', 3, '<');
$query->condition($andGroup);

$query->fields('n');
$result = $query->execute()->fetchAll();

// Ordering and filtering
$query = \Drupal::database()->select('node', 'n');
$query->fields('n');
$query->orderBy('n.nid', 'DESC');
$result = $query->execute()->fetchAll();

// Range
$query = \Drupal::database()->select('node', 'n');
$query->fields('n');
$query->range(0, 3);
$result = $query->execute()->fetchAll();
