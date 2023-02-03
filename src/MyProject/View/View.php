<?php

namespace MyProject\View;

class View
{
    private $templatesPath;

    public function __construct(string $templatesPath)
    {
        $this->templatesPath = $templatesPath;
    }

    public function renderHtml(string $templateName, array $vars = [], int $code = 200)
    {
        http_response_code($code);
        extract($vars);

        ob_start();
        include $this->templatesPath . '/' . $templateName;
        $buffer = ob_get_contents();
        ob_get_clean();


        /* дописать исключения (ошибки) */
        /* $error = "В шаблоне была ошибка!";
        
        if (empty($error)) {
        echo $buffer;
        } else {
        echo $error;
        } */

        echo $buffer;

    }

}

?>