<?php
/**
 * Index Template (Fallback)
 * 
 * Usado quando templates mais específicos não existem
 *
 * @package DaherClinica
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<section class="section page-content">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="blog-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('blog-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="blog-thumbnail">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        <?php endif; ?>
                        
                        <div class="blog-content">
                            <h2>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="blog-meta">
                                <span><?php echo get_the_date(); ?></span>
                                <span><?php _e('por', 'daherclinica'); ?> <?php the_author(); ?></span>
                            </div>
                            <div class="blog-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="link-arrow">
                                <?php _e('Leia mais', 'daherclinica'); ?> →
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <div class="pagination">
                <?php echo paginate_links(); ?>
            </div>
            
        <?php else : ?>
            <p><?php _e('Nenhum post encontrado.', 'daherclinica'); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>