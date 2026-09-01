<?php
/**
 * Template Part: Corpo Clínico (Team Section)
 * 
 * @package DaherClinica
 */

if (!defined('ABSPATH')) {
    exit;
}

// 1. Tenta buscar médicos do NOVO PAINEL (Settings API)
$medicos_painel = get_option('daher_medicos_options', []);

// Ordena os médicos por prioridade (campo 'ordem')
if (!empty($medicos_painel) && is_array($medicos_painel)) {
    usort($medicos_painel, function($a, $b) {
        $ordem_a = isset($a['ordem']) ? intval($a['ordem']) : 0;
        $ordem_b = isset($b['ordem']) ? intval($b['ordem']) : 0;
        return $ordem_a <=> $ordem_b;
    });
}

// 2. Tenta buscar médicos do CPT (Legacy) se o painel estiver vazio
$medicos_query = null;
if (empty($medicos_painel)) {
    $medicos_query = new WP_Query([
        'post_type'      => 'medico',
        'posts_per_page' => is_front_page() ? 3 : -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);
}

// WhatsApp Geral para o link de agendamento
$whatsapp_options = get_option('daher_whatsapp_options', []);
$whatsapp_geral = preg_replace('/[^0-9]/', '', $whatsapp_options['whatsapp_number'] ?? '5521977667676');
?>


<section id="equipe" class="section bg-light">
    <div class="container">
        <div class="team-section-header text-center">
            <span class="subtitle"><?php _e('Corpo Clínico', 'daherclinica'); ?></span>
            <h2 class="section-title"><?php _e('Especialistas Dedicados', 'daherclinica'); ?></h2>
            <p class="section-description"><?php _e('Médicos apaixonados pelo que fazem, unindo ciência rigorosa a um atendimento humanizado e individualizado.', 'daherclinica'); ?></p>
        </div>

        <div class="team-grid-new">
            <?php 
            // EXIBE MÉDICOS DO PAINEL (NOVO)
            if (!empty($medicos_painel)) : 
                $count = 0;
                foreach ($medicos_painel as $medico) : 
                    if (is_front_page() && $count >= 3) break;
                    $count++;
                    
                    $nome = $medico['nome'] ?? '';
                    $crm = $medico['crm'] ?? '';
                    $especialidade = $medico['especialidade'] ?? '';
                    $bio = $medico['bio'] ?? '';
                    $foto = !empty($medico['foto']) ? $medico['foto'] : get_template_directory_uri() . '/assets/images/medico-placeholder.webp';
                    
                    // Link de WhatsApp
                    $mensagem = "Olá! Gostaria de agendar uma consulta com " . $nome . " (" . $especialidade . ").";
                    $whatsapp_link = "https://wa.me/{$whatsapp_geral}?text=" . urlencode($mensagem);
            ?>
                <div class="team-card-new">
                    <div class="team-image-wrap">
                        <?php 
                        $foto_id = attachment_url_to_postid($foto);
                        if ($foto_id) {
                            echo wp_get_attachment_image($foto_id, 'medico-large', false, ['class' => 'team-img', 'loading' => 'lazy']);
                        } else {
                            echo '<img src="' . esc_url($foto) . '" alt="' . esc_attr($nome) . '" class="team-img" width="400" height="400" loading="lazy">';
                        }
                        ?>
                        <?php if (!empty($medico['instagram'])) : ?>
                            <div class="team-overlay">
                                <div class="team-social-links">
                                    <a href="<?php echo esc_url($medico['instagram']); ?>" class="team-social-btn" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                        <?php echo daherclinica_get_icon('instagram', ['width' => 22, 'height' => 22]); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="team-info-new">
                        <div class="team-info-header">
                            <h3><?php echo esc_html($nome); ?></h3>
                            <span class="team-specialty-badge"><?php echo esc_html($especialidade); ?></span>
                            <?php if ($crm) : ?>
                                <div class="team-crm"><?php echo esc_html($crm); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="team-info-body">
                            <?php if ($bio) : ?>
                                <p class="team-bio"><?php echo esc_html($bio); ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($medico['tags'])) : 
                                $tags = explode(',', $medico['tags']);
                                $tags = array_map('trim', $tags);
                                $tags = array_filter($tags);
                                $tags = array_slice($tags, 0, 3); // Limita a no máximo 3 tags
                            ?>
                                <div class="team-expertise">
                                    <?php foreach ($tags as $tag) : ?>
                                        <span class="doctor-tag"><?php echo esc_html($tag); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="team-info-footer">
                            <a href="<?php echo esc_url($whatsapp_link); ?>" class="btn btn-primary btn-sm btn-whatsapp" target="_blank" rel="noopener noreferrer" aria-label="Agendar consulta pelo WhatsApp">
                                <?php _e('Agendar Consulta', 'daherclinica'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
                endforeach; 

            // EXIBE MÉDICOS DO CPT (LEGACY)
            elseif ($medicos_query && $medicos_query->have_posts()) : 
                while ($medicos_query->have_posts()) : $medicos_query->the_post();
                    $crm = get_post_meta(get_the_ID(), '_medico_crm', true);
                    $especialidade = get_post_meta(get_the_ID(), '_medico_especialidade', true);
                    $foto = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'team-photo') : get_template_directory_uri() . '/assets/images/medico-placeholder.webp';
                    
                    $mensagem = "Olá! Gostaria de agendar uma consulta com " . get_the_title() . " (" . esc_html($especialidade) . ").";
                    $whatsapp_link = "https://wa.me/{$whatsapp_geral}?text=" . urlencode($mensagem);
            ?>
                <div class="team-card-new">
                    <div class="team-image-wrap">
                        <?php 
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('medico-large', ['class' => 'team-img', 'loading' => 'lazy']);
                        } else {
                            echo '<img src="' . esc_url($foto) . '" alt="' . the_title_attribute(['echo' => false]) . '" class="team-img" width="400" height="400" loading="lazy">';
                        }
                        ?>
                    </div>
                    <div class="team-info-new">
                        <div class="team-info-header">
                            <h3><?php the_title(); ?></h3>
                            <span class="team-specialty-badge"><?php echo esc_html($especialidade); ?></span>
                            <?php if ($crm) : ?>
                                <div class="team-crm"><?php echo esc_html($crm); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="team-info-body">
                            <p class="team-bio"><?php echo get_the_excerpt(); ?></p>
                        </div>
                        
                        <div class="team-info-footer">
                            <a href="<?php echo esc_url($whatsapp_link); ?>" class="btn btn-primary btn-sm btn-whatsapp" target="_blank" rel="noopener noreferrer" aria-label="Agendar consulta pelo WhatsApp">
                                <?php _e('Agendar Consulta', 'daherclinica'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
                endwhile;
                wp_reset_postdata();

            // FALLBACK: MÉDICOS PADRÃO (HARDCODED)
            else : 
            ?>
                <!-- Médicos fixos se nada for encontrado -->
                <div class="team-card-new">
                    <div class="team-image-wrap">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/medico-placeholder.webp" alt="Dr. Marcelo Daher" class="team-img" width="400" height="400" loading="lazy">
                    </div>
                    <div class="team-info-new">
                        <h3><?php _e('Dr. Marcelo de Azevedo Daher', 'daherclinica'); ?></h3>
                        <span class="team-specialty-badge"><?php _e('Cirurgião Vascular', 'daherclinica'); ?></span>
                        <div class="team-crm"><?php _e('CRM 52 61207-0', 'daherclinica'); ?></div>
                        <p class="team-bio"><?php _e('Especialista em tratamentos avançados de varizes e check-up vascular.', 'daherclinica'); ?></p>
                        <a href="https://wa.me/<?php echo $whatsapp_geral; ?>" class="btn btn-primary btn-sm btn-whatsapp" target="_blank" aria-label="Agendar consulta pelo WhatsApp">
                            <?php _e('Agendar Consulta', 'daherclinica'); ?>
                        </a>
                    </div>
                </div>
                <!-- ... outros médicos padrão podem vir aqui ... -->
            <?php endif; ?>
        </div>
    </div>
</section>