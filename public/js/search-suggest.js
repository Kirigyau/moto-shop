(function () {
    var input = document.getElementById('q');
    var box = document.getElementById('search-suggest');
    if (!input || !box || !input.dataset.searchSuggest) return;

    var wrap = input.closest('.search-wrap');
    var url = input.dataset.searchSuggest;
    var timer = null;
    var ctrl = null;

    function hide() {
        box.hidden = true;
        box.innerHTML = '';
    }

    function esc(s) {
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function render(items) {
        if (!items.length) {
            hide();
            return;
        }
        box.innerHTML = items
            .map(function (p) {
                return (
                    '<a class="search-suggest__item" href="' +
                    esc(p.url) +
                    '">' +
                    (p.image
                        ? '<span class="search-suggest__thumb"><img src="' +
                          esc(p.image) +
                          '" alt="" width="48" height="48" loading="lazy"></span>'
                        : '') +
                    '<span class="search-suggest__body"><span class="search-suggest__title">' +
                    esc(p.title) +
                    '</span><span class="search-suggest__price">' +
                    esc(p.price) +
                    '</span></span></a>'
                );
            })
            .join('');
        box.hidden = false;
    }

    function fetchSuggest(q) {
        if (ctrl) ctrl.abort();
        ctrl = new AbortController();
        fetch(url + '?q=' + encodeURIComponent(q), {
            signal: ctrl.signal,
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then(function (r) {
                return r.json();
            })
            .then(function (data) {
                render((data && data.products) || []);
            })
            .catch(function () {
                hide();
            });
    }

    input.addEventListener('input', function () {
        var q = input.value.trim();
        clearTimeout(timer);
        if (q.length < 2) {
            hide();
            return;
        }
        timer = setTimeout(function () {
            fetchSuggest(q);
        }, 200);
    });

    input.addEventListener('focus', function () {
        var q = input.value.trim();
        if (q.length >= 2 && !box.hidden) return;
        if (q.length >= 2) fetchSuggest(q);
    });

    document.addEventListener('click', function (e) {
        if (wrap && !wrap.contains(e.target)) hide();
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') hide();
    });
})();
