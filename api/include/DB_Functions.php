<?php
    class DB_Functions {
        private $conn;
     
        // constructor
        function __construct() {
            require_once 'DB_Connect.php';
            $db = new Db_Connect();
            $this->conn = $db->connect();
        }
     
        // destructor
        function __destruct() {
            mysqli_close($this->conn);
        }
     
        /**
         * Storing new user
         * returns user details
         */
        public function storeUser($userName, $email, $password) {
            $hash = $this->hashSSHA($password);
            $encrypted_password = $hash["encrypted"];
            $salt = $hash["salt"];
     
            $stmt = $this->conn->prepare("INSERT INTO users(username, email, encrypted_password, salt, created_at) VALUES(?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssss", $userName, $email, $encrypted_password, $salt);
            $result = $stmt->execute();
            $stmt->close();
     
            // check for successful store
            if ($result) {
                $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                return $user;
            } else {
                return false;
            }
        }
     
        /**
         * Get user by email and password
         */
        public function getUserByEmailAndPassword($email, $password) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                $user = $stmt->get_result()->fetch_assoc();
                $stmt->close();
     
                // verifying user password
                $salt = $user['salt'];
                $encrypted_password = $user['encrypted_password'];
                $hash = $this->checkhashSSHA($salt, $password);
                // check for password equality
                if ($encrypted_password == $hash) {
                    // user authentication details are correct
                    return $user;
                }
            } else {
                return NULL;
            }
        }
     
        /**
         * Get user by email
         */
        public function getUserByEmail($email) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                $user = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                return $user;
            } else {
                return NULL;
            }
        }

        /**
         * Get all users
         */
        public function getUsers() {
            $stmt = $this->conn->prepare("SELECT * FROM users");
            if ($stmt->execute()) {
                $users = $stmt->get_result();
                $result = array();
                while($row = $users->fetch_assoc()) {
                    $user = array();
                    $user["userName"] = $row["user_name"];
                    $user["email"] = $row["email"];
                    $user["createdTime"] = $row["created_at"];
                    $user["updatedTime"] = $row["updated_at"];
                    array_push($result, $user);
                }
                return $result;
            } else {
                return NULL;
            }
        }

        /**
         * update User
         */
        public function updateUser($userName, $email) {
            $user = $this->getUserByEmail($email);
            if(empty($firstName)) {
                $userName = $user["username"];
            }
            
            $stmt = $this->conn->prepare("UPDATE users SET first_name = ?, last_name = ?, display_name = ?, gender = ?, updated_at = NOW() WHERE email = ?");
            $stmt->bind_param("sssss",$firstName, $lastName, $displayName, $gender, $email);
            if ($stmt->execute()) {
                $user = $this->getUserByEmail($email);
                return $user;
            } else {
                return NULL;
            }
        }
		

        /**
         * delete User
         */
        public function deleteUserByEmail($email) {
            $stmt = $this->conn->prepare("DELETE FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * delete all User
         */
        public function deleteUsers() {
            $stmt = $this->conn->prepare("DELETE FROM users");
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Check user is existed or not
         */
        public function isUserExisted($email) {
            $stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");
     
            $stmt->bind_param("s", $email);
     
            $stmt->execute();
     
            $stmt->store_result();
     
            if ($stmt->num_rows > 0) {
                // user existed 
                $stmt->close();
                return true;
            } else {
                // user not existed
                $stmt->close();
                return false;
            }
        }
     
        /**
         * Encrypting password
         * @param password
         * returns salt and encrypted password
         */
        public function hashSSHA($password) {
            $salt = sha1(rand());
            $salt = substr($salt, 0, 10);
            $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
            $hash = array("salt" => $salt, "encrypted" => $encrypted);
            return $hash;
        }
     
        /**
         * Decrypting password
         * @param salt, password
         * returns hash string
         */
        public function checkhashSSHA($salt, $password) {
     
            $hash = base64_encode(sha1($password . $salt, true) . $salt);
     
            return $hash;
        }
     
    }
?>