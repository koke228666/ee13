<?php
use openvk\Web\Models\Entities\IP;
use openvk\Web\Models\Repositories\IPs;

// новейшая разработка моссада piska framework pro max
if (!isset($GLOBALS['ee13'])) {
    $GLOBALS['ee13'] = new class {
        private array $funcs = [];
        public array $locale;
        
        public function __construct()
        {
            $this->locale = require OPENVK_ROOT . "/themepacks/ee13/core/locales.php"; 
        }
        
        public function get_lang(string $key)
        {
            return $this->locale[getLanguage()][$key] ?? str_replace("_", " ", $key);
        }

        public function enable_bully_bob()
        {
            \Tracy\Debugger::$showBar = false;
        }

        public function get_resource($theme, $resource)
        {
            return "/themepack/" . $theme->getId() . "/" . $theme->getVersion() . "/resource" . $resource;
        }

        public function get_favicon($theme, $lang)
        {
            if ($lang === 'ru' || $lang === 'be' || $lang === 'uk') {
                return "/themepack/" . $theme->getId() . "/" . $theme->getVersion() . "/resource/images/faviconnew.ico";
            } else {
                return "/themepack/" . $theme->getId() . "/" . $theme->getVersion() . "/resource/images/favicon_vk.ico";
            }
        }

        public function get_header($lang)
        {
            if ($lang === 'ru' || $lang === 'be' || $lang === 'uk') {
                return "p_head p_head_l0";
            } else {
                return "p_head1 p_head_l1";
            }
        }

        public function get_al()
        {
            $al = $_REQUEST['al'] ?? 3;
            return $al;
        }

        // токо если есть hash в запросе
        public function check_csrf()
        {
            return $GLOBALS["csrfCheck"];
        }

        /*
            code:
                1 - email not confirmed
                2 - captcha
                3 - auth failed
                4 - redirect
                5 - reload
                6 - mobile activation needed
                7 - message
                    $ee13vars->ajax(7, ["ещё посидим"])
                8 - error
                    $ee13vars->ajax(7, ["ну всё пизда"])
                9 - votes payment
                10 - zero zone
                11 & 12 - mobile validation needed
        
        */
        public function ajax($code = 0, $payload = [], $headers = [])
        {
            $navVersion = isset($headers['navVersion']) ? $headers['navVersion'] : 1;
            $newStatic  = isset($headers['newStatic']) ? $headers['newStatic'] : '';
            $langId     = isset($headers['langId']) ? $headers['langId'] : 0;
            $langVer    = isset($headers['langVer']) ? $headers['langVer'] : 1;

            $response = [
                $navVersion,
                $newStatic,
                $langId,
                $langVer,
                $code
            ];

            foreach ($payload as $item) {
                if (is_array($item) || is_object($item)) {
                    $response[] = '<!json>' . json_encode($item, JSON_UNESCAPED_UNICODE);
                } elseif (is_int($item)) {
                    $response[] = '<!int>' . $item;
                } elseif (is_float($item)) {
                    $response[] = '<!float>' . $item;
                } elseif (is_bool($item)) {
                    $response[] = '<!bool>' . ($item ? '1' : '0');
                } elseif (is_null($item)) {
                    $response[] = '<!null>';
                } else {
                    $response[] = $item; 
                }
            }

            return implode('<!>', $response);
        }
        
        public function cookie($name, $value) {
            setcookie($name, $value, time() + (365 * 24 * 60 * 60), '/');
        }

        public function rate_limit() {
            $ip  = (new IPs())->get(CONNECTING_IP);
            $res = $ip->rateLimit();

            if (!($res === IP::RL_RESET || $res === IP::RL_CANEXEC)) {
                if ($res === IP::RL_BANNED && OPENVK_ROOT_CONF["openvk"]["preferences"]["security"]["rateLimits"]["autoban"]) {
                    $thisUser->ban("User account has been suspended for breaking API terms of service", false);
                    return 18;
                }

                return 29;
            }
        }
    };
}