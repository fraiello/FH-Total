  
<div class="wrap">

    <div id="icon-options-general" class="icon32"></div>
    <h1><?php esc_attr_e( 'FH-Total', 'WpAdminStyle' ); ?></h1>

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <!-- main content -->
            <div id="post-body-content">

                <div class="meta-box-sortables ui-sortable">

                    <div class="postbox">

                        <h2><span><?php esc_attr_e( 'Opciones:', 'WpAdminStyle' ); ?></span></h2>

                        <div class="inside">
                            <p>Con este plugin podras llevar la cuenta de tus horas.</p>
                            <p>Estado actual: <?php echo $fh_total_state; ?></p><br>
                            <p>Total horas: <?php echo $fh_total_hours_minuts[0] ."h ".$fh_total_hours_minuts[1]." min"?></p>
                            <p>
                                <form class="fh_total_options" action="" method="post">
                                    <input type="hidden" name="fh_total_form_submitted" value ="Y">
                                    <input class="button-primary" type="submit" name="start" value="<?php esc_attr_e( 'Start' ); ?>" />
                                    <input class="button-primary" type="submit" name="stop" value="<?php esc_attr_e( 'Stop' ); ?>" />
                                </form>
                            </p>
                        </div>
                        <!-- .inside -->

                    </div>
                    <!-- .postbox -->

                </div>
                <!-- .meta-box-sortables .ui-sortable -->

            </div>
            <!-- post-body-content -->
            <!-- .postbox -->

        </div>
        <!-- .meta-box-sortables -->

    </div>
    <!-- #postbox-container-1 .postbox-container -->

</div>
<!-- #post-body .metabox-holder .columns-2 -->

<br class="clear">
</div>
<!-- #poststuff -->

</div> <!-- .wrap -->