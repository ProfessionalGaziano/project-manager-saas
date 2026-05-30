# 📋 Project Manager SaaS

A full-featured, multi-tenant SaaS application for managing clients, projects, tasks, and invoices — built with Laravel, Backpack for Laravel, and Stripe.

> **Live Demo Credentials**
> | Role | Email | Password |
> |------|-------|----------|
> | Admin | admin@demo.com | password |
> | Manager | manager@demo.com | password |
> | Employee | employee@demo.com | password |
> | Client | client@demo.com | password |

---

## 🎯 Project Overview

**Project Manager SaaS** was born from a real-world need: agencies and digital studios need a centralized platform to manage their entire workflow — from the moment a client submits a request, all the way to project completion and invoicing.

### The Problem It Solves

Most project management tools are either too complex or too generic. This application is built specifically for **digital agencies** and **software studios** that need to:

- Receive and evaluate client project requests
- Assign projects to managers and tasks to employees
- Keep clients informed about their project status
- Handle subscriptions and billing in one place

### The Business Flow

```
Client submits a request
    → Admin receives it on their dashboard
        → Admin accepts → project created automatically
            → Admin assigns project to a Manager
                → Manager creates tasks and assigns them to Employees
                    → Employees update task status
                        → When all tasks are done → project auto-completes
                            → Client receives completion email
```

---

## 🚀 Tech Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| **Laravel** | 12.x | PHP backend framework |
| **Backpack for Laravel** | 7.x | Admin panel with CRUD generation |
| **MySQL** | 8+ | Relational database |
| **PHP** | 8.2+ | Backend language |
| **Laravel Sanctum** | 4.x | API token authentication |
| **Laravel Cashier** | 15.x | Stripe subscription management |
| **Spatie Laravel Permission** | 6.x | Role-based access control |
| **Laravel Breeze** | 2.x | Frontend authentication scaffolding |
| **Chart.js** | 4.x | Dashboard data visualizations |
| **Tailwind CSS** | 3.x | Frontend utility-first CSS |
| **Node.js + Vite** | 18+ | Asset compilation |

---

## ✨ Features

### 🔐 Authentication & Authorization

**Multi-role system** powered by [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission):

| Role | Capabilities |
|------|-------------|
| `admin` | Full access — manages teams, projects, invoices, subscriptions, and incoming requests |
| `manager` | Manages assigned projects, creates and assigns tasks to employees |
| `employee` | Views and updates only their assigned tasks |
| `client` | Submits project requests, views their project status (read-only) |

Each role sees a **different dashboard** with role-specific data and menu items. Unauthorized access attempts return a `403` response.

---

### 🏢 Multi-Tenancy

Every piece of data is scoped to a **Team** (tenant). This means:

- Users belonging to Team A can never see data from Team B
- Projects, tasks, invoices, and requests are all filtered by `team_id`
- Tasks are filtered through their parent project's `team_id`

This is implemented via a custom `TeamMiddleware` that stores the active team in the session, and `CRUD::addClause()` filters in every Backpack controller.

---

### 📬 Project Request System

Clients can submit project requests including:
- A detailed description of what they need
- A desired deadline
- An optional budget

Admins receive all pending requests directly on their dashboard, with:
- **Loyalty badges** showing how many requests the client has submitted before (New Client → Active → Loyal → VIP)
- One-click **Accept** (automatically creates a draft project) or **Reject** (with a mandatory reason sent to the client via email)

---

### 📧 Email Notification System

Built with **Laravel Mail** and tested with **Mailtrap**. Emails are sent automatically at every key lifecycle event:

| Event | Recipient |
|-------|-----------|
| Project request accepted | Client |
| Project request rejected (with reason) | Client |
| Project assigned | Manager |
| Task assigned | Employee |
| Project completed | Client |
| Team invitation | Invited user |
| Email verification | New user |

---

### 🤝 Team Invitation System

Admins can invite users to their team via email. The system:
1. Generates a **unique, expiring token** (7 days)
2. Sends an invitation email with a registration link
3. When the user registers via the link, they are automatically assigned the correct role and added to the team
4. The invitation is marked as accepted

---

### 💳 Stripe Subscriptions

