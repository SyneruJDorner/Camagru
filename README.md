# Camagru

A web site that is similar to Pintrest or Instagram, it allows users to upload images, add filters, like, dislike and comment on images.

## Requirements
- XAMPP: https://www.apachefriends.org/index.html

## Installation
### How to download the project
- Navigate to: https://github.com/justindd1994/Camagru
- Click "Code/Download Zip" or simply clone it with Git.
- Once you have downloaded the source code navigate to the fold

### How to set up and configure XAMPP
- Download XAMPP from the provided website
- Install XAMPP on you PC
- Place the downloaded Camagru folder into the installed path "C:\xampp\htdocs\"
- Ensure less secure apps enabled on gmail (as I used gmail for sending email)

- Next navigate to "C:\xampp\php\php.ini"
- Look for the heading "[mail function]"
- Set SMTP=smtp.gmail.com
- smtp_port=587
- sendmail_from = ENTER YOUR EMAIL HERE
- sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"
- Save and close php.ini

- Next navigate to "C:\xampp\sendmail\sendmail.ini"
- Look for the heading "[sendmail]"
- Set smtp_server=smtp.gmail.com
- Set smtp_port=587
- Set auth_username = ENTER YOUR EMAIL HERE
- Set auth_password = ENTER YOUR GMAIL PASSWORD
- Save and close sendmail.ini

### How to run the program
- Open XAMPP
- Click on the start button for "Apache"
- Click on the start button for "MySQL"
- Open a web browser of your choosing
- Type the following in your search bar "http://localhost/camugru/"
- Hit submit, and the website Camagru should appear.

## Code Breakdown
- Back end technologies
    - PHP
    - SQL

- Front-end technologies
    - HTML
    - CSS
    - JavaScript

- Database management systems
    - MySQL
    - phpMyAdmin

## Project Expectations
https://github.com/justindd1994/Camagru/blob/master/camagru.markingsheet.pdf

## Ensure
1.	PHP
2.	No exernal Frameworks
3.	Config/database.php
4.	Config/setup.php
5.	PDO’s


## Steps
1.	Navigate to localhost/Camagru/
2.	Successfully register an account. make sure to use a valid email address as password
3.	Check your inbox for a verification email and verify your account.
4.	Log in to your account.
5.	Update account information.
6.	Post an image with and without an overlay.
7.	Comment, like and dislike images successfully.
8.	Delete a posted image.
9.	Log out and test that strangers are welcome to view the images, but are unable to interact with it.

## Outcomes
1.	The webpage should load.
2.	The account should appear in the database.
3.	You should receive an email with a link to validate your account.
4.	Your username should appear in the top-left corner.
5.	You should be able to edit everything.
6.	A post should show the image and overlay on the home page along with pagination at 5 images.
7.	Your comment should appear.
8.	The post should be deleted.
9.	Strangers should be able to view all images, but will be unable to like, dislike or comment.
