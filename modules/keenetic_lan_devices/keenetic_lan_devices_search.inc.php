<?php
    /*
    * @version 0.1 (wizard)
    */
    global $session;
    if ($this->owner->name == 'panel') { $out['CONTROLPANEL'] = 1; }

    $qry = "1";
    // search filters
    // QUERY READY
    global $save_qry;

    if ($save_qry) { $qry = $session->data['keenetic_lan_devices_qry']; }
    else { $session->data['keenetic_lan_devices_qry'] = $qry; }

    if (!$qry) { $qry = "1"; }

    //---сортировка
    global $sortby;
    /* //Здесь попытка реализации с сохранением в сессию и обратной сортировкой, но сессия сохраняется не стабильно - только при смене колонки
    if ($sortby)
    {
        if ($sortby == $session->data['SORTBY_KEENETIC_LAN_DEVICES'])
        {
            $sortby = $sortby . ' DESC';
        }

        $session->data['SORTBY_KEENETIC_LAN_DEVICES'] = $sortby;
        $sortby_keenetic_lan_devices = $sortby;

    }
    else
    {
        $sortby_keenetic_lan_devices=$session->data['SORTBY_KEENETIC_LAN_DEVICES'];
    }
        //$session->save(); //сессия почему то сохраняется только при первом нажатии на сортировку столбца. Можно сохранять принудительно, но при пересортировке запрос проходит 2 раза
    */

    if ($sortby)
    {
        $sortby_keenetic_lan_devices = $sortby;
    }
    else
    {
        $sortby_keenetic_lan_devices = "ONLINE DESC, TITLE ASC";
    }

    $out['SORTBY'] = $sortby_keenetic_lan_devices;

    // SEARCH RESULTS
    $res = SQLSelect("SELECT * FROM keenetic_lan_devices WHERE $qry ORDER BY " . $sortby_keenetic_lan_devices);
    if ($res[0]['ID'])
    {
        //paging($res, 100, $out); // search result paging
        $total = count($res);
        for ($i = 0; $i < $total; $i++)
        {
            // some action for every record if required
            //$tmp = explode(' ', $res[$i]['UPDATED']);
            //$res[$i]['UPDATED'] = fromDBDate($tmp[0]) . " " . $tmp[1];
        }
        $out['RESULT'] = $res;
    }
