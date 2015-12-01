<?php
/**
 * Created by PhpStorm.
 * User: danrumney
 * Date: 5/23/15
 * Time: 10:55 AM
 */

global $nnbp_db_version;
$nnbp_db_version = '1.2';

function get_gravatar_hash($email) {
    return md5(strtolower(trim($email)));
}


function get_gravatar_url($email, $size = null) {
    $url = "http://www.gravatar.com/avatar/".get_gravatar_hash($email);
    if($size) {
        $url .= "?s=".$size;
    }
    return $url;
}

function pics_install () {
    global $wpdb;
    global $nnbp_db_version;

    $table_name = $wpdb->base_prefix . "nn_boss_pics";

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      bossId bigint(20) NOT NULL,
      url varchar(1024) DEFAULT '' NOT NULL,
      UNIQUE KEY id (id)
    ) $charset_collate;";


    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( 'nnbp_db_version', $nnbp_db_version );
}

register_activation_hook( __FILE__, 'pics_install' );

if ( get_site_option( 'nnbp_db_version' ) != $nnbp_db_version ) {
    pics_install();
}


/**
 * User Form
 */

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

/**
 * @param $user
 */
function my_show_extra_profile_fields( $user ) { ?>

    <h3>Nerd Pic</h3>

    <table class="form-table">
        <tr>
            <th>
                <label for="nn-boss-use-gravatar">Use Gravatar?</label>
            </th>
            <td>
                <input type="checkbox" id="nn-boss-use-gravatar" name="nn-boss-use-gravatar"
                       <?php if(get_user_meta($user->ID, 'nn-use-gravatar', true)) { echo "checked"; } ?>
                    />
            </td>
        </tr>

        <tr >
            <th><label for="nn-boss-file">A picture of you</label></th>

            <td>
                <div class="nn-boss-gravatar-preview" class="hidden">
                    <img id="nn-boss-gravatar-preview-img"  src="<?php echo get_gravatar_url($user->get("user_email"), 300) ?>" />
                </div>
                <div class="nn-boss-manual-preview" class="hidden">
                    <img id="nn-boss-manual-preview-img"/>
                </div>
                <input type="hidden" name="nn-boss-crop-data" id="nn-boss-crop-data" />
            </td>
        </tr>
        <tr class="nn-boss-manual-controls">
            <td>Preview</td>
            <td>
                <input type="file" name="nn-boss-file" id="nn-boss-file" accept="image/*"/><br />
            </td>
        </tr>

    </table>
<?php }

add_action('admin_init', 'load_cropper');

function load_cropper() {
    wp_deregister_script('jquery');
    wp_register_script('jquery', ("//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"), false, '2.1.4');

    wp_register_script('yc-cropper', plugins_url('/cropper.min.js', __FILE__), array('jquery'));
    wp_register_script('CropAvatar', plugins_url('/CropAvatar.js', __FILE__), array('yc-cropper'));
    wp_register_script('boss-pics', plugins_url('/boss-pics.js', __FILE__), array('yc-cropper', 'CropAvatar'));

    wp_register_style('yc-cropper', plugins_url('/cropper.min.css', __FILE__), array(), '1.05');
    wp_register_style('boss-pics', plugins_url('/boss-pic.css', __FILE__), array('yc-cropper'), '1.0.0');


    wp_enqueue_script('boss-pics');
    wp_enqueue_style('boss-pics');
}



?>