# Laravel Skeleton - Multi-Tenant SaaS API Template

**Version: 2.1.2**

A modular, multi-tenant Laravel 12 API backend template for building SaaS applications. Features users, companies, payments, invoicing, file management, and real-time events. Built with Domain-Driven Design (DDD) and designed as a **foundation/template** for large-scale platforms.

---

## Table of Contents

- [Domain Structure](#domain-structure)
- [Implemented Features](#implemented-features)
- [Authentication & Authorization](#authentication--authorization)
- [Multi-Tenancy](#multi-tenancy)
- [CRUD Convention](#crud-convention)
- [API Endpoints](#api-endpoints)
- [Event Broadcasting & WebSocket](#event-broadcasting--websocket)
- [Queue System](#queue-system)
- [Testing](#testing)
- [Service Bindings](#service-bindings-registerserviceprovider)

---

### Tech Stack

| Layer | Technology |
|-------|-----------|
| **Runtime** | PHP 8.4, Laravel 12 |
| **Auth** | Laravel Passport 13 (OAuth 2.0 Password Grant + Social Login) |
| **RBAC** | Spatie Laravel Permission 6 (Teams mode, per-company roles) |
| **WebSocket** | Laravel Reverb |
| **Database** | MySQL 8 |
| **Cache/Queue** | Redis |
| **Containerization** | Docker Compose (6 services) |

### Design Patterns

- **Single Action Controllers** — one `__invoke()` method per controller, no logic
- **Service Layer** — business logic lives in Services, accepts DTOs, returns Models
- **Repository Pattern** — data access abstracted behind interfaces, Eloquent implementations
- **DTO (Data Transfer Object)** — readonly classes with `toArray()` (camelCase to snake_case mapping)
- **Observer Pattern** — business rules, auto-creation of related models, number generation
- **Interface Binding** — all Services and Repositories are bound via `RegisterServiceProvider`
- **Global Scopes** — automatic multi-tenancy filtering (`CompanyScope`)

### Request Flow

```
HTTP Request
  → Route Middleware (auth:api, scope:api, teams.permission, company.active, permission:X)
  → FormRequest (authorize() + rules() + getDto())
  → Single Action Controller (__invoke)
  → Service (business logic, fires Events)
  → Repository (Eloquent operations)
  → Model (CompanyScope auto-filters, Observer triggers)
  → Event Broadcasting (WebSocket to company channel)
  → JSON Response
```

---

## Domain Structure

### Auth

Handles authentication flows. Does not create users — only authenticates existing ones.

| Component | Description |
|-----------|-------------|
| `User` | Extends Administration\User, adds `HasApiTokens` (Passport) |
| `SocialAccount` | Links external OAuth providers (Google, Facebook) to users |
| `AuthLog` | Audit trail: every CRUD action logged with IP, user agent, changes (JSON) |
| `AuthorizationService` | Password grant login via internal Passport subrequest |
| `SocialAuthService` | Social login: finds user by email, links provider, issues token |

### Administration

Core domain: users, company, roles, permissions.

| Component | Description |
|-----------|-------------|
| `User` | Main user model with `BelongsToCompany`, `HasRoles`, `SoftDeletes` |
| `Company` | Multi-tenant company entity. Observer auto-creates `Billing` on creation |
| `UserManagementService` | User CRUD with role assignment |
| `RoleService` | Per-company role management with permission sync |

### Payment

Financial domain: services, invoicing, proformas, billing.

| Component | Description |
|-----------|-------------|
| `Service` | Purchasable service/package (name, limit, price) |
| `Proforma` | Pre-invoice (extends Invoice with `is_proforma=true` scope). Full lifecycle: create → confirm → becomes Invoice |
| `Invoice` | Confirmed invoice (`is_proforma=false` scope). Read-only after confirmation. PDF download |
| `Billing` | Company billing settings (tax ID, bank, numbering formats). One per company, auto-created |
| `ProformaObserver` | Auto-generates proforma numbers on create, invoice numbers on confirm |
| `InvoiceObserver` | Prevents deletion of confirmed invoices |
| `ServiceObserver` | Prevents deletion of services used in any invoice/proforma |

### FileManager

Hierarchical file storage system per company.

| Component | Description |
|-----------|-------------|
| `FileManagerPath` | Tree structure (self-referencing `parent_id`). Types: `file` or `dir` |
| `FileManagerMeta` | File metadata: MIME type, extension, checksum, custom metadata (JSON) |
| `FileManagerLink` | Polymorphic shareable links to files |
| `FileManagerService` | Directory creation, file upload (multipart), rename, delete, download |

### Shared

Cross-cutting concerns used by all domains.

| Component | Description |
|-----------|-------------|
| `BelongsToCompany` trait | Auto-sets `company_id` on create, adds `CompanyScope`, adds `company()` relation |
| `CompanyScope` | Global scope that filters all queries by authenticated user's `company_id` |
| `HasActiveScope` trait | Provides `scopeActive()` and `scopeInactive()` query scopes |
| `Loggable` trait | Auto-logs CRUD actions to `AuthLog` (captures changed attributes, IP, user agent) |
| `ApiJsonException` | Renderable exception with custom HTTP status code and JSON error response |
| `BusinessRoles` enum | `Admin`, `Lector` |
| `EntityTypeEnum` | `file`, `dir` |
| `StoragesEnum` | `local`, `public`, `aws` |

---

## Implemented Features

### Core Infrastructure
- Domain-Driven Design with PSR-4 autoloading from `src/`
- Multi-tenancy via `CompanyScope` global scope (automatic, zero-config per query)
- Modular permission system
- Per-company roles with Spatie Teams mode
- Full audit logging (every CRUD action tracked with user, IP, changes)
- Event broadcasting on all CRUD operations
- Queue worker running via supervisord (Redis driver)
- Docker Compose environment with 6 services

### Authentication
- OAuth 2.0 Password Grant (Passport) — returns access + refresh tokens
- Social Login (Google, Facebook) via Laravel Socialite — links to existing users only
- Token expiry: 15 days access, 30 days refresh
- Scopes: `mobile`, `web`, `api`

### API Resources (Full CRUD)
- **Users** — create, list (paginated/searchable/filterable by role), show, update, delete (cannot delete self), avatar upload, auth logs
- **Roles** — create with permissions, list, update, delete (per-company)
- **Permissions** — list all available permissions
- **Services** — create, list, show, update, delete (blocked if used in invoices)
- **Proformas** — create, list, show, update, delete, restore (soft-delete), confirm (converts to invoice)
- **Invoices** — list, show, update status, download PDF (read-only after confirmation)
- **Billing** — get, update (one per company, auto-created with company)
- **File Manager** — create directories, upload files, list (with parent navigation), rename, delete, download


## Authentication & Authorization

### Two-Layer Authorization

Authorization is enforced at two levels — **both must pass**:

1. **Route Middleware** — `permission:module.action` (e.g., `permission:users.create`)
2. **FormRequest** — `authorize()` method checks `$this->user()->can('module.action')`

**No Policies are used.** This dual-layer approach ensures security even if a route is misconfigured.

### Permission Modules

```
users:       view, create, update, delete
permissions: view, manage
calendar:    view, create, update, delete
payments:    view, create, update, delete
materials:   view, create, update, delete
settings:    view, manage
services:    view, create, update, delete
```

Roles are per-company — each company can create and customize its own roles via the API.

---

## Multi-Tenancy

Multi-tenancy is handled transparently via the `BelongsToCompany` trait and `CompanyScope`:

- **Automatic query filtering** — every query on a model with `BelongsToCompany` is scoped to the authenticated user's `company_id`. No manual `where('company_id', ...)` needed anywhere.
- **Automatic assignment** — `company_id` is auto-set on model creation from the authenticated user.
- **Seeders** must use `withoutGlobalScopes()` to bypass the scope.
- **Team foreign key**: `company_id` (configured in `config/permission.php`)
- **Middleware**: `SetPermissionsTeamId` sets the Spatie team context on every request.
- **Middleware**: `CheckCompanyNotExpired` blocks API access if the company's `expired_at` date has passed.

---

## CRUD Convention

Every new resource **must** follow this structure. Reference implementation: **Users** (Administration domain).

### Files to Create

```
src/App/Http/Controllers/{Resources}/
  Get{Resources}Controller.php        # List (paginated)
  Get{Resource}Controller.php         # Show single
  Create{Resource}Controller.php      # Create (returns 201)
  Update{Resource}Controller.php      # Update
  Delete{Resource}Controller.php      # Delete (returns 204)

src/Domains/{Domain}/
  Models/{Resource}.php               # Eloquent model with traits
  Dtos/{Resource}CreateDto.php        # Readonly, toArray() with snake_case keys
  Dtos/{Resource}UpdateDto.php        # Nullable fields, toArray() filters nulls
  Requests/{Resource}CreateRequest.php # authorize() + rules() + getDto()
  Requests/{Resource}UpdateRequest.php
  Repositories/{Resource}RepositoryInterface.php
  Repositories/{Resource}Repository.php
  Services/Interfaces/{Resource}ServiceInterface.php
  Services/{Resource}Service.php
  Observers/{Resource}Observer.php    # Business rules, auto-creation
  Events/{Resource}Created|Updated|DeletedEvent.php
```

### Registration Checklist

1. **Routes** in `routes/api.php` with middleware `permission:module.action`
2. **Service binding** in `RegisterServiceProvider`: Interface → Implementation
3. **Repository binding** in `RegisterServiceProvider`: Interface → Implementation
4. **Permissions** in `config/permission.php` → `modules` array
5. **Seeder** update in `RoleAndPermissionsSeeder`

### Key Rules

- Controllers are `readonly class` with single `__invoke()` — no business logic
- Services accept DTOs (not Requests), return Models, fire Events
- Repositories never add `where('company_id')` — `CompanyScope` handles it
- Update DTOs use `array_filter(fn($v) => $v !== null)` to support partial updates
- Observers handle business constraints that can't be expressed in FormRequest validation
- Events implement `ShouldBroadcast` on `PresenceChannel('edu.company.{companyId}')`

---


## API Endpoints

### Auth (Public — `routes/oauth.php`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/oauth/login` | Password grant login |
| POST | `/oauth/refresh` | Refresh access token |
| POST | `/oauth/logout` | Revoke token (auth required) |
| GET | `/oauth/social/{provider}/redirect` | Social login redirect (Google/Facebook) |
| GET | `/oauth/social/{provider}/callback` | Social login callback |

---

## Event Broadcasting & WebSocket

All CRUD operations broadcast events via **Laravel Reverb** (WebSocket server on port 8080).

### Channel Structure

```
Presence Channel: edu.company.{companyId}
```

Each company gets its own real-time channel. Users are authorized based on their `company_id`.

### Broadcast Events

| Domain | Events |
|--------|--------|
| Auth | `LoginEvent`, `LogoutEvent` |
| Administration | `UserCreated/Updated/Deleted`, `UserAvatarUpdated`, `RoleCreated/Updated/Deleted` |
| Payment | `ServiceCreated/Updated/Deleted`, `ProformaCreated/Updated/Deleted/Restored/Confirmed`, `InvoiceUpdated`, `BillingUpdated` |
| FileManager | `FileUpload` |

---

## Queue System

Queue infrastructure is fully configured and running, ready for dispatching jobs.

| Aspect | Configuration |
|--------|--------------|
| **Driver (production)** | Redis (`QUEUE_CONNECTION=redis`) |
| **Driver (testing)** | Sync (`QUEUE_CONNECTION=sync`) |
| **Worker** | Supervisord process `laravel-queue` with auto-restart |
| **Worker command** | `php artisan queue:work --tries=1 --sleep=10 --timeout=0` |
| **Failed jobs** | Stored in `failed_jobs` table (database-uuids driver) |
| **Job batching** | Supported (`job_batches` table exists) |

No Job classes are dispatched yet — the infrastructure is in place for future use (e.g., email notifications, PDF generation, report exports).

---

## Testing

### Test Structure

```
tests/
├── Unit/                         # Pure PHP tests (no Laravel boot needed)
│   ├── Dtos/                     # DTO tests (constructors, getters, toArray)
│   ├── Enums/                    # BusinessRoles, EntityTypeEnum, StoragesEnum
│   ├── Exceptions/               # ApiJsonException render behavior
│   └── Models/                   # Invoice::currencyForCountry()
├── Feature/                      # Full API integration tests (with database)
│   ├── Auth/                     # Login, Logout
│   ├── Manage/                   # Profile
│   ├── Administration/           # Users, Permissions, Roles
│   ├── Management/               # Services, Proformas, Invoices, Billing
│   └── FileManager/              # FileManager
└── Requests/                     # HTTP client files (.http) for manual API testing
```

### Running Tests

```bash
# Prerequisite: create test database (one-time)
docker exec db-edu mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS edu_school_test;"
docker exec db-edu mysql -uroot -proot -e "GRANT ALL PRIVILEGES ON edu_school_test.* TO 'edu_user'@'%';"

# Run all tests
docker exec laravel-edu php artisan test

# Run by suite
docker exec laravel-edu php artisan test --testsuite=Unit
docker exec laravel-edu php artisan test --testsuite=Feature

# Run specific test class or method
docker exec laravel-edu php artisan test --filter=LoginTest
docker exec laravel-edu php artisan test --filter="admin_can_create_user"
```

### Test Infrastructure

- **`ApiTestCase`** — base class for Feature tests: seeds roles/permissions/users, sets company `expired_at`, provides `actingAsAdmin()`, `actingAsTeacher()`, `actingAsUser()` helpers
- **`RefreshDatabase`** — each test runs in a clean database (auto-migrated)
- **`Passport::actingAs()`** — simulates authenticated API requests without actual OAuth flow
- **`TestCase`** — overrides Docker env vars (`DB_DATABASE=edu_school_test`, `CACHE_STORE=array`, etc.) before app boots

---

## Service Bindings (RegisterServiceProvider)

All interfaces are bound to implementations in `src/App/Providers/RegisterServiceProvider.php`:

### Services

```
Auth:           AuthorizationServiceInterface       → AuthorizationService
                SocialAuthServiceInterface          → SocialAuthService
Administration: UserManagementServiceInterface      → UserManagementService
                UserActivityServiceInterface        → UserActivityService
                RoleServiceInterface                → RoleService
                PermissionServiceInterface          → PermissionService
Payment:        ServiceManagementServiceInterface   → ServiceManagementService
                ProformaServiceInterface            → ProformaService
                InvoiceServiceInterface             → InvoiceService
                BillingServiceInterface             → BillingService
FileManager:    FileManagerServiceInterface         → FileManagerService
```

### Repositories

```
Auth:           AuthLogRepositoryInterface          → AuthLogRepository
Administration: UserRepositoryInterface             → UserRepository
                RoleRepositoryInterface             → RoleRepository
Payment:        ServiceRepositoryInterface          → ServiceRepository
                ProformaRepositoryInterface         → ProformaRepository
                InvoiceRepositoryInterface          → InvoiceRepository
                BillingRepositoryInterface          → BillingRepository
FileManager:    FileManagerRepositoryInterface      → FileManagerRepository
```
