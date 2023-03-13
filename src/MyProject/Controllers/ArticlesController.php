<?php

namespace MyProject\Controllers;

use MyProject\Models\Articles\Article;
use MyProject\View\View;
use MyProject\Models\Users\User;

class ArticlesController
{
    /** @var View */
    private $view;


    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates');
    }

    public function view(int $articleId)
    {

        $article = Article::getById($articleId);


        if ($article === null) {
            $this->view->renderHtml('errors/404.php', [], 404);
            return;
        }

        $this->view->renderHtml('articles/view.php', [
            'article' => $article
        ]);
    }
    public function edit(int $articleId): void
    {
        /** @var Article $article */
        $article = Article::getById($articleId);

        if ($article === null) {
            $this->view->renderHtml('errors/404.php', [], 404);
            return;
        }

        $article->setName('Новое название статьи');
        $article->setText('Новый текст статьи');


        $article->save();
    }

    public function add(): void
    {
        $author = User::GetById(1);

        $article = new Article();
        $article->setAuthor($author);
        $article->setName('Новое название статьи');
        $article->setText('Новый текст статьи');

        $article->save();

        echo '<pre>';
        var_dump($article);
        echo '</pre>';
    }

    public function delete($articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            $this->view->renderHtml('errors/notObject.php', [], 404);
            return;
        }
        echo 'Статья удалена!<br>';
        echo '<pre>';
        var_dump($article);
        echo '</pre>';

        $article->delete();
    }
}

?>
