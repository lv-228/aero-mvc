<?php
//var_dump($data);die;
    if(empty($data['data']))
    {
        echo '<p class="uk-text-center uk-text-danger"> Tickets not found! </p>';
        die;
    }
    $dep     = $data['data']['i1'];
    $arrival = $data['data']['i2'];
    $result  = $data['data']['result'];
    // $count_page = count($result) / 5;
    // $rnd_count_page = ceil($count_page);
    // $count_last = count($result) % 5;
    // if(!isset($_GET['page']) || $_GET['page'] > $rnd_count_page)
    // {
    //     $_GET['page'] = 1;
    // }

    // $uri = explode('&', $_SERVER['REQUEST_URI']);

    // for($w = 0; $w < count($uri); $w++)
    // {
    //     if(explode('=', $uri[$w])[0] === 'page')
    //     {
    //         unset($uri[$w]);
    //         break;
    //     }
    // }
    // $uri[$w] = 'page=' . 1;
    // $first_page = implode('&', $uri);
    // $uri[$w] = 'page=' . ($_GET['page'] - 1);
    // $prev_page = implode('&', $uri);
    // $uri[$w] = 'page=' . ($_GET['page'] + 1);
    // $next_page = implode('&', $uri);
    // $elems = $_GET['page'] == $rnd_count_page ? count($result) : $_GET['page'] * 5;
    
    //var_dump($data['data']['ticket_obj']->parse_find_transfers($transfer));die;
?>
<div uk-filter="target: .js-filter">

    <div class="uk-grid-small uk-grid-divider uk-child-width-auto" uk-grid>
        <div>
            <ul class="uk-subnav uk-subnav-pill" uk-margin>
                <li class="uk-active" uk-filter-control><a href="#">All</a></li>
            </ul>
        </div>
        <div>
            <ul class="uk-subnav uk-subnav-pill" uk-margin>
                <li uk-filter-control="filter: [data-transfer='true']; group: data-transfer"><a href="#">Transfer</a></li>
                <li uk-filter-control="filter: [data-transfer='false']; group: data-transfer"><a href="#">Without Transfer</a></li>
            </ul>
        </div>
        <div>
            <ul class="uk-subnav uk-subnav-pill" uk-margin>
                <li uk-filter-control="filter: [data-type='all']; group: type"><a href="#">Eco & Bui</a></li>
                <li uk-filter-control="filter: [data-type='eco']; group: type"><a href="#">Econom (only)</a></li>
                <li uk-filter-control="filter: [data-type='bui']; group: type"><a href="#">Buiseness (only)</a></li>
            </ul>
        </div>
    </div>

    <ul class="js-filter uk-child-width-1-1 uk-child-width-1-1 uk-text-center" uk-grid="masonry: true">
        <?php for($i = 0; $i < count($result); $i++): ?>
        <?php
            $f_id     = $data['data']['result'][$i]['id'];
            $econom   = $data['data']['ticket_obj']->check_free_tickets($f_id, 15);
            $bui      = $data['data']['ticket_obj']->check_free_tickets($f_id, 16);
        ?>
        <li data-transfer="<?= $transfer ? 'true' : 'false' ?>" data-type="<?php if($econom === true && $bui === true){echo 'all';} elseif($econom === true) echo 'eco'; elseif($bui === true) echo 'bui' ?>">
            <div class="uk-card uk-card-default uk-card-body" style="outline: 1px solid #0080ff;">
    <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
        <li class="uk-active">Departure: <?= $dep ?> <span uk-icon='forward'></span> Arrival: <?= $arrival ?>  </li>
        <?php if(isset($_GET['non_stop']) && $_GET['non_stop'] === 'false'): ?>
            <?php
                $transfer = $data['data']['ticket_obj']->get_transfers($f_id);
                if($transfer):
                   $transfer_parse = $data['data']['ticket_obj']->parse_find_transfers($transfer);
            ?>
            <?php for($j = 0; $j < count($transfer_parse); $j++): ?>
            <li class="uk-parent">
            <a href="#">Transfer <?php $dep_trans = $transfer_parse[$j]['i1']; ?> <?= $dep_trans . '=>' . $arrival_trans = $transfer_parse[$j]['i2']; ?></a>
            <ul class="uk-nav-sub">
                <li>Departure place: <?= $dep_trans ?></li>
                <li>Arrival place: <?= $arrival_trans ?></li>
                <li>Date dep: <?= $transfer_parse[$j]['date_d'] ?></li>
                <li>Date arrival: <?= $transfer_parse[$j]['date_a'] ?></li>
                <li>Airplane: <?= $transfer_parse[$j]['plane'] ?></li>
                <li>Carrier: <?= $transfer_parse[$j]['carrier'] ?> </li>
            </ul>
            </li>
            <?php endfor; ?>
        <?php endif; ?>
        <?php endif; ?>
        <div class="uk-flex uk-flex-center">
            <li class='uk-overlay uk-overlay-default'><span class="uk-margin-small-right" uk-icon="icon: future"></span> Departure: <?= $data['data']['result'][$i]['i1'] ?></li>
            <li class='uk-overlay uk-overlay-default'><span class="uk-margin-small-right" uk-icon="icon: history"></span> Arrival: <?= $data['data']['result'][$i]['i2'] ?></li>
        </div>
        <li></span> Airplane: <?= $data['data']['result'][$i]['plane'] ?></li>
        <li><form action="index.php?ticket=invoice" method='post' style="text-align: center;" id='ticket_form<?= $result[$i]['id'] ?>'>
                <input type="hidden" name="flight_id" value='<?= $result[$i]['id'] ?>'>
                <div class="uk-flex uk-flex-center">
                    <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                        <label><input class="uk-radio" type="radio" name="class" value="eco" checked <?= $econom === true ? '' : 'disabled' ?>> Economy</label>
                        <label><input class="uk-radio" type="radio" name="class" value="bui" <?= $bui === true ? '' : 'disabled' ?>> Business</label>
                    </div>
                </div>
                <?php if(isset($_SESSION['login'])):?>
                <label> Count </label>
                    <input onchange="checkTicketCount(<?= $i ?>, this);" id='cnt<?= $i ?>' class="uk-input uk-form-width-xsmall" type="number" name='count' min='1' max='<?= $result[$i]['free_sits'] ?>' value='1' required>
                <!-- <button class="uk-button uk-button-primary" type='submit' ><span class="uk-margin-small-right" uk-icon="plus-circle"></span> Buy <?= $result[$i][3] ?> $ (for one) </button> -->
                    <button onclick='ftch("views/printForms.php", {"cnt" : document.getElementById("cnt<?= $i ?>").value, "flight_id" : <?= $result[$i]['id'] ?>}); return false;' id='btn<?= $i ?>' class="uk-button uk-button-primary" href="#modal-overflow" uk-toggle><span class="uk-margin-small-right" uk-icon="plus-circle" ></span>Buy <?= $result[$i]['price'] . '00' ?> (for one)</button>
                <?php else: ?>
                    <button disabled class="uk-button uk-button-primary" href="#modal-overflow" uk-toggle><span class="uk-margin-small-right" uk-icon="plus-circle" ></span>Need auth</button>
                <?php endif; ?>
            </form></li>
        <li></li>
    </ul>
            </div>
        </li>
        <?php endfor; ?>
    </ul>
