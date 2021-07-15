<?php //var_dump($data); ?>
<div class="uk-overflow-auto">
    <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
        <thead>
            <tr>
                <th></th>
                <th>Way</th>
                <th>Date</th>
                <th>Transfer</th>
                <th>Class</th>
                <th>Special id</th>
                <th><?= !isset($_GET['uid']) ? 'Registration' : 'See flight'; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php for($i=0; $i < count($data['data']['u_tickets']); $i++): ?>
            <?php
                $reg_count = 0;
                $transfer  = $data['data']['ticket_obj']->db_query('SELECT * FROM transfer WHERE flight_id = ' . $data['data']['u_tickets'][$i]['flight']);
                $ticket    = $data['data']['ticket_obj']->check_registration_ticket($data['data']['u_tickets'][$i]['id']);
                $departure = $data['data']['city_obj']->get_by_id($data['data']['u_tickets'][$i]['IATA1'])[0]['name'];
                $arrival   = $data['data']['city_obj']->get_by_id($data['data']['u_tickets'][$i]['IATA2'])[0]['name'];
            ?>
            <tr>
                <td><img class="uk-preserve-width uk-border-circle" src="source/ticket_icon.png" width="40" alt=""></td>
                <td>
                    <p>Departure: <?= $departure . '=>' . $arrival ?></p>
                </td>
                <td>Departure: <?= $data['data']['u_tickets'][$i]['date_d'] ?> | Arrival: <?= $data['data']['u_tickets'][$i]['date_a'] ?></td>
                <td>                
                    <ul uk-accordion="multiple: true">
                        <li>
                            <?php if(!$transfer): ?>
                            <a class="uk-accordion-title" href="#">[Flight]</a>
                            <div class="uk-accordion-content">
                                <ul class="uk-nav-sub">
                                    <li>Departure place: <?= $departure ?></li>
                                    <li>Arrival place: <?= $arrival ?></li>
                                    <li>Date dep: <?= $data['data']['u_tickets'][$i]['date_d'] ?></li>
                                    <li>Date arrival: <?= $data['data']['u_tickets'][$i]['date_a'] ?></li>
                                    <li>Airplane: <?= $data['data']['plane_obj']->get_by_id($data['data']['u_tickets'][$i]['planeid'])[0]['name'] ?> </li>
                                    <li>Carrier: <?= $data['data']['carrier_obj']->get_by_id($data['data']['u_tickets'][$i]['carrier_id'])[0]['full_name'] ?> </li>
                                    <li>Registration place: <?php if(!empty($ticket[$i])) {echo $ticket[$i]['sit']; $reg_count++;} else echo 'Not registration'; ?></li>
                                </ul>
                            </div>
                                <?php else: ?>
                                <?php //var_dump($transfer);die; ?>
                                <a class="uk-accordion-title" href="#">[Transfers]</a>
                                <div class="uk-accordion-content">
                                <?php for($j=0; $j<count($transfer); $j++): ?>
                                <ul class="uk-nav-sub">
                                    <li>Departure place: <?= $data['data']['city_obj']->get_by_id($transfer[$j]['IATA1'])[0]['name'] ?></li>
                                    <li>Arrival place: <?= $data['data']['city_obj']->get_by_id($transfer[$j]['IATA2'])[0]['name'] ?></li>
                                    <li>Date dep: <?= $transfer[$j]['date_dep'] ?></li>
                                    <li>Date arrival: <?= $transfer[$j]['date_arrival'] ?></li>
                                    <li>Airplane: <?= $data['data']['plane_obj']->get_by_id($transfer[$j]['planeid'])[0]['name'] ?> </li>
                                    <li>Carrier: <?= $data['data']['carrier_obj']->get_by_id($transfer[$j]['carrier_id'])[0]['full_name'] ?> </li>
                                    <li>Registration place: <?php if(!empty($ticket)){ echo $ticket[$j]['sit']; $reg_count++; } else echo 'Not registration' ?></li>
                                </ul>
                                <?php endfor; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </li>
                    </ul>
                </td>
                <td></td>
                <td><?= $data['data']['ticket_obj']->db_query('SELECT special_id FROM user_order WHERE ticket_id = ' . $data['data']['u_tickets'][$i]['id'])[0]['special_id'] ?></td>
                <td>
                    <?php if(!isset($_GET['uid'])): ?>
                    <form action='index.php?flightreg' method='get' style="text-align: center;">
                        <input type="hidden" name="flight_id" value='<?= $result[$i][0] ?>'>
                        <input type="hidden" name="ticket_id" value='<?= $result[$i][10] ?>'>
                        <input type="hidden" name="transfer" value="<?= $transfer != false ? 'true' : 'false'; ?>">
                        <?php if($reg_count === count($ticket)){echo '<button class="uk-button uk-button-primary uk-button-small">Registration passed</button>';}elseif($reg_count > 0 && $reg_count < count($ticket)){echo '<button class="uk-button uk-button-secondary uk-button-small">Incomplete registration</button>';} else {echo '<button class="uk-button uk-button-danger uk-button-small">Registration required</button>';}?>
                    </form>
                    <br>
<!--                     <form action='serverscripts/service_logic.php' method="post" style="text-align: center;">
                        <input type="hidden" name='ticket_id' value='<?= $result[$i][10] ?>'>
                        <input type="hidden" name='delete' value='true'>
                        <button class="uk-button uk-button-danger uk-button-small">Delete</button>
                    </form> -->
                    <?php else: ?>
                    <a href='index.php?page_controller=flight_reg&fid=<?= $data['data']['u_tickets'][$i]['flight'] ?>&tid=<?= $data['data']['u_tickets'][$i]['id'] ?>'>Registration info</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</div>