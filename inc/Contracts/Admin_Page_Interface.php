<?php
namespace DaherClinica\Contracts;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Contrato para as páginas administrativas do painel.
 */
interface Admin_Page_Interface {
    /**
     * Registra os hooks necessários (ex: admin_menu).
     */
    public function register_hooks(): void;

    /**
     * Renderiza o HTML da página.
     */
    public function render(): void;
}
