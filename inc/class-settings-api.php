<?php
/**
 * Class SettingsAPI
 * 
 * Gerencia o painel de configurações do tema Daher Clínica
 * 
 * @package DaherClinica
 */

namespace DaherClinica;

class SettingsAPI {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);

        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        add_action('admin_post_reset_daher_settings', [$this, 'reset_settings_handler']);
    }

    /**
     * Handler para restaurar as configurações padrão
     */
    public function reset_settings_handler() {
        if (!current_user_can('manage_options') || !isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'reset_daher_settings_nonce')) {
            wp_die('Acesso negado.');
        }

        $options_to_delete = [
            'daher_clinica_options',
            'daher_social_options',
            'daher_map_options',
            'daher_whatsapp_options',
            'daher_home_options',
            'daher_especialidades_options',
            'daher_sobre_options',
            'daher_medicos_options'
        ];

        foreach ($options_to_delete as $option) {
            delete_option($option);
        }

        wp_redirect(admin_url('admin.php?page=daher-settings&reset=success'));
        exit;
    }



    /**
     * Sanitização: Mídia e SEO
     */
    public function sanitize_media($input) {
        $output = [];
        $fields = [
            'og_default_image', 'logo_principal',
            'hero_home_desktop', 'hero_home_mobile',
            'hero_sobre_desktop', 'hero_sobre_mobile',
            'hero_especialidades_desktop', 'hero_especialidades_mobile',
            'hero_contato_desktop', 'hero_contato_mobile',
            'hero_default_desktop', 'hero_default_mobile'
        ];
        
        foreach ($fields as $field) {
            $output[$field] = esc_url_raw($input[$field] ?? '');
        }
        
        return $output;
    }

    /**
     * Carregar scripts no admin
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'daher-settings') === false) {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_style('font-awesome-admin', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
        // wp_enqueue_style('daher-admin-style', get_template_directory_uri() . '/assets/css/admin.css', [], '1.0.0');
    }

    /**
     * Adicionar menu no admin
     */
    public function add_admin_menu() {
        add_menu_page(
            'Daher Clínica',
            'Daher Clínica',
            'manage_options',
            'daher-settings',
            [$this, 'settings_page'],
            'dashicons-heart',
            60
        );
        
        // Renomeia o primeiro item do submenu
        add_submenu_page(
            'daher-settings',
            'Configurações',
            'Configurações',
            'manage_options',
            'daher-settings',
            [$this, 'settings_page']
        );
        
        // Adiciona a página de Instruções / Leia-me
        add_submenu_page(
            'daher-settings',
            'Instruções (Leia-me)',
            'Leia-me',
            'manage_options',
            'daher-readme',
            [$this, 'readme_page']
        );
        

    }
    
    
    /**
     * Página de Instruções (Leia-me)
     */
    public function readme_page() {
        ?>
        <div class="wrap" style="max-width: 800px;">
            <h1>Instruções do Tema: Daher Clínica Premium</h1>
            <p>Bem-vindo ao painel do seu tema. Este tema foi construído sob medida com foco extremo em performance (Zero Plugins), acessibilidade e segurança.</p>
            
            <hr>
            
            <h2>1. Como alterar informações?</h2>
            <p>Vá na aba <strong>Configurações</strong> (neste mesmo menu) para alterar telefones, WhatsApp, links das redes sociais, texto sobre a clínica, adicionar/remover médicos e gerenciar os selos de especialidades.</p>
            
            <h2>2. Tamanhos das Imagens</h2>
            <p>O tema recorta as imagens automaticamente. Para melhor qualidade, tente enviar as imagens nas seguintes proporções:</p>
            <ul>
                <li><strong>Fotos de Médicos:</strong> Formato quadrado (ex: 800x800px).</li>
                <li><strong>Imagens do Consultório/Especialidades:</strong> Formato paisagem 4:3 (ex: 1200x900px).</li>
                <li>Sempre dê preferência ao formato <strong>.webp</strong> ou converta usando sites como o <em>Squoosh.app</em> antes de subir imagens para o blog.</li>
            </ul>
            
            <h2>3. Performance e Cache</h2>
            <p>Se você alterar o visual ou fizer alguma mudança via código no CSS/JS e não aparecer no celular dos pacientes, não se preocupe: o tema usa <em>Cache Busting Dinâmico</em>. Isso significa que o site avisa os celulares para atualizarem o visual sozinhos assim que um arquivo é salvo.</p>
            
            <h2>4. Segurança Adicional</h2>
            <p>O XML-RPC está desativado nativamente no código para evitar ataques de força bruta, e cabeçalhos avançados de CSP (Content-Security-Policy) protegem contra injeção de scripts maliciosos.</p>
            
            <div style="margin-top: 40px; padding: 20px; background: #fff; border-left: 4px solid #1A365D; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3>👨‍💻 Desenvolvedor</h3>
                <p><strong>Desenvolvido por:</strong> Hadel Rachid Daher Junior</p>
                <p>Feito sob medida com arquitetura "Zero Plugins" para máxima performance (100/100 no PageSpeed Insights) e conformidade WCAG.</p>
                <p>🔗 <strong>GitHub:</strong> <a href="https://github.com/hadelrachid" target="_blank">https://github.com/hadelrachid</a></p>
            </div>
        </div>
        <?php
    }

    /**
     * Registrar todas as configurações
     */
    public function register_settings() {        // ============================================
        // ABA 10: PÁGINAS LEGAIS
        // ============================================
        register_setting('daher_legal_group', 'daher_legal_options', [$this, 'sanitize_legal']);
        
        add_settings_section(
            'daher_legal_section',
            '⚖️ Páginas Legais e LGPD',
            null,
            'daher-legal'
        );
        
        add_settings_field(
            'privacy_page',
            'Página de Política de Privacidade',
            [$this, 'dropdown_pages_callback'],
            'daher-legal',
            'daher_legal_section',
            ['id' => 'privacy_page']
        );
        
        add_settings_field(
            'terms_page',
            'Página de Termos de Uso',
            [$this, 'dropdown_pages_callback'],
            'daher-legal',
            'daher_legal_section',
            ['id' => 'terms_page']
        );

        // ============================================
        // ABA 1: CLÍNICA
        // ============================================
        register_setting('daher_clinica_group', 'daher_clinica_options', [$this, 'sanitize_clinica']);
        
        add_settings_section(
            'daher_clinica_section',
            '🏥 Informações da Clínica',
            null,
            'daher-clinica'
        );
        
        add_settings_field(
            'daher_address',
            'Endereço',
            [$this, 'textarea_small_callback'],
            'daher-clinica',
            'daher_clinica_section',
            ['id' => 'daher_address', 'placeholder' => 'Endereço completo da clínica']
        );
        
        add_settings_field(
            'daher_hours',
            'Horário de Funcionamento',
            [$this, 'text_field_callback'],
            'daher-clinica',
            'daher_clinica_section',
            ['id' => 'daher_hours', 'placeholder' => 'Segunda a Sexta: 09:00 - 18:00']
        );
        
        add_settings_field(
            'daher_phone',
            'Telefone',
            [$this, 'text_field_callback'],
            'daher-clinica',
            'daher_clinica_section',
            ['id' => 'daher_phone', 'placeholder' => '(21) 2415-9263']
        );
        
        add_settings_field(
            'daher_email',
            'E-mail',
            [$this, 'text_field_callback'],
            'daher-clinica',
            'daher_clinica_section',
            ['id' => 'daher_email', 'placeholder' => 'contato@daherclinica.com']
        );
        
        // ============================================
        // ABA 2: CORPO CLÍNICO (MÉDICOS)
        // ============================================
        register_setting('daher_medicos_group', 'daher_medicos_options', [$this, 'sanitize_medicos']);
        
        add_settings_section(
            'daher_medicos_section',
            '👨‍⚕️ Corpo Clínico',
            [$this, 'medicos_section_callback'],
            'daher-medicos'
        );
        
        add_settings_field(
            'daher_medicos_list',
            'Gerenciar Médicos',
            [$this, 'medicos_field_callback'],
            'daher-medicos',
            'daher_medicos_section'
        );
        
        // ============================================
        // ABA 3: REDES SOCIAIS
        // ============================================
        register_setting('daher_social_group', 'daher_social_options', [$this, 'sanitize_social']);
        
        add_settings_section(
            'daher_social_section',
            '📱 Redes Sociais',
            [$this, 'social_section_callback'],
            'daher-social'
        );
        
        add_settings_field(
            'instagram_url',
            'Instagram',
            [$this, 'text_field_callback'],
            'daher-social',
            'daher_social_section',
            ['id' => 'instagram_url', 'placeholder' => 'https://instagram.com/...']
        );
        
        add_settings_field(
            'facebook_url',
            'Facebook',
            [$this, 'text_field_callback'],
            'daher-social',
            'daher_social_section',
            ['id' => 'facebook_url', 'placeholder' => 'https://facebook.com/...']
        );
        
        add_settings_field(
            'youtube_url',
            'YouTube',
            [$this, 'text_field_callback'],
            'daher-social',
            'daher_social_section',
            ['id' => 'youtube_url', 'placeholder' => 'https://youtube.com/...']
        );
        
        // ============================================
        // ABA 4: MAPA E ENDEREÇO
        // ============================================
        register_setting('daher_map_group', 'daher_map_options', [$this, 'sanitize_map']);
        
        add_settings_section(
            'daher_map_section',
            '🗺️ Mapa e Endereço da Clínica',
            [$this, 'map_section_callback'],
            'daher-map'
        );
        
        add_settings_field(
            'map_iframe',
            'Iframe do Google Maps',
            [$this, 'textarea_small_callback'],
            'daher-map',
            'daher_map_section',
            ['id' => 'map_iframe', 'placeholder' => '<iframe src="..." ...></iframe>']
        );
        
        // ============================================
        // ABA 5: WHATSAPP
        // ============================================
        register_setting('daher_whatsapp_group', 'daher_whatsapp_options', [$this, 'sanitize_whatsapp']);
        
        add_settings_section(
            'daher_whatsapp_section',
            '💬 WhatsApp',
            null,
            'daher-whatsapp'
        );
        
        add_settings_field(
            'whatsapp_number',
            'Número do WhatsApp',
            [$this, 'phone_field_callback'],
            'daher-whatsapp',
            'daher_whatsapp_section',
            ['id' => 'whatsapp_number', 'placeholder' => '5521977667676']
        );
        
        add_settings_field(
            'whatsapp_message',
            'Mensagem Padrão',
            [$this, 'textarea_small_callback'],
            'daher-whatsapp',
            'daher_whatsapp_section',
            ['id' => 'whatsapp_message', 'placeholder' => 'Olá! Gostaria de agendar uma consulta.']
        );

        // ============================================
        // ABA 6: HOME (Página Inicial)
        // ============================================
        register_setting('daher_home_group', 'daher_home_options', [$this, 'sanitize_home']);

        add_settings_section(
            'daher_home_hero_section',
            '🌟 Hero Section',
            null,
            'daher-home'
        );

        add_settings_field(
            'hero_title',
            'Título Principal',
            [$this, 'text_field_callback'],
            'daher-home',
            'daher_home_hero_section',
            ['id' => 'hero_title', 'placeholder' => 'Cuidado Especializado para Sua Saúde e Beleza']
        );

        add_settings_field(
            'hero_subtitle',
            'Subtítulo',
            [$this, 'textarea_small_callback'],
            'daher-home',
            'daher_home_hero_section',
            ['id' => 'hero_subtitle', 'placeholder' => 'Unimos Cirurgia Vascular e Dermatologia...']
        );

        // Stats Section
        add_settings_section(
            'daher_home_stats_section',
            '📊 Stats (Números da Clínica)',
            null,
            'daher-home'
        );

        add_settings_field(
            'stat_years',
            'Anos de Excelência',
            [$this, 'text_field_callback'],
            'daher-home',
            'daher_home_stats_section',
            ['id' => 'stat_years', 'placeholder' => '+20']
        );

        add_settings_field(
            'stat_years_label',
            'Rótulo - Anos',
            [$this, 'text_field_callback'],
            'daher-home',
            'daher_home_stats_section',
            ['id' => 'stat_years_label', 'placeholder' => 'Anos de Excelência']
        );

        add_settings_field(
            'stat_patients',
            'Pacientes Atendidos',
            [$this, 'text_field_callback'],
            'daher-home',
            'daher_home_stats_section',
            ['id' => 'stat_patients', 'placeholder' => '+5.000']
        );

        add_settings_field(
            'stat_patients_label',
            'Rótulo - Pacientes',
            [$this, 'text_field_callback'],
            'daher-home',
            'daher_home_stats_section',
            ['id' => 'stat_patients_label', 'placeholder' => 'Pacientes Atendidos']
        );

        add_settings_field(
            'stat_team',
            'Equipe Especializada',
            [$this, 'text_field_callback'],
            'daher-home',
            'daher_home_stats_section',
            ['id' => 'stat_team', 'placeholder' => '100%']
        );

        add_settings_field(
            'stat_team_label',
            'Rótulo - Equipe',
            [$this, 'text_field_callback'],
            'daher-home',
            'daher_home_stats_section',
            ['id' => 'stat_team_label', 'placeholder' => 'Equipe Especializada']
        );

        // About Section
        add_settings_section(
            'daher_home_about_section',
            '📖 Sobre Section',
            null,
            'daher-home'
        );

        add_settings_field(
            'about_title',
            'Título da Seção Sobre',
            [$this, 'text_field_callback'],
            'daher-home',
            'daher_home_about_section',
            ['id' => 'about_title', 'placeholder' => 'Cuidado Integral para o seu Bem-Estar']
        );

        add_settings_field(
            'about_text_1',
            'Texto Principal',
            [$this, 'textarea_small_callback'],
            'daher-home',
            'daher_home_about_section',
            ['id' => 'about_text_1']
        );

        add_settings_field(
            'about_text_2',
            'Texto Secundário',
            [$this, 'textarea_small_callback'],
            'daher-home',
            'daher_home_about_section',
            ['id' => 'about_text_2']
        );

        // Specialties Cards (Repeater)
        add_settings_section(
            'daher_home_specialties_section',
            '🃏 Cards de Especialidades',
            [$this, 'specialties_section_callback'],
            'daher-home'
        );

        add_settings_field(
            'specialties_cards',
            'Gerenciar Cards',
            [$this, 'specialties_field_callback'],
            'daher-home',
            'daher_home_specialties_section'
        );

        // ============================================
        // ABA 7: ESPECIALIDADES
        // ============================================
        register_setting('daher_especialidades_group', 'daher_especialidades_options', [$this, 'sanitize_especialidades']);

        add_settings_section(
            'daher_especialidades_hero_section',
            '🌟 Hero da Página Especialidades',
            null,
            'daher-especialidades'
        );

        add_settings_field(
            'especialidades_hero_title',
            'Título Principal',
            [$this, 'text_field_callback'],
            'daher-especialidades',
            'daher_especialidades_hero_section',
            ['id' => 'especialidades_hero_title', 'placeholder' => 'Nossas Especialidades']
        );

        add_settings_field(
            'especialidades_hero_subtitle',
            'Subtítulo',
            [$this, 'textarea_small_callback'],
            'daher-especialidades',
            'daher_especialidades_hero_section',
            ['id' => 'especialidades_hero_subtitle', 'placeholder' => 'Tratamentos avançados com tecnologia de ponta e equipe especializada']
        );

        // Seção de Tecnologias
        add_settings_section(
            'daher_especialidades_tech_section',
            '🔬 Tecnologias Utilizadas',
            null,
            'daher-especialidades'
        );

        add_settings_field(
            'tech_title',
            'Título da Seção Tecnologias',
            [$this, 'text_field_callback'],
            'daher-especialidades',
            'daher_especialidades_tech_section',
            ['id' => 'tech_title', 'placeholder' => 'Equipamentos de Última Geração']
        );

        add_settings_field(
            'tech_subtitle',
            'Subtítulo da Seção Tecnologias',
            [$this, 'textarea_small_callback'],
            'daher-especialidades',
            'daher_especialidades_tech_section',
            ['id' => 'tech_subtitle', 'placeholder' => 'Investimos continuamente em tecnologia para oferecer tratamentos mais eficazes...']
        );

        add_settings_field(
            'tech_layout',
            'Layout dos Cards (Grade)',
            [$this, 'select_layout_callback'],
            'daher-especialidades',
            'daher_especialidades_tech_section',
            ['id' => 'tech_layout', 'options' => ['simple' => 'Padrão (3 colunas)', 'expanded' => 'Expandido (2 colunas)', 'compact' => 'Compacto (4 colunas)', 'centered' => 'Centralizado']]
        );

        // Repeater de Tecnologias
        add_settings_field(
            'tech_items',
            'Lista de Tecnologias',
            [$this, 'tech_items_field_callback'],
            'daher-especialidades',
            'daher_especialidades_tech_section'
        );

        // Seção de Diferenciais
        add_settings_section(
            'daher_especialidades_diff_section',
            '⭐ Diferenciais da Clínica',
            null,
            'daher-especialidades'
        );

        add_settings_field(
            'diff_title',
            'Título dos Diferenciais',
            [$this, 'text_field_callback'],
            'daher-especialidades',
            'daher_especialidades_diff_section',
            ['id' => 'diff_title', 'placeholder' => 'Nossos Diferenciais']
        );

        add_settings_field(
            'diff_layout',
            'Layout dos Cards (Grade)',
            [$this, 'select_layout_callback'],
            'daher-especialidades',
            'daher_especialidades_diff_section',
            ['id' => 'diff_layout', 'options' => ['simple' => 'Padrão (3 colunas)', 'expanded' => 'Expandido (2 colunas)', 'compact' => 'Compacto (4 colunas)', 'centered' => 'Centralizado']]
        );

        // Repeater de Diferenciais
        add_settings_field(
            'diff_items',
            'Lista de Diferenciais',
            [$this, 'diff_items_field_callback'],
            'daher-especialidades',
            'daher_especialidades_diff_section'
        );


        // ============================================
        // ABA 8: SOBRE
        // ============================================
        register_setting('daher_sobre_group', 'daher_sobre_options', [$this, 'sanitize_sobre']);

        add_settings_section(
            'daher_sobre_hero_section',
            '🌟 Hero da Página Sobre',
            null,
            'daher-sobre'
        );

        add_settings_field(
            'sobre_hero_title',
            'Título Principal',
            [$this, 'text_field_callback'],
            'daher-sobre',
            'daher_sobre_hero_section',
            ['id' => 'sobre_hero_title', 'placeholder' => 'Uma História de Excelência e Cuidado']
        );

        add_settings_field(
            'sobre_hero_subtitle',
            'Subtítulo',
            [$this, 'textarea_small_callback'],
            'daher-sobre',
            'daher_sobre_hero_section',
            ['id' => 'sobre_hero_subtitle', 'placeholder' => 'Há mais de 20 anos transformando vidas...']
        );

        // Seção de História
        add_settings_section(
            'daher_sobre_history_section',
            '📜 Nossa História',
            null,
            'daher-sobre'
        );

        add_settings_field(
            'history_title',
            'Título da História',
            [$this, 'text_field_callback'],
            'daher-sobre',
            'daher_sobre_history_section',
            ['id' => 'history_title', 'placeholder' => 'Daher Clínica: Cuidado que Transforma']
        );

        add_settings_field(
            'history_text_1',
            'Texto Principal da História',
            [$this, 'textarea_small_callback'],
            'daher-sobre',
            'daher_sobre_history_section',
            ['id' => 'history_text_1']
        );

        add_settings_field(
            'history_text_2',
            'Texto Secundário da História',
            [$this, 'textarea_small_callback'],
            'daher-sobre',
            'daher_sobre_history_section',
            ['id' => 'history_text_2']
        );

        add_settings_field(
            'history_text_3',
            'Texto Terciário da História',
            [$this, 'textarea_small_callback'],
            'daher-sobre',
            'daher_sobre_history_section',
            ['id' => 'history_text_3']
        );

        // Seção de Valores
        add_settings_section(
            'daher_sobre_values_section',
            '💎 Nossos Valores',
            null,
            'daher-sobre'
        );

        add_settings_field(
            'values_title',
            'Título da Seção Valores',
            [$this, 'text_field_callback'],
            'daher-sobre',
            'daher_sobre_values_section',
            ['id' => 'values_title', 'placeholder' => 'O que nos Move']
        );

        add_settings_field(
            'values_subtitle',
            'Subtítulo da Seção Valores',
            [$this, 'textarea_small_callback'],
            'daher-sobre',
            'daher_sobre_values_section',
            ['id' => 'values_subtitle', 'placeholder' => 'Valores que guiam nossa atuação diária...']
        );

        // Repeater de Valores
        add_settings_field(
            'values_items',
            'Lista de Valores',
            [$this, 'values_items_field_callback'],
            'daher-sobre',
            'daher_sobre_values_section'
        );

        // ============================================
        // ABA 9: MÍDIA & SEO
        // ============================================
        $this->register_media_options();

    }
    
    /**
     * Registra opções da Aba Mídia & SEO
     */
    private function register_media_options() {
        register_setting('daher_media_group', 'daher_media_options', [$this, 'sanitize_media']);
        
        add_settings_section(
            'daher_media_og_section',
            '🌐 Imagem de Compartilhamento (SEO & Redes Sociais)',
            [$this, 'media_og_section_callback'],
            'daher-media'
        );

        add_settings_field(
            'og_default_image',
            'Imagem Padrão (WhatsApp / Google)',
            [$this, 'image_upload_callback'],
            'daher-media',
            'daher_media_og_section',
            ['id' => 'og_default_image', 'description' => 'Aparece quando envia o link do site no WhatsApp. Recomendado: 1200x630px.']
        );

        add_settings_field(
            'logo_principal',
            'Logomarca Principal',
            [$this, 'image_upload_callback'],
            'daher-media',
            'daher_media_og_section',
            ['id' => 'logo_principal', 'description' => 'Logomarca exibida no cabeçalho do site e no Google (Esquema de Organização).']
        );

        add_settings_section(
            'daher_media_hero_section',
            '🖼️ Banners de Capa (Hero)',
            [$this, 'media_hero_section_callback'],
            'daher-media'
        );

        // Home
        add_settings_field('hero_home_desktop', 'Home (Desktop)', [$this, 'image_upload_callback'], 'daher-media', 'daher_media_hero_section', ['id' => 'hero_home_desktop', 'description' => 'Recomendado: 1920x1080px.']);
        add_settings_field('hero_home_mobile', 'Home (Mobile)', [$this, 'image_upload_callback'], 'daher-media', 'daher_media_hero_section', ['id' => 'hero_home_mobile', 'description' => 'Recomendado: 768x1024px.']);

        // Sobre
        add_settings_field('hero_sobre_desktop', 'Sobre Nós (Desktop)', [$this, 'image_upload_callback'], 'daher-media', 'daher_media_hero_section', ['id' => 'hero_sobre_desktop', 'description' => 'Recomendado: 1920x600px.']);
        add_settings_field('hero_sobre_mobile', 'Sobre Nós (Mobile)', [$this, 'image_upload_callback'], 'daher-media', 'daher_media_hero_section', ['id' => 'hero_sobre_mobile', 'description' => 'Recomendado: 768x600px.']);

        // Especialidades
        add_settings_field('hero_especialidades_desktop', 'Especialidades (Desktop)', [$this, 'image_upload_callback'], 'daher-media', 'daher_media_hero_section', ['id' => 'hero_especialidades_desktop', 'description' => 'Recomendado: 1920x600px.']);
        add_settings_field('hero_especialidades_mobile', 'Especialidades (Mobile)', [$this, 'image_upload_callback'], 'daher-media', 'daher_media_hero_section', ['id' => 'hero_especialidades_mobile', 'description' => 'Recomendado: 768x600px.']);

        // Contato
        add_settings_field('hero_contato_desktop', 'Contato (Desktop)', [$this, 'image_upload_callback'], 'daher-media', 'daher_media_hero_section', ['id' => 'hero_contato_desktop', 'description' => 'Recomendado: 1920x600px.']);
        add_settings_field('hero_contato_mobile', 'Contato (Mobile)', [$this, 'image_upload_callback'], 'daher-media', 'daher_media_hero_section', ['id' => 'hero_contato_mobile', 'description' => 'Recomendado: 768x600px.']);

        // Default/Fallback
        add_settings_field('hero_default_desktop', 'Padrão Internas (Desktop)', [$this, 'image_upload_callback'], 'daher-media', 'daher_media_hero_section', ['id' => 'hero_default_desktop', 'description' => 'Usada no Blog e páginas sem capa específica.']);
        add_settings_field('hero_default_mobile', 'Padrão Internas (Mobile)', [$this, 'image_upload_callback'], 'daher-media', 'daher_media_hero_section', ['id' => 'hero_default_mobile', 'description' => 'Mobile para páginas sem capa.']);
    }

    /**
     * Retorna opções da clínica
     */
    private function get_clinica_options() {
        return get_option('daher_clinica_options', [
            'daher_address' => 'Estrada dos Bandeirantes, 8591 - Sala 308, Rio de Janeiro - RJ',
            'daher_hours' => 'Segunda a Sexta: 09:00 - 18:00',
            'daher_phone' => '(21) 2415-9263',
            'daher_email' => 'contato@daherclinica.com'
        ]);
    }
    
    /**
     * Retorna opções sociais
     */
    private function get_social_options() {
        return get_option('daher_social_options', [
            'instagram_url' => 'https://www.instagram.com/daherclinica/',
            'facebook_url' => '',
            'youtube_url' => '',
            'tiktok_url' => '',
            'twitter_url' => '',
            'linkedin_url' => '',
            'threads_url' => '',
            'pinterest_url' => '',
            'telegram_url' => '',
            'twitch_url' => '',
            'discord_url' => '',
            'medium_url' => '',
        ]);
    }
    
    /**
     * Retorna opções do mapa
     */
    private function get_map_options() {
        return get_option('daher_map_options', [
            'map_latitude' => '-22.9744918',
            'map_longitude' => '-43.4165382',
            'map_zoom' => '15'
        ]);
    }
    
    /**
     * Retorna opções do WhatsApp
     */
    private function get_whatsapp_options() {
        return get_option('daher_whatsapp_options', [
            'whatsapp_number' => '5521977667676',
            'whatsapp_message' => 'Olá! Gostaria de agendar uma consulta.'
        ]);
    }

    // ============================================
    // CALLBACKS DAS SEÇÕES
    // ============================================
    
    public function medicos_section_callback() {
        echo '<div style="background: #e8f4f4; padding: 15px; border-radius: 8px; margin: 10px 0;">';
        echo '<p><i class="fas fa-stethoscope"></i> <strong>Gerenciar Médicos</strong></p>';
        echo '<p>Adicione, edite ou remova médicos do corpo clínico. Eles aparecerão automaticamente na seção "Equipe" do site.</p>';
        echo '<p>💡 <strong>Dica:</strong> Cada médico pode ter: Nome, CRM, Especialidade, Foto, Rede Social e WhatsApp próprio.</p>';
        echo '</div>';
    }
    
    public function social_section_callback() {
        echo '<p>Configure as URLs das suas redes sociais. Deixe em branco as que não quiser exibir.</p>';
    }
    
    public function map_section_callback() {
        echo '<p>Configure o endereço e a localização da clínica. Recomendamos usar as coordenadas (Latitude/Longitude) para o link do mapa.</p>';
    }
    
    public function media_og_section_callback() {
        echo '<p>Defina a imagem de apresentação do site. Esta imagem aparecerá quando o link do site for compartilhado no <strong>WhatsApp, Facebook, LinkedIn</strong> ou exibido no <strong>Google</strong>.</p>';
    }

    public function media_hero_section_callback() {
        echo '<p>Substitua as imagens de fundo do topo do site. Você pode definir imagens específicas para Desktop e Mobile. Se deixar vazio, o site usará as imagens originais do tema.</p>';
    }

    // ============================================
    // CAMPOS DE FORMULÁRIO
    // ============================================

    public function text_field_callback($args) {
        $options = $this->get_options_by_context($args['id']);
        $value = isset($options[$args['id']]) ? $options[$args['id']] : '';
        $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
        echo '<input type="text" id="' . esc_attr($args['id']) . '" name="' . esc_attr($this->get_option_name($args['id'])) . '[' . esc_attr($args['id']) . ']" value="' . esc_attr($value) . '" class="regular-text" placeholder="' . esc_attr($placeholder) . '" />';
    }

    public function textarea_small_callback($args) {
        $options = $this->get_options_by_context($args['id']);
        $value = $options[$args['id']] ?? '';
        
        echo '<textarea name="' . esc_attr($this->get_option_name($args['id'])) . '[' . esc_attr($args['id']) . ']" rows="3" style="max-width: 600px; width: 100%;" placeholder="' . esc_attr($args['placeholder'] ?? '') . '">' . esc_textarea($value) . '</textarea>';
    }

    public function select_layout_callback($args) {
        $options = $this->get_options_by_context($args['id']);
        $value = $options[$args['id']] ?? 'simple';
        
        echo '<select name="' . esc_attr($this->get_option_name($args['id'])) . '[' . esc_attr($args['id']) . ']" style="max-width: 400px; width: 100%;">';
        foreach ($args['options'] as $val => $label) {
            echo '<option value="' . esc_attr($val) . '" ' . selected($value, $val, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }

    public function phone_field_callback($args) {
        $options = $this->get_options_by_context($args['id']);
        $value = isset($options[$args['id']]) ? $options[$args['id']] : '';
        $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
        
        // Formata para exibição no campo do painel
        $display_value = function_exists('daherclinica_format_phone') ? daherclinica_format_phone($value) : $value;
        
        echo '<input type="text" id="' . esc_attr($args['id']) . '" name="' . esc_attr($this->get_option_name($args['id'])) . '[' . esc_attr($args['id']) . ']" value="' . esc_attr($display_value) . '" class="regular-text" placeholder="' . esc_attr($placeholder) . '" />';
        echo '<p class="description">Ex: (21) 97766-7676. O sistema salvará apenas os números.</p>';
    }

    /**
     * Componente: Campo de Upload de Imagem Simples
     */
    public function image_upload_callback($args) {
        $options = $this->get_options_by_context($args['id']);
        $value = $options[$args['id']] ?? '';
        $name = esc_attr($this->get_option_name($args['id'])) . '[' . esc_attr($args['id']) . ']';
        
        echo '<div class="daher-image-uploader">';
        echo '<div class="image-preview-wrapper" style="margin-bottom: 10px;">';
        if ($value) {
            echo '<img src="' . esc_url($value) . '" style="max-width: 300px; max-height: 150px; border: 1px solid #ccc; padding: 3px; background: #fff;" alt="Preview">';
        } else {
            echo '<img src="" style="max-width: 300px; max-height: 150px; display: none;" alt="Preview">';
            echo '<div class="no-image-placeholder" style="width: 300px; height: 100px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; color: #999; margin-bottom: 10px;">Sem Imagem (Fallback do Tema)</div>';
        }
        echo '</div>';
        echo '<input type="hidden" name="' . $name . '" value="' . esc_attr($value) . '" class="image-url-input">';
        echo '<button type="button" class="button upload-image-btn">Selecionar Imagem</button> ';
        echo '<button type="button" class="button remove-image-btn" ' . (empty($value) ? 'style="display:none;"' : '') . '>Remover</button>';
        
        if (isset($args['description'])) {
            echo '<p class="description" style="margin-top: 5px;">' . esc_html($args['description']) . '</p>';
        }
        echo '</div>';
    }

    public function medicos_field_callback() {
        $medicos = get_option('daher_medicos_options', []);
        ?>
        <div id="daher-medicos-repeater">
            <?php if (!empty($medicos)) : ?>
                <?php foreach ($medicos as $index => $medico) : ?>
                    <div class="daher-medico-item" style="background: #f5f5f5; padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #1A365D;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <strong style="color: #1A365D;">👨‍⚕️ Médico #<?php echo $index + 1; ?></strong>
                            <button type="button" class="button daher-remove-medico" style="background: #f87171; color: white; border-color: #f87171;">🗑️ Remover</button>
                        </div>
                        
                        <!-- Preview da foto -->
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-weight: 600; margin-bottom: 5px;">Foto do Médico</label>
                            <div id="daher_medicos_<?php echo $index; ?>_foto-preview" style="margin-bottom: 10px;">
                                <?php if (!empty($medico['foto'])) : ?>
                                    <img src="<?php echo esc_url($medico['foto']); ?>" style="width:100px; height:100px; border-radius:50%; object-fit:cover; border: 2px solid #1A365D;" />
                                <?php else : ?>
                                    <div style="width:100px; height:100px; background:#e2e8f0; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#64748B;"><i class="fas fa-user-md fa-3x"></i></div>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="daher_medicos_options[<?php echo $index; ?>][foto]" id="daher_medicos_<?php echo $index; ?>_foto" value="<?php echo esc_attr($medico['foto'] ?? ''); ?>" />
                            <button type="button" class="button daher-upload-medico-btn" data-target="daher_medicos_<?php echo $index; ?>_foto" data-preview="daher_medicos_<?php echo $index; ?>_foto-preview">📷 Selecionar Foto</button>
                            <button type="button" class="button daher-clear-medico-btn" data-target="daher_medicos_<?php echo $index; ?>_foto" data-preview="daher_medicos_<?php echo $index; ?>_foto-preview" style="margin-left: 5px;">🗑️ Remover</button>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div>
                                <label style="display: block; font-weight: 600;">Nome completo *</label>
                                <input type="text" name="daher_medicos_options[<?php echo $index; ?>][nome]" value="<?php echo esc_attr($medico['nome'] ?? ''); ?>" class="regular-text" style="width: 100%;" />
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600;">CRM / Registro</label>
                                <input type="text" name="daher_medicos_options[<?php echo $index; ?>][crm]" value="<?php echo esc_attr($medico['crm'] ?? ''); ?>" class="regular-text" style="width: 100%;" />
                            </div>
                        </div>
                        
                        <div style="margin-top: 10px;">
                            <label style="display: block; font-weight: 600;">Especialidade *</label>
                            <input type="text" name="daher_medicos_options[<?php echo $index; ?>][especialidade]" value="<?php echo esc_attr($medico['especialidade'] ?? ''); ?>" class="regular-text" style="width: 100%;" />
                        </div>
                        
                        <div style="margin-top: 10px;">
                            <label style="display: block; font-weight: 600;">URL do Instagram (opcional)</label>
                            <input type="url" name="daher_medicos_options[<?php echo $index; ?>][instagram]" value="<?php echo esc_url($medico['instagram'] ?? ''); ?>" class="regular-text" style="width: 100%;" placeholder="https://instagram.com/..." />
                        </div>
                        
                        <div style="margin-top: 10px;">
                            <label style="display: block; font-weight: 600;">Tags / Expertise (separe por vírgula)</label>
                            <input type="text" name="daher_medicos_options[<?php echo $index; ?>][tags]" value="<?php echo esc_attr($medico['tags'] ?? ''); ?>" class="regular-text" style="width: 100%;" placeholder="Ex: Tratamento de Varizes, Trombose, Check-up" />
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px;">
                            <div>
                                <label style="display: block; font-weight: 600;">Bio / Descrição</label>
                                <textarea name="daher_medicos_options[<?php echo $index; ?>][bio]" rows="2" style="width: 100%;"><?php echo esc_textarea($medico['bio'] ?? ''); ?></textarea>
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600;">Ordem de Exibição (Nº)</label>
                                <input type="number" name="daher_medicos_options[<?php echo $index; ?>][ordem]" value="<?php echo esc_attr($medico['ordem'] ?? '0'); ?>" class="small-text" style="width: 100%;" min="0" step="1" />
                                <p class="description">Menores números aparecem primeiro.</p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <button type="button" id="daher-add-medico" class="button" style="margin-top: 10px; background: #1A365D; color: white; border-color: #1A365D;">
            <i class="fas fa-plus-circle"></i> + Adicionar Médico
        </button>
        <?php
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================
    
    private function get_options_by_context($field_id) {
        if (in_array($field_id, ['og_default_image', 'logo_principal', 'hero_home_desktop', 'hero_home_mobile', 'hero_sobre_desktop', 'hero_sobre_mobile', 'hero_especialidades_desktop', 'hero_especialidades_mobile', 'hero_contato_desktop', 'hero_contato_mobile', 'hero_default_desktop', 'hero_default_mobile'])) {
            return get_option('daher_media_options', []);
        }
        if (in_array($field_id, ['daher_address', 'daher_hours', 'daher_phone', 'daher_email'])) {
            return $this->get_clinica_options();
        }
        if (in_array($field_id, ['instagram_url', 'facebook_url', 'youtube_url'])) {
            return $this->get_social_options();
        }
        if (strpos($field_id, 'map_') === 0) {
            return $this->get_map_options();
        }
        if (in_array($field_id, ['whatsapp_number', 'whatsapp_message'])) {
            return $this->get_whatsapp_options();
        }
        if (in_array($field_id, ['hero_title', 'hero_subtitle', 'stat_years', 'stat_years_label', 'stat_patients', 'stat_patients_label', 'stat_team', 'stat_team_label', 'about_title', 'about_text_1', 'about_text_2'])) {
            return get_option('daher_home_options', []);
        }
        if (in_array($field_id, ['especialidades_hero_title', 'especialidades_hero_subtitle', 'tech_title', 'tech_subtitle', 'tech_layout', 'diff_title', 'diff_layout'])) {
            return get_option('daher_especialidades_options', []);
        }
        if (in_array($field_id, ['sobre_hero_title', 'sobre_hero_subtitle', 'history_title', 'history_text_1', 'history_text_2', 'history_text_3', 'values_title', 'values_subtitle'])) {
            return get_option('daher_sobre_options', []);
        }
        return [];
    }
    
    private function get_option_name($field_id) {
        if (in_array($field_id, ['og_default_image', 'logo_principal', 'hero_home_desktop', 'hero_home_mobile', 'hero_sobre_desktop', 'hero_sobre_mobile', 'hero_especialidades_desktop', 'hero_especialidades_mobile', 'hero_contato_desktop', 'hero_contato_mobile', 'hero_default_desktop', 'hero_default_mobile'])) {
            return 'daher_media_options';
        }
        if (in_array($field_id, ['daher_address', 'daher_hours', 'daher_phone', 'daher_email'])) {
            return 'daher_clinica_options';
        }
        if (in_array($field_id, ['instagram_url', 'facebook_url', 'youtube_url'])) {
            return 'daher_social_options';
        }
        if (strpos($field_id, 'map_') === 0) {
            return 'daher_map_options';
        }
        if (in_array($field_id, ['whatsapp_number', 'whatsapp_message'])) {
            return 'daher_whatsapp_options';
        }
        if (in_array($field_id, ['hero_title', 'hero_subtitle', 'stat_years', 'stat_years_label', 'stat_patients', 'stat_patients_label', 'stat_team', 'stat_team_label', 'about_title', 'about_text_1', 'about_text_2'])) {
            return 'daher_home_options';
        }
        if (in_array($field_id, ['especialidades_hero_title', 'especialidades_hero_subtitle', 'tech_title', 'tech_subtitle', 'tech_layout', 'diff_title', 'diff_layout'])) {
            return 'daher_especialidades_options';
        }
        if (in_array($field_id, ['sobre_hero_title', 'sobre_hero_subtitle', 'history_title', 'history_text_1', 'history_text_2', 'history_text_3', 'values_title', 'values_subtitle'])) {
            return 'daher_sobre_options';
        }
        return '';
    }

    // ============================================
    // SANITIZAÇÃO
    // ============================================
    
    public function sanitize_clinica($input) {
        $output = [];
        $output['daher_address'] = sanitize_textarea_field($input['daher_address'] ?? '');
        $output['daher_hours'] = sanitize_text_field($input['daher_hours'] ?? '');
        $output['daher_phone'] = sanitize_text_field($input['daher_phone'] ?? '');
        $output['daher_email'] = sanitize_email($input['daher_email'] ?? '');
        return $output;
    }
    
    public function sanitize_social($input) {
        $output = [];
        
        $social_keys = ['instagram', 'facebook', 'youtube', 'tiktok', 'twitter', 'linkedin', 'threads', 'pinterest', 'telegram', 'twitch', 'discord', 'medium'];
        
        foreach ($social_keys as $key) {
            $output[$key . '_url'] = esc_url_raw($input[$key . '_url'] ?? '');
        }
        
        return $output;
    }
    
    public function sanitize_map($input) {
        $output = [];
        if (isset($input['map_iframe'])) {
            // Permite o HTML do iframe
            $output['map_iframe'] = $input['map_iframe'];
        }

        return $output;
    }
    
    public function sanitize_whatsapp($input) {
        $output = [];
        $number = preg_replace('/[^0-9]/', '', $input['whatsapp_number'] ?? '');
        
        // Se o usuário digitou 10 ou 11 dígitos (sem o 55), adicionamos o 55 automaticamente
        if (strlen($number) === 10 || strlen($number) === 11) {
            $number = '55' . $number;
        }
        
        $output['whatsapp_number'] = $number;
        $output['whatsapp_message'] = sanitize_textarea_field($input['whatsapp_message'] ?? '');
        return $output;
    }
    
    public function sanitize_medicos($input) {
        if (!is_array($input)) {
            return [];
        }
        $sanitized = [];
        foreach ($input as $medico) {
            if (!empty($medico['nome']) && !empty($medico['especialidade'])) {
                $sanitized[] = [
                    'nome' => sanitize_text_field($medico['nome']),
                    'crm' => sanitize_text_field($medico['crm'] ?? ''),
                    'especialidade' => sanitize_text_field($medico['especialidade']),
                    'foto' => esc_url_raw($medico['foto'] ?? ''),
                    'instagram' => esc_url_raw($medico['instagram'] ?? ''),
                    'bio' => sanitize_textarea_field($medico['bio'] ?? ''),
                    'tags' => sanitize_text_field($medico['tags'] ?? ''),
                    'ordem' => isset($medico['ordem']) ? intval($medico['ordem']) : 0
                ];
            }
        }

        // Ordena o array antes de salvar, para que o painel reflita a ordem correta
        if (!empty($sanitized)) {
            usort($sanitized, function($a, $b) {
                return $a['ordem'] <=> $b['ordem'];
            });
        }

        return $sanitized;
    }

    // ============================================
    // PÁGINA DE CONFIGURAÇÕES COM ABAS FUNCIONAIS
    // ============================================

    public function settings_page() {
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'clinica';
        ?>
        <div class="wrap">
            <h1>🏥 Daher Clínica - Configurações</h1>
            
            <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success') : ?>
                <div class="notice notice-success is-dismissible" style="border-left-color: #C5A880;">
                    <p><strong>✅ Tudo certo!</strong> Todas as configurações foram restauradas para os padrões de fábrica com sucesso.</p>
                </div>
            <?php endif; ?>
            
            <h2 class="nav-tab-wrapper">
                <a href="?page=daher-settings&tab=clinica" class="nav-tab <?php echo $active_tab == 'clinica' ? 'nav-tab-active' : ''; ?>">
                    🏥 Clínica
                </a>
                <a href="?page=daher-settings&tab=medicos" class="nav-tab <?php echo $active_tab == 'medicos' ? 'nav-tab-active' : ''; ?>">
                    👨‍⚕️ Corpo Clínico
                </a>
                <a href="?page=daher-settings&tab=social" class="nav-tab <?php echo $active_tab == 'social' ? 'nav-tab-active' : ''; ?>">
                    📱 Redes Sociais
                </a>
                <a href="?page=daher-settings&tab=mapa" class="nav-tab <?php echo $active_tab == 'mapa' ? 'nav-tab-active' : ''; ?>">
                    🗺️ Mapa
                </a>
                <a href="?page=daher-settings&tab=whatsapp" class="nav-tab <?php echo $active_tab == 'whatsapp' ? 'nav-tab-active' : ''; ?>">
                    💬 WhatsApp
                </a>
                <a href="?page=daher-settings&tab=home" class="nav-tab <?php echo $active_tab == 'home' ? 'nav-tab-active' : ''; ?>">
                    🏠 Home
                </a>
                <a href="?page=daher-settings&tab=especialidades" class="nav-tab <?php echo $active_tab == 'especialidades' ? 'nav-tab-active' : ''; ?>">
                    🩺 Especialidades
                </a>
                <a href="?page=daher-settings&tab=sobre" class="nav-tab <?php echo $active_tab == 'sobre' ? 'nav-tab-active' : ''; ?>">
                    📖 Sobre
                </a>
                                <a href="?page=daher-settings&tab=legal" class="nav-tab <?php echo $active_tab == 'legal' ? 'nav-tab-active' : ''; ?>">
                    ⚖️ Páginas Legais
                </a>
                <a href="?page=daher-settings&tab=media" class="nav-tab <?php echo $active_tab == 'media' ? 'nav-tab-active' : ''; ?>">
                    🖼️ Mídia & SEO
                </a>
            </h2>
            
            <form method="post" action="options.php">
                <?php
                switch($active_tab) {
                    case 'clinica':
                        settings_fields('daher_clinica_group');
                        do_settings_sections('daher-clinica');
                        submit_button('Salvar Configurações da Clínica');
                        break;
                    case 'medicos':
                        settings_fields('daher_medicos_group');
                        do_settings_sections('daher-medicos');
                        submit_button('Salvar Corpo Clínico');
                        break;
                    case 'social':
                        settings_fields('daher_social_group');
                        do_settings_sections('daher-social');
                        submit_button('Salvar Redes Sociais');
                        break;
                    case 'mapa':
                        settings_fields('daher_map_group');
                        do_settings_sections('daher-map');
                        submit_button('Salvar Configurações do Mapa');
                        break;
                    case 'whatsapp':
                        settings_fields('daher_whatsapp_group');
                        do_settings_sections('daher-whatsapp');
                        submit_button('Salvar Configurações do WhatsApp');
                        break;
                    case 'home':
                        settings_fields('daher_home_group');
                        do_settings_sections('daher-home');
                        submit_button('Salvar Configurações da Home');
                        break;
                    case 'especialidades':
                        settings_fields('daher_especialidades_group');
                        do_settings_sections('daher-especialidades');
                        submit_button('Salvar Configurações da Página Especialidades');
                        break;
                    case 'sobre':
                        settings_fields('daher_sobre_group');
                        do_settings_sections('daher-sobre');
                        submit_button('Salvar Configurações da Página Sobre');
                        break;
                                        case 'media':
                        settings_fields('daher_media_group');
                        do_settings_sections('daher-media');
                        submit_button('Salvar Mídia & SEO');
                        break;
                    case 'legal':
                        settings_fields('daher_legal_group');
                        do_settings_sections('daher-legal');
                        submit_button('Salvar Páginas Legais');
                        break;
                }
                ?>
            </form>
            
            <hr style="margin-top: 40px; margin-bottom: 20px;">
            
            <div style="background: #fff3f3; padding: 15px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #d63638;">
                <h3 style="color: #d63638; margin-top: 0;">⚠️ Restaurar Padrões de Fábrica</h3>
                <p>Caso queira apagar todas as configurações e voltar o site para os textos e imagens originais do tema, clique no botão abaixo. <strong>Atenção: Esta ação é irreversível e afetará todas as abas.</strong></p>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('Tem certeza absoluta que deseja apagar TODAS as configurações? O site voltará aos textos e imagens padrão de fábrica.');">
                    <input type="hidden" name="action" value="reset_daher_settings">
                    <?php wp_nonce_field('reset_daher_settings_nonce'); ?>
                    <button type="submit" class="button" style="color: #d63638; border-color: #d63638; background: white;">Restaurar Padrões de Fábrica</button>
                </form>
            </div>
            
            <div style="background: #f0f6fc; padding: 15px; border-radius: 8px; margin-top: 20px;">
                <h3>📌 Como usar as configurações</h3>
                <ul style="margin-left: 20px;">
                    <li><strong>Clínica:</strong> Configure endereço, horário, telefone e e-mail.</li>
                    <li><strong>Corpo Clínico:</strong> Adicione/edite/remova médicos.</li>
                    <li><strong>Redes Sociais:</strong> Configure as URLs do Instagram, Facebook e YouTube.</li>
                    <li><strong>Mapa:</strong> Configure as coordenadas para o Google Maps.</li>
                    <li><strong>WhatsApp:</strong> Configure o número e mensagem padrão.</li>
                </ul>
                <p>✅ Clique em "Salvar" em cada aba para aplicar as alterações.</p>
            </div>
        </div>
        
        <style>
            .form-table th { width: 200px; }
            .daher-medico-item {
                transition: all 0.3s ease;
            }
            .daher-medico-item:hover {
                background: #e8e8e8 !important;
            }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            var medicoIndex = <?php echo count(get_option('daher_medicos_options', [])); ?>;
            
            $('#daher-add-medico').on('click', function() {
                var html = '<div class="daher-medico-item" style="background: #f5f5f5; padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #1A365D;">';
                html += '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">';
                html += '<strong style="color: #1A365D;">👨‍⚕️ Médico #' + (medicoIndex + 1) + '</strong>';
                html += '<button type="button" class="button daher-remove-medico" style="background: #f87171; color: white; border-color: #f87171;">🗑️ Remover</button>';
                html += '</div>';
                
                // Preview da foto no novo item
                html += '<div style="margin-bottom: 15px;">';
                html += '<label style="display: block; font-weight: 600; margin-bottom: 5px;">Foto do Médico</label>';
                html += '<div id="daher_medicos_' + medicoIndex + '_foto-preview" style="margin-bottom: 10px;">';
                html += '<div style="width:100px; height:100px; background:#e2e8f0; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#64748B;"><i class="fas fa-user-md fa-3x"></i></div>';
                html += '</div>';
                html += '<input type="hidden" name="daher_medicos_options[' + medicoIndex + '][foto]" id="daher_medicos_' + medicoIndex + '_foto" value="" />';
                html += '<button type="button" class="button daher-upload-medico-btn" data-target="daher_medicos_' + medicoIndex + '_foto" data-preview="daher_medicos_' + medicoIndex + '_foto-preview">📷 Selecionar Foto</button>';
                html += '<button type="button" class="button daher-clear-medico-btn" data-target="daher_medicos_' + medicoIndex + '_foto" data-preview="daher_medicos_' + medicoIndex + '_foto-preview" style="margin-left: 5px;">🗑️ Remover</button>';
                html += '</div>';

                html += '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">';
                html += '<div><label style="display: block; font-weight: 600;">Nome completo *</label><input type="text" name="daher_medicos_options[' + medicoIndex + '][nome]" value="" class="regular-text" style="width: 100%;" /></div>';
                html += '<div><label style="display: block; font-weight: 600;">CRM / Registro</label><input type="text" name="daher_medicos_options[' + medicoIndex + '][crm]" value="" class="regular-text" style="width: 100%;" /></div>';
                html += '</div>';
                
                html += '<div style="margin-top: 10px;"><label style="display: block; font-weight: 600;">Especialidade *</label><input type="text" name="daher_medicos_options[' + medicoIndex + '][especialidade]" value="" class="regular-text" style="width: 100%;" /></div>';
                
                html += '<div style="margin-top: 10px;"><label style="display: block; font-weight: 600;">URL do Instagram</label><input type="url" name="daher_medicos_options[' + medicoIndex + '][instagram]" value="" class="regular-text" style="width: 100%;" placeholder="https://instagram.com/..." /></div>';

                html += '<div style="margin-top: 10px;"><label style="display: block; font-weight: 600;">Tags / Expertise (separe por vírgula)</label><input type="text" name="daher_medicos_options[' + medicoIndex + '][tags]" value="" class="regular-text" style="width: 100%;" placeholder="Ex: Tratamento de Varizes, Trombose, Check-up" /></div>';
                html += '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px;">';
                html += '<div><label style="display: block; font-weight: 600;">Bio / Descrição</label><textarea name="daher_medicos_options[' + medicoIndex + '][bio]" rows="2" style="width: 100%;"></textarea></div>';
                html += '<div><label style="display: block; font-weight: 600;">Ordem de Exibição (Nº)</label><input type="number" name="daher_medicos_options[' + medicoIndex + '][ordem]" value="0" class="small-text" style="width: 100%;" min="0" step="1" /><p class="description">Menores números aparecem primeiro.</p></div>';
                html += '</div>';
                html += '</div>';
                
                $('#daher-medicos-repeater').append(html);
                medicoIndex++;
            });
            
            $(document).on('click', '.daher-remove-medico', function() {
                $(this).closest('.daher-medico-item').remove();
            });

            // Lógica de Upload de Foto do Médico
            $(document).on('click', '.daher-upload-medico-btn', function(e) {
                e.preventDefault();
                var $button = $(this);
                var target = $button.data('target');
                var preview = $button.data('preview');
                
                var frame = wp.media({
                    title: 'Selecionar Foto do Médico',
                    button: { text: 'Usar esta foto' },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    var url = attachment.url;
                    
                    $('#' + target).val(url);
                    $('#' + preview).html('<img src="' + url + '" style="width:100px; height:100px; border-radius:50%; object-fit:cover; border: 2px solid #1A365D;" />');
                });

                frame.open();
            });
            
            $(document).on('click', '.daher-clear-medico-btn', function(e) {
                e.preventDefault();
                var target = $(this).data('target');
                var preview = $(this).data('preview');
                
                $('#' + target).val('');
                $('#' + preview).html('<div style="width:100px; height:100px; background:#e2e8f0; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#64748B;"><i class="fas fa-user-md fa-3x"></i></div>');
            });

            // Uploader de Imagem Geral
            $(document).on('click', '.upload-image-btn', function(e) {
                e.preventDefault();
                var $wrapper = $(this).closest('.daher-image-uploader');
                var frame = wp.media({
                    title: 'Selecionar Imagem',
                    button: { text: 'Usar imagem' },
                    multiple: false
                });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $wrapper.find('.image-url-input').val(attachment.url);
                    $wrapper.find('.image-preview-wrapper').html('<img src="' + attachment.url + '" style="max-width: 300px; max-height: 150px; border: 1px solid #ccc; padding: 3px; background: #fff;">');
                    $wrapper.find('.remove-image-btn').show();
                });
                frame.open();
            });

            $(document).on('click', '.remove-image-btn', function(e) {
                e.preventDefault();
                var $wrapper = $(this).closest('.daher-image-uploader');
                $wrapper.find('.image-url-input').val('');
                $wrapper.find('.image-preview-wrapper').html('<div class="no-image-placeholder" style="width: 300px; height: 100px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; color: #999; margin-bottom: 10px;">Sem Imagem (Fallback do Tema)</div>');
                $(this).hide();
            });
        });
        </script>
        <?php
    }

    // ============================================
    // SANITIZAÇÃO HOME
    // ============================================
    public function sanitize_home($input) {
        $output = [];
        $output['hero_title'] = sanitize_text_field($input['hero_title'] ?? '');
        $output['hero_subtitle'] = sanitize_textarea_field($input['hero_subtitle'] ?? '');
        
        // Stats
        $output['stat_years'] = sanitize_text_field($input['stat_years'] ?? '+20');
        $output['stat_years_label'] = sanitize_text_field($input['stat_years_label'] ?? 'Anos de Excelência');
        $output['stat_patients'] = sanitize_text_field($input['stat_patients'] ?? '+5.000');
        $output['stat_patients_label'] = sanitize_text_field($input['stat_patients_label'] ?? 'Pacientes Atendidos');
        $output['stat_team'] = sanitize_text_field($input['stat_team'] ?? '100%');
        $output['stat_team_label'] = sanitize_text_field($input['stat_team_label'] ?? 'Equipe Especializada');
        
        // About
        $output['about_title'] = sanitize_text_field($input['about_title'] ?? '');
        $output['about_text_1'] = wp_kses_post($input['about_text_1'] ?? '');
        $output['about_text_2'] = wp_kses_post($input['about_text_2'] ?? '');
        
        // Specialties Cards
        if (isset($input['specialties']) && is_array($input['specialties'])) {
            $output['specialties'] = [];
            foreach ($input['specialties'] as $card) {
                if (!empty($card['title'])) {
                    $output['specialties'][] = [
                        'title' => sanitize_text_field($card['title']),
                        'subtitle' => sanitize_text_field($card['subtitle'] ?? ''),
                        'description' => sanitize_textarea_field($card['description'] ?? ''),
                        'image' => esc_url_raw($card['image'] ?? ''),
                        'link' => esc_url_raw($card['link'] ?? '#'),
                        'items' => sanitize_textarea_field($card['items'] ?? '')
                    ];
                }
            }
        }
        
        return $output;
    }

    // ============================================
    // CALLBACKS HOME
    // ============================================
    public function specialties_section_callback() {
        echo '<p>Adicione, edite ou remova os cards de especialidades exibidos na página inicial.</p>';
    }

    public function specialties_field_callback() {
        $options = get_option('daher_home_options', []);
        $specialties = isset($options['specialties']) ? $options['specialties'] : [];
        ?>
        <div id="daher-specialties-repeater">
            <?php if (!empty($specialties)) : ?>
                <?php foreach ($specialties as $index => $card) : ?>
                    <div class="daher-specialty-item" style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #C5A880;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <strong style="color: #1A365D;">🃏 Card #<?php echo $index + 1; ?></strong>
                            <button type="button" class="button daher-remove-specialty" style="background: #f87171; color: white; border-color: #f87171;">🗑️ Remover</button>
                        </div>
                        
                        <!-- Imagem -->
                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-weight: 600;">Imagem do Card</label>
                            <div id="daher_specialties_<?php echo $index; ?>_preview" style="margin-bottom: 10px;">
                                <?php if (!empty($card['image'])) : ?>
                                    <img src="<?php echo esc_url($card['image']); ?>" style="width:100px; height:100px; object-fit:cover; border-radius:8px;" />
                                <?php else : ?>
                                    <div style="width:100px; height:100px; background:#e2e8f0; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#64748B;">Sem imagem</div>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="daher_home_options[specialties][<?php echo $index; ?>][image]" id="daher_specialties_<?php echo $index; ?>_image" value="<?php echo esc_attr($card['image'] ?? ''); ?>" />
                            <button type="button" class="button daher-upload-specialty-btn" data-target="daher_specialties_<?php echo $index; ?>_image" data-preview="daher_specialties_<?php echo $index; ?>_preview">📷 Selecionar Imagem</button>
                            <button type="button" class="button daher-clear-specialty-btn" data-target="daher_specialties_<?php echo $index; ?>_image" data-preview="daher_specialties_<?php echo $index; ?>_preview" style="margin-left: 5px;">🗑️ Remover</button>
                        </div>
                        
                        <!-- Título e Subtítulo -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                            <div>
                                <label style="display: block; font-weight: 600;">Título *</label>
                                <input type="text" name="daher_home_options[specialties][<?php echo $index; ?>][title]" value="<?php echo esc_attr($card['title'] ?? ''); ?>" class="regular-text" style="width: 100%;" />
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600;">Subtítulo (opcional)</label>
                                <input type="text" name="daher_home_options[specialties][<?php echo $index; ?>][subtitle]" value="<?php echo esc_attr($card['subtitle'] ?? ''); ?>" class="regular-text" style="width: 100%;" />
                            </div>
                        </div>
                        
                        <!-- Descrição -->
                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-weight: 600;">Descrição / Texto</label>
                            <textarea name="daher_home_options[specialties][<?php echo $index; ?>][description]" rows="3" style="width: 100%;"><?php echo esc_textarea($card['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <!-- Itens (lista) -->
                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-weight: 600;">Itens (um por linha)</label>
                            <textarea name="daher_home_options[specialties][<?php echo $index; ?>][items]" rows="4" style="width: 100%;" placeholder="Tratamento de Varizes (Laser, Espuma, Cirurgia)&#10;Check-up Vascular Completo&#10;Tratamento de Trombose"><?php echo isset($card['items']) && is_array($card['items']) ? implode("\n", $card['items']) : ''; ?></textarea>
                            <p class="description">Digite um item por linha. Eles aparecerão como lista com ícone ✓.</p>
                        </div>
                        
                        <!-- Link -->
                        <div>
                            <label style="display: block; font-weight: 600;">Link "Saiba mais" (Página de destino)</label>
                            <select name="daher_home_options[specialties][<?php echo $index; ?>][link]" style="width: 100%;">
                                <option value="#">-- Sem link --</option>
                                <?php
                                $pages = get_pages();
                                foreach ($pages as $p) {
                                    $url = get_permalink($p->ID);
                                    $selected = selected(esc_url_raw($card['link'] ?? ''), $url, false);
                                    echo '<option value="' . esc_url($url) . '" ' . $selected . '>' . esc_html($p->post_title) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <button type="button" id="daher-add-specialty" class="button" style="margin-top: 10px; background: #1A365D; color: white; border-color: #1A365D;">
            <i class="fas fa-plus-circle"></i> + Adicionar Card
        </button>
        
        <script>
        jQuery(document).ready(function($) {
            var specialtyIndex = <?php echo count($specialties); ?>;
            
            $('#daher-add-specialty').on('click', function() {
                var html = '<div class="daher-specialty-item" style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #C5A880;">';
                html += '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">';
                html += '<strong style="color: #1A365D;">🃏 Card #' + (specialtyIndex + 1) + '</strong>';
                html += '<button type="button" class="button daher-remove-specialty" style="background: #f87171; color: white; border-color: #f87171;">🗑️ Remover</button>';
                html += '</div>';
                
                // Imagem
                html += '<div style="margin-bottom: 10px;">';
                html += '<label style="display: block; font-weight: 600;">Imagem do Card</label>';
                html += '<div id="daher_specialties_' + specialtyIndex + '_preview" style="margin-bottom: 10px;">';
                html += '<div style="width:100px; height:100px; background:#e2e8f0; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#64748B;">Sem imagem</div>';
                html += '</div>';
                html += '<input type="hidden" name="daher_home_options[specialties][' + specialtyIndex + '][image]" id="daher_specialties_' + specialtyIndex + '_image" value="" />';
                html += '<button type="button" class="button daher-upload-specialty-btn" data-target="daher_specialties_' + specialtyIndex + '_image" data-preview="daher_specialties_' + specialtyIndex + '_preview">📷 Selecionar Imagem</button>';
                html += '<button type="button" class="button daher-clear-specialty-btn" data-target="daher_specialties_' + specialtyIndex + '_image" data-preview="daher_specialties_' + specialtyIndex + '_preview" style="margin-left: 5px;">🗑️ Remover</button>';
                html += '</div>';
                
                html += '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">';
                html += '<div><label style="display: block; font-weight: 600;">Título *</label><input type="text" name="daher_home_options[specialties][' + specialtyIndex + '][title]" value="" class="regular-text" style="width: 100%;" /></div>';
                html += '<div><label style="display: block; font-weight: 600;">Subtítulo</label><input type="text" name="daher_home_options[specialties][' + specialtyIndex + '][subtitle]" value="" class="regular-text" style="width: 100%;" /></div>';
                html += '</div>';
                
                html += '<div style="margin-bottom: 10px;">';
                html += '<label style="display: block; font-weight: 600;">Descrição / Texto</label>';
                html += '<textarea name="daher_home_options[specialties][' + specialtyIndex + '][description]" rows="3" style="width: 100%;"></textarea>';
                html += '</div>';
                
                html += '<div style="margin-bottom: 10px;">';
                html += '<label style="display: block; font-weight: 600;">Itens (um por linha)</label>';
                html += '<textarea name="daher_home_options[specialties][' + specialtyIndex + '][items]" rows="4" style="width: 100%;" placeholder="Tratamento de Varizes (Laser, Espuma, Cirurgia)&#10;Check-up Vascular Completo&#10;Tratamento de Trombose"></textarea>';
                html += '<p class="description">Digite um item por linha. Eles aparecerão como lista com ícone ✓.</p>';
                html += '</div>';
                
                // Dropdown de páginas para o link
                var pagesDropdown = '<select name="daher_home_options[specialties][' + specialtyIndex + '][link]" style="width: 100%;"><option value="#">-- Sem link --</option>';
                <?php foreach (get_pages() as $p) : ?>
                    pagesDropdown += '<option value="<?php echo esc_js(get_permalink($p->ID)); ?>"><?php echo esc_js($p->post_title); ?></option>';
                <?php endforeach; ?>
                pagesDropdown += '</select>';
                
                html += '<div><label style="display: block; font-weight: 600;">Link "Saiba mais" (Página de destino)</label>';
                html += pagesDropdown + '</div>';
                html += '</div>';
                
                $('#daher-specialties-repeater').append(html);
                specialtyIndex++;
            });
            
            $(document).on('click', '.daher-remove-specialty', function() {
                $(this).closest('.daher-specialty-item').remove();
            });
            
            // Upload de imagem
            $(document).on('click', '.daher-upload-specialty-btn', function(e) {
                e.preventDefault();
                var $button = $(this);
                var target = $button.data('target');
                var preview = $button.data('preview');
                
                var frame = wp.media({
                    title: 'Selecionar Imagem do Card',
                    button: { text: 'Usar esta imagem' },
                    multiple: false
                });
                
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    var url = attachment.url;
                    
                    $('#' + target).val(url);
                    $('#' + preview).html('<img src="' + url + '" style="width:100px; height:100px; object-fit:cover; border-radius:8px;" />');
                });
                
                frame.open();
            });
            
            $(document).on('click', '.daher-clear-specialty-btn', function(e) {
                e.preventDefault();
                var target = $(this).data('target');
                var preview = $(this).data('preview');
                
                $('#' + target).val('');
                $('#' + preview).html('<div style="width:100px; height:100px; background:#e2e8f0; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#64748B;">Sem imagem</div>');
            });
        });
        </script>
        <?php
    }


    // ============================================
    // SANITIZAÇÃO ESPECIALIDADES
    // ============================================
    public function sanitize_especialidades($input) {
        $output = [];
        $output['especialidades_hero_title'] = sanitize_text_field($input['especialidades_hero_title'] ?? '');
        $output['especialidades_hero_subtitle'] = sanitize_textarea_field($input['especialidades_hero_subtitle'] ?? '');
        $output['tech_title'] = sanitize_text_field($input['tech_title'] ?? '');
        $output['tech_subtitle'] = sanitize_textarea_field($input['tech_subtitle'] ?? '');
        $output['tech_layout'] = sanitize_text_field($input['tech_layout'] ?? 'simple');
        $output['diff_title'] = sanitize_text_field($input['diff_title'] ?? '');
        $output['diff_layout'] = sanitize_text_field($input['diff_layout'] ?? 'simple');
        
        // Tecnologias
        if (isset($input['tech_items']) && is_array($input['tech_items'])) {
            $output['tech_items'] = [];
            foreach ($input['tech_items'] as $item) {
                if (!empty($item['title'])) {
                    $output['tech_items'][] = [
                        'icon' => sanitize_text_field($item['icon'] ?? '🔬'),
                        'title' => sanitize_text_field($item['title']),
                        'description' => sanitize_textarea_field($item['description'] ?? '')
                    ];
                }
            }
        }
        
        // Diferenciais
        if (isset($input['diff_items']) && is_array($input['diff_items'])) {
            $output['diff_items'] = [];
            foreach ($input['diff_items'] as $item) {
                if (!empty($item['title'])) {
                    $output['diff_items'][] = [
                        'icon' => sanitize_text_field($item['icon'] ?? 'fas fa-star'),
                        'title' => sanitize_text_field($item['title']),
                        'description' => sanitize_textarea_field($item['description'] ?? '')
                    ];
                }
            }
        }
        
        return $output;
    }

    // ============================================
    // SANITIZAÇÃO SOBRE
    // ============================================
    public function sanitize_sobre($input) {
        $output = [];
        $output['sobre_hero_title'] = sanitize_text_field($input['sobre_hero_title'] ?? '');
        $output['sobre_hero_subtitle'] = wp_kses_post($input['sobre_hero_subtitle'] ?? '');
        $output['history_title'] = sanitize_text_field($input['history_title'] ?? '');
        $output['history_text_1'] = wp_kses_post($input['history_text_1'] ?? '');
        $output['history_text_2'] = wp_kses_post($input['history_text_2'] ?? '');
        $output['history_text_3'] = wp_kses_post($input['history_text_3'] ?? '');
        $output['values_title'] = sanitize_text_field($input['values_title'] ?? '');
        $output['values_subtitle'] = wp_kses_post($input['values_subtitle'] ?? '');
        
        // Valores
        if (isset($input['values_items']) && is_array($input['values_items'])) {
            $output['values_items'] = [];
            foreach ($input['values_items'] as $item) {
                if (!empty($item['title'])) {
                    $output['values_items'][] = [
                        'icon' => sanitize_text_field($item['icon'] ?? 'fas fa-heartbeat'),
                        'title' => sanitize_text_field($item['title']),
                        'description' => sanitize_textarea_field($item['description'] ?? '')
                    ];
                }
            }
        }
        
        return $output;
    }    


    // ============================================
    // CALLBACKS ESPECIALIDADES
    // ============================================
    public function tech_items_field_callback() {
        $options = get_option('daher_especialidades_options', []);
        $items = isset($options['tech_items']) ? $options['tech_items'] : [];
        ?>
        <div id="daher-tech-repeater">
            <?php if (!empty($items)) : ?>
                <?php foreach ($items as $index => $item) : ?>
                    <div class="daher-tech-item" style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #C5A880;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <strong style="color: #1A365D;">🔬 Tecnologia #<?php echo $index + 1; ?></strong>
                            <button type="button" class="button daher-remove-tech" style="background: #f87171; color: white; border-color: #f87171;">🗑️ Remover</button>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 2fr 3fr; gap: 10px;">
                            <div>
                                <label style="display: block; font-weight: 600;">Ícone</label>
                                <select name="daher_especialidades_options[tech_items][<?php echo $index; ?>][icon]" style="width: 100%;">
                                    <?php
                                    $icons = [
                                        'fas fa-microscope' => 'Microscópio',
                                        'fas fa-search-plus' => 'Dermatoscópio / Lupa',
                                        'fas fa-magic' => 'Estética / Laser',
                                        'fas fa-wave-square' => 'Ultrassom / Ondas',
                                        'fas fa-laptop-medical' => 'Tecnologia Digital',
                                        'fas fa-heartbeat' => 'Coração / Cardiologia',
                                        'fas fa-star' => 'Estrela / Destaque',
                                        'fas fa-user-md' => 'Médico Especialista',
                                        'fas fa-user-nurse' => 'Enfermagem / Apoio',
                                        'fas fa-stethoscope' => 'Estetoscópio / Consulta',
                                        'fas fa-shield-alt' => 'Segurança / Prevenção',
                                        'fas fa-award' => 'Qualidade / Certificação',
                                        'fas fa-leaf' => 'Dermatologia / Natural',
                                        'fas fa-pills' => 'Tratamento / Remédios',
                                        'fas fa-eye' => 'Olho / Oftalmo',
                                        'fas fa-bone' => 'Osso / Ortopedia',
                                        'fas fa-brain' => 'Cérebro / Neurologia',
                                        'fas fa-lungs' => 'Pulmão / Respiratório',
                                        'fas fa-dna' => 'DNA / Genética',
                                        'fas fa-procedures' => 'Procedimentos / Internação',
                                        'fas fa-vial' => 'Exames / Laboratório',
                                        'fas fa-clinic-medical' => 'Clínica / Estrutura',
                                        'fas fa-x-ray' => 'Raio-X / Imagem',
                                        'fas fa-syringe' => 'Seringa / Vacina',
                                        'fas fa-hand-holding-heart' => 'Cuidado / Acolhimento',
                                        'fas fa-tooth' => 'Odontologia / Dente'
                                    ];
                                    foreach ($icons as $class => $label) {
                                        echo '<option value="' . esc_attr($class) . '" ' . selected($item['icon'] ?? 'fas fa-microscope', $class, false) . '>' . esc_html($label) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600;">Título</label>
                                <input type="text" name="daher_especialidades_options[tech_items][<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" style="width: 100%;" />
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600;">Descrição (Máx. 150 caracteres)</label>
                                <textarea name="daher_especialidades_options[tech_items][<?php echo $index; ?>][description]" rows="2" maxlength="150" style="width: 100%;"><?php echo esc_textarea($item['description'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" id="daher-add-tech" class="button" style="margin-top: 10px; background: #1A365D; color: white; border-color: #1A365D;">+ Adicionar Tecnologia</button>
        
        <script>
        jQuery(document).ready(function($) {
            var techIndex = <?php echo count($items); ?>;
            
            $('#daher-add-tech').on('click', function() {
                var html = '<div class="daher-tech-item" style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #C5A880;">';
                html += '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">';
                html += '<strong style="color: #1A365D;">🔬 Tecnologia #' + (techIndex + 1) + '</strong>';
                html += '<button type="button" class="button daher-remove-tech" style="background: #f87171; color: white; border-color: #f87171;">🗑️ Remover</button>';
                html += '</div>';
                var iconDropdown = '<select name="daher_especialidades_options[tech_items][' + techIndex + '][icon]" style="width: 100%;"><option value="fas fa-microscope">Microscópio</option><option value="fas fa-search-plus">Dermatoscópio / Lupa</option><option value="fas fa-magic">Estética / Laser</option><option value="fas fa-wave-square">Ultrassom / Ondas</option><option value="fas fa-laptop-medical">Tecnologia Digital</option><option value="fas fa-heartbeat">Coração / Cardiologia</option><option value="fas fa-star">Estrela / Destaque</option><option value="fas fa-user-md">Médico Especialista</option><option value="fas fa-user-nurse">Enfermagem / Apoio</option><option value="fas fa-stethoscope">Estetoscópio / Consulta</option><option value="fas fa-shield-alt">Segurança / Prevenção</option><option value="fas fa-award">Qualidade / Certificação</option><option value="fas fa-leaf">Dermatologia / Natural</option><option value="fas fa-pills">Tratamento / Remédios</option><option value="fas fa-eye">Olho / Oftalmo</option><option value="fas fa-bone">Osso / Ortopedia</option><option value="fas fa-brain">Cérebro / Neurologia</option><option value="fas fa-lungs">Pulmão / Respiratório</option><option value="fas fa-dna">DNA / Genética</option><option value="fas fa-procedures">Procedimentos / Internação</option><option value="fas fa-vial">Exames / Laboratório</option><option value="fas fa-clinic-medical">Clínica / Estrutura</option><option value="fas fa-x-ray">Raio-X / Imagem</option><option value="fas fa-syringe">Seringa / Vacina</option><option value="fas fa-hand-holding-heart">Cuidado / Acolhimento</option><option value="fas fa-tooth">Odontologia / Dente</option></select>';
                
                html += '<div style="display: grid; grid-template-columns: 1fr 2fr 3fr; gap: 10px;">';
                html += '<div><label style="display: block; font-weight: 600;">Ícone</label>' + iconDropdown + '</div>';
                html += '<div><label style="display: block; font-weight: 600;">Título</label><input type="text" name="daher_especialidades_options[tech_items][' + techIndex + '][title]" value="" style="width: 100%;" /></div>';
                html += '<div><label style="display: block; font-weight: 600;">Descrição (Máx. 150 caracteres)</label><textarea name="daher_especialidades_options[tech_items][' + techIndex + '][description]" rows="2" maxlength="150" style="width: 100%;"></textarea></div>';
                html += '</div></div>';
                $('#daher-tech-repeater').append(html);
                techIndex++;
            });
            
            $(document).on('click', '.daher-remove-tech', function() {
                $(this).closest('.daher-tech-item').remove();
            });
        });
        </script>
        <?php
    }

    public function diff_items_field_callback() {
        $options = get_option('daher_especialidades_options', []);
        $items = isset($options['diff_items']) ? $options['diff_items'] : [];
        ?>
        <div id="daher-diff-repeater">
            <?php if (!empty($items)) : ?>
                <?php foreach ($items as $index => $item) : ?>
                    <div class="daher-diff-item" style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #C5A880;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <strong style="color: #1A365D;">⭐ Diferencial #<?php echo $index + 1; ?></strong>
                            <button type="button" class="button daher-remove-diff" style="background: #f87171; color: white; border-color: #f87171;">🗑️ Remover</button>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 2fr 3fr; gap: 10px;">
                            <div>
                                <label style="display: block; font-weight: 600;">Ícone</label>
                                <select name="daher_especialidades_options[diff_items][<?php echo $index; ?>][icon]" style="width: 100%;">
                                    <?php
                                    $icons = [
                                        'fas fa-microscope' => 'Microscópio',
                                        'fas fa-search-plus' => 'Dermatoscópio / Lupa',
                                        'fas fa-magic' => 'Estética / Laser',
                                        'fas fa-wave-square' => 'Ultrassom / Ondas',
                                        'fas fa-laptop-medical' => 'Tecnologia Digital',
                                        'fas fa-heartbeat' => 'Coração / Cardiologia',
                                        'fas fa-star' => 'Estrela / Destaque',
                                        'fas fa-user-md' => 'Médico Especialista',
                                        'fas fa-user-nurse' => 'Enfermagem / Apoio',
                                        'fas fa-stethoscope' => 'Estetoscópio / Consulta',
                                        'fas fa-shield-alt' => 'Segurança / Prevenção',
                                        'fas fa-award' => 'Qualidade / Certificação',
                                        'fas fa-leaf' => 'Dermatologia / Natural',
                                        'fas fa-pills' => 'Tratamento / Remédios',
                                        'fas fa-eye' => 'Olho / Oftalmo',
                                        'fas fa-bone' => 'Osso / Ortopedia',
                                        'fas fa-brain' => 'Cérebro / Neurologia',
                                        'fas fa-lungs' => 'Pulmão / Respiratório',
                                        'fas fa-dna' => 'DNA / Genética',
                                        'fas fa-procedures' => 'Procedimentos / Internação',
                                        'fas fa-vial' => 'Exames / Laboratório',
                                        'fas fa-clinic-medical' => 'Clínica / Estrutura',
                                        'fas fa-x-ray' => 'Raio-X / Imagem',
                                        'fas fa-syringe' => 'Seringa / Vacina',
                                        'fas fa-hand-holding-heart' => 'Cuidado / Acolhimento',
                                        'fas fa-tooth' => 'Odontologia / Dente'
                                    ];
                                    foreach ($icons as $class => $label) {
                                        echo '<option value="' . esc_attr($class) . '" ' . selected($item['icon'] ?? 'fas fa-star', $class, false) . '>' . esc_html($label) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600;">Título</label>
                                <input type="text" name="daher_especialidades_options[diff_items][<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" style="width: 100%;" />
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600;">Descrição (Máx. 150 caracteres)</label>
                                <textarea name="daher_especialidades_options[diff_items][<?php echo $index; ?>][description]" rows="2" maxlength="150" style="width: 100%;"><?php echo esc_textarea($item['description'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" id="daher-add-diff" class="button" style="margin-top: 10px; background: #1A365D; color: white; border-color: #1A365D;">+ Adicionar Diferencial</button>
        
        <script>
        jQuery(document).ready(function($) {
            var diffIndex = <?php echo count($items); ?>;
            
            $('#daher-add-diff').on('click', function() {
                var number = String(diffIndex + 1).padStart(2, '0');
                var html = '<div class="daher-diff-item" style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #C5A880;">';
                html += '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">';
                html += '<strong style="color: #1A365D;">⭐ Diferencial #' + (diffIndex + 1) + '</strong>';
                html += '<button type="button" class="button daher-remove-diff" style="background: #f87171; color: white; border-color: #f87171;">🗑️ Remover</button>';
                html += '</div>';
                var iconDropdown = '<select name="daher_especialidades_options[diff_items][' + diffIndex + '][icon]" style="width: 100%;"><option value="fas fa-microscope">Microscópio</option><option value="fas fa-search-plus">Dermatoscópio / Lupa</option><option value="fas fa-magic">Estética / Laser</option><option value="fas fa-wave-square">Ultrassom / Ondas</option><option value="fas fa-laptop-medical">Tecnologia Digital</option><option value="fas fa-heartbeat">Coração / Cardiologia</option><option value="fas fa-star">Estrela / Destaque</option><option value="fas fa-user-md">Médico Especialista</option><option value="fas fa-user-nurse">Enfermagem / Apoio</option><option value="fas fa-stethoscope">Estetoscópio / Consulta</option><option value="fas fa-shield-alt">Segurança / Prevenção</option><option value="fas fa-award">Qualidade / Certificação</option><option value="fas fa-leaf">Dermatologia / Natural</option><option value="fas fa-pills">Tratamento / Remédios</option><option value="fas fa-eye">Olho / Oftalmo</option><option value="fas fa-bone">Osso / Ortopedia</option><option value="fas fa-brain">Cérebro / Neurologia</option><option value="fas fa-lungs">Pulmão / Respiratório</option><option value="fas fa-dna">DNA / Genética</option><option value="fas fa-procedures">Procedimentos / Internação</option><option value="fas fa-vial">Exames / Laboratório</option><option value="fas fa-clinic-medical">Clínica / Estrutura</option><option value="fas fa-x-ray">Raio-X / Imagem</option><option value="fas fa-syringe">Seringa / Vacina</option><option value="fas fa-hand-holding-heart">Cuidado / Acolhimento</option><option value="fas fa-tooth">Odontologia / Dente</option></select>';
                
                html += '<div style="display: grid; grid-template-columns: 1fr 2fr 3fr; gap: 10px;">';
                html += '<div><label style="display: block; font-weight: 600;">Ícone</label>' + iconDropdown + '</div>';
                html += '<div><label style="display: block; font-weight: 600;">Título</label><input type="text" name="daher_especialidades_options[diff_items][' + diffIndex + '][title]" value="" style="width: 100%;" /></div>';
                html += '<div><label style="display: block; font-weight: 600;">Descrição (Máx. 150 caracteres)</label><textarea name="daher_especialidades_options[diff_items][' + diffIndex + '][description]" rows="2" maxlength="150" style="width: 100%;"></textarea></div>';
                html += '</div></div>';
                $('#daher-diff-repeater').append(html);
                diffIndex++;
            });
            
            $(document).on('click', '.daher-remove-diff', function() {
                $(this).closest('.daher-diff-item').remove();
            });
        });
        </script>
        <?php
    }

    public function values_items_field_callback() {
        $options = get_option('daher_sobre_options', []);
        $items = isset($options['values_items']) ? $options['values_items'] : [];
        ?>
        <div id="daher-values-repeater">
            <?php if (!empty($items)) : ?>
                <?php foreach ($items as $index => $item) : ?>
                    <div class="daher-value-item" style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #C5A880;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <strong style="color: #1A365D;">💎 Valor #<?php echo $index + 1; ?></strong>
                            <button type="button" class="button daher-remove-value" style="background: #f87171; color: white; border-color: #f87171;">🗑️ Remover</button>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 2fr 3fr; gap: 10px;">
                            <div>
                                <label style="display: block; font-weight: 600;">Ícone (classe Font Awesome)</label>
                                <input type="text" name="daher_sobre_options[values_items][<?php echo $index; ?>][icon]" value="<?php echo esc_attr($item['icon'] ?? 'fas fa-heartbeat'); ?>" style="width: 100%;" placeholder="fas fa-heartbeat" />
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600;">Título</label>
                                <input type="text" name="daher_sobre_options[values_items][<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" style="width: 100%;" />
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600;">Descrição</label>
                                <textarea name="daher_sobre_options[values_items][<?php echo $index; ?>][description]" rows="2" style="width: 100%;"><?php echo esc_textarea($item['description'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" id="daher-add-value" class="button" style="margin-top: 10px; background: #1A365D; color: white; border-color: #1A365D;">+ Adicionar Valor</button>
        
        <script>
        jQuery(document).ready(function($) {
            var valueIndex = <?php echo count($items); ?>;
            
            $('#daher-add-value').on('click', function() {
                var html = '<div class="daher-value-item" style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #C5A880;">';
                html += '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">';
                html += '<strong style="color: #1A365D;">💎 Valor #' + (valueIndex + 1) + '</strong>';
                html += '<button type="button" class="button daher-remove-value" style="background: #f87171; color: white; border-color: #f87171;">🗑️ Remover</button>';
                html += '</div>';
                html += '<div style="display: grid; grid-template-columns: 1fr 2fr 3fr; gap: 10px;">';
                html += '<div><label style="display: block; font-weight: 600;">Ícone (classe Font Awesome)</label><input type="text" name="daher_sobre_options[values_items][' + valueIndex + '][icon]" value="fas fa-heartbeat" style="width: 100%;" placeholder="fas fa-heartbeat" /></div>';
                html += '<div><label style="display: block; font-weight: 600;">Título</label><input type="text" name="daher_sobre_options[values_items][' + valueIndex + '][title]" value="" style="width: 100%;" /></div>';
                html += '<div><label style="display: block; font-weight: 600;">Descrição</label><textarea name="daher_sobre_options[values_items][' + valueIndex + '][description]" rows="2" style="width: 100%;"></textarea></div>';
                html += '</div></div>';
                $('#daher-values-repeater').append(html);
                valueIndex++;
            });
            
            $(document).on('click', '.daher-remove-value', function() {
                $(this).closest('.daher-value-item').remove();
            });
        });
        </script>
        <?php
    }   

    public function sanitize_legal($input) {
        $output = [];
        $output['privacy_page'] = absint($input['privacy_page'] ?? 0);
        $output['terms_page'] = absint($input['terms_page'] ?? 0);
        return $output;
    }
    
    public function dropdown_pages_callback($args) {
        $options = get_option('daher_legal_options', []);
        $selected = isset($options[$args['id']]) ? $options[$args['id']] : 0;
        
        wp_dropdown_pages([
            'name'              => 'daher_legal_options[' . $args['id'] . ']',
            'id'                => $args['id'],
            'show_option_none'  => '&mdash; Selecione uma página (Desativado) &mdash;',
            'option_none_value' => '0',
            'selected'          => $selected,
        ]);
        echo '<p class="description">Se selecionada, o link para esta página aparecerá no rodapé, modal LGPD e formulários. Se não for selecionada nenhuma, os links não serão exibidos.</p>';
    }
}

// Inicializa a classe
if (class_exists(__NAMESPACE__ . '\\SettingsAPI')) {
    new SettingsAPI();
}



