# Laravel Actor Trails

Track `created_by`, `modified_by`, and `deleted_by` as JSON objects in your Laravel models, with support for multiple guards and polymorphic actors.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digitalindoorsmen/laravel-actor-trails.svg?style=flat-square)](https://packagist.org/packages/digitalindoorsmen/laravel-actor-trails)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/digitalindoorsmen/laravel-actor-trails/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/digitalindoorsmen/laravel-actor-trails/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/digitalindoorsmen/laravel-actor-trails/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/digitalindoorsmen/laravel-actor-trails/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/digitalindoorsmen/laravel-actor-trails.svg?style=flat-square)](https://packagist.org/packages/digitalindoorsmen/laravel-actor-trails)

---

## ✨ What It Does

This package automatically tracks *who* created, modified, or deleted your Eloquent models.  
Instead of just storing a raw `user_id`, it stores a **self‑contained JSON object** with details about the actor at the time of the action:

```json
{
  "id": "16",
  "user_type": "Admin",
  "auth_table": "users",
  "display_name": "Kelly Montannavue"
}
```

This makes your audit trails:

- **Polymorphic** → works with multiple authenticatable models (`User`, `Admin`, `Member`, etc.)
- **Immutable** → keeps a snapshot of the actor’s display name and type, even if the user record changes later
- **Automatic** → hooks into Eloquent events (`creating`, `updating`, `deleting`)
- **Configurable** → choose which attributes to store in the JSON

---

## 🚀 Installation

You can install the package via composer:

```bash
composer require digitalindoorsmen/laravel-actor-trails
```

Publish the config file:

```bash
php artisan vendor:publish --tag="laravel-actor-trails-config"
```

Generate a migration for a specific table:

```bash
php artisan actor-trails:add posts
php artisan migrate
```

This will add `created_by`, `modified_by`, and `deleted_by` JSON columns to the `posts` table.

---

## ⚡ Usage

Add the `HasActorTrails` trait to any model you want to track:

```php
use DigitalIndoorsmen\LaravelActorTrails\Traits\HasActorTrails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasActorTrails, SoftDeletes;

    protected $fillable = ['title', 'body'];
}
```

Now whenever you create, update, or soft delete a `Post`, the actor will be stored automatically:

```php
$post = Post::create(['title' => 'Hello World']);

// Example created_by value:
[
  "id" => 1,
  "user_type" => "User",
  "auth_table" => "users",
  "display_name" => "Alice"
]
```

---

## ⚙️ Config

The published config file (`config/actor-trails.php`) lets you define which attributes are stored:

```php
return [
    'attributes' => [
        'id' => fn($user) => $user->getAuthIdentifier(),
        'user_type' => fn($user) => class_basename($user),
        'auth_table' => fn($user) => $user->getTable(),
        'display_name' => fn($user) => $user->name ?? $user->username ?? 'Unknown',
    ],
];
```

You can override this per model if needed.

---

## 🧪 Testing

```bash
composer test
```

---

## 📜 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

---

## 🤝 Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

---

## 🔒 Security

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

---

## 👏 Credits

- [Clay Bitner](https://github.com/claybitner)
- [All Contributors](../../contributors)

---

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
