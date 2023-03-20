<?php

// Load service node
$query = \Drupal::entityQuery('node');

// Load service taxonomy
$query = \Drupal::entityQuery('taxonomy_term');

// Load service user
$query = \Drupal::entityQuery('user');

// Load service
$query = \Drupal::service('entity.query');
$query->get('node');
$query->get('taxonomy');
$query->get('user');

// Fetch node
$query = \Drupal::entityQuery('node');
$query->condition('title', 'Premier article');
$query->condition('title', '%' . db_like($keyword) . '%', 'LIKE');
$query->condition('type', 'node_type');
$query->condition('status', 1);
$query->condition('field_some_field', 14);
$query->condition('field_some_field', [1, 2], 'IN');
$query->condition('field_some_field', substr($code_postal, 0, 2) . '%', 'LIKE');
$query->exists('field_some_field');
$query->notExists('field_some_field');
$query->sort('nid', 'DESC'); // ASC
$query->sort('created', 'DESC'); // ASC
$query->sort('changed', 'DESC'); // ASC
$query->range(0, 4);
$query->pager(10);
$query->accessCheck(TRUE);
$nids = $query->execute();

// Fetch taxonomy
$query = \Drupal::entityQuery('taxonomy_term');
$query->condition('name', 'Black');
$query->condition('name', '%' . db_like($keyword) . '%', 'LIKE');
$query->condition('vid', 'tags');
$query->condition('status', 1);
$query->condition('field_some_field', 14);
$query->condition('field_some_field', [1, 2], 'IN');
$query->condition('field_some_field', substr($code_postal, 0, 2) . '%', 'LIKE');
$query->exists('field_some_field');
$query->notExists('field_some_field');
$query->sort('tid', 'ASC'); // DESC
$query->sort('changed', 'ASC'); // DESC
$query->range(0, 4);
$query->->pager(10);
$query->accessCheck(TRUE);
$tids = $query->execute();

// Fetch user
$query = \Drupal::entityQuery('user');
$query->condition('roles', 'Administrator', 'CONTAINS') ;
$query->accessCheck(TRUE);
$uid = $query->execute();


// Condition OR
$query = \Drupal::entityQuery('user');

$condition_or = $query->orConditionGroup();
$condition_or->condition('field_prenom', 'Jefferson');
$condition_or->condition('field_prenom', 'Jeff');

$query->condition($condition_or);

$uids = $query->execute();

// Condition AND
$query = \Drupal::entityQuery('user');

$condition_and = $query->andConditionGroup();
$condition_and->condition('field_prenom', 'Jefferson');
$condition_and->condition('field_nom', 'Goven');

$query->condition($condition_and);

$uids = $query->execute();

// Condition OR et AND
$query = \Drupal::entityQuery('user');

$condition_or = $query->orConditionGroup();
$condition_or->condition('roles', 'Administrator');

$condition_and = $query->andConditionGroup();

$condition_and->condition('field_prenom', 'Jeff');
$condition_and->condition('field_nom', 'Goven');

$condition_or->condition($condition_and);

$query->condition($condition_or);

$uids = $query->execute();

