<?php
    chdir(dirname(__FILE__) . '/../');
    include_once("./config.php");
    include_once("./lib/loader.php");
    include_once("./lib/threads.php");
    set_time_limit(0);

    // connecting to database
    $db = new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME);
    include_once("./load_settings.php");
    //include_once(DIR_MODULES . "control_modules/control_modules.class.php");
    //$ctl = new control_modules();
    include_once(DIR_MODULES . 'keenetic_lan_devices/keenetic_lan_devices.class.php');
    $keenetic_lan_devices_module = new keenetic_lan_devices();
    $keenetic_lan_devices_module->getConfig();
    $sleepTime = (int)$keenetic_lan_devices_module->config['UPDATE_PERIOD'];

    if ($sleepTime == 0)
    {
        setGlobal('cycle_keenetic_lan_devices', 'stop');
        setGlobal('cycle_keenetic_lan_devices', '0');
        exit;
    }

    setGlobal('cycle_keenetic_lan_devices', '1');

    while (TRUE)
    {
        setGlobal((str_replace('.php', '', basename(__FILE__))) . 'Run', time(), 1);

        $keenetic_lan_devices_module->processCycle();

        if (file_exists('./reboot') || IsSet($_GET['onetime']))
        {
            $db->Disconnect();
            exit;
        }

        sleep($sleepTime);
    }

    DebMes("Unexpected close of cycle: " . basename(__FILE__));

