<!-- 
    This is an example of how to connect new devices from withing your app / website.
    You may use the same technique to send custom email templates containing the Almefy Connect Code.

    NOTE: In a future release we will add a "Connect Device" widget with the Javascript sdk.
    Implementation will be as easy as adding the login qr code!
-->

<?php if(!$backend->is_logged_in()): ?>
    <div>You must be logged in to connect new 2-factor devices.<div>
<?php else: ?>
    <?php
        $user = $backend->get_current_user();
        $identifier = $user->email;

        $enrollment_token = null;
        // Consider adding a placeholder img for error scenarios.
        $src = '';
        $connect_url = '';
        try {
            $enrollment_token = $almefy->client->enrollIdentity($identifier, ['sendEmail' => false]);

            // Get the base64 encoded enrollment qr code img
            $img = $enrollment_token->getBase64ImageData();
            // Format to display in img tag
            $src = "data:image/png;base64,$img";

            $token_id = $enrollment_token->getId();
            // If an user clicks this link on a mobile device, it will open the
            // Almefy app and prompt the user to connect the device.
            // This is necessary, as users can't scan their own smartphone. 
            $connect_url = "https://app.almefy.com/?c=$token_id";
        } catch (\Throwable $e) {
            // return "<div style='margin: auto;'>" . __("Could not create qr code. Please try again later.", 'almefy-me') . "</div>";
            echo "There was an error connecting to Almefy. ".$e->getMessage();
        }
    ?>
        <style>
            /* This would probably live in a css file. It is used to hide the qr code until user interaction. */
            .blurred {
                filter: blur(8px);
            }
        </style>

    <div class="col-sm">
        <div class="card">
            <h2 class="card-title">
                Manage your 2-factor devices
            </h2>

            <div class="d-flex" style="gap: 2rem;">
                <div class="w-150">
                    <!-- Optionally hide on mobile devices. -->
                    <!-- Wrapping the qr code with the connect url is good practice if you decide show the code on mobile. -->
                    <a href="<?php echo $connect_url ?>">
                        <img id="almefy-connect-code-img" class="img-fluid border blurred" src="<?php echo $src?>" alt="Connect Device QR Code">
                    </a>
                    <!-- Optionally only show this on mobile devices -->
                    <div class="text-center">
                        <button id="almefy-toggle-code" class="btn btn-primary">Show Code</button>
                        
                        <!-- Just refresh the page -->
                        <form action="" id="almefy-refresh-page" class="d-none">
                            <button  type="submit" class="btn btn-primary ">Refresh Code</button>
                        </form>

                        <script>

                            // This hides the show button, shows the refresh button
                            // and - most importantly - reveals the qr code!
                            // Alternatively you may provide the qr code via some custom endpoint in your framework / plugin.
                            // This way you can refresh the code with javascript, without having to reload the page.

                            const almefy_toggle_code_button = document.querySelector('#almefy-toggle-code');
                            const almefy_refresh = document.querySelector('#almefy-refresh-page');
                            const almefy_qr = document.querySelector('#almefy-connect-code-img');

                            if(almefy_toggle_code_button && almefy_qr && almefy_refresh) {
                                almefy_toggle_code_button.addEventListener('click', (e) => {
                                    e.preventDefault();
                                    almefy_qr.classList.remove('blurred');
                                    almefy_toggle_code_button.classList.add('d-none');
                                    almefy_refresh.classList.remove('d-none');
                                });
                            }

                        </script>
                    </div>
                </div>
                
                <div class="text-center w-200 d-flex flex-column justify-content-between">
                    
                    <div>
                        <p>
                            <b>Scan this code with the Almefy app on your phone.</b><br>
                        </p>
                        <p>
                            If you are viewing this page on your phone, press the "Connect Device" button instead.
                        </p>
                    </div>

                    <div>
                        <a href="<?php echo $connect_url ?>"><button class="btn">Connect Mobile Device</button></a>
                    </div>
                </div>
            </div>

            <hr class="my-20">

            <p class="text-muted">
                Make sure to only show the connection code to the user when they expect to see it.<br>
                Scanning the code will allow permanent access until removed with a device manager.<br>
                See <code class="code">almefy_device_manager.php</code> as example.
            </p>
            <p class="text-muted">
                To prevent bad actors from scanning the you should adhere to following advice:
                <ol class="text-muted">
                    <li>Only show enrollment codes for the currently logged in user.</li>
                    <li>Keep the code in a protected and expected area like the user profile or user settings page.</li>
                    <li>Hide or blur the image and only show it upon user action.</li>
                </ol>
            </p>
        </div>
    </div>
    
<?php endif; ?>