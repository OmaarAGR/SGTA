# SGTA — Test & Coverage Report

| | |
|---|---|
| **Project** | SGTA (Laravel 12 + Inertia) |
| **Branch / commit** | `feature/initial-project-setup` @ `054d7ea` |
| **Generated** | 2026-10-08 |
| **Runner** | PHPUnit 11.5.21 |
| **Runtime** | PHP 8.5.11 · Xdebug 3.5.3 (`XDEBUG_MODE=coverage`) |
| **Database** | SQLite `:memory:` (per `phpunit.xml`) |

## 1. Executive summary

| Metric | Result |
|---|---|
| Tests executed | **16** |
| Assertions | **45** |
| Passed / Failed / Errors | **16 / 0 / 0** |
| Runner warnings | 3 (empty test classes, see §5) |
| PHP deprecations | 5 distinct (all PHP 8.5 compatibility, see §6) |
| Total run time | 1.96 s (≈ 52 MB) |
| **Line coverage** | **17.9%** (104/580 statements) |
| **Method coverage** | **15.8%** (23/146) |
| **Class coverage** | **17.4 %** (8/46) |

**Verdict:** the suite is green, but it only exercises the Laravel starter-kit surface (authentication, dashboard, profile/password settings). **None of the business domain — appointments, clients, vehicles, technicians, service records, policies, form requests — is tested.** 35 of 46 files with executable code have 0 % coverage, accounting for 466 of 580 statements (80.3%).

## 2. How this was run

```bash
composer install --ignore-platform-req=php   # see note below
npm ci && npm run build                      # Vite manifest required by Inertia views
XDEBUG_MODE=coverage php vendor/bin/phpunit --testdox \
    --coverage-text --coverage-clover clover.xml --coverage-html cov --log-junit junit.xml
```

Environment notes (things that blocked a clean first run):

1. **`composer install` fails on PHP 8.5.** `composer.lock` pins `nette/utils v4.0.6` and `nette/schema v1.3.2`, which only allow PHP ≤ 8.4. I installed with `--ignore-platform-req=php`; everything still worked, but the lock file should be refreshed (`composer update nette/utils nette/schema`) before anyone runs on 8.5.
2. **First run: 4 failures, all environmental.** `Login screen`, `Registration screen`, `Profile page` and `Dashboard` tests returned HTTP 500 with `ViteManifestNotFoundException` because `public/build/manifest.json` did not exist. After `npm ci && npm run build` all 16 tests pass. CI must build assets before `phpunit` (or call `$this->withoutVite()` in `TestCase`).
3. `.env` was created from `.env.example` and an app key generated.

## 3. Test results

| File | Test | Assertions | Time | Result |
|---|---|---:|---:|---|
| `tests/Feature/Auth/AuthenticationTest.php` | `test_login_screen_can_be_rendered` | 1 | 179 ms | ✅ Pass |
| `tests/Feature/Auth/AuthenticationTest.php` | `test_users_can_authenticate_using_the_login_screen` | 3 | 305 ms | ✅ Pass |
| `tests/Feature/Auth/AuthenticationTest.php` | `test_users_can_not_authenticate_with_invalid_password` | 1 | 223 ms | ✅ Pass |
| `tests/Feature/Auth/AuthenticationTest.php` | `test_users_can_logout` | 3 | 20 ms | ✅ Pass |
| `tests/Feature/Auth/RegistrationTest.php` | `test_registration_screen_can_be_rendered` | 1 | 22 ms | ✅ Pass |
| `tests/Feature/Auth/RegistrationTest.php` | `test_new_users_can_register` | 3 | 155 ms | ✅ Pass |
| `tests/Feature/DashboardTest.php` | `test_guests_are_redirected_to_the_login_page` | 2 | 16 ms | ✅ Pass |
| `tests/Feature/DashboardTest.php` | `test_authenticated_users_can_visit_the_dashboard` | 1 | 24 ms | ✅ Pass |
| `tests/Feature/Settings/PasswordUpdateTest.php` | `test_password_can_be_updated` | 4 | 426 ms | ✅ Pass |
| `tests/Feature/Settings/PasswordUpdateTest.php` | `test_correct_password_must_be_provided_to_update_password` | 4 | 157 ms | ✅ Pass |
| `tests/Feature/Settings/ProfileUpdateTest.php` | `test_profile_page_is_displayed` | 1 | 30 ms | ✅ Pass |
| `tests/Feature/Settings/ProfileUpdateTest.php` | `test_profile_information_can_be_updated` | 6 | 25 ms | ✅ Pass |
| `tests/Feature/Settings/ProfileUpdateTest.php` | `test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged` | 4 | 24 ms | ✅ Pass |
| `tests/Feature/Settings/ProfileUpdateTest.php` | `test_user_can_delete_their_account` | 5 | 157 ms | ✅ Pass |
| `tests/Feature/Settings/ProfileUpdateTest.php` | `test_correct_password_must_be_provided_to_delete_account` | 5 | 157 ms | ✅ Pass |
| `tests/Unit/ExampleTest.php` | `test_that_true_is_true` | 1 | 13 ms | ✅ Pass |

