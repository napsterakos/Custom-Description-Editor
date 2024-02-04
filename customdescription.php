<?php
/*
Plugin Name: Custom Description Editor
Description: Allows admins to set custom descriptions for categories.
Version: 1.0
Author: Your Name
*/

// Add admin menu item
function custom_description_editor_menu() {
    add_menu_page(
        'Custom Description Editor',
        'Custom Description',
        'manage_options',
        'custom-description-editor',
        'custom_description_editor_page'
    );
}
add_action('admin_menu', 'custom_description_editor_menu');

// Render the settings page
function custom_description_editor_page() {
    ?>
    <div class="wrap">
        <h2>Custom Description Editor</h2>
        <form method="post" action="options.php">
            <?php settings_fields('custom-description-settings'); ?>
            <?php do_settings_sections('custom-description-settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings and fields
function custom_description_editor_init() {
    register_setting('custom-description-settings', 'custom_description_options', 'custom_description_sanitize');

    add_settings_section('custom-description-section', 'Settings', '', 'custom-description-settings');

    add_settings_field('text_area_1', 'Text Area 1', 'text_area_1_callback', 'custom-description-settings', 'custom-description-section');
    add_settings_field('text_area_2', 'Text Area 2', 'text_area_2_callback', 'custom-description-settings', 'custom-description-section');
    add_settings_field('text_area_3', 'Text Area 3', 'text_area_3_callback', 'custom-description-settings', 'custom-description-section');
    add_settings_field('text_area_4', 'Text Area 4', 'text_area_4_callback', 'custom-description-settings', 'custom-description-section');

    add_settings_field('category_selector', 'Category Selector', 'category_selector_callback', 'custom-description-settings', 'custom-description-section');
}
add_action('admin_init', 'custom_description_editor_init');

// Sanitize input
function custom_description_sanitize($input) {
    $sanitized_input = array();

    // Sanitize each input field
    foreach ($input as $key => $value) {
        $sanitized_input[$key] = sanitize_text_field($value);
    }

    return $sanitized_input;
}

// Render text area fields
function text_area_1_callback() {
    $options = get_option('custom_description_options');
    echo '<textarea id="text_area_1" name="custom_description_options[text_area_1]">' . esc_attr($options['text_area_1']) . '</textarea>';
}

function text_area_2_callback() {
    $options = get_option('custom_description_options');
    echo '<textarea id="text_area_2" name="custom_description_options[text_area_2]">' . esc_attr($options['text_area_2']) . '</textarea>';
}

function text_area_3_callback() {
    $options = get_option('custom_description_options');
    echo '<textarea id="text_area_3" name="custom_description_options[text_area_3]">' . esc_attr($options['text_area_3']) . '</textarea>';
}

function text_area_4_callback() {
    $options = get_option('custom_description_options');
    echo '<textarea id="text_area_4" name="custom_description_options[text_area_4]">' . esc_attr($options['text_area_4']) . '</textarea>';
}

// Render category selector
function category_selector_callback() {
    $options = get_option('custom_description_options');
    $categories = get_categories();

    echo '<select id="category_selector" name="custom_description_options[category_selector]">';
    foreach ($categories as $category) {
        echo '<option value="' . esc_attr($category->term_id) . '" ' . selected($options['category_selector'], $category->term_id, false) . '>' . esc_html($category->name) . '</option>';
    }
    echo '</select>';
}

// Override short description
function override_short_description($category_id) {
    $options = get_option('custom_description_options');
    $category_selected = $options['category_selector'];

    if ($category_id == $category_selected) {
        $short_description = '';

        // Concatenate text areas with the specified HTML
        $short_description .= '<p><img class="alignleft size-large wp-image-22270" src="https://click4phone.gr/wp-content/uploads/2023/05/line1-porto-1024x15.png" alt="line1-porto" width="1024" height="15"></p>';
        $short_description .= $options['text_area_1'] . '<br>';
        $short_description .= $options['text_area_2'] . '<br>';
        $short_description .= $options['text_area_3'] . '<br>';
        $short_description .= $options['text_area_4']; 

        // Update short description
        wp_update_term($category_id, 'category', array(
            'description' => $short_description
        ));
    }
}
add_action('created_category', 'override_short_description');
add_action('edited_category', 'override_short_description');
