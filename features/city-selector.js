jQuery('#nerdnite-city-selector').selectator({
    prefix: 'selectator_',             // CSS class prefix
    height: 'auto',                    // auto or element
    useDimmer: true,                  // dims the screen when option list is visible
    useSearch: true,                   // if false, the search boxes are removed and
                                       //   `showAllOptionsOnFocus` is forced to true
    keepOpen: false,                   // if true, then the dropdown will not close when
                                       //   selecting options, but stay open until losing focus
    showAllOptionsOnFocus: false,      // shows all options if input box is empty
    selectFirstOptionOnSearch: true,   // selects the topmost option on every search
    labels: {
        search: 'Find city...'            // Placeholder text in search box in single select box
    }
});