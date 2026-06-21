<?php
class Router {
    private $routes = [];

    public function get($path, $handler) {
        $this->routes['GET'][$path] = $handler;
    }

    public function post($path, $handler) {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Support both mod_rewrite and query parameter format
        if (isset($_GET['url'])) {
            $uri = '/' . $_GET['url'];
        } else {
            $uri = $_SERVER['REQUEST_URI'];
            if (strpos($uri, '?') !== false) {
                $uri = substr($uri, 0, strpos($uri, '?'));
            }
        }
        
        // Auto-detect base path from SCRIPT_NAME
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptDir !== '/' && $scriptDir !== '\\') {
            $uri = str_replace($scriptDir, '', $uri);
        }
        
        $originalUri = trim($uri, '/');
        
        if (empty($originalUri)) {
            $originalUri = '/';
        }

        $callback = null;

        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo "404 Not Found - No routes for method: $method";
            return;
        }

        foreach ($this->routes[$method] as $route => $handler) {
            $routeTrimmed = trim($route, '/');
            
            // Handle root route
            if ($routeTrimmed === '' && $originalUri === '/') {
                $callback = $handler;
                break;
            }
            
            if ($routeTrimmed === $originalUri) {
                $callback = $handler;
                break;
            }

            if (strpos($route, '{') !== false) {
                $routePattern = preg_replace('/\//', '\\/', $route);
                $routePattern = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $routePattern);
                $routePattern = '/^' . $routePattern . '$/';

                if (preg_match($routePattern, $originalUri, $matches)) {
                    $callback = $handler;
                    foreach ($matches as $key => $match) {
                        if (is_numeric($key)) {
                            unset($matches[$key]);
                        }
                    }
                    $_GET = array_merge($_GET, $matches);
                    break;
                }
            }
        }

        if ($callback === null) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        if (is_array($callback)) {
            $controllerName = $callback[0];
            $action = $callback[1];

            require_once "app/controllers/$controllerName.php";
            $controller = new $controllerName();

            if (isset($_GET['id'])) {
                $controller->$action($_GET['id']);
            } elseif (!empty($_GET)) {
                $params = array_values($_GET);
                $controller->$action(...$params);
            } else {
                $controller->$action();
            }
        } else {
            call_user_func($callback);
        }
    }
}
