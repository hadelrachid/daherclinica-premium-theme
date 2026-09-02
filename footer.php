        </main>
        <?php 
            /* Recupera as páginas de privacidade e termos de uso para os links do rodapé */
            $privacidade_page = get_page_by_path('privacidade');
            $termos_page = get_page_by_path('termos-de-uso');
        ?>
        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="Daher Clínica" class="footer-logo-img" width="120" height="135">
                            <span class="footer-logo-text">Daher <span class="accent">Clínica</span></span>
                        </a>
                        <p><?php _e('Excelência médica, tecnologia avançada e cuidados que realçam sua saúde e autoestima.', 'daherclinica'); ?></p>
                        <div class="footer-social">
                            <?php 
                            $social_options = get_option('daher_social_options', []);
                            $whatsapp_options = get_option('daher_whatsapp_options', []);
                            
                            // Lista completa de redes sociais com ícones
                            $social_networks = [
                                'instagram' => ['label' => 'Instagram', 'icon' => 'fab fa-instagram', 'default' => 'https://www.instagram.com/daherclinica/'],
                                'facebook'  => ['label' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'default' => ''],
                                'youtube'   => ['label' => 'YouTube', 'icon' => 'fab fa-youtube', 'default' => ''],
                                'tiktok'    => ['label' => 'TikTok', 'icon' => 'fab fa-tiktok', 'default' => ''],
                                'twitter'   => ['label' => 'X (Twitter)', 'icon' => 'fab fa-x-twitter', 'default' => ''],
                                'linkedin'  => ['label' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'default' => ''],
                                'threads'   => ['label' => 'Threads', 'icon' => 'fab fa-threads', 'default' => ''],
                                'pinterest' => ['label' => 'Pinterest', 'icon' => 'fab fa-pinterest-p', 'default' => ''],
                                'telegram'  => ['label' => 'Telegram', 'icon' => 'fab fa-telegram-plane', 'default' => ''],
                                'twitch'    => ['label' => 'Twitch', 'icon' => 'fab fa-twitch', 'default' => ''],
                                'discord'   => ['label' => 'Discord', 'icon' => 'fab fa-discord', 'default' => ''],
                                'medium'    => ['label' => 'Medium', 'icon' => 'fab fa-medium-m', 'default' => ''],
                            ];
                            
                            foreach ($social_networks as $key => $network) {
                                $url = !empty($social_options[$key . '_url']) ? $social_options[$key . '_url'] : $network['default'];
                                if (!empty($url)) : 
                            ?>
                                <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($network['label']); ?>">
                                    <i class="<?php echo esc_attr($network['icon']); ?>"></i>
                                </a>
                            <?php 
                                endif;
                            } 
                            ?>
                            
                            <!-- WhatsApp (ícone separado) -->
                            <?php 
                            $whatsapp_clean = preg_replace('/[^0-9]/', '', $whatsapp_options['whatsapp_number'] ?? '5521977667676');
                            if ($whatsapp_clean) : 
                            ?>
                                <a href="https://wa.me/<?php echo esc_attr($whatsapp_clean); ?>?text=<?php echo urlencode('Olá! Acessei o site da clínica e gostaria de entrar em contato.'); ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="footer-links">
                        <h4><?php _e('Navegação', 'daherclinica'); ?></h4>
                        <?php
                        if (has_nav_menu('footer')) {
                            wp_nav_menu([
                                'theme_location' => 'footer',
                                'menu_class'     => '',
                                'container'      => false,
                                'fallback_cb'    => false,
                            ]);
                        } else {
                            echo '<ul>';
                            echo '<li><a href="' . esc_url(home_url('/')) . '">' . __('Início', 'daherclinica') . '</a></li>';
                            echo '<li><a href="#sobre">' . __('Sobre Nós', 'daherclinica') . '</a></li>';
                            echo '<li><a href="#especialidades">' . __('Especialidades', 'daherclinica') . '</a></li>';
                            echo '<li><a href="#equipe">' . __('Equipe Médica', 'daherclinica') . '</a></li>';
                            echo '<li><a href="' . esc_url(home_url('/blog')) . '">' . __('Blog', 'daherclinica') . '</a></li>';
                            echo '<li><a href="#contato">' . __('Contato', 'daherclinica') . '</a></li>';
                            echo '</ul>';
                        }
                        ?>
                    </div>
                    
                    <div class="footer-links">
                        <h4><?php _e('Institucional', 'daherclinica'); ?></h4>
                        <ul>
                            <li><a href="<?php echo $privacidade_page ? get_permalink($privacidade_page) : esc_url(home_url('/privacidade')); ?>"><?php _e('Política de Privacidade', 'daherclinica'); ?></a></li>
                            <li><a href="<?php echo $termos_page ? get_permalink($termos_page) : esc_url(home_url('/termos-de-uso')); ?>"><?php _e('Termos de Uso', 'daherclinica'); ?></a></li>
                            <li><a href="#" class="open-privacy-notice"><?php _e('LGPD', 'daherclinica'); ?></a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-newsletter">
                        <h4><?php _e('Newsletter', 'daherclinica'); ?></h4>
                        <p><?php _e('Receba dicas de saúde e novidades', 'daherclinica'); ?></p>
                        <form class="newsletter-form" id="newsletterForm" novalidate>
                            <input type="email" id="newsletterEmail" name="email" placeholder="<?php _e('Seu e-mail', 'daherclinica'); ?>" required aria-label="<?php _e('Seu e-mail', 'daherclinica'); ?>">
                            <button type="submit" aria-label="<?php _e('Assinar newsletter', 'daherclinica'); ?>"><i class="fas fa-paper-plane"></i></button>
                        </form>
                        <p class="newsletter-msg" id="newsletterMsg" style="display:none; font-size:0.8rem; margin-top:8px;"></p>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('Todos os direitos reservados.', 'daherclinica'); ?></p>
                    <p><?php _e('Desenvolvido por Rachid Daher', 'daherclinica'); ?></p>
                </div>
            </div>
        </footer>

        <!-- WhatsApp Float -->
        <?php 
        // Garante que $whatsapp_clean tenha valor mesmo se não foi definido acima
        if (empty($whatsapp_clean)) {
            $whatsapp_clean = preg_replace('/[^0-9]/', '', get_option('daher_whatsapp_options', [])['whatsapp_number'] ?? '5521977667676');
            if (empty($whatsapp_clean)) $whatsapp_clean = '5521977667676';
        }
        ?>
        <div class="whatsapp-float">
            <a href="https://wa.me/<?php echo esc_attr($whatsapp_clean); ?>?text=<?php echo urlencode('Olá! Acessei o site da clínica e gostaria de entrar em contato.'); ?>" target="_blank" class="whatsapp-btn" aria-label="<?php _e('Fale conosco pelo WhatsApp', 'daherclinica'); ?>">
                <i class="fab fa-whatsapp" aria-hidden="true"></i>
                <span class="whatsapp-tooltip"><?php _e('Fale conosco', 'daherclinica'); ?></span>
            </a>
        </div>

        <!-- Notificação de Privacidade (LGPD) -->
        <div id="privacyNotice" class="privacy-notice" style="display: none;">
            <div class="container privacy-container">
                <div class="privacy-content">
                    <p>
                        <?php _e('Utilizamos cookies para melhorar sua experiência. Ao continuar navegando, você concorda com nossa', 'daherclinica'); ?>
                        <a href="<?php echo $privacidade_page ? get_permalink($privacidade_page) : esc_url(home_url('/privacidade')); ?>"><?php _e('Política de Privacidade', 'daherclinica'); ?></a>
                        <?php _e('e', 'daherclinica'); ?>
                        <a href="<?php echo $termos_page ? get_permalink($termos_page) : esc_url(home_url('/termos-de-uso')); ?>"><?php _e('Termos de Uso', 'daherclinica'); ?></a>
                    </p>
                </div>
                <div class="privacy-actions">
                    <button type="button" id="acceptPrivacy" class="btn btn-primary btn-sm"><?php _e('Aceitar', 'daherclinica'); ?></button>
                    <button type="button" id="rejectPrivacy" class="btn btn-outline btn-sm"><?php _e('Recusar', 'daherclinica'); ?></button>
                </div>
            </div>
        </div>

        <!-- Carregamento Diferido do GTM (Performance) -->
        <script>
        (function() {
            var gtmLoaded = false;
            function loadGTM() {
                if (gtmLoaded) return;
                gtmLoaded = true;
                var script1 = document.createElement('script');
                script1.src = 'https://www.googletagmanager.com/gtag/js?id=AW-18319109877';
                script1.async = true;
                script1.setAttribute('data-cfasync', 'false');
                document.head.appendChild(script1);

                var script2 = document.createElement('script');
                script2.src = 'https://www.googletagmanager.com/gtag/js?id=G-C4RJ4QJB0V';
                script2.async = true;
                script2.setAttribute('data-cfasync', 'false');
                document.head.appendChild(script2);
            }
            // Carrega após primeira interação
            ['scroll', 'mousemove', 'touchstart', 'keydown'].forEach(function(e) {
                window.addEventListener(e, loadGTM, { once: true, passive: true });
            });
            // Ou após 5 segundos se não houver interação (fallback)
            setTimeout(loadGTM, 5000);
        })();
        </script>

        <?php wp_footer(); ?>
    </body>
    </html>
