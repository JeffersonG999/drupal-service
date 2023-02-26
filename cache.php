https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Cache%21CacheBackendInterface.php/interface/CacheBackendInterface/10

<?php

// Request default cache bin
\\Drupal::cache();

// Request a specific cache bin
\\Drupal::cache('bin2');

//
\Drupal::cache()->set('jeff', 'goven');
$cache = \Drupal::cache()->get('jeff');
$cache->cid; // jeff
$cache->data; // goven
$cache->created; // 1677422420.598
$cache->expire; // -1
$cache->serialized; // 0
$cache->tags; // array()
$cache->checksum; // 0
$cache->valid; // 1

// 
$items = array(
  'jeff' => array(
    'data' => 'gov'
  ),
  'jefferson' => array(
    'data' => 'goven'
  ),
);
$cids = array(
  'jeff',
  'jefferson'
);
\Drupal::cache()->setMultiple($items);
\Drupal::cache()->get('jeff');
\Drupal::cache()->get('jefferson');
\Drupal::cache()->getMultiple($cids);

//
\Drupal::cache()->invalidate('jeff');
\Drupal::cache()->get('jeff');
\Drupal::cache()->get('jeff', TRUE);

//
\Drupal::cache()->invalidateMultiple($cids);
\Drupal::cache()->getMultiple($cids);
\Drupal::cache()->getMultiple($cids, TRUE);
  
//
\Drupal::cache()->invalidateAll();
\Drupal::cache()->getMultiple($cids);
\Drupal::cache()->getMultiple($cids, TRUE);

//
\Drupal::cache()->set('jeff', 'goven');
\Drupal::cache()->invalidate('jeff');
\Drupal::cache()->garbageCollection();
\Drupal::cache()->get('jeff');

// 
\Drupal::cache()->set('jeff', 'goven');
\Drupal::cache()->delete('jeff');
\Drupal::cache()->get('jeff');
\Drupal::cache()->get('jeff', TRUE);

//
\Drupal::cache()->set('my_cache_item', $school_list, \Drupal::time()->getRequestTime() + (86400));

//
// node:5 — cache tag for Node entity 5 (invalidated whenever it changes)
// user:3 — cache tag for User entity 3 (invalidated whenever it changes)
// node_list — list cache tag for Node entities (invalidated whenever any Node entity is updated, deleted or created, i.e., when a listing of nodes may need to change). Applicable to any entity type in following format: {entity_type}_list.
// node_list:article — list cache tag for the article bundle (content type). Applicable to any entity + bundle type in following format: {entity_type}_list:{bundle}.
// config:node_type_list — list cache tag for Node type entities (invalidated whenever any content types are updated, deleted or created). Applicable to any entity type in the following format: config:{entity_bundle_type}_list.
// config:system.performance — cache tag for the system.performance configuration
// library_info — cache tag for asset libraries

\Drupal::cache()->set('jeff', 'goven', Cache::PERMANENT, array('user:1', 'node:1'));
\Drupal::cache()->set('jeff2', 'goven2', Cache::PERMANENT, array('user:2', 'node:2'));
\Drupal::cache()->set('jeff3', 'goven3', Cache::PERMANENT, array('user:3', 'node:3'));
Cache::invalidateTags(array('node:1'));

// Drupal core cache context
cookies
  :name
headers
  :name
ip
languages
  :type
protocol_version // Available in 8.9.x or higher.
request_format
route
  .book_navigation
  .menu_active_trails
    :menu_name
  .name
session
  .exists
theme
timezone
url
  .path
    .is_front // Available in 8.3.x or higher.
    .parent
  .query_args
    :key
    .pagers
      :pager_id
  .site
user
  .is_super_user
  .node_grants
    :operation
  .permissions
  .roles