</div>
<div id="modal-overflow" uk-modal>
    <div class="uk-modal-dialog">

        <button class="uk-modal-close-default" type="button" uk-close></button>

        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Personal data</h2>
        </div>

        <div class="uk-modal-body" uk-overflow-auto>
            <h3>Please indicate your documents</h3>
            <div uk-grid>
                    <div>
                        <div uk-grid>
                            <div class="uk-width-auto@m">
                                <ul id='ulswitcher' class="uk-tab-left" uk-tab="connect: #component-tab-left; animation: uk-animation-fade">
                                    <!-- <li><a href="#">Ticket 1</a></li> -->
                                </ul>
                            </div>
                            <div class="uk-width-expand@m">
                                <ul id="component-tab-left" class="uk-switcher">
                                    <!-- <li id='liform'>
                                        <div class="uk-margin">
                                                <label class="uk-form-label">Document</label>
                                                <input form='ticket_form' id='doc_reg' class="uk-input uk-form-blank uk-form-width-small" type="text" name='reg_doc' placeholder="____ ______" pattern="\d{4}\s\d{6}" maxlength="11" required="">
                                        </div>
                                        <div class="uk-margin">
                                                <label class="uk-form-label">First name</label>
                                                <input form='ticket_form' id='fname' class="uk-input uk-form-blank uk-form-width-large" type="text" name='reg_doc' placeholder="__________" required="">
                                        </div>
                                        <div class="uk-margin">
                                                <label class="uk-form-label">Second name</label>
                                                <input form='ticket_form' id='sname' class="uk-input uk-form-blank uk-form-width-large" type="text" name='reg_doc' placeholder="__________" required="">
                                        </div>
                                        <div class="uk-margin">
                                                <label class="uk-form-label">Last name</label>
                                                <input form='ticket_form' id='lname' class="uk-input uk-form-blank uk-form-width-large" type="text" name='reg_doc' placeholder="__________" required="">
                                        </div>
                                    </li> -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

        </div>

        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button id='btn_ticket_form' class="uk-button uk-button-primary" type='submit' form='ticket_form'><span class="uk-margin-small-right" uk-icon="plus-circle"></span> Buy </button>
        </div>
    </div>
</div>
<script src="js/my.js"></script>
<script type="text/javascript">

    function checkTicketCount(i, e)
    {
        var btn = document.getElementById('btn' + i);
        if(e.value <= 0 || e.value === undefined)
        {
            alert('Укажите колличество билетов!');
            btn.setAttribute('disabled', 'disabled');
            return false;
        }
        else
            btn.removeAttribute('disabled');
    }

</script>
