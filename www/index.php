<?php
try {

        /* подключаем все классы, которые встречаются на пути */
    spl_autoload_register(function (string $className) {
        require_once __DIR__ . '/../src/' . $className . '.php';
    });

    /**
     * если правила роутинга в .htaccess не сработали
     * то берем данные из параметра route (идёт из .htaccess)
     * */

    $route = $_GET['route'] ?? '';

    // подключаем перенаправления
    $routes = require __DIR__ . '/../src/routes.php';

    $isRouteFound = false;

    foreach ($routes as $pattern => $controllerAndAction) {

        /* ищем совпадения по регулярке и пишем в $matches */
        preg_match($pattern, $route, $matches);

        /* как нашли - прерываем цикл */
        if (!empty($matches)) {

            $isRouteFound = true;
            break;

        }
    }

    if (!$isRouteFound) {
        echo 'Страница не найдена!';
        return;
    }

    /* удаляем полное совпадение по паттерну 
    *(полный путь ни к чему)
    */

    unset($matches[0]);

    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];

    $controller = new $controllerName();
    $controller->$actionName(...$matches);


} catch (\MyProject\Exceptions\DbException $e) {
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('500.php', ['error'=> $e->getMessage()], 500);

} catch (\MyProject\Exceptions\UnauthorizedException $e) {
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('401.php', ['error' => $e->getMessage()], 401);
} catch (\MyProject\Exceptions\Forbidden $e) {
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('403.php', ['error' => $e->getMessage()], 403);
}


?>