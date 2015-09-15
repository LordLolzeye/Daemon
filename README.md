# Daemon

# Necesar:
    -> Vom avea un server Apache cu PHP pe serverul care contine VMChecker-ul.
    -> Pe serverul GitLab vom avea nevoie de un trigger pentru a accesa script-ul de pe serverul VMChecker-ului.
    -> Vom avea nevoie de o baza de date MySQL pe serverul VMChecker.


# Procesul se va desfasura in modul urmator:
         1) Utilizatorul trimite un git commit cu un text care sa contina "COMPILE"
         2) GitLab trimite repo-ul catre script-ul php /var/www/VMChecker/hook.php
                   Se va accesa in modul urmator: /var/www/VMChecker/hook.php?pass=token + un POST cu toate datele de la COMMIT / PUSH
         3) ..../VMChecker/hook.php va descarca toate fisierele acestui folder si le va stoca in /var/www/VMChecker/Temp/$USER
         4) In momentul in care procesul de descarcare a fisierelor s-a terminat, fisierul /var/www/VMChecker/hook.php stocheaza in baza de date 
            locatia arhivei / email-ul si trimite arhiva/folderul catre procesare. dpdv al securitatii, vom bloca orice accesare a server-ului apache din
            printr-un htaccess (Vom lasa doar un IP sa acceseze aceste fisiere)
         5) /var/www/VMChecker/hook.php sterge folder-ul de pe serverul local.(lasand arhiva daca optam pentru varianta asta)

            ----- ????? -----
         6) In momentul terminarii verificarii, VMChecker-ul va transmite un trigger catre fisierul /var/www/VMChecker/finishCheck.php?user="DB USER"&file="Path catre fisierul cu rezultatul"
            Acest script va trimite un email utilizatorului cu rezultatul si va seta status in "Finished"

#DB Structure

CREATE TABLE IF NOT EXISTS `queue` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `date` varchar(100) NOT NULL,
  `user` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'inQueue',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
