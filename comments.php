<?php
/**
 * Template de Comentários - Daher Clínica
 * 
 * @package DaherClinica
 */

if (!defined('ABSPATH')) {
    exit;
}

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <i class="far fa-comments"></i>
            <?php
            $comment_count = get_comments_number();
            if ($comment_count === 1) {
                echo __('1 Comentário', 'daherclinica');
            } else {
                echo sprintf(__('%s Comentários', 'daherclinica'), number_format_i18n($comment_count));
            }
            ?>
        </h2>

        <ul class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ul',
                'short_ping'  => true,
                'avatar_size' => 50,
                'callback'    => 'daherclinica_comment_callback',
            ));
            ?>
        </ul>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav class="comment-navigation">
                <div class="nav-previous"><?php previous_comments_link('← ' . __('Comentários anteriores', 'daherclinica')); ?></div>
                <div class="nav-next"><?php next_comments_link(__('Comentários recentes', 'daherclinica') . ' →'); ?></div>
            </nav>
        <?php endif; ?>

    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number()) : ?>
        <p class="comments-closed"><?php _e('Comentários fechados.', 'daherclinica'); ?></p>
    <?php endif; ?>

    <?php
    comment_form(array(
        'title_reply'          => '<i class="far fa-edit"></i> ' . __('Deixe seu Comentário', 'daherclinica'),
        'title_reply_to'       => __('Responder para %s', 'daherclinica'),
        'cancel_reply_link'    => __('Cancelar resposta', 'daherclinica'),
        'label_submit'         => __('Publicar Comentário', 'daherclinica'),
        'class_submit'         => 'submit',
        'comment_field'        => '<div class="comment-form-comment"><label for="comment">' . __('Comentário', 'daherclinica') . ' <span class="required">*</span></label><textarea id="comment" name="comment" rows="5" placeholder="' . __('Escreva seu comentário aqui...', 'daherclinica') . '" required></textarea></div>',
        'fields'               => array(
            'author' => '<div class="comment-form-author"><label for="author">' . __('Nome', 'daherclinica') . ' <span class="required">*</span></label><input id="author" name="author" type="text" placeholder="' . __('Seu nome', 'daherclinica') . '" value="' . esc_attr($commenter['comment_author']) . '" required /></div>',
            'email'  => '<div class="comment-form-email"><label for="email">' . __('E-mail', 'daherclinica') . ' <span class="required">*</span></label><input id="email" name="email" type="email" placeholder="' . __('seu@email.com', 'daherclinica') . '" value="' . esc_attr($commenter['comment_author_email']) . '" required /></div>',
            'url'    => '<div class="comment-form-url"><label for="url">' . __('Site', 'daherclinica') . '</label><input id="url" name="url" type="url" placeholder="' . __('https://seusite.com', 'daherclinica') . '" value="' . esc_attr($commenter['comment_author_url']) . '" /></div>',
        ),
    ));
    ?>

</div>