(function () {
    function onReady(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    onReady(function () {
        var root = document.querySelector('[data-hero-slider]');
        if (!root) {
            return;
        }

        var slides = root.querySelectorAll('[data-hero-slide]');
        if (slides.length < 2) {
            return;
        }

        var dots = root.querySelectorAll('[data-hero-dot]');
        var interval = parseInt(root.getAttribute('data-interval') || '10000', 10);
        if (interval < 3000) {
            interval = 10000;
        }

        var index = 0;
        var timer = null;

        function setActive(i) {
            slides[index].classList.remove('is-active');
            if (dots[index]) {
                dots[index].classList.remove('is-active');
            }
            index = i;
            slides[index].classList.add('is-active');
            if (dots[index]) {
                dots[index].classList.add('is-active');
            }
        }

        function next() {
            setActive((index + 1) % slides.length);
        }

        function start() {
            stop();
            timer = window.setInterval(next, interval);
        }

        function stop() {
            if (timer !== null) {
                window.clearInterval(timer);
                timer = null;
            }
        }

        root.addEventListener('mouseenter', stop);
        root.addEventListener('mouseleave', start);

        start();
    });
})();
