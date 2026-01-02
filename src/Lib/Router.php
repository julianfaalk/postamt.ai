<?php
/**
 * Simple PHP Router
 */

class Router
{
    private array $routes = [];
    private array $middleware = [];

    public function get(string $path, callable $handler): self
    {
        return $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): self
    {
        return $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, callable $handler): self
    {
        return $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, callable $handler): self
    {
        return $this->addRoute('DELETE', $path, $handler);
    }

    public function addMiddleware(callable $middleware): self
    {
        $this->middleware[] = $middleware;
        return $this;
    }

    private function addRoute(string $method, string $path, callable $handler): self
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $this->pathToPattern($path),
            'handler' => $handler,
        ];
        return $this;
    }

    private function pathToPattern(string $path): string
    {
        // Convert {param} to named capture groups
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Run middleware
        foreach ($this->middleware as $middleware) {
            $result = $middleware($method, $uri);
            if ($result === false) {
                return;
            }
        }

        // Find matching route
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                // Extract named parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                try {
                    $result = ($route['handler'])($params);
                    if ($result !== null) {
                        $this->sendResponse($result);
                    }
                } catch (Exception $e) {
                    $this->handleError($e);
                }
                return;
            }
        }

        // No route found
        $this->notFound();
    }

    private function sendResponse($data): void
    {
        if (is_array($data)) {
            header('Content-Type: application/json');
            echo json_encode($data);
        } elseif (is_string($data)) {
            echo $data;
        }
    }

    private function notFound(): void
    {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not found']);
    }

    private function handleError(Exception $e): void
    {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        header('Content-Type: application/json');

        $response = ['error' => $e->getMessage()];
        if (APP_DEBUG) {
            $response['trace'] = $e->getTraceAsString();
        }

        echo json_encode($response);
    }
}
