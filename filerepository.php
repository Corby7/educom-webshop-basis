<?php
    define("USER_DATA_PATH", 'users/users.txt');
  
    //call readUserDataFile to obtain the user data
    $userdata_array = readUserDataFile(USER_DATA_PATH);

function readUserDataFile($userdatafile_path) {
    $userdata_array = array();
    $usersfile = fopen($userdatafile_path, 'r') or die("Unable to open file!");

    while (!feof($usersfile)) {
        $line = fgets($usersfile);
        $values = explode('|', $line);

        if (count($values) === 3) {
            $userdata_array[] = array(
                'email' => trim($values[0]),
                'name' => trim($values[1]),
                'pass' => trim($values[2])
            );
        }
    }

    //close the file
    fclose($usersfile);
    return $userdata_array;
}

function findUserByEmail($email, $userdata_array) {
    foreach ($userdata_array as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null; //return null if no user with the given email is found
}

//maybe a better name is saveUser?
function writeUserDataFile($email, $name, $pass) {
    // Specify the path to the .txt file
    $userdatafile_path = 'users/users.txt';

    //open userdata file, append new userdata in newline and close file
    $usersfile = fopen($userdatafile_path, 'a') or die("Unable to open file!");
    $newUserDatatxt = $email . '|' . $name . '|' . $pass . "\n";
    fwrite($usersfile, $newUserDatatxt);
    fclose($usersfile);
}

?>