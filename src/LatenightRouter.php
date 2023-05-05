<?php

namespace Latenight\Router;

class Router
{
    protected $routes = [];
    protected $viewPath = __DIR__ . '/views';

    private function handlePlaceholderValue($pattern)
    {
        return preg_replace('/:([\w-]+)/', '(?<$1>[\w-]+)', $pattern);
    }

    public function get($pattern, $callback)
    {
        $pattern = $this->handlePlaceholderValue($pattern);
        $this->routes['GET'][$pattern] = $callback;
    }

    public function post($pattern, $callback)
    {
        $pattern = $this->handlePlaceholderValue($pattern);
        $this->routes['POST'][$pattern] = $callback;
    }

    public function render($view, $data = [])
    {
        extract($data);
        ob_start();
        require "$this->viewPath/$view.php";
        return ob_get_clean();
    }

    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes[$method] as $pattern => $callback) {
            if (preg_match("#^$pattern$#", $uri, $matches)) {
                $params = array_intersect_key($matches, array_flip(array_filter(array_keys($matches), 'is_string')));

                return call_user_func_array($callback, $params);
            }
        }

        // No route was found
        http_response_code(404);
        return '404 Page Not Found';
    }
}

$app = new Router();
