# Database Schema

Entity relationship diagram for the job board. Open in VS Code with the "Markdown Preview Mermaid Support" extension, or view on GitHub — it renders automatically.

```mermaid
erDiagram
    users {
        bigint id PK
        string name
        string email
        string password
        timestamp email_verified_at
        timestamps created_at
    }

    companies {
        bigint id PK
        bigint user_id FK
        string name
        string logo "nullable"
        string website "nullable"
        text description "nullable"
        string location
        timestamps created_at
    }

    job_categories {
        bigint id PK
        string name
        string slug
        timestamps created_at
    }

    job_listings {
        bigint id PK
        bigint company_id FK
        bigint category_id FK
        string title
        text description
        enum type "full-time | part-time | contract"
        string location
        integer salary_min "nullable"
        integer salary_max "nullable"
        boolean is_remote
        enum status "draft | published | closed"
        timestamp expires_at "nullable"
        timestamps created_at
    }

    job_applications {
        bigint id PK
        bigint job_listing_id FK
        bigint user_id FK
        string resume_path "nullable"
        text cover_letter "nullable"
        enum status "pending | reviewed | rejected | accepted"
        timestamps created_at
    }

    users ||--o| companies : "owns (as employer)"
    users ||--o{ job_applications : "submits (as job seeker)"
    companies ||--o{ job_listings : "posts"
    job_categories ||--o{ job_listings : "categorises"
    job_listings ||--o{ job_applications : "receives"
```

---

## Relationships in plain English

| Relationship | Meaning |
|---|---|
| User → Company | An employer creates one company profile |
| Company → JobListings | A company posts many job listings |
| JobCategory → JobListings | A category groups many job listings |
| JobListing → JobApplications | A job listing receives many applications |
| User → JobApplications | A job seeker submits many applications |

## Two roles, one User table

A `User` can be either a **job seeker** or an **employer** (or both). We'll add an `is_employer` boolean to the `users` table in Module 6 when we get to authorization. For now, the distinction is:

- **Employer** — has a `Company`, posts `JobListings`
- **Job seeker** — submits `JobApplications`

## What we are NOT building (yet)

- Tags / skills on job listings
- Saved/bookmarked jobs
- Messaging between employer and applicant
- Admin panel
