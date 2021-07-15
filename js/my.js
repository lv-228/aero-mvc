function findDep() {
	var keys = {}, html = '';

	val     = document.getElementById('dep').value;
	json    = document.getElementById('json').value;
	if(json.indexOf(val) != 0){
		arrival = document.getElementById('arrival');
		while (arrival.firstChild) {
			arrival.removeChild(arrival.firstChild);
		}
 		options = JSON.parse(json)[val.toUpperCase()];
		for(i = 0; i < options.length; i++){
			option = document.createElement('option');
			option.value = options[i];
			arrival.append(option);
		}
		arrival_input = document.getElementById('arrival_input');
		arrival_input.removeAttribute('disabled');
		return;
	}
	arrival_input = document.getElementById('arrival_input');
	arrival_input.setAttribute('disabled', '');
	arrival_input.setAttribute('value', '');
}

function messageToUser() {
	arrival_input = document.getElementById('arrival_input');
	err = document.getElementById('err');
	if(err == null){
		form = document.getElementById('tickets');
		div = document.createElement('div');
		a = document.createElement('a');
		p = document.createElement('p');
		p.innerHTML = 'Выберите место отправления';
		div.setAttribute('id', 'err');
		div.setAttribute('class', 'uk-alert-danger');
		div.setAttribute('uk-alert', '');
		a.setAttribute('class', 'uk-alert-close');
		a.setAttribute('uk-close', ''); 
		if(arrival_input.hasAttribute('disabled')){
			arrival_input.value = '';
			form.after(div);
			div.append(a);
			div.append(p);
		}
	}
}

function ajax(url)
{
	var request = new XMLHttpRequest();
    request.open('GET', url, true);
    request.setRequestHeader('accept', 'aplication/json');
    request.send();

    if (request.status != 200)
    {
  		console.log(request.status + ': ' + request.statusText ); // пример вывода: 404: Not Found
	} 
	else
	{
  		console.log(request.responseText);
	}
}

function ftch(url, usrData) {
postData(url, usrData)
  .then((data) => {
  	var btn_ticket_form = document.getElementById('btn_ticket_form');
  	btn_ticket_form.setAttribute('form', 'ticket_form' + usrData['flight_id']);
  	var parser = new DOMParser();
	var liforms_from_answer = parser.parseFromString(JSON.stringify(data['liform']).replace(/u0022/g, '"'), 'text/html');
	var liswitcher_from_answer = parser.parseFromString(JSON.stringify(data['liswitcher']).replace(/u0022/g, '"'), 'text/html');
	var liform = liforms_from_answer.querySelectorAll('li');
	var liswitcher = liswitcher_from_answer.querySelectorAll('li');
	var get_switcher = document.getElementById('ulswitcher');
	var	get_forms_ul = document.getElementById('component-tab-left');

	while(get_switcher.firstChild)
	{
  		get_switcher.removeChild(get_switcher.firstChild);
	}

	while(get_forms_ul.firstChild)
	{
  		get_forms_ul.removeChild(get_forms_ul.firstChild);
	}
    
    liswitcher.forEach(function (item) {
    	get_switcher.appendChild(item);
    });

    liform.forEach(function (item) {
    	get_forms_ul.appendChild(item);
    });

    console.log(data);
  });
}

async function postData(url = '', data = {}) {
  // Default options are marked with *
  const response = await fetch(url, {
    method: 'POST', // *GET, POST, PUT, DELETE, etc.
    mode: 'cors', // no-cors, *cors, same-origin
    cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
    credentials: 'same-origin', // include, *same-origin, omit
    headers: {
      'Content-Type': 'application/json'
      // 'Content-Type': 'application/x-www-form-urlencoded',
    },
    redirect: 'follow', // manual, *follow, error
    referrerPolicy: 'no-referrer', // no-referrer, *client
    body: JSON.stringify(data) // body data type must match "Content-Type" header
  });
  return await response.json(); // answer
}

function setFlightId(flight_id) {
	var btn_ticket_form = document.getElementById('btn_ticket_form');
	var input_doc = document.getElementById('doc_reg');
	var input_fname = document.getElementById('fname');
	var input_sname = document.getElementById('sname');
	var input_lname = document.getElementById('lname');
  	btn_ticket_form.setAttribute('form', 'ticket_form' + flight_id);
  	input_doc.setAttribute('form', 'ticket_form' + flight_id);
  	nput_fname.setAttribute('form', 'ticket_form' + flight_id);
  	input_sname.setAttribute('form', 'ticket_form' + flight_id);
  	input_lname.setAttribute('form', 'ticket_form' + flight_id);

}
