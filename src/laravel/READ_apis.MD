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
