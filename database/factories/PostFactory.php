<?php

namespace Database\Factories;

use App\Models\Agency;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $rand_user = User::all()->random();

        $estate_type = $this->faker->randomElement(['new_apartment', 'apartment', 'house_villa', 'office', 'garage', 'land']);
        $trade_type = $this->faker->randomElement(['sell', 'rent']);

        if($estate_type == 'land') {
            $area_unit = 'land';
        }
        else {
            $area_unit = 'room';
        }

        $numSell = $this->faker->numberBetween($min = 50000, $max = 1000000, 'linearLow');
        $numSell = $numSell - ($numSell % 100);

        $numRent = $this->faker->numberBetween($min = 100, $max = 1500, 'linearLow');
        $numRent = $numRent - ($numRent % 10);

        $apartmentFloor = $this->faker->numberBetween($min = 1, $max = 20);
        $totalFloors = $this->faker->numberBetween($min = 5, $max = 20);

        $temp = 0;

        if($totalFloors < $apartmentFloor) {
            $temp = $totalFloors;
            $totalFloors = $apartmentFloor;
            $apartmentFloor = $temp;
        }

        // $realtorType = $this->faker->randomElement('self');

        if($rand_user->agency) {
            // give 50% chance to create own post if user is in agency
            $realtorType = $this->faker->randomElement(['self', 'agent']);
        }
        else {
            // if user does not have agency just create own post
            $realtorType = $this->faker->randomElement(['self']);
        }

        $latitude = $this->faker->randomFloat(3, 39.6, 41.17); // yuxari-asagi
        $longitude = $this->faker->randomFloat(3, 46.3, 49.8); // sol-sag

        return [
            'user_id' => $rand_user,
            'agency_id' => $rand_user->agency,

            'estate_type' => $estate_type,

            'latitude' => $latitude,
            'longitude' => $longitude,

            'city' => $this->faker->randomElement(['Bakı', 'Sumqayıt', 'Gəncə']),
            'area' => $this->faker->numberBetween($min = 10, $max = 500),
            'area_unit' => $area_unit,
            'room_count' => $this->faker->numberBetween($min = 1, $max = 20),
            'address' => $this->faker->address(),
            'district' => $this->faker->city(),

            'apartment_floor' => $apartmentFloor,
            'total_floors' => $totalFloors,

            'description' => $this->faker->text(),
            'price' => $trade_type == 'sell' ? $numSell : $numRent,

            'contact_email' => $this->faker->safeEmail(),
            'contact_phone_number' => $this->faker->phoneNumber(),
            'realtor_name' => null,

            'trade_type' => $trade_type,
            'realtor_type' => $realtorType,

            'is_vip' => $this->faker->boolean(25),
            'has_voucher' => $this->faker->boolean(50),

            'views_total' => $this->faker->numberBetween($min = 10, $max = 100),
            'views_today' => $this->faker->numberBetween($min = 0, $max = 10),
            
            'updated_at' => $this->faker->dateTimeBetween($startDate='-5 years', $endDate='now'),
            'created_at' => $this->faker->dateTimeBetween($startDate='-5 years', $endDate='now'),
        ];
    }
}
