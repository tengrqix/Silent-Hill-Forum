<?php
require_once 'Database.php';

class Auth {

    public static function register(string $username, string $password, string $email): bool {
        $conn = (new Database())->connect();

      
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $_SESSION['message'] = "Username already exists.";
            return false;
        }


        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $_SESSION['message'] = "Email already exists.";
            return false;
        }


        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashedPassword, $email);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Registration successful. You can now login.";
            return true;
        } else {
            $_SESSION['message'] = "Error during registration.";
            return false;
        }
    }

    public static function login(string $username, string $password): bool {
        $conn = (new Database())->connect();

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $username;
                return true;
            }
        }

        $_SESSION['message'] = "Invalid username or password.";
        return false;
    }

    public static function logout(): void {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }

    public static function isLogged(): bool {
        return isset($_SESSION['user']);
    }

    public static function currentUser(): ?string {
        return $_SESSION['user'] ?? null;
    }
}
