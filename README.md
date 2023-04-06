# Invoice reading application

Rakenduse proovimiseks peaks kopeerima failist .env.example faili .env ja määrama vähemalt järgmised väärtused: APP_KEY, APP_URL, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_USERNAME, DB_PASSWORD,
REDIS_HOST, REDIS_PASSWORD, MAIL_MAILER, MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_ADDRESS. Dockeri asemel mõne muu asjaga, nt Xampp'iga proovimisel REDIS_HOST ja REDIS_PASSWORD ei lähe tarvis.

For inserting an admin user is seeding:

    php artisan db:seed

After then log in <http://localhost/login>: username=admin, password=passwor.
