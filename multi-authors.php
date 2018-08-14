<?php
/**
 * Plugin Name: Multi authors plugin
 * Description: A simple plugin for adding multiple authors to a post
 * Version: 0.0
 * Author: Magdalena Korpela
 * Author URI: https://github.com/magosb
 * License: MIT
 * 
 * @package WordPress
 * @author  Magdalena Korpela
 * @version 1.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MULTI_AUTHORS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-multi-authors-activator.php
 */
function activate_multi_authors() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-multi-authors-activator.php';
	Multi_Authors_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-multi-authors-deactivator.php
 */
function deactivate_multi_authors() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-multi-authors-deactivator.php';
	Multi_Authors_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_multi_authors' );
register_deactivation_hook( __FILE__, 'deactivate_multi_authors' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-multi-authors.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_multi_authors() {

	$plugin = new Multi_Authors();
	$plugin->run();

}
run_multi_authors();

add_action( 'add_meta_boxes', 'cd_meta_box_add' );
function cd_meta_box_add()
{
    add_meta_box( 'my-meta-box-id', 'My First Meta Box', 'cd_meta_box_cb', 'post', 'normal', 'high' );
}

function cd_meta_box_cb()
{
    // $post is already set, and contains an object: the WordPress post
    global $post;
    $values = get_post_custom( $post->ID );
    $text = isset( $values['my_meta_box_text'] ) ? $values['my_meta_box_text'] : '';
    $selected = isset( $values['my_meta_box_select'] ) ? esc_attr( $values['my_meta_box_select'] ) : '';
    $check = isset( $values['my_meta_box_check'] ) ? esc_attr( $values['my_meta_box_check'] ) : '';
     
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
    ?>
    <p>
        <label for="my_meta_box_text">Text Label</label>
        <input type="text" name="my_meta_box_text" id="my_meta_box_text" value="<?php echo $text; ?>" />
    </p>
     
    <p>
        <label for="my_meta_box_select">Color</label>
        <select name="my_meta_box_select" id="my_meta_box_select">
            <option value="red" <?php selected( $selected, 'red' ); ?>>Red</option>
            <option value="blue" <?php selected( $selected, 'blue' ); ?>>Blue</option>
        </select>
    </p>
     
    <p>
        <input type="checkbox" id="my_meta_box_check" name="my_meta_box_check" <?php checked( $check, 'on' ); ?> />
        <label for="my_meta_box_check">Do not check this</label>
    </p>
    <?php    
}

add_action( 'save_post', 'cd_meta_box_save' );
function cd_meta_box_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
     
    // now we can actually save the data
    $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
    if( isset( $_POST['my_meta_box_text'] ) )
        update_post_meta( $post_id, 'my_meta_box_text', wp_kses( $_POST['my_meta_box_text'], $allowed ) );
         
    if( isset( $_POST['my_meta_box_select'] ) )
        update_post_meta( $post_id, 'my_meta_box_select', esc_attr( $_POST['my_meta_box_select'] ) );
         
    // This is purely my personal preference for saving check-boxes
    $chk = isset( $_POST['my_meta_box_check'] ) && $_POST['my_meta_box_select'] ? 'on' : 'off';
    update_post_meta( $post_id, 'my_meta_box_check', $chk );
}
