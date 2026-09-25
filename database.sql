-- Create the database used by the mini social media application.
CREATE DATABASE IF NOT EXISTS social_media;
-- Select the database so the following tables are created inside it.
USE social_media;
-- Create the users table for account information.
CREATE TABLE IF NOT EXISTS users (
    -- Store the unique numeric identifier of each user.
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Store the user's chosen username.
    username VARCHAR(50) NOT NULL UNIQUE,
    -- Store the user's email address.
    email VARCHAR(100) NOT NULL UNIQUE,
    -- Store the securely hashed password.
    password VARCHAR(255) NOT NULL,
    -- Store the date and time when the account was created.
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Create the posts table for text-only posts.
CREATE TABLE IF NOT EXISTS posts (
    -- Store the unique numeric identifier of each post.
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Store the ID of the user who owns the post.
    user_id INT NOT NULL,
    -- Store the text content of the post.
    content TEXT NOT NULL,
    -- Store the date and time when the post was created.
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- Store the date and time when the post was last edited.
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    -- Connect each post to its owner.
    CONSTRAINT fk_posts_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
-- Create the comments table for comments belonging to posts.
CREATE TABLE IF NOT EXISTS comments (
    -- Store the unique numeric identifier of each comment.
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Store the ID of the post being commented on.
    post_id INT NOT NULL,
    -- Store the ID of the user who wrote the comment.
    user_id INT NOT NULL,
    -- Store the text content of the comment.
    content TEXT NOT NULL,
    -- Store the date and time when the comment was created.
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- Store the date and time when the comment was last edited.
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    -- Connect each comment to its post.
    CONSTRAINT fk_comments_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    -- Connect each comment to its author.
    CONSTRAINT fk_comments_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
