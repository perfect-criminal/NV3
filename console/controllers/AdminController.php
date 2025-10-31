<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\User;

/**
 * Admin management console controller
 */
class AdminController extends Controller
{
    /**
     * Create an admin user
     *
     * Usage: php yii admin/create-user <username> <email> <password>
     * Example: php yii admin/create-user admin admin@nv3.local admin123
     */
    public function actionCreateUser($username, $email, $password)
    {
        // Check if user already exists
        if (User::findByUsername($username)) {
            $this->stdout("Error: User '{$username}' already exists!\n", Console::FG_RED);
            return 1;
        }

        if (User::findByEmail($email)) {
            $this->stdout("Error: Email '{$email}' is already registered!\n", Console::FG_RED);
            return 1;
        }

        // Create new user
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;

        if ($user->save()) {
            $this->stdout("✓ User created successfully!\n\n", Console::FG_GREEN);
            $this->stdout("Login Details:\n", Console::BOLD);
            $this->stdout("  Username: {$username}\n");
            $this->stdout("  Email: {$email}\n");
            $this->stdout("  Password: {$password}\n");
            $this->stdout("\nAccess backend at: http://localhost:8080\n");
            return 0;
        } else {
            $this->stdout("Error creating user:\n", Console::FG_RED);
            foreach ($user->errors as $attribute => $errors) {
                $this->stdout("  {$attribute}: " . implode(', ', $errors) . "\n", Console::FG_RED);
            }
            return 1;
        }
    }

    /**
     * Create default admin user
     *
     * Usage: php yii admin/setup
     */
    public function actionSetup()
    {
        $this->stdout("\n=== NV3 Admin Setup ===\n\n", Console::BOLD);

        // Check if admin already exists
        if (User::findByUsername('admin')) {
            $this->stdout("Admin user already exists!\n", Console::FG_YELLOW);
            $this->stdout("Username: admin\n");
            $this->stdout("You can create additional users with: php yii admin/create-user <username> <email> <password>\n");
            return 0;
        }

        // Create default admin
        $user = new User();
        $user->username = 'admin';
        $user->email = 'admin@nv3.local';
        $user->setPassword('admin123');
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;

        if ($user->save()) {
            $this->stdout("✓ Default admin user created!\n\n", Console::FG_GREEN);
            $this->stdout("Login Details:\n", Console::BOLD);
            $this->stdout("  Backend URL: http://localhost:8080\n");
            $this->stdout("  Username: admin\n");
            $this->stdout("  Password: admin123\n");
            $this->stdout("\n⚠ IMPORTANT: Change this password after first login!\n", Console::FG_YELLOW);
            $this->stdout("\nNext steps:\n");
            $this->stdout("  1. Start backend: php yii serve --docroot=backend/web --port=8080\n");
            $this->stdout("  2. Login at: http://localhost:8080\n");
            $this->stdout("  3. Start creating ingredients!\n");
            return 0;
        } else {
            $this->stdout("Error creating admin user:\n", Console::FG_RED);
            foreach ($user->errors as $attribute => $errors) {
                $this->stdout("  {$attribute}: " . implode(', ', $errors) . "\n", Console::FG_RED);
            }
            return 1;
        }
    }

    /**
     * List all users
     *
     * Usage: php yii admin/list-users
     */
    public function actionListUsers()
    {
        $users = User::find()->all();

        if (empty($users)) {
            $this->stdout("No users found. Create one with: php yii admin/setup\n", Console::FG_YELLOW);
            return 0;
        }

        $this->stdout("\n=== Registered Users ===\n\n", Console::BOLD);
        $this->stdout(sprintf("%-15s %-30s %-10s %-20s\n", 'Username', 'Email', 'Status', 'Created'));
        $this->stdout(str_repeat('-', 80) . "\n");

        foreach ($users as $user) {
            $status = $user->status == User::STATUS_ACTIVE ? 'Active' : 'Inactive';
            $created = date('Y-m-d H:i', $user->created_at);
            $this->stdout(sprintf("%-15s %-30s %-10s %-20s\n",
                $user->username,
                $user->email,
                $status,
                $created
            ));
        }
        $this->stdout("\n");
        return 0;
    }

    /**
     * Reset user password
     *
     * Usage: php yii admin/reset-password <username> <new-password>
     * Example: php yii admin/reset-password admin newpass123
     */
    public function actionResetPassword($username, $newPassword)
    {
        $user = User::findByUsername($username);

        if (!$user) {
            $this->stdout("Error: User '{$username}' not found!\n", Console::FG_RED);
            return 1;
        }

        $user->setPassword($newPassword);
        $user->generateAuthKey();

        if ($user->save()) {
            $this->stdout("✓ Password reset successfully!\n\n", Console::FG_GREEN);
            $this->stdout("  Username: {$username}\n");
            $this->stdout("  New Password: {$newPassword}\n");
            return 0;
        } else {
            $this->stdout("Error resetting password:\n", Console::FG_RED);
            foreach ($user->errors as $attribute => $errors) {
                $this->stdout("  {$attribute}: " . implode(', ', $errors) . "\n", Console::FG_RED);
            }
            return 1;
        }
    }
}
