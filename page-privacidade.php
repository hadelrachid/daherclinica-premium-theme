<?php
/**
 * Template Name: Política de Privacidade
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
        <h1><?php _e('Política de Privacidade', 'daherclinica'); ?></h1>
        <p><?php _e('LGPD - Lei Geral de Proteção de Dados (Lei nº 13.709/2018)', 'daherclinica'); ?></p>
    </div>
</section>

<section class="section page-content">
    <div class="container">
        <div class="content-wrapper legal-content">
            <p class="lead"><strong><?php _e('Última atualização: Abril de 2026', 'daherclinica'); ?></strong></p>
            <p class="lead"><?php _e('A Daher Clínica está empenhada em proteger a privacidade e os dados pessoais de seus pacientes e visitantes, em total conformidade com a Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018).', 'daherclinica'); ?></p>
            
            <h2>1. <?php _e('Coleta de Dados Pessoais', 'daherclinica'); ?></h2>
            <p><?php _e('Ao interagir com nosso site através de formulários ou WhatsApp, coletamos informações essenciais para o seu atendimento:', 'daherclinica'); ?></p>
            <ul>
                <li><strong><?php _e('Identificação:', 'daherclinica'); ?></strong> <?php _e('Nome completo para registro e personalização do contato.', 'daherclinica'); ?></li>
                <li><strong><?php _e('Contato:', 'daherclinica'); ?></strong> <?php _e('E-mail e Telefone (WhatsApp) para retorno de dúvidas e confirmação de consultas.', 'daherclinica'); ?></li>
                <li><strong><?php _e('Interesse Clínico:', 'daherclinica'); ?></strong> <?php _e('Informações sobre a especialidade buscada (Cirurgia Vascular ou Dermatologia).', 'daherclinica'); ?></li>
            </ul>
            
            <h2>2. <?php _e('Finalidade do Tratamento', 'daherclinica'); ?></h2>
            <p><?php _e('Os dados coletados são utilizados exclusivamente para:', 'daherclinica'); ?></p>
            <ul>
                <li><?php _e('Gerenciar pré-agendamentos e solicitações de informações.', 'daherclinica'); ?></li>
                <li><?php _e('Prestar orientações sobre os tratamentos oferecidos pela clínica.', 'daherclinica'); ?></li>
                <li><?php _e('Enviar avisos administrativos e lembretes de consultas.', 'daherclinica'); ?></li>
            </ul>
            
            <h2>3. <?php _e('Sigilo Médico e Segurança', 'daherclinica'); ?></h2>
            <p><?php _e('Dada a natureza de nossa atuação em Cirurgia Vascular e Dermatologia, garantimos o mais estrito sigilo profissional. Seus dados são armazenados em servidores seguros e não são compartilhados com terceiros para fins publicitários ou comerciais.', 'daherclinica'); ?></p>
            
            <h2>4. <?php _e('Direitos do Titular', 'daherclinica'); ?></h2>
            <p><?php _e('Você tem total direito de solicitar o acesso, a correção ou a exclusão definitiva de seus dados de nossa base de contatos a qualquer momento, enviando uma solicitação através de nossos canais oficiais.', 'daherclinica'); ?></p>
            
            <div class="legal-footer">
                <small><?php _e('Última atualização: Abril de 2026', 'daherclinica'); ?></small>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>