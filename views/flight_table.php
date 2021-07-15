<?php if(!isset($_GET['transfer'])): ?>
<ul class="uk-subnav uk-subnav-pill" uk-switcher="animation: uk-animation-slide-left-medium, uk-animation-slide-right-medium">
    <li><a href="#">My flight</a></li>
    <li <?= isset($_GET['page']) ? 'class="uk-active"' : '' ; ?>><a href="#">All</a></li>
</ul>
<ul class="uk-switcher uk-margin">
    <li><?php require_once 'user_flight.php'; ?></li>
    <li><?php require_once 'flight_table_all.php'; ?></li>
</ul>
<?php elseif(isset($_GET['transfer']) && $transfers = $connect->query('SELECT * FROM transfer WHERE id = ' . $_GET['transfer'])): ?>
<?php require_once 'user_transfer.php'; ?>
<?php endif; ?>