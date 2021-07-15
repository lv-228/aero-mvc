<div class="uk-child-width-1-1" uk-grid>
    <div>
        <div uk-grid>
            <div class="uk-width-auto@m">
                <ul class="uk-tab-left" uk-tab="connect: #component-tab-left; animation: uk-animation-fade">
                    <li><a href="#">Find flight (API)</a></li>
                    <li><a href="#">Users</a></li>
                </ul>
            </div>
            <div class="uk-width-expand@m">
                <ul id="component-tab-left" class="uk-switcher">
                    <li><?php include_once 'check_flight.php'; ?></li>
                    <li><?php include_once 'check_users.php' ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
