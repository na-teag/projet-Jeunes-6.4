function check(){
    var checkboxes = document.getElementsByName('skills[]');
    for(var i=0; i<checkboxes.length; i++){
        checkboxes[i].checked = true;
    }
}

function uncheck(){
    var checkboxes = document.getElementsByName('skills[]');
    for(var i=0; i<checkboxes.length; i++){
        checkboxes[i].checked = false;
    }
}

function show() {
    var mail = document.getElementById('email');
    mail.innerHTML = '<label for="email">Adresse e-mail du référent :</label><input type="email" id="email" name="email" required>';
}

function hide() {
    var mail = document.getElementById('email');
    mail.innerHTML = '';
}

function goToArchive() {
    window.location.href = "archive.php";
}

function goToSelect() {
    window.location.href = "select.php";
}