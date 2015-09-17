# Daemon

# Necesar:
    -> Vom avea un server Apache cu PHP pe serverul care contine VMChecker-ul.
    -> Pe serverul GitLab vom avea nevoie de un trigger pentru a accesa script-ul de pe serverul VMChecker-ului.
    -> Vom avea nevoie de support SQLite3 pe serverul VMChecker


# Procesul se va desfasura in modul urmator:
         1) Utilizatorul trimite un git commit cu un text care sa contina "COMPILE"
         2) GitLab trimite un "ping" catre script-ul php /var/www/VMChecker/hook.php
                   Se va accesa in modul urmator: /var/www/VMChecker/hook.php?pass=token + un POST cu toate datele de la COMMIT / PUSH
         3) ..../VMChecker/hook.php va clona fisierele acestui repo in folderul /home/vmchecker/storer-vmchecker/repo/?????/$submitUser/
         4) Fisierul /var/www/VMChecker/hook.php va stoca in baza de date locatia folderului utilizatorului care a dat commit si email-ul acestuia
            si trimite arhiva catre procesare. dpdv al securitatii, vom bloca orice accesare a server-ului apache din
            printr-un htaccess (Vom lasa doar un IP sa acceseze aceste fisiere)
         5) In momentul terminarii verificarii, VMChecker-ul va transmite un trigger catre fisierul /var/www/VMChecker/finishCheck.php?pass="O PAROLA RANDOM"
            Acest script va trimite un email utilizatorului cu rezultatul si va seta status in "Finished" in baza de date

#DB Structure

    CREATE TABLE IF NOT EXISTS `queue` (
           `id` int(255) NOT NULL AUTO_INCREMENT,
           `date` varchar(100) NOT NULL,
           `user` varchar(80) NOT NULL,
           `email` varchar(80) NOT NULL,
           `localDir` varchar(255) NOT NULL,
           `status` varchar(30) NOT NULL DEFAULT 'inQueue',
           PRIMARY KEY (`id`),
           UNIQUE KEY `user` (`user`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
