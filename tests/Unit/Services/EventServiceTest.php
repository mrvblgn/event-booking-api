<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Concretes\EventService;
use App\Repositories\Abstracts\IEventRepository;
use App\Models\Entities\Event;
use App\Models\Entities\Venue;
use App\Models\Dtos\Events\Requests\CreateEventRequestDto;
use App\Models\Dtos\Events\Requests\UpdateEventRequestDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Mockery;

class EventServiceTest extends TestCase
{
    use RefreshDatabase;

    private EventService $eventService;
    private $eventRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eventRepository = Mockery::mock(IEventRepository::class);
        $this->eventService = new EventService($this->eventRepository);
    }

    public function test_create_event_successfully()
    {
        // Arrange
        $dto = new CreateEventRequestDto(
            name: 'Test Event',
            description: 'Test Description',
            venue_id: 1,
            start_date: '2024-04-01 10:00:00',
            end_date: '2024-04-01 12:00:00',
            status: 'draft'
        );

        $venue = new Venue([
            'id' => 1,
            'name' => 'Test Venue',
            'address' => 'Test Address'
        ]);

        $event = new Event([
            'id' => 1,
            'name' => 'Test Event',
            'description' => 'Test Description',
            'venue_id' => 1,
            'start_date' => '2024-04-01 10:00:00',
            'end_date' => '2024-04-01 12:00:00',
            'status' => 'draft'
        ]);
        $event->setRelation('venue', $venue);

        $this->eventRepository->shouldReceive('create')
            ->with($dto->toArray())
            ->andReturn($event);

        // Act
        $result = $this->eventService->createEvent($dto);

        // Assert
        $this->assertEquals('Test Event', $result->getName());
        $this->assertEquals('draft', $result->getStatus());
    }

    public function test_update_event_successfully()
    {
        // Arrange
        $eventId = 1;
        $dto = new UpdateEventRequestDto(
            name: 'Updated Event',
            status: 'published'
        );

        $venue = new Venue([
            'id' => 1,
            'name' => 'Test Venue',
            'address' => 'Test Address'
        ]);

        $event = new Event([
            'id' => 1,
            'name' => 'Updated Event',
            'description' => 'Updated Description',
            'venue_id' => 1,
            'start_date' => '2024-04-01 10:00:00',
            'end_date' => '2024-04-01 12:00:00',
            'status' => 'published'
        ]);
        $event->setRelation('venue', $venue);

        // Carbon'a çevir
        $event->start_date = \Carbon\Carbon::parse($event->start_date);
        $event->end_date = \Carbon\Carbon::parse($event->end_date);

        $this->eventRepository->shouldReceive('find')
            ->with($eventId)
            ->andReturn($event);

        $this->eventRepository->shouldReceive('update')
            ->with($event, $dto->toArray())
            ->andReturn($event);

        // Act
        $result = $this->eventService->updateEvent($eventId, $dto);

        // Assert
        $this->assertEquals('Updated Event', $result->getName());
        $this->assertEquals('published', $result->getStatus());
    }

    public function test_update_nonexistent_event()
    {
        // Arrange
        $eventId = 999;
        $dto = new UpdateEventRequestDto(
            name: 'Updated Event'
        );

        $this->eventRepository->shouldReceive('find')
            ->with($eventId)
            ->andReturn(null);

        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->eventService->updateEvent($eventId, $dto);
    }

    public function test_get_all_events_with_cache()
    {
        // Arrange
        $events = collect([
            new Event(['id' => 1, 'name' => 'Event 1']),
            new Event(['id' => 2, 'name' => 'Event 2'])
        ]);

        Cache::shouldReceive('remember')
            ->once()
            ->withArgs(function ($key, $ttl, $callback) {
                return $key === 'events.all' && $ttl === 3600;
            })
            ->andReturn($events);

        // Act
        $result = $this->eventService->getAllEvents();

        // Assert
        $this->assertEquals(2, $result->count());
    }

    public function test_delete_event_successfully()
    {
        // Arrange
        $eventId = 1;

        $this->eventRepository->shouldReceive('delete')
            ->with($eventId)
            ->andReturn(true);

        // Act
        $result = $this->eventService->deleteEvent($eventId);

        // Assert
        $this->assertTrue($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 