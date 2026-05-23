(function () {
    var drawer = document.getElementById('catalog-filters-drawer');
    if (!drawer) {
        return;
    }

    var mq = window.matchMedia('(max-width: 900px)');
    var blocks = drawer.querySelectorAll('.filter-block');

    function setDesktopState() {
        drawer.setAttribute('open', '');
        blocks.forEach(function (block) {
            block.setAttribute('open', '');
        });
    }

    function setMobileState() {
        var hasOpenBlock = false;

        blocks.forEach(function (block) {
            if (!block.hasAttribute('open')) {
                block.removeAttribute('open');
            } else {
                hasOpenBlock = true;
            }
        });

        if (!hasOpenBlock) {
            drawer.removeAttribute('open');
        }
    }

    function sync() {
        if (mq.matches) {
            setMobileState();
            return;
        }
        setDesktopState();
    }

    if (typeof mq.addEventListener === 'function') {
        mq.addEventListener('change', sync);
    } else {
        mq.addListener(sync);
    }

    sync();
})();
