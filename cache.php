https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Cache%21CacheBackendInterface.php/interface/CacheBackendInterface/10

<?php

// Request default cache bin
\\Drupal::cache();

// Request a specific cache bin
\\Drupal::cache('bin2');

// Example of cache bin
cache.default Cache général
cache.render Éléments de rendu
cache.data Données applicatives
cache.bootstrap Données de démarrage
cache.config Configuration
cache.discovery Plugins, annotations

// Lire
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

//  Set an array
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

// Invalide un cache
\Drupal::cache()->invalidate('jeff');
\Drupal::cache()->get('jeff');
\Drupal::cache()->get('jeff', TRUE);

// Invalide plusieurs caches
\Drupal::cache()->invalidateMultiple($cids);
\Drupal::cache()->getMultiple($cids);
\Drupal::cache()->getMultiple($cids, TRUE);
  
// Invalide tous les caches
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

// Avec expiration
\Drupal::cache()->set('my_cache_item', $school_list, \Drupal::time()->getRequestTime() + (86400));

// Plusieurs méthodes d'invalidation
// Invalider
\Drupal::cache()->delete('mon_module:ma_cle');
\Drupal::cache()->deleteMultiple(['cle1', 'cle2']);
\Drupal::cache()->deleteAll(); // vide tout le bin

// Cache Tags — invalidation par tags (le plus puissant)
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

use Drupal\Core\Cache\Cache;

// Écrire avec des tags
$cache->set('ma_cle', $data, CacheBackendInterface::CACHE_PERMANENT, [
  'node:42',           // invalidé quand le node 42 est modifié
  'taxonomy_term:5',   // invalidé quand le terme 5 est modifié
  'config:system.site',
  'monmodule_liste',   // tag custom
]);

// Invalider manuellement par tag
Cache::invalidateTags(['monmodule_liste']);
Cache::invalidateTags(['node:42']);

// Tags utiles fournis par Drupal automatiquement :
// node:{nid}, taxonomy_term:{tid}, user:{uid}, config:{config_name}

// Cache in service/class
// Add in service.yml
arguments: ['@cache.data']

// In Service class
use Drupal\Core\Cache\CacheBackendInterface;

class MonService {
  public function __construct(
    private readonly CacheBackendInterface $cache,
  ) {}

  public function getDonnees(int $id): array {
    $cid = 'monmodule:donnees:' . $id;

    $cached = $this->cache->get($cid);
    if ($cached !== FALSE) {
      return $cached->data;
    }

    $this->cache->set($cid, $data, CacheBackendInterface::CACHE_PERMANENT, [
      'node:' . $id,
      'monmodule_tag',
    ]);

    return $data;
  }
}

// Cache Contexts — vary par contexte
$build['#cache'] = [
  'contexts' => [
    'user.roles',         // varie par rôle
    'languages:language_interface', // varie par langue
    'url.path',           // varie par URL
    'url.query_args',     // varie par query string
  ],
  'tags' => ['node_list'],
  'max-age' => 3600,
];

// Cache dans les Render Arrays
$build = [
  '#theme' => 'mon_theme',
  '#data'  => $data,
  '#cache' => [
    'keys'     => ['monmodule', 'ma_vue', $id],  // clé unique du render cache
    'tags'     => ['node:' . $id, 'taxonomy_term_list'],
    'contexts' => ['user.roles', 'languages:language_interface'],
    'max-age'  => CacheBackendInterface::CACHE_PERMANENT, // ou nb de secondes
  ],
];

// Pattern classique : cache-then-compute
$cid = 'monmodule:bloc:' . $langcode;

if ($cached = $this->cache->get($cid)) {
  return $cached->data;
}

$tags = Cache::mergeTags(
  ['node_list', 'taxonomy_term_list'],
  $donnees_tags  // tags collectés dynamiquement
);

$this->cache->set($cid, $data, CacheBackendInterface::CACHE_PERMANENT, $tags);

// Fusion
use Drupal\Core\Cache\Cache;

// Fusionner des tags
$tags = Cache::mergeTags($tags1, $tags2);

// Fusionner des contexts
$contexts = Cache::mergeContexts($ctx1, $ctx2);

// Fusionner des max-age (retourne le minimum)
$max_age = Cache::mergeMaxAges(3600, 1800); // = 1800

// max-age "jamais cachable"
Cache::mergeMaxAges(0, 3600); // = 0

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
