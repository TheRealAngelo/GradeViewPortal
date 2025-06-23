# San Vicente Academy Grade View System

## Overview

The San Vicente Academy Grade View System is a web-based platform designed to streamline academic communication between the faculty and students. This system provides a centralized and secure environment where faculty members can efficiently create and update student grades as well as post important announcements.

In turn, students are given easy and timely access to view their academic performance and stay informed through announcements. By leveraging this system, San Vicente Academy aims to enhance transparency, accuracy, and accessibility in managing academic records and school communications.

## Features

### Admin Features
- Create and manage faculty and student accounts
- Import/export users from CSV files
- System administration capabilities

### Faculty Features
- Dashboard with statistical overview of student performance
- Search and view student grades
- Update and edit student grades
- Import grades from CSV files
- Export grades to CSV files
- Create, edit, and delete announcements
- Change password and switch school years

### Student Features
- Dashboard with performance overview
- View grades for all subjects
- See top performing and lowest performing subjects
- View all announcements
- Change password and switch school years
- Access school vision and mission

## Technology Stack

- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL
- Server: XAMPP (Apache)

## Installation

1. Clone the repository to your XAMPP's htdocs folder:
https://github.com/TheRealAngelo/GradeViewPortal.git


2. Start your XAMPP server and ensure both Apache and MySQL services are running.

3. Import the database schema (located in `database/schema.sql`) to your MySQL server.

4. Configure the database connection in `includes/db_connection.php`.

5. Access the application by navigating to `http://localhost/SoftwareEngineering2Final/src/` in your web browser.

## Directory Structure

- `/assets` - Contains CSS, JavaScript, fonts, icons, and images
- `/includes` - Shared PHP files like database connections and utility functions
- `/src` - Main source code
  - `/admin` - Admin panel and functionality
  - `/faculty` - Faculty dashboard and features
  - `/student` - Student dashboard and features
  - `/login` - Authentication system

## Login Information

Default login credentials for testing:

- Admin:
  - Username: admin
  - Password: admin123

- Faculty:
  - Username: faculty
  - Password: faculty123

- Student:
  - Username: student
  - Password: student123

## Security Features

- Password hashing for secure authentication
- Session management
- Input validation
- Session timeout functionality

## Contributors

This project was developed by ADHD as part of the Software Engineering Final project.

## License

This project is for educational purposes only.
