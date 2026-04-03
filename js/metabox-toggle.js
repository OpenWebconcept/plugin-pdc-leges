(function () {
    var checkbox = document.getElementById('_pdc-lege-use-percentage');

    if (!checkbox) {
        return;
    }

    var priceFields = [
        document.getElementById('_pdc-lege-price').closest('.cmb-row'),
        document.getElementById('_pdc-lege-new-price').closest('.cmb-row'),
    ];

    var percentageFields = [
        document.getElementById('_pdc-lege-percentage').closest('.cmb-row'),
        document.getElementById('_pdc-lege-new-percentage').closest('.cmb-row'),
    ];

    function toggle() {
        var usePercentage = checkbox.checked;

        priceFields.forEach(function (row) {
            row.style.display = usePercentage ? 'none' : '';
        });

        percentageFields.forEach(function (row) {
            row.style.display = usePercentage ? '' : 'none';
        });
    }

    checkbox.addEventListener('change', toggle);
    toggle();
})();
