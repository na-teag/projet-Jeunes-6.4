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

