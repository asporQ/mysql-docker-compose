<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DiaryEntry;
use App\Models\Emotion;

class DiaryEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diaryEntries = Auth::user()->diaryEntries()->with('emotions')->paginate(3);
        return view('diary.index', compact('diaryEntries'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $emotions = Emotion::all(); // Fetch all emotions for selection
        return view('diary.create', compact('emotions')); // Pass emotions to the view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'date' => 'required|date',
            'content' => 'required|string',
            'emotions' => 'array', // Validate emotions as an array
            'intensity' => 'array', // Validate intensity as an array
        ]);

        // Create the diary entry
        $diaryEntry = Auth::user()->diaryEntries()->create([
            'date' => $validated['date'],
            'content' => $validated['content'],
        ]);

        // Check if emotions and intensity arrays are present
        if (!empty($validated['emotions']) && !empty($validated['intensity'])) {
            foreach ($validated['emotions'] as $emotionId) {
                $intensity = $validated['intensity'][$emotionId] ?? null;
                
                // Attach emotions and intensities to the diary entry
                $diaryEntry->emotions()->attach($emotionId, ['intensity' => $intensity]);
            }
        }

        // Redirect to the diary index with a success message
        return redirect()->route('diary.index')->with('status', 'Diary entry added successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $diaryEntry = Auth::user()->diaryEntries()->findOrFail($id);
        return view('diary.show', compact('diaryEntry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $diaryEntry = Auth::user()->diaryEntries()->with('emotions')->findOrFail($id);
        $emotions = Emotion::all(); // you must have a model called Emotion to fetch all emotions
        return view('diary.edit', compact('diaryEntry', 'emotions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Retrieve the diary entry by its ID
        // Validate the request
        $validated = $request->validate([
            'date' => 'required|date',
            'content' => 'required|string',
            'emotions' => 'array', // Validate emotions as an array
            'intensity' => 'array', // Validate intensity as an array
        ]);

        // Find and update the diary entry
        $diaryEntry = Auth::user()->diaryEntries()->findOrFail($id);
        $diaryEntry->update([
            'date' => $validated['date'],
            'content' => $validated['content'],
        ]);

        // Sync emotions and intensities
        if (!empty($validated['emotions'])) {
            $emotions = [];

            foreach ($validated['emotions'] as $emotionId) {
                // Get the intensity for each emotion or default to null if not provided
                $intensity = $validated['intensity'][$emotionId] ?? null;
                // Build the array for sync with emotion and intensity
                $emotions[$emotionId] = ['intensity' => $intensity];
            }

            // Sync emotions with the corresponding intensities
            $diaryEntry->emotions()->sync($emotions);
        } else {
            // If no emotions are selected, clear all associated emotions
            $diaryEntry->emotions()->sync([]);
        }

        // Redirect back to the diary index with a success message
        return redirect()->route('diary.index')->with('status', 'Diary entry updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Retrieve the diary entry by its ID
        $diaryEntry = DiaryEntry::findOrFail($id);
        // Delete the retrieved diary entry
        $diaryEntry->delete();
        // Redirect back to the diary index with a success message
        return redirect()->route('diary.index')->with('status','Diary entry deleted successfully!');
    }

    /**
     * Display data of diary as json.
     */
    public function display_diary()
    {
        /*
        * SELECT *
        * FROM diary_entries
        * WHERE user_id = $userId;
        * @ select all
        */

        /*
        * SELECT *
        * FROM diary_entries
        * WHERE user_id = $userId
        * LIMIT 1;
        * @ select first
        */
        // $userId = Auth::id(); // Get the authenticated user's ID

        
        // // Fetch all diary entries for the authenticated user
        // $diaryEntries = DB::table('diary_entries')
        //                 ->where('user_id', $userId)
        //                 ->first(); // select first diary limit 1 
        //                 // ->get(); // select all diary
        
        // return response()->json($diaryEntries); // return as json

        /*
        * SELECT date, content
        * FROM diary_entries
        * WHERE user_id = ? AND content LIKE '%day%';
        */
        $results = DB::table('diary_entries')
                    ->where('content', 'like', '%day%')
                    ->get();
        return response()->json($results);

    }

    public function mockup()
    {
        /*
        * LAB 9: laravel query builder
        *
        * SELECT * FROM diary_entries
        * JOIN diary_entry_emotions
        * ON diary_entries.id = diary_entry_emotions.diary_entry_id
        * JOIN emotions 
        * ON diary_entry_emotions.emotion_id = 'emotions.id
        * WHERE diary_entry_emotions.emotion_id = 2 
        * AND diary_entries.content LIKE %happy%
        */
        $results = DB::table('diary_entries')
                    ->join('diary_entry_emotions', 'diary_entries.id', '=', 'diary_entry_emotions.diary_entry_id')
                    ->join('emotions', 'diary_entry_emotions.emotion_id', '=', 'emotions.id')
                    ->where('diary_entry_emotions.emotion_id', 2)
                    ->where('diary_entries.content', 'like', '%happy%')
                    ->get();
        // return response()->json($results);
        return view('mockup', compact('results'));
    }

}
