<?php
/**
 * This file contains the App/Models/UserModel.php class for project WS-0001-A
 *
 * File Information:
 * Project Name: WS-0001-A
 * Module Name: App/Models
 * File Name: UserModel.php
 * File Version: 1.0.0
 * Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Copyright: 01/2024
 */
namespace App\Models;

use Framework\Model;

class UserModel extends Model {
    protected string $table = "tbl_users";

    /**
     * Changes the password for a user specified by the given column id.
     *
     * @param string $colId The column id of the user.
     * @param string $password The new password to be set.
     *
     * @return void
     *
     * @noinspection PhpUnused
     */
    public function changePassword(string $colId, string $password): void {
        $encPwd = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE $this->table SET colPassword = :encPwd WHERE colId = :colId";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":encPwd", $encPwd);
        $stmt->bindValue( ":colId", $colId);
        $stmt->execute();
    }
}