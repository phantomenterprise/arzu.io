<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

<nav class="nav">
    <div class="logo">
        <?php if (has_custom_logo()) : ?>
            <?php the_custom_logo(); ?>
        <?php else : ?>
            <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
        <?php endif; ?>
    </div>
    
    <?php
    wp_nav_menu(array(
        'theme_location' => 'primary',
        'container' => false,
        'menu_class' => 'nav-links',
        'fallback_cb' => 'arzu_default_menu'
    ));
    ?>
    
    <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle mobile menu">☰</button>
</nav>

<div class="mobile-menu" id="mobileMenu">
    <?php
    wp_nav_menu(array(
        'theme_location' => 'primary',
        'container' => false,
        'items_wrap' => '<ul>%3$s</ul>',
        'fallback_cb' => 'arzu_default_mobile_menu'
    ));
    ?>
</div>
