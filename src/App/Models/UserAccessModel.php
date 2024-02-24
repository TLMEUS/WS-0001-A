<?php
/**
 * This file contains the App/Models/UserAccessModel.php class for project WS-0001-A
 *
 * File Information:
 * Project Name: WS-0001-A
 * Module Name: App/Models
 * File Name: UserAccessModel.php
 * File Version: 1.0.0
 * Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Copyright: 01/2024
 */
namespace App\Models;

use Framework\Model;
use PDO;

/**
 * The UserAccessModel class represents a model for user access data.
 * It extends the base Model class.
 */
class UserAccessModel extends Model {

    protected string $table = "tbl_user_access";

    /**
     * Validates a user based on their username and application.
     *
     * @param string $user The username of the user to validate.
     * @param string $app The application to validate the user for.
     *
     * @return bool Returns true if the user is valid, false otherwise.
     */
    public function validateUser(string $user, string $app): bool {
        $sql = "SELECT * FROM $this->table WHERE colName = :user AND colApplication = :app";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(param: ":user", value: $user);
        $stmt->bindValue(param: ":app", value: $app);
        $stmt->execute();
        $result = $stmt->fetch(mode: PDO::FETCH_ASSOC);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retrieves the access level of a user for a specific application.
     *
     * @param string $user The name of the user.
     * @param string $app The name of the application.
     *
     * @return int The access level of the user for the specified application,
     *              or 0 if the user does not have access.
     */
    public function getUserAccess(string $user, string $app): int {
        $sql = "SELECT * FROM $this->table WHERE colName = :user AND colApplication = :app";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(param: ":user", value: $user);
        $stmt->bindValue(param: ":app", value: $app);
        $stmt->execute();
        $result = $stmt->fetch(mode: PDO::FETCH_ASSOC);
        if ($result) {
            return $result['colAccess'];
        } else {
            return 0;
        }
    }
}