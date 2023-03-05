// https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Flood%21FloodInterface.php/interface/FloodInterface/10

<?php

// Flood sert a controler le nombres de fois qu'un évenement est lancé comme soumettre un formulaire et pouvoir le bloquer à partir d'un certain nombre de fois
\Drupal::flood()->register('mon_module.flood_soumission_formulaire');
// Possibilité de spécifier pour l'évenement le temps d'expiration par default 3600 secondes et un identifiant pour reconnaitre l'utilisateur de l'évenement par default l'adresse IP
\Drupal::flood()->register('mon_module.flood_soumission_formulaire', 3600, $floodIdentifier);

// Vérifier si l'utilisateur a dépassé le nombres d'appel à l'évenement ici 10
\Drupal::flood()->isAllowed('mon_module.flood_soumission_formulaire', 10);
// Possibilité de spécifier pour l'évenement le temps d'expiration par default 3600 secondes et un identifiant pour reconnaitre l'utilisateur de l'évenement par default l'adresse IP
\Drupal::flood()->isAllowed('mon_module.flood_soumission_formulaire', 10, 3600, $floodIdentifier);

// Reset les nombres d'appel à l'évenement
\Drupal::flood()->clear('mon_module.flood_soumission_formulaire', $floodIdentifier);

// Delete expired flood
\Drupal::flood()->garbageCollection();
?>

<?php

namespace Drupal\web\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Flood\FloodInterface;

/**
 * Provides a Web form.
 */
class ExampleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'web_example';
  }

  /**
   * The flood service.
   *
   * @var \Drupal\Core\Flood\FloodInterface
   */
  protected $flood;

  /**
   * FloodProtectedForm constructor.
   *
   * @param \Drupal\Core\Flood\FloodInterface $flood
   *   The flood service.
   */
  public function __construct(FloodInterface $flood)
  {
    $this->flood = $flood;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('flood')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (mb_strlen($form_state->getValue('message')) < 10) {
      $form_state->setErrorByName('message', $this->t('Message should be at least 10 characters.'));
    }
    if (!$this->flood->isAllowed('web.flood_protected_form', 5, 3600, 15121984)) {
      //$this->flood->clear('web.flood_protected_form', 15121984);
      $form_state->setErrorByName('url', $this->t('Too many uses of this form from your IP address. This IP address is temporarily blocked. Try again later.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t('The message has been sent.'));

    $this->flood->register('web.flood_protected_form', 3600, 15121984);
  }

}
