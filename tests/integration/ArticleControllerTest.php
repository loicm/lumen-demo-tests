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

    /** @test */
    public function it_stores_a_new_article()
    {
        # 1. Mettre en place le contexte
        $article = factory(App\Article::class)->make();

        # 2. Effectuer l'appel sur l'API
        $this->post('/article', [
            'title' => $article->title,
            'description' => $article->description,
        ]);

        # 3. Réaliser les assertions
        $this->seeJson([
            'title' => $article->title,
            'description' => $article->description
        ]);
        $this->seeJsonStructure([
            'id',
        ]);

        $returned_article = json_decode($this->response->getContent());
        $this->seeInDatabase('articles', [
            'id' => $returned_article->id,
            'title' => $article->title,
            'description' => $article->description,
        ]);

        $this->assertEquals(1, App\Article::count());
    }

    /** @test */
    public function it_can_updates_an_article()
    {
        # 1. Mettre en place le contexte
        $article = factory(App\Article::class)->create();

        $article->title = 'The title is updated';
        $article->description = 'The description is updated';

        # 2. Effectuer l'appel sur l'API
        $this->put('/article/' . $article->id, [
            'title' => $article->title,
            'description' => $article->description,
        ]);

        # 3. Réaliser les assertions
        $article_in_db = App\Article::find($article->id);
        $this->assertEquals($article_in_db->title, $article->title);
        $this->assertEquals($article_in_db->description, $article->description);

        $this->seeJson([
            'id' => $article->id,
            'title' => $article->title,
            'description' => $article->description,
        ]);
    }

    /** @test */
    public function it_deletes_an_article()
    {
        # 1. Mettre en place le contexte
        $article = factory(App\Article::class)->create();
        $article2 = factory(App\Article::class)->create();

        # 2. Effectuer l'appel sur l'API
        $this->delete('/article/' . $article->id);

        # 3. Réaliser les assertions
        $this->seeJson([
            'success' => true,
        ]);

        $this->seeInDatabase('articles', [ 'id' => $article2->id ]);

        $this->assertEquals(1, App\Article::count());
    }
}
