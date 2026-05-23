(function () {
    function onReady(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    onReady(function () {
        var sidebar = document.getElementById('admin-sidebar');
        var toggle = document.getElementById('admin-menu-toggle');
        var backdrop = document.getElementById('admin-sidebar-backdrop');

        function setOpen(open) {
            if (!sidebar || !toggle) {
                return;
            }
            document.body.classList.toggle('admin-menu-open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (backdrop) {
                backdrop.hidden = !open;
            }
        }

        if (toggle) {
            toggle.addEventListener('click', function () {
                setOpen(!document.body.classList.contains('admin-menu-open'));
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', function () {
                setOpen(false);
            });
        }

        document.querySelectorAll('[data-admin-toast]').forEach(function (toast) {
            var close = toast.querySelector('.admin-toast__close');
            if (close) {
                close.addEventListener('click', function () {
                    toast.remove();
                });
            }
            window.setTimeout(function () {
                if (toast.parentNode) {
                    toast.classList.add('is-hiding');
                    window.setTimeout(function () {
                        toast.remove();
                    }, 300);
                }
            }, 5000);
        });
    });
})();
