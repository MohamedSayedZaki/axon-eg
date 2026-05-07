(function () {
    'use strict';

    var countryEl = document.getElementById('country');
    var validityEl = document.getElementById('validity');
    var resultsEl = document.getElementById('customer-results');

    if (!countryEl || !validityEl || !resultsEl) {
        return;
    }

    function loadCustomers(page) {
        if (typeof page !== 'number' || isNaN(page) || page < 1) {
            page = 1;
        }

        var country = countryEl.value;
        var validity = validityEl.value;
        var url = '/?country=' + encodeURIComponent(country) + '&validity=' + encodeURIComponent(validity) + '&page=' + encodeURIComponent(page);
        fetch(url, {
            method: 'GET',
            headers: { Accept: 'text/html' },
            credentials: 'same-origin',
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Request failed');
                }
                return response.text();
            })
            .then(function (html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var next = doc.getElementById('customer-results');
                if (next) {
                    resultsEl.innerHTML = next.innerHTML;
                }
            })
            .catch(function () {
                resultsEl.innerHTML = '<p>Could not load customers.</p>';
            });
    }

    countryEl.addEventListener('change', function () {
        loadCustomers(1);
    });
    validityEl.addEventListener('change', function () {
        loadCustomers(1);
    });

    resultsEl.addEventListener('click', function (event) {
        var link = event.target.closest('.pagination-link');
        if (!link || !resultsEl.contains(link)) {
            return;
        }
        event.preventDefault();
        var page = parseInt(link.getAttribute('data-page'), 10);
        if (isNaN(page) || page < 1) {
            return;
        }
        loadCustomers(page);
    });
})();
