<?php

define("USER_DATA_PATH", 'users/users.txt');

function findUserByEmail($email) {
    $usersfile = fopen(USER_DATA_PATH, 'r') or die("Unable to open file!");
    $user = null;

    while (!feof($usersfile)) {
        $line = fgets($usersfile);
        $values = explode('|', $line);

        if (count($values) === 3) {
            if ($values[0] == $email) {
                $user = array(
                'email' => trim($values[0]),
                'name' => trim($values[1]),
                'pass' => trim($values[2])
                );
                break;
            }
        }
    }

    //close the file
    fclose($usersfile);
    return $user;
}


function saveUser($email, $name, $pass) {
    // Specify the path to the .txt file
    $userdatafile_path = 'users/users.txt';

    //open userdata file, append new userdata in newline and close file
    $usersfile = fopen($userdatafile_path, 'a') or die("Unable to open file!");
    $newUserDatatxt = $email . '|' . $name . '|' . $pass . "\n";
    fwrite($usersfile, $newUserDatatxt);
    fclose($usersfile);
}

?>