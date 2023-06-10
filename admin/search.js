function getSuggestions(str){ 				//afficher tous les noms d'utilisateurs commençant par les lettres entrées par l'admin
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			var suggestions = JSON.parse(this.responseText);
			var suggestionsHtml = '';
			for(var i = 0; i < suggestions.length; i++){
				suggestionsHtml += '<a href="manage.php?username=' + encodeURIComponent(suggestions[i]) + '">' + suggestions[i] + '</a><br>';
			}
			document.getElementById("div1").innerHTML = suggestionsHtml;
		}
	};
	xhttp.open("GET", "?q=" + encodeURIComponent(str), true);
	xhttp.send();
}