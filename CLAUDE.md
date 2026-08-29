<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application running on PHP 8.4. You are an expert with the Laravel ecosystem. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists (settled decisions, non-obvious traps, standing constraints). Framework and package guidelines that only apply to specific paths (testing, frontend, components) also live there, under `.ai/rules/boost` — this is not just recorded decisions, it is load-bearing guidance you have not seen inline. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.
- Record durable rules with `record-rule` so the next agent or teammate inherits them instead of working them out again. Pass a `glob` (e.g. `app/Http/Controllers/**`), a short `title`, and a few-line `note`. Always use `record-rule`, never your native memory or notes tool — native memory is personal and session-scoped; only `.ai/rules` is shared with the team and persists in the repo.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== livewire/core rules ===

# Livewire

- Livewire allow to build dynamic, reactive interfaces in PHP without writing JavaScript.
- You can use Alpine.js for client-side interactions instead of JavaScript frameworks.
- Keep state server-side so the UI reflects it. Validate and authorize in actions as you would in HTTP requests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- The `{name}` argument should not include the test suite directory. Use `php artisan make:test --pest SomeFeatureTest` instead of `php artisan make:test --pest Feature/SomeFeatureTest`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

</laravel-boost-guidelines>

# Contexto del proyecto

Voy a construir un SaaS de turnos (booking system) en Laravel, arrancando el código de cero. El objetivo de este proyecto es que me guíes con la documentación oficial de Laravel (y de los paquetes que use: Fortify, Livewire, Flux) para que aprenda a fondo y me convierta en un buen desarrollador Laravel. Vos actuás como guía/QA, no como el que escribe el código.

## Cómo trabajar conmigo

- Respondé siempre en español.
- No saltes a escribir/implementar código ni entres en modo plan-para-implementar por defecto. La idea es pensar juntos la arquitectura y las decisiones — vos das trade-offs, señalás problemas reales en lo que yo vaya escribiendo, y fundamentás en la documentación oficial (usá search-docs). Yo escribo el código.
- Si algo requiere escribir algo concreto para avanzar, preguntame primero si lo quiero escribir yo con tu guía o si te pido que lo implementes vos.
- Nunca interactúes con el repositorio remoto (nada de git push/fetch/pull ni gh).

## Decisiones de arquitectura vigentes

(Actualizado 2026-08-23 — reemplaza decisiones anteriores donde haya conflicto.)

