<?php
namespace DaherClinica\Contracts;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Interface para os serviços de rastreamento (Tracking).
 * Define o contrato independente de onde os dados serão salvos (Banco, Arquivo, API Externa).
 */
interface Tracker_Interface {
    /**
     * Registra um novo clique/evento.
     *
     * @param string $device Dispositivo (mobile/desktop).
     * @param string $source Origem do clique (form, button_link).
     * @return array Dados atualizados do evento.
     */
    public function track_click(string $device, string $source): array;

    /**
     * Retorna os dados consolidados de um mês específico.
     *
     * @param string $month_key Chave do mês (ex: 2026_09).
     * @return array Estatísticas e logs do mês.
     */
    public function get_stats_for_month(string $month_key): array;

    /**
     * Retorna o histórico consolidado de todos os meses disponíveis.
     *
     * @return array
     */
    public function get_all_months(): array;
}
