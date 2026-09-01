<?php
/**
 * Doctors CPT Module
 * Gerencia o cadastro de médicos do corpo clínico
 */

if (!defined('ABSPATH')) exit;

class DaherClinica_Doctors {
    
    private static $already_rendered = false; // ← NOVO: flag para evitar duplicação
    
    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
        $this->add_order_support();
        $this->register_shortcode();
    }
    
    public function register_post_type() {
        register_post_type('medico', [
            'labels' => [
                'name'          => __('Médicos', 'daherclinica'),
                'singular_name' => __('Médico', 'daherclinica'),
                'add_new'       => __('Adicionar Médico', 'daherclinica'),
                'add_new_item'  => __('Adicionar Novo Médico', 'daherclinica'),
                'edit_item'     => __('Editar Médico', 'daherclinica'),
            ],
            'supports' => ['title', 'thumbnail', 'editor', 'page-attributes'], 
            'public'        => true,
            'show_in_rest'  => true,
            'menu_icon'     => 'dashicons-groups',
            'menu_position' => 20,
        ]);
    }
    
    public function add_meta_boxes() {
        add_meta_box('medico_detalhes', __('Detalhes do Médico', 'daherclinica'), [$this, 'render_meta_box'], 'medico', 'normal', 'high');
    }
    
    public function render_meta_box($post) {
        wp_nonce_field('medico_meta_box', 'medico_meta_box_nonce');
        $crm = get_post_meta($post->ID, '_medico_crm', true);
        $especialidade = get_post_meta($post->ID, '_medico_especialidade', true);
        $rede_tipo = get_post_meta($post->ID, '_medico_rede_tipo', true);
        $rede_url = get_post_meta($post->ID, '_medico_rede_url', true);
        ?>
        <p><label><strong><?php _e('CRM', 'daherclinica'); ?>:</strong></label><br>
        <input type="text" name="medico_crm" value="<?php echo esc_attr($crm); ?>" style="width:100%"></p>
        <p><label><strong><?php _e('Especialidade', 'daherclinica'); ?>:</strong></label><br>
        <input type="text" name="medico_especialidade" value="<?php echo esc_attr($especialidade); ?>" style="width:100%"></p>
        <p><label><strong><?php _e('Rede Social', 'daherclinica'); ?>:</strong></label><br>
        <select name="medico_rede_tipo" style="width:100%">
            <option value=""><?php _e('Nenhuma', 'daherclinica'); ?></option>
            <option value="instagram" <?php selected($rede_tipo, 'instagram'); ?>>Instagram</option>
            <option value="facebook" <?php selected($rede_tipo, 'facebook'); ?>>Facebook</option>
            <option value="youtube" <?php selected($rede_tipo, 'youtube'); ?>>YouTube</option>
        </select></p>
        <p><label><strong><?php _e('URL da Rede Social', 'daherclinica'); ?>:</strong></label><br>
        <input type="url" name="medico_rede_url" value="<?php echo esc_url($rede_url); ?>" style="width:100%"></p>
        <p><em><?php _e('A foto do médico deve ser adicionada como "Imagem Destacada" (thumbnail).', 'daherclinica'); ?></em></p>
        <?php
    }
    
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['medico_meta_box_nonce']) || !wp_verify_nonce($_POST['medico_meta_box_nonce'], 'medico_meta_box')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        
        if (isset($_POST['medico_crm'])) update_post_meta($post_id, '_medico_crm', sanitize_text_field($_POST['medico_crm']));
        if (isset($_POST['medico_especialidade'])) update_post_meta($post_id, '_medico_especialidade', sanitize_text_field($_POST['medico_especialidade']));
        if (isset($_POST['medico_rede_tipo'])) update_post_meta($post_id, '_medico_rede_tipo', sanitize_text_field($_POST['medico_rede_tipo']));
        if (isset($_POST['medico_rede_url'])) update_post_meta($post_id, '_medico_rede_url', esc_url_raw($_POST['medico_rede_url']));
    }

    public function add_order_support() {
        add_action('pre_get_posts', [$this, 'order_by_menu_order']);
    }

    public function order_by_menu_order($query) {
        if (!is_admin() && $query->is_main_query() && $query->get('post_type') === 'medico') {
            $query->set('orderby', 'menu_order');
            $query->set('order', 'ASC');
        }
    }

    public function register_shortcode() {
        add_shortcode('lista_medicos', [$this, 'render_medicos_shortcode']);
    }

    /**
     * NOVA FUNÇÃO: Renderiza o corpo clínico com controle de duplicação
     */
    public static function render_corpo_clinico() {
        // Evita duplicação
        if (self::$already_rendered) {
            return '';
        }
        self::$already_rendered = true;
        
        ob_start();
        get_template_part('template-parts/corpo-clinico');
        return ob_get_clean();
    }

    public function render_medicos_shortcode($atts) {
        // Usa a mesma função para evitar duplicação no shortcode também
        return self::render_corpo_clinico();
    }
}

new DaherClinica_Doctors();

// NOVA FUNÇÃO GLOBAL para ser usada no front-page.php
function daherclinica_get_corpo_clinico() {
    return DaherClinica_Doctors::render_corpo_clinico();
}