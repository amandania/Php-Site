<script src="<?php echo BASE_URL; ?>/register/register.js" rel="script"> </script>

<?php
$userNameErr = "";
$userName = "";
$password = "";
$passError = "";
$email = "";
$emailError = "";

$completed = [false, false, false]; // steps are fully complete
$formComplete = false; //if all steps complete form is done

/**
 * Register submit validations.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $userNameErr = "Username is required";
    } else if (strlen($_POST["username"])  < 4) { // lets also check if our name is less then a certain character count.
        $userNameErr = "Name must be more then 3 letters.";
    } else {
        $userName = test_input($_POST["username"]);
        if(user_exists($conn, $userName)) {
            $userNameErr = "Username already exists. Please choose another.";
        } else {
            $completed[0] = true;
        }

    }
    if (empty($_POST["password"])) {
        $passError = "Password is required";
    }else if (strlen($_POST["password"])  < 4) { // lets also check if our name is less then a certain character count.
        $userNameErr = "Password must be more then 3 letters.";
    } else {
        $password = test_input($_POST["password"]);
        $completed[1] = true;
    }
    if (empty($_POST["email"])) {
        $emailError = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "Invalid email";
        } else {
            $completed[2] = true;
        }
    }
    if($completed[0] == true && $completed[1] == true && $completed[2] == true) {
        $formComplete = true;
        register_account($conn, $userName, $password, $email);
    }
}

/**
 * @param $conn
 * @param $username
 * @param $password
 * @param $email
 * This function adds the account to the database
 */
function register_account($conn, $username, $password, $email) {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $register_data = array(
        'username' => $username,
        'password' => md5($password),
        'email' => $email,
        'registerd_ip' => ($ip=='::1') ? "localhost" : $ip,
        'logged_inIP' => ($ip=='::1') ? "localhost" : $ip
    );

    $fields = '' . implode(',', array_keys($register_data)) . '';
    $data = '\'' . implode('\', \'', $register_data) . '\'';
    $result = mysqli_query($conn, "INSERT INTO users ($fields) VALUES ($data)");
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
                            <h3><?php echo ($formComplete == true ? "Thanks for signing up!" : "Create an Account");?></h3>
                            <p class="small text-secondary"><?php echo ($formComplete == true ? "You have successfully created an account with us! ": "Create a new profile with us to make purchases, participate in the forums, and much more!")?></p>
                            <div class="small-divider mb-5"></div>
                        </div>
                        <?php
                          if (!isset($formComplete) || !$formComplete) {
                        ?>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="myRegForm">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="user-addon"><i class="fal fa-user-circle fa-fw"></i></span>
                                </div>
                                <input type="text" id="username" value="<?php echo $userName;?>" name="username" class="form-control" aria-describedby="user-addon" aria-label="Username" placeholder="Username">                </div>

                            <div class="small text-muted mb-3" id="underUser">
                                 Username must be more then 4 characters
                             </div>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="password-addon"><i class="fal fa-lock fa-fw"></i></span>
                                </div>

                                <input type="password" id="password" name="password" class="form-control" aria-describedby="password-addon" aria-label="Password" placeholder="Password">                </div>

                            <div class="small text-muted mb-3">Password must more then 4 characters</div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="email-addon"><i class="fal fa-at fa-fw"></i></span>
                                </div>
                                <input type="text" id="email" name="email"  class="form-control" aria-describedby="email-addon" aria-label="Email Address" placeholder="Email Address"  value="<?php echo $email;?>" >                </div>

                            <input type="hidden" id="dk1lNjdrNERQbE5NMHFyNzJzdUhOUT09" name="dk1lNjdrNERQbE5NMHFyNzJzdUhOUT09" value="RGRGaEw2RXNNU1RaNlliT1VTSXJuZz09">
                            <div class="form-group">
                                <input type="submit" value="Create" class="btn btn-info btn-block">
                                <div class="text-center small mt-2">
                                    <a href="../login" class="text-info">I already have an account</a>
                                    <p id="errormsg1" class="text-info"><?php echo ($userNameErr == "" ? "" : $userNameErr);?></p>
                                    <p id="errormsg2" class="text-info"><?php echo ($passError == "" ? "" : $passError);?></p>
                                    <p id="errormsg3" class="text-info"><?php echo ($emailError == "" ? "" : $emailError);?></p>
                                </div>
                            </div>
                        </form>
                        <?php
                          }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</center>