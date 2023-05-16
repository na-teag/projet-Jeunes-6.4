function addRow(){ // ajouter une ligne au tableau
  var table = document.getElementById("myTable");
  var ligne = table.insertRow(-1);
  var cell1 = ligne.insertCell(0);
  cell1.innerHTML = "<input type='text' name='myTable[]' class='long'>";
}


function deleteRow(){// retirer une ligne du tableau
  var table = document.getElementById("myTable");
  var compteur = table.rows.length;
  if(compteur > 0) {
    table.deleteRow(compteur - 1);
  }
}

function checkLimite(checkbox){ // vérifier que seulement 4 cases max ont été cochées
  var checkboxes = document.getElementsByName("socialSkills[]");
  var nbrChecked = 0;
  for(var i=0; i<checkboxes.length; i++){
  if(checkboxes[i].checked){
    nbrChecked++;
  }
  }
  if(nbrChecked > 4){
  checkbox.checked = false;
  alert("Vous ne pouvez pas cocher plus de 4 cases.");
  }
}
 