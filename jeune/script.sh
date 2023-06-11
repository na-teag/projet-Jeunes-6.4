#!/bin/bash
if ! command -v wkhtmltopdf >/dev/null 2>&1 # vérifier que la commande est installée
then
    echo "Impossible de générer le pdf, exécutez la commande apt-get install wkhtmltopdf en administrateur, et réessayez." # générer un messsage d'erreur
else
    wkhtmltopdf --encoding utf-8 cv.html cv.pdf 2>/dev/null # lancer wkhtmltopdf et rediriger les messages sur null
fi