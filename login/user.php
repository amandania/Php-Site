<?php

/**
 * @param $conn
 * @param $username
 * if username is inside db
 * @return bool
 */
function user_exists($conn, $username) {
    $sql = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    return (mysqli_num_rows($result) > 0) ? true : false;
}

/**
 * @param $conn
 * @param $username
 * check if username and password match to db info
 * @return bool
 */
function loginSuccuess($conn, $username, $password) {
    $newpass = md5($password);
    $sql = "SELECT username FROM users WHERE username = '$username' AND password = '$newpass'";
    $result = mysqli_query($conn, $sql);
    return (mysqli_num_rows($result) > 0) ? true : false;
}


/**
 * activeUsername is set when
 *   loginSuccuess is true
 *  function used to display new header with Welcome 'activeusername'
 * @return bool
 */
function logged_in() {
    return (isset($_SESSION['activeUsername'])) ? true : false;
}