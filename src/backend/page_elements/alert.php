<?php

// draw info alert at the top
if(isset($_GET['message']) && $_GET['message'] != '') {
    $message = $_GET['message'];
    $is_error = isset($_GET['is_error']);

    $color_class = $is_error ? "alert-danger" : "alert-success";

    ?>
        <div  class="alert <?php echo $color_class ?>">
            <button class="close" data-dismiss="alert" type="button" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        
            <h4 class="alert-heading">Notice</h4>
            <?php echo $message?>
        </div>

    <?php
}