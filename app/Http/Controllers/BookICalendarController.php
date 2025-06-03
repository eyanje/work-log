<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sabre\VObject;
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
                'UID' => $record->uid,
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

    public function import(Request $request, string $id)
    {
        $book = $request->user()->books()->findOrFail($id);
        $records = $book->records;

        $calendar = VObject\Reader::read($request->file('book')->get());

        foreach ($calendar->VJOURNAL as $journal) {
            $record = $book->records()->create([
                'content' => $journal->SUMMARY,
                'started_at' => $journal->DTSTART,
            ]);
            if ($record->DTEND != null) {
                $record['ended_at'] = $journal->DTEND;
            }
            $record->save();
        }

        return redirect()->route('book.edit', ['id' => $id]);
    }
}
