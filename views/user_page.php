<?php if(!isset($_GET['adminpanel'])): ?>
<div class="uk-child-width-1-1" uk-grid>
    <div>
        <div uk-grid>
            <div class="uk-width-auto@m">
                <ul class="uk-tab-left" uk-tab="connect: #component-tab-left; animation: uk-animation-fade">
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Tickets (buy/hold)</a></li>
                </ul>
            </div>
            <div class="uk-width-expand@m">
                <ul id="component-tab-left" class="uk-switcher">
                    <li><?php include_once 'user_profile.php'; ?></li>
                    <li><?php include_once 'user_tickets.php'; ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php else: include_once 'adminpanel.php'; endif; ?>
