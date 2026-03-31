## Email setup (optional for local development)

To enable welcome emails locally, configure these in your `.env`:

MAIL_MAILER=smtp
MAIL_ENCRYPTION=tls
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=@gmail.com
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=@gmail.com
MAIL_FROM_NAME="Autonex"

If you do not want to send real emails locally, use:

MAIL_MAILER=log