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

// Result
// Get single result
$query = \Drupal::database()->select('node', 'n');
$query->fields('n');
$query->range(0, 3);
$result = $query->execute();
$record = $result->fetch(); // Object
$record = $result->fetchObject(); // Object
$record = $result->fetchAssoc(); // Array

// Get specific field
$query = \Drupal::database()->select('node', 'n');
$query->fields('n', ['nid', 'vid', 'type', 'langcode']);
$query->range(0, 3);
$result = $query->execute();
$record = $result->fetchField(0); // Nid field
$record = $result->fetchField(1); // Vid field
$record = $result->fetchField(2); // Type fied
$record = $result->fetchField(3); // Langcode fied

// Get number od row
$query = \Drupal::database()->select('node', 'n');
$query->fields('n');
$query->range(0, 3);
$result = $query->countQuery()->execute()->fetchField();

// Get multiple values
$query = \Drupal::database()->select('node', 'n');
$query->fields('n');
$result = $query->execute();
$records = $result->fetchAll(); // Array of object
$records = $result->fetchAllAssoc('nid'); // Array of object with array key = field
$records = $result->fetchAllKeyed(); // Array with key = field_0 => value = field_1
$records = $result->fetchAllKeyed(0, 2); // Array with key = field_0 => value = field_2
$records = $result->fetchCol(); // Array with value = field_0, key is auto
$records = $result->fetchCol(2); // Array with value = field_2, key is auto

// Fetching Into a Custom Class
// A revoir

// Insert Queries
// Compact form
\Drupal::database()->insert('web_example')
->fields([
  'uid' => 1,
  'status' => 1,
  'data' => time(),
])
->execute();

\Drupal::database()->insert('web_example')
->fields(['uid', 'status', 'data'])
->values([
  'uid' => 1,
  'status' => 1,
  'data' => time(),
])
->execute();

\Drupal::database()->insert('web_example')
->fields(['uid', 'status', 'data'])
->values([
  'uid' => 1,
  'status' => 1,
  'data' => time(),
])
->values([
  'uid' => 2,
  'status' => 1,
  'data' => time(),
])
->values([
  'uid' => 3,
  'status' => 0,
  'data' => time(),
])
->execute();

$query = \Drupal::database()->select('node', 'n');
$query->addField('n', 'nid');
$query->addField('u', 'name');
$query->condition('type', 'page');
// Perform the insert.
\Drupal::database()->insert('web_example')
->from($query)
->execute();

// Update Queries
\Drupal::database()->update('web_example')
->fields([
  'uid' => 2,
  'status' => 6,
  'data' => time(),
])
->condition('id', 1, '=')
->execute();

// Merge Queries
// Insert + Update
\Drupal::database()->merge('web_example')
->key('id', 1)
->fields([
  'uid' => 2,
  'status' => 6,
  'data' => time(),
])
->execute();

\Drupal::database()->merge('web_example')
->insertFields([
  'uid' => 2,
  'status' => 6,
  'data' => time(),
])
->updateFields([
  'uid' => 2,
  'status' => 0,
  'data' => 'jefferson'
])
->key('id', 7)
->execute();

// Upsert Queries
$upsert = \Drupal::database()->upsert('web_example')
->fields(['uid', 'status', 'data'])
->key('id');
$upsert->values([
  'uid' => 2,
  'status' => 0,
  'data' => 'jefferson',
  'id' => 1,
]);
$upsert->values([
  'uid' => 2,
  'status' => 0,
  'data' => 'jefferson goven',
  'id' => 2,
]);
$upsert->execute();

// Delete Queries
\Drupal::database()->delete('web_example')
->condition('id', 1)
->execute();

// Truncate Queries
\Drupal::database()->truncate('web_example')->execute();

// Transaction
// If second query depend on first query and secon query failed, transaction cancel first query
$transaction = \Drupal::database()->startTransaction();
try {
  // Do some thing that writes to the database, such as creating an entity.
  $media->save();
  // Do another database write that depends upon the first.
  $node->save();
}
catch (Exception $e) {
  // There was an error in writing to the database, so the database is rolled back
  // to the state when the transaction was started.
  $transaction->rollBack();
   // Log the exception to watchdog.
  \Drupal::logger('type')->error($e->getMessage());
}
// Commit the transaction by unsetting the $transaction variable.
unset($transaction);

Règles d'or
Toujours préférer l'Entity API (entityTypeManager) pour les entités Drupal (nodes, terms, users…) — la Database API est réservée aux tables custom ou aux requêtes de performance critique qui nécessitent des jointures complexes impossibles via l'Entity Query.
php// ✗ À éviter pour les entités Drupal
$db->select('node_field_data', 'n')...

// ✓ Préférer
\Drupal::entityTypeManager()->getStorage('node')->getQuery()...

// ✓ Database API — cas légitimes
$db->select('monmodule_table', 'm')...   // table custom
$db->select('watchdog', 'w')...          // lecture de logs
$db->select('sessions', 's')...          // sessions

  // Load by storage in service/class
// Add in service.yml
arguments: ['@database']

// In Service class
use Drupal\Core\Database\Connection;

class MonService {
  public function __construct(
    private readonly Connection $database,
  ) {}
}
