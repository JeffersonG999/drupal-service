// https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormBuilderInterface.php/interface/FormBuilderInterface/10

// routing.yml
example.form:
  path: '/example/form'
  defaults:
    _title: 'Example'
    _form: 'Drupal\web\Form\ExampleForm'
  requirements:
    _permission: 'access content'

example.form:
  path: '/example/form'
  defaults:
    _title: 'Example'
    _controller: '\Drupal\web\Controller\ExampleController::build'
  requirements:
    _permission: 'access content'

// ExampleForm.php
<?php

namespace Drupal\web\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

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
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
  }

}

// ExampleController.php

<?php

namespace Drupal\web\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Web routes.
 */
class WebController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => \Drupal::formBuilder()->getForm('Drupal\mzo_bridgeman\Form\ExtractForm'),,
    ];

    return $build;
  }

}
