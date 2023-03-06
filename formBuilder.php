// https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormBuilderInterface.php/interface/FormBuilderInterface/10

// routing.yml
example.form:
  path: '/example/form'
  defaults:
    _title: 'Example'
    _form: 'Drupal\example\Form\ExampleForm'
  requirements:
    _permission: 'access content'

example.form:
  path: '/example/form'
  defaults:
    _title: 'Example'
    _controller: '\Drupal\example\Controller\ExampleController::build'
  requirements:
    _permission: 'access content'

// ExampleForm.php
<?php

namespace Drupal\example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ExampleForm extends FormBase {

  public function getFormId() {
    return 'web_example';
  }

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

  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (mb_strlen($form_state->getValue('message')) < 10) {
      $form_state->setErrorByName('message', $this->t('Message should be at least 10 characters.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
  }

}

//
<?php

function example_theme($existing, $type, $theme, $path) {
  return [
    'example_page' => [
      'variables' => [
        'example_form' => '',
      ],
    ],
  ];
}


// ExampleController.php
<?php

namespace Drupal\example\Controller;

use Drupal\Core\Controller\ControllerBase;

class ExampleController extends ControllerBase {

  public function build() {
    return [
      '#theme' => 'example_page',
      '#example_form' => \Drupal::formBuilder()->getForm('Drupal\example\Form\ExtractForm'),
    ];
  }

}

// example-page.html.twig
<div>
  {{ example_form }}
</div>
