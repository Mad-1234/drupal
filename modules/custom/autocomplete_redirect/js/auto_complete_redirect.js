(function (Drupal, drupalSettings) {
  'use strict';

  /**
   * Provides autocomplete functionality for the search field.
   *
   * This behavior attaches to the 'redirect-search-field' input element and
   * fetches autocomplete suggestions from a Drupal endpoint as the user types.
   */
  Drupal.behaviors.autocompleteRedirect = {
    attach: function (context, settings) {
      // Ensure this behavior only runs once per element.
      const searchField = context.querySelector('#redirect-search-field');
      if (searchField && !searchField.classList.contains('autocomplete-processed')) {
        searchField.classList.add('autocomplete-processed');

        searchField.addEventListener('input', function () {
          const query = this.value;
          if (query.length > 2) { // Start searching after 2 characters.
            const absoluteUrl = drupalSettings.path.baseUrl + '/auto-complete-callback?q=' + query;
            fetch(absoluteUrl)
              .then(response => response.json())
              .then(data => {
                const resultsContainer = context.querySelector('#autocomplete-results');
                if (resultsContainer) { // Check if the container exists.
                  resultsContainer.innerHTML = ''; // Clear previous results.
                  data.forEach(item => {
                    const div = document.createElement('div');
                    div.innerHTML = item.path;
                    resultsContainer.appendChild(div);
                  });
                }
              });
          }
        });
      }
    }
  };
})(Drupal, drupalSettings);
