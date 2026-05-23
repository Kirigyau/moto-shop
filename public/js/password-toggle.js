(function () {
    function onReady(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    function setVisible(button, input, visible) {
        input.type = visible ? 'text' : 'password';
        button.setAttribute('aria-pressed', visible ? 'true' : 'false');
        button.setAttribute('aria-label', visible ? 'Скрыть пароль' : 'Показать пароль');

        var showIcon = button.querySelector('.password-field__icon--show');
        var hideIcon = button.querySelector('.password-field__icon--hide');

        if (showIcon) {
            showIcon.hidden = visible;
        }
        if (hideIcon) {
            hideIcon.hidden = !visible;
        }
    }

    onReady(function () {
        document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
            var id = button.getAttribute('aria-controls');
            var input = id ? document.getElementById(id) : null;

            if (!input) {
                return;
            }

            button.addEventListener('click', function () {
                setVisible(button, input, input.type === 'password');
            });
        });
    });
})();
