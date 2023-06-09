function check(){ // cocher toutes les checkbox
    var checkboxes = document.getElementsByName('skills[]');
    for(var i=0; i<checkboxes.length; i++){
        checkboxes[i].checked = true;
    }
}

function uncheck(){ // décocher toutes les checkbox
    var checkboxes = document.getElementsByName('skills[]');
    for(var i=0; i<checkboxes.length; i++){
        checkboxes[i].checked = false;
    }
}

function show_email(){ // montrer la partie mail et cacher la partie du choix de format de fichier
    var file = document.getElementById('file');
    file.innerHTML = '';
    var mail = document.getElementById('email');
    var texte1 = '<br><br><input type="radio" id="all" name="option_email" value="all" required><label for="all">Ignorer la séléction de compétences ci-  dessus et séléctionner toutes les expériences (y compris celles que vous entrerez ultérieurement)</label>';
    var texte2 = '<br><input type="radio" id="selected" name="option_email" value="selected" required><label for="selected">se référer à la sélection ci-dessus</label>';
    var texte3 = '<br><br><label for="email">Adresse e-mail du consultant :</label><input type="email" id="email" name="email" required>';
    mail.innerHTML = texte1 + texte2 + texte3;
}

function show_file(){ // montrer la partie de choix de format de fichier et cacher la partie mail
    var mail = document.getElementById('email');
    mail.innerHTML = '';
    var file = document.getElementById('email');
    var texte1 = '<br><br><input type="radio" id="html" name="option_file" value="html" required><label for="html">format html</label>';
    var texte2 = '<br><input type="radio" id="pdf" name="option_file" value="pdf" required><label for="pdf">format pdf</label>';
    file.innerHTML = texte1 + texte2;
}

function hide_both(){ // cacher la partie mail et la partie fichier 
    var file = document.getElementById('file');
    file.innerHTML = '';
    var mail = document.getElementById('email');
    mail.innerHTML = '';
}


function goToArchive(){ // redirection sur la page des archives
    window.location.href = "archive.php";
}

function goToSelect(){ // redirection sur la page select.php
    window.location.href = "select.php";
}