### Suite structure

| Suite | Files | Tests | Notes |
|---|---:|---:|---|
| Unit | 1 | 1 | `ExampleTest` is the stock `assertTrue(true)` placeholder — it tests nothing |
| Feature / Auth | 5 | 6 | 3 of the 5 files are fully commented out (§5) |
| Feature / Settings | 2 | 7 | Profile + password updates, account deletion |
| Feature (root) | 1 | 2 | Dashboard guest redirect / authenticated access |

## 4. Coverage

### 4.1 By area

| Area | Files | Lines | Line % | Methods | Method % |
|---|---:|---:|---:|---:|---:|
| `app/Console` | 1 | 0/1 | 0.0% | 0/1 | 0.0% |
| `app/Http/Controllers` | 19 | 54/414 | 13.0% | 10/59 | 16.9% |
| `app/Http/Middleware` | 2 | 16/16 | 100.0% | 3/3 | 100.0% |
| `app/Http/Requests` | 13 | 26/86 | 30.2% | 5/28 | 17.9% |
| `app/Models` | 6 | 6/23 | 26.1% | 3/18 | 16.7% |
| `app/Policies` | 5 | 0/38 | 0.0% | 0/35 | 0.0% |
| `app/Providers` | 1 | 2/2 | 100.0% | 2/2 | 100.0% |
| **Total** | **47** | **104/580** | **17.9%** | **23/146** | **15.8%** |

### 4.2 By file

