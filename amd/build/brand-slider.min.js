define([], function() {
    var current = 0;
    var timer;

    function goTo(slides, dots, index) {
        slides[current].classList.remove('logini-slide--active');
        dots[current].classList.remove('logini-dot--active');
        current = index;
        slides[current].classList.add('logini-slide--active');
        dots[current].classList.add('logini-dot--active');
    }

    function init() {
        var slider = document.querySelector('.logini-brand-slider');
        if (!slider) {
            return;
        }
        var slides = Array.from(slider.querySelectorAll('.logini-slide'));
        var dots   = Array.from(slider.querySelectorAll('.logini-dot'));

        if (slides.length <= 1) {
            return;
        }

        dots.forEach(function(dot, i) {
            dot.addEventListener('click', function() {
                clearInterval(timer);
                goTo(slides, dots, i);
                timer = setInterval(function() {
                    goTo(slides, dots, (current + 1) % slides.length);
                }, 5000);
            });
        });

        timer = setInterval(function() {
            goTo(slides, dots, (current + 1) % slides.length);
        }, 5000);
    }

    return { init: init };
});
