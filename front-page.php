<?php
/**
 * Template Name: Página Inicial
 */
get_header(); ?>

<!-- Hero Section -->
<?php get_template_part('template-parts/hero'); ?>

<!-- Sobre Section -->
<?php get_template_part('template-parts/sobre'); ?>

<!-- Especialidades Section -->
<?php get_template_part('template-parts/especialidades'); ?>

<!-- Corpo Clínico Section (usando função global para evitar duplicação) -->
<?php echo daherclinica_get_corpo_clinico(); ?>

<!-- Contato Section -->
<?php get_template_part('template-parts/contato'); ?>

<?php get_footer();