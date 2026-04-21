# Messaging Website

A real-time chat application built with PHP and MySQL.

## Features

- No login required - just enter a name
- Real-time messaging with polling
- Responsive design
- Admin feature to clear all messages (password: 2007)
- Message history

## Setup

1. Install XAMPP or a similar PHP/MySQL environment.

2. Create a database named `chat_app`.

3. Create a table `messages` with the following structure:

```sql
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    msg TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

4. Place the files in your web server's root directory (e.g., htdocs for XAMPP).

5. Open `index.php` in your browser.

## Usage

- Enter your name to start chatting.
- Type messages and press Enter or click Send.
- Messages update automatically every 1.5 seconds.
- To clear all messages, click "Clear All" and enter the password '2007'.

## Technologies

- PHP
- MySQL
- JavaScript (vanilla)
- HTML/CSS