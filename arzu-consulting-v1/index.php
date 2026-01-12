<?php get_header(); ?>

<div class="grid-background">
    <canvas id="gridCanvas"></canvas>
</div>

<section class="hero">
    <div class="hero-content">
        <h1><?php echo esc_html(get_theme_mod('arzu_hero_title', 'Transform Your Products With Systems Thinking & AI')); ?></h1>
        <p class="subtitle"><?php echo esc_html(get_theme_mod('arzu_hero_subtitle', 'Strategic product management consulting for organizations ready to innovate')); ?></p>
        <a href="#contact" class="cta-button"><?php echo esc_html(get_theme_mod('arzu_hero_button_text', "Let's Talk")); ?></a>
    </div>
</section>

<section id="services" class="section">
    <h2 class="section-title">Services</h2>
    <div class="services-grid">
        <?php
        $services = new WP_Query(array(
            'post_type' => 'service',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        ));
        
        if ($services->have_posts()) :
            while ($services->have_posts()) : $services->the_post();
                $icon = get_post_meta(get_the_ID(), '_service_icon', true);
                if (empty($icon)) {
                    $icon = '⭐';
                }
        ?>
            <div class="service-card">
                <div class="service-icon"><?php echo esc_html($icon); ?></div>
                <h3><?php the_title(); ?></h3>
                <p><?php the_content(); ?></p>
            </div>
        <?php
            endwhile;
            wp_reset_postdata();
        else :
            $default_services = array(
                array('icon' => '🎯', 'title' => 'Product Strategy', 'content' => 'Strategic alignment, roadmap development, and business case creation that connects your products to organizational goals and market opportunities.'),
                array('icon' => '🔄', 'title' => 'Systems Thinking', 'content' => 'Holistic analysis of your product ecosystem, identifying interconnections, feedback loops, and leverage points for transformational change.'),
                array('icon' => '🤖', 'title' => 'AI Integration', 'content' => 'Practical AI implementation strategies, from opportunity discovery to integration planning, leveraging tools like Claude, ChatGPT, and emerging technologies.'),
                array('icon' => '⚡', 'title' => 'Agile Transformation', 'content' => 'Scrum implementation, backlog management, and team coaching to accelerate product delivery and improve cross-functional collaboration.'),
                array('icon' => '📊', 'title' => 'Requirements Engineering', 'content' => 'Comprehensive elicitation, documentation, and validation of business requirements through proven techniques and stakeholder engagement.'),
                array('icon' => '🚀', 'title' => 'Change Management', 'content' => 'Organizational readiness assessment, stakeholder engagement, and adoption strategies to ensure successful product launches and transformations.'),
            );
            
            foreach ($default_services as $service) :
        ?>
            <div class="service-card">
                <div class="service-icon"><?php echo esc_html($service['icon']); ?></div>
                <h3><?php echo esc_html($service['title']); ?></h3>
                <p><?php echo esc_html($service['content']); ?></p>
            </div>
        <?php
            endforeach;
        endif;
        ?>
    </div>
</section>

<section id="expertise" class="section">
    <h2 class="section-title">Areas of Expertise</h2>
    <div class="expertise-list">
        <?php
        $expertise = new WP_Query(array(
            'post_type' => 'expertise',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        ));
        
        if ($expertise->have_posts()) :
            while ($expertise->have_posts()) : $expertise->the_post();
        ?>
            <span class="expertise-tag"><?php the_title(); ?></span>
        <?php
            endwhile;
            wp_reset_postdata();
        else :
            $default_expertise = array(
                'Product Management', 'Systems Thinking', 'Agile & Scrum', 'AI Integration',
                'Requirements Analysis', 'Stakeholder Management', 'Business Process',
                'Digital Transformation', 'Loyalty Programs', 'E-commerce',
                'Aviation & Hospitality', 'Change Management'
            );
            
            foreach ($default_expertise as $tag) :
        ?>
            <span class="expertise-tag"><?php echo esc_html($tag); ?></span>
        <?php
            endforeach;
        endif;
        ?>
    </div>
</section>

<section id="contact" class="section">
    <h2 class="section-title">Let's Work Together</h2>
    <form class="contact-form" id="contactForm">
        <div class="success-message" id="successMessage"></div>
        <div class="form-group">
            <label for="name">Name *</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="company">Company</label>
            <input type="text" id="company" name="company">
        </div>
        <div class="form-group">
            <label for="service">Service Interest</label>
            <select id="service" name="service">
                <option value="">Select a service</option>
                <option value="strategy">Product Strategy</option>
                <option value="systems">Systems Thinking</option>
                <option value="ai">AI Integration</option>
                <option value="agile">Agile Transformation</option>
                <option value="requirements">Requirements Engineering</option>
                <option value="change">Change Management</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="message">Message *</label>
            <textarea id="message" name="message" required placeholder="Tell me about your project or challenge..."></textarea>
        </div>
        <button type="submit" class="submit-button">Send Inquiry</button>
    </form>
</section>

<?php get_footer(); ?>
