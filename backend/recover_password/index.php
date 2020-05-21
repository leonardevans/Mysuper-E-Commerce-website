<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MySuper | Admin</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/signin.css">
    <link href='https://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
</head>

<body>
    <div class="container-div">
        <header>
            <div class="nav-top">
                <div class="brand">
                    <h4 class="logo">My<span style="color:#fff;">Super</span></h4>
                </div>

            </div>
            <div class="nav-bottom">
                <h4>ADMIN AREA | RECOVER PASSWORD</h4>
            </div>
        </header>

        <div class="signin-contents">

            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <form action="" class="well" id="recover-form">
                            <div class="recover-error"></div>
                            <div class="form-group">
                                <label for="">Email Address</label>
                                <input type="text" name="email" id="email" class="form-control"
                                    placeholder="Enter Email" aria-describedby="helpId" />

                            </div>

                            <button type="submit" class="btn-block btn btn-default ">
                                Email Me Password
                            </button>
                            <p><a href="../signup/">Sign Up</a></p>
                            <p><a href="../">Sign In</a></p>

                        </form>
                    </div>
                </div>
            </div>
            <div class="demo-div"></div>
        </div>
        <footer>
            <p>MySuper &copy;2019 - <?php echo date("Y");?>. All Rights Reserved.</p>
        </footer>
    </div>

    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/recover_password.js"></script>

</body>

</html>