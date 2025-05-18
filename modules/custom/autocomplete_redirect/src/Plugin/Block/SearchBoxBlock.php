<?php

namespace Drupal\autocomplete_redirect\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides the Search Box Block.
 *
 * This block integrates with the AutoComplete Redirect Form to enhance 
 * the search functionality by providing autocomplete suggestions.
 *
 * @Block(
 *   id = "search_box_block",
 *   admin_label = @Translation("Search Box Block"),
 *   category = @Translation("Custom")
 * )
 */
class SearchBoxBlock extends BlockBase {

  /**
   * Builds and returns the render array for the block.
   *
   * @return array
   *   A render array containing the autocomplete form.
   */
  public function build() {
    // Generate the form using Drupal's FormBuilder service.
    $build['form'] = \Drupal::formBuilder()->getForm('\Drupal\autocomplete_redirect\Form\AutoCompleteRedirectForm');
    
    return $build;
  }

}
