# Profile Builder Website Application

Profile Builder Website Application is a full-stack Core PHP and MySQL project that helps users create a structured online profile, portfolio, and printable resume using simple form inputs. It is designed as a beginner-friendly college project and can run locally in XAMPP or WAMP.

## Project Description

This system lets a user register, log in, enter personal, academic, and professional information, choose a visual template, and publish a public profile page using a username-based link. It works like a no-code personal website generator for resumes and portfolios.

## Who Will Use This System

- Students
- Job seekers
- Freelancers
- Professionals
- Teachers
- Small business owners
- Designers
- Developers

## Why The Project Is Useful

- Builds a professional online profile without coding knowledge
- Organizes profile data into a clean portfolio/resume layout
- Gives users a shareable public link
- Supports printable resume generation for interviews and applications
- Allows profile updates anytime from a personal dashboard

## Real-World Problem Solved

Many people need a personal portfolio or resume website but do not know web development. This project solves that by providing guided forms, reusable templates, and public profile publishing in one simple system.

## Technologies Used

### Frontend

- HTML
- CSS
- JavaScript
- Bootstrap 5

### Backend

- Core PHP (PHP 8.1)

### Database

- MySQL

## Main Modules / Features

- User registration
- User login/logout
- User dashboard with profile completion progress
- Personal profile management
- Education management
- Skills management
- Projects management
- Certificates management
- Contact and social links management
- Template selection
- Profile preview
- Publish/unpublish public profile
- Public profile page by username
- Printable resume page
- Password change
- Admin dashboard
- User management with activate/deactivate
- Profile listing for admin
- Contact message management for admin

## User Workflow

1. User registers an account.
2. User logs into the system.
3. User opens the dashboard.
4. User fills in personal profile details.
5. User adds education information.
6. User adds skills.
7. User adds projects.
8. User adds certificates.
9. User adds contact and social links.
10. User selects a profile template.
11. User previews the profile page.
12. User sets profile visibility to `public` or `private`.
13. User shares the public profile link if published.
14. User opens the printable resume page and uses the print button.
15. User edits profile information anytime.

## Folder Structure

```text
profile-builder-website-application/
├── README.md
├── LICENSE
├── .gitignore
├── database.sql
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js
│   └── images/
│       └── default-avatar.png
├── uploads/
│   └── profiles/
│       └── .gitkeep
├── config/
│   └── database.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── auth.php
│   └── functions.php
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── user/
│   ├── dashboard.php
│   ├── profile.php
│   ├── education.php
│   ├── skills.php
│   ├── projects.php
│   ├── certificates.php
│   ├── contact.php
│   ├── templates.php
│   ├── preview.php
│   ├── resume.php
│   └── settings.php
├── admin/
│   ├── dashboard.php
│   ├── users.php
│   ├── profiles.php
│   └── messages.php
├── public/
│   ├── _profile_layout.php
│   └── view.php
├── index.php
├── about.php
└── contact.php
```

## Database Design Summary

The project uses eight main tables:

- `users` for login accounts, roles, and status
- `profiles` for personal profile and publishing settings
- `education` for academic records
- `skills` for skill items and levels
- `projects` for project details and links
- `certificates` for certificates and issue metadata
- `contacts` for personal contact and social links
- `contact_messages` for website inquiry messages

The `profiles`, `education`, `skills`, `projects`, `certificates`, and `contacts` tables are linked to `users` using `user_id`.

## Installation Guide

1. Install XAMPP or WAMP.
2. Copy the `profile-builder-website-application` folder into your `htdocs` directory.
3. Start Apache and MySQL from the XAMPP or WAMP control panel.
4. Open phpMyAdmin.
5. Import the `database.sql` file.
6. Open `config/database.php`.
7. Update the database name, username, or password if your local MySQL setup is different.
8. Run the project in your browser:

```text
http://localhost/profile-builder-website-application/
```

## Demo Credentials

### Admin

- Email: `admin@example.com`
- Password: `Admin@123`

### Student User

- Email: `student@example.com`
- Password: `User@123`

### Freelancer User

- Email: `freelancer@example.com`
- Password: `User@123`

### Professional User

- Email: `professional@example.com`
- Password: `User@123`

## Public Profile URL Example

```text
/public/view.php?u=johnstudent
```

Full local example:

```text
http://localhost/profile-builder-website-application/public/view.php?u=johnstudent
```

## Screens / Pages Included

- Home page
- About page
- Contact page
- Register page
- Login page
- User dashboard
- Personal profile form
- Education management page
- Skills management page
- Projects management page
- Certificates management page
- Contact/social links page
- Template selection page
- Profile preview page
- Printable resume page
- Password settings page
- Admin dashboard
- Admin users page
- Admin profiles page
- Admin messages page
- Public profile page

## Security Features

- Session-based authentication
- Role-based access control
- `password_hash()` for secure password storage
- `password_verify()` for login validation
- PDO prepared statements for database queries
- Output escaping using `htmlspecialchars()`
- Form input validation
- Image-only profile upload restriction
- Upload size limit
- Safe uploaded filename generation
- Route protection for user and admin pages
- Private profiles hidden from public access

## Future Improvements

- Custom domain support
- PDF resume export
- Drag-and-drop profile builder
- More templates
- QR code for public profile
- Email verification
- Password reset
- Analytics for profile views

## License

This project is released under the MIT License. See the `LICENSE` file for details.
