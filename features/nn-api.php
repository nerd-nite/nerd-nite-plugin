<?php

function makeplugins_add_json_endpoint() {
    add_rewrite_endpoint( "nn-api", EP_ALL );
}
add_action( "init", "makeplugins_add_json_endpoint" );



function makeplugins_json_template_redirect() {
    global $wp_query;

    // if this is not a request for json or a singular object then bail
    if ( ! isset( $wp_query->query_vars["nn-api"] ) ) {
        return;
    }

    // include custom template
    include dirname( __FILE__ ) . "/api/api-handler.php";
    exit;
}
add_action( "template_redirect", "makeplugins_json_template_redirect" );


function makeplugins_endpoints_activate() {
    // ensure our endpoint is added before flushing rewrite rules
    makeplugins_add_json_endpoint();
    // flush rewrite rules - only do this on activation as anything more frequent is bad!
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'makeplugins_endpoints_activate' );

function makeplugins_endpoints_deactivate() {
    // flush rules on deactivate as well so they're not left hanging around uselessly
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'makeplugins_endpoints_deactivate' );

?>