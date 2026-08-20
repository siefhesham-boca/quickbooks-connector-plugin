# Quickbooks Connector

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bocapro/quickbooks-connector.svg?style=flat-square)](https://packagist.org/packages/bocapro/quickbooks-connector)
[![Total Downloads](https://img.shields.io/packagist/dt/bocapro/quickbooks-connector.svg?style=flat-square)](https://packagist.org/packages/bocapro/quickbooks-connector)

A [Filament](https://filamentphp.com) panel plugin that connects your app to **QuickBooks Online**. It ships a settings page to configure and OAuth-connect a company, and a `Qbo` facade to work with **invoices**, **payments**, **credit notes** and **customers**.

## Installation

```bash
composer require bocapro/quickbooks-connector
```

Publish and run the migration:

```bash
php artisan vendor:publish --tag="quickbooks-connector-migrations"
php artisan migrate
```

Optionally publish the config:

```bash
php artisan vendor:publish --tag="quickbooks-connector-config"
```

Set your Intuit developer app credentials in `.env`:

```dotenv
QBO_CLIENT_ID=...
QBO_CLIENT_SECRET=...
QBO_ENVIRONMENT=sandbox
```

> The **Redirect URI** is fixed by the package's callback route. Copy the value shown on the QuickBooks settings page and register it on your Intuit app — you never set it in `.env` or the UI.

Register the plugin on your panel:

```php
use Bocapro\QuickbooksConnector\QuickbooksConnectorPlugin;

$panel->plugin(QuickbooksConnectorPlugin::make());
```

## Connecting

Open the **QuickBooks Online** page under the *Integrations* navigation group and click **Connect to QuickBooks**. After authorizing on Intuit you'll be redirected back and the company tokens are stored (encrypted). Tokens refresh automatically.

## Usage

```php
use Bocapro\QuickbooksConnector\Facades\Qbo;

// Read
$invoice   = Qbo::invoices()->find(130);
$open      = Qbo::invoices()->query("WHERE Balance > '0'");
$customers = Qbo::customers()->all();

// Create
$invoice = Qbo::invoices()->create([
    'Line' => [/* ... */],
    'CustomerRef' => ['value' => '1'],
]);

// Update
$invoice = Qbo::invoices()->find(130);
Qbo::invoices()->update($invoice, ['PrivateNote' => 'Paid in full']);

// Same API for payments and credit notes
Qbo::payments()->all();
Qbo::creditNotes()->find(5);

if (Qbo::isConnected()) {
    // ...
}
```

Each module (`invoices`, `payments`, `creditNotes`, `customers`) exposes `find()`, `query()`, `all()`, `create()`, `update()` and `delete()`.

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.
