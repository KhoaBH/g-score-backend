# G-Scores

A web application for analyzing and looking up Vietnam's 2024 National High School Graduation Exam (THPT) results. Built with Laravel and PostgreSQL.

## Overview

G-Scores ingests the raw 2024 THPT exam score dataset (~1 million records) into a PostgreSQL database and provides three core features:

- **Score Lookup** — search for an individual student's results by registration number (SBD)
- **Score Distribution Report** — view statistics (count, average, median, max, min, pass rate) and a bar chart of score distribution across four levels (`<4`, `4-6`, `6-8`, `>=8`) for each subject
- **Top 10 Ranking** — view the top 10 students by combined score for admission groups A, A01, B, and D

## Tech Stack

- **Backend:** Laravel (PHP)
- **Database:** PostgreSQL
- **Frontend:** Blade templates, Tailwind CSS, Chart.js
- **Local environment:** Docker Compose (PostgreSQL)

## Requirements

- PHP >= 8.1
- Composer
- Docker & Docker Compose

## Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/KhoaBH/g-score-backend.git
cd g-score-backend
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure environment

```bash
cp .env.example .env      # Windows: copy .env.example .env
php artisan key:generate
```

The default `.env` is preconfigured to connect to the local Docker PostgreSQL instance:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=g_score
DB_USERNAME=postgres
DB_PASSWORD=password
```

### 4. Start the database

```bash
docker compose up -d
```

### 5. Run migrations and seed the database

```bash
php artisan migrate:fresh
php artisan db:seed --class=ScoreSeeder
```

> The seeder reads `database/seeders/diem_thi_thpt_2024.csv` (~1 million rows) and imports it in chunks. If you encounter a memory limit error, run:
> ```bash
> php -d memory_limit=1G artisan db:seed --class=ScoreSeeder
> ```

### 6. Run the application

```bash
php artisan serve
```

Visit **http://localhost:8000**

## Project Structure

```
app/
├── Http/Controllers/
│   └── DashboardController.php   # Page + API endpoints
├── Services/
│   └── ScoreService.php          # Score aggregation & statistics logic
database/
├── migrations/
│   └── create_scores_table.php
├── seeders/
│   ├── ScoreSeeder.php
│   └── diem_thi_thpt_2024.csv
resources/views/
├── layouts/app.blade.php
├── dashboard.blade.php
└── partials/
    ├── search.blade.php          # Score lookup
    ├── chart.blade.php             # Score distribution report
    └── ranking.blade.php          # Top 10 ranking
```

## Database Schema

Table `scores`:

| Column | Type | Description |
|---|---|---|
| `sbd` | string (PK) | Registration number |
| `toan` | decimal | Math |
| `ngu_van` | decimal | Literature |
| `ngoai_ngu` | decimal | Foreign language |
| `vat_li` | decimal | Physics |
| `hoa_hoc` | decimal | Chemistry |
| `sinh_hoc` | decimal | Biology |
| `lich_su` | decimal | History |
| `dia_li` | decimal | Geography |
| `gdcd` | decimal | Civic education |
| `ma_ngoai_ngu` | string | Foreign language code |

## Features

### Score Lookup
Enter a registration number to view all subject scores for that candidate.

### Score Distribution Report
Select a subject to view:
- Total exam entries, average, median, highest, and lowest scores
- Pass rate (≥ 5.0)
- A bar chart showing the number of students in 4 score bands: `<4`, `4-6`, `6-8`, `>=8`

### Top 10 Ranking
Select an admission group to view the top 10 candidates by combined score:

| Group | Subjects |
|---|---|
| A | Math, Physics, Chemistry |
| A01 | Math, Physics, English |
| B | Math, Chemistry, Biology |
| D | Math, Literature, English |

## Deployment Notes

When deploying to a managed PostgreSQL provider that requires SNI-based endpoint routing (e.g. Neon), `AppServiceProvider` automatically appends the required `endpoint` option to the connection DSN when `DB_HOST` contains `neon.tech`. No additional configuration is needed for standard PostgreSQL hosts.