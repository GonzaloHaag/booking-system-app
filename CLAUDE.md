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

(Actualizado 2026-10-12 — reemplaza decisiones anteriores donde haya conflicto.)

- **Layout compartido por rol:** los componentes Livewire de página completa sin `#[Layout]` (Settings) y la vista `dashboard` genérica usan el default `component_layout => layouts::app` (no hay `config/livewire.php` publicado; rige el default del paquete). `resources/views/layouts/app.blade.php` es un despachador: si `auth()->user()->isStaff()` renderiza `<x-layouts::staff.app>`, si no `<x-layouts::customer.app>`. Una sola decisión en un solo archivo; las pantallas de `staff`/`customer` que usan su layout directo no pasan por acá. Supone usuario autenticado (hoy todas las rutas que lo usan están detrás de `auth`); una página pública no puede usar `layouts::app`. La doc de Livewire documenta `#[Layout]` (estático) y `->layout()` en `render()` (dinámico) pero no layouts por rol: elegir por rol es decisión de la app, alternativa descartada por repetir la condición en cada componente.
- **Logout:** `FortifyServiceProvider::register()` bindea un `LogoutResponse` que redirige a `route('login')` (por defecto Fortify redirige a `/`), mismo mecanismo que el `LoginResponse` custom. Aplica a todos los botones de logout, que hacen POST a la ruta `logout` de Fortify.

