<? include __DIR__ . '/../header.php'; ?>
<?


foreach ($articles as $article){ ?>

    <h2><a href="/www/articles/<?= $article->getId() ;?>"><?= $article->getName() ;?></a></h2>
    <p><?= $article->getText() ?></p>
    <hr>

<? } ?>

<? include __DIR__ . '/../footer.php'; ?>