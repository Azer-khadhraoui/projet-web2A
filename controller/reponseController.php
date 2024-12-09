<?php

require_once '../config.php';
require_once '../model/reponseModel.php';

require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ReponseController
{
    private $db;

    public function __construct()
    {
        $this->db = Config::getConnexion();
    }

    public function addResponse(ReponseModel $response)
    {
        $sql = "INSERT INTO response (contenue, id_quest, id_user, date) VALUES (:contenue, :id_quest, :id_user, :date)";
        try {
            $stmt = $this->db->prepare($sql);

            $contenue = $response->getContenue();
            $idQuest = $response->getIdQuest();
            $idUser = $response->getIdUser();
            $date = $response->getDate();

            $stmt->bindParam(':contenue', $contenue);
            $stmt->bindParam(':id_quest', $idQuest);
            $stmt->bindParam(':id_user', $idUser);
            $stmt->bindParam(':date', $date);

            $success = $stmt->execute();

            if ($success) {
                // Fetch question owner's email
                $email = $this->getQuestionOwnerEmail($idQuest);
                 /*($email) {
                    // Send notification email
                    $this->sendEmailNotification(
                        $email,
                        "New Response to Your Question",
                        "Your question has received a new response:\n\n" . $contenue
                    );
                }*/
            }

            return $success;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getResponsesByQuestionId($id_quest)
    {
        $sql = "SELECT * FROM response WHERE id_quest = :id_quest ORDER BY date ASC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_quest', $id_quest);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getQuestionOwnerEmail($idQuest)
    {
        $sql = "SELECT users.email 
                FROM users 
                INNER JOIN question ON question.id_user = users.id 
                WHERE question.id_quest = :id_quest";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_quest', $idQuest, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['email'] ?? null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function sendEmailNotification($to, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io'; // Replace with your SMTP host
            $mail->SMTPAuth = true;
            $mail->Username = '85d78cee936db2'; // Replace with your email
            $mail->Password = 'ffaeb05ad99f1e'; // Replace with your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 2525;

            // Recipients
            $mail->setFrom('agricult@gmail.com', 'Your Application'); // Replace "yourdomain.com" with your real domain if applicable.

            $mail->addAddress($to);

            // Content
            $mail->isHTML(false); // Set email format to plain text
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
        } catch (Exception $e) {
            // Log email sending errors (optional)
            error_log("Error sending email: " . $mail->ErrorInfo);
        }
    }
    /* public function addResponse(ReponseModel $response) {
         $sql = "INSERT INTO response (contenue, id_quest, id_user, date) VALUES (:contenue, :id_quest, :id_user, :date)";
         try {
             $stmt = $this->db->prepare($sql);
     
             // Assign method results to variables
             $contenue = $response->getContenue();
             $idQuest = $response->getIdQuest();
             $idUser = $response->getIdUser();
             $date = $response->getDate();
     
             // Bind variables
             $stmt->bindParam(':contenue', $contenue);
             $stmt->bindParam(':id_quest', $idQuest);
             $stmt->bindParam(':id_user', $idUser);
             $stmt->bindParam(':date', $date);
     
             return $stmt->execute();  // Execute and return success status
         } catch (PDOException $e) {
             // Handle exceptions (optional: log them for debugging)
             return false;
         }
     }
*/

   /* public function getResponsesByQuestionId($id_quest)
    {
        $sql = "SELECT * FROM response WHERE id_quest = :id_quest ORDER BY date ASC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_quest', $id_quest);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }*/

    // Check if the response belongs to the user
    public function isUserResponseOwner($id_response, $id_user)
    {
        $sql = "SELECT COUNT(*) FROM response WHERE id_reponse = :id AND id_user = :id_user";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id_response);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteResponse($id_response, $id_user)
    {
        if ($this->isUserResponseOwner($id_response, $id_user)) {
            $sql = "DELETE FROM response WHERE id_reponse= :id";
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id_response);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                return false;
            }
        } else {
            return false;
        }
    }
    public function countResponses()
    {
        try {
            $query = $this->db->prepare("SELECT COUNT(*) AS total FROM response");
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            die("Error counting responses: " . $e->getMessage());
        }
    }


    public function updateResponse($id_response, $contenue, $id_user)
    {
        if ($this->isUserResponseOwner($id_response, $id_user)) {
            $sql = "UPDATE response SET contenue = :contenue WHERE id_reponse = :id";
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':contenue', $contenue);
                $stmt->bindParam(':id', $id_response);
                $stmt->execute();

                // Return true if the update was successful
                return $stmt->rowCount() > 0;
            } catch (PDOException $e) {
                return false;
            }
        } else {
            return false;  // Return false if user is not authorized
        }
    }
    public function countUserResponsesToQuestion($questionId, $userId)
    {
        try {
            // Prepare the SQL query
            $query = "SELECT COUNT(*) FROM response WHERE id_quest = :id_quest AND id_user = :id_user";
            $statement = $this->db->prepare($query);

            // Assign values to variables
            $idQuest = intval($questionId); // Explicitly cast to integer
            $idUser = intval($userId);

            // Bind variables to the query
            $statement->bindParam(':id_quest', $idQuest);
            $statement->bindParam(':id_user', $idUser);

            // Execute the statement
            $statement->execute();

            // Return the count as an integer
            return (int) $statement->fetchColumn();
        } catch (PDOException $e) {
            // Handle potential errors gracefully
            echo "Error counting user responses: " . $e->getMessage();
            return 0; // Return 0 if there's an error
        }
    }


    private function deleteResponseById($id_reponse)
    {
        $deleteQuery = "DELETE FROM response WHERE id_reponse = ?";
        $deleteStmt = $this->db->prepare($deleteQuery);
        $deleteStmt->execute([$id_reponse]);
    }

    public function deleteResponsesWithBadWords()
    {
        // List of bad words to search for
        $badWords = ['bad1', 'bad2', 'bad3'];

        // Prepare the query to fetch responses containing bad words
        $placeholders = implode(',', array_fill(0, count($badWords), '?'));
        $query = "SELECT id_reponse, contenue FROM response WHERE " . implode(' OR ', array_map(fn($word) => "contenue LIKE ?", $badWords));
        $stmt = $this->db->prepare($query);

        // Bind the bad words with wildcards for partial matching
        foreach ($badWords as $index => $word) {
            $stmt->bindValue($index + 1, '%' . $word . '%');
        }

        $stmt->execute();
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop through results and delete each response
        foreach ($response as $response) {
            $this->deleteResponseById($response['id_reponse']);
        }

        return count($response); // Return the count of deleted responses
    }

}
?>