// https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormBuilderInterface.php/interface/FormBuilderInterface/10

// routing.yml
web.form:
  path: '/web/form'
  defaults:
    _title: 'Example'
    _form: 'Drupal\web\Form\ExampleForm'
  requirements:
    _permission: 'access content'

web.form.controller.a:
  path: '/web/form/controller/a'
  defaults:
    _title: 'Example'
    _controller: '\Drupal\web\Controller\WebController::a'
  requirements:
    _permission: 'access content'

web.form.controller.b:
  path: '/web/form/controller/b'
  defaults:
    _title: 'Example'
    _controller: '\Drupal\web\Controller\WebController::b'
  requirements:
    _permission: 'access content'

web.form.controller.c:
  path: '/web/form/controller/c'
  defaults:
    _title: 'Example'
    _controller: '\Drupal\web\Controller\WebController::c'
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
  public function buildForm(array $form, FormStateInterface $form_state, $message = NULL) {

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#value' => $message
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

//
<?php

function web_theme($existing, $type, $theme, $path) {
  return [
    'web_page_b' => [
      'variables' => [
        'example_form' => '',
      ],
    ],
    'web_page_c' => [
      'variables' => [
        'example_form' => '',
      ],
    ],
  ];
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
  public function a() {
    $myForm = \Drupal::formBuilder()->getForm('Drupal\web\Form\ExampleForm');
    // Pass argument to build form
    $myForm = \Drupal::formBuilder()->getForm('Drupal\web\Form\ExampleForm', 'Lorem ipsum. Lorem Ipsum.');
    $renderer = \Drupal::service('renderer');
    $myFormHtml = $renderer->render($myForm);

    $build['content'] = [
      '#items',
      '#markup' => $myFormHtml
    ];
    return $build;
  }

  public function b() {
    return [
      '#theme' => 'web_page_b',
      '#example_form' => \Drupal::formBuilder()->getForm('Drupal\web\Form\ExampleForm'),
    ];
  }

  public function c() {
    return [
      '#theme' => 'web_page_c',
      '#example_form' => \Drupal::formBuilder()->getForm('Drupal\web\Form\ExampleForm'),
    ];
  }

}

// web-page-b.html.twig
<div>
  {{ example_form }}
</div>

// web-page-c.html.twig
<form{{ attributes }}>
    {{ example_form.messages }}
    {{ example_form.message }}
    {{ example_form.form_token }}
    {{ example_form.form_build_id }}
    {{ example_form.form_id }}
    {{ example_form.actions.submit }}
</form>
