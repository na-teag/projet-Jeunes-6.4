function addRow() {
  var table = document.getElementById("myTable");
  var ligne = table.insertRow(-1);
  var cell1 = ligne.insertCell(0);
  cell1.innerHTML = "<input type='text' name='myTable[]' class='long'>";
}


function deleteRow() {
  var table = document.getElementById("myTable");
  var compteur = table.rows.length;
  if(compteur > 0) {
    table.deleteRow(compteur - 1);
  }
}
 