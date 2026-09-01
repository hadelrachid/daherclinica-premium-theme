<?php
/**
 * Template Part: Especialidades Section
 * 
 * @package DaherClinica
 */

if (!defined('ABSPATH')) {
    exit;
}

$home_options = get_option('daher_home_options', []);
$specialties = isset($home_options['specialties']) ? $home_options['specialties'] : [];

// Se não houver cards configurados, mostra um aviso no admin
if (empty($specialties) && current_user_can('manage_options')) {
    echo '<div class="container"><div class="admin-notice" style="background: #f0f6fc; padding: 20px; text-align: center; border-radius: 8px;">';
    echo '<p>⚠️ Nenhum card de especialidade configurado. <a href="' . admin_url('admin.php?page=daher-settings&tab=home') . '">Clique aqui para adicionar</a>.</p>';
    echo '</div></div>';
}
?>

<section id="especialidades" class="section specialties">
    <div class="container">
        <div class="section-header text-center">
            <div class="section-tag">
                <span class="tag">✦ <?php _e('Especialidades', 'daherclinica'); ?></span>
            </div>
            <h2 class="section-title"><?php _e('Nossas Áreas de Atuação', 'daherclinica'); ?></h2>
            <p class="section-subtitle"><?php _e('Tratamentos modernos com resultados excepcionais', 'daherclinica'); ?></p>
        </div>
        
        <div class="specialties-grid">
            <?php if (!empty($specialties)) : ?>
                <?php foreach ($specialties as $card) : 
                    $title = $card['title'] ?? '';
                    $subtitle = $card['subtitle'] ?? '';
                    $description = $card['description'] ?? '';
                    $image = !empty($card['image']) ? $card['image'] : get_template_directory_uri() . '/assets/images/placeholder.webp';
                    $link = !empty($card['link']) ? $card['link'] : '#contato';
                    $items = isset($card['items']) && is_array($card['items']) ? $card['items'] : [];
                    
                    // Se items veio como string com quebras de linha
                    if (!is_array($items) && !empty($card['items'])) {
                        $items = explode("\n", $card['items']);
                        $items = array_map('trim', $items);
                        $items = array_filter($items);
                    }
                ?>
                    <div class="specialty-card">
                        <div class="card-image">
                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" width="800" height="600" loading="lazy" decoding="async">
                            <div class="card-overlay"></div>
                        </div>
                        <div class="card-content text-center" style="text-align: center; display: flex; flex-direction: column; align-items: center;">
                            <div class="card-icon" style="margin: 0 auto 15px auto;">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            
                            <div class="card-title-wrap" style="width: 100%;">
                                <h3><?php echo esc_html($title); ?></h3>
                                <?php if ($subtitle) : ?>
                                    <p class="card-subtitle"><?php echo esc_html($subtitle); ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($description) : ?>
                                <div class="card-description-wrap" style="width: 100%;">
                                    <p><?php echo esc_html($description); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($items)) : ?>
                                <div class="card-list-wrap" style="width: 100%; margin-top: 15px;">
                                    <ul class="card-list" style="list-style: none; padding: 0; margin: 0; text-align: center;">
                                        <?php foreach ($items as $item) : ?>
                                            <li style="margin-bottom: 5px; justify-content: center; display: flex; align-items: center;"><i class="fas fa-check-circle" style="color: var(--primary); margin-right: 8px;"></i> <?php echo esc_html($item); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-footer" style="width: 100%; margin-top: 20px;">
                                <a href="<?php echo esc_url($link); ?>" class="card-link" style="display: inline-block;">
                                    <?php _e('Saiba mais', 'daherclinica'); ?> <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>