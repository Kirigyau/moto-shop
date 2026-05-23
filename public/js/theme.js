(function () {
    var storageKey = 'moto-shop-theme';
    var root = document.documentElement;

    function readStorage() {
        try {
            return localStorage.getItem(storageKey);
        } catch (e) {
            return null;
        }
    }

    function resolveTheme() {
        var stored = readStorage();
        if (stored === 'light' || stored === 'dark') {
            return stored;
        }
        return 'light';
    }

    function apply(theme, persist) {
        if (theme !== 'light' && theme !== 'dark') {
            theme = 'light';
        }
        root.setAttribute('data-theme', theme);
        if (persist) {
            try {
                localStorage.setItem(storageKey, theme);
            } catch (e) {}
        }
        var input = document.getElementById('theme-toggle');
        if (input) {
            input.checked = theme === 'dark';
            input.setAttribute('aria-checked', theme === 'dark' ? 'true' : 'false');
        }
    }

    function onDomReady(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    window.motoShopTheme = {
        init: function () {
            apply(resolveTheme(), false);
            onDomReady(function () {
                apply(resolveTheme(), false);
                var input = document.getElementById('theme-toggle');
                if (input) {
                    input.addEventListener('change', function () {
                        apply(input.checked ? 'dark' : 'light', true);
                    });
                }
            });
        },
        toggle: function () {
            var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            apply(next, true);
        },
    };

    window.motoShopTheme.init();
})();
