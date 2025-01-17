jQuery(document).ready(function ($) {
    const popup = $('#custom-popup');
    const closePopup = $('.close-popup');
    const triggerButton = $('#popup-trigger');

    // Auto popup
    if (triggerButton.length === 0) {
        popup.fadeIn();
    }

    // On-click popup
    triggerButton.on('click', function () {
        popup.fadeIn();
    });

    // Close popup
    closePopup.on('click', function () {
        popup.fadeOut();
    });

    // Close when clicking outside the popup
    $(document).on('click', function (e) {
        if ($(e.target).is('#custom-popup')) {
            popup.fadeOut();
        }
    });
});