- Multi-tenancy: una sola base de datos, con columna `tenant_id` en cada tabla que pertenezca a un tenant. Desde el arranque, resolver el aislamiento de tenant con un Global Scope (o trait equivalente) en los modelos tenant-scoped, no filtrando `tenant_id` a mano en cada query — es el error de seguridad más común en apps multi-tenant y no queremos dejarlo para después. **Implementado en `Customer` y `Employee` (fail closed vía `booted()`); todavía falta en `Appointment`.**
- Tenant se identifica por dominio (columna `tenants.domain`), pero esa resolución **todavía no está implementada**. Por ahora, la separación `staff`/`customer` se hace por prefijo de ruta simple (`/staff/*`, `/customer/*`), no por subdominio. Migrar a subdominio se evalúa más adelante, si hace falta.
- `users` = identidad de autenticación pura: email (único por tenant), password, `role` enum. **Por ahora los roles son `admin, employee, customer` — no existe `superadmin` todavía.** `tenant_id` es obligatorio (NOT NULL) en `users`, porque no hay rol sin tenant por ahora.
- `employees`: siempre requieren un `User` (login obligatorio, porque necesitan gestionar el negocio). `user_id` obligatorio. **`employees.tenant_id` es una columna propia, duplicada a propósito de `employee->user->tenant_id`** (decisión consciente para facilitar filtrado sin joins, no un error). `Actions\Employee\CreateEmployee` garantiza la consistencia **por construcción**: crea el `User` primero (su `booted()` le asigna `tenant_id` desde `auth()->user()->tenant_id`) y copia ese mismo `$user->tenant_id` al `Employee`, en la misma transacción — no hay dos asignaciones independientes que puedan divergir. No hace falta validación adicional a nivel de aplicación; pendiente sí un test que fije ese invariante.
- `employee_schedules`: un rango horario **continuo por día** por empleado (sin turnos partidos, decisión tomada a propósito para simplificar). "Día libre" = **ausencia de fila** para ese `day_of_week`, no un campo `is_active` — se evaluó explícitamente usar `is_active` y se descartó: obligaría a todo lector futuro de la tabla (reportes, disponibilidad de turnos, lo que sea) a acordarse de filtrar por ese flag; con ausencia de fila, esa pregunta no existe. `unique(employee_id, day_of_week)` a nivel de DB garantiza un solo horario por día. Domingo (`day_of_week = 0`) queda excluido a propósito de la UI (`EmployeeScheduleForm`) porque el negocio no abre domingos — no es un olvido.
- **`EmployeeSchedule` no tiene Global Scope de tenant**, a diferencia de `Customer`/`Employee` — decisión consciente, no un gap. A diferencia de esos dos modelos, `EmployeeSchedule` no es un recurso de primer nivel: no se lista ni se busca por ID directo desde el cliente, solo se alcanza a través de un `Employee` ya validado por su propio Global Scope (`employee_id` no-nulo, cascade delete). Este supuesto se rompe el día que algún flujo reciba un ID de `EmployeeSchedule` directo del cliente sin pasar antes por `Employee` — ahí hay que revisar si sigue aplicando.
- UI de horarios: **modal por fila** (`flux:modal`/`flux:modal.trigger` con `name` parametrizado por `employee->id`, ej. `schedule-employee-{id}`), no un modal único compartido con eventos. Se evaluó la alternativa (un solo modal + `$dispatch`/`#[On]` para cargar el empleado clickeado) y se descartó por ser complejidad injustificada para la escala actual (pocos empleados por tenant) — revisar solo si se vuelve un problema de performance real, no antes.
- `EmployeeSchedule::start_time`/`end_time` tienen **accessors** que cortan el valor a `HH:MM` — Postgres devuelve columnas `time` como `HH:MM:SS`, que no matchea el formato que esperan los `<input type="time">` ni la regla `date_format:H:i`.
- Un `customer` nunca asciende a `employee` (ni viceversa): es una regla real del negocio, no una simplificación temporal. Confirma que `users.role` es un invariante real (un `User` tiene un solo rol de negocio para siempre), no una limitación técnica a resolver después. Consecuencia directa: el alta de `Employee` siempre crea un `User` nuevo en la misma Action (nunca vincula uno existente) — no hace falta una Action separada para "promover" un `User` preexistente ni validar su `tenant_id` contra el de un usuario ya creado.
- `Customer::creating` y el Global Scope de `Customer` exigen `auth()->check()` siempre (fail closed: sin usuario autenticado, tiran `AuthenticationException` en vez de devolver datos sin filtrar o crear sin tenant). Es deliberado: no hay excepción para seeders/factories/tests — cualquier código que cree o consulte `Customer` necesita un usuario autenticado en contexto (`actingAs()` en tests, `Auth::login()` en seeders).
- `customers`: NO requieren login por defecto. `user_id` nullable, `tenant_id` propio y obligatorio (no se puede derivar del user si no existe), y `name`/`phone`/`birthdate` propios en la tabla (no dependen de `users.name`). Dar de alta un cliente desde el panel de staff crea SOLO el registro de `Customer` — nada de cuenta de login.
- Crear una cuenta de login para un cliente es una acción explícita y separada (`Actions/Customer/CreateCustomerAccount` o similar), no automática al crear el cliente. **No hay invitación por mail ni password random**: el staff carga el email y la contraseña directamente en el momento, como un alta de cuenta común — reutilizando `App\Concerns\PasswordValidationRules` (el mismo trait que ya usa `App\Actions\Fortify\CreateNewUser`) para las reglas de password, así la política es consistente en toda la app. El único email que existe para un customer es `users.email`, y solo aparece cuando se le creó la cuenta — `customers` no tiene columna `email` propia.
- El registro público (`Features::registration()` de Fortify) no tiene sentido en este modelo — todo `User` se crea desde un flujo interno (alta de staff o invitación de customer), nunca por auto-registro anónimo. **Todavía sigue habilitado en `config/fortify.php` y roto** (`CreateNewUser` no setea `tenant_id`, que es NOT NULL) — deshabilitarlo queda pendiente a propósito, ver gaps.
- Lógica de negocio organizada por entidad de dominio, no por área: `Actions/<Entidad>/` en singular (ej. `Actions/Customer/CreateCustomer.php`), porque una Action representa una operación de negocio reusable, no algo atado a una sola pantalla. Los componentes Livewire, en cambio, sí se organizan por área + entidad (`Livewire/Staff/Customers/Create.php`) porque están atados a una pantalla concreta — son convenciones distintas a propósito, no una inconsistencia. Los Form objects de Livewire van planos en `Livewire/Forms/` (sin anidar por área) y se nombran por la entidad sin verbo (`CustomerForm`, no `CreateCustomerForm`), para poder reusarse entre alta y edición como muestra la doc de Livewire.
- `users.name` en la práctica solo es relevante para roles sin tabla de perfil propia (por ahora, `admin`) — para `customer`/`employee` el nombre real vive en su propia tabla de perfil. Evaluar hacer `users.name` nullable por esto.
- Autorización con Policies por modelo (CustomerPolicy, EmployeePolicy, etc.), no con checks de rol sueltos repartidos por todos lados — salvo el middleware de gate de área (`staff` vs `customer`), que sí es un check de rol a nivel de ruta y es un caso válido para eso. Cuando se reintroduzca `superadmin`, usar `Gate::before` para que tenga acceso total sin repetir la excepción en cada policy.
- **La autorización se aplica en el componente Livewire, nunca dentro de la Action.** Las Actions son operaciones de negocio planas y reusables (consola, seeders, jobs, sin usuario autenticado) y no saben de permisos. Cada componente autoriza antes de llamar a la Action: `#[Authorize('create', Modelo::class)]` en altas, `#[Authorize('update', 'propiedad')]` cuando el modelo es una propiedad pública del componente, y `$this->authorize('delete', $modelo)` cuando llega un id suelto (se busca primero con `findOrFail`, que pasa por el Global Scope, y recién después se autoriza). Consecuencia: cualquier punto de entrada nuevo (API, controlador) también tiene que autorizar.
- `ServicePolicy` (implementada): `create` exige `isStaff()`; `update` y `delete` exigen `isStaff()` y que `$user->tenant_id === $service->tenant_id` (redundante con el Global Scope a propósito, defensa en profundidad). `viewAny` es `true` para cualquier usuario autenticado (no hay modelo contra el cual comparar; el Global Scope ya limita el listado al tenant) y `view` exige solo el mismo tenant, **sin `isStaff()`**, porque los `customer` también ven servicios (los necesitan para elegir uno al reservar). Ver servicios no implica acceso a la pantalla de administración: esa sigue cerrada por el middleware `staff`. Resultado: staff y customer ven, solo staff modifica. Ninguna de las dos se consulta todavía en el código (la pantalla de `Table` ya está protegida por el middleware); quedan definidas para el flujo de reserva. No tiene `restore`/`forceDelete` porque `services` no usa soft delete. Es el patrón a replicar en `CustomerPolicy` y `EmployeePolicy`.
- Separación de rutas/vistas por área: `routes/staff.php` (middleware `staff`, layout con sidebar, pensado para escritorio) y `routes/customer.php` (layout header + bottom-nav, pensado para mobile). Cada archivo de rutas se registra con su propio `require` en `web.php`. Los layouts siguen el patrón ya usado por el starter kit (`layouts/<área>/app.blade.php` + `layouts/<área>/sidebar.blade.php`).
- Lógica de negocio (ej. "crear un cliente") va en clases de Action planas (estilo `App\Actions\Fortify\CreateNewUser` del starter kit), separadas de los Form objects de Livewire (que solo manejan estado/validación de UI) y de los componentes Livewire (que solo orquestan y manejan feedback visual). Las Actions no dependen de Livewire y se testean directo con Pest.
- Componentes Livewire: seguir el patrón de dos archivos (clase en app/Livewire + vista en resources/views/livewire) que ya trae el starter kit para Settings/Auth, no el formato de archivo único (SFC) de Livewire 4, para mantener consistencia.
- Nomenclatura de componentes Livewire dentro de `Livewire/Staff/<Entidad>/`: `Table` para el componente que lista el recurso (no `Index` — colisionaba con el "index" real, que es la página que lo embebe en `routes/staff.php` + `views/staff/<entidad>.blade.php`), `Create` para el alta puntual y `Update` para la edición (verbos que coinciden con las Actions `Create<Entidad>`/`Update<Entidad>`, los eventos `<entidad>-created`/`-updated` y los nombres de modal `create-`/`update-<entidad>`; no `Form`, porque colisionaría con los Form objects `<Entidad>Form` de `Livewire/Forms/`, que sí se reusan entre alta y edición). El borrado no tiene componente propio: es un método `delete()` en `Table`, con un modal de confirmación por fila en su vista (el evento entre componentes solo hace falta cuando el cambio lo origina un componente hermano, no `Table` mismo). Los componentes hijos montados en un loop (ej. `Update` por fila) llevan su propio `:wire:key` con prefijo, además del `wire:key` de la card. Para lo que no encaje en ese patrón CRUD, nombre por rol (ej. `Schedule`: no lista ni da de alta un recurso, es una acción puntual sobre un `Employee` ya existente). Criterio a futuro: nombre por rol cuando el componente es una pieza de UI reusable/ambigua en contexto; nombre por verbo cuando es una acción puntual sin plan de reuso. Implementado en `Customers`, `Employees` y `Services`.
- `services`: **sin soft delete por ahora** (hard delete). Hoy es inocuo porque `appointments` no tiene `service_id`. **Se revisa en el momento en que se agregue esa relación**: un hard delete borraría o bloquearía (según el FK) el historial de turnos del servicio. En ese punto elegir entre soft delete o `restrictOnDelete` y bloquear el borrado si tiene turnos.
- Higiene de migraciones: mientras el modelo de datos esté en etapa de diseño (sin datos reales en producción), está bien seguir editando las migraciones `create_*` directamente en vez de acumular migraciones `alter_*`. El corte a migraciones aditivas-solamente es a partir de que haya datos reales que no se puedan perder.
- El scoping de tenant, las policies de autorización y los middlewares/checks de rol (como `EnsureUserIsStaff`) son código de seguridad: deberían tener tests (Pest) desde que se escriben, no quedar para "después".

