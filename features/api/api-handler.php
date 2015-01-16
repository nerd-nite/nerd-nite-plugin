<?php
    header( 'Content-Type: application/json' );

    $post = get_queried_object();
    echo json_encode( $post );
?>