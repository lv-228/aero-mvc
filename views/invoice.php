<?php
require_once './models/flight_model.php';

$flight = new flight();
$flight_data = $flight->get_by_id($data['data']['flight_id']);

$tickets = [];

$price = $flight_data[0]['price'] . '0000';

for($i = 0; $i < count($data['data']['tickets']); $i++)
{
	$tickets[] = array(
		'price'   => (int)$price,
		'product' => 'Ticket on flight ' . $data['data']['flight_id'],
		'quantity' => 1,
		'taxMode' =>
		[
			'rate' => '10%',
			'type' => 'InvoiceLineTaxVAT'
		]
	);
}

$data_curl = 
[
  'shopID'   => 'adf4b72a-5150-4633-bc72-51c9fa40d8f0',
  'dueDate'  => date('Y-m-d H:i:s', strtotime('-10')) . 'Z',//date(value, strtotime('-10'));
  'amount'   => $price * $data['data']['count'],
  'currency' => 'RUB',
  'product'  => 'Заказ номер ' . time(),
  'description' => 'Авиабилеты',
  'cart' => $tickets,
  'metadata' => 
  [
    'order_id' => 'Заказ с идентификатор ' . time() . $data['data']['flight_id']
  ]
];

//var_dump($data);die;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.bank.standoff.city/v2/processing/invoices",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($data_curl),
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJvUW5UQzdpa3AzdUhIcWswemdCQ2hwVGwtTk5DajVKTE1LVUdEU0RpdXRJIn0.eyJleHAiOjE2NTI3ODY3NjQsImlhdCI6MTYyMTI1MDc2NSwiYXV0aF90aW1lIjoxNjIxMjUwNzY0LCJqdGkiOiI3NWZiMGI3Ny0wZjkzLTQ4ODItYmVjZi05N2M5ZmNmNDg2MmQiLCJpc3MiOiJodHRwczovL2F1dGguYmFuay5zdGFuZG9mZi5jaXR5L2F1dGgvcmVhbG1zL2V4dGVybmFsIiwiYXVkIjpbImNvbW1vbi1hcGkiLCJ1cmwtc2hvcnRlbmVyIiwiYWNjb3VudCJdLCJzdWIiOiIyODEyMjBlYi1hNGVmLTRkMDMtYjY2Ni1iZGVjNGIyNmM1MDEiLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJrb2ZmaW5nIiwibm9uY2UiOiIwOTY2Y2ZhYS02MjNkLTQyZGMtODI2ZC1hNWFmNzFhNjgxZjUiLCJzZXNzaW9uX3N0YXRlIjoiZTAyNzI0NjktZTNhNS00NTA1LTk4NmQtOGY2ODk4Y2JiODc1IiwiYWNyIjoiMSIsImFsbG93ZWQtb3JpZ2lucyI6WyJodHRwczovL2Rhc2hib2FyZC5iYW5rLnN0YW5kb2ZmLmNpdHkiXSwicmVzb3VyY2VfYWNjZXNzIjp7ImNvbW1vbi1hcGkiOnsicm9sZXMiOlsiaW52b2ljZXMuKi5wYXltZW50czp3cml0ZSIsImN1c3RvbWVycy4qLmJpbmRpbmdzOndyaXRlIiwicGFydHk6cmVhZCIsImludm9pY2VzLioucGF5bWVudHM6cmVhZCIsImN1c3RvbWVyczp3cml0ZSIsInBhcnR5OndyaXRlIiwiY3VzdG9tZXJzLiouYmluZGluZ3M6cmVhZCIsImN1c3RvbWVyczpyZWFkIiwiaW52b2ljZXM6d3JpdGUiLCJpbnZvaWNlczpyZWFkIl19LCJ1cmwtc2hvcnRlbmVyIjp7InJvbGVzIjpbInNob3J0ZW5lZC11cmxzOndyaXRlIiwic2hvcnRlbmVkLXVybHM6cmVhZCJdfSwiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwibmFtZSI6IkRlbW8gTWVyY2hhbnQgNCIsInByZWZlcnJlZF91c2VybmFtZSI6ImRlbW9fbWVyY2hhbnRfNCIsImdpdmVuX25hbWUiOiJEZW1vIiwiZmFtaWx5X25hbWUiOiJNZXJjaGFudCA0IiwiZW1haWwiOiJtZXJjaGFudDRAaXRzLmRlbW8ifQ.I_h7M99KIW_Q3PnQeCZUiamcEH_tllGd5o82muYK_RKJi3XLU5PHjeJFuoICwgtfwcfJ7Uu5PKNyZdM2pUr2NVTeekHhXi54ai3Q1O4ScIUUjKlIoiZJQJV6_5KkEcE9qIIzLRnPBkV3Pe_qE3fWhQWlveWtcz8tHZitEFAURFsMZOHTK71b8CJkwq_iPUIHSXQgoVWgj-irlBvKtwP5fWTSFbRB3EfkIr88DiVwTD3tsXQsKLNLFdDaKQdgSz-EA4QT-86I7OhCqA8KoVjL1UEyWWmblwOODpTqCH7ZivaGaymLCAjHRaSBRdIDCV8dFiMGoWRhlfYpYn19gKSZNTG27W-OdO-UiFe3bj2zS9jUNQ1zL7NMwY2WPMRcamDwsMeGguwUhuUOLb9vyQdlb1aKmDCVoTn35q0Rs-7wLCi68Qa9-b2PaROevAF6O7-17115qRESQn-YborMYx1y6mSfw2QUTKWScg1tC-sUSg1L9Ihr-a4nD4T7RXAw5vCtOJ8wNpxwdXpqby6Bx3EWsrX7NC0LLwH0fwEUeTd84KBW1Lxs72_KlJ_cjkq0owqBFwgqRoHk1kfjVa7NOFzunvP6q2gUKajBzdZpenWr0N8J3qGEUecLIfxWzh6PXSpYbGOlsXV4EC1mSSU7Il_IYfzTRhoYAIlyDVsjXmNviI4",
    "X-Request-ID: " . uniqid(),
    "Content-Type: application/json; charset=utf-8"
  ),
));

