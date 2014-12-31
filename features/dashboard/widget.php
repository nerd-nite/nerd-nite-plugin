<?php
    $details = get_blog_details();
// Need to call this in the context of the main site to figure out 'city bosses"
    switch_to_blog(1);
    $users   = get_users(array('blog_id' => $details->blog_id));
    restore_current_blog();

    $location = get_option('city_location');
    $location =  str_replace(array('lat:','lng:'), array('"lat":', '"lng":'), $location);
    if(json_decode($location) == NULL ) {
        $location = false;
        delete_option('city_location');
	}

?>
<div id="nn-city-details-dashboard">
    Details about your city:
    <table>
        <tr><th>Name</th><td><?php echo $details->blogname?></td></tr>
        <tr><th>Bosses</th><td>
            <ul id="nn-city-details-dashboard-bosses">
                <?php
                foreach ($users as $userDetails) {
                    if(in_array("city_boss", $userDetails->roles)) {
                        echo "<li>$userDetails->display_name ($userDetails->user_login)</li>";
                    }
                }
                ?>
            </ul>
        </td></tr>
        <tr>
            <th>Location</th>
            <td><?php if($location) {
                    echo "Location is set: $location";
                } else {
                    echo 'Location is not currently set; please update this.';
                    $location = '{ lat:0, lng: 0 }';
                }

                echo "<script>cityLocation = $location;  dashboardConfig=false;</script>"

                ?>
            </td>
        </tr>
        <tr><td colspan="2"><div id="nn-city-map-canvas"></div></td></tr>
    </table>
</div>
