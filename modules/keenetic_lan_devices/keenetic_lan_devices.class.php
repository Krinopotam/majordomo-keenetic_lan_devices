<?php
    /**
     * Устройства Онлайн Keenetic
     * @package project
     * @author Krinopotam <omegatester@gmail.com>
     * @copyright http://majordomo.smartliving.ru/ (c)
     * @version 0.1 (wizard, 16:09:19 [Sep 01, 2018])
     *
     * @property int|null|string id
     * @property int|null|string mode
     * @property int|null|string view_mode
     * @property int|null|string edit_mode
     * @property int|null|string data_source
     * @property int|null|string tab
     * @property int|null|string action
     */
    //
    //
    class keenetic_lan_devices extends module
    {
        /**
         * keenetic_lan_devices
         *
         * Module class constructor
         *
         * @access private
         */
        function __construct()
        {
            $this->name = "keenetic_lan_devices";
            $this->title = "Устройства Онлайн Keenetic";
            $this->module_category = "<#LANG_SECTION_DEVICES#>";
            $this->checkInstalled();

        }

        /**
         * saveParams
         *
         * Saving module parameters
         *
         * @access public
         * @param int $data
         * @return string|void
         */
        function saveParams($data = 1)
        {
            $p = array();
            if (IsSet($this->id))
            {
                $p["id"] = $this->id;
            }
            if (IsSet($this->view_mode))
            {
                $p["view_mode"] = $this->view_mode;
            }
            if (IsSet($this->edit_mode))
            {
                $p["edit_mode"] = $this->edit_mode;
            }
            if (IsSet($this->data_source))
            {
                $p["data_source"] = $this->data_source;
            }
            if (IsSet($this->tab))
            {
                $p["tab"] = $this->tab;
            }

            return parent::saveParams($p);
        }

        /**
         * getParams
         *
         * Getting module parameters from query string
         *
         * @access public
         */
        function getParams()
        {
            global $id;
            global $mode;
            global $view_mode;
            global $edit_mode;
            global $data_source;
            global $tab;
            if (isset($id))
            {
                $this->id = $id;
            }
            if (isset($mode))
            {
                $this->mode = $mode;
            }
            if (isset($view_mode))
            {
                $this->view_mode = $view_mode;
            }
            if (isset($edit_mode))
            {
                $this->edit_mode = $edit_mode;
            }
            if (isset($data_source))
            {
                $this->data_source = $data_source;
            }
            if (isset($tab))
            {
                $this->tab = $tab;
            }
        }

        /**
         * Run
         *
         * Description
         *
         * @access public
         */
        function run()
        {
            global $session;

            $out = array();
            if ($this->action == 'admin')
            {
                $this->admin($out);
            }
            else
            {
                $this->usual($out);
            }
            if (IsSet($this->owner->action))
            {
                $out['PARENT_ACTION'] = $this->owner->action;
            }
            if (IsSet($this->owner->name))
            {
                $out['PARENT_NAME'] = $this->owner->name;
            }
            $out['VIEW_MODE'] = $this->view_mode;
            $out['EDIT_MODE'] = $this->edit_mode;
            $out['MODE'] = $this->mode;
            $out['ACTION'] = $this->action;
            $out['DATA_SOURCE'] = $this->data_source;
            $out['TAB'] = $this->tab;
            $this->data = $out;
            $p = new parser(DIR_TEMPLATES . $this->name . "/" . $this->name . ".html", $this->data, $this);
            $this->result = $p->result;
        }

        /**
         * BackEnd
         *
         * Module backend
         *
         * @access public
         * @param $out
         */
        function admin(&$out)
        {
            $this->getConfig();

            $out['API_URL'] = $this->config['API_URL'];
            if (!$out['API_URL']) { $out['API_URL'] = '192.168.1.1'; }

            $out['ADMIN_USERNAME'] = $this->config['ADMIN_USERNAME'];
            if (!$out['ADMIN_USERNAME']) { $out['ADMIN_USERNAME'] = 'admin'; }

            $out['ADMIN_PASSWORD'] = $this->config['ADMIN_PASSWORD'];

            $out['UPDATE_PERIOD'] = $this->config['UPDATE_PERIOD'];
            if (!$out['UPDATE_PERIOD']) { $out['UPDATE_PERIOD'] = '10'; }

            if ($this->view_mode == 'update_settings')
            {
                global $api_url;
                $this->config['API_URL'] = $api_url;
                global $admin_username;
                $this->config['ADMIN_USERNAME'] = $admin_username;
                global $admin_password;
                $this->config['ADMIN_PASSWORD'] = $admin_password;
                global $update_period;
                $this->config['UPDATE_PERIOD'] = $update_period;

                $this->saveConfig();
                $this->redirect("?");
            }
            if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source'])
            {
                $out['SET_DATASOURCE'] = 1;
            }
            if ($this->data_source == 'keenetic_lan_devices' || $this->data_source == '')
            {
                if ($this->view_mode == '' || $this->view_mode == 'search_keenetic_lan_devices')
                {
                    $this->search_keenetic_lan_devices($out);
                }
                if ($this->view_mode == 'update_keenetic_lan_devices')
                {
                    $this->update_keenetic_lan_devices($out);
                }
                if ($this->view_mode == 'edit_keenetic_lan_devices')
                {
                    $this->edit_keenetic_lan_devices($out, $this->id);
                }
                if ($this->view_mode == 'delete_keenetic_lan_devices')
                {
                    $this->delete_keenetic_lan_devices($this->id);
                    $this->redirect("?data_source=keenetic_lan_devices");
                }
            }
            if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source'])
            {
                $out['SET_DATASOURCE'] = 1;
            }
            if ($this->data_source == 'keenetic_lan_devices_values')
            {
                if ($this->view_mode == '' || $this->view_mode == 'search_keenetic_lan_devices_values')
                {
                    $this->search_keenetic_lan_devices_values($out);
                }
                if ($this->view_mode == 'edit_keenetic_lan_devices_values')
                {
                    $this->edit_keenetic_lan_devices_values($out, $this->id);
                }
            }
        }

        /**
         * FrontEnd
         *
         * Module frontend
         *
         * @access public
         * @param $out
         */
        function usual(&$out)
        {
            $this->admin($out);
        }

        /**
         * keenetic_lan_devices search
         *
         * @access public
         * @param $out
         */
        function search_keenetic_lan_devices(&$out)
        {
            require(DIR_MODULES . $this->name . '/keenetic_lan_devices_search.inc.php');
        }


        /**
         * keenetic_lan_devices update
         *
         * @access public
         * @param $out
         */
        function update_keenetic_lan_devices(&$out)
        {
            $this->updateDevices();
            $this->redirect("?data_source=keenetic_lan_devices");
        }

        /**
         * keenetic_lan_devices edit/add
         *
         * @access public
         * @param $out
         * @param $id
         */
        function edit_keenetic_lan_devices(&$out, $id)
        {
            require(DIR_MODULES . $this->name . '/keenetic_lan_devices_edit.inc.php');
        }

        /**
         * keenetic_lan_devices delete record
         *
         * @access public
         * @param $id
         */
        function delete_keenetic_lan_devices($id)
        {
            $rec = SQLSelectOne("SELECT * FROM keenetic_lan_devices WHERE ID='" . DBSafe($id) . "'");
            // some action for related tables
            SQLExec("DELETE FROM keenetic_lan_devices WHERE ID='" . DBSafe($rec['ID']) . "'");
            SQLExec("DELETE FROM keenetic_lan_devices_values WHERE DEVICE_ID='" . DBSafe($rec['ID']) . "'");
        }

        /**
         * keenetic_lan_devices_values search
         *
         * @access public
         * @param $out
         */
        function search_keenetic_lan_devices_values(&$out)
        {
            require(DIR_MODULES . $this->name . '/keenetic_lan_devices_values_search.inc.php');
        }

        /**
         * keenetic_lan_devices_values edit/add
         *
         * @access public
         * @param $out
         * @param $id
         */
        function edit_keenetic_lan_devices_values(&$out, $id)
        {
            require(DIR_MODULES . $this->name . '/keenetic_lan_devices_values_edit.inc.php');
        }

        /**
         * Установка значений из объекта в устройство (для параметров, для которых это разрешено)
         * @param $object
         * @param $property
         * @param $value
         */
        function propertySetHandle($object, $property, $value)
        {
            //в данном модуле нельзя установить никаких значений на устройтсве keenetic
        }

        function processCycle()
        {
            $this->getConfig();

            $this->updateDevices();

            //DebMes("Цикл keenetic_lan_devices отработал.");
            //$this->debug ($equipments);
            //DebMes($this->config);
        }

        /**
         * Install
         *
         * Module installation routine
         *
         * @access private
         * @param string $data
         */
        function install($data = '')
        {
            parent::install();
        }

        //*************************** My Functions **************************
        /**
         * Получает и сохраняет статус объектов из роутера
         */
        function updateDevices()
        {
            $equipments =  $this->getAllDevicesInfo();

            if (!is_array($equipments)) {return;}

            $equipmentsInDb = SQLSelect("SELECT * FROM keenetic_lan_devices ");

            //проходим по устройствам, которые есть в БД
            foreach ($equipmentsInDb as $key => $value)
            {
                if (!isset($equipments[$value["MAC"]])) {continue;}

                $value["HOST_NAME"] = $equipments[$value["MAC"]]["HOST_NAME"];
                $value["DEVICE_NAME"] = $equipments[$value["MAC"]]["DEVICE_NAME"];
                $value["IP"] = $equipments[$value["MAC"]]["IP"];
                $value["REGISTERED"] = $equipments[$value["MAC"]]["REGISTERED"];
                $value["ONLINE"] = $equipments[$value["MAC"]]["STATUS"];
                $value['UPDATED'] = date('Y-m-d H:i:s');
                SQLUpdate('keenetic_lan_devices', $value);

                $this->updateValues($value['ID'], $equipments[$value["MAC"]]);

                unset($equipments[$value["MAC"]]);
            }

            //проходим по оставшимся устройствам, которых нет в БД
            foreach ($equipments as $key => $value)
            {
                $newEquipment = array();

                if ($value["DEVICE_NAME"]!='') {$newEquipment["TITLE"] = $value["DEVICE_NAME"];}
                else  {$newEquipment["TITLE"] = $value["HOST_NAME"];}

                $newEquipment["MAC"] = $value["MAC"];
                $newEquipment["HOST_NAME"] = $value["HOST_NAME"];
                $newEquipment["DEVICE_NAME"] = $value["DEVICE_NAME"];
                $newEquipment["IP"] = $value["IP"];
                $newEquipment["REGISTERED"] = $value["REGISTERED"];
                $newEquipment["ONLINE"] = $value["STATUS"];

                $newEquipment['ID'] = SQLInsert('keenetic_lan_devices', $newEquipment);

                if (!$newEquipment['ID']) {return;}

                $this->updateValues($newEquipment['ID'], $value);
            }
        }

        /**
         * Возвращает информацию о всех устройствах из маршрутизатора Keenetic
         * @return mixed|null
         */
        private function getAllDevicesInfo()
        {
            try
            {
                if ($this->config['API_URL']=="") {return NULL;}

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'http://'.$this->config['API_URL'].'/ci');
                //curl_setopt($ch, CURLOPT_POSTFIELDS, '<request id="0"><command name="show dyndns"><profile>_WEBADMIN</profile><name>ISP</name></command></request><request id="1"><command name="show interface stat"><name>ISP</name></command></request>');
                curl_setopt($ch, CURLOPT_POSTFIELDS, '<request id="0"><command name="show ip hotspot"></command></request>');
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/xml']);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
                curl_setopt($ch, CURLOPT_USERPWD, $this->config['ADMIN_USERNAME'].':'.$this->config['ADMIN_PASSWORD']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $data = curl_exec($ch);

                if(curl_errno($ch)) {return NULL;}
                if(strpos($data, "401 Authorization")>0) {echo ("dsdsds");return NULL;}

                $objectResult = new SimpleXMLElement($data);

                $result = array();

                foreach ($objectResult->children()->children() as $node) {
                    if ($node->mac=='' || $node->registered!="yes") {continue;}

                    $result[(string)$node->mac] = array();
                    $result[(string)$node->mac]["MAC"] = (string)$node->mac;
                    $result[(string)$node->mac]["STATUS"] = ((string)$node->link=='up' ? 1 : 0);
                    $result[(string)$node->mac]["STATUS_TXT"] = ((string)$node->link=='up' ? 'Online' : 'Offline');
                    $result[(string)$node->mac]["HOST_NAME"] = (string)$node->hostname;
                    $result[(string)$node->mac]["DEVICE_NAME"] = (string)$node->name;
                    $result[(string)$node->mac]["IP"] = (string)$node->ip;
                    $result[(string)$node->mac]["REGISTERED"] = (string)$node->registered;
                }

                return $result;
            }
            catch (Exception $e) {
                // код который может обработать исключение
                //echo $e->getMessage();
                return NULL;
            }
        }

        /**
         * Обновление значений устройства
         * @param int $deviceId устройства в БД
         * @param array $values значения устройства
         */
        function updateValues($deviceId, $values)
        {
            if (!is_array($values)) {return;}

            $rec_vals=array();
            $rec_val=array();

            if (isset($values['STATUS']))
            {
                $rec_val['DEVICE_ID'] = $deviceId;
                $rec_val['TITLE'] = "status";
                $rec_val['DESCRIPTION'] = " (R/O) Статус";
                $rec_val['VALUE'] = $values['STATUS'];
                $rec_vals[] = $rec_val;
            }

            if (isset($values['MAC']))
            {
                $rec_val['DEVICE_ID'] = $deviceId;
                $rec_val['TITLE'] = "mac";
                $rec_val['DESCRIPTION'] = " (R/O) MAC";
                $rec_val['VALUE'] = $values['MAC'];
                $rec_vals[] = $rec_val;
            }

            if (isset($values['IP']))
            {
                $rec_val['DEVICE_ID'] = $deviceId;
                $rec_val['TITLE'] = "ip";
                $rec_val['DESCRIPTION'] = " (R/O) IP";
                $rec_val['VALUE'] = $values['IP'];
                $rec_vals[] = $rec_val;
            }

            $rec_val['DEVICE_ID'] = $deviceId;
            $rec_val['TITLE'] = "updated";
            $rec_val['DESCRIPTION'] = " (R/O) Обновлено";
            $rec_val['VALUE'] = date("Y-m-d H:i:s");
            $rec_vals[] = $rec_val;

            foreach ($rec_vals as $rec_val)
            {
                $this->processValues($deviceId, $rec_val);
            }
        }

        /**
         * Сохраняет новые значения и вызывает привязанные события или свойства
         * @param $device_id
         * @param $rec_val
         * @param int $params
         */
        function processValues($device_id, $rec_val, $params = 0) {

            $old_rec = SQLSelectOne("SELECT * FROM keenetic_lan_devices_values WHERE DEVICE_ID=".(int)$device_id." AND TITLE LIKE '".DBSafe($rec_val['TITLE'])."'");

            $old_value = "";

            if (!$old_rec['ID'])
            {
                $rec_val['ID'] = SQLInsert('keenetic_lan_devices_values', $rec_val);
            }
            else
            {
                $old_value = $old_rec['VALUE'];

                $rec_val['ID'] = $old_rec['ID'];
                $rec_val['DEVICE_ID'] = $old_rec['DEVICE_ID'];
                $rec_val['LINKED_OBJECT'] = $old_rec['LINKED_OBJECT'];
                $rec_val['LINKED_PROPERTY'] = $old_rec['LINKED_PROPERTY'];
                $rec_val['LINKED_METHOD'] = $old_rec['LINKED_METHOD'];
                $rec_val['SCRIPT_ID'] = $old_rec['SCRIPT_ID'];
                $rec_val['UPDATED'] = date('Y-m-d H:i:s');
                SQLUpdate('keenetic_lan_devices_values', $rec_val);
            }

            //если вдруг связанное свойство пустое (только привязали), то сразу его обновляем текущим значением
            if ($rec_val['LINKED_OBJECT'] && $rec_val['LINKED_PROPERTY'])
            {
                $linkedValue = getGlobal($rec_val['LINKED_OBJECT'] . '.' . $rec_val['LINKED_PROPERTY']);

                //$this->debug ("$linkedValue".$linkedValue);

                if ($linkedValue!=$rec_val['VALUE'])
                {
                    setGlobal($rec_val['LINKED_OBJECT'] . '.' . $rec_val['LINKED_PROPERTY'], $rec_val['VALUE'], array($this->name => '0'));
                }
            }

            // Если значение метрики не изменилось, то выходим.
            if ($old_value == $rec_val['VALUE']) return;

            // Иначе обновляем привязанное свойство.
            if ($rec_val['LINKED_OBJECT'] && $rec_val['LINKED_PROPERTY']) {
                setGlobal($rec_val['LINKED_OBJECT'] . '.' . $rec_val['LINKED_PROPERTY'], $rec_val['VALUE'], array($this->name => '0'));
            }

            // И вызываем привязанный метод.
            if ($rec_val['LINKED_OBJECT'] && $rec_val['LINKED_METHOD']) {
                if (!is_array($params)) {
                    $params = array();
                }
                $params['VALUE'] = $rec_val['VALUE'];
                $params['OLD_VALUE'] = $old_value;
                $params['NEW_VALUE'] = $rec_val['VALUE'];

                callMethodSafe($rec_val['LINKED_OBJECT'] . '.' . $rec_val['LINKED_METHOD'], $params);
            }

            // И вызываем привязанный скрипт
            if ($rec_val['SCRIPT_ID']) {
                $params['VALUE']=$rec_val['VALUE'];
                $params['value']=$rec_val['VALUE'];
                runScript($rec_val['SCRIPT_ID'], $params);
            }
        }

        /**
         * Выводит в log файл объект (print_r)
         * @param mixed $content
         */
        function debug($content)
        {
            $this->log(print_r($content, TRUE));
        }

        /**
         * Выводит в log файл строку
         * @param string $message
         */
        function log($message)
        {
            //echo $message . "\n";
            // DEBUG MESSAGE LOG
            if (!is_dir(ROOT . 'cms/debmes'))
            {
                mkdir(ROOT . 'cms/debmes', 0777);
            }

            $today_file = ROOT . 'cms/debmes/log_' . date('Y-m-d') . '-keenetic_lan_devices.php.txt';
            $data = date("H:i:s") . " " . $message . "\n";
            file_put_contents($today_file, $data, FILE_APPEND | LOCK_EX);
        }

        //*******************End of my functions************************************************

        /**
         * Uninstall
         *
         * Module uninstall routine
         *
         * @access public
         */
        function uninstall()
        {
            SQLExec('DROP TABLE IF EXISTS keenetic_lan_devices');
            SQLExec('DROP TABLE IF EXISTS keenetic_lan_devices_values');
            parent::uninstall();
        }

        /**
         * dbInstall
         *
         * Database installation routine
         *
         * @access private
         * @param string $data
         */
        function dbInstall($data)
        {
            /*
            keenetic_lan_devices -
            keenetic_lan_devices_values -
            */
            $data = <<<EOD
 keenetic_lan_devices: ID int(10) unsigned NOT NULL auto_increment
 keenetic_lan_devices: TITLE varchar(100) NOT NULL DEFAULT ''
 keenetic_lan_devices: MAC varchar(17) NOT NULL DEFAULT ''
 keenetic_lan_devices: DEVICE_NAME varchar(255) NOT NULL DEFAULT ''
 keenetic_lan_devices: HOST_NAME varchar(255) NOT NULL DEFAULT ''
 keenetic_lan_devices: IP varchar(15) NOT NULL DEFAULT ''
 keenetic_lan_devices: REGISTERED varchar(10) NOT NULL DEFAULT ''
 keenetic_lan_devices: ONLINE int(1) NOT NULL DEFAULT '0'
 keenetic_lan_devices: UPDATED datetime
 keenetic_lan_devices_values: ID int(10) unsigned NOT NULL auto_increment
 keenetic_lan_devices_values: TITLE varchar(100) NOT NULL DEFAULT ''
 keenetic_lan_devices_values: DESCRIPTION varchar(100) NOT NULL DEFAULT ''
 keenetic_lan_devices_values: VALUE varchar(255) NOT NULL DEFAULT ''
 keenetic_lan_devices_values: DEVICE_ID int(10) NOT NULL DEFAULT '0'
 keenetic_lan_devices_values: LINKED_OBJECT varchar(100) NOT NULL DEFAULT ''
 keenetic_lan_devices_values: LINKED_PROPERTY varchar(100) NOT NULL DEFAULT ''
 keenetic_lan_devices_values: LINKED_METHOD varchar(100) NOT NULL DEFAULT ''
 keenetic_lan_devices_values: SCRIPT_ID int(10) NOT NULL DEFAULT '0'
 keenetic_lan_devices_values: UPDATED datetime
EOD;
            parent::dbInstall($data);
        }
        // --------------------------------------------------------------------
    }
    /*
    *
    * TW9kdWxlIGNyZWF0ZWQgU2VwIDAxLCAyMDE4IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
    *
    */
