jQuery(document).ready(function ($) {
     // Store all authors at load
     let allAuthors = [];
     $('#wab-filter-author option').each(function () {
          if ($(this).val() !== "") {
               allAuthors.push({ val: $(this).val(), text: $(this).text() });
          }
     });

     // Filter author dropdown by selected letter
     $('#wab-filter-author-letter').on('change', function () {
          let selectedLetter = $(this).val();
          let $authorSelect = $('#wab-filter-author');
          $authorSelect.find('option:not(:first)').remove(); // Keep 'All'
          let filtered = allAuthors;
          if (selectedLetter) {
               filtered = allAuthors.filter(a => a.text.charAt(0).toUpperCase() === selectedLetter);
          }
          $.each(filtered, function (i, a) {
               $authorSelect.append($('<option>', { value: a.val, text: a.text }));
          });
          // Reset author selection and trigger filter
          $authorSelect.val('');
          fetchBooks(1);
     });

     // Filter books when author or other filters change
     $('#wab-filter-author, #wab-filter-price, #wab-filter-sort').on('change', function () {
          fetchBooks(1);
     });

     // Loader
     function wabShowLoader(show) {
          if (show) {
               $('.wab-loader').show();
          } else {
               $('.wab-loader').hide();
          }
     }

     function fetchBooks(page = 1) {
          wabShowLoader(true);
          var data = {
               action: 'wab_filter_books',
               nonce: WAB_Books.nonce,
               author: $('#wab-filter-author').val(),
               author_letter: $('#wab-filter-author-letter').val(),
               price_range: $('#wab-filter-price').val(),
               sort_by: $('#wab-filter-sort').val(),
               page: page
          };

          $.post(WAB_Books.ajax_url, data, function (response) {
               $('#wab-books-list').html('<div class="wab-loader" style="display:none;"><div class="wab-spinner"></div></div>' + response);
               wabShowLoader(false);
          });
     }

     // Pagination click
     $(document).on('click', '.wab-books-page', function (e) {
          e.preventDefault();
          var page = $(this).data('page');
          fetchBooks(page);
     });
});