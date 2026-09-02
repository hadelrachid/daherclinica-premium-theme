/**
 * Daher Clínica - Main JavaScript
 * Versão 2.0.0 - Organizado por módulos
 * 
 * Funcionalidades:
 * - Efeito Paralaxe no Fundo Dourado
 * - Efeito Paralaxe no Hero
 * - Header Scroll Effect
 * - Menu Mobile (toggle, fechar, submenu)
 * - Smooth Scroll para âncoras
 * - Formulário de Contato (WhatsApp)
 * - Efeito de Brilho Dourado nos Cards
 * - Intersection Observer para animações
 * - LGPD / Privacy Notice
 * - Modal Jurídico
 */

(function () {
    'use strict';

    // ============================================================
    // 1. EFEITO PARALAXE NO FUNDO DOURADO
    // ============================================================
    function initParallaxBackground() {
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    const scrolled = window.pageYOffset;
                    const body = document.body;
                    const bgPosition = 350 - (scrolled * 0.05);
                    body.style.backgroundPosition = `center ${bgPosition}px`;
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    // ============================================================
    // 2. EFEITO PARALAXE NO HERO
    // ============================================================
    function initHeroParallax() {
        // Seleciona todos os heros (Home e Internas)
        const heroes = document.querySelectorAll('.hero, .page-hero');

        heroes.forEach(hero => {
            const heroBg = hero.querySelector('.hero-bg, .page-hero-bg');
            if (heroBg) {
                let ticking = false;
                window.addEventListener('scroll', () => {
                    if (!ticking) {
                        window.requestAnimationFrame(() => {
                            const scrolled = window.pageYOffset;
                            const rate = scrolled * 0.3;
                            if (scrolled < hero.offsetHeight) {
                                heroBg.style.transform = `translateY(${rate}px)`;
                            }
                            ticking = false;
                        });
                        ticking = true;
                    }
                }, { passive: true });
            }
        });
    }

    // ============================================================
    // 3. HEADER SCROLL EFFECT
    // ============================================================
    function initHeaderScroll() {
        const header = document.getElementById('header');
        if (!header) return;

        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    if (window.scrollY > 50) {
                        header.classList.add('scrolled');
                    } else {
                        header.classList.remove('scrolled');
                    }
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    // ============================================================
    // 4. MENU MOBILE (TOGGLE, FECHAR, SUBMENU)
    // ============================================================
    function initMobileMenu() {
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileCloseBtn = document.getElementById('mobileCloseBtn');
        const mobileOverlay = document.getElementById('mobileMenuOverlay');

        // Função global para fechar o menu
        window.closeMobileMenu = function () {
            if (mobileMenu) {
                mobileMenu.classList.remove('active');
                if (mobileOverlay) mobileOverlay.classList.remove('active');
                document.body.style.overflow = '';

                // Fechar todos os submenus
                document.querySelectorAll('.mobile-submenu-items').forEach(sub => {
                    sub.classList.remove('active');
                });
                // Resetar setas
                document.querySelectorAll('.mobile-arrow').forEach(arrow => {
                    arrow.classList.remove('fa-chevron-up');
                    arrow.classList.add('fa-chevron-down');
                });
            }
        };

        // Abrir/fechar menu
        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', function (e) {
                e.preventDefault();
                mobileMenu.classList.toggle('active');
                if (mobileOverlay) mobileOverlay.classList.toggle('active');

                if (mobileMenu.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                    // Abrir todos os submenus por padrão
                    document.querySelectorAll('.mobile-submenu-items').forEach(sub => {
                        sub.classList.add('active');
                    });
                    document.querySelectorAll('.mobile-arrow').forEach(arrow => {
                        arrow.classList.remove('fa-chevron-down');
                        arrow.classList.add('fa-chevron-up');
                    });
                } else {
                    document.body.style.overflow = '';
                }
            });
        }

        // Fechar ao clicar no overlay
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', function () {
                window.closeMobileMenu();
            });
        }

        // Botão fechar
        if (mobileCloseBtn && mobileMenu) {
            mobileCloseBtn.addEventListener('click', function (e) {
                e.preventDefault();
                window.closeMobileMenu();
            });
        }

        // Botão "Agendar Consulta" mobile - navegação inteligente
        const mobileAgendarBtn = document.getElementById('mobileAgendarBtn');
        if (mobileAgendarBtn) {
            mobileAgendarBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const homeUrl = this.getAttribute('data-home-url') || '/';

                // Normaliza as URLs para comparação (remove trailing slash e hash)
                const currentBase = window.location.origin + window.location.pathname.replace(/\/$/, '');
                const homeBase = homeUrl.replace(/\/$/, '');

                // Fecha o menu mobile primeiro
                window.closeMobileMenu();

                if (currentBase === homeBase) {
                    // Está na home: usa scrollIntoView que respeita o scroll-margin-top do CSS
                    const target = document.getElementById('contactForm');
                    if (target) {
                        setTimeout(function () {
                            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }, 100);
                    }
                } else {
                    // Está em outra página: navega para home + âncora
                    window.location.href = homeUrl + '#contactForm';
                }
            });
        }


        // Submenu mobile toggle (apenas botão)
        document.querySelectorAll('.mobile-toggle-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const submenu = this.closest('.mobile-submenu')?.querySelector('.mobile-submenu-items');
                const arrow = this.querySelector('.mobile-arrow');

                if (submenu) {
                    submenu.classList.toggle('active');
                    if (arrow) {
                        arrow.classList.toggle('fa-chevron-down');
                        arrow.classList.toggle('fa-chevron-up');
                    }
                }
            });
        });

        // Fechar menu ao clicar em um link
        document.querySelectorAll('.mobile-nav-list a').forEach(link => {
            link.addEventListener('click', function () {
                if (!this.classList.contains('mobile-toggle-btn') &&
                    !this.closest('.mobile-toggle-btn')) {
                    window.closeMobileMenu();
                }
            });
        });

        // Fechar menu ao redimensionar para desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth > 1024 && mobileMenu?.classList.contains('active')) {
                window.closeMobileMenu();
            }
        });
    }

    // ============================================================
    // 5. SMOOTH SCROLL PARA ÂNCORAS
    // ============================================================
    function initSmoothScroll() {
        // Seleciona links que começam com # ou que contêm /# (para suportar links de menu mobile)
        document.querySelectorAll('a[href^="#"], a[href*="/#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                // Extrai apenas o hash do link
                const href = this.getAttribute('href');
                const targetId = href.substring(href.indexOf('#'));
                
                if (targetId.length <= 1) return; // Ignora apenas '#'
                
                // Se o link contiver caminho (ex: /#agendamento), e não estivermos na home, deixa o navegador agir
                if (href.includes('/#') && window.location.pathname !== '/' && window.location.pathname !== '/daherclinica/') {
                    return; 
                }

                // Corrige links antigos ou dinâmicos apontando para #agendamento
                const finalTargetId = targetId === '#agendamento' ? '#contactForm' : targetId;
                const target = document.querySelector(finalTargetId);
                
                if (target) {
                    e.preventDefault();
                    
                    // Fecha o menu mobile se estiver aberto
                    const mobileMenu = document.getElementById('mobileMenu');
                    const overlay = document.getElementById('menuOverlay');
                    const hamburger = document.getElementById('hamburgerBtn');
                    
                    if (mobileMenu && mobileMenu.classList.contains('active')) {
                        mobileMenu.classList.remove('active');
                        if (overlay) overlay.classList.remove('active');
                        if (hamburger) hamburger.classList.remove('active');
                    }
                    
                    setTimeout(() => {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 50);
                }
            });
        });

        // Auto-scroll ao carregar página com âncora na URL (ex: home_url/#agendamento)
        if (window.location.hash) {
            const hash = window.location.hash === '#agendamento' ? '#contactForm' : window.location.hash;
            const hashTarget = document.querySelector(hash);
            if (hashTarget) {
                // Aguarda rendering completo
                setTimeout(() => {
                    hashTarget.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 400);
            }
        }
    }

    // ============================================================
    // 6. FORMULÁRIO DE CONTATO (WHATSAPP)
    // ============================================================
    function initContactForm() {
        const contactForm = document.querySelector('.contact-form');
        if (!contactForm) return;

        // Máscara de telefone
        const phoneInput = document.getElementById('telefone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 2) {
                    value = value.length > 0 ? `(${value}` : '';
                } else if (value.length <= 6) {
                    value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
                } else if (value.length <= 10) {
                    value = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
                } else {
                    value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7, 11)}`;
                }
                e.target.value = value;
            });
        }

        // Submit do formulário
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const nome = document.getElementById('nome')?.value || '';
            const telefone = document.getElementById('telefone')?.value || '';
            const email = document.getElementById('email')?.value || '';
            const especialidadeSelect = document.getElementById('especialidade');
            const especialidade = especialidadeSelect?.options[especialidadeSelect.selectedIndex]?.text || 'Não especificado';
            const mensagem = document.getElementById('mensagem')?.value || '';

            // Validação básica
            if (!nome) {
                showFormError('nome', 'Por favor, informe seu nome completo.');
                return;
            }
            if (!telefone) {
                showFormError('telefone', 'Por favor, informe seu telefone para contato.');
                return;
            }
            if (!especialidadeSelect?.value) {
                showFormError('especialidade', 'Por favor, selecione uma especialidade.');
                return;
            }

            const privacyCheckbox = document.getElementById('privacy');
            if (privacyCheckbox && !privacyCheckbox.checked) {
                showFormError('privacy', 'Você precisa aceitar a Política de Privacidade e Termos de Uso.');
                return;
            }

            const whatsappNumber = window.daherData?.whatsappNumber || '5521977667676';
            const userCount = window.daherData?.userCount || 'XX';
            const today = new Date().toLocaleDateString('pt-BR');
            const isMobileStr = (window.innerWidth <= 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) ? 'mobile' : 'desktop';

            let message = `*📍 AVISO: MENSAGEM RECEBIDA PELO SITE DAHER CLÍNICA*%0A%0A`;
            message += `Olá! Acessei o site da clínica e preenchi o formulário de contato:%0A%0A`;
            message += `*Nome:* ${encodeURIComponent(nome)}%0A`;
            message += `*Telefone:* ${encodeURIComponent(telefone)}%0A`;
            if (email) message += `*E-mail:* ${encodeURIComponent(email)}%0A`;
            message += `*Especialidade:* ${encodeURIComponent(especialidade)}%0A`;
            if (mensagem) message += `*Mensagem:* ${encodeURIComponent(mensagem)}%0A`;
            message += `%0A*(Auditoria: Usuário nº ${userCount}, em ${today}, via ${isMobileStr}, formulário do site)*`;

            window.open(`https://wa.me/${whatsappNumber}?text=${message}`, '_blank');
            
            // Registra o clique no contador de WhatsApp do painel Admin
            let ajaxUrl = (typeof daherData !== 'undefined' && daherData.ajaxUrl) ? daherData.ajaxUrl : '/wp-admin/admin-ajax.php';
            ajaxUrl += '?action=track_wa_click&_nocache=' + new Date().getTime();

            const isMobile = window.innerWidth <= 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? 'mobile' : 'desktop';
            
            // Usar FormData é mais seguro para sendBeacon do que URLSearchParams
            const data = new FormData();
            data.append('action', 'track_wa_click'); // redundante mas seguro
            data.append('device', isMobile);
            data.append('source', 'form');
            
            // Registra Conversão no Google Ads
            if (typeof gtag === 'function') {
                gtag('event', 'ads_conversion_Fale_conosco_1');
            }
            
            if (navigator.sendBeacon) {
                navigator.sendBeacon(ajaxUrl, data);
            } else {
                fetch(ajaxUrl, { method: 'POST', body: data }).catch(() => {});
            }

            contactForm.reset();
            showFormSuccess(contactForm);
        });

        // Função para mostrar erro
        function showFormError(fieldId, message) {
            const field = document.getElementById(fieldId);
            if (!field) return;

            // Remove erro anterior
            const existingError = field.parentElement?.querySelector('.form-error');
            if (existingError) existingError.remove();

            field.style.borderColor = '#ef4444';
            const error = document.createElement('span');
            error.className = 'form-error';
            error.textContent = message;
            error.style.cssText = 'display: block; color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;';
            field.parentElement?.appendChild(error);

            setTimeout(() => {
                error.remove();
                field.style.borderColor = '';
            }, 3000);
        }

        // Função para mostrar sucesso
        function showFormSuccess(form) {
            const successMsg = document.createElement('div');
            successMsg.className = 'form-success-message';
            successMsg.innerHTML = '<i class="fas fa-check-circle"></i> Redirecionando para o WhatsApp...';
            successMsg.style.cssText = `
                background: #4CAF50;
                color: white;
                padding: 12px;
                border-radius: 8px;
                margin-top: 15px;
                text-align: center;
                animation: fadeOutMsg 3s ease forwards;
            `;
            form.appendChild(successMsg);

            setTimeout(() => successMsg.remove(), 3000);
        }
    }

    // ============================================================
    // 7. EFEITO DE BRILHO DOURADO NOS CARDS
    // ============================================================
    function initCardGlowEffect() {
        document.querySelectorAll('.specialty-card, .team-card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
            });
        });
    }

    // ============================================================
    // 8. INTERSECTION OBSERVER PARA ANIMAÇÕES
    // ============================================================
    function initScrollAnimations() {
        const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Elementos que devem animar ao scroll
        const elementsToObserve = document.querySelectorAll(
            '.specialty-card, .team-card, .about-grid, .post-card'
        );

        elementsToObserve.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    }

    // ============================================================
    // 8B. NEWSLETTER FORM (Simulação de sucesso)
    // ============================================================
    function initNewsletter() {
        const forms = document.querySelectorAll('.newsletter-form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const msg = this.nextElementSibling;
                if (msg && msg.classList.contains('newsletter-msg')) {
                    msg.textContent = 'Inscrição realizada com sucesso!';
                    msg.style.display = 'block';
                    msg.style.color = '#4CAF50';
                    setTimeout(() => { msg.style.display = 'none'; }, 3000);
                }
                this.reset();
            });
        });
    }

    // ============================================================
    // 9. PRIVACY NOTICE (LGPD)
    // ============================================================
    function initPrivacyNotice() {
        const privacyNotice = document.getElementById('privacyNotice');
        if (!privacyNotice) return;

        // Verifica se o usuário já aceitou
        const privacyAccepted = localStorage.getItem('daher_privacy_accepted');
        if (privacyAccepted === 'true') {
            privacyNotice.style.display = 'none';
            return;
        }

        // Mostra o banner após 1 segundo
        setTimeout(() => {
            privacyNotice.style.display = 'block';
            setTimeout(() => privacyNotice.classList.add('show'), 10);
        }, 1000);

        // Botão Aceitar
        const acceptBtn = document.getElementById('acceptPrivacy');
        if (acceptBtn) {
            acceptBtn.addEventListener('click', () => {
                localStorage.setItem('daher_privacy_accepted', 'true');
                privacyNotice.classList.remove('show');
                setTimeout(() => { privacyNotice.style.display = 'none'; }, 500);
            });
        }

        // Botão Recusar - esconde apenas temporariamente
        const rejectBtn = document.getElementById('rejectPrivacy');
        if (rejectBtn) {
            rejectBtn.addEventListener('click', () => {
                privacyNotice.classList.remove('show');
                setTimeout(() => { privacyNotice.style.display = 'none'; }, 500);
            });
        }
    }

    // ============================================================
    // 10. REABRIR BANNER LGPD (para links "LGPD" no site)
    // ============================================================
    function initLegalModal() {
        const privacyNotice = document.getElementById('privacyNotice');
        if (!privacyNotice) return;

        // Reabre o banner ao clicar em links LGPD
        function reopenPrivacyBanner(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            privacyNotice.style.display = 'block';
            void privacyNotice.offsetWidth; // força reflow para animação CSS
            privacyNotice.classList.add('show');
            setTimeout(() => {
                privacyNotice.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }, 100);
        }

        // Links com classe específica (.open-privacy-notice, .open-legal-modal)
        document.querySelectorAll('.open-privacy-notice, .open-legal-modal').forEach(link => {
            link.addEventListener('click', reopenPrivacyBanner);
        });

        // Fallback: links com href="#" que contenham "LGPD" no texto
        document.querySelectorAll('a[href="#"]').forEach(link => {
            if (link.textContent.trim().includes('LGPD')) {
                link.addEventListener('click', reopenPrivacyBanner);
            }
        });
    }

    // ============================================================
    // 11. ADICIONA ESTILOS DE ANIMAÇÃO (se não existirem)
    // ============================================================
    function addAnimationStyles() {
        if (!document.querySelector('#mainAnimationStyle')) {
            const style = document.createElement('style');
            style.id = 'mainAnimationStyle';
            style.textContent = `
                .specialty-card.visible, 
                .team-card.visible, 
                .about-grid.visible, 
                .post-card.visible {
                    opacity: 1 !important;
                    transform: translateY(0) !important;
                }
                
                @keyframes fadeOutMsg {
                    0% { opacity: 1; transform: translateY(0); }
                    70% { opacity: 1; transform: translateY(0); }
                    100% { opacity: 0; transform: translateY(-20px); visibility: hidden; }
                }
            `;
            document.head.appendChild(style);
        }
    }

    // ============================================================
    // 12. WHATSAPP FLOAT (atualizar link)
    // ============================================================
    function initWhatsAppFloat() {
        const whatsappBtn = document.querySelector('.whatsapp-btn');
        if (whatsappBtn) {
            const whatsappNumber = window.daherData?.whatsappNumber || '5521977667676';
            const defaultMessage = encodeURIComponent('Olá! Gostaria de informações sobre os serviços da Daher Clínica.');
            whatsappBtn.href = `https://wa.me/${whatsappNumber}?text=${defaultMessage}`;
        }
    }

    // ============================================================
    // 13. INICIALIZAÇÃO GERAL
    // ============================================================
    function init() {
        // Efeitos visuais
        initParallaxBackground();
        initHeroParallax();
        initCardGlowEffect();
        initScrollAnimations();

        // Header e menu
        initHeaderScroll();
        initMobileMenu();

        // Interações
        initSmoothScroll();
        initContactForm();
        initNewsletter();

        // LGPD e modais
        initPrivacyNotice();
        initLegalModal();

        // Utilitários
        initWhatsAppFloat();
        addAnimationStyles();
    }

    // Inicializar quando o DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
