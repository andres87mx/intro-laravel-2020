<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Post;

class PostConstrollerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store()
    {
        $this->withoutExceptionHandling();
        $response = $this->json('POST', '/api/posts', [
            'title' => 'El post de prueba'
        ]);

        $response->assertJsonStructure(['id','title','created_at','updated_at'])
            ->assertJson(['title' => 'El post de prueba'])
            ->assertStatus(201); // 201 Completado de manera OK y creado un recurso
        
        $this->assertDatabaseHas('posts', ['title' => 'El post de prueba']);
    }

    public function test_validate_title()
    {
        $response = $this->json('POST', '/api/posts', [
            'title' => ''
        ]);
        $response->assertStatus(422) //Estatus HTTP 422
            ->assertJsonValidationErrors('title');
    }

    public function test_show()
    {
        $post = factory(Post::class)->create();
        
        $response = $this->json('GET', "/api/posts/$post->id");

        $response->assertJsonStructure(['id','title','created_at','updated_at'])
            ->assertJson(['title' => $post->title])
            ->assertStatus(200); 
        
    }

    public function test_404_show()
    {
        $response = $this->json('GET', "/api/posts/1000");

        $response->assertStatus(404); 
        
    }

    public function test_update()
    {
        $this->withoutExceptionHandling();
        //vendor/bin/phpunit --filter test_update ->UNICO TEST
        $post = factory(Post::class)->create();

        $response = $this->json('PUT', "/api/posts/$post->id", [
            'title' => 'NUEVO POST'
        ]);

        $response->assertJsonStructure(['id','title','created_at','updated_at'])
            ->assertJson(['title' => 'NUEVO POST'])
            ->assertStatus(200);
        
        $this->assertDatabaseHas('posts', ['title' => 'NUEVO POST']);
    }

    public function test_delete()
    {
        //$this->withoutExceptionHandling();

        $post = factory(Post::class)->create();

        $response = $this->json('DELETE', "/api/posts/$post->id");

        $response->assertSee(null)
            ->assertStatus(204); //Sin contenido
        
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_index()
    {
        factory(Post::class, 5)->create();

        $response = $this->json('GET', '/api/posts');

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'created_at', 'updated_at']
            ]
        ])->assertStatus(200);
    }
}
