<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lessons;
use App\Models\DevicesPositions;

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
            // echo($curl_scraped_page);
            $devices = json_decode($curl_scraped_page);
            $device = collect($devices);
            $device_position_data = [];
            if ($device['status'] == 'online') {
                $device_position_data = collect($this->getPositionData($id));
                $server_preferences = collect($this->getServerData());
                $device_attributes = collect($device_position_data['attributes']);
                $server_atributes = collect($server_preferences['attributes']);
                $this->isHarchBrake($lesson->id, $id, $device_position_data['speed']);
                $this->isRapidAcceleration($lesson->id, $id, $device_position_data['speed']);
                DevicesPositions::create([
                    'device_id' => $id,
                    'lesson_id' => 1,
                    'speed' => $device_position_data['speed'],
                    'longitude' => $device_position_data['longitude'],
                    'latitude' => $device_position_data['latitude'],
                    'course' => $device_position_data['course'],
                    'distance' => $device_attributes['distance']
                ]);
                if($device_position_data['speed'])
                if ($device_position_data['speed'] > $server_atributes['speedLimit']){
                    echo "Device is moving out of limit ";
                    return $device_position_data;
                }else{
                    echo "Moving is stable. No problems ";
                    return $device_position_data;
                }
            }else{
                return "Check device connection. Now it's offline or disabled";
            }    
        }else{
            echo 'НЕТ ТАКОГО';
        }
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
        $max_speed = DevicesPositions::where('device_id', $lesson->device_id)
                                     ->whereBetween('created_at', [$lesson->lesson_start, $lesson->lesson_end])
                                     ->orderBy('speed', 'DESC')->first();
        
        $lesson->max_speed = round($max_speed->speed*1.831, 2);
        $lesson->save();
        echo round($max_speed->speed*1.831, 2);
    }

    public function getHarchBrakes($lesson_id){
        $lesson = Lessons::where('id', $lesson_id)->first();
        $positions = DevicesPositions::where('device_id', $lesson->device_id)->whereBetween('created_at', [$lesson->lesson_start, $lesson->lesson_end])->get();
        $previous_item = null;
        $brakes_counter = 0;
        foreach($positions as $item) {
            if($previous_item == null){
                $previous_item = $item;
            }else{
                $last_speed_data = round($previous_item->speed*1.831, 2);
                $speeds_differnce = $last_speed_data - round($item->speed*1.831,2); 
                if ($last_speed_data/100*70 <= $speeds_differnce){
                    $brakes_counter++;
                }
                $previous_item = $item;
            }
        }
        echo $brakes_counter;
        $lesson->harsh_braking_count = $brakes_counter;
        $lesson->save();
    }

    public function getRapidAccelerations($lesson_id){
        $lesson = Lessons::where('id', $lesson_id)->first();
        $positions = DevicesPositions::where('device_id', $lesson->device_id)->whereBetween('created_at', [$lesson->lesson_start, $lesson->lesson_end])->get();
        $previous_item = null;
        $accelerations_counter = 0;
        foreach($positions as $item) {
            if($previous_item == null){
                $previous_item = $item;
            }else{
                $last_speed_data = round($previous_item->speed*1.831, 2);
                if ($item->speed > $last_speed_data){
                    $speeds_differnce = round($item->speed*1.831, 2); - $last_speed_data; 
                    if($speeds_differnce >= 10){
                        $accelerations_counter++;
                    }
                }
                $previous_item = $item;
            }
        }
        echo $accelerations_counter;
        $lesson->rapid_acceleration_count = $accelerations_counter;
        $lesson->save();
    }

    public function getWideTurns($lesson_id){
        $lesson = Lessons::where('id', $lesson_id)->first();
        $positions = DevicesPositions::where('device_id', $lesson->device_id)->whereBetween('created_at', [$lesson->lesson_start, $lesson->lesson_end])->get();
        $previous_item = null;
        $wide_turns_counter = 0;
        $turns = [];
        $trend = '';
        foreach($positions as $item) {
            if($previous_item == null){
                $previous_item = $item;
            }else{
                
                if($previous_item->course >= $item->course){
                    $courses_difference = $previous_item->course - $item->course;
                    if($courses_difference >= 10){
                        if($trend == 'down' || $trend == ''){
                            $turns = [];
                            $trend == 'up';
                            if(count($turns) >= 9){
                                $wide_turns_counter++;
                            }
                            $turns[] = $item->course;
                        }else if($trend == 'up'){
                            $turns[] = $item->course;
                        }
                    }
                    var_dump($turns);
                }else{
                    $courses_difference = $item->course - $previous_item->course;
                    if($courses_difference >= 10){
                        if($trend == 'up' || $trend == ''){
                            $trend = 'down';
                            $turns = [];
                            if(count($turns) >= 9){
                                $wide_turns_counter++;
                            }
                            $turns[] = $item->course;
                        }else if($trend == 'down'){
                            $turns[] = $item->course;
                        }
                    }
                    var_dump($turns);
                }

                $previous_item = $item;
            }
        }
        echo $wide_turns_counter;
        $lesson->wide_turn_count = $wide_turns_counter;
        $lesson->save();
    }

    public function getFullDistance($lesson_id){
        $lesson = Lessons::where('id', $lesson_id)->first();
        return DevicesPositions::where('device_id', $lesson->device_id)->whereBetween('created_at', [$lesson->lesson_start, $lesson->lesson_end])->sum('distance');
    }

}   