/* ============================================================
/* ============================================================
   12. WHATSAPP CLICK TRACKING E MENSAGENS DINAMICAS
   ============================================================ */
document.addEventListener('DOMContentLoaded', function() {
    const waLinks = document.querySelectorAll('a[href*="wa.me"], a[href*="api.whatsapp.com"]');
    
    waLinks.forEach(function(link) {
        // Ignorar se for um link de compartilhamento (ex: share no blog)
        if (link.classList.contains('share-btn') || link.classList.contains('share-mini')) return;

        const isMobile = window.innerWidth <= 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? 'mobile' : 'desktop';
        const userCount = window.daherData?.userCount || 'XX';
        const today = new Date().toLocaleDateString('pt-BR');
        
        let sourceName = 'link geral';
        if (link.closest('.whatsapp-float')) {
            sourceName = 'botão flutuante';
        } else if (link.closest('.site-footer')) {
            sourceName = 'ícone do rodapé';
        } else if (link.closest('.contact-info')) {
            sourceName = 'página de contato';
        }

        let dynamicMsg = `*📍 AVISO: MENSAGEM RECEBIDA PELO SITE DAHER CLÍNICA*%0A%0A`;
        dynamicMsg += `Olá! Acessei o site da clínica e gostaria de entrar em contato.%0A%0A`;
        dynamicMsg += `*(Auditoria: Usuário nº ${userCount}, em ${today}, via ${isMobile}, ${sourceName})*`;
        
        // Atualiza o href com a mensagem dinmica
        try {
            const url = new URL(link.href);
            url.searchParams.set('text', decodeURIComponent(dynamicMsg));
            link.href = url.toString();
        } catch(e) {
            // fallback
            let baseHref = link.href.split('?')[0];
            link.href = `${baseHref}?text=${dynamicMsg}`;
        }

        link.addEventListener('click', function() {
            // Check if admin-ajax is available via local data
            let ajaxUrl = (typeof daherData !== 'undefined' && daherData.ajaxUrl) ? daherData.ajaxUrl : '/wp-admin/admin-ajax.php';
            ajaxUrl += '?action=track_wa_click&_nocache=' + new Date().getTime();
            
            // Send beacon or fetch in background
            const data = new FormData();
            data.append('action', 'track_wa_click');
            data.append('device', isMobile);
            data.append('source', sourceName === 'botão flutuante' ? 'floating' : 'button_link');
            
            // Registra Conversão no Google Ads
            if (typeof gtag === 'function') {
                gtag('event', 'ads_conversion_Fale_conosco_1');
            }
            
            if (navigator.sendBeacon) {
                navigator.sendBeacon(ajaxUrl, data);
            } else {
                fetch(ajaxUrl, { method: 'POST', body: data }).catch(() => {});
            }
        });
    });
});


document.addEventListener("DOMContentLoaded", function() {
    // ============================================================
    // 6. READING PROGRESS BAR
    // ============================================================
    const progressBar = document.querySelector(".reading-progress");
    if (progressBar) {
        window.addEventListener("scroll", () => {
            const scrollTotal = document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrollOutput = height > 0 ? (scrollTotal / height) * 100 : 0;
            progressBar.style.width = scrollOutput + "%";
        });
    }

    // ============================================================
    // 7. FADE-IN UP OBSERVER (UX Polish)
    // ============================================================
    const elementsToFade = document.querySelectorAll(".hero-content h1, .hero-content p, .section-title, .specialty-card, .team-card, .contact-info");
    
    // Adiciona a classe base antes de observar
    elementsToFade.forEach(el => el.classList.add("fade-in-up"));

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("is-visible");
                observer.unobserve(entry.target); // Anima s uma vez
            }
        });
    }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });

    elementsToFade.forEach(el => observer.observe(el));
});
