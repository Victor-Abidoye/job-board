# Laravel Learning Curriculum via Job Board Project

## Context

You're a React/TypeScript frontend engineer learning Laravel by building a real job board on top of an existing starter kit (Laravel 13 + Inertia.js + React 19 + Tailwind CSS 4). The auth scaffold is already done — registration, login, 2FA, passkeys. Everything else is yours to build.

**Approach:** Each module is tied to a real job board feature. You write the code; I point you to what to read and bridge concepts to what you already know (React, TypeScript, Prisma-like thinking).

**Concept bridges to keep in mind:**
- Eloquent ORM ≈ Prisma/TypeORM
- Artisan CLI ≈ `npx create-...` / `prisma migrate`
- Middleware ≈ Express middleware
- Service Container ≈ dependency injection
- Inertia pages ≈ React route-level components that get props from the server
- Blade = not used here — Inertia replaces it for full pages
- `.env` = same as you know

---

## Module 0 — Orient Yourself (Before Writing Anything)

**Goal:** Understand what already exists before touching it.

**Read these files** in the project:
- `routes/web.php` + `routes/settings.php` — how routes are structured
- `app/Http/Controllers/Settings/ProfileController.php` — your template for all future controllers
- `app/Http/Middleware/HandleInertiaRequests.php` — how data gets from Laravel into React props
- `resources/js/pages/settings/profile.tsx` — how a settings page consumes those props

