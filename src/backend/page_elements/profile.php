<div class="col-sm">
    <div class="card">
        <h2 class="card-title">
            Manage Profile
        </h2>
            <div class="form-group">
                <label>Username</label>
                <input class="form-control" type="text" disabled value="<?php echo $backend->get_current_user()->username ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="text" disabled value="<?php echo $backend->get_current_user()->email ?>">
            </div>

            <div class="d-flex justify-content-between">
                <button class="btn" disabled>Change Password</button>

                <a href="/backend/requests/delete_account.php/">
                    <button class="btn btn-danger">Delete Account</button>
                </a>
            </div>
    </div>
</div>


<!-- START ALMEFY INTEGRATION -->

<div class="row">
    <?php require_once __DIR__ . "/../plugins/almefy_connect_device.php" ?>
    <?php require_once __DIR__ . "/../plugins/almefy_device_manager.php" ?>
</div>

<!-- END ALMEFY INTEGRATION -->