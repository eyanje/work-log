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
            $calendar->add('VJOURNAL', [
                'UID' => "work-log-{$record->id}",
                'DTSTAMP' => $record->updated_at,
                'SUMMARY' => $record->content,
                'DTSTART' => $record->created_at,
                'X-WORK-LOG-BOOK' => $book->id,
            ]);
        }

        return response()->streamDownload(function () use ($calendar) {
            echo $calendar->serialize();
        }, 'journal-export.ics');
    }
}
