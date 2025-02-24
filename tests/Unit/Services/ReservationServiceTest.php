<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Concretes\ReservationService;
use App\Repositories\Abstracts\IReservationRepository;
use App\Models\DTOs\Reservations\Requests\CreateReservationRequestDto;
use Mockery;

class ReservationServiceTest extends TestCase
{
    private $reservationRepository;
    private $reservationService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->reservationRepository = Mockery::mock(IReservationRepository::class);
        $this->reservationService = new ReservationService($this->reservationRepository);
    }

    public function test_create_reservation_success()
    {
        // Arrange
        $dto = new CreateReservationRequestDto(1, [1, 2, 3]);
        
        // Assert
        $this->reservationRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn(/* mock reservation */);

        // Act
        $result = $this->reservationService->createReservation($dto);

        // Assert
        $this->assertNotNull($result);
    }
} 