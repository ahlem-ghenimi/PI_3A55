<?php


namespace App\Controller;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\BotManFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use BotMan\BotMan\Interfaces\CacheInterface;

class ChatbotController extends AbstractController
{
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    #[Route('/chatbot', name: 'chatbot')]
    public function handleChatbot(Request $request): Response
    {
        // Configure Botman
        $config = [];
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
        $botman = BotManFactory::create($config, $this->cache);

        // Define chatbot responses
        $botman->hears('Hello', function (BotMan $bot) {
            $bot->reply('Hello! How can I assist you with product details?');
        });

        $botman->hears('Product {id}', function (BotMan $bot, $id) {
            // Fetch product details from database (example)
            $product = ['id' => $id, 'name' => 'Product Name', 'price' => '$100'];
            $bot->reply("Product ID: {$product['id']}, Name: {$product['name']}, Price: {$product['price']}");
        });

        // Start listening
        $botman->listen();

        return new Response();
    }
}
