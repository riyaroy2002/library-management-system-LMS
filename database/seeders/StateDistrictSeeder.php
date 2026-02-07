<?php

namespace Database\Seeders;

use App\Models\State;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StateDistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('json/states-districts.json'));
        $data = json_decode($json, true);
        $states = $data['states'];
        foreach ($states as $state) {
            $stateId = State::insertGetId([
                'state' => $state['state'],
                'sid'   => $state['sid']
            ]);
            $districts = $state['districts'];
            foreach ($districts as $districtName) {
                District::create([
                    'state_id' => $stateId,
                    'name'     => $districtName,
                ]);
            }
        }
    }
}
