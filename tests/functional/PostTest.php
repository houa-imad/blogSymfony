<?php
namespace App\Tests\Functional;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostTest extends WebTestCase
{
    public function testBlogPage(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);


        $this->assertSelectorExists('h1');
        $this->assertSelectorTextContains('h1', 'Blog');
    }

    public function testPagination():void{
        $client = static::createClient();
        $crawler =$client->request(Request::METHOD_GET, '/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $posts = $crawler->filter('div.block');
        $this->assertEquals(10, $posts->count());

        $link =$crawler->selectLink('2')->extract(['href']);
        $crawler = $client->request(Request::METHOD_GET, $link[0]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $posts = $crawler->filter('div.block');
        $this->assertGreaterThanOrEqual(1, $posts->count());
    }
}