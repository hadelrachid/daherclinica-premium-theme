<?php
/**
 * Template Part: Carrossel do Blog
 * Recebe o array de posts injetado. (SOLID: Open/Closed & Dependency Inversion)
 */

if (empty($args['posts'])) {
    return;
}

$posts = $args['posts'];
?>

<section class="daher-carousel-section">
    <div class="container">
        <div class="daher-carousel-header">
            <h2 class="section-title">Últimos Artigos</h2>
            <div class="daher-carousel-controls">
                <button type="button" class="daher-carousel-btn prev" aria-label="Artigos anteriores">
                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                </button>
                <button type="button" class="daher-carousel-btn next" aria-label="Próximos artigos">
                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <div class="daher-carousel-viewport">
            <div class="daher-carousel-track">
                <?php foreach ($posts as $post_item) : ?>
                    <article class="daher-carousel-slide">
                        <a href="<?php echo esc_url($post_item['permalink']); ?>" class="daher-carousel-card">
                            <?php if (!empty($post_item['thumbnail'])) : ?>
                                <div class="card-image" style="background-image: url('<?php echo esc_url($post_item['thumbnail']); ?>');"></div>
                            <?php else : ?>
                                <div class="card-image placeholder-image"><i class="fas fa-newspaper"></i></div>
                            <?php endif; ?>
                            
                            <div class="card-content">
                                <span class="card-date"><i class="far fa-calendar-alt"></i> <?php echo esc_html($post_item['date']); ?></span>
                                <h3 class="card-title"><?php echo esc_html($post_item['title']); ?></h3>
                                <p class="card-excerpt"><?php echo esc_html($post_item['excerpt']); ?></p>
                                <span class="card-readmore">Ler artigo completo <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
