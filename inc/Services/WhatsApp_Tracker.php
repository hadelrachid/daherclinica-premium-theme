<?php
namespace DaherClinica\Services;

use DaherClinica\Contracts\Tracker_Interface;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Serviço responsável por gravar e ler cliques de WhatsApp.
 * SRP: Apenas lida com o repositório de dados.
 */
class WhatsApp_Tracker implements Tracker_Interface {
    
    private $db;
    
    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
    }

    /**
     * @inheritDoc
     */
    public function track_click(string $device, string $source): array {
        // Correção de Bug: wp_date garante que o mês bate com o fuso do WP
        // ao contrário de date('Y_m') que usa o fuso do servidor.
        $current_month = function_exists('wp_date') ? wp_date('Y_m') : current_time('Y_m');
        $option_name = 'daher_wa_clicks_' . $current_month;
        
        $current = $this->db->get_var(
            $this->db->prepare("SELECT option_value FROM {$this->db->options} WHERE option_name = %s", $option_name)
        );
        
        if ($current === null) {
            $data = ['total' => 0, 'mobile' => 0, 'desktop' => 0, 'sources' => [], 'logs' => []];
        } else {
            $data = json_decode($current, true);
            if (!is_array($data)) {
                $data = [
                    'total' => (int)$current, 
                    'mobile' => 0, 
                    'desktop' => 0, 
                    'sources' => ['legacy' => (int)$current],
                    'logs' => []
                ];
            }
            if (!isset($data['logs'])) {
                $data['logs'] = [];
            }
        }
        
        $data['total'] = isset($data['total']) ? $data['total'] + 1 : 1;
        
        if ($device === 'mobile' || $device === 'desktop') {
            $data[$device] = isset($data[$device]) ? $data[$device] + 1 : 1;
        }
        
        if (!isset($data['sources'])) $data['sources'] = [];
        $data['sources'][$source] = isset($data['sources'][$source]) ? $data['sources'][$source] + 1 : 1;
        
        $data['logs'][] = [
            'time'   => current_time('mysql'),
            'device' => $device,
            'source' => $source
        ];
        
        if (count($data['logs']) > 1000) {
            array_shift($data['logs']);
        }
        
        $json_value = wp_json_encode($data);
        
        if ($current === null) {
            add_option($option_name, $json_value, '', 'no');
        } else {
            $this->db->update(
                $this->db->options,
                ['option_value' => $json_value],
                ['option_name' => $option_name],
                ['%s'],
                ['%s']
            );
            wp_cache_delete($option_name, 'options');
        }
        
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function get_stats_for_month(string $month_key): array {
        $option_name = 'daher_wa_clicks_' . str_replace('-', '_', $month_key);
        $raw_val = get_option($option_name);
        
        if (!$raw_val) {
            return [];
        }

        $data = json_decode($raw_val, true);
        return is_array($data) ? $data : [];
    }

    /**
     * @inheritDoc
     */
    /**
     * Get total clicks for the current month
     */
    public function get_current_month_total(): int {
        $current_month = function_exists('wp_date') ? wp_date('Y_m') : current_time('Y_m');
        $option_name = 'daher_wa_clicks_' . $current_month;
        
        $raw_val = get_option($option_name);
        if (!$raw_val) return 0;
        
        $data = json_decode($raw_val, true);
        if (is_array($data) && isset($data['total'])) {
            return (int) $data['total'];
        }
        
        return (int) $raw_val;
    }
    
    public function get_all_months(): array {
        $results = $this->db->get_results("SELECT option_name, option_value FROM {$this->db->options} WHERE option_name LIKE 'daher_wa_clicks_%' ORDER BY option_name DESC");
        
        if (empty($results)) {
            return [];
        }

        $processed_data = [];
        
        foreach ($results as $row) {
            $raw_val = $row->option_value;
            $data_obj = json_decode($raw_val, true);
            
            if (is_array($data_obj)) {
                $clicks = (int) ($data_obj['total'] ?? 0);
                $mobile = (int) ($data_obj['mobile'] ?? 0);
                $desktop = (int) ($data_obj['desktop'] ?? 0);
                $src_form = (int) ($data_obj['sources']['form'] ?? 0);
                $src_floating = (int) ($data_obj['sources']['floating'] ?? 0);
                $src_link = (int) ($data_obj['sources']['button_link'] ?? 0);
                $logs = $data_obj['logs'] ?? [];
            } else {
                $clicks = (int) $raw_val;
                $mobile = 0; $desktop = 0; $src_form = 0; $src_floating = 0; $src_link = 0; $logs = [];
            }

            $parts = explode('_', str_replace('daher_wa_clicks_', '', $row->option_name));
            if (count($parts) == 2) {
                $year = $parts[0];
                $month = $parts[1];
                $month_names = ['01'=>'Janeiro', '02'=>'Fevereiro', '03'=>'Março', '04'=>'Abril', '05'=>'Maio', '06'=>'Junho', '07'=>'Julho', '08'=>'Agosto', '09'=>'Setembro', '10'=>'Outubro', '11'=>'Novembro', '12'=>'Dezembro'];
                $display_month = isset($month_names[$month]) ? $month_names[$month] . ' de ' . $year : $month . '/' . $year;
                $value_key = $year . '-' . $month;
            } else {
                $display_month = str_replace('daher_wa_clicks_', '', $row->option_name);
                $value_key = $display_month;
            }
            
            $processed_data[] = [
                'label'    => $display_month,
                'key'      => $value_key,
                'clicks'   => $clicks,
                'mobile'   => $mobile,
                'desktop'  => $desktop,
                'src_form' => $src_form,
                'src_link' => $src_floating + $src_link,
                'logs'     => $logs
            ];
        }
        
        return $processed_data;
    }
}