$response = curl_exec($curl);

curl_close($curl);

$response = json_decode($response,true);
//var_dump($response, $data);die;
//var_dump($data);
$date_current = date('Y-m-d');
?>
	   <h2 class="uk-margin uk-margin-left">Your order has been completed</h2>
	   <form action="index.php?ticket=buy" method="POST" class="uk-margin uk-margin-left">
      <input type="hidden" name="flight_id" value="<?= $data['data']['flight_id'] ?>">
      <input type="hidden" name="count" value="<?= $data['data']['count'] ?>">
      <input type="hidden" name="class" value="<?= $data['data']['class'] ?>">
      <?php for($i=0; $i < count($data['data']['tickets']); $i++): ?>
        <input type="hidden" name="tickets[<?= $i ?>][regdoc]" value="<?= $data['data']['tickets'][$i]['regdoc'] ?>">
        <input type="hidden" name="tickets[<?= $i ?>][fname]" value="<?= $data['data'][$i]['fname'] ?>">
        <input type="hidden" name="tickets[<?= $i ?>][sname]" value="<?= $data['data']['tickets'][$i]['sname'] ?>">
        <input type="hidden" name="tickets[<?= $i ?>][lname]" value="<?= $data['data']['tickets'][$i]['lname'] ?>">
      <?php endfor; ?>
    <script src="https://checkout.bank.standoff.city/checkout.js" class="rbkmoney-checkout"
            data-invoice-id="<?= $response['invoice']['id'] ?>"
            data-invoice-access-token="<?= $response['invoiceAccessToken']['payload'] ?>"
            data-payment-flow-hold="true"
            data-hold-expiration="cancel"            
            data-name="<?= $response['invoice']['product'] ?>"
            data-email='example@mail.com'
            data-label="Payment by card"
            data-description="Avia tickets"
            data-pay-button-label="Payment by card">
    </script>
</form>
