<?php
/**
 * Created by PhpStorm.
 * User: akith
 * Date: 12/2/2018
 * Time: 11:56 AM
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_GET['action'])) {
        if ($_GET['action'] == 'remove') {
            $item_Id = $_GET['id'];
            if (count($_SESSION['cart']) == 1) {
                unset($_SESSION["cart"]);
            } else {
                unset($_SESSION["cart"][$_GET['id']]);
            }
            header('Location: ' . BASE_URL . 'checkout');
        }
    }
}
if(isset($_POST['checkout'])) {
    if(isset($_POST['check'])) { //agreed and now processing

        //completed checkout form successfully
        echo 'I want to add product id: ';
        foreach($_SESSION['cart'] AS $product) {
            echo $product['product_id'] . ' ';
        }
        echo ' to the list.<br>';
        //this is for checkout completion and unlocked products
        foreach($_SESSION['cart'] AS $product) {
            $data = $product['product_id'];
            $cartUser = $_SESSION['activeUsername'];
            $sql= "SELECT username FROM unlocked_products WHERE (owns_product_id = '$data' AND username = '$cartUser')";
            $checkSQL = mysqli_query($conn, $sql);
            if(mysqli_num_rows($checkSQL) == 1){//owns product already
                $row = mysqli_fetch_assoc($checkSQL);
                echo $cartUser. ' owns product ' . $data ." id<br>";
            } else {
                echo 'I dont own '. $data . '<br>';

                //add unlcoked product to databse and update the unlocked product list created in index.php
                $register_data = array(
                    'username' => $cartUser,
                    'owns_product_id' => $data
                );

                $fields = '' . implode(',', array_keys($register_data)) . '';
                $data = '\'' . implode('\', \'', $register_data) . '\'';
                $result = mysqli_query($conn, "INSERT INTO unlocked_products ($fields) VALUES ($data)");

            }
        }
        unset($_SESSION['cart']);
        header("location:" . BASE_URL . '/products');

        //check if username owns product id in table
        //else
        //put product id owned to username to table.

    } else {
        echo 'You must agree to term <br>';
    }
}
?>
<center>
    <div class="mainContainer">
        <section class="section-info py-5" style="background-color: #2c2c2c;">
            <div class="content">
                <div class="container">
                    <script type="text/javascript" src="https://www.paypalobjects.com/api/checkout.js"></script>

                    <div class="row my-5">
                        <div class="col-sm-12 col-lg-8 col-xl-6 offset-lg-2 offset-xl-3">
                            <div id="postdata"></div>
                            <div class="card" style="background-color: #1f1f1f; padding-bottom: 50px">
                                <div class="card-body">
                                    <h4 class="card-title">My Cart</h4>
                                    <p class="card-text">
                                        <?php if(isset($_SESSION['cart'])) {?>
                                            Review your items below before finalizing checkout. All purchases are final! If you need help,
                                            contact me through email, <strong>@ amandani@ramapo.edu</strong>
                                        <?php } else { ?>
                                            Your cart is empty.
                                        <?php } ?>
                                    </p>

                                    <?php if(isset($_SESSION['cart'])) {?>
                                    <p>Upon completion you will be able to go to the products page and download purchased files.</p>
                                    <div id="msg" class="alert alert-danger d-none">
                    You must agree with our Terms &amp; Conditions before making
                </div><?php }?>
                                </div>

                                <div class="list-group list-group-flush">

                                    <?php
                                      if(isset($_SESSION['cart'])) {?>
                                    <form action="" method="post">
                                        <input type="hidden" id="hidden_item" name="hidden_item" value="checkout_item">
                                        <?php
                                            $counter = 0;
                                            foreach($_SESSION['cart'] AS $product) { ?>
                                        <div class="list-group-item" style="line-height: 1em; background-color: #292929;">
                                            <img src="<?php echo $product['img_url']; ?>" class="float-left mr-3" height="50px">                    <div class="float-right mt-2">
                                                <button formmethod="post" formaction="<?php echo BASE_URL; ?>checkout/index.php?action=remove&id=<?php echo $counter; ?>" type="submit" name="remove" value="Remove from Cart" class="btn btn-info btn-sm float-right">
                                                    <i class="fal fa-trash-alt"></i>
                                                </button>
                                            </div>
                                            <span class="text-info"><?php echo $product['item_name']; ?></span><br>
                                            <div class="text-muted small text-truncate" style="max-width:300px;">
                                                <?php echo $product['product_desc']; ?>                    </div>
                                            <div class="text-muted small">
                                                Price: <strong>$<?php echo number_format($product['item_price'], 2)?></strong>
                                            </div>
                                        </div><?php $counter++; } ?>

                                        <div class="list-group-item text-center" style="background-color: #1f1f1f">

                                            <input class="" type="checkbox" id="check" name="check">
                                            <label for="customCheck2"> I agree to the
                                                <a href="/pages/terms" class="text-info" target="_blank">Terms &amp; Conditions</a></label>


                                            <div id="paypal-button-container">
                                                <button type="submit" name="checkout" value="Checkout" class="btn btn-info badge-pill btn-block px-4">Checkout</button>
                                            </div>


                                        </div>
                                    </form>
                                    <?php } ?>


                                </div>

                            </div>
                        </div>
                    </div>

                    <script>
                        var msg = $('#msg');
                        var check = $('#check');

                        function isValid() {
                            return document.querySelector('#check').checked;
                        }

                        function onChangeCheckbox(handler) {
                            document.querySelector('#check').addEventListener('change', handler);
                        }

                        function toggleValidationMessage() {
                            if (isValid()) {
                                $('#msg').addClass("d-none");
                            } else {
                                $('#msg').removeClass("d-none");
                            }
                        }

                        function toggleButton(actions) {
                            return isValid() ? actions.enable() : actions.disable();
                        }

                        var buttonData = {
                            env: 'production',
                            style: {
                                label: 'checkout',
                                size:  'large', // small | medium | large | responsive
                                shape: 'pill',  // pill | rect
                                color: 'blue',  // gold | blue | silver | black
                                tagline: true,
                                branding: 'Foxtrot Studios'
                            },

                            client: {
                                sandbox:    'AVco-z4Ocwx1zgqJumpjuFa0AqtswpH2eLesH1WypPIpVgGLlQfuA3APkYnLrDpYW7fXKp1FwmMSmbHv',
                                production: 'ASeEG9hYnutNPCwsIMS9XVHnZmh5vzpZIpo87d87hnaXSTUSSgukUoAhXw95NMJjCu1LsfGpnAMtsq1F'
                            },

                            validate: function(actions) {
                                toggleButton(actions);

                                onChangeCheckbox(function() {
                                    toggleButton(actions);
                                });
                            },

                            onClick: function() {
                                toggleValidationMessage();
                            },

                            funding: {
                                disallowed: [ paypal.FUNDING.CARD, paypal.FUNDING.ELV ]
                            },

                            commit: true,

                            payment: function(data, actions) {
                                return actions.payment.create({
                                    payment: {
                                        "transactions": [{
                                            "amount": {
                                                "total"     : "60",
                                                "currency"  : "USD",
                                                "details"   : {
                                                    "subtotal": "60"
                                                }
                                            },
                                            "item_list": {
                                                "items": [
                                                    {
                                                        'name'      : 'Hiscores',
                                                        'sku'       : '4',
                                                        'price'     : '15',
                                                        'currency'  : 'USD',
                                                        'quantity'  : 1,
                                                        'description' : 'Hiscores with search, compare, modes, and player cards.'
                                                    },{
                                                        'name'      : 'Store v2',
                                                        'sku'       : '7',
                                                        'price'     : '20',
                                                        'currency'  : 'USD',
                                                        'quantity'  : 1,
                                                        'description' : 'A store integrated with PayPal for selling your digital items.'
                                                    },{
                                                        'name'      : 'Zamorak',
                                                        'sku'       : '3',
                                                        'price'     : '10',
                                                        'currency'  : 'USD',
                                                        'quantity'  : 1,
                                                        'description' : 'A dark themed homepage perfect for beginners.'
                                                    },{
                                                        'name'      : 'FoxVote',
                                                        'sku'       : '8',
                                                        'price'     : '15',
                                                        'currency'  : 'USD',
                                                        'quantity'  : 1,
                                                        'description' : 'Voting script that gives a reward for every vote.'
                                                    },                            ]
                                            },
                                            "description"   : "Foxtrot Studios",
                                            "custom"        : "aki2k4"
                                        }],
                                        note_to_payer: 'Contact me on Discord (OG KingFox#1019) for any questions on your order.'
                                    },
                                    experience: {
                                        input_fields: {
                                            no_shipping: 1
                                        }
                                    }
                                });
                            },

                            onAuthorize: function(paymentData, actions) {
                                return actions.payment.get().then(function(details) {
                                    var transaction = details.transactions[0];
                                    var username    = transaction.custom;

                                    if (!username.trim()) {
                                        $('#postdata').html("<div class=\"alert alert-danger\">Your session has expired.</div>");
                                        return false;
                                    }

                                    return actions.payment.execute().then(function(response) {
                                        $.post("/checkout/process", {
                                            postdata: response
                                        }).done(function(data) {
                                            var transId = response.transactions[0].related_resources[0].sale.id;
                                            window.location.replace("/checkout/details/" + transId);
                                        });
                                    });
                                })
                            },

                            onError: function(err) {
                                alert(err);
                            },

                            onCancel: function(data, actions) {
                                $('#result').html("<div class=\"alert alert-danger\">You have cancelled this transaction.</div>");
                            }
                        };
                    </script>

                </div>
            </div>
        </section>
    </div>
</center>