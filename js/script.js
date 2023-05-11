document.addEventListener('DOMContentLoaded', function () {
    window.addEventListener('scroll', () => {
        const navbar = document.querySelector('.navbar.home');
        if (navbar instanceof HTMLElement) {
            if (window.scrollY > 200) {
                navbar.classList.add('bg-dark');
                navbar.classList.add('bg-dark');
            } else {
                navbar.classList.remove('bg-dark');
            }
        }
    });

    const carouselElement = document.querySelector('#realisations-carousel');
    if (carouselElement instanceof HTMLElement) {
        const carousel = new bootstrap.Carousel(carouselElement);
    }
});
