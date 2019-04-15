<?php
require_once( "../php/config.php");
include (ROOT_PATH . "db/init.php");
include (ROOT_PATH . "login/user.php");
include (ROOT_PATH . "general.php");
if(!isset($_SESSION))
{
    session_start();
}

include(ROOT_PATH . "/php/head.php");
include(ROOT_PATH . "/php/headerNav.php");

$userNameErr = "";
$userName = "";
$password = "";
$incorrectPassError = "";

$completed = [false, false]; // steps are fully complete
$formComplete = false; //if all steps complete form is done

/**
 * simple login form validations.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["loginusername"])) {
        $userNameErr = "Username cannot be empty.";
    }
    if (user_exists($conn, $username) == false) {

        $userNameErr = "Username does not exist.";
    } else if(user_exists($conn, $username) == true) {
        $completed[0] = true;
        if(loginSuccuess($conn, $username, $_POST["loginpassword"]) == false) {
            $userNameErr = 'Incorrect password or username';
        } else {
            //set usersession redirect to home
            $_SESSION['activeUsername'] = $username;
            $userNameErr = 'Welcome back '. $_SESSION['activeUsername'] ;
            $formComplete = true;
            header("location:" . BASE_URL);
            exit();
        }
    }
    if($completed[0] == true && $completed[1] == true) {
        $formComplete = true;
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

<center>
    <div class="mainContainer">

        <section class="section-info py-5" style="background-color: #2c2c2c;">
            <div class="col-sm-12 col-xl-4 offset-xl-0">
                <div class="card">
                    <div class="card-body">

                        <div class="text-center">
                            <h3>Member Login</h3>
                            <p class="small text-secondary">Log in to your account</p>
                            <div class="small-divider mb-5"></div>
                        </div>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="user-addon"><i class="far fa-user-circle fa-fw"></i></span>
                                </div>
                                <input type="text" id="loginusername" name="loginusername" class="form-control" aria-describedby="user-addon" aria-label="Username" placeholder="Username">                </div>


                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="password-addon"><i class="fas fa-lock fa-fw"></i></span>
                                </div>
                                <input type="password" id="loginpassword" name="loginpassword" class="form-control" aria-describedby="password-addon" aria-label="Password" placeholder="Password">                </div>

                            <div class="text-right small mt-2">
                                <a href="/recover" class="text-info">Forgot Password</a>                </div>

                            <input type="hidden" id="QjdGWENmdHlrYnlHaStGMVA0aEk4UT09" name="QjdGWENmdHlrYnlHaStGMVA0aEk4UT09" value="dmFmdklrN2c1SE5iQjdiTHRVeGpJdz09">
                            <div class="form-group mt-4">
                                <input type="submit" value="Sign In" class="btn btn-info btn-block">
                                <div class="text-center small mt-2">
                                    <a href="../register" class="text-info">Create an Account</a>

                                    <p id="errormsg1" class="text-info"><?php echo ($userNameErr == "" ? "" : $userNameErr);?></p>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</center>

<?php
include(ROOT_PATH . "/php/footer.php");
?>