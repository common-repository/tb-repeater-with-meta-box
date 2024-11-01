<?php
 /**
 *
 *
 * @link              https://wordpress.org/plugins/tb-repeater-with-meta-box/
 * @since             1.0
 * @package           TbRepeater
 *
 * @wordpress-plugin
 * Plugin Name:       TB Repeater With Meta Box
 * Plugin URI:        https://wordpress.org/plugins/tb-repeater-with-meta-box/
 * Description:       To add custom repeater in Post and Pages
 * Version:           1.0
 * Author:            vishitshah
 * Author URI:        https://www.vishitshah.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tb-repeater-meta-box
 * Domain Path:       /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add meta box
add_action( 'add_meta_boxes', 'tb_repeater_meta_box' );
function tb_repeater_meta_box() {
    $show_post_types = get_post_types();
    $post_types = array( 'post', 'page' );
    foreach ( $post_types as $post_type ) {
        add_meta_box(
            'tb_repeater_meta_box',
            'Custom Repeater Meta Box',
            'tb_repeater_meta_box_callback',
            $post_type,
            'normal',
            'high'
        );
    }
}

// Meta box callback function
function tb_repeater_meta_box_callback( $post ) {
    // Get existing repeater field value
    $tb_repeater_custom_data = get_post_meta( $post->ID, '_tb_repeater_data', true );

    // Include necessary JavaScript for repeater field
    wp_enqueue_script( 'tb-repeater-scripts', plugins_url( 'js/tb-repeater-scripts.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ), '1.0', true );

    // Display the repeater field
    ?>
    <div class="custom-repeater-container">
        <div class="custom-repeater-fields">
            <?php if ( ! empty( $tb_repeater_custom_data ) ) { ?>
                <?php foreach ( $tb_repeater_custom_data as $i => $row ) { ?>
                    <div class="custom-repeater-row">
                        <label for="custom_text_<?php echo esc_attr( $i ); ?>"><?php echo esc_html__( 'Text', 'tb-repeater-meta-box' ); ?></label>
                        <input class="input-box" type="text" name="tb_repeater_custom_data[text][]" value="<?php echo isset( $row['text'] ) ? esc_attr( $row['text'] ) : ''; ?>" id="custom_text_<?php echo esc_attr( $i ) ; ?>" />
                        <label for="custom_editor_<?php echo esc_attr( $i ) ; ?>"><?php echo esc_html__( 'Editor', 'tb-repeater-meta-box' ); ?></label>
                        <?php
                            $editor_content = isset( $row['editor'] ) ? $row['editor'] : '';
                            wp_editor( $editor_content, 'custom_editor_' . esc_attr( $i ), array( 'textarea_name' => 'tb_repeater_custom_data[editor][]' ) );
                        ?>
                        <label for="custom_image_<?php echo esc_attr( $i ) ; ?>"><?php echo esc_html__( 'Image', 'tb-repeater-meta-box' ); ?></label>
                        <input type="text" name="tb_repeater_custom_data[image][]" class="custom-image-field input-box" value="<?php echo isset( $row['image'] ) ? esc_url( $row['image'] ) : ''; ?>" id="custom_image_<?php echo esc_attr( $i ) ; ?>" />
                        <button class="upload-image-button button"><?php echo esc_html__( 'Upload Image', 'tb-repeater-meta-box' ); ?></button>
                        <img class="custom-image-preview" src="<?php echo isset( $row['image'] ) ? esc_url( $row['image'] ) : ''; ?>" alt="Image Preview" />
                        <button class="remove-image-button button"><?php echo esc_html__( 'Remove Image', 'tb-repeater-meta-box' ); ?></button>

                        <button class="remove-row-button button"><?php echo esc_html__( 'Remove Row', 'tb-repeater-meta-box' ); ?></button>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="custom-repeater-row">
                    <label for="custom_text_0">Text:</label>
                    <input class="input-box" type="text" name="tb_repeater_custom_data[text][]" value="" id="custom_text_0" />

                    <label for="custom_editor_0">Editor:</label>
                    <?php
                        $editor_content = '';
                        wp_editor( $editor_content, 'custom_editor_0', array( 'textarea_name' => 'tb_repeater_custom_data[editor][]' ) );
                    ?>
                    <label for="custom_image_0"><?php echo esc_html__( 'Image', 'tb-repeater-meta-box' ); ?></label>
                    <input type="text" name="tb_repeater_custom_data[image][]" class="custom-image-field input-box" value="" id="custom_image_0" />
                    <button class="upload-image-button button"><?php echo esc_html__( 'Upload Image', 'tb-repeater-meta-box' ); ?></button>
                    <img class="custom-image-preview" src="" alt="Image Preview" />
                    <button class="remove-image-button button"><?php echo esc_html__( 'Remove Image', 'tb-repeater-meta-box' ); ?></button>

                    <button class="remove-row-button button"><?php echo esc_html__( 'Remove Row', 'tb-repeater-meta-box' ); ?></button>
                </div>
            <?php } ?>
        </div>
        <button class="add-row-button button"><?php echo esc_html__( 'Add Row', 'tb-repeater-meta-box' ); ?></button>
    </div>
    <?php
}

// Save custom repeater field data
add_action( 'save_post', 'tb_repeater_save_data' );
function tb_repeater_save_data( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
        return;
    }

    // Verify nonce
    // if ( ! isset( $_POST['tb_repeater_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tb_repeater_nonce_field'] ) ), 'tb_repeater_nonce' ) ) {
    //     wp_die( 'Invalid nonce' ); // Handle non-verified nonce or missing nonce securely
    //     exit;
    // }

    if ( isset( $_POST['tb_repeater_custom_data'] ) ) {
        $tb_repeater_custom_data = array();
        foreach ( $_POST['tb_repeater_custom_data'] as $key => $value ) {
            foreach ( $value as $index => $field_value ) {
                $tb_repeater_custom_data[$index][$key] = sanitize_text_field( $field_value );
            }
        }
        update_post_meta( $post_id, '_tb_repeater_data', $tb_repeater_custom_data );
    }
}

// Enqueue scripts and styles for media uploader
add_action( 'admin_enqueue_scripts', 'tb_repeater_enqueue_media_scripts');
function tb_repeater_enqueue_media_scripts( $hook ) {
    if ( 'post.php' == $hook || 'post-new.php' == $hook ) {
        wp_enqueue_media();
        wp_enqueue_script( 'tb-repeater-media', plugins_url( 'js/tb-repeater-media.js', __FILE__), array( 'jquery' ), '1.0', true );
        wp_enqueue_style( 'tb-repeater-css', plugins_url( 'css/tb-repeater.css', __FILE__), array(), '1.0.0' );
    }
}

function tb_repeater_front_data( $post_id ) {
    // Get the custom repeater data
    $tb_repeater_custom_data = get_post_meta( $post_id, '_tb_repeater_data', true );
    
    return $tb_repeater_custom_data;
}