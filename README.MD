
<h3>Project deployment: </h3>

Clone from repository: <br>
``
git clone https://github.com/itwixspecial/exchange-dockerised.git
``
Change directory:<br>
``
cd exchange-dockerised
``

Create .env:<br>
``
cp src/.env.example src/.env
``


Run: <br>
``
docker-compose up nginx -d
``

Install composer <br>
``
docker-compose run composer install
``


Run Migration && Generate keys:<br>
``
docker-compose run artisan migrate
``
<br>
``
docker-compose run artisan key:generate   
``


Checkout:
http://localhost/


Links: 

/auth/login     [email;password]
/auth/register  [name;email;password;password_confirmation]

[resource] currencies (req: 'symb' )

get all requests
/request [GET]

create new request
/request  [POST]
REQ fields: currency_from_id, currency_to_id, amount_from, amount_to

create new wallet
/wallets [POST]
REQ feilds: currency_id

Accept exchange
/exchanges/{exchangeId}/apply [POST]

