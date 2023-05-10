function compareArrays(a, b){
  if(Object.keys(a).length !== Object.keys(b).length){
    return false;
  }
  for(const key in a){
    if(Array.isArray(a[key])){
      if(!Array.isArray(b[key]) || a[key].length !== b[key].length){
        return false;
      }
      for(let i = 0; i < a[key].length; i++){
        if(JSON.stringify(a[key][i]) !== JSON.stringify(b[key][i])){
          return false;
        }
      }
    } else {
      if(a[key] !== b[key]){
        return false;
      }
    }
  }
  return true;
}

function loadUsers(){
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function(){
    if(this.readyState === 4 && this.status === 200){
      const newusers = JSON.parse(this.responseText);
      if(!compareArrays(newusers, <?php echo json_encode($oldusers); ?>)){
        location.reload();
      }
    }
  };
  xhr.open("GET", "../data.php", true);
  xhr.send();
}

function test(){
    alert("ok");
}