<!-- 
    This is an example of how a device manager might look like.
    In practice you would probably send the requests via Javascript to 
    custom endpoint that then use the Almefy php SDK to retrieve and delete devices
    without having to reload the page.

    For simplicities sake, this example is just using POST requests and forms.

    NOTE: In a future release we will add a device manager widget with the Javascript sdk.
    Adding a device manager will be as easy as adding the login qr code!
-->

<?php if(!$backend->is_logged_in()): ?>
    <div>You must be logged in to manage your 2-factor devices.<div>
<?php else: ?>
    <?php

        if(isset($_POST['delete_device_id']) && $_POST['delete_device_id']) {
            $device_id = $_POST['delete_device_id'];

            $almefy->delete_device($device_id);
        }

        $user = $backend->get_current_user();
        // We are using the users email as identifier, but we could use any string if we wanted.
        $identifier = $user->email;
        $devices = [];

        try {
            $identity = $almefy->client->getIdentity($identifier);
            $devices = $identity->getTokens(); 
        } catch (\Almefy\Exception\TransportException $e) {
            echo 'Could not connect to Almefy service: '.$e->getMessage();
        }
    ?>

    <div class="col-sm">
        <div class="card">
            <h2 class="card-title">
                Manage your 2-factor devices
            </h2>

            <?php 
            
            if(count($devices) == 0) {
                echo "<strong>No devices connected.</strong>";
            }
        
            foreach ($devices as $device){ 
                // These are all fields describing a device. You may use some or all of them.
                $id = $device->getId();
                $created_at = explode("T", $device->getCreatedAt())[0];
                $name = $device->getName();
                $label = $device->getLabel();
                $model = $device->getModel();
                ?>
                    <div>
                        <div class="d-flex flex-wrap justify-content-between" style="gap: 1rem;">
                            <div>
                                <strong><?php echo $name ?></strong><br>
                                Model: <?php echo $model ?><br>
                                Label: <?php echo $label ?>
        
                            </div>
                            <div class="d-flex flex-column justify-content-between">
                                <div><?php echo $created_at ?></div>
                                <form action="/?is_error&message=Deleted  <?php echo $id ?>" method="post">
                                    <input type="hidden" name="delete_device_id" value="<?php echo $id ?>">
                                    <input class="btn btn-danger" type="submit" value="Delete">
                                </form>
                            </div>
                        </div>
                        
                    </div>
                    <hr class="my-10"/>
                <?php
            }
            ?>
        
            <div class="d-flex mt-20 flex-wrap justify-content-between" style="gap: .5rem;">
        
                <!-- Just refresh the page -->
                <form action="">
                    <button type="submit" class="btn btn-primary ">Refresh</button>
                </form>
        
                <!-- Sending "Connect Device" emails -->
                <form action="/backend/plugins/almefy_reconnect.php?redirect=/" method="post">
                    <input type="hidden" name="email" id="email_reconnect" value="<?php echo $user->email ?>">                
                    <div>
                        <input class="btn" type="submit" value="Send 'Connect Device' Email">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
<?php endif; ?>