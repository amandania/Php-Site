<?php
include (ROOT_PATH . "db/init.php");
if(!isset($_SESSION)) {
    session_start();
}
/**
 * checks to see if logged in, if so set
 * uername to session username
 * session username exists if logged_in func returns true.
 * I  create a unlcokedlist array object to hold active products on startup.
 */
if(logged_in()) {
    $username = $_SESSION['activeUsername'];
    $sql = "SELECT * FROM unlocked_products WHERE username = '". $username . "'";
    $result = mysqli_query($conn, $sql);
    $counter = 0;
    while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
        $item_array = array(
                'unlocked_product_id' => $row[2]
        );
        $_SESSION['unlockedList'][$row[2]] = $item_array;
        $counter++;
    }
}
/**
 * If you are coming from the login page rather then original session.
 */
if(isset($_POST['loginusername']) && $_POST['loginpassword']) {
    if(empty($_POST == false)) {
        $username = $_POST['loginusername'];
        $password = $_POST['loginpassword'];
        $_SESSION["loginusername"] = $username;
        $_SESSION["loginpassword"] = $password;
    }
}

if (isset($_GET['reset'])) {
    destroySession();
    exit();
}
function destroySession() {
    session_destroy();
    header("location:" . BASE_URL);
}
?>
<container>
    <div id="header">
        <?php if(isset($_SESSION['activeUsername']))
        { ?>
        <div id="signUp">
            <container>
                <a href="" id="loginBtn" class="loginBtn"> <?php echo "Welcome ". ucfirst($username)?></a>
            </container>
        </div>
            <div id="signUp">
                <container>
                    <a href='<?php echo BASE_URL . "?reset=true"; ?>' id="registerBtn">Log Out</a>
                </container>
            </div>
        <?php } else { ?>
        <div id="signUp">
            <container>
                <a href=<?php echo BASE_URL . "login/";?> id="loginBtn" class="loginBtn">Login</a>
            </container>
        </div>
        <div id="signUp">
            <container>
                <a href="<?php echo BASE_URL; ?>register" id="registerBtn">Register</a>
            </container>
        </div>
        <?php } ?>
        <div id="currentDate">current date</div>
    </div>
    <div id='cssmenu'>
        <ul>
            <li class="active"><a href='<?php echo BASE_URL; ?>'><span>Home</span></a></li>
            <li class='has-sub'><a href='<?php echo BASE_URL; ?>/products'><span>Products</span></a>
                <ul>
                    <?php
                    /**
                     * Fetch all from categories table and display it for products tab.
                     */
                    $counter = 0;
                    $result = mysqli_query($conn,"SELECT * FROM categories");
                    while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {?>
                        <li class='has-sub'><a href='<?php echo BASE_URL;?>products/index.php?filter=<?php echo $row[1] ?>'><span><?php echo $row[1]?></span></a>
                        </li>
                    <?php }?>
                </ul>
            </li>
            <li><a href='<?php echo BASE_URL ?>about'><span>About</span></a></li>
        </ul>
    </div>
</container>
<div class="pageContainer">