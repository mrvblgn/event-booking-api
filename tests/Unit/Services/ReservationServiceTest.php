<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Concretes\ReservationService;
use App\Repositories\Abstracts\IReservationRepository;
use App\Repositories\Abstracts\ISeatRepository;
use App\Repositories\Abstracts\IEventRepository;
use App\Models\Dtos\Reservations\Requests\CreateReservationRequestDto;
use App\Models\Entities\Event;
use App\Models\Entities\Seat;
use App\Models\Entities\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ReservationServiceTest extends TestCase
{
    use RefreshDatabase;

    private ReservationService $reservationService;
    private $reservationRepository;
    private $seatRepository;
    private $eventRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->reservationRepository = Mockery::mock(IReservationRepository::class);
        $this->seatRepository = Mockery::mock(ISeatRepository::class);
        $this->eventRepository = Mockery::mock(IEventRepository::class);
        
        $this->reservationService = new ReservationService(
            $this->reservationRepository,
            $this->seatRepository,
            $this->eventRepository
        );
    }

    public function test_create_reservation_with_valid_data()
    {
        // Arrange
        $seats = collect([
            new Seat(['id' => 1, 'status' => 'available']),
            new Seat(['id' => 2, 'status' => 'available'])
        ]);

        $dto = new CreateReservationRequestDto(
            event_id: 1,
            seat_ids: [1, 2]
        );

        $reservation = new Reservation([
            'id' => 1,
            'user_id' => 1,
            'event_id' => 1,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(15),
            'total_amount' => 200.00
        ]);

        // Mock repository calls
        $this->seatRepository->shouldReceive('lockForUpdate->findMany')
            ->with([1, 2])
            ->andReturn($seats);

        $this->seatRepository->shouldReceive('updateMany')
            ->with([1, 2], ['status' => 'reserved'])
            ->andReturn(true);

        $this->reservationRepository->shouldReceive('create')
            ->andReturn($reservation);

        $this->reservationRepository->shouldReceive('createItem')
            ->twice()
            ->andReturn(true);

        // Act
        $result = $this->reservationService->createReservation($dto);

        // Assert
        $this->assertEquals(1, $result->getId());
        $this->assertEquals('pending', $result->getStatus());
    }

    public function test_create_reservation_with_unavailable_seats()
    {
        // Arrange
        $seats = collect([
            new Seat(['id' => 1, 'status' => 'available']),
            new Seat(['id' => 2, 'status' => 'reserved'])
        ]);

        $dto = new CreateReservationRequestDto(
            event_id: 1,
            seat_ids: [1, 2]
        );

        $this->seatRepository->shouldReceive('lockForUpdate->findMany')
            ->with([1, 2])
            ->andReturn($seats);

        // Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Some seats are not available');

        // Act
        $this->reservationService->createReservation($dto);
    }
} 