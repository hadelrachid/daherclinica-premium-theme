<?php
$file = "D:/xampp/htdocs/daherclinica/wp-content/themes/daherclinica-premium/inc/class-settings-api.php";
$content = file_get_contents($file);

// Adicionar as variaveis de url no array de sanitizacao
$sanitize_clinica_orig = "public function sanitize_clinica(\$input) {\n        \$output = [];\n        \$fields = [\n            \"daher_address\", \"daher_hours\", \"daher_phone\", \"daher_email\"\n        ];";
$sanitize_clinica_new = "public function sanitize_clinica(\$input) {\n        \$output = [];\n        \$fields = [\n            \"daher_address\", \"daher_hours\", \"daher_phone\", \"daher_email\", \"saas_area_restrita\", \"saas_agendamento\"\n        ];";
$content = str_replace($sanitize_clinica_orig, $sanitize_clinica_new, $content);

// Adicionar os fields no menu clinica
$add_email_field = "add_settings_field(
            'daher_email',
            'E-mail',
            [\$this, 'text_field_callback'],
            'daher-clinica',
            'daher_clinica_section',
            ['id' => 'daher_email', 'placeholder' => 'contato@daherclinica.com']
        );";
        
$add_saas_fields = $add_email_field . "

        // SAAS Configs
        add_settings_field(
            'saas_area_restrita',
            'Link da Área Restrita (CockPit)',
            [\$this, 'text_field_callback'],
            'daher-clinica',
            'daher_clinica_section',
            ['id' => 'saas_area_restrita', 'placeholder' => 'https://agendamento.daherclinica.com/login']
        );
        
        add_settings_field(
            'saas_agendamento',
            'Link do Agendamento Online',
            [\$this, 'text_field_callback'],
            'daher-clinica',
            'daher_clinica_section',
            ['id' => 'saas_agendamento', 'placeholder' => 'https://agendamento.daherclinica.com/agendamento']
        );";

$content = str_replace($add_email_field, $add_saas_fields, $content);

file_put_contents($file, $content);
echo "Settings updated.\n";

