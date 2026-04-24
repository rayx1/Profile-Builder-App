CREATE DATABASE IF NOT EXISTS profile_builder_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE profile_builder_db;

DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS contacts;
DROP TABLE IF EXISTS certificates;
DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS skills;
DROP TABLE IF EXISTS education;
DROP TABLE IF EXISTS profiles;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(120) NOT NULL,
    headline VARCHAR(150) NOT NULL,
    bio TEXT NULL,
    date_of_birth DATE NULL,
    profile_photo VARCHAR(255) NULL,
    template_name VARCHAR(50) NOT NULL DEFAULT 'classic',
    privacy ENUM('public', 'private') NOT NULL DEFAULT 'public',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_profiles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE education (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    degree VARCHAR(150) NOT NULL,
    institution VARCHAR(150) NOT NULL,
    start_year VARCHAR(10) NOT NULL,
    end_year VARCHAR(10) NULL,
    description TEXT NULL,
    CONSTRAINT fk_education_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    skill_level ENUM('Beginner', 'Intermediate', 'Advanced', 'Expert') NOT NULL DEFAULT 'Beginner',
    CONSTRAINT fk_skills_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    project_link VARCHAR(255) NULL,
    github_link VARCHAR(255) NULL,
    technologies VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_projects_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    organization VARCHAR(150) NOT NULL,
    issue_date DATE NULL,
    certificate_link VARCHAR(255) NULL,
    CONSTRAINT fk_certificates_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    email VARCHAR(120) NULL,
    phone VARCHAR(30) NULL,
    address VARCHAR(255) NULL,
    website VARCHAR(255) NULL,
    linkedin VARCHAR(255) NULL,
    github VARCHAR(255) NULL,
    twitter VARCHAR(255) NULL,
    instagram VARCHAR(255) NULL,
    CONSTRAINT fk_contacts_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (id, name, email, password, role, status, created_at) VALUES
(1, 'System Admin', 'admin@example.com', '$2y$10$v.B9GjygG5Y0MjN4InvHo.Wh.BBFy4rNa7YA2gMbdPYoF68cHLI2u', 'admin', 'active', '2026-01-05 09:00:00'),
(2, 'John Student', 'student@example.com', '$2y$10$9gpuEQT8t9BIITF8R.Pgl.cF0w0ksYsjjffvLc02G5yuaECy0zhdq', 'user', 'active', '2026-01-08 10:00:00'),
(3, 'Sara Freelancer', 'freelancer@example.com', '$2y$10$9gpuEQT8t9BIITF8R.Pgl.cF0w0ksYsjjffvLc02G5yuaECy0zhdq', 'user', 'active', '2026-01-12 11:15:00'),
(4, 'David Professional', 'professional@example.com', '$2y$10$9gpuEQT8t9BIITF8R.Pgl.cF0w0ksYsjjffvLc02G5yuaECy0zhdq', 'user', 'active', '2026-01-15 12:30:00');

INSERT INTO profiles (id, user_id, username, full_name, headline, bio, date_of_birth, profile_photo, template_name, privacy, created_at, updated_at) VALUES
(1, 2, 'johnstudent', 'John Student', 'BCA Student and Frontend Learner', 'Focused on web development, academic projects, and internship-ready portfolio building.', '2004-06-12', NULL, 'classic', 'public', '2026-01-08 10:30:00', '2026-04-10 09:00:00'),
(2, 3, 'sarafreelancer', 'Sara Freelancer', 'Freelance Designer and Social Media Manager', 'Helping startups build online identity through branding, landing pages, and content strategy.', '1998-03-18', NULL, 'modern', 'public', '2026-01-12 11:45:00', '2026-04-11 14:30:00'),
(3, 4, 'davidpro', 'David Professional', 'Senior Operations Manager', 'Experienced professional with expertise in project leadership, business operations, and team coordination.', '1991-11-27', NULL, 'minimal', 'private', '2026-01-15 13:00:00', '2026-04-12 16:00:00');

INSERT INTO education (user_id, degree, institution, start_year, end_year, description) VALUES
(2, 'Bachelor of Computer Applications', 'City College of Technology', '2022', '2025', 'Studied programming fundamentals, web development, databases, and software engineering.'),
(2, 'Higher Secondary', 'National Senior Secondary School', '2020', '2022', 'Focused on computer science and mathematics.'),
(3, 'B.A. in Multimedia Design', 'Creative Arts Institute', '2016', '2019', 'Built strong design, branding, and content production skills.'),
(4, 'MBA in Operations Management', 'Metro Business School', '2013', '2015', 'Specialized in operations, analytics, and cross-functional leadership.');

INSERT INTO skills (user_id, skill_name, skill_level) VALUES
(2, 'HTML', 'Advanced'),
(2, 'CSS', 'Advanced'),
(2, 'JavaScript', 'Intermediate'),
(2, 'MySQL', 'Intermediate'),
(3, 'Brand Design', 'Expert'),
(3, 'Bootstrap', 'Advanced'),
(3, 'Client Communication', 'Expert'),
(3, 'WordPress', 'Intermediate'),
(4, 'Project Management', 'Expert'),
(4, 'Business Reporting', 'Advanced'),
(4, 'Team Leadership', 'Expert'),
(4, 'Process Improvement', 'Advanced');

INSERT INTO projects (user_id, title, description, project_link, github_link, technologies, created_at) VALUES
(2, 'Student Portfolio Website', 'A responsive personal portfolio website created to showcase projects and internship experience.', 'https://example.com/student-portfolio', 'https://github.com/example/student-portfolio', 'HTML, CSS, Bootstrap, JavaScript', '2026-02-10 10:00:00'),
(2, 'Library Management Mini Project', 'College mini project for managing book records and student issue tracking.', 'https://example.com/library-project', 'https://github.com/example/library-project', 'PHP, MySQL, Bootstrap', '2026-03-01 12:00:00'),
(3, 'Startup Brand Kit', 'Created a full digital branding package and online profile setup for a startup founder.', 'https://example.com/brand-kit', 'https://github.com/example/brand-kit', 'Canva, Figma, HTML, CSS', '2026-02-20 09:15:00'),
(3, 'Freelance Landing Page', 'Designed and delivered a mobile-first landing page for a coaching business.', 'https://example.com/freelance-landing', 'https://github.com/example/freelance-landing', 'Bootstrap, JavaScript, Content Strategy', '2026-03-12 15:00:00'),
(4, 'Operations Dashboard Rollout', 'Led a KPI dashboard rollout that improved weekly reporting visibility across departments.', 'https://example.com/ops-dashboard', 'https://github.com/example/ops-dashboard', 'Reporting, Process Design, Stakeholder Management', '2026-02-28 08:30:00');

INSERT INTO certificates (user_id, title, organization, issue_date, certificate_link) VALUES
(2, 'Responsive Web Design', 'freeCodeCamp', '2025-11-15', 'https://example.com/certificates/responsive-web-design'),
(2, 'MySQL Basics', 'Great Learning', '2025-12-22', 'https://example.com/certificates/mysql-basics'),
(3, 'Graphic Design Masterclass', 'Udemy', '2024-08-05', 'https://example.com/certificates/design-masterclass'),
(4, 'Project Leadership Certification', 'Coursera', '2023-09-14', 'https://example.com/certificates/project-leadership');

INSERT INTO contacts (user_id, email, phone, address, website, linkedin, github, twitter, instagram) VALUES
(2, 'student@example.com', '+91-9000000001', 'Kolkata, India', 'https://johnstudent.example.com', 'https://linkedin.com/in/johnstudent', 'https://github.com/johnstudent', 'https://twitter.com/johnstudent', 'https://instagram.com/johnstudent'),
(3, 'freelancer@example.com', '+91-9000000002', 'Bengaluru, India', 'https://sarafreelancer.example.com', 'https://linkedin.com/in/sarafreelancer', 'https://github.com/sarafreelancer', 'https://twitter.com/sarafreelancer', 'https://instagram.com/sarafreelancer'),
(4, 'professional@example.com', '+91-9000000003', 'Mumbai, India', 'https://davidpro.example.com', 'https://linkedin.com/in/davidpro', 'https://github.com/davidpro', 'https://twitter.com/davidpro', 'https://instagram.com/davidpro');

INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES
('Neha Sharma', 'neha@example.com', 'Project Inquiry', 'I would like to know whether this system supports private profile links.', '2026-04-13 10:10:00'),
('Amit Roy', 'amit@example.com', 'Template Question', 'Can more portfolio templates be added later?', '2026-04-14 15:25:00');
