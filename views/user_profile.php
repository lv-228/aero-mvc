<h1 class="uk-heading-line uk-text-center"><span><?php echo 'User profile: '; ?></span></h1>
<p>Gender: <?= $gender = $data['data']['u_data'][0]['sex'] === 1 ? 'male' : 'female'  ?></p>
<p>Age: <?= $data['data']['u_data'][0]['age'] ?></p>
<p>Full name: <?= $data['data']['u_data'][0]['first_name'] . ' '  . $data['data']['u_data'][0]['second_name'] . ' ' . $data['data']['u_data'][0]['last_name'] ?> </p>
<p>Email: <?= $data['data']['u_data'][0]['email'] ?></p>