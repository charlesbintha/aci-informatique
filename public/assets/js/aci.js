const toggle = document.querySelector('.menu-toggle');
const nav = document.querySelector('#navigation');
function closeMenu() { toggle.setAttribute('aria-expanded', 'false'); nav.classList.remove('is-open'); }
toggle.addEventListener('click', () => { const open = toggle.getAttribute('aria-expanded') !== 'true'; toggle.setAttribute('aria-expanded', String(open)); nav.classList.toggle('is-open', open); });
nav.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));
document.addEventListener('keydown', e => { if(e.key === 'Escape') { closeMenu(); toggle.focus(); } });

// Adapted from Aurax's gsap-animation.js: reveals, parallax and panel pinning.
// Keep native scrolling and visible HTML when JavaScript or GSAP is unavailable.
if (window.gsap && window.ScrollTrigger) {
    gsap.registerPlugin(ScrollTrigger);
    const motion = gsap.matchMedia();

    motion.add('(prefers-reduced-motion: no-preference)', () => {
        const heroLines = document.querySelectorAll('.aci-hero .hero-title > span');
        if (heroLines.length) {
            gsap.timeline({ defaults: { ease: 'power3.out' } })
                .from(heroLines, { y: 75, opacity: 0, rotationX: -18, duration: 1.25, stagger: 0.18 })
                .from('.hero-eyebrow, .hero-aside, .hero-bottom', { y: 22, opacity: 0, duration: 0.8, stagger: 0.12 }, '-=0.65');
        }

        const revealTargets = gsap.utils.toArray('.section-top, .about-grid > div, .audiences, .commitment-grid > div, .price-row, .contact-grid > div, .contact-form, .detail-grid > *, .service-page h1, .footer-top');
        revealTargets.forEach((element) => {
            // Never conceal content already above the viewport on a deep link.
            if (element.getBoundingClientRect().top < 0) return;
            gsap.from(element, {
                y: 42, opacity: 0, duration: 0.9, ease: 'power3.out',
                scrollTrigger: { trigger: element, start: 'top 94%', once: true },
                onComplete: () => gsap.set(element, { clearProps: 'transform,opacity,visibility' })
            });
        });

        gsap.utils.toArray('.aci-service').forEach((card) => {
            const info = card.querySelector('.branding-service-wrap');
            gsap.from(info, {
                y: 30, opacity: 0, duration: 0.8, ease: 'power3.out',
                scrollTrigger: { trigger: card, start: 'top 92%', once: true },
                onComplete: () => gsap.set(info, { clearProps: 'transform,opacity,visibility' })
            });
        });

        // Keyboard navigation must never leave a focused control invisible.
        const revealFocused = (event) => {
            revealTargets.forEach((element) => {
                if (element.contains(event.target)) {
                    gsap.getTweensOf(element).forEach(tween => tween.progress(1));
                }
            });
        };
        document.addEventListener('focusin', revealFocused);
        return () => document.removeEventListener('focusin', revealFocused);
    });

    motion.add('(min-width: 1200px) and (min-height: 700px) and (prefers-reduced-motion: no-preference)', () => {
        const cards = gsap.utils.toArray('.aci-service');
        cards.slice(0, -1).forEach((card) => {
            ScrollTrigger.create({
                trigger: card,
                start: 'top 118px',
                endTrigger: cards[cards.length - 1],
                end: 'top 118px',
                pin: true,
                pinSpacing: false
            });
        });
    });

    motion.add('(min-width: 801px) and (prefers-reduced-motion: no-preference)', () => {
        gsap.utils.toArray('.aci-visual, .service-thumb-wrap').forEach((frame) => {
            const img = frame.querySelector('img');
            if (!img) return;
            gsap.fromTo(img, { yPercent: -5, scale: 1.13 }, {
                yPercent: 5, scale: 1.13, ease: 'none',
                scrollTrigger: { trigger: frame, start: 'top bottom', end: 'bottom top', scrub: 1 }
            });
        });
    });

    window.addEventListener('load', () => ScrollTrigger.refresh(), { once: true });
}
