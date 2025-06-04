<?php
$url = $_SERVER['REQUEST_URI'];
$url = strtok($url, '?');
$base_folder = '/kltn'; // tên dự án
$request = str_replace($base_folder, '', $url);

$routes = [
    '/' => 'HomeController@index',
    '/products' => 'ProductController@index',

    '/department' => 'DepartmentController@index',
    '/department/create' => 'DepartmentController@create',
    '/department/edit' => 'DepartmentController@edit',
    '/department/delete' => 'DepartmentController@delete',

    '/user/login' => 'UserController@login',
];

// Custom router function
function route($request, $routes) {
    foreach ($routes as $pattern => $handler) {
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<$1>[^\/]+)', $pattern);
        if (preg_match('/^' . $pattern . '$/', $request, $matches)) {
            return ['handler' => $handler, 'params' => array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY)];
        }
    }
    return null;
}

$match = route($request, $routes);

if ($match) {
    $controllerAction = explode('@', $match['handler']);
    $controllerName = $controllerAction[0];
    $methodName = $controllerAction[1];
    require_once "controller/$controllerName.php";
    $controller = new $controllerName();
    call_user_func_array([$controller, $methodName], $match['params']);
} else {
    http_response_code(404);
    echo "404 - Không tìm thấy trang";
}