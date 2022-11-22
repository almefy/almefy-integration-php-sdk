<div class="container-md">
    <div class="row">

        <!-- Login -->
        <div class="col-sm">
            <div class="card">
                <h2 class="card-title">
                    Login
                </h2>
        
                <form action="/backend/requests/login.php?redirect=/" method="post">
                    
                    <div class="form-group">
                        <label class="required" for="email_login">Email</label>
                        <input class="form-control" required name="email" id="email_login" type="email">
                    </div>
                    
                    <div class="form-group">
                        <label class="required" for="password_login">Password</label>
                        <input class="form-control" required name="password" id="password_login" type="password">
                    </div>
                        
                    <div class="text-right">
                        <input class="btn" type="submit" value="Login">
                    </div>

                    <p class="text-muted">
                        Your regular login solutions work in parallel with Almefy.<br>
                        If you want to increase security we suggest disabling password based auth entirely.
                    </p>
                </form>
                
                <hr class="my-20">
        
                <div class="d-flex justify-content-center">
                    <!-- START ALMEFY INTEGRATION -->
                    
                    <!-- 
                        Place this HTML code wherever the Almefy login code should appear.

                        The data-almefy-api attribute is optional. If you do not set it, Almefy will connect to the live api. 
                    -->
                    <div style="max-width: 140px;" data-almefy-auth
                        data-almefy-api="<?php echo $_ENV["ALMEFY_API"] ?>"
                        data-almefy-key="<?php echo $_ENV["ALMEFY_KEY"] ?>"
                        data-almefy-auth-url="http://localhost:3000/backend/plugins/auth-controller.php">
                    </div>
                    
                    <!-- END ALMEFY INTEGRATION -->
                </div>

                <p class="text-muted">
                    You may put this login code wherever you want.<br>
                    We suggest putting it on a login page or into a drop
                    down in the header, though.
                </p>
            </div>
        </div>
        
        <!-- Register -->
        <div class="col-sm">
            <div class="card">
                <h2 class="card-title">
                    Register
                </h2>
        
                <form action="/backend/requests/register.php?redirect=/" method="post">
                    <div class="form-group">
                        <label class="required">Username</label>
                        <input class="form-control" required name="username" id="username" type="text">
                    </div>
                    <div class="form-group">
                        <label class="required">Email</label>
                        <input class="form-control" required name="email" id="email" type="email">
                    </div>
                    <div class="form-group">
                        <label class="required">Password</label>
                        <input class="form-control" required name="password" id="password" type="password">
                    </div>
                    <div class="text-right">
                        <input class="btn" type="submit" value="Register">
                    </div>
                </form>

                <p class="text-muted">
                    Almefy works without interfering with your usual onboarding process, but is fully capable of replacing it.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Almefy specific "password reset" replacement / addition -->
    <div class="col-sm"> <!-- w-400 = width: 40rem (400px), mw-full = max-width: 100% -->
        <div class="card">
            <h2 class="card-title">
                Reconnect Almefy
            </h2>
            <p class="text-muted">
                Lost phone or accidentally deleted your access?<br>
                Send an email to reconnect your device!
            </p>
    
            <!-- START ALMEFY INTEGRATION -->
    
            <form action="/backend/plugins/almefy_reconnect.php?redirect=/" method="post">
                <div class="form-group">
                    <label class="required" for="email_reconnect">Email</label>
                    <input class="form-control" type="email" name="email" id="email_reconnect" required>
                </div>
                <div class="text-right">
                    <input class="btn" type="submit" value="Send">
                </div>
            </form>
    
            <!-- END ALMEFY INTEGRATION -->
    
            <p class="text-muted">
                Instead of using this to just reconnect almefy, you could use it to
                create an account without a password.<br>

                Example at <code class="code">src/backend/plugins/MyAlmefyPlugin.php</code>, <code class="code">function send_connect_device_email()</code>.
            </p>
      </div>
    </div>
</div>