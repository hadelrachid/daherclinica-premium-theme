<?php
namespace DaherClinica\Admin\Pages;

use DaherClinica\Contracts\Admin_Page_Interface;
use DaherClinica\Contracts\Tracker_Interface;

if (!defined('ABSPATH')) {
    exit;
}

class Reports_Page implements Admin_Page_Interface {
    
    private $tracker;
    
    public function __construct(Tracker_Interface $tracker) {
        $this->tracker = $tracker;
    }
    
    public function register_hooks(): void {
        // O menu em si é registrado pela SettingsAPI principal (ou por um Admin_Menu_Registrar),
        // mas aqui nós podemos acoplar a view dessa página se formos separar tudo.
        // Por hora, apenas chamaremos o método render() a partir do SettingsAPI refatorado.
    }
    
    public function render(): void {
        $data = $this->tracker->get_all_months();
        
        echo '<style>
            .daher-chart-container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 20px; }
            .daher-bar-row { display: flex; align-items: center; margin-bottom: 20px; }
            .daher-bar-label { width: 150px; font-weight: bold; color: #1e293b; }
            .daher-bar-track { background: #f1f5f9; height: 24px; border-radius: 4px; overflow: hidden; position: relative; width: 100%; }
            .daher-bar-fill { background: linear-gradient(90deg, #10b981, #34d399); height: 100%; border-radius: 4px; transition: width 1s ease-out; min-width: 5px; }
            .daher-bar-value { margin-left: 15px; font-weight: bold; color: #0f172a; width: 60px; text-align: right; }
            .daher-filter-box { margin-bottom: 20px; padding: 15px; background: #f8fafc; border-radius: 6px; border: 1px solid #e2e8f0; }
        </style>';
        
        echo '<div class="wrap" style="max-width: 800px;">';
        echo '<div style="display: flex; justify-content: space-between; align-items: center;">';
        echo '<h1>Relatório de Cliques - WhatsApp</h1>';
        echo '<button onclick="window.location.reload();" class="button button-primary"><i class="fas fa-sync-alt"></i> Atualizar Agora</button>';
        echo '</div>';
        echo '<p>Monitore o engajamento dos seus pacientes ao longo dos meses através deste gráfico 100% nativo.</p>';
        
        if (empty($data)) {
            echo '<div class="daher-chart-container"><p>Nenhum clique registrado ainda. (Pode demorar alguns minutos para o primeiro clique aparecer)</p></div>';
            echo '</div>';
            return;
        }

        $max_clicks = 0;
        $months_available = [];
        
        foreach ($data as $item) {
            if ($item['clicks'] > $max_clicks) $max_clicks = $item['clicks'];
            $months_available[$item['key']] = $item['label'];
        }
        
        $max_scale = $max_clicks < 100 ? 100 : $max_clicks * 1.1; 
        
        echo '<div class="daher-filter-box">';
        echo '<label for="daher-month-filter"><strong>Filtrar por Mês: </strong></label>';
        echo '<select id="daher-month-filter" onchange="filterDaherChart(this.value)">';
        echo '<option value="all">Todos os meses</option>';
        foreach ($months_available as $key => $label) {
            echo '<option value="' . esc_attr($key) . '">' . esc_html($label) . '</option>';
        }
        echo '</select>';
        echo '</div>';

        echo '<div class="daher-chart-container" id="daher-chart-container">';
        foreach ($data as $item) {
            $percentage = $max_scale > 0 ? round(($item['clicks'] / $max_scale) * 100, 2) : 0;
            echo '<div class="daher-bar-row" data-month="' . esc_attr($item['key']) . '">';
            echo '<div class="daher-bar-label">' . esc_html($item['label']) . '</div>';
            
            echo '<div style="flex-grow: 1;">';
            echo '<div class="daher-bar-track">';
            echo '<div class="daher-bar-fill" style="width: 0%;" data-target-width="' . $percentage . '%"></div>';
            echo '</div>';
            
            echo '<div style="font-size: 12px; color: #64748b; margin-top: 5px; display: flex; gap: 15px; flex-wrap: wrap;">';
            if ($item['mobile'] > 0 || $item['desktop'] > 0) {
                echo '<span><span class="dashicons dashicons-smartphone" style="font-size: 16px; margin-top: -2px;"></span> Mobile: ' . $item['mobile'] . ' | <span class="dashicons dashicons-desktop" style="font-size: 16px; margin-top: -2px;"></span> PC: ' . $item['desktop'] . '</span>';
            } else {
                echo '<span><span class="dashicons dashicons-info" style="font-size: 16px; margin-top: -2px;"></span> Dados Detalhados Indisponíveis</span>';
            }
            if ($item['src_form'] > 0 || $item['src_link'] > 0) {
                echo '<span><span class="dashicons dashicons-email" style="font-size: 16px; margin-top: -2px;"></span> Formulário: ' . $item['src_form'] . ' | <span class="dashicons dashicons-whatsapp" style="font-size: 16px; margin-top: -2px;"></span> Outros Botões: ' . $item['src_link'] . '</span>';
            }
            echo '</div>';
            
            echo '<div style="margin-top: 8px;">';
            $export_url = admin_url('admin.php?page=daher-settings&daher_export_csv=' . $item['key']);
            echo '<a href="' . esc_url($export_url) . '" class="button button-secondary button-small" title="Baixar relatório"><i class="fas fa-file-excel" style="color: #107c41; margin-right: 3px;"></i> CSV</a>';
            echo '<button type="button" class="button button-secondary button-small toggle-logs-btn" data-target="logs-' . esc_attr($item['key']) . '" style="margin-left: 5px;" title="Ver os últimos cliques"><i class="dashicons dashicons-visibility" style="margin-top: 3px;"></i> Ver Histórico</button>';
            echo '</div>';
            
            if (!empty($item['logs'])) {
                echo '<div id="logs-' . esc_attr($item['key']) . '" class="daher-visual-logs" style="display: none; margin-top: 15px; padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; max-height: 250px; overflow-y: auto;">';
                echo '<table class="wp-list-table widefat fixed striped" style="margin: 0; border: none;">';
                echo '<thead><tr><th style="padding: 8px;">🕒 Horário do Clique</th><th style="padding: 8px;">📱 Dispositivo</th><th style="padding: 8px;">🔗 Origem</th></tr></thead>';
                echo '<tbody>';
                
                $reversed_logs = array_reverse($item['logs']);
                foreach ($reversed_logs as $log) {
                    $time = $log['time'] ?? 'Desconhecido';
                    if ($time !== 'Desconhecido') {
                        $time = date('d/m/Y \à\s H:i:s', strtotime($time));
                    }
                    $device = $log['device'] === 'mobile' ? '<span class="dashicons dashicons-smartphone" style="color:#64748b;"></span> Mobile' : ($log['device'] === 'desktop' ? '<span class="dashicons dashicons-desktop" style="color:#64748b;"></span> Computador' : 'Desconhecido');
                    $source = $log['source'] === 'form' ? 'Formulário' : ($log['source'] === 'button_link' ? 'Botão Flutuante / Link' : ($log['source'] === 'floating' ? 'Botão Flutuante' : 'Desconhecido'));
                    
                    echo '<tr>';
                    echo '<td style="padding: 8px;"><strong>' . esc_html($time) . '</strong></td>';
                    echo '<td style="padding: 8px;">' . $device . '</td>';
                    echo '<td style="padding: 8px;">' . esc_html($source) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
                echo '</div>';
            }
            
            echo '</div>'; 
            
            echo '<div class="daher-bar-value" style="font-size: 18px;">' . esc_html($item['clicks']) . '</div>';
            echo '</div>';
        }
        echo '</div>';
        
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                setTimeout(() => {
                    const fills = document.querySelectorAll(".daher-bar-fill");
                    fills.forEach(fill => {
                        fill.style.width = fill.getAttribute("data-target-width");
                    });
                }, 100);
                
                document.querySelectorAll(".toggle-logs-btn").forEach(btn => {
                    btn.addEventListener("click", function(e) {
                        e.preventDefault();
                        const targetId = this.getAttribute("data-target");
                        const targetDiv = document.getElementById(targetId);
                        if(targetDiv) {
                            if(targetDiv.style.display === "none") {
                                targetDiv.style.display = "block";
                            } else {
                                targetDiv.style.display = "none";
                            }
                        } else {
                            alert("Não há dados de horário detalhados registrados para este mês.");
                        }
                    });
                });
            });
            
            function filterDaherChart(selected) {
                const rows = document.querySelectorAll(".daher-bar-row");
                rows.forEach(row => {
                    if (selected === "all" || row.getAttribute("data-month") === selected) {
                        row.style.display = "flex";
                    } else {
                        row.style.display = "none";
                    }
                });
            }
        </script>';
        
        echo '</div>';
    }
}
