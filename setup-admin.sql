-- NV3 Vegan Database - Quick Setup with Admin User
-- Run this AFTER running: php yii migrate

USE nv3_vegan_db;

-- Create admin user
-- Username: admin
-- Password: admin123
-- Email: admin@nv3.local
INSERT INTO `user` (
    `username`,
    `auth_key`,
    `password_hash`,
    `password_reset_token`,
    `email`,
    `status`,
    `created_at`,
    `updated_at`,
    `verification_token`
) VALUES (
    'admin',
    'test100key000000000000000000000',
    '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3lO',
    NULL,
    'admin@nv3.local',
    10,
    UNIX_TIMESTAMP(),
    UNIX_TIMESTAMP(),
    NULL
);

-- Optional: Create a test editor user
-- Username: editor
-- Password: editor123
-- Email: editor@nv3.local
INSERT INTO `user` (
    `username`,
    `auth_key`,
    `password_hash`,
    `password_reset_token`,
    `email`,
    `status`,
    `created_at`,
    `updated_at`,
    `verification_token`
) VALUES (
    'editor',
    'test200key000000000000000000000',
    '$2y$13$nkpLFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3lP',
    NULL,
    'editor@nv3.local',
    10,
    UNIX_TIMESTAMP(),
    UNIX_TIMESTAMP(),
    NULL
);

SELECT 'Admin user created successfully!' AS message;
SELECT 'Login credentials:' AS '';
SELECT '  Backend: http://localhost:8080' AS '';
SELECT '  Username: admin' AS '';
SELECT '  Password: admin123' AS '';
SELECT '' AS '';
SELECT '  Username: editor' AS '';
SELECT '  Password: editor123' AS '';
