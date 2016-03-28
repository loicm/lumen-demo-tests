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
}
