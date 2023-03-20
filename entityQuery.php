<?php

// Fetch node
$query = \Drupal::entityQuery('node');

// Fetch taxonomy
$query = \Drupal::entityQuery('taxonomy_term');

// Fetch user
$query = \Drupal::entityQuery('user');

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
$query->accessCheck(TRUE);
$tids = $query->execute();

// Fetch user
