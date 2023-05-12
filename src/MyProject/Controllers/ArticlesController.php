<?php

namespace MyProject\Controllers;

use InvalidArgumentException;
use MyProject\Models\Articles\Article;
use MyProject\Exceptions\UnauthorizedException;


class ArticlesController extends AbstractController
{

    public function view(int $articleId):void
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
        if ($this->user === null) {
            throw new UnauthorizedException();
        }
        if (!empty($_POST)) {
            try {
                $article = Article::createFromArray($_POST, $this->user);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/add.php', ['error' => $e->getMessage()]);
                return;
            }
            
            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }

        $this->view->renderHtml('articles/add.php');
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
