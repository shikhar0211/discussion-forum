# Discussion Forum (PHP + MySQL)

A simple, modern discussion forum built with PHP (procedural + mysqli), Bootstrap 5, and MySQL. Features user registration/login, topics, replies, likes, search, profiles, and an admin dashboard for moderation.

## Tech Stack
- **Backend**: PHP 8+ (procedural), mysqli prepared statements
- **Frontend**: Bootstrap 5.3, Bootstrap Icons, custom CSS (`assests/css/style.css`)
- **Database**: MySQL (tested with XAMPP)
- **Session/Auth**: Native PHP sessions, password hashing (`password_hash`, `password_verify`)

## Project Structure
```
Discussion-Forum-main/
├─ admin/
│  ├─ dashboard.php           # Admin dashboard (manage users, topics, posts)
│  ├─ delete_user.php         # Admin-only delete
│  ├─ delete_topic.php        # Admin-only delete
│  └─ delete_post.php         # Admin-only delete
├─ assests/
│  └─ css/
│     └─ style.css            # Custom styles
├─ includes/
│  ├─ db.php                  # Database connection (mysqli)
│  ├─ header.php              # <head>, navbar, theme toggle, container start
│  ├─ footer.php              # Footer, Bootstrap JS, theme script
│  └─ auth.php                # Login guard (require session)
├─ create_topic.php           # Create new topic (auth required)
├─ data_seed.sql              # Example seed data (users, topics, posts)
├─ edit_profile.php           # Update profile bio (auth required)
├─ index.php                  # Home, list recent topics
├─ like.php                   # Toggle like/unlike on replies (auth required)
├─ login.php                  # Email + password login
├─ logout.php                 # Session destroy
├─ post_reply.php             # Reply to topic (auth required)
├─ profile.php                # Public profile and activity
├─ register.php               # Registration
├─ search.php                 # Search topics and replies
└─ topic.php                  # Topic detail, replies, like buttons, reply form
```

## Features
- **Auth**: Register, login, logout; roles: `user`, `admin` (set via DB)
- **Topics**: Create, list, view details
- **Replies**: Post replies under a topic
- **Likes**: Like/unlike replies (per-user)
- **Search**: Query topics (title/description) and replies (content)
- **Profiles**: Public profile with bio, topics, and replies; edit own bio
- **Admin**: Dashboard to view and delete users/topics/posts
- **UI**: Bootstrap 5, responsive, dark/light theme toggle (localStorage)

## Requirements
- PHP 8.0+
- MySQL 5.7+/8.0+
- Web server (XAMPP/LAMP/WAMP). Tested with XAMPP on Windows.

## Local Setup (XAMPP)
1. Clone or copy the folder into your web root. With XAMPP default:
   - `C:\xampp\htdocs\Discussion-Forum-main`
2. Create the database and tables (example schema below). You can use phpMyAdmin or MySQL CLI.
3. Configure DB connection in `includes/db.php` if needed:
   - Host: `localhost`
   - User: `root`
   - Password: `""` (empty by default in XAMPP)
   - Database: `forum_db`
   - Port: `3307` (adjust if your MySQL uses 3306)
4. Start Apache and MySQL in XAMPP.
5. Visit `http://localhost/Discussion-Forum-main/` in your browser.

## Database Schema (MySQL)
Below is a minimal schema inferred from the code. Adjust types/indices as desired.

```sql
CREATE DATABASE IF NOT EXISTS forum_db;
USE forum_db;

-- Users
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  bio TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Topics
CREATE TABLE IF NOT EXISTS topics (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_topics_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Posts (replies)
CREATE TABLE IF NOT EXISTS posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  topic_id INT NOT NULL,
  user_id INT NOT NULL,
  content TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_posts_topic FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE,
  CONSTRAINT fk_posts_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Likes (per user per post)
CREATE TABLE IF NOT EXISTS likes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  post_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_like (user_id, post_id),
  CONSTRAINT fk_likes_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_likes_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB;
```

After creating tables, optionally load demo data:

```sql
SOURCE C:/xampp/htdocs/Discussion-Forum-main/data_seed.sql;
```

> Note: The `data_seed.sql` uses the hashed password for the literal string `password`.

## Environment/Config
- Database credentials are defined in `includes/db.php`. If your MySQL port is 3306, change `$port = 3306;`.
- Session is started in `includes/header.php`. Auth-protected pages include `includes/auth.php` to require login.

## Usage
- Open the site and register a new account.
- Login using your email/password.
- Create topics, post replies, and like/unlike replies.
- View profiles via navbar → Profile. Edit your bio at `edit_profile.php`.
- Use the search box in the navbar to find topics and replies.
- Theme toggle (moon/sun icon) persists preference via `localStorage`.

## Admin Access
- Admin pages live under `/admin` and require `$_SESSION['role'] === 'admin'`.
- To make an admin, update the user row in MySQL:

```sql
UPDATE users SET role = 'admin' WHERE email = 'bob@example.com';
```

- Admin can delete users, topics, and posts from `admin/dashboard.php`.

## Security Notes
- Uses prepared statements throughout for SQL queries.
- Passwords are hashed using `password_hash` and verified with `password_verify`.
- CSRF protection is not implemented; add tokens if deploying publicly.
- Input is sanitized with `htmlspecialchars` for displayed values.

## Styling
- Custom styles live in `assests/css/style.css` and Bootstrap 5.3 is loaded via CDN.

## Known Quirks
- XAMPP often runs MySQL on port `3307` (this project default). If your setup uses `3306`, update `includes/db.php`.
- Directory name is `assests/` (typo by design); ensure the folder path matches exactly.
- `topic.php` contains two like forms per reply (a styled button and a plain one). You may remove the duplicate block if unwanted.

## Extending
- Add CSRF tokens to forms.
- Add pagination for topics and replies.
- Add edit/delete for own topics/posts.
- Add avatars and file uploads.
- Add email verification and password reset.

## License
This project is provided as-is without a specific license. Add a license file if you plan to distribute.

