<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sabre\VObject\Component\VCalendar;

class BookICalendarController extends Controller
{
    public function export(Request $request, string $id)
    {
        $book = $request->user()->books()->findOrFail($id);
        $records = $book->records;

        $calendar = new VCalendar;

        foreach ($records as $record) {
            $properties = [
                'UID' => "work-log-{$record->id}",
                'DTSTAMP' => $record->updated_at,
                'SUMMARY' => $record->content,
                'DTSTART' => $record->started_at,
                'X-WORK-LOG-BOOK' => $book->id,
            ];
            if ($record->ended_at != null) {
                $properties['DTEND'] = $record->ended_at;
            }
            $calendar->add('VJOURNAL', $properties);
        }

        return response()->streamDownload(function () use ($calendar) {
            echo $calendar->serialize();
        }, 'journal-export.ics');
    }
}
