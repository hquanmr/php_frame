<?php
declare(strict_types=1);



/**
 * 浏览器友善的打印数据
 *
 * @param  array  $data 数据
 * @return mixed
 */
if (!function_exists('p')) {
    function p($var, $echo = true, $label = null, $strict = true)
    {
        $label = ($label === null) ? '' : rtrim($label) . ' ';
        if (!$strict) {
            if (ini_get('html_errors')) {
                $output = print_r($var, true);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            } else {
                $output = $label . print_r($var, true);
            }
        } else {
            ob_start();
            var_dump($var);
            $output = ob_get_clean();
            if (!extension_loaded('xdebug')) {
                $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            }
        }
        if ($echo) {
            echo ($output);
            return null;
        } else
            return $output;
    }
}

/**
 * 获取容器对象
 *
 * @param  array  $data 数据
 * @return mixed
 */
if (!function_exists('app')) {
    function app($name)
    {
          $app = new  \core\App(__DIR__);
          return  $app->container->get($name);
    }
}
