window.onload = function () {
    document.getElementById("pas1").onchange = validatePassword;
    document.getElementById("pas2").onchange = validatePassword;
}
function validatePassword(){
var pass2=document.getElementById("pas2").value;
var pass1=document.getElementById("pas1").value;
if(pass1!=pass2)
    document.getElementById("pas2").setCustomValidity("Passwords Don't Match");
else
    document.getElementById("pas2").setCustomValidity('');
//empty string means no validation error
}