selector = jQuery('#nerdnite-city-selector');
selector.chosen({
        no_results_text: "No city matches what you typed"
}).change(function(evt, result) {
    window.location = 'http://'+result.selected;
});


selector.closest('.Block').css('z-index', '1');