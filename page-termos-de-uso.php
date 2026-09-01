<?php
/**
 * Template Name: Termos de Uso
 * 
 * @package DaherClinica
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<section class="page-header">
    <div class="container">
        <h1><?php _e('Termos de Uso', 'daherclinica'); ?></h1>
        <p><?php _e('Condições gerais de navegação e utilização do site da Daher Clínica', 'daherclinica'); ?></p>
    </div>
</section>

<section class="section page-content">
    <div class="container">
        <div class="content-wrapper legal-content">
            <p class="lead"><strong><?php _e('Última atualização: Abril de 2026', 'daherclinica'); ?></strong></p>
            <p class="lead"><?php _e('Estes termos regulamentam o uso do portal informativo da Daher Clínica. Ao navegar neste site, você concorda com as condições abaixo. Se não concordar, recomendamos que não utilize nossos serviços online.', 'daherclinica'); ?></p>
            
            <h2>1. <?php _e('Isenção de Diagnóstico Médico', 'daherclinica'); ?></h2>
            <p><?php _e('O conteúdo deste site (textos, imagens, vídeos e infográficos) possui caráter <strong>meramente informativo e educacional</strong> sobre saúde vascular e dermatológica.', 'daherclinica'); ?></p>
            <p><strong class="highlight"><?php _e('Nenhuma informação contida aqui substitui a consulta médica presencial', 'daherclinica'); ?></strong> <?php _e(', o exame físico ou o diagnóstico profissional individualizado.', 'daherclinica'); ?></p>
            
            <h2>2. <?php _e('Uso das Informações', 'daherclinica'); ?></h2>
            <p><?php _e('As orientações sobre varizes, tratamentos a laser, peelings, dermatite, acne, melasma e outras especialidades devem ser discutidas diretamente com nossos médicos especialistas durante uma consulta clínica.', 'daherclinica'); ?></p>
            
            <h2>3. <?php _e('Agendamentos Online', 'daherclinica'); ?></h2>
            <p><?php _e('O envio de dados pelos formulários do site ou link de WhatsApp constitui uma <strong>solicitação de agendamento</strong> e não uma reserva garantida. A marcação final depende:', 'daherclinica'); ?></p>
            <ul>
                <li><?php _e('Da disponibilidade de horários na agenda médica;', 'daherclinica'); ?></li>
                <li><?php _e('Da confirmação por nossa equipe de recepção;', 'daherclinica'); ?></li>
                <li><?php _e('Da verificação de dados do paciente.', 'daherclinica'); ?></li>
            </ul>
            
            <h2>4. <?php _e('Propriedade Intelectual', 'daherclinica'); ?></h2>
            <p><?php _e('Todo o conteúdo visual e textual (incluindo logotipo, imagens, textos, layout, código-fonte) é de propriedade exclusiva da Daher Clínica ou de seus licenciadores.', 'daherclinica'); ?></p>
            
            <div class="legal-footer">
                <small><?php _e('Última atualização: Abril de 2026', 'daherclinica'); ?></small>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
