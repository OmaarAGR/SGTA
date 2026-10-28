# SGTA — Static Analysis Report (PHPStan + Larastan)

| | |
|---|---|
| **Project** | SGTA (Laravel 12.15, Inertia 2) |
| **Branch / commit** | `feature/initial-project-setup` @ `054d7ea` |
| **Generated** | 2026-10-08 |
| **Tools** | PHPStan 2.3.1 · Larastan 3.13.0 |
| **PHP** | 8.5.11 (deprecations suppressed during analysis) |
| **Paths analysed** | `app/`, `routes/`, `database/`, `config/` (tests excluded) |

## 1. Executive summary

| Level | Errors | Meaning |
|---|---:|---|
| **5** (Larastan's recommended baseline) | **7** | Real defects: calls to undefined relations/properties |
| **max** (level 10) | **128** | Includes strict typing: missing types, nullable `user()`, `mixed` handling |

No analyser crashes or parse errors (`totals.errors = 0`).

**Highlights**

- 🔴 **2 confirmed runtime bugs** (level 5): `ServiceRecordController` filters/loads relations that do not exist on `ServiceRecord`; `TechnicianController::show` eager-loads a non-existent `Technician::appointments` relation. Both will throw `RelationNotFoundException` (HTTP 500) when executed.
- 🟠 **`AppointmentPolicy` accesses `->client` on a generic `Model`** (2×): untyped rather than proven broken; needs a typed parameter.
- 🟡 Most of the 121 additional level-max findings are type-hygiene issues, dominated by `missingType.return` (26), `binaryOp.invalid` and `argument.type` entries that cascade from a handful of root causes (§4.3).

## 2. Configuration used

Run against a throw-away copy of the repository so your `composer.json` / lock were **not modified**. To reproduce in-repo:

```bash
composer require --dev larastan/larastan --ignore-platform-req=php
```

```neon
# phpstan.neon
includes:
    - vendor/larastan/larastan/extension.neon
parameters:
    level: 5          # raise gradually toward max
    paths: [app, routes, database, config]
```

```bash
vendor/bin/phpstan analyse --memory-limit=1G
```

## 3. Level 5 findings (fix first)

| Location | Identifier | Message |
|---|---|---|
| `app/Http/Controllers/ServiceRecordController.php:24` | `larastan.relationExistence` | Relation 'client' is not found in App\Models\ServiceRecord model. |
| `app/Http/Controllers/ServiceRecordController.php:26` | `larastan.relationExistence` | Relation 'vehicle' is not found in App\Models\ServiceRecord model. |
| `app/Http/Controllers/ServiceRecordController.php:75` | `larastan.relationExistence` | Relation 'vehicle' is not found in App\Models\ServiceRecord model. |
| `app/Http/Controllers/ServiceRecordController.php:90` | `larastan.relationExistence` | Relation 'vehicle' is not found in App\Models\ServiceRecord model. |
| `app/Http/Controllers/TechnicianController.php:60` | `larastan.relationExistence` | Relation 'appointments' is not found in App\Models\Technician model. |
| `app/Policies/AppointmentPolicy.php:26` | `property.notFound` | Access to an undefined property Illuminate\Database\Eloquent\Model::$client. |
| `app/Policies/AppointmentPolicy.php:51` | `property.notFound` | Access to an undefined property Illuminate\Database\Eloquent\Model::$client. |

### Analysis

| Finding | Verified cause | Fix |
|---|---|---|
| `ServiceRecordController.php:24,26,75,90` — relations `client`, `vehicle` | `ServiceRecord` only defines `appointment()` and `technician()`. The controller queries `whereHas('vehicle')` / `->with('vehicle')` as if they were direct relations. | Go through the appointment: `whereHas('appointment.vehicle', …)` / `with('appointment.vehicle.client')`, or add `hasOneThrough` relations. Searching service records currently 500s. |
| `TechnicianController.php:60` — `appointments` | `Technician` defines `user()` and `serviceRecords()` only; `->load(['appointments…'])` throws, so the technician detail page (`TechnicianController::show`) will 500. | Add an `appointments()` relation to `Technician`, or load via `serviceRecords.appointment.vehicle.client`. |
| `AppointmentPolicy.php:26,51` — `Model::$client` | The policy method is typed with a generic `Model`. | Type-hint `Appointment` (and `@property Client $client` or a `client()` relation) in the signature. |

## 4. Level max findings

### 4.1 By category

| Identifier | Count |
|---|---:|
| `missingType.return` | 26 |
| `binaryOp.invalid` | 24 |
| `argument.type` | 23 |
| `missingType.generics` | 17 |
| `method.nonObject` | 12 |
| `property.nonObject` | 9 |
| `property.notFound` | 5 |
| `larastan.relationExistence` | 5 |
| `assign.propertyType` | 4 |
| `return.type` | 2 |
| `offsetAccess.nonOffsetAccessible` | 1 |

### 4.2 By file

| File | Errors |
|---|---:|
| `app/Http/Controllers/AppointmentController.php` | 26 |
| `app/Http/Controllers/ServiceRecordController.php` | 16 |
| `app/Http/Controllers/TechnicianController.php` | 10 |
| `app/Http/Controllers/ClientController.php` | 9 |
| `app/Models/Appointment.php` | 7 |
| `app/Http/Controllers/Auth/NewPasswordController.php` | 6 |
| `app/Http/Controllers/Client/ClientAppointmentController.php` | 6 |
| `app/Http/Controllers/Settings/ProfileController.php` | 5 |
| `app/Http/Controllers/VehicleController.php` | 5 |
| `app/Policies/AppointmentPolicy.php` | 4 |
| `app/Http/Controllers/Settings/PasswordController.php` | 3 |
| `app/Models/Client.php` | 3 |
| `app/Models/User.php` | 3 |
| `app/Http/Controllers/Auth/EmailVerificationNotificationController.php` | 2 |
| `app/Http/Controllers/Auth/VerifyEmailController.php` | 2 |
| `app/Http/Requests/UpdateUserRequest.php` | 2 |
| `app/Models/ServiceRecord.php` | 2 |
| `app/Models/Technician.php` | 2 |
| `app/Models/Vehicle.php` | 2 |
| `app/Console/Commands/CreateAdminUser.php` | 1 |
| `app/Http/Controllers/Auth/ConfirmablePasswordController.php` | 1 |
| `app/Http/Controllers/Auth/EmailVerificationPromptController.php` | 1 |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | 1 |
| `app/Http/Controllers/DashboardController.php` | 1 |
| `app/Http/Middleware/HandleInertiaRequests.php` | 1 |
| `app/Http/Requests/Settings/ProfileUpdateRequest.php` | 1 |
| `config/app.php` | 1 |
| `config/cache.php` | 1 |
| `config/database.php` | 1 |
| `config/logging.php` | 1 |
| `config/mail.php` | 1 |
| `config/session.php` | 1 |

### 4.3 Root causes (fixing these removes most of the noise)

1. **`auth()->user()` / `$request->user()` is `User|null`** → `method.nonObject` (12) + `property.nonObject` (9). Use `$request->user()` with an `abort_unless(...)`, or `assert($user instanceof User)`, or rely on `auth` middleware plus a typed helper.
2. **Search-term concatenation** (`binaryOp.invalid`, 24): `request()->query('search')` returns `array|string|null`. Cast/validate once (`$request->string('search')->toString()`), then reuse.
3. **`Client::vehicles` accessed on `Client|Collection`** (`argument.type` 23, `property.notFound` 5): `Client::find($data['client_id'])` receives a `mixed` id, so PHPStan infers `Client|Collection|null`. Cast the id (`(int)`) or use `findOrFail()`, which also removes the null case.
4. **Missing generics on `Attribute`** casts in models (`missingType.generics`, 17): annotate `@return Attribute<string, string>`.
5. **Missing return types** (`missingType.return`, 26): `handle(): int` on `CreateAdminUser`, `: Response`/`: RedirectResponse` on controller actions.
6. **Return-type mismatch** (`return.type`, 2) in `TechnicianController::index()` — `inertia()` helper returns `Response|ResponseFactory`; use `Inertia::render()`.
7. **`assign.propertyType`** (4) in `AppointmentController` — `$client->user_id` is nullable and `$data['technician_id']` is `mixed`; guard or cast before assigning.
8. **Config files** (6): environment-driven values typed as `mixed`/PDO constants — low value, safe to exclude `config/` or baseline.

### 4.4 Full list

#### `app/Console/Commands/CreateAdminUser.php`

| Line | Identifier | Message |
|---:|---|---|
| 26 | `missingType.return` | Method App\Console\Commands\CreateAdminUser::handle() has no return type specified. |

#### `app/Http/Controllers/AppointmentController.php`

| Line | Identifier | Message |
|---:|---|---|
| 21 | `missingType.return` | Method App\Http\Controllers\AppointmentController::index() has no return type specified. |
| 33 | `binaryOp.invalid` | Binary operation "." between '%' and array\|string results in an error. |
| 33 | `binaryOp.invalid` | Binary operation "." between mixed and '%' results in an error. |
| 36 | `binaryOp.invalid` | Binary operation "." between '%' and array\|string results in an error. |
| 36 | `binaryOp.invalid` | Binary operation "." between mixed and '%' results in an error. |
| 78 | `missingType.return` | Method App\Http\Controllers\AppointmentController::store() has no return type specified. |
| 91 | `argument.type` | Parameter #1 $key of method Illuminate\Database\Eloquent\Collection<int,Illuminate\Database\Eloquent\Model>::contains() expects (callable(Illuminate\Database\Eloquent\Model, int): bool)\|Illuminate\Database\Eloquent\Model\|int\|string, mixed given. |
| 91 | `property.notFound` | Access to an undefined property App\Models\Client\|Illuminate\Database\Eloquent\Collection<int, App\Models\Client>::$vehicles. |
| 98 | `binaryOp.invalid` | Binary operation "." between mixed and ' ' results in an error. |
| 98 | `binaryOp.invalid` | Binary operation "." between non-falsy-string and mixed results in an error. |
| 99 | `argument.type` | Parameter #2 $timezone of static method Carbon\Carbon::parse() expects DateTimeZone\|int\|string\|null, mixed given. |
| 100 | `argument.type` | Parameter #1 $value of method Carbon\Carbon::tz() expects DateTimeZone\|int\|string\|null, mixed given. |
| 118 | `assign.propertyType` | Property App\Models\Appointment::$user_id (int<0, max>) does not accept int<0, max>\|null. |
| 118 | `property.notFound` | Access to an undefined property App\Models\Client\|Illuminate\Database\Eloquent\Collection<int, App\Models\Client>::$user_id. |
| 119 | `assign.propertyType` | Property App\Models\Appointment::$technician_id (int\|null) does not accept mixed. |
| 142 | `missingType.return` | Method App\Http\Controllers\AppointmentController::edit() has no return type specified. |
| 157 | `missingType.return` | Method App\Http\Controllers\AppointmentController::update() has no return type specified. |
| 170 | `argument.type` | Parameter #1 $key of method Illuminate\Database\Eloquent\Collection<int,Illuminate\Database\Eloquent\Model>::contains() expects (callable(Illuminate\Database\Eloquent\Model, int): bool)\|Illuminate\Database\Eloquent\Model\|int\|string, mixed given. |
| 170 | `property.notFound` | Access to an undefined property App\Models\Client\|Illuminate\Database\Eloquent\Collection<int, App\Models\Client>::$vehicles. |
| 177 | `binaryOp.invalid` | Binary operation "." between mixed and ' ' results in an error. |
| 177 | `binaryOp.invalid` | Binary operation "." between non-falsy-string and mixed results in an error. |
| 178 | `argument.type` | Parameter #2 $timezone of static method Carbon\Carbon::parse() expects DateTimeZone\|int\|string\|null, mixed given. |
| 179 | `argument.type` | Parameter #1 $value of method Carbon\Carbon::tz() expects DateTimeZone\|int\|string\|null, mixed given. |
| 196 | `assign.propertyType` | Property App\Models\Appointment::$user_id (int<0, max>) does not accept int<0, max>\|null. |
| 196 | `property.notFound` | Access to an undefined property App\Models\Client\|Illuminate\Database\Eloquent\Collection<int, App\Models\Client>::$user_id. |
| 207 | `missingType.return` | Method App\Http\Controllers\AppointmentController::destroy() has no return type specified. |

#### `app/Http/Controllers/Auth/ConfirmablePasswordController.php`

| Line | Identifier | Message |
|---:|---|---|
| 29 | `property.nonObject` | Cannot access property $email on App\Models\User\|null. |

#### `app/Http/Controllers/Auth/EmailVerificationNotificationController.php`

| Line | Identifier | Message |
|---:|---|---|
| 16 | `method.nonObject` | Cannot call method hasVerifiedEmail() on App\Models\User\|null. |
| 20 | `method.nonObject` | Cannot call method sendEmailVerificationNotification() on App\Models\User\|null. |

#### `app/Http/Controllers/Auth/EmailVerificationPromptController.php`

| Line | Identifier | Message |
|---:|---|---|
| 18 | `method.nonObject` | Cannot call method hasVerifiedEmail() on App\Models\User\|null. |

#### `app/Http/Controllers/Auth/NewPasswordController.php`

| Line | Identifier | Message |
|---:|---|---|
| 49 | `method.nonObject` | Cannot call method forceFill() on mixed. |
| 50 | `argument.type` | Parameter #1 $value of static method Illuminate\Support\Facades\Hash::make() expects string, mixed given. |
| 52 | `method.nonObject` | Cannot call method save() on mixed. |
| 54 | `argument.type` | Parameter #1 $user of class Illuminate\Auth\Events\PasswordReset constructor expects Illuminate\Contracts\Auth\Authenticatable, mixed given. |
| 62 | `argument.type` | Parameter #1 $key of function __ expects string\|null, mixed given. |
| 66 | `argument.type` | Parameter #1 $key of function __ expects string\|null, mixed given. |

#### `app/Http/Controllers/Auth/RegisteredUserController.php`

| Line | Identifier | Message |
|---:|---|---|
| 42 | `argument.type` | Parameter #1 $value of static method Illuminate\Support\Facades\Hash::make() expects string, mixed given. |

#### `app/Http/Controllers/Auth/VerifyEmailController.php`

| Line | Identifier | Message |
|---:|---|---|
| 17 | `method.nonObject` | Cannot call method hasVerifiedEmail() on App\Models\User\|null. |
| 21 | `method.nonObject` | Cannot call method markEmailAsVerified() on App\Models\User\|null. |

#### `app/Http/Controllers/Client/ClientAppointmentController.php`

| Line | Identifier | Message |
|---:|---|---|
| 54 | `argument.type` | Parameter #1 $key of method Illuminate\Database\Eloquent\Collection<int,Illuminate\Database\Eloquent\Model>::contains() expects (callable(Illuminate\Database\Eloquent\Model, int): bool)\|Illuminate\Database\Eloquent\Model\|int\|string, mixed given. |
| 61 | `binaryOp.invalid` | Binary operation "." between mixed and ' ' results in an error. |
| 61 | `binaryOp.invalid` | Binary operation "." between non-falsy-string and mixed results in an error. |
| 62 | `argument.type` | Parameter #2 $timezone of static method Carbon\Carbon::parse() expects DateTimeZone\|int\|string\|null, mixed given. |
| 63 | `argument.type` | Parameter #1 $value of method Carbon\Carbon::tz() expects DateTimeZone\|int\|string\|null, mixed given. |
| 77 | `assign.propertyType` | Property App\Models\Appointment::$user_id (int<0, max>) does not accept int<0, max>\|null. |

#### `app/Http/Controllers/ClientController.php`

| Line | Identifier | Message |
|---:|---|---|
| 14 | `missingType.return` | Method App\Http\Controllers\ClientController::index() has no return type specified. |
| 21 | `binaryOp.invalid` | Binary operation "." between '%' and array\|string results in an error. |
| 21 | `binaryOp.invalid` | Binary operation "." between mixed and '%' results in an error. |
| 37 | `missingType.return` | Method App\Http\Controllers\ClientController::create() has no return type specified. |
| 45 | `missingType.return` | Method App\Http\Controllers\ClientController::store() has no return type specified. |
| 53 | `missingType.return` | Method App\Http\Controllers\ClientController::show() has no return type specified. |
| 65 | `missingType.return` | Method App\Http\Controllers\ClientController::edit() has no return type specified. |
| 77 | `missingType.return` | Method App\Http\Controllers\ClientController::update() has no return type specified. |
| 89 | `missingType.return` | Method App\Http\Controllers\ClientController::destroy() has no return type specified. |

#### `app/Http/Controllers/DashboardController.php`

| Line | Identifier | Message |
|---:|---|---|
| 13 | `property.nonObject` | Cannot access property $client on App\Models\User\|null. |

#### `app/Http/Controllers/ServiceRecordController.php`

| Line | Identifier | Message |
|---:|---|---|
| 24 | `larastan.relationExistence` | Relation 'client' is not found in App\Models\ServiceRecord model. |
| 26 | `binaryOp.invalid` | Binary operation "." between '%' and array\|string results in an error. |
| 26 | `binaryOp.invalid` | Binary operation "." between mixed and '%' results in an error. |
| 26 | `larastan.relationExistence` | Relation 'vehicle' is not found in App\Models\ServiceRecord model. |
| 27 | `binaryOp.invalid` | Binary operation "." between '%' and array\|string results in an error. |
| 27 | `binaryOp.invalid` | Binary operation "." between mixed and '%' results in an error. |
| 29 | `binaryOp.invalid` | Binary operation "." between '%' and array\|string results in an error. |
| 29 | `binaryOp.invalid` | Binary operation "." between mixed and '%' results in an error. |
| 45 | `missingType.return` | Method App\Http\Controllers\ServiceRecordController::create() has no return type specified. |
| 57 | `missingType.return` | Method App\Http\Controllers\ServiceRecordController::store() has no return type specified. |
| 71 | `missingType.return` | Method App\Http\Controllers\ServiceRecordController::show() has no return type specified. |
| 75 | `larastan.relationExistence` | Relation 'vehicle' is not found in App\Models\ServiceRecord model. |
| 85 | `missingType.return` | Method App\Http\Controllers\ServiceRecordController::edit() has no return type specified. |
| 90 | `larastan.relationExistence` | Relation 'vehicle' is not found in App\Models\ServiceRecord model. |
| 98 | `missingType.return` | Method App\Http\Controllers\ServiceRecordController::update() has no return type specified. |
| 112 | `missingType.return` | Method App\Http\Controllers\ServiceRecordController::destroy() has no return type specified. |

#### `app/Http/Controllers/Settings/PasswordController.php`

| Line | Identifier | Message |
|---:|---|---|
| 33 | `method.nonObject` | Cannot call method update() on App\Models\User\|null. |
| 34 | `argument.type` | Parameter #1 $value of static method Illuminate\Support\Facades\Hash::make() expects string, mixed given. |
| 34 | `offsetAccess.nonOffsetAccessible` | Cannot access offset 'password' on mixed. |

#### `app/Http/Controllers/Settings/ProfileController.php`

| Line | Identifier | Message |
|---:|---|---|
| 32 | `method.nonObject` | Cannot call method fill() on App\Models\User\|null. |
| 34 | `method.nonObject` | Cannot call method isDirty() on App\Models\User\|null. |
| 35 | `property.nonObject` | Cannot access property $email_verified_at on App\Models\User\|null. |
| 38 | `method.nonObject` | Cannot call method save() on App\Models\User\|null. |
| 56 | `method.nonObject` | Cannot call method delete() on App\Models\User\|null. |

#### `app/Http/Controllers/TechnicianController.php`

| Line | Identifier | Message |
|---:|---|---|
| 23 | `binaryOp.invalid` | Binary operation "." between '%' and array\|string results in an error. |
| 23 | `binaryOp.invalid` | Binary operation "." between mixed and '%' results in an error. |
| 29 | `return.type` | Method App\Http\Controllers\TechnicianController::index() should return Inertia\Response but returns Inertia\Response\|Inertia\ResponseFactory. |
| 38 | `missingType.return` | Method App\Http\Controllers\TechnicianController::create() has no return type specified. |
| 46 | `missingType.return` | Method App\Http\Controllers\TechnicianController::store() has no return type specified. |
| 58 | `missingType.return` | Method App\Http\Controllers\TechnicianController::show() has no return type specified. |
| 60 | `larastan.relationExistence` | Relation 'appointments' is not found in App\Models\Technician model. |
| 70 | `missingType.return` | Method App\Http\Controllers\TechnicianController::edit() has no return type specified. |
| 80 | `missingType.return` | Method App\Http\Controllers\TechnicianController::update() has no return type specified. |
| 92 | `missingType.return` | Method App\Http\Controllers\TechnicianController::destroy() has no return type specified. |

#### `app/Http/Controllers/VehicleController.php`

| Line | Identifier | Message |
|---:|---|---|
| 26 | `binaryOp.invalid` | Binary operation "." between '%' and array\|string results in an error. |
| 26 | `binaryOp.invalid` | Binary operation "." between mixed and '%' results in an error. |
| 28 | `binaryOp.invalid` | Binary operation "." between '%' and array\|string results in an error. |
| 28 | `binaryOp.invalid` | Binary operation "." between mixed and '%' results in an error. |
| 106 | `missingType.return` | Method App\Http\Controllers\VehicleController::destroy() has no return type specified. |

#### `app/Http/Middleware/HandleInertiaRequests.php`

| Line | Identifier | Message |
|---:|---|---|
| 39 | `return.type` | Method App\Http\Middleware\HandleInertiaRequests::share() should return array<string, mixed> but returns array. |

#### `app/Http/Requests/Settings/ProfileUpdateRequest.php`

| Line | Identifier | Message |
|---:|---|---|
| 26 | `property.nonObject` | Cannot access property $id on App\Models\User\|null. |

#### `app/Http/Requests/UpdateUserRequest.php`

| Line | Identifier | Message |
|---:|---|---|
| 14 | `property.nonObject` | Cannot access property $id on App\Models\User\|null. |
| 14 | `property.notFound` | Access to an undefined property (object\|string\|null)::$id. |

#### `app/Models/Appointment.php`

| Line | Identifier | Message |
|---:|---|---|
| 23 | `missingType.generics` | Method App\Models\Appointment::datetime() return type with generic class Illuminate\Database\Eloquent\Casts\Attribute does not specify its types: TGet, TSet |
| 26 | `argument.type` | Parameter #2 $timezone of static method Carbon\Carbon::parse() expects DateTimeZone\|int\|string\|null, mixed given. |
| 26 | `argument.type` | Parameter $get of static method Illuminate\Database\Eloquent\Casts\Attribute<mixed,mixed>::make() expects (callable(mixed, array<string, mixed>): Illuminate\Support\Carbon)\|null, Closure(string): Illuminate\Support\Carbon given. |
| 30 | `missingType.generics` | Method App\Models\Appointment::vehicle() return type with generic class Illuminate\Database\Eloquent\Relations\BelongsTo does not specify its types: TRelatedModel, TDeclaringModel |
| 35 | `missingType.generics` | Method App\Models\Appointment::user() return type with generic class Illuminate\Database\Eloquent\Relations\BelongsTo does not specify its types: TRelatedModel, TDeclaringModel |
| 40 | `missingType.generics` | Method App\Models\Appointment::technician() return type with generic class Illuminate\Database\Eloquent\Relations\BelongsTo does not specify its types: TRelatedModel, TDeclaringModel |
| 45 | `missingType.generics` | Method App\Models\Appointment::serviceRecords() return type with generic class Illuminate\Database\Eloquent\Relations\HasMany does not specify its types: TRelatedModel, TDeclaringModel |

#### `app/Models/Client.php`

| Line | Identifier | Message |
|---:|---|---|
| 24 | `missingType.generics` | Method App\Models\Client::user() return type with generic class Illuminate\Database\Eloquent\Relations\BelongsTo does not specify its types: TRelatedModel, TDeclaringModel |
| 29 | `missingType.generics` | Method App\Models\Client::vehicles() return type with generic class Illuminate\Database\Eloquent\Relations\HasMany does not specify its types: TRelatedModel, TDeclaringModel |
| 34 | `missingType.generics` | Method App\Models\Client::appointments() return type with generic class Illuminate\Database\Eloquent\Relations\HasManyThrough does not specify its types: TRelatedModel, TIntermediateModel, TDeclaringModel |

#### `app/Models/ServiceRecord.php`

| Line | Identifier | Message |
|---:|---|---|
| 24 | `missingType.generics` | Method App\Models\ServiceRecord::appointment() return type with generic class Illuminate\Database\Eloquent\Relations\BelongsTo does not specify its types: TRelatedModel, TDeclaringModel |
| 29 | `missingType.generics` | Method App\Models\ServiceRecord::technician() return type with generic class Illuminate\Database\Eloquent\Relations\BelongsTo does not specify its types: TRelatedModel, TDeclaringModel |

#### `app/Models/Technician.php`

| Line | Identifier | Message |
|---:|---|---|
| 19 | `missingType.generics` | Method App\Models\Technician::user() return type with generic class Illuminate\Database\Eloquent\Relations\BelongsTo does not specify its types: TRelatedModel, TDeclaringModel |
| 24 | `missingType.generics` | Method App\Models\Technician::serviceRecords() return type with generic class Illuminate\Database\Eloquent\Relations\HasMany does not specify its types: TRelatedModel, TDeclaringModel |

#### `app/Models/User.php`

| Line | Identifier | Message |
|---:|---|---|
| 53 | `missingType.generics` | Method App\Models\User::client() return type with generic class Illuminate\Database\Eloquent\Relations\HasOne does not specify its types: TRelatedModel, TDeclaringModel |
| 58 | `missingType.generics` | Method App\Models\User::technician() return type with generic class Illuminate\Database\Eloquent\Relations\HasOne does not specify its types: TRelatedModel, TDeclaringModel |
| 63 | `missingType.generics` | Method App\Models\User::appointments() return type with generic class Illuminate\Database\Eloquent\Relations\HasMany does not specify its types: TRelatedModel, TDeclaringModel |

#### `app/Models/Vehicle.php`

| Line | Identifier | Message |
|---:|---|---|
| 23 | `missingType.generics` | Method App\Models\Vehicle::client() return type with generic class Illuminate\Database\Eloquent\Relations\BelongsTo does not specify its types: TRelatedModel, TDeclaringModel |
| 28 | `missingType.generics` | Method App\Models\Vehicle::appointments() return type with generic class Illuminate\Database\Eloquent\Relations\HasMany does not specify its types: TRelatedModel, TDeclaringModel |

#### `app/Policies/AppointmentPolicy.php`

| Line | Identifier | Message |
|---:|---|---|
| 26 | `property.nonObject` | Cannot access property $client on Illuminate\Database\Eloquent\Model\|null. |
| 26 | `property.nonObject` | Cannot access property $user_id on mixed. |
| 51 | `property.nonObject` | Cannot access property $client on Illuminate\Database\Eloquent\Model\|null. |
| 51 | `property.nonObject` | Cannot access property $user_id on mixed. |

#### `config/app.php`

| Line | Identifier | Message |
|---:|---|---|
| 105 | `argument.type` | Parameter #2 $string of function explode expects string, bool\|string given. |

#### `config/cache.php`

| Line | Identifier | Message |
|---:|---|---|
| 106 | `argument.type` | Parameter #1 $title of static method Illuminate\Support\Str::slug() expects string, bool\|string given. |

#### `config/database.php`

| Line | Identifier | Message |
|---:|---|---|
| 150 | `argument.type` | Parameter #1 $title of static method Illuminate\Support\Str::slug() expects string, bool\|string given. |

#### `config/logging.php`

| Line | Identifier | Message |
|---:|---|---|
| 57 | `argument.type` | Parameter #2 $string of function explode expects string, bool\|string given. |

#### `config/mail.php`

| Line | Identifier | Message |
|---:|---|---|
| 49 | `argument.type` | Parameter #1 $url of function parse_url expects string, bool\|string given. |

#### `config/session.php`

| Line | Identifier | Message |
|---:|---|---|
| 132 | `argument.type` | Parameter #1 $title of static method Illuminate\Support\Str::slug() expects string, bool\|string given. |

## 5. Recommended adoption plan

1. Commit a `phpstan.neon` at **level 5**, fix the 7 findings (two are production 500s), and add it to CI so the baseline stays at zero.
2. Run `phpstan analyse --generate-baseline` at level 8 to freeze the existing 121 issues, then pay them down by root cause (§4.3) rather than file-by-file.
3. Exclude `config/` from analysis (6 findings, no signal).
4. Re-run at `max` quarterly; target 0 non-baselined errors.

## 6. Raw output

JSON reports (`phpstan-5.json`, `phpstan-max.json`) were generated in the session scratchpad; regenerate with `phpstan analyse --error-format=json`.
