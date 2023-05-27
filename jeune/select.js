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
    var texte1 = '<br><br><input type="radio" id="all" name="option_vue" value="all" required><label for="all">Ignorer la séléction de compétences ci-  dessus et séléctionner toutes les expériences (y compris celles que vous entrerez ultérieurement)</label>';
    var texte2 = '<br><input type="radio" id="selected" name="option_vue" value="selected" required><label for="selected">se référer à la sélection ci-dessus</label>';
    var texte3 = '<br><br><label for="email">Adresse e-mail du référent :</label><input type="email" id="email" name="email" required>';
    mail.innerHTML = texte1 + texte2 + texte3;
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