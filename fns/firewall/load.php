<?php

class Firewall {
    private $ipBlocks = array();
    private $ipAllows = array();
    private $configScript = '';
    const GET = 2;
    const POST = 4;
    const COOKIE = 8;
    const SESSION = 16;
    const FILES = 32;
    const SERVER = 64;
    public function __construct($configscript = false) {
        $this->configScript = $configscript;

        if ($this->configScript)$this->loadSettingsFromFile();

    }
    public function printSettings() {
        echo "<h1>Debug firewall</h1>";
        echo "<h2>Hi, your ip address is: ", $this->getUserIP(), "</h2>";
        echo "<h2>Ips that are currently blocked:</h2><ul>";
        if (count($this->ipAllows) > 0) {
            echo "<li>ALL IPS EXCEPT FROM Allowed IP's.....</li>";
        } else {
            foreach ($this->ipBlocks as $it) {
                echo "<li>$it</li>";
            }
        }
    }
    private function loadSettingsFromFile() {
        if (!file_exists($this->configScript))throw new Exception('Could not find config script provided: '.$this->configScript);

        $xml = simplexml_load_file($this->configScript);

        if (isset($xml->IP->block) && count($xml->IP->block) > 0) {
            foreach ($xml->IP->block->item as $it) {
                $this->blockIP((String)$it);
            }
        }
        if (isset($xml->IP->allow) && count($xml->IP->allow) > 0) {
            foreach ($xml->IP->allow->item as $it) {
                $this->allowIP((String)$it);
            }
        }
    }
    public function blockIP($ip) {
        if (is_array($ip)) {
            $this->ipBlocks = array_merge($this->ipBlocks, $ip);
        } else {
            $this->ipBlocks[] = $ip;
        }
    }
    public function allowIP($ip) {
        if (is_array($ip)) {
            $this->ipAllows = array_merge($this->ipAllows, $ip);
        } else {
            $this->ipAllows[] = $ip;
        }
    }
    public function run() {
        $clientip = $this->getUserIP();


        $ipblock = false;
        $ipallow = true;

        $iptriggerblock = false;
        if (count($this->ipBlocks) > 0) {

            foreach ($this->ipBlocks as $block) {
                $bit = explode('/', $block);
                if (!isset($bit[1]))$bit[1] = '255.255.255.255';
                if ($this->ipCompare($clientip, $bit[0], $bit[1])) {
                    $ipblock = true;
                    $iptriggerblock = true;
                    continue;
                } else {
                    if (!$iptriggerblock)$ipblock = false;
                }
                unset($bit);
            }
        }

        $iptriggerallow = false;

        if (count($this->ipAllows) > 0) {

            foreach ($this->ipAllows as $block) {
                $bit = explode('/', $block);
                if (!isset($bit[1]))$bit[1] = '255.255.255.255';
                if ($this->ipCompare($clientip, $bit[0], $bit[1])) {
                    $ipallow = true;
                    $iptriggerallow = true;
                    continue;
                } else {
                    if (!$iptriggerallow)$ipallow = false;
                }
                unset($bit);
            }
        }

        if ($ipblock)throw new Exception('Client blocked from php web application. Reason: IP Blocked.', 1);
        if (!$ipallow)throw new Exception('Client blocked from php web application. Reason: IP not in allow list.', 2);
    }
    public function getUserIP() {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];
        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }
        if ($ip == '') {
            $ip = '127.0.0.1';
        } else if ($ip == '::1') {
            $ip = '127.0.0.1';
        }

        return $ip;
    }
    private function ipCompare ($ip1, $ip2, $mask) {

        if (filter_var($ip1, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ip_a_long = $this->ip2long_v6($ip1);
        } else {
            $ip_a_long = ip2long($ip1);
        }

        if (filter_var($ip2, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ip_b_long = $this->ip2long_v6($ip2);
        } else {
            $ip_b_long = ip2long($ip2);
        }


        if (filter_var($mask, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ip_mask_long = $this->ip2long_v6($mask);
        } else {
            $ip_mask_long = ip2long($mask);
        }

        $masked1 = $ip_a_long;
        $masked2 = $ip_b_long;
        if ($masked1 == $masked2) {
            return true;
        } else {
            return false;
        }
    }

    public function ip2long_v6($ip) {
        $ip_n = inet_pton($ip);
        $bin = '';
        for ($bit = strlen($ip_n) - 1; $bit >= 0; $bit--) {
            $bin = sprintf('%08b', ord($ip_n[$bit])) . $bin;
        }

        if (function_exists('gmp_init')) {
            return gmp_strval(gmp_init($bin, 2), 10);
        } elseif (function_exists('bcadd')) {
            $dec = '0';
            for ($i = 0; $i < strlen($bin); $i++) {
                $dec = bcmul($dec, '2', 0);
                $dec = bcadd($dec, $bin[$i], 0);
            }
            return $dec;
        } else {
            trigger_error('GMP or BCMATH extension not installed!', E_USER_ERROR);
        }
    }

    public function long2ip_v6($dec) {
        if (function_exists('gmp_init')) {
            $bin = gmp_strval(gmp_init($dec, 10), 2);
        } elseif (function_exists('bcadd')) {
            $bin = '';
            do {
                $bin = bcmod($dec, '2') . $bin;
                $dec = bcdiv($dec, '2', 0);
            } while (bccomp($dec, '0'));
        } else {
            trigger_error('GMP or BCMATH extension not installed!', E_USER_ERROR);
        }

        $bin = str_pad($bin, 128, '0', STR_PAD_LEFT);
        $ip = array();
        for ($bit = 0; $bit <= 7; $bit++) {
            $bin_part = substr($bin, $bit * 16, 16);
            $ip[] = dechex(bindec($bin_part));
        }
        $ip = implode(':', $ip);
        return inet_ntop(inet_pton($ip));
    }

    public function getUserAgent($agent = 0) {

        if (empty($agent)) {
            $agent = $_SERVER ['HTTP_USER_AGENT'];
        }
        $result = array();
        $result['browser'] = $result['version'] = $result['platform'] = '?';
        $result['user_agent'] = $agent;
        $regexfound = 'Chrome';

        $browser_array = array('/msie/i' => 'Internet Explorer',
            '/Mobile/i' => 'Handheld Browser',
            '/Firefox/i' => 'Firefox',
            '/Safari/i' => 'Safari',
            '/Chrome/i' => 'Chrome',
            '/Opera/i' => 'Opera',
            '/Edge/i' => 'Edge',
            '/Edg/i' => 'Edge',
            '/Opr/i' => 'Opera',
            '/Netscape/i' => 'Netscape',
            '/Maxthon/i' => 'Maxthon',
            '/Konqueror/i' => 'Konqueror');

        foreach ($browser_array as $regex => $value) {
            if (preg_match($regex, $agent, $output)) {
                $result['browser'] = $value;
                $regexfound = str_replace('/', '', str_replace('/i', '', $regex));
            }
        }

        $platform_array = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );

        foreach ($platform_array as $regex => $value) {
            if (preg_match($regex, $agent)) {
                $result['platform'] = $value;
            }
        }

        $known = array('Version', $regexfound, 'other');
        $pattern = '#(?<browser>' . join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $agent, $matches)) {}

        $i = count($matches['browser']);
        if ($i != 1) {
            if (strripos($agent, "Version") < strripos($agent, $result['browser'])) {
                if (isset($matches['version'][0])) {
                    $result['version'] = $matches['version'][0];
                }
            } else {
                if (isset($matches['version'][1])) {
                    $result['version'] = $matches['version'][1];
                }
            }
        } else {
            if (isset($matches['version'][0])) {
                $result['version'] = $matches['version'][0];
            }
        }

        if (!isset($result['version']) || $result['version'] == null || $result['version'] == "") {
            $result['version'] = "?";
        }
        return $result;
    }

}


?>