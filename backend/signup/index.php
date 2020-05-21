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
                <h4>ADMIN AREA | ACCOUNT SIGN UP</h4>
            </div>
        </header>

        <div class="signin-contents">

            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <form action="" class="well" id="sign-up-form">
                            <div class="submit-errors text-danger"></div>
                            <div class="form-group">
                                <label for="">Username</label>
                                <input type="text" name="username" id="username" class="form-control"
                                    placeholder="Enter Username" aria-describedby="helpId" />

                            </div>
                            <div class="form-group">
                                <label for="">Email Address</label>
                                <input type="text" name="email" id="email" class="form-control"
                                    placeholder="Enter Email" aria-describedby="helpId" />

                            </div>
                            <div class="form-group">
                                <label for="Password">Security code</label>
                                <input type="password" name="security-code" id="security-code" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="Password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="Password">Confirm Password</label>
                                <input type="password" name="confirm-password" id="confirm-password"
                                    class="form-control" />
                            </div>
                            <button type="submit" class="btn-block btn btn-default ">
                                Sign Up
                            </button>
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
    <script src="../js/signup.js"></script>

</body>

</html>