| File | Lines | Line % | Methods | Method % | Status |
|---|---:|---:|---:|---:|---|
| `app/Console/Commands/CreateAdminUser.php` | 0/1 | 0.0% | 0/1 | 0.0% | 🔴 None |
| `app/Http/Controllers/AppointmentController.php` | 0/115 | 0.0% | 0/7 | 0.0% | 🔴 None |
| `app/Http/Controllers/Auth/AuthenticatedSessionController.php` | 11/11 | 100.0% | 3/3 | 100.0% | 🟢 Full |
| `app/Http/Controllers/Auth/ConfirmablePasswordController.php` | 0/10 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Http/Controllers/Auth/EmailVerificationNotificationController.php` | 0/4 | 0.0% | 0/1 | 0.0% | 🔴 None |
| `app/Http/Controllers/Auth/EmailVerificationPromptController.php` | 0/3 | 0.0% | 0/1 | 0.0% | 🔴 None |
| `app/Http/Controllers/Auth/NewPasswordController.php` | 0/24 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Http/Controllers/Auth/PasswordResetLinkController.php` | 0/10 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | 15/15 | 100.0% | 2/2 | 100.0% | 🟢 Full |
| `app/Http/Controllers/Auth/VerifyEmailController.php` | 0/6 | 0.0% | 0/1 | 0.0% | 🔴 None |
| `app/Http/Controllers/Client/ClientAppointmentController.php` | 0/35 | 0.0% | 0/3 | 0.0% | 🔴 None |
| `app/Http/Controllers/Client/ClientVehicleController.php` | 0/3 | 0.0% | 0/1 | 0.0% | 🔴 None |
| `app/Http/Controllers/ClientController.php` | 0/29 | 0.0% | 0/7 | 0.0% | 🔴 None |
| `app/Http/Controllers/Controller.php` | 0/0 | n/a | 0/0 | n/a | — |
| `app/Http/Controllers/DashboardController.php` | 2/2 | 100.0% | 1/1 | 100.0% | 🟢 Full |
| `app/Http/Controllers/ServiceRecordController.php` | 0/46 | 0.0% | 0/7 | 0.0% | 🔴 None |
| `app/Http/Controllers/Settings/PasswordController.php` | 8/9 | 88.9% | 1/2 | 50.0% | 🟡 Partial |
| `app/Http/Controllers/Settings/ProfileController.php` | 18/18 | 100.0% | 3/3 | 100.0% | 🟢 Full |
| `app/Http/Controllers/TechnicianController.php` | 0/33 | 0.0% | 0/7 | 0.0% | 🔴 None |
| `app/Http/Controllers/VehicleController.php` | 0/41 | 0.0% | 0/7 | 0.0% | 🔴 None |
| `app/Http/Middleware/HandleAppearance.php` | 2/2 | 100.0% | 1/1 | 100.0% | 🟢 Full |
| `app/Http/Middleware/HandleInertiaRequests.php` | 14/14 | 100.0% | 2/2 | 100.0% | 🟢 Full |
| `app/Http/Requests/Auth/LoginRequest.php` | 15/23 | 65.2% | 4/5 | 80.0% | 🟡 Partial |
| `app/Http/Requests/Settings/ProfileUpdateRequest.php` | 11/11 | 100.0% | 1/1 | 100.0% | 🟢 Full |
| `app/Http/Requests/StoreAppointmentRequest.php` | 0/8 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Http/Requests/StoreClientRequest.php` | 0/4 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Http/Requests/StoreServiceRecordRequest.php` | 0/4 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Http/Requests/StoreTechnicianRequest.php` | 0/4 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Http/Requests/StoreVehicleRequest.php` | 0/4 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Http/Requests/UpdateAppointmentRequest.php` | 0/4 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Http/Requests/UpdateClientRequest.php` | 0/4 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Http/Requests/UpdateServiceRecordRequest.php` | 0/4 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Http/Requests/UpdateTechnicianRequest.php` | 0/4 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Http/Requests/UpdateUserRequest.php` | 0/8 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Http/Requests/UpdateVehicleRequest.php` | 0/4 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Models/Appointment.php` | 0/7 | 0.0% | 0/5 | 0.0% | 🔴 None |
| `app/Models/Client.php` | 0/3 | 0.0% | 0/3 | 0.0% | 🔴 None |
| `app/Models/ServiceRecord.php` | 0/2 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Models/Technician.php` | 0/2 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Models/User.php` | 6/7 | 85.7% | 3/4 | 75.0% | 🟡 Partial |
| `app/Models/Vehicle.php` | 0/2 | 0.0% | 0/2 | 0.0% | 🔴 None |
| `app/Policies/AppointmentPolicy.php` | 0/10 | 0.0% | 0/7 | 0.0% | 🔴 None |
| `app/Policies/ClientPolicy.php` | 0/7 | 0.0% | 0/7 | 0.0% | 🔴 None |
| `app/Policies/ServiceRecordPolicy.php` | 0/7 | 0.0% | 0/7 | 0.0% | 🔴 None |
| `app/Policies/TechnicianPolicy.php` | 0/7 | 0.0% | 0/7 | 0.0% | 🔴 None |
| `app/Policies/VehiclePolicy.php` | 0/7 | 0.0% | 0/7 | 0.0% | 🔴 None |
| `app/Providers/AppServiceProvider.php` | 2/2 | 100.0% | 2/2 | 100.0% | 🟢 Full |

### 4.3 Partially covered code

| File | Gap |
|---|---|
| `Http/Requests/Auth/LoginRequest.php` | 8 uncovered lines (65.2 %): lines 66–75, the lockout branch of `ensureIsNotRateLimited()` (too many failed attempts) |
| `Settings/PasswordController.php` | `edit()` (line 20) never called — the password settings page is not rendered in any test |
| `Models/User.php` | `appointments()` relation (line 65) never used |

### 4.4 Largest untested files (by statements)

| File | Statements |
|---|---:|
| `app/Http/Controllers/AppointmentController.php` | 115 |
| `app/Http/Controllers/ServiceRecordController.php` | 46 |
| `app/Http/Controllers/VehicleController.php` | 41 |
| `app/Http/Controllers/Client/ClientAppointmentController.php` | 35 |
| `app/Http/Controllers/TechnicianController.php` | 33 |
| `app/Http/Controllers/ClientController.php` | 29 |
| `app/Http/Controllers/Auth/NewPasswordController.php` | 24 |
| `app/Http/Controllers/Auth/ConfirmablePasswordController.php` | 10 |
| `app/Http/Controllers/Auth/PasswordResetLinkController.php` | 10 |
| `app/Policies/AppointmentPolicy.php` | 10 |

## 5. Test-suite quality findings

| # | Finding | Impact |
|---|---|---|
| 1 | **No tests for any domain feature.** Appointments (115 stmts), service records, vehicles, clients, technicians, the client portal, and all five `Policy` classes are at 0 %. | High — authorization rules (who may view/edit what) are completely unverified. |
| 2 | **3 test classes are commented out** (`EmailVerificationTest`, `PasswordConfirmationTest`, `PasswordResetTest`) → PHPUnit warning *“No tests found in class”*. | Medium — the matching controllers (`NewPasswordController`, `VerifyEmailController`, …) are at 0 %. Either re-enable them or delete the dead files. |
| 3 | `tests/Unit/ExampleTest.php` is a placeholder. | Low — inflates the test count by 1 without testing anything. |
| 4 | Factories exist for all domain models (`Appointment`, `Client`, `ServiceRecord`, `Technician`, `Vehicle`), so writing domain tests is cheap — they are simply not used yet. | Medium |
| 5 | `LoginRequest` lockout (rate-limit) behaviour untested. | Medium — security-relevant. |
| 6 | Several domain bugs found by static analysis (see the companion report) would have been caught by even a smoke test of `GET /service-records?search=x` and `GET /technicians/{id}`. | High |

## 6. Warnings & deprecations

PHPUnit reports 5 distinct deprecations across 15 tests. All are PHP 8.5 compatibility, none are test failures:

| Source | Message | Owner |
|---|---|---|
| `config/database.php:61, :81` | `PDO::MYSQL_ATTR_SSL_CA` deprecated since 8.5 → use `Pdo\Mysql::ATTR_SSL_CA` | **This project** — fix with `PHP_VERSION_ID >= 80500 ? \Pdo\Mysql::ATTR_SSL_CA : PDO::MYSQL_ATTR_SSL_CA` |
| `vendor/laravel/framework/config/database.php:62, :82` | same constant | Laravel 12.15 — resolved by upgrading the framework |
| `vendor/nikic/php-parser/.../Php8.php:2580` | `SplObjectStorage::attach()` deprecated | php-parser (used by the coverage driver) — upgrade `nikic/php-parser` |

The `⚠` marks next to the login tests in `--testdox` are these deprecations being attributed to the first tests that boot the app; the tests themselves pass.

## 7. Recommendations (priority order)

1. **Fix the two runtime bugs** found by PHPStan (`ServiceRecordController` search, `TechnicianController::show`), and add a feature test for each — they are real HTTP 500s.
2. **Add policy tests** (`AppointmentPolicy`, `ClientPolicy`, `VehiclePolicy`, `TechnicianPolicy`, `ServiceRecordPolicy`): 38 statements, pure logic, cheapest coverage with the highest security value.
3. **Add CRUD feature tests** per controller (index/store/update/destroy, validation failures, and forbidden access by role). Controllers and requests account for most of the 466-statement untested statements, so this is where the coverage gain is.
4. **Re-enable or delete** the three commented-out auth test files.
5. **Add `FormRequest` validation tests** (datasets via `#[DataProvider]`) for the 10 request classes.
6. **CI:** build assets before tests, pin PHP ≤ 8.4 *or* refresh the lock file, and add a coverage gate (suggest starting at the current 18 % and ratcheting up).
7. Remove `ExampleTest`.

## 8. Artifacts

HTML coverage, Clover XML and JUnit XML were produced during the run (kept outside the repository in the session scratchpad). Re-generate with the commands in §2.