Powered by [Laravel Cashier](https://laravel.com/docs/billing). Two plans are available:

| Feature | Free | Pro |
|---------|------|-----|
| Projects | Max 3 | Unlimited |
| Tasks | Max 10 | Unlimited |
| Team members | Max 3 | Unlimited |
| Invoices | ❌ | ✅ |
| Priority support | ❌ | ✅ |

Plan limits are enforced at the controller level — users on the Free plan cannot create resources beyond the limits, and see informative warnings instead.

---

### 📊 Role-Based Dashboards

Each role gets a tailored dashboard with real-time data:

**Admin Dashboard:**
- Total projects by status (doughnut chart)
- Team members by role (doughnut chart)
- Revenue collected vs. pending (stats cards)
- Incoming client requests with accept/reject actions

**Manager Dashboard:**
- Tasks by status (doughnut chart)
- Project progress bars (% of tasks completed per project)
- Upcoming deadlines list

**Employee Dashboard:**
- Personal task progress (doughnut chart)
- Tasks completed this month
- Upcoming deadlines

**Client Dashboard:**
- All submitted requests with their current status
- Quick access to submit a new request

---

### 🔁 Observer Pattern — Auto-Completion

When an employee marks a task as `done`, a **Laravel Observer** (`TaskObserver`) automatically checks if all tasks in the parent project are done. If they are, the project status is updated to `completed` and a completion email is sent to the client.

This is a clean implementation of the **Observer design pattern** — the model does not know about the side effects, keeping the code decoupled and maintainable.

```php
// TaskObserver.php
public function updated(Task $task): void
{
    if ($task->isDirty('status') && $task->status === 'done') {
        $allDone = $project->tasks()->where('status', '!=', 'done')->doesntExist();
        if ($allDone) {
            $project->update(['status' => 'completed']);
            // Send completion email to client...
        }
    }
}
```

Similarly, a `ProjectObserver` sends an email to the manager when a project is assigned to them, and an `InvoiceObserver` auto-generates sequential invoice numbers (`INV-0001`, `INV-0002`...).

---

### 🌐 REST API

A full REST API secured with **Laravel Sanctum** token authentication:

#### Authentication
```http
POST /api/login
Content-Type: application/json

{ "email": "admin@demo.com", "password": "password" }
```

Returns a bearer token to use in subsequent requests.

#### Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `POST` | `/api/login` | Get API token | No |
| `POST` | `/api/logout` | Revoke token | Yes |
| `GET` | `/api/projects` | List projects (scoped by role) | Yes |
| `GET` | `/api/projects/{id}` | Get project details with tasks | Yes |
| `GET` | `/api/projects/{id}/tasks` | List tasks for a project | Yes |
| `PATCH` | `/api/tasks/{id}` | Update task status | Yes |
| `GET` | `/api/project-requests` | List requests (admin: all pending; client: own) | Yes |
| `POST` | `/api/project-requests` | Submit a new request (clients only) | Yes |

#### Example API call
```bash
# Get API token
TOKEN=$(curl -s -X POST http://your-domain/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@demo.com","password":"password"}' \
  | python3 -c "import sys,json; print(json.load(sys.stdin)['token'])")

# List projects
curl -X GET http://your-domain/api/projects \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

---

### 🧪 Automated Tests

**35 tests, 61 assertions** — all passing. Tests cover:

- Authentication flows (login, logout, password reset, email verification)
- Admin can access the Backpack panel
- Guests are redirected from protected routes
- Clients can submit project requests
- Admins can accept and reject requests
- Observer-driven project auto-completion logic
- Free plan enforces project limits
- Subscription and invitation pages are accessible
- Role-based access control (clients blocked from invoices)

Tests use a dedicated MySQL test database (`project_manager_saas_test`) to avoid conflicts with `MODIFY COLUMN` syntax not supported by SQLite.

---

## 🗄️ Database Schema

### Tables

| Table | Description |
|-------|-------------|
| `users` | All system users |
| `teams` | Agency/company tenants |
| `team_user` | Pivot: users ↔ teams with role |
| `projects` | Projects belonging to a team |
| `tasks` | Tasks belonging to a project |
| `invoices` | Invoices belonging to a team |
| `project_requests` | Client requests for new projects |
| `team_invitations` | Pending team invitations with tokens |
| `roles` / `permissions` | Spatie RBAC tables |
| `subscriptions` | Stripe subscription data (via Cashier) |
| `personal_access_tokens` | Sanctum API tokens |

### Key Relationships

```
User         → belongs to many Teams (via team_user pivot)
Team         → has many Projects, Invoices, TeamInvitations
Project      → belongs to Team, has many Tasks
Task         → belongs to Project, assigned to User
Invoice      → belongs to Team and Project
ProjectRequest → belongs to User (client), optionally converted to Project
```

---

## ⚙️ Local Installation

### Prerequisites

- PHP 8.2+
- Composer 2+
- Node.js 18+
- MySQL 8+

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/your-username/project-manager-saas.git
cd project-manager-saas

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure your database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_manager_saas
DB_USERNAME=root
DB_PASSWORD=your_password

# 7. Configure Stripe keys in .env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...

# 8. Configure Mail (Mailtrap recommended for local)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password

# 9. Run migrations
php artisan migrate

# 10. Create the storage symlink
php artisan storage:link

# 11. Seed the database with roles and demo data
php artisan db:seed

# 12. Compile assets
npm run build

# 13. Start the server
php artisan serve
```

### Create your admin user

```bash
php artisan backpack:user
```

Access the admin panel at: `http://localhost:8000/admin`

---

## 🌱 Database Seeding

The project includes two seeders:

**`RolesAndPermissionsSeeder`** — Creates all roles (`admin`, `manager`, `employee`, `client`) and their associated permissions using Spatie Laravel Permission.

**`DemoSeeder`** — Populates the database with realistic demo data:
- 6 users (1 admin, 1 manager, 2 employees, 2 clients)
- 1 team (Bianchi Digital Agency) with all members
- 4 projects in various states (active, completed, draft)
- 17 tasks assigned to employees
- 4 invoices (paid, sent, draft)
- 4 project requests (accepted, pending)

Run seeders:
```bash
# Fresh install with all seeders
php artisan migrate:fresh --seed

# Or run individually
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=DemoSeeder
```

---

## 🧩 Key Packages & Why We Chose Them

### Backpack for Laravel
Used by companies like Volkswagen, PwC, and McDonald's, Backpack generates fully functional CRUD admin panels in minutes. Instead of building admin interfaces from scratch, we focused on business logic while Backpack handled the scaffolding. Version 7 uses the Tabler UI theme and the Basset asset system.

### Spatie Laravel Permission
The industry standard for RBAC in Laravel. It provides `roles`, `permissions`, and `model_has_roles` tables out of the box, and exposes clean helper methods like `hasRole()`, `hasAnyRole()`, and `givePermissionTo()`. We use it to protect every CRUD controller and API endpoint.

### Laravel Cashier
Stripe's official Laravel integration. It handles the complexity of subscription management — checkout sessions, webhooks, plan upgrades/downgrades — with a clean, expressive API. We attached it to the `Team` model instead of `User`, since subscriptions are team-level in this application.

### Laravel Sanctum
Lightweight API token authentication. Each user can generate multiple tokens (one per device/client), and tokens can be revoked individually. Perfect for SaaS APIs where you want simple, stateless authentication without the overhead of OAuth.

### Laravel Observers
Used to implement side effects in response to model events without polluting models or controllers:
- `TaskObserver` — triggers project auto-completion and employee notification emails
- `ProjectObserver` — sends manager assignment emails
- `InvoiceObserver` — auto-generates sequential invoice numbers

---

## 🗺️ Roadmap

- [x] Phase 1 — Project Setup (Laravel, Backpack, MySQL, GitHub)
- [x] Phase 2 — Database Schema & Models
- [x] Phase 3 — Backpack CRUD (Teams, Projects, Tasks, Invoices)
- [x] Phase 4 — Roles & Permissions (Spatie)
- [x] Phase 5 — Multi-tenancy
- [x] Phase 6 — Stripe Subscriptions (Laravel Cashier)
- [x] Phase 7 — Team Invitation System
- [x] Phase 8 — Email Notifications
- [x] Phase 9 — Project Request Flow
- [x] Phase 10 — Role-Based Dashboards with Charts
- [x] Phase 11 — Plan Limits Enforcement
- [x] Phase 12 — Demo Seeder
- [x] Phase 13 — Automated Tests (35 passing)
- [x] Phase 14 — REST API with Sanctum
- [x] Phase 15 — Production Deploy (Railway)

---

## 👨‍💻 Author

Developed by **[Francesco Gaziano]**
- GitHub: [@imFrazziano](https://github.com/ProfessionalGaziano)
- LinkedIn: [Francesco Gaziano](https://www.linkedin.com/in/francesco-gaziano-06299b266/)
- Email: hlevel648@gmail.com

---

## 📄 License

This project is open-sourced under the [MIT License](LICENSE).
