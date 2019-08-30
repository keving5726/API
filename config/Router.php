<?php

namespace Config;

class Router
{
    protected $basePath;
    protected $requestUri;
    protected $requestMethod;
    protected $supportedHttpMethods = array('GET', 'POST', 'PUT', 'DELETE');
    protected $wildCards = array('int' => '/^[0-9]+$/', 'any' => '/^[0-9A-Za-z]+$/');

    function __construct()
    {
        $this->basePath = '';
        $this->requestUri = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
        $this->requestMethod = $this->determineMethod();
    }

    private function determineMethod(): string
    {
        if (in_array($_SERVER['REQUEST_METHOD'], $this->supportedHttpMethods))
        {
            return $_SERVER['REQUEST_METHOD'];
        }
        else
        {
            return 'GET';
        }
    }

    public function respond(string $method, string $route, $callback)
    {
        if ($route == '/')
        {
            $route = $this->basePath;
        }
        else
        {
            $route = $this->basePath . $route;
        }

        $matches = $this->matchWildCards($route);

        if (is_array($matches) && $method == $this->requestMethod)
        {
            call_user_func_array($callback, $matches);
        }
    }

    private function matchWildCards($route)
    {
        $variables = array();

        $expRequest = explode('/', $this->requestUri);
        $expRoute = explode('/', $route);

        if (count($expRequest) == count($expRoute))
        {
            foreach ($expRoute as $key => $value)
            {
                if ($value == $expRequest[$key])
                {
                    continue;
                }
                elseif ($value[0] == '(' && substr($value, -1) == ')')
                {
                    $strip = str_replace(array('(', ')'), '', $value);
                    $exp = explode(':', $strip);

                    if (array_key_exists($exp[0], $this->wildCards))
                    {
                        $pattern = $this->wildCards[$exp[0]];

                        if (preg_match($pattern, $expRequest[$key]))
                        {
                            if (isset($exp[1]))
                            {
                                $variables[$exp[1]] = $expRequest[$key];
                            }
                            continue;
                        }
                    }
                }
                return false;
            }
            return $variables;
        }
        return false;
    }
}
