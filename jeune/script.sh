if ! command -v wkhtmltopdf >/dev/null 2>&1
then
    echo "Impossible de générer le pdf, exécutez la commande apt-get install wkhtmltopdf en administrateur, et réessayez."
else
    wkhtmltopdf --encoding utf-8 cv.html cv.pdf 2>/dev/null
fi