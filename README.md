# Invoice reading application

Rakenduse proovimiseks tuleks see laaida terminali käsuga

    git clone https://github.com/ivose/invoices.git

Seejärel installida

    cd invoices
    composer install

Ja tekitada omale MySql andmebaas:

    CREATE DATABASE IF NOT EXISTS `invoice_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

Seejärel kopeeria failist .env.example faili .env ja määrama vähemalt järgmised väärtused: APP_NAME, APP_KEY, APP_URL, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_USERNAME, DB_PASSWORD,
REDIS_HOST, REDIS_PASSWORD, MAIL_MAILER, MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_ADDRESS. Dockeri asemel mõne muu asjaga, nt Xampp'iga proovimisel REDIS_HOST ja REDIS_PASSWORD ei lähe tarvis.

Laadida andmebaasi tabelid

    php artisan migrate

Ja lisada admin-kasutaja

    php artisan db:seed

Lisaks on vaja ka krüptivõtme seadmist, mis automaatselt määrab APP_KEY'le väärtuse:

   php artisan key:generate

Peale selle minna <http://localhost/invoices/public/> (või mis iganes see peaurl, ehk .env'is APP_URL on).
Kui on vaja sisse logida, siis username=admin@example.com, password=password.

Proovida võib ka dockeriga: alguses käsk: docker-compose up -d --build. Artisani käske tuleks anda dockeri php konteineri terminalis. Oma põhiterminalis tuleks aeg-ajalt laadida "sudo chown -R myuser:myuser * && sudo chown -R 82:82 storage" (82 on põhiterminali vaates dockeri php-kasutaja www-data), kuna koodiredaktoriga redigeerimisel tekivad õiguste probleemid.
Dockeriga proovimise puhul oleks kasulikum kui see poleks Windowsi C:\ alamkataloogis, kuna see variant on väga aeglase laadimisega, vaja oleks linuxit või tõsta see wsl'i kataloogi nt

    \\wsl.localhost\Ubuntu-20.04\home\<username>\<project>.
