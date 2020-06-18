<?php
if ( is_user_logged_in() ) {

    echo $before_widget;

    echo $before_title . $title . $after_title;

    echo '<p>Total Horas: ' . $fh_total_hours_minuts[0] .' h ' . $fh_total_hours_minuts[1] . 'min</p>';
			
    echo $after_widget;
}
?>