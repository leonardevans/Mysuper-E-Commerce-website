<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>home</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Indie+Flower|Oswald:300,400,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css" />
    <link rel="stylesheet" href="../css/dropdown.css">
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="../css/products.css">
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="../css/scrollBtn.css">
    <link rel="stylesheet" href="../css/contact.css">

    <script src="../js/jquery.js"></script>

</head>

<body>
    <span id="toTop" style="display: none;"></span>
    <header>
        <nav id="nav-bar">
            <a href="../" style="text-decoration: none;">
                <h2 class="logo">My<span style="color:#fff;">Super</span></h2>
            </a>
            </a>

        </nav>


    </header>


    <section class="contact">
        <h2>Contact Us</h2>

        <p>
            <span> <i class="fa fa-phone"></i> +254716077601</span> <br /><br />
            <span><i class="fa fa-whatsapp"></i> +254716077601 </span>
        </p>

        <div class="container">
            <form action="" method="POST" id="contact-form">
                <input type="text" name="name" id="name" placeholder="NAME" />

                <input type="text" name="email" id="email" placeholder="EMAIL" />

                <input type="text" name="subject" id="subject" placeholder="SUBJECT" />

                <textarea name="message" id="message" cols="30" rows="10" style="height:200px"
                    placeholder="MESSAGE..."></textarea>

                <input type="submit" value="send message" />
                <p class="result"></p>
            </form>
        </div>
    </section>
    </section>

    <!--Footer-->
    <footer id="footer">
        <section class="top-bar">
            <a href="./">Contact us</a>
        </section>
        <section class="middle-bar">
            <a href="https://facebook.com/evans.dranoel" target="_black"><i class="fa fa-facebook"></i></a>
            <a href="https://twitter.com" target="_black"><i class="fa fa-twitter"></i></a>
            <a href="https://github.com/leonardevans" target="_black"><i class="fa fa-github"></i></a>
        </section>
        <section class="bottom-bar">
            <p>MySuper &copy;2019 - <?php echo date("Y");?>. All Rights Reserved.</p>
        </section>
    </footer>
    <!--End of footer-->



    <span id="scrollTop"><i class="fas fa-chevron-up"></i></span>

    <script src="../js/scrollBtn.js"></script>
    <script src="../js/contact.js"></script>
</body>

</html>