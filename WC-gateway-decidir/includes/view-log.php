<?php

function prisma_view_log() {
    ?>

    <style>
img {
    width: 80px;
}

textarea {
    
    width: 1000px;
    height: 600px;

}


</style>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/WC-gategay-decidir/assets/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>View Log</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <textarea name="tekst"><?php Read(); ?></textarea><br>
      
            </div>
            <br class="clear">
        </div>
         
    </div>
    <?php
}

function Read() {
                   $file = ROOTDIR . '/log/Log-WC-gateway.log';
                   $fp = fopen($file, "r");
                   while(!feof($fp)) {
                       $data = fgets($fp, filesize($file));
                       echo "$data <br>";
                   }
                   fclose($fp);
               }