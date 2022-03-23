<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lessons;
use App\Models\TcPositions;
use App\Models\Brakes;
use App\Models\Accelerations;
use App\Models\WideTurns;
use App\Models\TcDevices;

class TraccarController extends Controller
{

    public function devices($id){
        if(Lessons::where('device_id', $id)->where('grade', 0)->count() > 0){
            $lesson = Lessons::where('device_id', $id)->first();
            
            $url = 'http://5.101.119.123:8082/api/devices/'.$id;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "admin:admin");
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $curl_scraped_page = curl_exec($ch);
            curl_close($ch);
            $devices = json_decode($curl_scraped_page);
            $device = collect($devices);
            $device_position_data = [];
            if ($device['status'] == 'online') {
                $device_last_position = TcPositions::where('deviceid', $id)->first();
                $device_attributes = collect($device_last_position->attributes);

                $device_position_data = collect($this->getPositionData($id));
                $server_preferences = collect($this->getServerData());
            }else{
                 return "Check device connection. Now it's offline or disabled";
            }    
        }else{
            echo 'There is not this lesson';
        }
    }

    public function getCurrentPositionByLesson($id){
        $lesson = Lessons::where('id', $id)->first();

        $device_last_position = TcPositions::where('deviceid', $id)->first();
        $device_attributes = collect($device_last_position->attributes);

        $brakes = $this->getHarchBrakes($id);
        $accelerations = $this->getRapidAccelerations($id);
        $turns = $this->getWideTurns($id);

        return [$device_last_position, $brakes, $accelerations, $turns];
    }

    public function getServerData(){
        $url = 'http://5.101.119.123:8082/api/server';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "admin:admin");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
        // echo($curl_scraped_page);
        $server_data = json_decode($curl_scraped_page);
        $server_data = collect($server_data);
        return $server_data;
    }

    public function getPositionData($id){
        $url = 'http://5.101.119.123:8082/api/positions';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "admin:admin");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
        // echo($curl_scraped_page);
        $positions = json_decode($curl_scraped_page);
        $positions = collect($positions);
        $positions = $positions->where('deviceId', $id)->first();
        return $positions;
    }

    public function getMaxSpeedByLesson($lesson_id){
        $lesson = Lessons::where('id', $lesson_id)->first();
        $max_speed = TcPositions::where('deviceid', $lesson->device_id)
                                     ->whereBetween('devicetime', [$lesson->lesson_start, $lesson->lesson_end])
                                     ->orderBy('speed', 'DESC')->first();
        
        $lesson->max_speed = round($max_speed->speed*1.85, 2);
        $lesson->save();
        echo round($max_speed->speed*1.85, 2);
    }

    public function getHarchBrakes($lesson_id){
        $lesson = Lessons::where('id', $lesson_id)->first();
        $positions = TcPositions::where('deviceid', $lesson->device_id)->whereBetween('devicetime', [$lesson->lesson_start, $lesson->lesson_end])->get();
        $previous_item = null;
        $brakes_counter = 0;
        $harch_brakes_time_score = 3;
        foreach($positions as $item) {
            if($previous_item == null){
                $previous_item = $item;
            }else{
                $last_speed_data = round($previous_item->speed*1.852, 2);
                $speeds_differnce = $last_speed_data - round($item->speed*1.852,2); 
                if ($speeds_differnce > 9){
                    if($harch_brakes_time_score == 4){
                        if(round($item->speed*1.852,2) > 15){
                            $brakes_counter++;
                            if (Brakes::where('lesson_id', $id)->where('longitude', $item->longitude)->where('latitude', $item->latitude)->count() == 0){
                                Brakes::create([
                                    'lesson_id' => $lesson->id,
                                    'longitude' => $item->longitude,
                                    'latitude' => $item->latitude
                                ]);    
                            }
                        }
                        
                        $harch_brakes_time_score--;
                    }else{
                        $harch_brakes_time_score--;
                        if($harch_brakes_time_score == 0){
                            $harch_brakes_time_score = 4;
                        }
                    }
                    
                }
                $previous_item = $item;
            }
        }
        echo $brakes_counter;
        $lesson->harsh_braking_count = $brakes_counter;
        $lesson->save();

        return Brakes::where('lesson_id', $lesson_id)->get();
    }

    public function getRapidAccelerations($lesson_id){
        $lesson = Lessons::where('id', $lesson_id)->first();
        $positions = TcPositions::where('deviceid', $lesson->device_id)->whereBetween('devicetime', [$lesson->lesson_start, $lesson->lesson_end])->get();
        $previous_item = null;
        $accelerations_counter = 0;
        $rapid_acceleration_time_score = 3;
        foreach($positions as $item) {
            if($previous_item == null){
                $previous_item = $item;
            }else{
                $last_speed_data = round($previous_item->speed*1.852, 2);
                if ($item->speed > $last_speed_data){
                    $speeds_differnce = round($item->speed*1.852, 2) - $last_speed_data; 
                    if($speeds_differnce >= 9){
                        if (Accelerations::where('lesson_id', $id)->where('longitude', $item->longitude)->where('latitude', $item->latitude)->count() == 0){
                            Accelerations::create([
                                'lesson_id' => $lesson->id,
                                'longitude' => $item->longitude,
                                'latitude' => $item->latitude
                            ]);    
                        }
                        $accelerations_counter++;
                    }
                }
                $previous_item = $item;
            }
        }
        echo $accelerations_counter;
        $lesson->rapid_acceleration_count = $accelerations_counter;
        $lesson->save();

        return Accelerations::where('lesson_id', $lesson_id)->get();
    }

    public function getWideTurns($lesson_id){
        $lesson = Lessons::where('id', $lesson_id)->first();
        $positions = TcPositions::where('deviceid', $lesson->device_id)->whereBetween('devicetime', [$lesson->lesson_start, $lesson->lesson_end])->get();
        $previous_item = null;
        $wide_turns_counter = 0;

        foreach($positions as $item) {

            if($item->speed*1.852 > 10){
                if($previous_item == null){
                    $previous_item = $item;
                }else{
                    if ($previous_item->course > $item->course){
                        if ($previous_item->course - $item->course >= 60){
                            if (WideTurns::where('lesson_id', $id)->where('longitude', $item->longitude)->where('latitude', $item->latitude)->count() == 0){
                                WideTurns::create([
                                    'lesson_id' => $lesson->id,
                                    'longitude' => $item->longitude,
                                    'latitude' => $item->latitude
                                ]);    
                            }
                            $wide_turns_counter++;
                        }
                    }else{
                        if ($item->course-$previous_item->course >= 60){
                            if (WideTurns::where('lesson_id', $id)->where('longitude', $item->longitude)->where('latitude', $item->latitude)->count() == 0){
                                WideTurns::create([
                                    'lesson_id' => $lesson->id,
                                    'longitude' => $item->longitude,
                                    'latitude' => $item->latitude
                                ]);    
                            }
                            $wide_turns_counter++;
                        }
                    }
                    
                }
            }
            $previous_item = $item;
        }

        echo $wide_turns_counter;
        $lesson->wide_turn_count = $wide_turns_counter;
        $lesson->save();

        return WideTurns::where('lesson_id', $lesson_id)->get();
    }

    public function getFullDistance($lesson_id){
        $lesson = Lessons::where('id', $lesson_id)->first();
        $attributes = TcPositions::where('deviceid', $lesson->device_id)->whereBetween('devicetime', [$lesson->lesson_start, $lesson->lesson_end])->pluck('attributes');
        $total_distance = 0;
        foreach($attributes as $item){
            $item = collect(json_decode($item));
            $total_distance += $item['distance'];
        }
        
        return round($total_distance, 2);
    }

    public function getLessonPositions($lesson_id){
        $lesson = Lessons::where('id', $lesson_id)->first();
        return TcPositions::where('deviceid', $lesson->device_id)->whereBetween('devicetime', [$lesson->lesson_start, $lesson->lesson_end])->get();
    }

    public function getAllDevices(){
        return TcDevices::all();
    }
}   