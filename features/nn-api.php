<?php

function makeplugins_add_json_endpoint() {
    add_rewrite_endpoint( "nn-api", EP_PERMALINK | EP_PAGES );
}
add_action( "init", "makeplugins_add_json_endpoint" );



function makeplugins_json_template_redirect() {
    global $wp_query;

    // if this is not a request for json or a singular object then bail
    if ( ! isset( $wp_query->query_vars["nn-api"] ) || ! is_singular() ) {
        return;
    }

    // include custom template
    include dirname( __FILE__ ) . "api/api-handler.php";
    exit;
}
add_action( "template_redirect", "makeplugins_json_template_redirect" );

?>