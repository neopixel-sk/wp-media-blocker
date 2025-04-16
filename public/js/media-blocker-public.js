(function( $ ) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     */

    $(function() {
        // Public JavaScript code will go here
        $('.media-blocker-container').on('click', '.media-blocker-overlay', function(e) {
            e.preventDefault();
            $(this).fadeOut();
        });
    });

})( jQuery );