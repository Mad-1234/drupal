<?php

namespace Drupal\autocomplete_redirect\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides an autocomplete search form with redirection functionality.
 *
 * This form enhances search functionality by providing autocomplete
 * suggestions and redirecting users based on their input.
 */
class AutoCompleteRedirectForm extends FormBase {

  /**
   * {@inheritdoc}
   *
   * Returns the unique ID of the form.
   *
   * @return string
   *   The form ID used to identify the form.
   */
  public function getFormId() {
    return 'autocomplete_redirect_form';
  }

  /**
   * Builds the form structure.
   *
   * @param array $form
   *   The form definition array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   A renderable form array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Define the search field with autocomplete disabled.
    $form['search_field'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'autocomplete' => 'off',
        'placeholder' => $this->t('Type to search...'),
        'id' => 'redirect-search-field',
      ],
    ];

    // Add suffix div for autocomplete results.
    $form['search_field']['#suffix'] = '<div id="autocomplete-results"></div>';

    // Attach the autocomplete library for frontend functionality.
    $form['#attached']['library'][] = 'autocomplete_redirect/auto_complete_redirect';

    // Define a hidden submit button.
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#attributes' => ['style' => 'display: none;'],
    ];

    return $form;
  }

  /**
   * Handles form submission.
   *
   * Redirects the user to the search results page using the entered search term.
   *
   * @param array $form
   *   The form structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $base_url = \Drupal::request()->getBaseUrl();
    $search_query = urlencode($form_state->getValue('search_field'));

    // Perform redirection to the search results page.
    $response = new RedirectResponse("{$base_url}/search/node/?keys={$search_query}");
    $response->send();
  }

}
