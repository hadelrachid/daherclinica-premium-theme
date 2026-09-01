<?php
/**
 * Template Name: Default Page Template
 * 
 * @package DaherClinica
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<?php while (have_posts()) : the_post(); ?>
    <section class="page-header">
        <div class="container">
            <h1><?php the_title(); ?></h1>
            <?php if (has_excerpt()) : ?>
                <p><?php echo get_the_excerpt(); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <section class="section page-content">
        <div class="container">
            <div class="content-wrapper">
                <?php the_content(); ?>
            </div>
        </div>
    </section>
<?php endwhile; ?>

<?php get_footer(); ?>
