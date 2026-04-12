https://api.drupal.org/api/drupal/core%21lib%21Drupal.php/function/Drupal%3A%3Adestination/10
<?php

// Le service redirect.destination gère le paramètre destination dans les URLs.
// Il permet de rediriger l'utilisateur vers une page précise après une action (login, submit de formulaire, suppression, etc.).

// Procédural
$destination = \Drupal::service('redirect.destination');

// Dans un service (recommandé)
// Load by storage in service/class
// Add in service.yml
arguments: ['@redirect.destination']

use Drupal\Core\Routing\RedirectDestinationInterface;

class MonService {
  public function __construct(
    private readonly RedirectDestinationInterface $redirectDestination,
  ) {}
}

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

$destination = \Drupal::service('redirect.destination');

// Retourne le chemin de destination sous forme de tableau ['destination' => '/chemin']
$destination->getAsArray();
// → ['destination' => '/node/42']

// Retourne uniquement le chemin en string
$destination->get();
// → '/node/42'

// Forcer une destination custom
$destination->set('/mon/chemin/custom');

// Cas d'usage 1 — Ajouter destination à un lien
use Drupal\Core\Url;

// Lien vers le formulaire de login avec retour sur la page courante
$url = Url::fromRoute('user.login', [], [
  'query' => $this->redirectDestination->getAsArray(),
]);

// Lien d'action avec destination
$url = Url::fromRoute('monmodule.edit', ['node' => $nid], [
  'query' => [
    'destination' => \Drupal::service('redirect.destination')->get(),
  ],
]);

// Cas d'usage 2 — Rediriger après submit de formulaire
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RedirectDestinationInterface;

class MonFormulaire extends FormBase {

  public function __construct(
    private readonly RedirectDestinationInterface $redirectDestination,
  ) {}

  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('redirect.destination'),
    );
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // Traitement...

    // Rediriger vers la destination si présente, sinon page par défaut
    $destination = $this->redirectDestination->get();

    if ($destination) {
      $form_state->setRedirectUrl(
        Url::fromUserInput($destination)
      );
    }
    else {
      $form_state->setRedirect('monmodule.liste');
    }
  }
}

// Cas d'usage 3 — Dans un Controller
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MonController extends ControllerBase {

  public function action(int $nid): RedirectResponse {
    // Traitement...

    $destination = \Drupal::service('redirect.destination')->get();

    // Rediriger vers destination ou fallback
    $url = $destination
      ? Url::fromUserInput($destination)->toString()
      : Url::fromRoute('monmodule.liste')->toString();

    return new RedirectResponse($url);
  }
}

// Cas d'usage 4 — Dans les opérations d'entité
function monmodule_entity_operation(EntityInterface $entity): array {
  $destination = \Drupal::service('redirect.destination');

  return [
    'archiver' => [
      'title'  => t('Archiver'),
      'url'    => Url::fromRoute('monmodule.archiver', [
        'entity_type' => $entity->getEntityTypeId(),
        'entity_id'   => $entity->id(),
      ]),
      'weight' => 50,
      'query'  => $destination->getAsArray(),  // ← retour automatique
    ],
  ];
}

// Cas d'usage 5 — Combiné avec CSRF token
use Drupal\Core\Url;

// Pattern complet : action sécurisée + retour sur page courante
$url = Url::fromRoute('monmodule.delete', ['node' => $nid]);

$url->setOption('query', array_merge(
  ['token' => \Drupal::service('csrf_token')->get($url->getInternalPath())],
  \Drupal::service('redirect.destination')->getAsArray(),
));

// Cas 5 Gérer la destination dans la route
# monmodule.routing.yml
monmodule.delete:
  path: '/monmodule/delete/{node}'
  defaults:
    _controller: '\Drupal\monmodule\Controller\MonController::delete'
  requirements:
    _permission: 'delete any article content'
    _csrf_token: 'TRUE'

public function delete(int $node): RedirectResponse {
  $storage = $this->entityTypeManager()->getStorage('node');
  $node_entity = $storage->load($node);
  $node_entity->delete();

  $this->messenger()->addStatus($this->t('Node supprimé.'));

  // Récupérer la destination depuis la requête
  $destination = \Drupal::service('redirect.destination')->get();

  return new RedirectResponse(
    $destination ?: Url::fromRoute('<front>')->toString()
  );
}

// Cas 6 Sécurité — valider la destination
use Drupal\Component\Utility\UrlHelper;

$destination = \Drupal::service('redirect.destination')->get();

// Vérifier que la destination est bien une URL interne
if ($destination && UrlHelper::isExternal($destination)) {
  // Destination externe → fallback sécurisé
  $destination = Url::fromRoute('<front>')->toString();
}

return new RedirectResponse($destination);
