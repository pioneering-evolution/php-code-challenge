<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
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

            // For each performer lets get the tasks and put into an array and convert to collection
            foreach($performer['tasks'] as $performerTask)
            {
                $taskTitle = ($performerTask['title']);
                $taskTotal = 0;
                foreach($performerTask['items'] as $item)
                {
                    $itemTitle = $item["title"];
                    foreach($item['fiscal_years'] as $key => $year)
                    {
                        $yearTotal = $year['total_dollars'];
                        $taskTotal = $yearTotal + $taskTotal;
                        $perfLabor[$display_name][$taskTitle][$itemTitle][$key] = $yearTotal;
                        $laborTaskArray[] = array('owner' => $performer_id, 'type' => 'labor', 'amount' => $yearTotal,
                            'performer_total' => $taskTotal, 'item_title' => $itemTitle, 'year' => $key);
                        $allTaskArray[] = array('owner' => $performer_id, 'type' => 'labor', 'amount' => $yearTotal,
                            'performer_total' => $taskTotal, 'item_title' => $itemTitle, 'year' => $key);
                        //Will also need to add logic for non_labor and labor totals
                    }
                }
            }
        }
        //Convert the labor tasks array to a collection
        $laborTaskCollection = collect($laborTaskArray);

        //Lets do the same for the list of non_labor tasks
        // For each performer lets get the non_labor tasks and put into an array and convert to collection
        foreach($nonLaborGroup as $performer)
        {
            $display_name = $performer['performer']['display_name'];
            $performer_id = $performer['performer']['id'];
            $performerArray[] = array('display_name' => $display_name, 'id' => $performer_id);
            foreach($performer['tasks'] as $performerTask)
            {
                $taskTitle = ($performerTask['title']);
                $taskTotal = 0;
                foreach($performerTask['items'] as $item)
                {
                    $itemTitle = $item["title"];
                    foreach($item['fiscal_years'] as $key => $year)
                    {
                        $yearTotal = $year['total_dollars'];
                        $taskTotal = $yearTotal + $taskTotal;
                        $perfLabor[$display_name][$taskTitle][$itemTitle][$key] = $yearTotal;
                        $nonLaborTaskArray[] = array('owner' => $performer_id, 'type' => 'non_labor', 'amount' => $yearTotal,
                            'performer_total' => $taskTotal, 'item_title' => $itemTitle, 'year' => $key);
                        $allTaskArray[] = array('owner' => $performer_id, 'type' => 'non_labor', 'amount' => $yearTotal,
                            'performer_total' => $taskTotal, 'item_title' => $itemTitle, 'year' => $key);
                    }
                }
            }
        }
        //Add to the array and collection list
        $laborTaskCollection = collect($nonLaborTaskArray);
        $nonLaborTaskCollection = collect($nonLaborTaskArray);
        $allTaskCollection = collect($allTaskArray);
        return json_encode($allTaskCollection);
    }
}
