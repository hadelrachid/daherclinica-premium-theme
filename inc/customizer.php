<?php
/**
 * Customizer Module
 * Gerencia as opções de personalização do tema
 * 
 * @package DaherClinica
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe de gerenciamento do Customizer
 */
class DaherClinica_Customizer {
    
    private $wp_customize;
    
    public function __construct($wp_customize) {
        $this->wp_customize = $wp_customize;
        $this->add_sections();
        $this->add_settings();
    }
    
    private function add_sections() {
        $this->wp_customize->add_section('daher_contact', [
            'title'    => __('Contato', 'daherclinica'),
            'priority' => 30,
        ]);
        
        $this->wp_customize->add_section('daher_social', [
            'title'    => __('Redes Sociais', 'daherclinica'),
            'priority' => 31,
        ]);
        
        $this->wp_customize->add_section('daher_info', [
            'title'    => __('Informações da Clínica', 'daherclinica'),
            'priority' => 32,
        ]);
    }
    
    private function add_settings() {
        // Contato
        $this->add_setting('whatsapp_number', '5521977667676');
        $this->add_control('whatsapp_number', __('WhatsApp (com DDD)', 'daherclinica'), 'daher_contact');
        
        $this->add_setting('phone_number', '(21) 2415-9263');
        $this->add_control('phone_number', __('Telefone', 'daherclinica'), 'daher_contact');
        
        $this->add_setting('contact_email', 'contato@daherclinica.com');
        $this->add_control('contact_email', __('E-mail', 'daherclinica'), 'daher_contact');
        
        // Informações da Clínica
        $this->add_setting('clinic_address', 'Estrada dos Bandeirantes, 8591 - Sala 308, Rio de Janeiro - RJ');
        $this->add_control('clinic_address', __('Endereço', 'daherclinica'), 'daher_info');
        
        $this->add_setting('clinic_hours', 'Segunda a Sexta: 09:00 - 18:00');
        $this->add_control('clinic_hours', __('Horário de Funcionamento', 'daherclinica'), 'daher_info');
        
        // Redes Sociais
        $this->add_setting('instagram_url', 'https://www.instagram.com/daherclinica/');
        $this->add_control('instagram_url', __('Instagram URL', 'daherclinica'), 'daher_social');
        
        $this->add_setting('facebook_url', '');
        $this->add_control('facebook_url', __('Facebook URL', 'daherclinica'), 'daher_social');
    }
    
    private function add_setting($id, $default) {
        $this->wp_customize->add_setting($id, [
            'default'           => $default,
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);
    }
    
    private function add_control($id, $label, $section) {
        $this->wp_customize->add_control($id, [
            'label'    => $label,
            'section'  => $section,
            'type'     => 'text',
            'settings' => $id,
        ]);
    }
}

/**
 * ============================================================
 * INICIALIZA DO CUSTOMIZER - AQUI!
 * ============================================================
 */
add_action('customize_register', function($wp_customize) {
    new DaherClinica_Customizer($wp_customize);
});