## Gaps conocidos, aceptados y pendientes

(No son descubrimientos nuevos si los volvés a ver.)

- Global Scope de aislamiento de tenant: implementado en `Customer` y `Employee`. Falta replicarlo en `Appointment`.
- `CustomerPolicy` no existe todavía y `Livewire\Staff\Customers\Create::save()` no tiene `#[Authorize]` — cualquier `admin`/`employee` autenticado puede crear un `Customer` sin chequeo granular, solo el middleware `staff` grueso lo protege.
- `Customer`, `Employee`, `EmployeeSchedule`, `Appointment` no tienen `HasFactory` ni factory — hace falta para escribir tests de estos modelos. `Service` ya los tiene (`ServiceFactory`).
- **Los tests corren contra Postgres** (`phpunit.xml`: `DB_CONNECTION=pgsql`, `DB_DATABASE=booking_system_db_testing`, distinta de la de desarrollo a propósito: `RefreshDatabase` hace `migrate:fresh`). **Estado actual (2026-10-12): 63 tests, 61 pasan, 1 falla (`RegistrationTest`), 1 skipped (2FA).** Se destrabó sin aflojar el `booted()` de `User` (código de seguridad, sigue fail closed): `UserFactory` define `tenant_id` (`Tenant::factory()`), `role` (por defecto `customer`) y estados `admin()`/`employee()`/`customer()`; `tests/Pest.php` tiene dos helpers globales: `createUser(array $attributes = [], ?Tenant $tenant = null)` crea el `User` dentro de `User::withoutEvents(...)` (así no choca con `creating` ni se pisa el `tenant_id`; pasar `$tenant` sirve para usuarios de otro tenant), y `actingAsUser(UserRole $role = ADMIN, ?Tenant $tenant = null, array $attributes = [])` lo crea y lo autentica. Los tests heredados de Auth/Settings/Dashboard usan `createUser()`. `actingAsUser()` lo usan los tests de Servicios; también hay `createService(Tenant $tenant, array $attributes = [])`, que crea un `Service` de un tenant dado con `Service::withoutEvents(...)` y pasa `tenant_id` directo, porque `Service` no tiene la relación `tenant()` que necesita `->for()`. Pendientes: `RegistrationTest` prueba el registro público, que no existe en este modelo (se deja fallando a propósito; borrarlo requiere aprobación; la solución de fondo es deshabilitar `Features::registration()`); el test de 2FA queda skipped porque Fortify no tiene esa feature habilitada, y `UserFactory::withTwoFactor()` sigue con el cuerpo vacío (`: static`) y ninguna migración tiene columnas `two_factor_*`.
- Servicios: tests escritos en `tests/Feature/Services/` (33 tests): `ServiceTenantScopeTest` (scope, asignación de tenant al crear, fail closed), `ServicePolicyTest` (todas las acciones por rol y tenant), `ServiceActionsTest` (las tres Actions directo) y `ServiceComponentsTest` (acceso a la página y flujos de `Create`/`Update`/`Table`, incluido 403 para customer y para staff de otro tenant). Se verificó que protegen: al quitar `isStaff()` y la comparación de tenant de la policy fallan 5 tests. `Table::delete()` no captura `ModelNotFoundException` si el servicio ya no existe (el staff ve la página de error) y un test lo fija tal cual (`toThrow(ModelNotFoundException::class)`); si se resuelve la UX, ajustarlo. Opciones evaluadas contra la doc (pendiente decidir): `try/catch` de `ModelNotFoundException` en `delete()` con un toast de error, o el hook `exception($e, $stopPropagation)` del componente (Livewire, *Lifecycle Hooks*; su ejemplo usa `NotFoundException`, sin verificar que reciba excepciones de acciones). El 403 de `authorize()` no conviene capturarlo (avisa de un intento sin permiso); en su lugar ocultar el botón con `@can('delete', $service)` en la vista. No cubren los topes del `ServiceForm` (solo `required` y `min`) ni el form de `Update` que conserva lo escrito.
- `UserSeeder` tiene `tenant_id => 1` hardcodeado en vez de referenciar el tenant creado por `TenantSeeder` — frágil si cambia el orden de seeders o se corre más de una vez sin `migrate:fresh`.
- No hay validación de solapamiento de turnos para un mismo empleado (unique constraint solo bloquea duplicados exactos) — mismo problema en `appointments` y en `employees_schedules`.
- `EmployeeScheduleForm::rules()` no condiciona `start_time`/`end_time` a que el día esté `enabled` — pide horario válido igual para días desactivados. Hoy no rompe nada porque el default de cada día nunca es un string vacío (siempre `09:00`-`18:00`), pero es frágil: si en algún momento un día queda con un horario realmente vacío, la validación va a fallar sin necesidad.
- `Livewire\Staff\Employees\Schedule::save()` no tiene autorización granular — mismo gap que `Customers\Create::save()`, protegido solo por el middleware `staff`.
- La lógica de precargar/resetear `daysOfWeek` desde la base de datos vive en `Schedule::mount()`, no en `EmployeeScheduleForm` — en rigor es responsabilidad del Form (es quien conoce la forma interna de `daysOfWeek`), pero se pospuso a propósito por simplicidad.
- No hay tests para `EmployeeSchedule`/`Schedule`/`CreateOrUpdateEmployeeSchedule` todavía (el helper `actingAsUser()` ya permite escribirlos).
- No hay test de arquitectura (Pest `arch()`) que valide que el nombre de clase coincida con el nombre de archivo en `Livewire/Staff/**`. Pospuesto a propósito: el bug apareció dos veces durante el rename manual `Index`→`Table` (`ModalSchedule`, `Services/Table.php` con `class Index` adentro), pero componentes creados con `php artisan make:livewire` no deberían tener este problema por diseño. Revisar si vuelve a aparecer fuera de un rename manual.
- (Resuelto) `layouts/app.blade.php` apuntaba a un sidebar borrado; ver la decisión del layout compartido por rol más arriba. Pendiente verificarlo con tests (staff y customer reciben 200 en `profile.edit`) y a mano en el navegador. Sigue pendiente decidir si la ruta `dashboard` genérica del starter kit se mantiene, ya que el `LoginResponse` redirige a `staff.dashboard`/`customer.dashboard`.
- El `x-settings.layout` (menú lateral de Settings) no distingue área (`staff`/`customer`) — detalle de UX pospuesto.
- `Livewire\Settings\DeleteUserForm::deleteUser()` redirige a `/` tras borrar la cuenta (no usa el `LogoutResponse`); `App\Livewire\Actions\Logout` solo lo usa ese flujo y su `redirect('/')` se descarta. Decidir si ese redirect también va a `/login`.
- `ServiceForm` sin topes máximos: `price` (`decimal(10,2)`, techo de la base `99999999.99`) y `duration_minutes` (`unsignedInteger`) no tienen `max`, y `price` acepta más de 2 decimales (la base redondea en silencio). Valores propuestos, a decidir: `duration_minutes` `max:1440`, `price` `max:9999999.99` (moneda ARS, ver `AppServiceProvider`) y `decimal:0,2`. Poner los topes en el Form, que comparten Create y Update.
- `Livewire\Staff\Services\Update`: el form conserva lo escrito (y los errores de validación) si se cierra el modal sin guardar, porque cada fila monta su propia instancia que persiste. Pendiente resetear/recargar el form al cerrar o al abrir.
- Hacer `users.name` nullable — decisión tomada en el diseño pero todavía no aplicada al código.
- `password_reset_tokens` usa `email` como clave primaria global, pero `users.email` es único solo por tenant — riesgo de colisión entre tenants con el mismo email hasta que se resuelva la identificación de tenant por dominio.
- Middleware `EnsureUserIsStaff` y el `LoginResponse` custom de Fortify (redirect según rol) todavía no tienen test Pest.
- (Resuelto) Los tests pasaron de SQLite en memoria a Postgres con base propia; ver el gap de tests más arriba. Si se dockeriza el entorno, crear `booking_system_db_testing` en el script de inicialización del servicio de Postgres.
- Login de clientes con Google (ej. Socialite) evaluado pero no compatible todavía sin resolver antes: (1) `users.password` es `NOT NULL`, un login solo-OAuth necesita password nullable o un hash dummy; (2) implica auto-registro de `customer` sin intervención del staff, lo que matiza la decisión de "sin registro público" — falta decidir explícitamente que el self-registration aplicaría solo a `customer`, nunca a `employee`/`admin`; (3) necesita resolver el tenant en el callback de OAuth antes de crear el `User` (`tenant_id` es NOT NULL), lo cual probablemente depende de tener primero la resolución de tenant por dominio; (4) no hay forma de enlazar un `Customer` walk-in existente (sin columna `email` propia) con la futura cuenta de Google — se crearía un `Customer` duplicado sin vincular al histórico.
- `ProfileValidationRules::emailRules()` (usada por `CreateNewUser` de Fortify) hace `Rule::unique(User::class)` sin filtrar por `tenant_id` — contradice el diseño de "email único por tenant" (la constraint real en `users` es compuesta, `users_email_tenant_id_unique`). Hoy está dormido porque el registro de Fortify ya está roto por otra razón (ver decisiones más arriba). Resolverlo requiere saber el `tenant_id` en el momento de la validación, lo cual depende de tener resuelta la identificación de tenant por dominio (gap de arriba) — no tiene sentido arreglarlo antes que eso.
