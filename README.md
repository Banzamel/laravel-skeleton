# Laravel Vision

**Version: 2.3.0**

Laravel Vision is a modular Laravel 12 API template built for multi-tenant SaaS projects. The repository is intentionally opinionated: every business area lives in its own domain, every new project should learn the structure from the 4 default domains already present in `src/Domains`, and every next domain should stay identical at the folder level.

## Current Default Domains

The template currently ships with 4 reference domains:

- `Administration` - users, company, roles, permissions.
- `Auth` - login, logout, refresh token, social login, auth logs.
- `Payment` - services, proformas, invoices, billing.
- `FileManager` - directories, files, metadata, downloads, uploads.

`src/Shared` is not a domain. It contains cross-cutting building blocks reused by multiple domains.

## Domain Contract

Every domain in `src/Domains/{Domain}` must contain exactly the same 10 top-level folders, even when some of them are empty. Empty layers should stay in git via `.gitkeep`.

This is a hard template rule. New projects should treat the existing 4 domains as the source of truth and keep every newly created domain structurally identical.

### Required 10 Folders

```text
src/Domains/{Domain}/
+-- Dtos/
+-- Enums/
+-- Events/
+-- Factories/
+-- Models/
+-- Observers/
+-- Repositories/
+-- Requests/
+-- Resources/
+-- Services/
```

### Responsibility Of Each Layer

| Folder | Responsibility |
| --- | --- |
| `Dtos` | Immutable input/output objects passed between requests, controllers and services. |
| `Enums` | Domain enums and strongly typed value sets. |
| `Events` | Domain events and event-related classes. Event listeners should live under `Events/Listeners/`, not as an extra top-level folder. |
| `Factories` | Domain-specific factories and object builders. |
| `Models` | Eloquent models and relationships. |
| `Observers` | Model observers and persistence rules that should not live in controllers. |
| `Repositories` | Data access layer. Concrete repositories live here, and repository interfaces live in `Repositories/Interfaces/`. |
| `Requests` | Domain `FormRequest` classes and request-to-DTO mapping. |
| `Resources` | JSON/API resources used for response serialization. |
| `Services` | Application/business logic. Service interfaces live in `Services/Interfaces/`. |

## Repository And Service Interface Rule

The project now keeps interface placement symmetrical:

- Service interfaces: `src/Domains/{Domain}/Services/Interfaces/*`
- Repository interfaces: `src/Domains/{Domain}/Repositories/Interfaces/*`

Concrete classes remain one level above:

- `Services/{Something}Service.php`
- `Repositories/{Something}Repository.php`

All bindings are registered centrally in [RegisterServiceProvider](src/App/Providers/RegisterServiceProvider.php).

## Application Structure

```text
src/
+-- App/
|   +-- Http/Controllers/     # single-action controllers
|   +-- Providers/            # route, event and DI registration
+-- Domains/
|   +-- Administration/
|   +-- Auth/
|   +-- FileManager/
|   +-- Payment/
+-- Shared/                   # traits, scopes, exceptions, helpers, middleware
```

## Request Flow

The preferred flow is consistent across the template:

```text
Route
-> Middleware
-> FormRequest
-> Controller (__invoke only)
-> Service
-> Repository
-> Model / Observer
-> Event / Resource / JSON response
```

### Practical Rules

- Controllers stay thin and coordinate only the HTTP layer.
- Requests validate input and build DTOs.
- Services contain business logic.
- Repositories contain data access logic.
- Observers enforce model lifecycle rules.
- Events broadcast or notify about domain changes.
- Shared concerns belong in `src/Shared`, not inside random domains.

## Routing Overview

The project uses two main route entry points:

- [routes/oauth.php](routes/oauth.php) for public authentication endpoints.
- [routes/api.php](routes/api.php) for protected business endpoints.

Protected API groups currently cover:

- `manage/*`
- `administration/*`
- `files/*`
- `management/*`

## Authentication And Authorization

Implemented stack in code:

- Laravel Passport for OAuth2 token handling.
- Laravel Socialite for social login providers.
- Spatie Laravel Permission in teams mode with `company_id`.
- Middleware-based permission checks in routes.
- Request-level authorization inside `FormRequest` classes.

Permissions are grouped by modules in [config/permission.php](config/permission.php). Some modules are already placeholders for future projects, which is expected in this template.

## Multi-Tenancy

The template is designed around company-based multi-tenancy:

- `company_id` is the permission team key.
- shared traits/scopes enforce company-aware querying.
- routes use `teams.permission` and `company.active` middleware where needed.
- new domains should reuse the same tenant conventions instead of inventing their own.

## How To Add A New Domain

When adding a new domain:

1. Copy the folder contract from one of the 4 existing default domains.
2. Create all 10 top-level folders immediately, even if some are empty.
3. Keep interfaces in `Services/Interfaces` and `Repositories/Interfaces`.
4. Register bindings in [RegisterServiceProvider](src/App/Providers/RegisterServiceProvider.php).
5. Register events/listeners in [EventServiceProvider](src/App/Providers/EventServiceProvider.php) when needed.
6. Expose endpoints through the existing routing style in [routes/api.php](routes/api.php) or [routes/oauth.php](routes/oauth.php).
7. Reuse `src/Shared` for cross-domain concerns instead of duplicating helpers.

## How To Add A New Resource Inside A Domain

The usual resource flow is:

```text
App/Http/Controllers/{ResourcePlural}/
Domain/Requests/
Domain/Dtos/
Domain/Services/Interfaces/
Domain/Services/
Domain/Repositories/Interfaces/
Domain/Repositories/
Domain/Models/
Domain/Observers/
Domain/Events/
Domain/Resources/
```

Not every resource needs every artifact, but the domain itself must always preserve the 10-folder contract.

## Implemented Domains In Practice

### Administration

- user management
- roles and permissions
- company-related administration concerns

### Auth

- login
- logout
- refresh token
- social auth redirect/callback
- auth log handling

### Payment

- services catalog
- proformas
- invoices
- billing

### FileManager

- directory listing
- directory creation
- file upload/download
- metadata loading
- path updates and deletion

## Tech Stack

Verified from the repository:

- PHP `^8.4`
- Laravel `^12.0`
- Laravel Passport `^13.2.2`
- Laravel Reverb `1.7.0.0`
- Laravel Socialite `^5.25`
- Spatie Laravel Permission `^6.21`
- Redis/Predis support
- PHPUnit `^11.5.3`
- Vite + Tailwind CSS 4

## Useful Commands

```bash
composer install
php artisan key:generate
php artisan migrate
composer test
npm install
npm run dev
```

## Testing

Tests are split into:

- `tests/Unit`
- `tests/Feature`
- `tests/Requests`

The suite already contains coverage for auth, administration, management, file manager, DTOs, enums and selected model/exception behavior.
