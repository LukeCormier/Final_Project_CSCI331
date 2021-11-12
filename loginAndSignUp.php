<?php
require_once 'header.php';

echo <<<_END
<script>
    function checkUser(user) {
        if (user.value == '') {
            $('#used').html('&nbsp;');
            return;
        }

        $.post('checkuser.php', { user : user.value }, function(data) {
            $('#used').html(data)
        });
    }
</script>  
_END;

$error = $user = $pass = "";
if (isset($_SESSION['user'])) 
    destroySession();

if (isset($_POST['user'])) {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);
    
    if ($user == "" || $pass == "")
        $error = 'Not all fields were entered<br><br>';
    else {
        $result = queryMysql("SELECT * FROM members WHERE user='$user'");

        if ($result->num_rows)
            $error = 'That username already exists<br><br>';
        else {
            queryMysql("INSERT INTO members VALUES('$user', '$pass')");
            die('<h4>Account created</h4>Please Log in.</div></body></html>');
        }
    }
}

echo <<<_END
    <ul>
        <li data-tab-target = "signUp">Sign Up</li>
        <li data-tab-target = "login">Login</li>
    </ul>

    <div class="tab-content">
        <div id = "signUp" data-tab-content>
            <form method='post' action='signup.php'>$error
                <div data-role='fieldcontain'>
                    <label></label>
                    <h3>Create username and password</h3>
                </div>
                <div data-role='fieldcontain'>
                    <label>Username</label>
                    <input type='text' maxlength='16' name='user' value='$user' onBlur='checkUser(this)'>
                    <label></label>
                    <!-- <div id='used'>&nbsp;</div> -->
                </div>
                <div data-role='fieldcontain'>
                    <label>Password</label>
                    <input type='text' maxlength='16' name='pass' value='$pass'>
                </div>
                <div data-role='fieldcontain'>
                    <label></label>
                    <input data-transition='slide' type='submit' value='Sign Up'>
                </div>
            </form>
        </div>
        <div id = "login" data-tab-content>
            <form method='post' action='login.php'>
                <div data-role='fieldcontain'>
                    <label></label>
                    <span class='error'>$error</span>
                </div>
                <div data-role='fieldcontain'>
                    <label></label>
                    <h3>Enter username and password</h3>
                </div>
                <div data-role='fieldcontain'>
                    <label>Username</label>
                    <input type='text' maxlength='16' name='user' value='$user'>
                </div>
                <div data-role='fieldcontain'>
                    <label>Password</label>
                    <input type='password' maxlength='16' name='pass' value='$pass'>
                </div>
                <div data-role='fieldcontain'>
                    <label></label>
                    <input data-transition='slide' type='submit' value='Login'>
                </div>
            </form>
        </div>
    </div>
_END;
  require_once 'footer.php';
?>
