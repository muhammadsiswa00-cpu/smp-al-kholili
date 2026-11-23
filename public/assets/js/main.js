/**
 * JavaScript for SMP Islam Al-Kholili Website
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuIcon = document.getElementById('menuIcon');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            if (mobileMenu.classList.contains('hidden')) {
                menuIcon.classList.remove('fa-xmark');
                menuIcon.classList.add('fa-bars');
            } else {
                menuIcon.classList.remove('fa-bars');
                menuIcon.classList.add('fa-xmark');
            }
        });
    }

    // 2. Header shadow & shrink on scroll
    const mainHeader = document.getElementById('mainHeader');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 40) {
            mainHeader.classList.add('shadow-md', 'py-1');
        } else {
            mainHeader.classList.remove('shadow-md', 'py-1');
        }
    });

    // 3. Back to Top Button
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopBtn.classList.remove('hidden');
            } else {
                backToTopBtn.classList.add('hidden');
            }
        });

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // 4. FAQ Accordion
    const faqButtons = document.querySelectorAll('.faq-btn');
    faqButtons.forEach(button => {
        button.addEventListener('click', () => {
            const faqItem = button.closest('.faq-item');
            const isActive = faqItem.classList.contains('active');

            // Close other items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });

            // Toggle current item
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });

    // 5. Gallery Filter (if present)
    const filterButtons = document.querySelectorAll('.gallery-filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            filterButtons.forEach(b => {
                b.classList.remove('bg-brand-700', 'text-white');
                b.classList.add('bg-white', 'text-slate-700');
            });
            btn.classList.add('bg-brand-700', 'text-white');
            btn.classList.remove('bg-white', 'text-slate-700');

            const filterValue = btn.getAttribute('data-filter');

            galleryItems.forEach(item => {
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
