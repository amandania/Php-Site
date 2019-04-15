/**
 * Register Account
 * Reads form input
 * Writes form input onto a .txt
 *
 */
function Account(username, password, email, ip_address) {
    this.creds = {
        'username': username,
        'password' : password,
        'email' : email,
        'ip_address' : ip_address
    };
}
function errorMessage(errorSize) {
    var message = ["Username is to short!", "Password is to short!", "Invalid Email Address!"]
    for (var i = 1 ; i <= errorSize; i++) {
        document.getElementById('errormsg'+i).innerHTML = message[i-1];
    }
}

var ALL_MEMBERS = [];

function writeAccount(account) {
    var json = JSON.stringify(account);
    console.log(json);
    //write to database..write ip too so onload i can lookup if it exits to load saved account on startup.
    ALL_MEMBERS.push(account); // add to end of list..
    document.getElementById("registerForm").reset();

}
function loadMembers() {
    //grab database info and load into table.
}
function createAccount(form) {
    var errors = 0;
    if (form.username.value.length < 4) {
        errors++;
    }
    if (form.password.value.length < 5) {
        errors++;
    }
    if(errors > 0) {
        errorMessage(errors);
        return;
    }
    var account = new Account(form.username.value, form.password.value, form.email.value);

    $.getJSON('https://api.ipify.org?format=json', function(data){
        account.creds.ip_address = data.ip.toString();
        console.log(account.creds.ip_address); // lol...sorry? its not logged...yet..
    });

    writeAccount(account);

}
function startup() {
    loadMembers();
    doDate();
}
function doDate()
{
    var str = "";

    var days = new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

    var now = new Date();

    str += "Today is: " + days[now.getDay()] + ", " + now.getDate() + " " + months[now.getMonth()] + " " + now.getFullYear() + " " + now.getHours() +":" + now.getMinutes() + ":" + now.getSeconds();
    document.getElementById("currentDate").innerHTML = str;

}
setInterval(doDate, 1000);