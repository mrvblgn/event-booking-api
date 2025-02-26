<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Concretes\SeatService;
use App\Repositories\Abstracts\ISeatRepository;
use App\Models\Entities\Seat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SeatServiceTest extends TestCase
{
    use RefreshDatabase;

    private SeatService $seatService;
    private $seatRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Test database'ini hazırla
        $this->artisan('migrate:fresh');
        
        // Mock'ları hazırla
        $this->seatRepository = Mockery::mock(ISeatRepository::class);
        $this->seatService = new SeatService($this->seatRepository);
    }

    public function test_block_seats_successfully()
    {
        // Arrange
        $seatIds = [1, 2];
        $seats = collect([
            new Seat(['id' => 1, 'status' => 'available']),
            new Seat(['id' => 2, 'status' => 'available'])
        ]);

        // Mock DB transaction
        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        // Mock auth()->id()
        Auth::shouldReceive('id')->once()->andReturn(1);

        // Mock repository calls
        $this->seatRepository->shouldReceive('lockForUpdate->findMany')
            ->with($seatIds)
            ->andReturn($seats);

        $this->seatRepository->shouldReceive('updateMany')
            ->withArgs(function ($actualSeatIds, $data) use ($seatIds) {
                return $actualSeatIds === $seatIds 
                    && $data['status'] === 'blocked'
                    && isset($data['blocked_at'])
                    && $data['blocked_by'] === 1;
            })
            ->andReturn(true);

        // Act
        $result = $this->seatService->blockSeats($seatIds);

        // Assert
        $this->assertTrue($result);
    }

    public function test_block_seats_when_some_seats_unavailable()
    {
        // Arrange
        $seatIds = [1, 2];
        $seats = collect([
            new Seat(['id' => 1, 'status' => 'available']),
            new Seat(['id' => 2, 'status' => 'blocked'])
        ]);

        // Mock DB transaction
        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->seatRepository->shouldReceive('lockForUpdate->findMany')
            ->with($seatIds)
            ->andReturn($seats);

        // Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Some seats are not available');

        // Act
        $this->seatService->blockSeats($seatIds);
    }

    public function test_release_seats_successfully()
    {
        // Arrange
        $seatIds = [1, 2];

        $this->seatRepository->shouldReceive('releaseSeats')
            ->with($seatIds)
            ->andReturn(true);

        // Act
        $result = $this->seatService->releaseSeats($seatIds);

        // Assert
        $this->assertTrue($result);
    }

    public function test_release_seats_with_empty_array()
    {
        // Arrange
        $seatIds = [];

        // Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No seats selected');

        // Act
        $this->seatService->releaseSeats($seatIds);
    }

    public function test_get_venue_seats_successfully()
    {
        // Arrange
        $venueId = 1;
        $seats = collect([
            new Seat(['id' => 1, 'venue_id' => 1]),
            new Seat(['id' => 2, 'venue_id' => 1])
        ]);

        $this->seatRepository->shouldReceive('getVenueSeats')
            ->with($venueId)
            ->andReturn($seats);

        // Act
        $result = $this->seatService->getVenueSeats($venueId);

        // Assert
        $this->assertEquals(2, $result->count());
    }

    public function test_get_venue_seats_when_empty()
    {
        // Arrange
        $venueId = 1;
        $seats = collect([]);

        $this->seatRepository->shouldReceive('getVenueSeats')
            ->with($venueId)
            ->andReturn($seats);

        // Assert
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('No seats found for this venue');

        // Act
        $this->seatService->getVenueSeats($venueId);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 