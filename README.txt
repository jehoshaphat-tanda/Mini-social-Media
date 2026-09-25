MINI SOCIAL MEDIA APPLICATION
==============================

Requirements:
- XAMPP
- Apache
- MySQL
- PHP 8.x recommended

INSTALLATION
1. Copy the SOCIAL_MEDIA folder into C:\xampp\htdocs\.
2. Start Apache and MySQL from XAMPP.
3. Open phpMyAdmin.
4. Import database.sql.
5. Open http://localhost/SOCIAL_MEDIA/register.php.
6. Create two test accounts so you can demonstrate interaction between users.

FEATURES
1. User registration and login.
2. Secure password hashing with password_hash().
3. Session-based authentication.
4. Create text-only posts.
5. Edit only your own posts.
6. Delete only your own posts.
7. View posts from other users.
8. Add comments to posts.
9. Edit only your own comments.
10. Delete only your own comments.
11. View comments on every post.
12. Prepared SQL statements to reduce SQL injection risk.
13. htmlspecialchars() to reduce XSS risk when displaying user content.
14. Foreign keys with cascading deletes.

PRESENTATION TEST
1. Register User A.
2. Register User B.
3. Log in as User A and create a post.
4. Log in as User B and verify that User B can see User A's post.
5. User B comments on User A's post.
6. User B edits and deletes User B's comment.
7. Log in as User A and edit/delete User A's post.
8. Explain that PHP checks user_id before editing or deleting content.

IMPORTANT
- The database username is assumed to be root.
- The default XAMPP MySQL password is assumed to be empty.
- If your MySQL password is different, change $dbpass in config/database.php.
- Every source-code line in this starter project has an explanatory comment.
