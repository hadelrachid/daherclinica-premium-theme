/**
 * Blog JavaScript - Filtros, Busca e Compartilhamento
 */

(function () {
    'use strict';

    // ============================================================
    // Paralaxe no Blog Hero
    // ============================================================

    const blogHeroBg = document.querySelector('.blog-hero-bg');
    const blogHero = document.querySelector('.blog-hero');

    if (blogHeroBg && blogHero) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * 0.3;
            if (scrolled < blogHero.offsetHeight) {
                blogHeroBg.style.transform = `translateY(${rate}px)`;
            }
        });
    }

    // ============================================================
    // Filtros de Categoria
    // ============================================================

    const filterBtns = document.querySelectorAll('.filter-btn');
    const blogGrid = document.getElementById('blogGrid');
    let posts = [];

    if (blogGrid) {
        posts = Array.from(blogGrid.querySelectorAll('.post-card'));
    }

    if (filterBtns.length) {
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const filter = btn.getAttribute('data-filter');
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                let visibleCount = 0;
                posts.forEach(post => {
                    const postCats = (post.getAttribute('data-category') || '').split(' ');
                    if (filter === 'all' || postCats.includes(filter)) {
                        post.style.display = 'block';
                        visibleCount++;
                        setTimeout(() => {
                            post.style.opacity = '1';
                            post.style.transform = 'translateY(0)';
                        }, 10);
                    } else {
                        post.style.opacity = '0';
                        post.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            post.style.display = 'none';
                        }, 300);
                    }
                });

                toggleEmptyMsg(visibleCount);
            });
        });
    }

    function toggleEmptyMsg(count) {
        const emptyMsg = document.querySelector('.blog-empty');
        if (!emptyMsg) return;
        
        if (count === 0) {
            emptyMsg.style.display = 'block';
            setTimeout(() => emptyMsg.style.opacity = '1', 10);
        } else {
            emptyMsg.style.opacity = '0';
            setTimeout(() => emptyMsg.style.display = 'none', 300);
        }
    }

    // ============================================================
    // Busca de Artigos
    // ============================================================

    const searchInput = document.getElementById('searchInput');

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase().trim();
            let visibleCount = 0;
            posts.forEach(post => {
                const title = post.querySelector('h3')?.innerText.toLowerCase() || '';
                const content = post.querySelector('p')?.innerText.toLowerCase() || '';
                const category = post.querySelector('.post-category')?.innerText.toLowerCase() || '';

                if (title.includes(searchTerm) || content.includes(searchTerm) || category.includes(searchTerm)) {
                    post.style.display = 'block';
                    visibleCount++;
                    setTimeout(() => {
                        post.style.opacity = '1';
                        post.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    post.style.opacity = '0';
                    post.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        post.style.display = 'none';
                    }, 300);
                }
            });
            toggleEmptyMsg(visibleCount);
        });
    }

    // ============================================================
    // Compartilhamento Social e Copiar Link
    // ============================================================

    const shareButtons = document.querySelectorAll('.share-mini, .share-btn');
    
    if (shareButtons.length) {
        shareButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const isCopyBtn = btn.classList.contains('copy-link');
                
                // Se for botão de cópia, não abre nova janela
                if (isCopyBtn) {
                    e.preventDefault();
                    const url = btn.getAttribute('data-url') || window.location.href;
                    
                    navigator.clipboard.writeText(url).then(() => {
                        const originalHtml = btn.innerHTML;
                        btn.innerHTML = '<i class="fas fa-check"></i>';
                        btn.classList.add('copied');
                        
                        setTimeout(() => {
                            btn.innerHTML = originalHtml;
                            btn.classList.remove('copied');
                        }, 2000);
                    }).catch(err => {
                        console.error('Erro ao copiar link: ', err);
                    });
                    return;
                }

                // Lógica de compartilhamento social
                const shareType = btn.getAttribute('data-share');
                // Se não tem data-share mas é um link (como no single.php), deixa o link natural agir ou trata aqui
                if (!shareType && btn.tagName === 'A') return; 

                e.preventDefault();
                const url = encodeURIComponent(btn.getAttribute('data-url') || window.location.href);
                const title = encodeURIComponent(btn.getAttribute('data-title') || document.title);

                let shareUrl = '';
                if (shareType === 'whatsapp') shareUrl = `https://wa.me/?text=${title}%20${url}`;
                if (shareType === 'facebook') shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                if (shareType === 'twitter' || shareType === 'x') shareUrl = `https://x.com/intent/tweet?url=${url}&text=${title}`;

                if (shareUrl) window.open(shareUrl, '_blank', 'width=600,height=400');
            });
        });
    }

    // ============================================================
    // Newsletter Form
    // ============================================================

    const newsletterForm = document.getElementById('blogNewsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const input = document.getElementById('newsletterEmail');
            const email = input?.value;

            if (email && email.includes('@')) {
                const successMsg = document.createElement('div');
                successMsg.innerHTML = '<i class="fas fa-check-circle"></i> Inscrição realizada com sucesso!';
                successMsg.style.cssText = 'background:#4CAF50;color:white;padding:12px;border-radius:8px;margin-top:15px;text-align:center;font-size:0.875rem';
                newsletterForm.appendChild(successMsg);
                input.value = '';
                setTimeout(() => successMsg.remove(), 3000);
            } else {
                const errorMsg = document.createElement('div');
                errorMsg.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Por favor, insira um e-mail válido.';
                errorMsg.style.cssText = 'background:#f44336;color:white;padding:12px;border-radius:8px;margin-top:15px;text-align:center;font-size:0.875rem';
                newsletterForm.appendChild(errorMsg);
                setTimeout(() => errorMsg.remove(), 3000);
            }
        });
    }
})();