import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import './styles/app.css';

$.LoadingOverlaySetup({
    background: "rgba(230, 230, 230, 0.8)", 
    image: '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 48 48"><path fill="currentColor" d="M13.5 4a9.5 9.5 0 1 0 0 19h8.25c.69 0 1.25-.56 1.25-1.25V13.5A9.5 9.5 0 0 0 13.5 4m-.025 40C8.26 44 4 39.774 4 34.55a9.45 9.45 0 0 1 9.45-9.45h8.3c.69 0 1.25.56 1.25 1.25v8.15c0 5.252-4.283 9.5-9.525 9.5M34.5 4a9.5 9.5 0 0 1 0 19h-8.25c-.69 0-1.25-.56-1.25-1.25V13.5A9.5 9.5 0 0 1 34.5 4m0 40a9.5 9.5 0 0 0 0-19h-8.25c-.69 0-1.25.56-1.25 1.25v8.25a9.5 9.5 0 0 0 9.5 9.5"/></svg>',
    imageColor: "#13795b",
    imageAnimation: true,
    imageAutoResize: true,
    imageResizeFactor: 1
});

$('a[data-loading="true"],button[data-loading="true"]').each(function () {
    $(this).click(function () {
        $.LoadingOverlay('show');

        if ($(this).data('loading')) {
            setTimeout(function () {
                $.LoadingOverlay("hide");
            }, 5000);
        }
    });
});

$('form[data-loading="true"]').each(function () {
    $(this).on('submit', function () {
        $.LoadingOverlay('show');
        
        if ($(this).data('loading')) {
            setTimeout(function () {
                $.LoadingOverlay("hide");
            }, 5000);
        }
    });
});