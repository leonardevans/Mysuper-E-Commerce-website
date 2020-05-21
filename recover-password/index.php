<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Mysuper-Login</title>
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
 <link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500&display=swap" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css?family=Indie+Flower|Oswald:300,400,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css" />
</head>

<body>
    <nav id="nav-bar">
        <a href="../" style="text-decoration: none;">
            <h2 class="logo">My<span style="color:#fff;">Super</span></h2>
        </a>
    </nav>
    <div id="page-in">
        <h4>MySuper | Recover Password</h4>
    </div>
    <section id="container">
        <div class="login-container">
            <form action="" class="login-form" id="recover-form">
                <div class="recover-error"></div>
                <table class="login-table">
                    <tr>
                        <td>Email Address</td>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" name="email" placeholder="example@mail.com" id="email" />
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <button type="submit" class="btn login-btn">
                                Email me a password
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><a href="../login/" class="recover-password">Login</a></td>
                    </tr>
                    <tr>
                        <td>
                            <a href="../register/" class="create-account">Sign Up</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </section>
    <!--Footer-->
    <footer id="footer">
        <section class="top-bar">
            <a href="../contact/">Contact us</a>
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
    <script src="../js/jquery.js"></script>
    <script src="../js/recover_password.js"></script>
</body>

</html>