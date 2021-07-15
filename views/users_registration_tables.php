<ul uk-tab>
    <li <?= isset($_GET['id']) ? 'class="uk-active"' : '' ; ?>><a href="#">Find by flight id</a></li>
    <li <?= isset($_GET['special_id']) ? 'class="uk-active"' : '' ; ?>><a href="#">Find by special id</a></li>
    <li <?= isset($_GET['by']) ? 'class="uk-active"' : '' ; ?>><a href="#">Find by user name/docs</a></li>
</ul>

<ul class="uk-switcher uk-margin">
    <li><?php require_once 'find_flight.php'; ?></li>
    <li><?php require_once 'find_by_special.php'; ?></li>
    <li><?php require_once 'find_by_document.php'; ?></li>
</ul>
