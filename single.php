<?php
/**
 * Single Post Template
 * Exibe um post individual do blog
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
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-post glow-card'); ?>>
                
                <!-- Breadcrumb -->
                <div class="breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Início', 'daherclinica'); ?></a>
                    <i class="fas fa-chevron-right"></i>
                    <a href="<?php echo esc_url(home_url('/blog')); ?>"><?php _e('Blog', 'daherclinica'); ?></a>
                    <i class="fas fa-chevron-right"></i>
                    <span><?php the_title(); ?></span>
                </div>
                
                <h1 class="post-title"><?php the_title(); ?></h1>
                
                <div class="post-meta">
                    <span class="post-date">
                        <i class="far fa-calendar-alt"></i> <?php echo get_the_date(); ?>
                    </span>
                    <span class="post-author">
                        <i class="far fa-user"></i> <?php _e('por', 'daherclinica'); ?> <?php the_author(); ?>
                    </span>
                    <span class="post-cats">
                        <i class="far fa-folder"></i> <?php the_category(', '); ?>
                    </span>
                    <?php if (get_the_tags()) : ?>
                    <span class="post-tags-icon">
                        <i class="fas fa-tags"></i> <?php the_tags('', ', '); ?>
                    </span>
                    <?php endif; ?>
                </div>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large', array('class' => 'post-featured-image')); ?>
                    </div>
                <?php endif; ?>
                
                <div class="post-content">
                    <?php the_content(); ?>
                </div>
                
                <!-- Compartilhar -->
                <div class="post-share">
                    <span><?php _e('Compartilhar:', 'daherclinica'); ?></span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="share-btn facebook" data-share="facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://x.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share-btn twitter" data-share="x">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                    <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" target="_blank" class="share-btn whatsapp" data-share="whatsapp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <button class="share-btn copy-link" data-url="<?php the_permalink(); ?>" title="Copiar link">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
                
                <!-- Navegação entre posts -->
                <div class="post-navigation">
                    <div class="nav-previous">
                        <?php previous_post_link('%link', '<i class="fas fa-arrow-left"></i> ' . __('Post anterior', 'daherclinica')); ?>
                    </div>
                    <div class="nav-next">
                        <?php next_post_link('%link', __('Próximo post', 'daherclinica') . ' <i class="fas fa-arrow-right"></i>'); ?>
                    </div>
                </div>
                
                <!-- Comentários (opcional) -->
                <?php if (comments_open() || get_comments_number()) : ?>
                    <div class="post-comments">
                        <?php comments_template(); ?>
                    </div>
                <?php endif; ?>
                
            </article>
            
        <?php endwhile; endif; ?>
    </div>
</section>

<?php get_footer(); ?>