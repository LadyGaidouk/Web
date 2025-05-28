document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries, obs) => {
        for (const entry of entries) {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                obs.unobserve(entry.target); // Остановка наблюдения после активации
            }
        }
    }, { threshold: 0.25 });

    document.querySelectorAll('.scroll-animation, img[loading="lazy"]').forEach(el => {
        observer.observe(el);
    });

    const loadStructuredData = async () => {
        try {
            const page = location.pathname.split('/').pop().replace(/\.(php|html)$/, '');
            if (!page) throw new Error("Не удалось определить страницу.");

            const response = await fetch(`data/structured/${page}.json`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

            const data = await response.json();
            const script = document.createElement('script');
            script.type = 'application/ld+json';
            script.textContent = JSON.stringify(data);
            document.head.appendChild(script);
        } catch (error) {
            console.warn('SEO optimization:', error);
        }
    };

    loadStructuredData();
});