**Topics to look up:**
- Laravel directory structure (what lives in `app/`, `config/`, `database/`, `resources/`, `routes/`)
- `php artisan list` — run it, read through what's available
- How Inertia.js works: [inertiajs.com/how-it-works](https://inertiajs.com/how-it-works)

**Key insight:** `Inertia::render('PageName', ['key' => $value])` in a controller = returning `{ props }` to a React component. That's the whole bridge.

---

## Module 1 — Database Design & Migrations

**Job board feature:** Design and create the core schema.

**What to build:**
1. Migration: `create_companies_table` (id, user_id, name, logo, website, description, location)
2. Migration: `create_job_categories_table` (id, name, slug)
3. Migration: `create_job_listings_table` (id, company_id, category_id, title, description, type [full-time/part-time/contract], location, salary_min, salary_max, is_remote, status [draft/published/closed], expires_at)
4. Migration: `create_job_applications_table` (id, job_listing_id, user_id, resume_path, cover_letter, status [pending/reviewed/rejected/accepted])

**Artisan commands to learn:**
```bash
php artisan make:migration create_companies_table
php artisan migrate
php artisan migrate:rollback
php artisan migrate:status
```

**Topics to look up:**
- Laravel Migrations docs — column types, foreign key constraints, indexes
- `foreignId()->constrained()->cascadeOnDelete()` pattern

**Bridge:** Migrations = versioned SQL schema files, but with a PHP DSL. Like Prisma schema but imperative.

---

## Module 2 — Eloquent Models & Relationships

**Job board feature:** Wire models so you can query `$job->company`, `$company->jobs`, `$user->applications`.

**What to build:**
1. `app/Models/Company.php` — belongsTo User, hasMany JobListings
2. `app/Models/JobCategory.php` — hasMany JobListings
3. `app/Models/JobListing.php` — belongsTo Company, belongsTo Category, hasMany Applications
4. `app/Models/JobApplication.php` — belongsTo JobListing, belongsTo User
5. Add relationships to existing `User` model

**Artisan commands to learn:**
```bash
php artisan make:model Company
php artisan make:model JobListing -m   # -m creates migration at the same time
```

**Topics to look up:**
- Eloquent relationships: `hasMany`, `belongsTo`, `belongsToMany`
- `$fillable` vs `$guarded`
- Eloquent attribute casting (`casts` array — e.g. enums, booleans, dates)
- Eloquent scopes (`scopePublished`, `scopeRemote`) — like computed filters

**Bridge:** Eloquent model = a class that represents a DB table row, with methods for querying. Similar to a Prisma model + repository combined.

---

## Module 3 — Factories & Seeders

**Job board feature:** Populate the database with realistic fake data so you can build UI without manual data entry.

**What to build:**
1. `CompanyFactory`, `JobCategoryFactory`, `JobListingFactory`, `JobApplicationFactory`
2. `DatabaseSeeder` — seed 5 companies, 10 categories, 50 job listings, 20 applications

**Artisan commands to learn:**
```bash
php artisan make:factory JobListingFactory
php artisan db:seed
php artisan migrate:fresh --seed   # nuke + remigrate + reseed (your best friend in dev)
```

**Topics to look up:**
- Laravel Model Factories (Faker integration)
- `definition()` method and `state()` for variants (e.g. a "remote job" state)
- DatabaseSeeder and calling factories in it

**Bridge:** Factories = Jest fixture builders / `faker.js` but integrated with your models. `migrate:fresh --seed` = reset your dev database in one command.

---

## Module 4 — Controllers & Resource Routing

**Job board feature:** Employers can create, edit, and delete their job listings.

**What to build:**
1. `php artisan make:controller JobListingController --resource`
2. Implement `index`, `show`, `create`, `store`, `edit`, `update`, `destroy`
3. Register `Route::resource('jobs', JobListingController::class)` in `web.php`
4. Form Request classes for validation: `StoreJobListingRequest`, `UpdateJobListingRequest`

**Artisan commands to learn:**
```bash
php artisan make:controller JobListingController --resource
php artisan make:request StoreJobListingRequest
php artisan route:list   # see all registered routes — run this often
```

**Topics to look up:**
- Resource controllers and what the 7 methods map to (GET /jobs, POST /jobs, GET /jobs/{job}, etc.)
- Route model binding — how `{job}` in the URL auto-fetches the model
- Form Request classes — where validation logic lives (not in controllers)
- `$request->validated()` — only get fields that passed validation

**Bridge:** Form Requests = Zod schemas but server-side. Route model binding = Next.js `params` but the ORM fetch is automatic.

---

## Module 5 — Inertia Deep Dive (Frontend Integration)

**Job board feature:** Job listing browse page + single job detail page.

**What to build:**
1. `resources/js/pages/jobs/index.tsx` — list all published jobs with pagination
2. `resources/js/pages/jobs/show.tsx` — single job detail
3. `resources/js/pages/dashboard/employer.tsx` — employer's jobs management view
4. Wire `JobListingController@index` to return jobs with `Inertia::render()`

**Topics to look up:**
- Inertia pagination — `$jobs->paginate(15)` on the backend, `<Link>` component on frontend
- `usePage()` hook — access shared props (like logged-in user) from any component
- Inertia forms — `useForm()` hook for create/edit forms (replaces axios + useState)
- Inertia `router.visit()` for programmatic navigation
- Wayfinder (already in this project) — generates typed route helpers so you write `route.jobs.show(job.id)` in TypeScript

**Bridge:** `Inertia::render('Jobs/Index', ['jobs' => $jobs])` = a server component returning props to `pages/jobs/index.tsx`. `useForm()` = React Hook Form but backed by Inertia's request lifecycle.

---

## Module 6 — Authorization (Gates & Policies)

**Job board feature:** Only the employer who posted a job can edit or delete it. Job seekers cannot access employer routes.

**What to build:**
1. `php artisan make:policy JobListingPolicy --model=JobListing`
2. Implement `update`, `delete`, `create` methods
3. Add `$this->authorize('update', $job)` in controller
4. Add a basic role system to User (enum or boolean `is_employer`)

**Topics to look up:**
- Laravel Policies — the recommended way to handle authorization
- `$this->authorize()` in controllers
- `@can` directive (Blade) — for Inertia, pass permissions as props instead
- Gates vs Policies — use Policies for model-specific rules, Gates for app-level checks

**Bridge:** Policies = route guards / middleware in React Router but they live on the server and are tied to model instances, not routes.

---

## Module 7 — File Storage

**Job board feature:** Job seekers upload a resume (PDF) when applying. Employers upload a company logo.

**What to build:**
1. Resume upload on job application form
2. Company logo upload on company profile
3. Store files in `storage/app/private/resumes` and `storage/app/public/logos`

**Topics to look up:**
- Laravel Filesystem / Storage facade
- `php artisan storage:link` — why you need it (makes public files accessible via URL)
- `$request->file('resume')->store('resumes', 'private')` pattern
- Generating temporary signed URLs for private files (`Storage::temporaryUrl()`)
- File validation rules: `mimes:pdf`, `max:2048`

**Bridge:** Storage facade = S3 SDK but abstracted — same code works for local disk, S3, or R2 just by changing config.

---

## Module 8 — Queues & Background Jobs

**Job board feature:** When someone applies to a job, send a confirmation email to the applicant and a notification email to the employer — without blocking the HTTP response.

**What to build:**
1. `php artisan make:job SendApplicationConfirmation`
2. Dispatch the job from `JobApplicationController@store`
3. Run the queue worker locally: `php artisan queue:work`

**Topics to look up:**
- Laravel Queues — why async matters (don't make users wait for email sends)
- `dispatch(new SendApplicationConfirmation($application))` pattern
- Queue connection in `.env` — already set to `database` in this project
- `php artisan queue:work` vs `queue:listen`
- Failed jobs: `php artisan queue:failed`

**Bridge:** Queue jobs = Web Workers or `setTimeout` callbacks, but persistent, retryable, and server-side. The `database` queue driver stores pending jobs in a table — you can see them with `php artisan queue:monitor`.

---

## Module 9 — Mail & Notifications

**Job board feature:** Styled application confirmation email and employer alert email.

**What to build:**
1. `php artisan make:mail ApplicationConfirmationMail`
2. `php artisan make:notification NewApplicationReceived`
3. Send from inside the queue job built in Module 8

**Topics to look up:**
- Mailables — PHP classes that represent an email (subject, view, attachments)
- Laravel Notifications — higher-level than Mailables; one notification can go to email, database, Slack
- Database notifications — stored in `notifications` table, readable in UI
- Mail preview in browser during dev: return a Mailable from a route temporarily

**Bridge:** A Mailable = a React Email component (JSX → HTML email), but in PHP. Database notifications = a simple notification inbox (like GitHub's).

---

## Module 10 — Search & Advanced Queries

**Job board feature:** Search jobs by keyword, filter by category, location, remote, salary range.

**What to build:**
1. Search form on the jobs index page
2. `JobListing::query()` builder in controller, applying filters conditionally
3. Eloquent scopes on `JobListing`: `scopePublished()`, `scopeRemote()`, `scopeInCategory()`

**Topics to look up:**
- `when()` method on query builder — conditional query clauses without if-else mess
- Eloquent local scopes
- `LIKE` queries vs full-text search
- Eager loading (`with()`) — critical for avoiding N+1 queries (load `company` with each job in one query)
- Laravel Telescope (already may be available) — see query counts in dev

**Bridge:** `when($request->category, fn($q) => $q->where(...))` = conditional array spread in a filter function. N+1 problem = the same as fetching data in a loop in React without batching.

---

## Module 11 — Testing with Pest

**Job board feature:** Cover the core flows — posting a job, applying, authorization checks.

**What to build:**
1. Feature test: employer can create a job listing
2. Feature test: job seeker can apply to a job
3. Feature test: job seeker cannot edit someone else's job listing (403)
4. Feature test: unauthenticated user cannot access dashboard

**Commands:**
```bash
php artisan test                          # run all
php artisan test --filter JobListingTest  # run one file
php artisan test tests/Feature/JobListingTest.php
```

**Topics to look up:**
- Pest syntax: `it('can create a job', function() { ... })`
- `actingAs($user)` — authenticate as a user in a test
- `assertInertia()` — assert what props an Inertia page receives
- Database assertions: `assertDatabaseHas()`, `assertDatabaseMissing()`
- Using factories in tests: `User::factory()->create()`

**Bridge:** Pest tests = Vitest/Jest tests but for HTTP requests. `actingAs()` = mocking an auth context. `assertDatabaseHas()` = checking state after a mutation, like checking a store after a dispatch.

---

## Build Order Summary

| # | Feature to Build | Core Concept |
|---|---|---|
| 0 | Read existing code | Orientation |
| 1 | Schema design | Migrations |
| 2 | Model relationships | Eloquent |
| 3 | Fake data | Factories & Seeders |
| 4 | Job CRUD (backend) | Controllers, Validation |
| 5 | Job browsing (frontend) | Inertia, Pagination |
| 6 | Role-based access | Policies |
| 7 | Resume & logo upload | Storage |
| 8 | Background email send | Queues |
| 9 | Email & notifications | Mail, Notifications |
| 10 | Job search & filters | Query Builder, Scopes |
| 11 | Tests | Pest |

---

## Working Style

- Propose a plan before writing code
- Explain trade-offs when there are multiple approaches
- Point to the Laravel docs section to read — you then write the code
- Frame Laravel concepts against what you know from React/TS
- When you're stuck, identify the specific topic to look up, not just hand you the solution
