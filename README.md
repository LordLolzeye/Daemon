# Daemon

# Necesar:
    -> Vom avea un server Apache cu PHP pe serverul care contine VMChecker-ul.
    -> Pe serverul GitLab vom avea nevoie de un compiler PHP cu extensia ZIP, voi dezactiva optiunea de 
       siguranta pentru comenzile shell exec() (din PHP)
    -> Vom avea nevoie de o baza de date MySQL pe unul dintre cele doua servere. (cu acces de pe orice IP)


# Procesul se va desfasura in modul urmator:
         "...." inseamna -> Nu conteaza unde este stocat
         1) Utilizatorul trimite un git commit cu un text care sa contina "COMPILE"
         2) GitLab trimite repo-ul catre script-ul php ..../GitLab/doArchive.php
                   Se va accesa in modul urmator: ..../GitLab/doArchive.php?repo=aici/am/salvat/folderul
         3) ..../GitLab/doArchive.php va face o arhiva zip a acestui folder si o va stoca in ..../GitLab/Temp/$USER.zip, si dupa o uploadeaza in /var/www/VMChecker/Queue/$USER.zip
            -> Deci daca utilizatorul deja a dat upload la o alta arhiva mai devreme, aceasta va fi suprascrisa (Nu vor putea da compile in prostie)
               O alta optiune ar fi, ca in momentul in care scriptul descopera ca utilizatorul deja a trimis o arhiva catre compilare, sa ii trimita un email care
               sa ii spuna ca are un compile deja in queue.
         4) Dupa ce o uploadeaza, doArchive sterge fisierul ..../GitLab/Temp/$USER.zip
         5) In momentul in care arhivarea s-a terminat, fisierul /var/www/GitLab/doArchive.php stocheaza in baza de date locatia arhivei / email-ul si user-ul si
            trimite un request catre http://IP-SERVERVMCHECKER/checkFile.php?id="ID DIN DB"&path="PATH", dpdv al securitatii, vom bloca orice accesare a server-ului apache din
            printr-un htaccess (Vom lasa doar un IP sa acceseze aceste fisiere)
         6) /var/www/VMChecker/checkFile.php uploadeaza /var/www/VMChecker/Queue/$USER.zip in QUEUE si o va sterge de pe serverul local.

            ----- ????? -----
         7) In momentul terminarii verificarii, VMChecker-ul va transmite un trigger catre fisierul /var/www/VMChecker/triggerMail.php?user="DB USER"&file="Path catre fisierul cu rezultatul"
            Acest script va trimite un email utilizatorului cu rezultatul si va seta status in "Finished"

#DB Structure

CREATE TABLE IF NOT EXISTS `queue` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `date` varchar(100) NOT NULL,
  `user` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `path` varchar(255) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'inQueue',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
