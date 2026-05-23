(function () {
    var wrap = document.querySelector('.cart-wrap');
    if (!wrap) {
        return;
    }

    var preview = wrap.querySelector('.cart-preview');
    var toggle = wrap.querySelector('.cart-toggle');
    if (!preview || !toggle) {
        return;
    }

    var pad = 12;

    function place() {
        var width = Math.min(320, window.innerWidth - pad * 2);
        var toggleRect = toggle.getBoundingClientRect();
        var left = toggleRect.right - width;

        left = Math.max(pad, Math.min(left, window.innerWidth - width - pad));

        preview.classList.add('is-fixed');
        preview.style.width = width + 'px';
        preview.style.left = left + 'px';
        preview.style.top = toggleRect.bottom + 6 + 'px';
    }

    function clear() {
        preview.classList.remove('is-fixed');
        preview.style.width = '';
        preview.style.left = '';
        preview.style.top = '';
    }

    function isOpen() {
        return wrap.matches(':hover') || wrap.matches(':focus-within');
    }

    wrap.addEventListener('mouseenter', place);
    wrap.addEventListener('focusin', place);

    wrap.addEventListener('mouseleave', clear);
    wrap.addEventListener('focusout', function (e) {
        if (!wrap.contains(e.relatedTarget)) {
            clear();
        }
    });

    window.addEventListener('resize', function () {
        if (isOpen()) {
            place();
        }
    });

    window.addEventListener('scroll', function () {
        if (isOpen()) {
            place();
        }
    }, true);
})();
