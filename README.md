# Movie-Reservation-System

This project is a Movie Reservation System developed using HTML, CSS, PHP, and MySQL.
Description

The Movie Reservation System allows users to log in and reserve seats for movies. Administrators have additional privileges, such as adding, updating, and deleting movies. Users can also create accounts and reset their passwords if forgotten. The system utilizes both simple and complex SQL queries, including variable and normal queries, as well as insertion, editing, and deletion queries.
Features

    User Authentication: Users can log in with their credentials.
    Admin Privileges: Administrators can manage movies (add, update, delete).
    Movie Reservation: Users can reserve seats for movies.
    Account Management: Users can create accounts and reset passwords.

Technologies Used

    HTML: Front-end structure of web pages.
    CSS: Styling for web pages.
    PHP: Backend scripting language for server-side logic and database connectivity.
    MySQL: Relational database management system for storing movie and user data.

Setup

    Clone this repository to your local machine.
    Import the provided SQL file into your MySQL database to set up the database schema and populate initial data.
    Configure the database connection in the PHP files with your MySQL credentials.
    Upload the project files to your web server.

Usage

    Access the login page and enter your credentials.
    If logged in as an admin, navigate to the admin panel to manage movies.
    If logged in as a regular user, reserve seats for movies.
    Create an account or reset your password if needed.

Database Schema

The database schema includes tables for users, movies, reservations, and password reset requests.

    Users Table: Stores user information including username, password, and role.
    Movies Table: Stores movie details such as title, description, and availability.
    Reservations Table: Stores reservations made by users.
    Password Reset Requests Table: Stores requests for password resets.
