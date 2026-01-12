<?php
/**
 * Arzu Consulting Theme Functions
 */

// Theme setup
function arzu_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('custom-logo');
    
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'arzu-consulting'),
    ));
}
add_action('after_setup_theme', 'arzu_theme_setup');

// Enqueue styles and scripts
function arzu_enqueue_scripts() {
    wp_enqueue_style('arzu-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap', array(), null);
    wp_enqueue_style('arzu-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_script('arzu-scripts', get_template_directory_uri() . '/js/scripts.js', array(), '1.0.0', true);
    
    wp_localize_script('arzu-scripts', 'arzuAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('arzu_contact_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'arzu_enqueue_scripts');

// Create custom post type for Services
function arzu_create_services_post_type() {
    register_post_type('service',
        array(
            'labels' => array(
                'name' => __('Services', 'arzu-consulting'),
                'singular_name' => __('Service', 'arzu-consulting'),
            ),
            'public' => true,
            'has_archive' => false,
            'menu_icon' => 'dashicons-portfolio',
            'supports' => array('title', 'editor', 'thumbnail'),
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'arzu_create_services_post_type');

// Create custom post type for Expertise
function arzu_create_expertise_post_type() {
    register_post_type('expertise',
        array(
            'labels' => array(
                'name' => __('Expertise Areas', 'arzu-consulting'),
                'singular_name' => __('Expertise', 'arzu-consulting'),
            ),
            'public' => true,
            'has_archive' => false,
            'menu_icon' => 'dashicons-awards',
            'supports' => array('title'),
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'arzu_create_expertise_post_type');

// Add custom fields for services
function arzu_service_meta_boxes() {
    add_meta_box(
        'service_icon',
        'Service Icon',
        'arzu_service_icon_callback',
        'service',
        'side'
    );
}
add_action('add_meta_boxes', 'arzu_service_meta_boxes');

function arzu_service_icon_callback($post) {
    wp_nonce_field('arzu_save_service_icon', 'arzu_service_icon_nonce');
    $value = get_post_meta($post->ID, '_service_icon', true);
    echo '<label for="service_icon_field">Enter emoji icon: </label>';
    echo '<input type="text" id="service_icon_field" name="service_icon_field" value="' . esc_attr($value) . '" size="25" placeholder="🎯" />';
}

function arzu_save_service_icon($post_id) {
    if (!isset($_POST['arzu_service_icon_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['arzu_service_icon_nonce'], 'arzu_save_service_icon')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (isset($_POST['service_icon_field'])) {
        update_post_meta($post_id, '_service_icon', sanitize_text_field($_POST['service_icon_field']));
    }
}
add_action('save_post', 'arzu_save_service_icon');

// Register customizer settings
function arzu_customize_register($wp_customize) {
    $wp_customize->add_section('arzu_hero_section', array(
        'title' => __('Hero Section', 'arzu-consulting'),
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('arzu_hero_title', array(
        'default' => 'Transform Your Products With Systems Thinking & AI',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('arzu_hero_title', array(
        'label' => __('Hero Title', 'arzu-consulting'),
        'section' => 'arzu_hero_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('arzu_hero_subtitle', array(
        'default' => 'Strategic product management consulting for organizations ready to innovate',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('arzu_hero_subtitle', array(
        'label' => __('Hero Subtitle', 'arzu-consulting'),
        'section' => 'arzu_hero_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('arzu_hero_button_text', array(
        'default' => "Let's Talk",
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('arzu_hero_button_text', array(
        'label' => __('Hero Button Text', 'arzu-consulting'),
        'section' => 'arzu_hero_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_section('arzu_footer_section', array(
        'title' => __('Footer Settings', 'arzu-consulting'),
        'priority' => 40,
    ));
    
    $wp_customize->add_setting('arzu_footer_text', array(
        'default' => 'Product Management & Systems Thinking Consulting',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('arzu_footer_text', array(
        'label' => __('Footer Text', 'arzu-consulting'),
        'section' => 'arzu_footer_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('arzu_footer_credentials', array(
        'default' => 'Certified Scrum Product Owner | Certified Scrum Master',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('arzu_footer_credentials', array(
        'label' => __('Footer Credentials', 'arzu-consulting'),
        'section' => 'arzu_footer_section',
        'type' => 'text',
    ));
}
add_action('customize_register', 'arzu_customize_register');

// Handle contact form submission
function arzu_handle_contact_form() {
    check_ajax_referer('arzu_contact_nonce', 'nonce');
    
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $company = sanitize_text_field($_POST['company']);
    $service = sanitize_text_field($_POST['service']);
    $message = sanitize_textarea_field($_POST['message']);
    
    $to = get_option('admin_email');
    $subject = 'New Contact Form Submission from ' . $name;
    $body = "Name: $name\n";
    $body .= "Email: $email\n";
    $body .= "Company: $company\n";
    $body .= "Service Interest: $service\n";
    $body .= "Message:\n$message\n";
    
    $headers = array('Content-Type: text/plain; charset=UTF-8', 'From: ' . $name . ' <' . $email . '>');
    
    if (wp_mail($to, $subject, $body, $headers)) {
        wp_send_json_success(array('message' => 'Thank you! Your inquiry has been received. I\'ll get back to you shortly.'));
    } else {
        wp_send_json_error(array('message' => 'There was an error sending your message. Please try again.'));
    }
}
add_action('wp_ajax_arzu_contact_form', 'arzu_handle_contact_form');
add_action('wp_ajax_nopriv_arzu_contact_form', 'arzu_handle_contact_form');

// Widget areas
function arzu_widgets_init() {
    register_sidebar(array(
        'name' => __('Footer Widget Area', 'arzu-consulting'),
        'id' => 'footer-widget-area',
        'description' => __('Appears in the footer section', 'arzu-consulting'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'arzu_widgets_init');

// Default menu fallback functions
function arzu_default_menu() {
    echo '<ul class="nav-links">';
    echo '<li><a href="#services">Services</a></li>';
    echo '<li><a href="#expertise">Expertise</a></li>';
    echo '<li><a href="#contact">Contact</a></li>';
    echo '</ul>';
}

function arzu_default_mobile_menu() {
    echo '<a href="#services">Services</a>';
    echo '<a href="#expertise">Expertise</a>';
    echo '<a href="#contact">Contact</a>';
}
?>
