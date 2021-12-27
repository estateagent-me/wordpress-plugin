<?php

$cronName = 'estateagentme_cron_update';

if (! wp_next_scheduled($cronName)) {
    wp_schedule_event(time(), 'hourly', $cronName);
}

add_action($cronName, 'EACronUpdate');