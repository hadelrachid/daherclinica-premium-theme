<?php
/**
 * Template Name: Página Blog
 * 
 * @package DaherClinica
 */

if (!defined('ABSPATH')) {
    exit;
}

// ============================================================
// BLOQUEIO ABSOLUTO DE CACHE DE NAVEGADOR E EDGE (CLOUDFLARE)
// ============================================================
// Força navegadores, proxies e CDNs a NUNCA servir cópia antiga
nocache_headers();
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('X-LiteSpeed-Cache-Control: no-cache');

get_header();
?>

    <!-- Blog Hero com Paralaxe -->
    <section class="blog-hero">
        <div class="blog-hero-bg">
            <div class="blog-hero-overlay"></div>
            <picture>
                <source media="(max-width: 992px)" srcset="<?php echo get_template_directory_uri(); ?>/assets/images/blog-hero-mob.webp">
                <source media="(min-width: 993px)" srcset="<?php echo get_template_directory_uri(); ?>/assets/images/blog-hero.webp">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/blog-hero.webp" alt="Blog Daher Clínica" class="blog-hero-image" width="1920" height="800" loading="eager">
            </picture>
        </div>
        <div class="container">
            <div class="blog-hero-content">
                <div class="section-tag">
                    <span class="tag">✦ Blog</span>
                </div>
                <h1>Dicas e Novidades em<br><span class="gradient-text">Saúde Vascular e Dermatologia</span></h1>
                <p>Artigos escritos por nossos especialistas para ajudar você a cuidar melhor da sua saúde e beleza</p>
            </div>
        </div>
        <div class="blog-hero-scroll">
            <a href="#blogContent" class="scroll-link">
                <span class="scroll-text">Leia os artigos</span>
                <i class="fas fa-chevron-down"></i>
            </a>
        </div>
    </section>

    <!-- Blog Content -->
    <section id="blogContent" class="blog-section">
        <div class="container">
            
            <!-- Filtros - ATUALIZADO com Clínica Geral -->
            <div class="blog-filters">
                <button class="filter-btn active" data-filter="all">Todos</button>
                <button class="filter-btn" data-filter="vascular">Cirurgia Vascular</button>
                <button class="filter-btn" data-filter="dermatologia">Dermatologia</button>
                <button class="filter-btn" data-filter="clinica-geral">Clínica Geral</button>
                <button class="filter-btn" data-filter="bem-estar">Bem-estar</button>
            </div>
            
            <!-- Search Bar -->
            <div class="blog-search">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar artigos...">
            </div>
            
            <!-- Posts Grid -->
            <div class="blog-grid" id="blogGrid">
                
                <?php
                $paged = get_query_var('paged') ? get_query_var('paged') : 1;
                $args = array(
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'posts_per_page' => 6,
                    'paged'          => $paged
                );
                $blog_posts = new WP_Query($args);
                
                if ($blog_posts->have_posts()) :
                    while ($blog_posts->have_posts()) : $blog_posts->the_post();
                        
                        $categories = get_the_category();
                        
                        // Determina o nome para exibição na badge (ignora 'Sem categoria' ou 'Todos' se possível)
                        $category_name = 'Bem-estar';
                        if (!empty($categories)) {
                            foreach ($categories as $cat) {
                                if (strtolower($cat->name) !== 'todos' && strtolower($cat->name) !== 'sem categoria') {
                                    $category_name = $cat->name;
                                    break;
                                }
                            }
                            // Fallback caso todas sejam "Todos"
                            if ($category_name === 'Bem-estar' && !empty($categories[0])) {
                                $category_name = $categories[0]->name;
                            }
                        }

                        // Mapeamento para os filtros
                        $filter_map = array(
                            'vascular' => 'vascular',
                            'cirurgia-vascular' => 'vascular',
                            'dermatologia' => 'dermatologia',
                            'clinica-geral' => 'clinica-geral',
                            'clinica geral' => 'clinica-geral',
                            'bem-estar' => 'bem-estar',
                            'saude' => 'bem-estar'
                        );
                        
                        $filter_cats = array();
                        if (!empty($categories)) {
                            foreach ($categories as $cat) {
                                if (isset($filter_map[$cat->slug])) {
                                    $filter_cats[] = $filter_map[$cat->slug];
                                }
                            }
                        }
                        
                        if (empty($filter_cats)) {
                            $filter_cats[] = 'bem-estar';
                        }
                        
                        $filter_cat_string = implode(' ', array_unique($filter_cats));
                        
                        // Tempo de leitura
                        $word_count = str_word_count(strip_tags(get_the_content()));
                        $reading_time = max(1, ceil($word_count / 200));
                ?>
                
                <article class="post-card" data-category="<?php echo esc_attr($filter_cat_string); ?>">
                    <a href="<?php the_permalink(); ?>" class="post-image-link">
                        <div class="post-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php echo get_the_post_thumbnail(get_the_ID(), 'medium_large', ['loading' => 'lazy']); ?>
                            <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/blog-placeholder.webp" alt="<?php the_title_attribute(); ?>" width="768" height="512" loading="lazy">
                            <?php endif; ?>
                            <span class="post-category"><?php echo esc_html($category_name); ?></span>
                        </div>
                    </a>
                    <div class="post-content">
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="post-meta">
                            <span><i class="far fa-calendar-alt"></i> <?php echo get_the_date('d/m/Y'); ?></span>
                            <span><i class="far fa-user"></i> <?php the_author(); ?></span>
                            <span><i class="far fa-clock"></i> <?php echo $reading_time; ?> min de leitura</span>
                        </div>
                        <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                        <div class="post-footer">
                            <a href="<?php the_permalink(); ?>" class="read-more">
                                Ler artigo <i class="fas fa-arrow-right"></i>
                            </a>
                            <div class="post-share-mini">
                                <a href="#" class="share-mini" data-share="whatsapp" data-url="<?php the_permalink(); ?>" data-title="<?php the_title_attribute(); ?>"><i class="fab fa-whatsapp"></i></a>
                                <a href="#" class="share-mini" data-share="facebook" data-url="<?php the_permalink(); ?>" data-title="<?php the_title_attribute(); ?>"><i class="fab fa-facebook-f"></i></a>
                                <button class="share-mini copy-link" data-url="<?php the_permalink(); ?>" title="Copiar link"><i class="fas fa-link"></i></button>
                            </div>
                        </div>
                    </div>
                </article>
                
                <?php 
                    endwhile;
                else :
                ?>
                
                <div class="blog-empty">
                    <i class="fas fa-newspaper fa-4x"></i>
                    <h3>Nenhum artigo encontrado</h3>
                    <p>Em breve publicaremos conteúdo interessante para você.</p>
                </div>
                
                <?php endif; ?>
                
            </div>
            
            <!-- Pagination -->
            <?php if ($blog_posts->max_num_pages > 1) : ?>
            <div class="pagination">
                <?php
                echo paginate_links(array(
                    'total'     => $blog_posts->max_num_pages,
                    'current'   => max(1, $paged),
                    'prev_text' => '<i class="fas fa-chevron-left"></i> Anterior',
                    'next_text' => 'Próximo <i class="fas fa-chevron-right"></i>',
                    'type'      => 'list',
                ));
                ?>
            </div>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-card glass-card">
                <div class="newsletter-icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h3>Receba Nossas Novidades</h3>
                <p>Inscreva-se para receber dicas de saúde, novidades e promoções exclusivas</p>
                <form class="newsletter-form newsletter-form-large" id="blogNewsletterForm" novalidate>
                    <input type="email" id="newsletterEmail" name="email" placeholder="Seu melhor e-mail" required>
                    <button type="submit">Inscrever-se <i class="fas fa-paper-plane"></i></button>
                </form>
                <p class="newsletter-msg" style="display:none; color:#4CAF50; margin-top:10px;"></p>
                <p class="newsletter-note">Não enviamos spam. Você pode cancelar a qualquer momento.</p>
            </div>
        </div>
    </section>

<?php get_footer(); ?>