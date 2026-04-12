https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21DependencyInjection%21ClassResolverInterface.php/interface/ClassResolverInterface/10

<?php

use Drupal\user\Controller\UserController;

$userController = \Drupal::classResolver()->getInstanceFromDefinition(UserController::class);
$userController->logout();

$userController = \Drupal::classResolver(UserController::class);
$userController->logout();
 
Class Resolver dans Drupal 11
Le Class Resolver (class_resolver) est un service qui permet d'instancier des classes en injectant automatiquement leurs dépendances — sans qu'elles soient déclarées comme services dans un .yml.
C'est utile pour instancier à la volée des classes qui implémentent ContainerInjectionInterface.

ContainerInjectionInterface — le prérequis
La classe cible doit implémenter ContainerInjectionInterface pour exposer ses dépendances :
phpuse Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MonHandler implements ContainerInjectionInterface {

  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly LoggerInterface $logger,
  ) {}

  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('logger.channel.monmodule'),
    );
  }

  public function handle(int $nid): void {
    $node = $this->entityTypeManager->getStorage('node')->load($nid);
    $this->logger->notice('Node chargé : @title', ['@title' => $node->getTitle()]);
  }
}

Utilisation du Class Resolver
Procédural
php$handler = \Drupal::service('class_resolver')
  ->getInstanceFromDefinition(MonHandler::class);

$handler->handle(42);
Dans un service (recommandé)
phpuse Drupal\Core\DependencyInjection\ClassResolverInterface;

class MonOrchestrator {

  public function __construct(
    private readonly ClassResolverInterface $classResolver,
  ) {}

  public function executer(string $handlerClass, int $id): void {
    $handler = $this->classResolver->getInstanceFromDefinition($handlerClass);
    $handler->handle($id);
  }
}
monmodule.services.yml :
yamlservices:
  monmodule.orchestrator:
    class: Drupal\monmodule\MonOrchestrator
    arguments:
      - '@class_resolver'

Cas d'usage typiques
1. Sélectionner dynamiquement un handler selon un type
php$handlers = [
  'article'    => ArticleHandler::class,
  'page'       => PageHandler::class,
  'product'    => ProductHandler::class,
];

$type = $node->bundle();

if (isset($handlers[$type])) {
  $handler = $this->classResolver
    ->getInstanceFromDefinition($handlers[$type]);

  $handler->process($node);
}

2. Batch processing — getBatchWorker()
Le Class Resolver est fréquemment utilisé dans les batches Drupal :
php// Dans le callback de batch
function mon_batch_process($items, &$context) {
  $worker = \Drupal::service('class_resolver')
    ->getInstanceFromDefinition(MonBatchWorker::class);

  $worker->process($items, $context);
}
phpclass MonBatchWorker implements ContainerInjectionInterface {

  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly LoggerInterface $logger,
  ) {}

  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('logger.channel.monmodule'),
    );
  }

  public function process(array $items, array &$context): void {
    foreach ($items as $nid) {
      $node = $this->entityTypeManager->getStorage('node')->load($nid);
      // traitement...
      $context['results'][] = $nid;
    }
  }
}

3. Commandes Drush
Les commandes Drush utilisent ce pattern en interne — c'est pourquoi les classes Commands implémentent ContainerInjectionInterface :
phpclass MonModuleCommands extends DrushCommands implements ContainerInjectionInterface {

  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
  ) {
    parent::__construct();
  }

  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  #[CLI\Command(name: 'monmodule:sync')]
  public function sync(): void {
    // ...
  }
}

Différence avec \Drupal::service()
class_resolver\Drupal::service()CibleClasse quelconqueService déclaré en .ymlInstanceNouvelle à chaque appelSingleton (partagé)DépendancesVia create()Via arguments: en ymlUsageHandlers, batches, stratégiesServices applicatifs

En résumé : le Class Resolver est idéal pour le pattern Strategy ou Command où tu veux instancier des handlers interchangeables avec injection de dépendances, sans avoir à déclarer chaque variante comme service dans le conteneur.
