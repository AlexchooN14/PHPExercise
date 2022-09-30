<?php
    require_once("dbconnect.php");
    require_once("Register&Login/email_auth.php");

    function addUser($username, $email, $password, $activation_code, $expiry = 1 * 24  * 60 * 60) {
        $connection = DBConnection::getDatabaseConnection();
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $activation_code = password_hash($activation_code, PASSWORD_DEFAULT);
        $activation_expiry = date('Y-m-d H:i:s',  time() + $expiry);
        $query = "INSERT INTO user(name, email, password, activation_code, activation_expiry)
                  VALUES('$username', '$email', '$hashed_password', '$activation_code', '$activation_expiry')";
        
        return mysqli_query($connection, $query);
    }

    function getUserByEmail($email) {
        $connection = DBConnection::getDatabaseConnection();
        $query = sprintf("SELECT * FROM user WHERE email='%s'", mysqli_real_escape_string($connection, $email));
        $result = mysqli_query($connection, $query);
        return mysqli_fetch_assoc($result);
    }

    function getUserById($id) {
        $connection = DBConnection::getDatabaseConnection();
        $query = sprintf("SELECT * FROM user WHERE id='%s'", mysqli_real_escape_string($connection, $id));
        $result = mysqli_query($connection, $query);
        return mysqli_fetch_assoc($result);
    }

    function userExists($email) {
        return getUserByEmail($email);
    }

    function isPasswordMatch($email, $password) {
        $hashed_password = getUserByEmail($email)['password'];
        return password_verify($password, $hashed_password);
    }

    function displayError($message, $positive=false) {
        echo '<div class="'.(($positive) ? "alert success" : "alert danger").'"><span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>'.$message.'</div>';
    }

    function registerUser($username, $email, $password, $activation_code) {
        if (userExists($email)) {
            echo displayError("Email already taken!");
        } else {
            if (addUser($username, $email, $password, $activation_code)) {     
                sendVerificationEmail($email, $activation_code);           
                header("Location: login.php?register=success", TRUE, 301);
                exit();
            } else {
                echo displayError("Something went wrong!");
            }
        }
    }

    function loginUser($email, $password) {
        $user = getUserByEmail($email);

        if ($user) {

            if (password_verify($password, $user['password'])) {

                // session_start();
                session_regenerate_id();                
                $_SESSION["user_id"] = $user["id"];
                header("Location: ../Index/index.php", TRUE, 301);
                exit;

            } else {
                echo displayError("Wrong password!");
            }
        } else {
            echo displayError("Invalid login!");
        }
    }

    // -------------- Email -------------------

    function generateActivationCode() {
        return bin2hex(random_bytes(16));
    }

    function deleteUserById(int $id, int $active = 0) {
        $query = "DELETE FROM user WHERE id='$id' AND active='$active'";
        $result = mysqli_query(DBConnection::getDatabaseConnection(), $query);

        return mysqli_fetch_assoc($result);
    }

    function isUserActivated($email) {
        $user = getUserByEmail($email);
        return (int)$user['active'] == 1;
    }

    function isUserCodeExpired($email) {
        $connection = DBConnection::getDatabaseConnection();
        $email = mysqli_real_escape_string($connection, $email);
        
        $query = "SELECT id, activation_code, activation_expiry < now() as expired
                FROM user
                WHERE active = 0 AND email='$email'";
        $user = (mysqli_query($connection, $query))->fetch_assoc();
        if ($user) {            
            return (int)$user['expired'] === 1;
        }
        return true;
    }

    function activateUser($user) {
        $connection = DBConnection::getDatabaseConnection();
        $query = "UPDATE user SET active = 1, activated_at = CURRENT_TIMESTAMP WHERE id='".$user['id']."'";
        return mysqli_query($connection, $query);
    }

    function setNewActivationCode($email, $activation_code) {
        $connection = DBConnection::getDatabaseConnection();
        $query = "UPDATE user SET activation_code = '$activation_code' WHERE email='$email'";
        return mysqli_query($connection, $query);
    }
    
    // -------------- Profile Image -------------------

    function getProfileImage($user_id) {
        $query = "SELECT profile_picture FROM user WHERE id='$user_id'";
        $filename = ((mysqli_query(DBConnection::getDatabaseConnection(), $query))->fetch_assoc())['profile_picture'];
        return getSaveDir($filename, FileTypes::Image);
    }

    function setProfileImage($user_id, $filename, $tempname, $filesize) {
        if (empty($filename)) {
            echo displayError("Please choose an image!");
            return;
        } else {
            $files = areValidFiles($filename, $tempname, $filesize, FileTypes::Image);
            if ($files[$filename] == false)
                return;
        }

        $folder = getSaveDir($filename, FileTypes::Image);
        $query = "UPDATE user SET profile_picture = '$filename' WHERE id='$user_id'";
        mysqli_query(DBConnection::getDatabaseConnection(), $query);
        if (move_uploaded_file($tempname, $folder)) {
            echo displayError("You set your profile image successfully!", true);
        }
    }

    // Add Gallery Images or Files
    function addFiles($user_id, $filenames, $tempnames, $filesizes, $filetype) {
        if (empty($filenames[0]) || empty($tempnames[0])) {
            echo displayError("Please choose file!");
            return;
        }
        
        $connection = DBConnection::getDatabaseConnection();
        $files = "";
        switch ($filetype) {
            case FileTypes::Image: $files = areValidFiles($filenames, $tempnames, $filesizes, FileTypes::Image); break;
            case FileTypes::File: $files = areValidFiles($filenames, $tempnames, $filesizes, FileTypes::File); break;
        }
        
        $count_invalid_files = 0;
        for ($i = 0; $i < count($filenames); $i++) {            
            $filename = $filenames[$i];
            $is_valid_file = $files[$filename];
            if (!$is_valid_file) {
                $count_invalid_files++;
                continue;
            }

            $tempname = $tempnames[$i];
            $folder = getSaveDir($filename, $filetype);
            $query = "";
            switch ($filetype) {
                case FileTypes::Image:
                    $query = "INSERT INTO gallery (user_id, filename) VALUES ('$user_id', '$filename')";
                    break;
                case FileTypes::File:
                    $query = "INSERT INTO files (user_id, filename) VALUES ('$user_id', '$filename')";
                    break;
            }

            try {
                mysqli_query($connection, $query);
            } catch (Exception $e) {
                continue;
            }

            if (!move_uploaded_file($tempname, $folder))
                echo displayError("Something went wrong!"); break;       
        }

        if ($count_invalid_files < count($filenames)) {
            switch ($filetype) {
                case FileTypes::Image: echo displayError("You updated your gallery successfully!", true); break;
                case FileTypes::File: echo displayError("You updated your file library successfully!", true); break;
            }
        }
    }

    function getUserGallery($user_id) {
        $query = "SELECT filename FROM gallery WHERE user_id='$user_id'";
        $result = mysqli_query(DBConnection::getDatabaseConnection(), $query)->fetch_all();
        return array_column($result, "0");
    }

    function getUserLibrary($user_id) {
        $query = "SELECT filename FROM files WHERE user_id='$user_id'";
        $result = mysqli_query(DBConnection::getDatabaseConnection(), $query)->fetch_all();
        return array_column($result, "0");
    }

    function getSaveDir($filename, $filetype) {
        switch ($filetype) {
            case FileTypes::Image: return "..".IMAGE_FOLDER.$filename; break;
            case FileTypes::File: return "..".FILE_FOLDER.$filename; break;
        }
    }

    //------------- Validation ----------------

    function isValidExtMime($filename, $tempname, $allowed_extensions, $allowed_mimetypes) {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $mime = mime_content_type($tempname);
        
        foreach ($allowed_extensions as $ext_key => $allowed_extension) {
            if ($ext != $allowed_extension) {
                if ($ext_key == count($allowed_extensions) - 1) {                   
                    return false;
                }
            } else {
                foreach ($allowed_mimetypes as $mime_key => $allowed_mimetype) {
                    if ($mime != $allowed_mimetype) {
                        if ($mime_key == count($allowed_mimetypes) - 1) {                   
                            return false;
                        }
                    } else {
                        return true;
                    }
                }
            }
        }
    }

    function isValidFileSize($filesize) {
        return ($filesize/ 1024 / 1024) <= ALLOWED_FILE_SIZE;
    }

    function areValidFiles($filenames, $tempnames, $filesizes, $filetype) {
        if (gettype($filenames) == "string") $filenames = [$filenames];      
        if (gettype($tempnames) == "string") $tempnames = array($tempnames);
        if (gettype($filesizes) == "integer") $filesizes = array($filesizes);

        $files = [];
        foreach ($filenames as $file_key => $filename) {            
            switch ($filetype) {
                case FileTypes::Image: $files[$filename] = isValidExtMime($filename, $tempnames[$file_key], ALLOWED_IMAGE_EXTENSIONS, ALLOWED_IMAGE_MIMETYPES); break;
                case FileTypes::File: $files[$filename] = isValidExtMime($filename, $tempnames[$file_key], ALLOWED_FILE_EXTENSIONS, ALLOWED_FILE_MIMETYPES); break;
            }

            if ($files[$filename] == true) {
                if (!isValidFileSize($filesizes[$file_key])) {
                    $files[$filename] = false;
                }
            }                    
        }
        if (in_array(false, $files)) {
            switch ($filetype) {
                case FileTypes::Image: echo displayError("Max filesize is 10MB and Allowed extensions are ".implode(", ", ALLOWED_IMAGE_EXTENSIONS)); break;
                case FileTypes::File: echo displayError("Max filesize is 10MB and Allowed extensions are ".implode(", ", ALLOWED_FILE_EXTENSIONS)); break;
            }            
        }

        return $files;
    }
?>