<?php

namespace Drupal\autocomplete_redirect\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides an autocomplete search functionality.
 *
 * This controller handles autocomplete requests for search queries 
 * and returns relevant node titles based on the user's input.
 */
class AutocompleteController extends ControllerBase {

  /**
   * Handles autocomplete requests.
   *
   * Fetches search results based on the provided query string and returns 
   * matching nodes with their respective search links.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object containing the query parameter.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JSON response containing the search matches.
   */
  public function handleAutocomplete(Request $request) {
    // Retrieve the search query from request.
    $string = $request->query->get('q');
    
    // If query is empty, return an empty JSON response.
    if (empty($string)) {
      return new JsonResponse([]);
    }

    $matches = [];

    // Entity query to search nodes based on title, tags, author, and body content.
    $query = \Drupal::entityQuery('node');
    $orQuery = $query->orConditionGroup()
      ->condition('title', $string, 'CONTAINS')
      ->condition('field_tags.entity.name', $string, 'CONTAINS')
      ->condition('uid.entity.name', $string, 'CONTAINS')
      ->condition('body.value', $string, 'CONTAINS');

    // Apply conditions to filter published nodes.
    $query->condition($orQuery);
    $query->condition('status', 1);
    $query->accessCheck(TRUE);
    $query->range(0, 10);
    
    // Execute the query to get matching node IDs.
    $nids = $query->execute();

    $base_url = \Drupal::request()->getBaseUrl();

    // Load nodes and prepare results.
    foreach (Node::loadMultiple($nids) as $node) {
      $path = "{$base_url}/search/node/?keys=" . urlencode($node->getTitle());
      $html = "<a href='{$path}'>{$node->getTitle()}</a>";

      $matches[] = [
        'value' => $node->id(),
        'label' => $node->getTitle(),
        'path' => $html,
      ];
    }

    // Return the matches as a JSON response.
    return new JsonResponse($matches);
  }

}
