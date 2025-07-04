<?php
function authenticateAdmin($username, $password, $conn) {
    if (empty($username) || empty($password)) {
        return ['success' => false, 'error' => 'Please fill in all fields'];
    }

    $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL);
    $sql = "SELECT adminID, username, email, password FROM admin WHERE " . ($isEmail ? "email = :username" : "username = :username");
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() !== 1) {
            return ['success' => false, 'error' => 'Account not found'];
        }

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!password_verify($password, $admin['password'])) {
            return ['success' => false, 'error' => 'Incorrect password'];
        }

        return [
            'success' => true,
            'admin' => [
                'id' => $admin['adminID'],
                'username' => $admin['username'],
                'email' => $admin['email']
            ]
        ];
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error'];
    }
}
?>