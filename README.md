# Almefy SDK Integration Sample

> This sample is not a secure integration of the Almefy platform.
> The Almefy SDK is properly integrated but the session management, database and general user
> flow are just a mockup up and should be replaced by your infrastructure.
> If you are having trouble getting this sample to work or have general questions about Almefy
> please contact us via hello@almefy.com

## About

Almefy is 2-factor authentication as a service.

## Running this example project

1. install php and composer if you haven't already
2. run `composer install` to download the required dependencies for this project
3. create `.env` file in root directory of this project and enter:
```
ALMEFY_KEY="your_public_key"
ALMEFY_SECRET="your_private_key"
ALMEFY_API="https://api.dev.almefy.com"

# You may test with live-api keys, but please make sure to not run this example on the public internet either way.
# ALMEFY_API="https://api.almefy.com"
```
3. run `php -S localhost:3000 -t src/` to start a development server
4. visit http://localhost:3000 in your browser

> If you don't have a key pair already, request a pair at https://almefy.com/contact

## Structure of this project

> In this example we are accessing variables like `$_GET` and `$_POST` directly as to not introduce too much complexity.
> A real framework would probably expose methods to access query, post and header variables.
> Accessing these values directly opens you up to a range of security vulnerabilities.
> Read more about it [here](https://symfony.com/doc/current/create_framework/http_foundation.html) and consider using [this](https://symfony.com/doc/current/components/http_foundation.html).

### Dependencies

- [Almefy SDK](https://packagist.org/packages/almefy/sdk) - The Almefy Php SDK ([Github](https://github.com/almefy/almefy-sdk-php))
- [phpdotenv"](https://packagist.org/packages/vlucas/phpdotenv) - Used to load environment variables from disc

### The "Framework"

The example project consists of a simple backend called "Backend.php" found in `src/backend`.
The backend itself does not know about almefy but offers a few helper functions to create, delete and authenticate users.

All files in `/src/backend/plugins` make up the main part of the Almefy integration and do only interact with the
framework via it's interfaces.

Depending on the system in question, Almefy will have to be added directly to templates, modules or other pieces of code representing part
of the user interface.

`/src/backend/page_elements/login_page.php` 
Adding qr code and "reconnect device" section.

`/src/backend/page_elements/footer.php` 
Load the javascript SDK **after** the rest of the site has been built.
Almefy will them scan the document to insert itself.

`/src/backend/page_elements/profile.php` 
Add optional device manager and "Connect Device" widget.


## Integration summary for your project

Requirements
- php 5.5 or higher
- composer (recommended)
- [The Almefy Php SDK](https://github.com/almefy/almefy-sdk-php) for the backend
- [The Almefy js SDK](https://cdn.almefy.com/js/almefy-0.9.8.js) for the frontend

Install the almefy SDK `composer require almefy/sdk` to your backend.

Store the API keys securely on your server. For example in an .env file, or your database containing other server configuration settings.

Include the javascript SDK in your frontend. We recommend pointing to our CDN for the latest version to make sure
you get the latest security updates as soon as they are available.

In order to get Almefy to work with your framework of choice on a bare minimum level you must be able to:

- Offer a public api endpoint for Almefy to call to, e.g. "https://my-website.com/auth-controller.php"
- Manually authenticate users
- Be able to query users by an unique identifier of your choice, e.g. Email
- Add HTML & javascript to your frontend, preferably on your login page

Other features that are not strictly required but recommended:

- Hook into account account creation and deletion functions, to create and delete Almefy Identities
- Add content to profile pages ( Device manager, connecting new devices without sending an email...)

For detailed instructions please check the example files in `src/backend/plugins`.
