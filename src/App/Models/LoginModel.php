<?php
/**
 * This file contains the App/Models/LoginModel.php class for project WS-0001-A
 *
 * File Information:
 * Project Name: WS-0001-A
 * Module Name: App/Models
 * File Name: LoginModel.php
 * File Version: 1.0.0
 * Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Copyright: 02/2024
 */
namespace App\Models;

use Framework\Model;

/**
 * Class LoginModel
 *
 * This class represents a LoginModel object that handles logging in users by inserting their information
 * into the database.
 *
 * @noinspection PhpUnused
 */
class LoginModel extends Model {

    protected string $table = "tbl_login";

    /**
     * Logs in a user by inserting their information into the database.
     *
     * @param int $user The user ID.
     * @param int $dep The department ID.
     * @param int $role The role ID.
     * @param int $access The access ID.
     *
     * @return int The last inserted ID.
     */
    public function loginUser(int $user, int $dep, int $role, int $access ): int {
        if ($this->loggedUser(user: $user)) {
            $this->updateLog(user: $user);
            return $user;
        }
        $sql = "INSERT INTO $this->table (colName, colTime, colDepartment, colRole, colAccess) 
                VALUES (:colName, :colTime, :colDepartment, :colRole, :colAccess)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare(query: $sql);
        $stmt->bindValue(param: ':colName', value: $user);
        $stmt->bindValue(param: ':colTime', value: time());
        $stmt->bindValue(param: ':colDepartment', value: $dep);
        $stmt->bindValue(param: ':colRole', value: $role);
        $stmt->bindValue(param: ':colAccess', value: $access);
        $stmt->execute();
        return $conn->lastInsertId();
    }

    /**
     * Checks if a user is logged in by searching for their information in the database.
     *
     * @param int $user The user ID to check.
     *
     * @return bool True if the user is logged in, false otherwise.
     */
    public function loggedUser(int $user): bool {
        $sql = "SELECT * FROM $this->table WHERE colName = :colName";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':colName', $user);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    /**
     * Update the log with the current timestamp for the specified user.
     *
     * @param int $user The ID of the user to update the log for.
     *
     * @return void
     */
    public function updateLog(int $user): void
    {
        $sql = "UPDATE $this->table SET colTime = :colTime WHERE colName = :colName";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':colTime', time());
        $stmt->bindValue(':colName', $user);
        $stmt->execute();
    }
}