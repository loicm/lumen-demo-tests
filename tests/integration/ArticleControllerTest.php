<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class ArticleControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_fetch_an_article()
    {
        # 1. Mettre en place le contexte
        $article = factory(App\Article::class)->create();

        # 2. Effectuer l'appel sur l'API
        $this->get('/article/' . $article->id);

        # 3. Réaliser les assertions
        $this->seeStatusCode(200);
        $this->seeJson([
            'id' => $article->id,
            'title' => $article->title,
            'description' => $article->description,
        ]);
    }

    /** @test */
    public function it_returns_a_404_if_article_doesnot_exist()
    {
        # 1. Mettre en place le contexte
        # Rien à faire, on ne veut pas d'article dans la base de données

        # 2. Effectuer l'appel sur l'API
        # On demande donc un article qui n'existe pas
        $this->get('/article/123');

        # 3. Réaliser les assertions
        $this->seeStatusCode(404);
    }
}
