<?php
/*
Plugin Name: Something Popup
Description: A plugin to display auto-popup and on-click popup content on WordPress landing pages.
Version: 1.1
Author: Muhammad Yasin
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Add popup scripts and styles
function popup_enqueue_scripts() {
    wp_enqueue_script('popup-script', plugin_dir_url(__FILE__) . 'popup.js', ['jquery'], '1.0', true);
    wp_enqueue_style('popup-style', plugin_dir_url(__FILE__) . 'popup.css');
}
add_action('wp_enqueue_scripts', 'popup_enqueue_scripts');

// Register admin menu for popup settings
function popup_admin_menu() {
    add_menu_page(
        'Popup Settings',
        'Popup Settings',
        'manage_options',
        'popup-settings',
        'popup_settings_page',
        'dashicons-external'
    );
}
add_action('admin_menu', 'popup_admin_menu');

// Display popup settings page with WordPress editor
function popup_settings_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        update_option('popup_content', wp_kses_post($_POST['popup_content']));
        update_option('popup_trigger', sanitize_text_field($_POST['popup_trigger']));
        update_option('popup_button_id', sanitize_text_field($_POST['popup_button_id']));
        echo '<div class="updated"><p>Settings saved successfully!</p></div>';
    }

    $popup_content = get_option('popup_content', 'This is a sample popup!');
    $popup_trigger = get_option('popup_trigger', 'auto');
    $popup_button_id = get_option('popup_button_id', 'popup-trigger');
    ?>
    <div class="wrap">
        <h1>Popup Settings</h1>
        <form method="POST">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="popup_content">Popup Content</label>
                    </th>
                    <td>
                        <?php
                        wp_editor(
                            $popup_content,
                            'popup_content',
                            [
                                'textarea_name' => 'popup_content',
                                'media_buttons' => true,
                                'textarea_rows' => 10,
                            ]
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="popup_trigger">Popup Trigger</label>
                    </th>
                    <td>
                        <select name="popup_trigger" id="popup_trigger">
                            <option value="auto" <?php selected($popup_trigger, 'auto'); ?>>Auto Popup</option>
                            <option value="click" <?php selected($popup_trigger, 'click'); ?>>On Click</option>
                        </select>
                        <p class="description">Choose "Auto Popup" to display the popup automatically when the page loads. Select "On Click" to show the popup only when the user clicks a button or element with a specific ID.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="popup_button_id">Button/Element ID for On-Click Trigger</label>
                    </th>
                    <td>
                        <input type="text" name="popup_button_id" id="popup_button_id" value="<?php echo esc_attr($popup_button_id); ?>" class="regular-text">
                        <p class="description">Specify the ID of the button or element that will trigger the popup when clicked. Default is "popup-trigger".</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}

// Add popup HTML to the footer
function display_popup() {
    $popup_content = get_option('popup_content', 'This is a sample popup!');
    $popup_trigger = get_option('popup_trigger', 'auto');
    $popup_button_id = get_option('popup_button_id', 'popup-trigger');
    ?>
    <div id="custom-popup" class="popup-overlay" style="display: none;" data-trigger-id="<?php echo esc_attr($popup_trigger === 'click' ? $popup_button_id : 'auto'); ?>">
        <div class="popup-content">
            <span class="close-popup">&times;</span>
            <?php echo wp_kses_post($popup_content); ?>
        </div>
    </div>
    <?php if ($popup_trigger === 'click') : ?>
        <button id="<?php echo esc_attr($popup_button_id); ?>" class="popup-button">Show Popup</button>
    <?php endif; ?>
    <?php
}

add_action('wp_footer', 'display_popup');
?>
