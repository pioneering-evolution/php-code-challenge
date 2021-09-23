<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PerformerController extends Controller
{
    public function index()
    {
        //Get the path to the json file
        $jsonFile = "C:\wamp64\www\php-code-challenge-master\data.json";
        //Decode the json file
        $json = json_decode(file_get_contents($jsonFile), true);

        //Initialize an array that will be used to push each performer and their attributes
        $performerArray = null;

        //Split into the two different labor groups **  Documentation had a typo with non-labor is actually non_labor **
        $laborGroup = $json['cost_data']['data']['labor']['groups'];
        $nonLaborGroup = $json['cost_data']['data']['non_labor']['groups'];

        // Organize the json file - Separate out the needed data lets do labor performers first
        foreach($laborGroup as $performer)
        {
            $display_name = $performer['performer']['display_name'];
            $performer_id = $performer['performer']['id'];
            $performerArray[] = array('display_name' => $display_name, 'id' => $performer_id);
        }
        // Convert the array of performers into a collection for easier searching - may not be needed
        $performerCollection = collect($performerArray);

        // Now lets get the performers for the non_labor group. If performer is already in the labor group we don't add to the list
        foreach($nonLaborGroup as $performer)
        {
            $display_name = $performer['performer']['display_name'];
            $performer_id = $performer['performer']['id'];
            if($performerCollection->contains('id', $performer_id))
            {
                // No need to add it to the performer list
            }
            else
            {
                //Push to the array and convert to collection again list
                $performerArray[] = array('display_name' => $display_name, 'id' => $performer_id);
                $performerCollection = collect($performerArray);
            }
        }
        // Return the collection of all performers
        return json_encode($performerCollection->all());

    }
}
