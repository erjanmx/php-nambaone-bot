<?php 

namespace erjanmx\nambaone\Tests;

use erjanmx\nambaone\Client;
use erjanmx\nambaone\Message;
use erjanmx\nambaone\EventsHandler;

class ClientUnitTest extends \PHPUnit_Framework_TestCase
{
    public $client;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->client = new Client('api_token');
    }

    /**
     * @covers Client
     * @expectedException \InvalidArgumentException
     */
    public function testCreateClientWithBadHandler()
    {
        $client = new Client('api_token', 'handler');
    }

    /**
     * @covers Client
     */
    public function testClientWithoutDefaultHandler()
    {
        $this->assertInstanceOf(EventsHandler::class, $this->client->getEventsHandler());
    }

    /**
     * @covers Client
     */
    public function testEventAssign()
    {
        $handler = $this->client->getEventsHandler();

        $this->client->command('on/follow', function () {});
        
        $this->assertArrayHasKey('on/follow', $handler->events);
    }
}
