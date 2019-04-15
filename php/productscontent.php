<?php
if(isset($_SESSION['activeUsername'])) {
    //used to remove products from cart.
    if(isset($_POST['cart_item_id']) && isset($_SESSION['cart'])) {
        $item_Id = $_GET['id'];
        if(count($_SESSION['cart']) == 1) {
            unset($_SESSION["cart"]);
        } else {
            unset($_SESSION["cart"][$_GET['id']]);
        }
        header('Location: '.BASE_URL.'products');
    }
    //post request check for add button
    if (isset($_POST['add'])) {
        //check if u are adding to existing cart else add to new cart as index 0
        if (isset($_SESSION['cart'])) {
            $item_array_id = array_column($_SESSION['cart'], "product_id");
            if (!in_array($_GET['id'], $item_array_id)) {
                $count = count($_SESSION['cart']);
                //item object to be sent to cart which is used in checkout page.
                $item_array = array(
                    'product_id' => $_GET['id'],
                    'item_name' => $_POST['hidden_name'],
                    'item_price' => $_POST['hidden_price'],
                    'img_url' => $_POST['hidden_imgurl'],
                    'product_desc' => $_POST['hidden_desc']
                );
                $_SESSION['cart'][$count] = $item_array;
            }
        } else {
            //adds to new cart at index 0
            $item_array = array(
                'product_id' => $_GET['id'],
                'item_name' => $_POST['hidden_name'],
                'item_price' => $_POST['hidden_price'],
                'img_url' => $_POST['hidden_imgurl'],
                'product_desc' => $_POST['hidden_desc']
            );
            $_SESSION['cart'][0] = $item_array;
        }
        header('Location: '.BASE_URL.'products');
    }
}
?>
<center>
    <div class="mainContainer">
    <section class="section-info py-5" style="background-color: 2c2c2c">
            <?php
            if(!isset($_SESSION['activeUsername'])) {?>
                <div class="cartErrorAlert" style="color: #16a2b8;background-color: #1d1a1a;border: 1px solid transparent;border-radius: .75rem;border-color: ##2e7a86; height: 30px">
                  You need to login before adding to cart.</div>
            <?php } ?>
    <div class="row py-5">
        <div class="col-sm-12 col-lg-4 col-xl-3">
            <div class="list-group list-group-flush mb-3" id="sidebar">

                <a href="<?php echo BASE_URL; ?>products" class="list-group-item text-info" style="background-color: #1d1a1a">All Items</a>

                <?php
                $sql_user = "SELECT COUNT(*) FROM products WHERE (categories = 'template' OR categories = 'templates')";
                $result1 = mysqli_query($conn,$sql_user);
                $row1 = $result1->fetch_row();

                $sql_user2 = "SELECT COUNT(*) FROM products WHERE (categories = 'script' OR categories = 'scripts')";
                $result3 = mysqli_query($conn,$sql_user2);
                $row3 = $result3->fetch_row();
                ?>
                <a rel="no-follow" href="<?php echo BASE_URL; ?>products/index.php?filter=Template" class="list-group-item text-info " style="background-color: #1d1a1a" id="mylink">Templates<span class="badge badge-info float-right mt-1"><?php echo $row1['0']; ?></span></a>
                <a rel="no-follow" href="<?php echo BASE_URL; ?>products/index.php?filter=Script" class="list-group-item text-info " style="background-color: #1d1a1a">Scripts<span class="badge badge-info float-right mt-1"><?php echo $row3['0']; ?></span></a>
            </div>
            <div class="list-group list-group-flush">
                <?php if(!isset($_SESSION['cart'])) { ?>
                  <div class="list-group-item text-center" style="background-color: #1d1a1a">
                    <div id="text" class="list-group-item text-info" style="background-color: #1d1a1a">Your cart is empty</div>
                  </div>
                <?php } else { ?>
                    <form action="" method="post">
                        <input type="hidden" id="cart_item" name="cart_item" value="cart_item">

                        <?php if (!empty($_SESSION['cart'])) {
                            $counter = 0;
                            $total = 0;
                            foreach ($_SESSION["cart"] as $key => $value) {?>
                                <div class="list-group-item" style="line-height: 1em; background-color: #1d1a1a;">
                                    <div class="float-right">
                                        <button formaction="<?php echo BASE_URL; ?>products/index.php?action=remove&id=<?php echo $counter; ?>" type="submit" name="remove" value="Remove from Cart" class="btn btn-info btn-sm float-right">
                                            <i class="fal fa-trash-alt"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="cart_item_id" value="<?php echo $value['product_id']; ?>">
                                    <span class="text-info"><?php echo $value['item_name']; ?></span><br>
                                    <small class="text-muted">Price: $<?php echo $value['item_price']; ?></small>
                                </div>
                            <?php
                                $counter++;
                                $total += $value['item_price']; } ?>
                        <?php } ?>


                        </form>



                        <div class="list-group-item text-center" style="background-color: #1d1a1a;">
                            <div class="mb-2">
                                Total: <strong class="text-info">$<?php echo number_format($total,2);?></strong>
                            </div>
                            <a href="<?php echo BASE_URL; ?>checkout" class="btn btn-info badge-pill btn-block px-4">Checkout</a>        </div>
                <?php } ?>
            </div>
        </div>

        <div class="col-sm-12 col-lg-8 col-xl-9">
            <div class="row" style="margin-left: -30px; margin-right: -5px; margin-top: -40px; padding-bottom: 65px;">
                 <?php
                    if (!isset($_GET['filter']) || $_GET['filter'] != 'Template' && $_GET['filter'] != 'Script') {
                    ?>
                <?php
                $counter = 0;
                $result = mysqli_query($conn,"SELECT id, title, price, image, description FROM products");
                while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) { ?>
                    <div class="col-sm-12 col-xl-4">
                        <form class="shopping_form" method="post" action="<?php echo BASE_URL; ?>products/index.php?action=add&id=<?php echo $row[0] ?>">
                            <div class="card mb-3 aos-init aos-animate" data-aos="fade-down" data-aos-delay="0">
                                <img src="<?php echo $row[3]; ?>" class="card-img-top img-fluid">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <a id="product_name" name="product_name" href=""  class="text-info card-title mb-3"><?php echo $row[1]; ?>
                                            <span class="badge float-right text-dark badge-pill px-2 mt-1" name="hidden_price" type="hidden">$<?php echo $row[2]; ?></span></a>
                                        <input type="hidden" name="hidden_name" value="<?php echo $row[1]; ?>">
                                        <input type="hidden" name="hidden_imgurl" value="<?php echo $row[3]; ?> ?>">
                                        <input type="hidden" name="hidden_price" value="<?php echo $row[2]; ?>">
                                    </div>
                                    <p class="card-text small"><?php echo $row[4]; ?></p>
                                    <input type="hidden" name="hidden_desc" value="<?php echo $row[4]; ?>">
                                        <hr>
                                        <div class="small text-muted">
                                            <div class="float-right">
                                                <?php if(isset($_SESSION['activeUsername']))
                                                { ?>
                                                    <?php if(isset($_SESSION['unlockedList'][$row[0]])) {?>
                                                        <button type="submit" name="download" value="download" class="btn btn-info btn-sm badge-pill px-3">Download</button>
                                                    <?php } else { ?>
                                                        <button type="submit" name="add" value="Add to Cart" class="btn btn-info btn-sm badge-pill px-3">Add to Cart</button>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <button type="submit" name="add" value="Add to Cart" class="btn btn-info btn-sm badge-pill px-3 disabled">Add to Cart</button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </form>
                          <?php
                            $counter++;
                            if($counter % 3 == 0) {
                              echo("</div><div class=\"row\" style=\"margin-left: -30px; margin-right: -5px; margin-top: -42px;\">");
                            }
                          ?>
                        </div>
                <?php } ?>

                        <?php
                    } else if(isset($_GET['filter'])) {?>
                        <?php
                            $counter = 0;
                            $result = mysqli_query($conn,"SELECT id, title, price, image, description FROM products WHERE categories = '".$_GET['filter']."'");
                            while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) { ?>
                                <div class="col-sm-12 col-xl-4">
                                    <form class="shopping_form" method="post" action="<?php echo BASE_URL; ?>products/index.php?action=add&id=<?php echo $row[0] ?>">

                                    <div class="card mb-3 aos-init aos-animate" data-aos="fade-down" data-aos-delay="0">
                                        <img src="<?php echo $row[3]; ?>" class="card-img-top img-fluid">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <a href="" class="text-info card-title mb-3"><?php echo $row[1]; ?>
                                                <span class="badge float-right text-dark badge-pill px-2 mt-1">$<?php echo $row[2]; ?></span></a>
                                                <input type="hidden" name="hidden_name" value="<?php echo $row[1]; ?>">
                                                <input type="hidden" name="hidden_price" value="<?php echo $row[2]; ?>">
                                            </div>
                                            <p class="card-text small"><?php echo $row[4]; ?></p>
                                            <input type="hidden" name="hidden_desc" value="<?php echo $row[4]; ?>">
                                            <hr>
                                            <div class="small text-muted">
                                                <div class="float-right">
                                                    <?php if(isset($_SESSION['activeUsername']))
                                                    { ?>
                                                        <?php if(isset($_SESSION['unlockedList'][$row[0]])) {?>
                                                        <button type="submit" name="download" value="download" class="btn btn-info btn-sm badge-pill px-3">Download</button>
                                                    <?php } else { ?>
                                                        <button type="submit" name="add" value="Add to Cart" class="btn btn-info btn-sm badge-pill px-3">Add to Cart</button>
                                                    <?php } ?>
                                                    <?php } else { ?>
                                                        <button type="submit" name="add" value="Add to Cart" class="btn btn-info btn-sm badge-pill px-3 disabled">Add to Cart</button>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                    <?php
                                    $counter++;
                                    if($counter % 3 == 0) {
                                        echo("</div><div class=\"row\" style=\"margin-left: -30px; margin-right: -5px; margin-top: -42px;\">");
                                    }
                                    ?>
                                </div>
                            <?php } ?>
                    <?php } ?>
            </div>
        </div>

    </div>
    </section>
    </div>
</center>
<script>
    $('a#product_name').click(function(){ console.log("test"); })
</script>