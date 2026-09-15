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
                            <a href="https://wa.me/<?php echo function_exists('daherclinica_get_whatsapp_clean') ? daherclinica_get_whatsapp_clean() : '5521977667676'; ?>?text=<?php echo urlencode('Olá! Acessei o site da clínica e gostaria de entrar em contato.'); ?>" target="_blank">
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
<?php
    $clinica_opts = get_option('daher_clinica_options', []);
    $agendar_url = !empty($clinica_opts['saas_agendamento']) ? $clinica_opts['saas_agendamento'] : 'https://agendamento.daherclinica.com/agendamento';
    $agendar_label = !empty($clinica_opts['saas_agendamento_label']) ? $clinica_opts['saas_agendamento_label'] : 'Agendamento Online';
?>
                
                    
                    
<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; max-width: 600px; margin: 0 auto; text-align: center; padding: 40px 20px; background: #f9f9f9; border-radius: 12px; border: 2px dashed #C5A880; box-sizing: border-box;">
                        <i class="fas fa-calendar-check" style="font-size: 48px; color: #C5A880; margin-bottom: 20px;"></i>
                        <h3 style="margin-bottom: 15px; color: #1A365D;"><?php echo esc_html($agendar_label); ?></h3>
                        <p style="margin-bottom: 30px; color: #666;">Evite filas e esperas. Escolha o melhor dia e horário para você diretamente em nosso sistema seguro.</p>
                        
                        <a href="<?php echo esc_url($agendar_url); ?>" class="btn btn-primary btn-agendar-header" style="font-size: 18px; padding: 15px 30px; border-radius: 30px; box-shadow: 0 10px 20px rgba(197, 168, 128, 0.3); width: 100%; max-width: 300px; display: inline-block; white-space: normal; line-height: 1.3;">
                            <i class="fas fa-rocket"></i> <?php echo esc_html($agendar_label); ?>
                        </a>
                    </div>


            </div>
        </div>
    </div>
</section>

