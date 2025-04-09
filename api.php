<?php
session_start();
header('Content-Type: application/json');

require_once 'config.php'; // Include database connection


// Handle CORS for development
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Simple router based on request method and endpoint
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'login':
        handleLogin($pdo);
        break;
    case 'logout':
        handleLogout();
        break;
    case 'employee':
        handleEmployee($pdo);
        break;
    case 'searchEmployee':
        searchEmployee($pdo);
        break;
    case 'allEmployees':
        getAllEmployees($pdo);
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
}

// Handle login requests
function handleLogin($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['email']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Email and password are required']);
        return;
    }

    $email = $data['email'];
    $password = $data['password'];

    $stmt = $pdo->prepare("SELECT id, first_name, last_name, password, is_admin FROM employees WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // For demo purposes, we'll use simple password verification
        // In production, use password_verify with properly hashed passwords
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = $user['is_admin'];

            echo json_encode([
                'success' => true,
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['first_name'] . ' ' . $user['last_name'],
                    'is_admin' => (bool)$user['is_admin']
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid password']);
        }
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'User not found']);
    }
}

// Handle logout
function handleLogout() {
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
}

// Handle employee data operations
function handleEmployee($pdo) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        return;
    }

    $userId = $_SESSION['user_id'];
    $isAdmin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false;

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Fetch employee data
        $employeeId = isset($_GET['id']) ? $_GET['id'] : $userId;

        // Non-admins can only view their own profile
        if   (!$isAdmin && $employeeId != $userId) {
            http_response_code(403);
            echo json_encode(['error' => 'Access denied']);
            return;
        }

        $stmt = $pdo->prepare("SELECT id, first_name, last_name, date_of_birth, phone_number, 
                               office_number, employee_category, email, is_admin 
                               FROM employees WHERE id = ?");
        $stmt->execute([$employeeId]);
        $employee = $stmt->fetch();

        if ($employee) {
            echo json_encode(['success' => true, 'employee' => $employee]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Employee not found']);
        }
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Update employee data
        $data = json_decode(file_get_contents('php://input'), true);
        $employeeId = isset($data['id']) ? $data['id'] : $userId;

        // Non-admins can only edit their own profile
        if (!$isAdmin && $employeeId != $userId) {
            http_response_code(403);
            echo json_encode(['error' => 'Access denied']);
            return;
        }

        $allowedFields = ['first_name', 'last_name', 'date_of_birth', 'phone_number',
            'office_number', 'employee_category'];
        $updates = [];
        $params = [];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = ?";
                $params[] = $data[$field];
            }
        }

        if (empty($updates)) {
            http_response_code(400);
            echo json_encode(['error' => 'No fields to update']);
            return;
        }

        $params[] = $employeeId;
        $sql = "UPDATE employees SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute($params)) {
            echo json_encode(['success' => true, 'message' => 'Employee updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update employee']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
}

// Search for employee by email (admin function)
function searchEmployee($pdo) {
    // Check if user is logged in and is admin
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized']);
        return;
    }

    if (!isset($_GET['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Email parameter is required']);
        return;
    }

    $email = $_GET['email'];

    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email FROM employees WHERE email = ?");
    $stmt->execute([$email]);
    $employee = $stmt->fetch();

    if ($employee) {
        echo json_encode(['success' => true, 'employee' => $employee]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Employee not found']);
    }
}

// Get all employees (admin function)
function getAllEmployees($pdo) {
    // Check if user is logged in and is admin
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized']);
        return;
    }

    $stmt = $pdo->query("SELECT id, first_name, last_name, email FROM employees ORDER BY last_name, first_name");
    $employees = $stmt->fetchAll();

    echo json_encode(['success' => true, 'employees' => $employees]);
}