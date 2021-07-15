<div class="uk-alert-primary" uk-alert>
    <a class="uk-alert-close" uk-close></a>
    <p>Want to work with us? Send your vacancies to hr@hsl.gqs</p>
</div>
<div class="uk-alert-warning" uk-alert>
    <a class="uk-alert-close" uk-close></a>
    <p>Hello! We are glad to see you on our website for buying air tickets, our ticket search works with IATA airport codes, at the moment, due to limited air traffic, flights are only possible: ABQ => SEA, DME => BER, HKG => SIN, FRA => SFO, YYZ => LAS</p>
</div>
<div class="uk-position-relative uk-visible-toggle" tabindex="-1" uk-slideshow="animation: push; min-height: 500; max-height: 600;">
    <ul class="uk-slideshow-items">
        <li>
            <img src="source/bashna.jpg" alt="" uk-cover>
            <div class="uk-overlay uk-overlay-primary uk-position-bottom uk-text-center">
                <h3 class="uk-margin-remove">Overlay Bottom</h3>
                <p class="uk-margin-remove">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
        </li>
        <li>
            <img src="source/venecia.jpg" alt="" uk-cover>
            <div class="uk-overlay uk-overlay-primary uk-position-bottom uk-text-center">
                <h3 class="uk-margin-remove">Overlay Bottom</h3>
                <p class="uk-margin-remove">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
        </li>
        <li>
            <img src="source/yorke.jpg" alt="" uk-cover>
            <div class="uk-overlay uk-overlay-primary uk-position-bottom uk-text-center">
                <h3 class="uk-margin-remove">Overlay Bottom</h3>
                <p class="uk-margin-remove">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
        </li>
        <li>
            <img src="source/most.jpg" alt="" uk-cover>
            <div class="uk-overlay uk-overlay-primary uk-position-bottom uk-text-center">
                <h3 class="uk-margin-remove">Overlay Bottom</h3>
                <p class="uk-margin-remove">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
        </li>
    </ul>

    <div class="uk-light">
        <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slideshow-item="previous"></a>
        <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slideshow-item="next"></a>
    </div>
<p><a name="tickets"></a></p>
<h2 class="uk-text-center uk-heading-line"><span>Choose direction</span></h2>

</div>
<form id='tickets' name='user_data' class="uk-grid-small" uk-grid action='index.php'>
    <input type="hidden" name="ticket" value="main">
    <div class="uk-width-1-3@s">
        <label><input id='dep' class="uk-input uk-form-success" type="text" placeholder="Departure" name="origin" list="cities" onchange="findDep()" required="" value="<?= $dep = isset($_GET['origin']) ? $_GET['origin'] : '' ?>"> Departure point </label>
<!--         <datalist id="cities">
            <?php foreach ($json_array as $key => $value): ?>
                <?= "<option value='$key' />"?>
            <?php endforeach; ?>
        </datalist> -->
    </div>
    <div class="uk-width-1-3@s">
        <label><input id='arrival_input' class="uk-input" type="text" placeholder="Arrival" name="destination" list="arrival" required="" value="<?= $arri = isset($_GET['destination']) ? $_GET['destination'] : '' ?>"> Arrival point </label>
        <datalist id="arrival">
        </datalist>
    </div>
    <div class="uk-width-1-3@s">
        <label><input class="uk-input" type="date" placeholder="Вылет __.__.____" name='departure_date' required="" value='<?= isset($_GET['departure_date']) ? $_GET['departure_date'] : $GLOBALS["date"] ?>' min='<?= $GLOBALS["date"] ?>'> Departure date </label>
    </div>
    <div class="boundary-align uk-panel uk-placeholder uk-align-center">
    <button class="uk-button uk-button-default uk-float-left" type="button">Tickets</button>
    <div uk-dropdown="pos: bottom-justify; boundary: .boundary-align; boundary-align: true">
        <ul class="uk-nav uk-dropdown-nav">
            <!-- <li class="">Взрослые</a></li> -->
            <li class="uk-nav-divider"></li>
            <li><input class="uk-input" type="text" placeholder="Number of tickets" name='adults' required="" value="<?= $number = isset($_GET['adults']) ? $_GET['adults'] : '' ?>"></li>
            <li class="uk-nav-header">Class</li>
            <li class="uk-nav-divider"></li>
            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                <label><input class="uk-radio" type="radio" <?= isset($_GET['class']) && $_GET['class'] == 'eco' ? 'checked' : '' ?> name='class' value='eco'> Econom</label>
                <label><input class="uk-radio" type="radio" <?= isset($_GET['class']) && $_GET['class'] == 'bui' ? 'checked' : '' ?> name='class' value='bui'> Business</label>
            </div>
            <li class="uk-nav-divider"></li>
            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                <label><input class="uk-radio" type="radio" <?= isset($_GET['non_stop']) && $_GET['non_stop'] == 'false' ? 'checked' : '' ?> name='non_stop' value='false'>With transfers</label>
                <label><input class="uk-radio" type="radio" <?= isset($_GET['non_stop']) && $_GET['non_stop'] == 'true' ? 'checked' : '' ?> name='non_stop' value='true'>Non stop</label>
            </div>
            <li class="uk-nav-divider"></li>
        </ul>
    </div>
    </div>
    <button class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom">Find tickets</button>
</form>
<br>
<p><a name="tours"></a></p>
<h2 class="uk-text-center uk-heading-line"><span>Tickets</span></h2>
<br>

<?php if(isset($data) && is_array($data)) include 'tickets_table.php'; else echo '<p class="uk-text-center uk-text-danger"> Tickets not found! </p>'; ?>

<hr class="uk-divider-icon">