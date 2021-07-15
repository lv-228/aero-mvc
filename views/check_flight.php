
<form class="uk-grid-small" uk-grid method="post">
    <div class="uk-width-1-4@s">
    	<label>Departure</label>
        <input class="uk-input" type="text" name='dep'>
    </div>
    <div class="uk-width-1-4@s">
    	<label>Arrival</label>
        <input class="uk-input" type="text" name='ari'>
    </div>
    <div class="uk-width-1-4@s">
    	<label>Date Departure</label>
        <input class="uk-input" type="text" name='date'>
    </div>
    <div class="uk-width-1-4@s">
    	<label>Key</label>
        <input class="uk-input" type="text" name='key'>
    </div>
    <input type="hidden" name="find" value='true'>
    <button class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom">Find</button>
</form>

<!-- <ul class="uk-comment-list">
	<?php for($i=0; $i<count($data['data']); $i++): ?>
    <li>
        <article class="uk-comment uk-visible-toggle" tabindex="-1">
            <header class="uk-comment-header uk-position-relative">
                <div class="uk-grid-medium uk-flex-middle" uk-grid>
                    <div class="uk-width-expand">
                        <h4 class="uk-comment-title uk-margin-remove"><a class="uk-link-reset" href="#"><?= $data['data'][$i]['itineraries'][0]['segments'][0]['departure']['iataCode'] . '=>' . $data['data'][$i]['itineraries'][0]['segments'][count($data['data'][$i]['itineraries'][0]['segments']) - 1]['arrival']['iataCode'] ?></a></h4>
                        <p class="uk-comment-meta uk-margin-remove-top"><a class="uk-link-reset" href="#">12 days ago</a></p>
                    </div>
                </div>
                <div class="uk-position-top-right uk-position-small uk-hidden-hover"><a class="uk-link-muted" href="#">Reply</a></div>
            </header>
            <div class="uk-comment-body">
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
            </div>
        </article>
        <ul>
            <li>
                <article class="uk-comment uk-comment-primary uk-visible-toggle" tabindex="-1">
                    <header class="uk-comment-header uk-position-relative">
                        <div class="uk-grid-medium uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <img class="uk-comment-avatar" src="images/avatar.jpg" width="80" height="80" alt="">
                            </div>
                            <div class="uk-width-expand">
                                <h4 class="uk-comment-title uk-margin-remove"><a class="uk-link-reset" href="#">Author</a></h4>
                                <p class="uk-comment-meta uk-margin-remove-top"><a class="uk-link-reset" href="#">12 days ago</a></p>
                            </div>
                        </div>
                        <div class="uk-position-top-right uk-position-small uk-hidden-hover"><a class="uk-link-muted" href="#">Reply</a></div>
                    </header>
                    <div class="uk-comment-body">
                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
                    </div>
                </article>
            </li>
        </ul>
    </li>
	<?php endfor; ?>
</ul> -->