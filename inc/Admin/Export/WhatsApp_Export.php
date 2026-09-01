<?php
namespace DaherClinica\Admin\Export;

use DaherClinica\Contracts\Tracker_Interface;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Serviço de Exportação de CSV.
 * Responsável apenas por converter os logs do Tracker em CSV.
 */
class WhatsApp_Export {
    
    private $tracker;

    public function __construct(Tracker_Interface $tracker) {
        $this->tracker = $tracker;
    }

    public function export_csv_handler(): void {
        if (!isset($_GET['daher_export_csv']) || !current_user_can('manage_options')) {
            return;
        }

        $month_key = sanitize_text_field($_GET['daher_export_csv']);
        
        $data = $this->tracker->get_stats_for_month($month_key);
        
        if (empty($data)) {
            wp_die('Nenhum dado encontrado para este mês.');
        }

        $logs = isset($data['logs']) && is_array($data['logs']) ? $data['logs'] : [];

        // Força download do arquivo CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="relatorio-whatsapp-' . $month_key . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Adiciona BOM (Byte Order Mark) para UTF-8
        fputs($output, "\xEF\xBB\xBF");
        
        // Cabeçalhos
        fputcsv($output, ['Data e Hora', 'Dispositivo', 'Origem (Local)']);
        
        foreach ($logs as $log) {
            $time = $log['time'] ?? 'Desconhecido';
            $device = $log['device'] === 'mobile' ? 'Mobile (Celular)' : ($log['device'] === 'desktop' ? 'Computador (PC)' : 'Desconhecido');
            $source = $log['source'] === 'form' ? 'Formulário de Contato' : ($log['source'] === 'button_link' ? 'Botão Flutuante / Link' : 'Desconhecido');
            
            fputcsv($output, [$time, $device, $source]);
        }
        
        fclose($output);
        exit;
    }
}
