<?php
	// Calcula la diferencia de horas.
	
    function fh_total_calc_diff($fh_total_hour_start, $fh_total_hour_finish){


        $fh_total_date1 = date_create($fh_total_hour_start);
        $fh_total_date2 = date_create($fh_total_hour_finish);

        $fh_total_diff = date_diff($fh_total_date1, $fh_total_date2);

        return $fh_total_diff->format('%H:%i');

    }

    // Agrega nuevo registro con el id del usuario, la fecha y la hora de inicio.
	
    function fh_total_insert_data($fh_total_user_id, $fh_total_hour_start, $fh_total_date_now){

        global $wpdb;

        $fh_total_data = array(
            'user_id' => $fh_total_user_id,
            'hour_start' => $fh_total_hour_start,
            'date' => $fh_total_date_now
        );

        $wpdb->insert($wpdb->prefix.'fh_total', $fh_total_data, array('%d', '%s', '%s'));
    }
    // Actualiza el registro  con la hora de finalizacion del conteo.
	
    function fh_total_update_data($fh_total_user_id, $fh_total_hour_finish, $fh_total_date_now){

        global $wpdb;

        $fh_total_hour_start = $wpdb->get_var($wpdb->prepare("SELECT  hour_start
                                                                FROM {$wpdb->prefix}fh_total 
                                                            WHERE hour_finish = %s", '00:00:00'));

        $wpdb->update($wpdb->prefix.'fh_total',
            array('hour_finish' => $fh_total_hour_finish),
            array('user_id' => $fh_total_user_id,
                  'hour_finish' => '00:00:00',
                  'date'=>$fh_total_date_now));

        $fh_total_total_hours = fh_total_calc_diff($fh_total_hour_finish,  $fh_total_hour_start);

        $wpdb->update($wpdb->prefix.'fh_total',
            array('hours_total' => $fh_total_total_hours),
            array('user_id' => $fh_total_user_id,
                'hours_total' => '00:00:00',
                'date'=>$fh_total_date_now));
    }

    // Obtiene los datos para calcular las horas empleadas.
	
    function fh_total_get_data($fh_total_user_id){

        global $wpdb;

        $fh_total_data = $wpdb->get_var($wpdb->prepare("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(hours_total))) 
                                                                FROM {$wpdb->prefix}fh_total 
                                                            WHERE user_id = %d", $fh_total_user_id));

        return $fh_total_data;
    }