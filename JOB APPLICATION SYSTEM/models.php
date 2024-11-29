<?php
// Database connection setup
$host = 'localhost';  // or your database host
$dbname = 'job_application_system';  // your database name
$username = 'root';  // your database username
$password = '';  // your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

function createApplicant($full_name, $email, $phone, $qualification, $experience) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO applicants (full_name, email, phone, qualification, experience) VALUES (:full_name, :email, :phone, :qualification, :experience)");
        $stmt->execute([
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'qualification' => $qualification,
            'experience' => $experience
        ]);
        return [
            'statusCode' => 200,
            'message' => 'Applicant added successfully'
        ];
    } catch (PDOException $e) {
        return [
            'statusCode' => 400,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

// Function to get all applicants
function getAllApplicants() {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM applicants");
        $stmt->execute();
        $applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [
            'statusCode' => 200,
            'querySet' => $applicants
        ];
    } catch (PDOException $e) {
        return [
            'statusCode' => 400,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

// Function to get an applicant by ID
function getApplicantById($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM applicants WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $applicant = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($applicant) {
            return [
                'statusCode' => 200,
                'querySet' => [$applicant]
            ];
        } else {
            return [
                'statusCode' => 404,
                'message' => 'Applicant not found'
            ];
        }
    } catch (PDOException $e) {
        return [
            'statusCode' => 400,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

// Function to update an applicant
function updateApplicant($id, $full_name, $email, $phone, $qualification, $experience) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE applicants SET full_name = :full_name, email = :email, phone = :phone, qualification = :qualification, experience = :experience WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'qualification' => $qualification,
            'experience' => $experience
        ]);
        return [
            'statusCode' => 200,
            'message' => 'Applicant updated successfully'
        ];
    } catch (PDOException $e) {
        return [
            'statusCode' => 400,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

// Function to delete an applicant
function deleteApplicant($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM applicants WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return [
            'statusCode' => 200,
            'message' => 'Applicant deleted successfully'
        ];
    } catch (PDOException $e) {
        return [
            'statusCode' => 400,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

// Function to search applicants based on various fields
function searchApplicants($searchTerm) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM applicants WHERE full_name LIKE :searchTerm OR email LIKE :searchTerm OR phone LIKE :searchTerm OR qualification LIKE :searchTerm OR experience LIKE :searchTerm");
        $stmt->execute(['searchTerm' => "%" . $searchTerm . "%"]);
        $applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [
            'statusCode' => 200,
            'querySet' => $applicants
        ];
    } catch (PDOException $e) {
        return [
            'statusCode' => 400,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}
?>