- Multi-tenancy: una sola base de datos, con columna `tenant_id` en cada tabla que pertenezca a un tenant. Desde el arranque, resolver el aislamiento de tenant con un Global Scope (o trait equivalente) en los modelos tenant-scoped, no filtrando `tenant_id` a mano en cada query — es el error de seguridad más común en apps multi-tenant y no queremos dejarlo para después. **Implementado en `Customer` (fail closed vía `booted()`); todavía falta en `Employee` y `Appointment`.**
- Tenant se identifica por dominio (columna `tenants.domain`), pero esa resolución **todavía no está implementada**. Por ahora, la separación `staff`/`customer` se hace por prefijo de ruta simple (`/staff/*`, `/customer/*`), no por subdominio. Migrar a subdominio se evalúa más adelante, si hace falta.
- `users` = identidad de autenticación pura: email (único por tenant), password, `role` enum. **Por ahora los roles son `admin, employee, customer` — no existe `superadmin` todavía.** `tenant_id` es obligatorio (NOT NULL) en `users`, porque no hay rol sin tenant por ahora.
- `employees`: siempre requieren un `User` (login obligatorio, porque necesitan gestionar el negocio). `user_id` obligatorio. **`employees.tenant_id` es una columna propia, duplicada a propósito de `employee->user->tenant_id`** (decisión consciente para facilitar filtrado sin joins, no un error). La consistencia entre ambos `tenant_id` se valida a nivel de aplicación (Action), no a nivel de base de datos — pendiente de implementar esa validación y su test.
- `Customer::creating` y el Global Scope de `Customer` exigen `auth()->check()` siempre (fail closed: sin usuario autenticado, tiran `AuthenticationException` en vez de devolver datos sin filtrar o crear sin tenant). Es deliberado: no hay excepción para seeders/factories/tests — cualquier código que cree o consulte `Customer` necesita un usuario autenticado en contexto (`actingAs()` en tests, `Auth::login()` en seeders).
- `customers`: NO requieren login por defecto. `user_id` nullable, `tenant_id` propio y obligatorio (no se puede derivar del user si no existe), y `name`/`phone`/`birthdate` propios en la tabla (no dependen de `users.name`). Dar de alta un cliente desde el panel de staff crea SOLO el registro de `Customer` — nada de cuenta de login.
- Crear una cuenta de login para un cliente ("invitar al portal") es una acción explícita y separada, no automática al crear el cliente. Cuando se haga: crear el `User` con email real + password random, y disparar `Password::sendResetLink()` (reutiliza las rutas/vistas de reset de Fortify que ya vienen con el starter kit, no hay que armar nada nuevo).
- El registro público (`Features::registration()` de Fortify) no tiene sentido en este modelo — todo `User` se crea desde un flujo interno (alta de staff o invitación de customer), nunca por auto-registro anónimo. **Todavía sigue habilitado en `config/fortify.php` y roto** (`CreateNewUser` no setea `tenant_id`, que es NOT NULL) — deshabilitarlo queda pendiente a propósito, ver gaps.
- Lógica de negocio organizada por entidad de dominio, no por área: `Actions/<Entidad>/` en singular (ej. `Actions/Customer/CreateCustomer.php`), porque una Action representa una operación de negocio reusable, no algo atado a una sola pantalla. Los componentes Livewire, en cambio, sí se organizan por área + entidad (`Livewire/Staff/Customers/Create.php`) porque están atados a una pantalla concreta — son convenciones distintas a propósito, no una inconsistencia. Los Form objects de Livewire van planos en `Livewire/Forms/` (sin anidar por área) y se nombran por la entidad sin verbo (`CustomerForm`, no `CreateCustomerForm`), para poder reusarse entre alta y edición como muestra la doc de Livewire.
- `users.name` en la práctica solo es relevante para roles sin tabla de perfil propia (por ahora, `admin`) — para `customer`/`employee` el nombre real vive en su propia tabla de perfil. Evaluar hacer `users.name` nullable por esto.
- Autorización con Policies por modelo (CustomerPolicy, EmployeePolicy, etc.), no con checks de rol sueltos repartidos por todos lados — salvo el middleware de gate de área (`staff` vs `customer`), que sí es un check de rol a nivel de ruta y es un caso válido para eso. Cuando se reintroduzca `superadmin`, usar `Gate::before` para que tenga acceso total sin repetir la excepción en cada policy.
- Separación de rutas/vistas por área: `routes/staff.php` (middleware `staff`, layout con sidebar, pensado para escritorio) y `routes/customer.php` (layout header + bottom-nav, pensado para mobile). Cada archivo de rutas se registra con su propio `require` en `web.php`. Los layouts siguen el patrón ya usado por el starter kit (`layouts/<área>/app.blade.php` + `layouts/<área>/sidebar.blade.php`).
- Lógica de negocio (ej. "crear un cliente") va en clases de Action planas (estilo `App\Actions\Fortify\CreateNewUser` del starter kit), separadas de los Form objects de Livewire (que solo manejan estado/validación de UI) y de los componentes Livewire (que solo orquestan y manejan feedback visual). Las Actions no dependen de Livewire y se testean directo con Pest.
- Componentes Livewire: seguir el patrón de dos archivos (clase en app/Livewire + vista en resources/views/livewire) que ya trae el starter kit para Settings/Auth, no el formato de archivo único (SFC) de Livewire 4, para mantener consistencia.
- Higiene de migraciones: mientras el modelo de datos esté en etapa de diseño (sin datos reales en producción), está bien seguir editando las migraciones `create_*` directamente en vez de acumular migraciones `alter_*`. El corte a migraciones aditivas-solamente es a partir de que haya datos reales que no se puedan perder.
- El scoping de tenant, las policies de autorización y los middlewares/checks de rol (como `EnsureUserIsStaff`) son código de seguridad: deberían tener tests (Pest) desde que se escriben, no quedar para "después".

## Gaps conocidos, aceptados y pendientes

(No son descubrimientos nuevos si los volvés a ver.)

- Global Scope de aislamiento de tenant: implementado solo en `Customer`. Falta replicarlo en `Employee` y `Appointment`.
- `CustomerPolicy` no existe todavía y `Livewire\Staff\Customers\Create::save()` no tiene `#[Authorize]` — cualquier `admin`/`employee` autenticado puede crear un `Customer` sin chequeo granular, solo el middleware `staff` grueso lo protege.
- `Customer`, `Employee`, `Appointment` no tienen `HasFactory` — hace falta para escribir factories/tests de estos modelos.
- `UserSeeder` tiene `tenant_id => 1` hardcodeado en vez de referenciar el tenant creado por `TenantSeeder` — frágil si cambia el orden de seeders o se corre más de una vez sin `migrate:fresh`.
- No hay validación de solapamiento de turnos para un mismo empleado (unique constraint solo bloquea duplicados exactos) — mismo problema en `appointments` y en `employees_schedules`.
- `customers.email` (opcional, para poder invitar al portal después) y hacer `users.name` nullable — decisiones tomadas en el diseño pero todavía no aplicadas al código.
- `password_reset_tokens` usa `email` como clave primaria global, pero `users.email` es único solo por tenant — riesgo de colisión entre tenants con el mismo email hasta que se resuelva la identificación de tenant por dominio.
- Middleware `EnsureUserIsStaff` y el `LoginResponse` custom de Fortify (redirect según rol) todavía no tienen test Pest.
- Los tests (`phpunit.xml`) siguen configurados para SQLite en memoria (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`), pero `pdo_sqlite` no está instalado en el entorno — la app real ya corre con Postgres (`.env`: `DB_CONNECTION=pgsql`). Hay que decidir entre instalar `pdo_sqlite` o pasar los tests a Postgres antes de poder correr la suite. Bloquea escribir tests nuevos hasta que se resuelva.
