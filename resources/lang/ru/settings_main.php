<?php

use App\Models\SettingsMain;

$settingsMain = SettingsMain::all();
$data = [];
foreach($settingsMain as $sm) {
    $data[$sm->prefix] = $sm->name;
}

return $data;
