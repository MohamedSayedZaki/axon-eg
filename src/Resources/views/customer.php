<link rel="stylesheet" href="/Public/assets/css/customer.css">

<main class="customer-layout">
<h1>Phone numbers</h1>

<form id="phone-form" action="/" method="get">
    <div class="form-container">
        <div class="form-group">
            <label for="country"> Select Country</label><br>
            <select id="country" name="country" required>
                <option value="">Select country</option>
                <option value="237">Cameroon</option>
                <option value="251">Ethiopia</option>
                <option value="212">Morocco</option>
                <option value="258">Mozambique</option>
                <option value="256">Uganda</option>
            </select>
        </div>

        <div class="form-group">
            <label for="validity"> Select Validity</label><br>
            <select id="validity" name="validity" required>
                <option value="1">Valid phone number</option>
                <option value="2">Invalid phone number</option>
            </select>
        </div>
    </div>
</form>

<div id="customer-results">
    <table>
        <thead>
            <tr>
                <th>Country</th>
                <th>State</th>
                <th>Country Code</th>
                <th>Phone Number</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer) { ?>
                <?php
                $phone = (string) ($customer['phone'] ?? '');
                $phoneParts = $phone !== '' ? preg_split('/\s+/', $phone, 2) : [];
                $countryCodeCell = (string) ($phoneParts[0] ?? '');
                $numberCell = (string) ($phoneParts[1] ?? '');
                $countryCell = (string) ($customer['country'] ?? '');
                $stateCell = ($validity === '1') ? 'ok' : (($validity === '2') ? 'nok' : '');
                ?>
                <tr>
                    <td><?= $countryCell ?></td>
                    <td><?= $stateCell ?></td>
                    <td><?= $countryCodeCell ?></td>
                    <td><?= $numberCell ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php if ($page > 1) { ?>
            <a href="?country=<?= $country ?>&validity=<?= $validity ?>&page=<?= $page - 1 ?>" class="pagination-link" data-page="<?= $page - 1 ?>">Previous</a>
        <?php } ?>
        <?php if (count($customers) === 5) { ?>
            <a href="?country=<?= $country ?>&validity=<?= $validity ?>&page=<?= $page + 1 ?>" class="pagination-link" data-page="<?= $page + 1 ?>">Next</a>
        <?php } ?>
    </div>
</div>

<script src="/Public/assets/js/customer.js" defer></script>
</main>
