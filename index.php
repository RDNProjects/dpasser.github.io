<?php
function getUsers()
{
    return parse_ini_file('users.ini', true);
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';

    if ($action === 'getUser') {
        $login = $data['login'] ?? '';
        $users = getUsers();
        if (isset($users[$login])) {
            echo json_encode(['status' => 'success', 'user' => $users[$login]]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
        }
    } elseif ($action === 'getUserByEmail') {
        $email = $data['email'] ?? '';
        $users = getUsers();
        foreach ($users as $login => $user) {
            if (strtolower($user['email']) === strtolower($email)) {
                echo json_encode(['status' => 'success', 'user' => $user]);
                exit;
            }
        }
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
}
?>
