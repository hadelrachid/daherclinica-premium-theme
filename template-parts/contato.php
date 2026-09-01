<?php
/**
 * Template Part: Contato Section (Formulário)
 * 
 * @package DaherClinica
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<section id="contato" class="section contact-section">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-info">
                <span class="subtitle"><?php _e('Contato e Localização', 'daherclinica'); ?></span>
                <h2><?php _e('Agende sua Consulta', 'daherclinica'); ?></h2>
                <p><?php _e('Nossa equipe está pronta para receber você com máximo conforto e atenção.', 'daherclinica'); ?></p>

                <div class="info-blocks">
                    <div class="info-item">
                        <strong><?php _e('Endereço', 'daherclinica'); ?></strong>
                        <?php 
                        $map_options = get_option('daher_map_options', []);
                        $address = '';
                        if (!empty($map_options['map_street'])) {
                            $address = esc_html($map_options['map_street']);
                            if (!empty($map_options['map_number'])) $address .= ', ' . esc_html($map_options['map_number']);
                            if (!empty($map_options['map_complement'])) $address .= ' - ' . esc_html($map_options['map_complement']);
                            $address .= '<br>';
                            if (!empty($map_options['map_neighborhood'])) $address .= esc_html($map_options['map_neighborhood']) . ', ';
                            $address .= esc_html($map_options['map_city'] ?? '') . ' - ' . esc_html($map_options['map_state'] ?? '');
                            if (!empty($map_options['map_zip'])) $address .= '<br>CEP ' . esc_html($map_options['map_zip']);
                        } else {
                            $address = 'Estrada dos Bandeirantes, 8591<br>Sala 308 MAP BAND SHOPPING, Rio de Janeiro - RJ<br>CEP 22783-115';
                        }
                        ?>
                        <p><?php echo wp_kses($address, ['br' => []]); ?></p>
                    </div>
                    <div class="info-item">
                        <strong><?php _e('Contatos', 'daherclinica'); ?></strong>
                        <p>
                            <?php 
                            $clinica_options = get_option('daher_clinica_options', []);
                            $whatsapp_options = get_option('daher_whatsapp_options', []);
                            
                            $phone_raw = !empty($clinica_options['daher_phone']) ? $clinica_options['daher_phone'] : '(21) 2415-9263';
                            $whatsapp_raw = !empty($whatsapp_options['whatsapp_number']) ? $whatsapp_options['whatsapp_number'] : '5521977667676';
                            
                            // Formata ambos para exibição
                            $phone_display = daherclinica_format_phone($phone_raw);
                            $whatsapp_display = daherclinica_format_phone($whatsapp_raw);
                            ?>
                            <?php _e('Telefone:', 'daherclinica'); ?> <?php echo esc_html($phone_display); ?><br>
                            <?php _e('WhatsApp:', 'daherclinica'); ?> 
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $whatsapp_raw); ?>" target="_blank">
                                <?php echo esc_html($whatsapp_display); ?>
                            </a>
                        </p>
                    </div>
                    <div class="info-item">
                        <strong><?php _e('Horário de Funcionamento', 'daherclinica'); ?></strong>
                        <p><?php echo esc_html($clinica_options['daher_hours'] ?? 'Segunda a Sexta: 09:00 - 18:00'); ?></p>
                    </div>
                </div>

                <div class="contact-map map-facade-container" tabindex="0" role="button" aria-label="<?php esc_attr_e('Carregar mapa interativo de localização', 'daherclinica'); ?>" style="position: relative; width: 100%; height: 250px; background: #e0e0e0; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 1px solid #ccc;">
                    <div class="map-facade-overlay" style="text-align: center;">
                        <i class="fas fa-map-marker-alt" style="font-size: 2rem; color: var(--primary); margin-bottom: 10px;" aria-hidden="true"></i>
                        <p style="margin:0; font-weight: 600; color: #1E293B;"><?php _e('Clique para carregar o mapa interativo', 'daherclinica'); ?></p>
                        <small style="color: #334155; font-weight: 500;"><?php _e('(Otimizado para economia de dados)', 'daherclinica'); ?></small>
                    </div>
                    <template id="googleMapTemplate">
                        <?php if (!empty($map_options['map_iframe'])) : ?>
                            <?php 
                            // Injeta title no iframe se não houver
                            $iframe = $map_options['map_iframe'];
                            if (strpos($iframe, 'title=') === false) {
                                $iframe = str_replace('<iframe ', '<iframe title="Mapa de localização da Clínica" ', $iframe);
                            }
                            if (strpos($iframe, 'height=') !== false) {
                                $iframe = preg_replace('/height="[^"]+"/', 'height="100%"', $iframe);
                            } else {
                                $iframe = str_replace('<iframe ', '<iframe height="100%" ', $iframe);
                            }
                            echo $iframe; 
                            ?>
                        <?php else : ?>
                            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3673.3408103696943!2d-43.4165382!3d-22.9744918!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9bdc0c2c727431%3A0x62dff47d2403862f!2sShopping%20Map%20Band!5e0!3m2!1spt-BR!2sbr!4v1775526662430!5m2!1spt-BR!2sbr" 
                                    title="Mapa de localização da Clínica"
                                    width="100%" 
                                    height="100%" 
                                    style="border:0; border-radius: 12px;" 
                                    allowfullscreen="" 
                                    loading="lazy"></iframe>
                        <?php endif; ?>
                    </template>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var facade = document.querySelector('.map-facade-container');
                        if (facade) {
                            function loadMap() {
                                var tpl = document.getElementById('googleMapTemplate');
                                if (tpl) {
                                    facade.innerHTML = tpl.innerHTML;
                                }
                            }
                            facade.addEventListener('click', loadMap, {once: true});
                            facade.addEventListener('keydown', function(e) {
                                if (e.key === 'Enter' || e.key === ' ') {
                                    e.preventDefault();
                                    loadMap();
                                }
                            }, {once: true});
                        }
                    });
                </script>
            </div>

            <div class="contact-form-wrapper" id="agendamento">
                <form class="contact-form" id="contactForm">
                    <h3><?php _e('Solicitar Agendamento', 'daherclinica'); ?></h3>
                    
                    <div class="form-group">
                        <label for="nome"><?php _e('Nome Completo', 'daherclinica'); ?> *</label>
                        <input type="text" id="nome" name="nome" placeholder="<?php _e('Digite seu nome completo', 'daherclinica'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefone"><?php _e('WhatsApp / Telefone', 'daherclinica'); ?> *</label>
                        <input type="tel" id="telefone" name="telefone" placeholder="(00) 00000-0000" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email"><?php _e('E-mail', 'daherclinica'); ?></label>
                        <input type="email" id="email" name="email" placeholder="seu@email.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="especialidade"><?php _e('Especialidade Desejada', 'daherclinica'); ?> *</label>
                        <select id="especialidade" name="especialidade" required>
                            <option value=""><?php _e('Selecione uma especialidade', 'daherclinica'); ?></option>
                            <option value="vascular"><?php _e('Cirurgia Vascular', 'daherclinica'); ?></option>
                            <option value="dermatologia"><?php _e('Dermatologia', 'daherclinica'); ?></option>
                            <option value="clinica-geral"><?php _e('Clínica Geral', 'daherclinica'); ?></option>
                            <option value="ambos"><?php _e('Ambas as especialidades', 'daherclinica'); ?></option>
                            <option value="outros"><?php _e('Dúvidas / Outros', 'daherclinica'); ?></option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="mensagem"><?php _e('Mensagem', 'daherclinica'); ?></label>
                        <textarea id="mensagem" name="mensagem" rows="4" placeholder="<?php _e('Descreva brevemente sua necessidade ou dúvida...', 'daherclinica'); ?>"></textarea>
                    </div>
                    
                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="privacy" required>
                            <?php 
                            $privacidade_page = get_page_by_path('privacidade');
                            $termos_page = get_page_by_path('termos-de-uso');
                            ?>
                            <span><?php _e('Li e aceito a', 'daherclinica'); ?> <a href="<?php echo $privacidade_page ? get_permalink($privacidade_page) : esc_url(home_url('/privacidade')); ?>" class="open-legal-modal"><?php _e('Política de Privacidade', 'daherclinica'); ?></a> <?php _e('e os', 'daherclinica'); ?> <a href="<?php echo $termos_page ? get_permalink($termos_page) : esc_url(home_url('/termos-de-uso')); ?>" class="open-legal-modal"><?php _e('Termos de Uso', 'daherclinica'); ?></a> *</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-full">
                        <?php _e('Enviar via WhatsApp', 'daherclinica'); ?>
                    </button>
                    
                    <p class="form-note">* <?php _e('Campos obrigatórios | Seus dados estão seguros conosco', 'daherclinica'); ?></p>
                </form>
            </div>
        </div>
    </div>
</section>