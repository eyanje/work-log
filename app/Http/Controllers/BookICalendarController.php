<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
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
            $oldRecord = $book->records()
                ->where('uid', $journal->UID)
                ->get()
                ->first();

            // Skip this entry if it is out of date.
            if ($oldRecord != null && $oldRecord->updated_at >= $journal->DTSTAMP) {
                continue;
            }

            // If the old record is null, make and modify a new record.
            $record = $oldRecord ?? $book->records()->make(['uid' => $journal->UID]);

            $record->content = $journal->SUMMARY;
            $record->started_at = $journal->DTSTART;
            if ($record->DTEND != null) {
                $record['ended_at'] = $journal->DTEND;
            }

            $record->save();
        }

        return redirect()->route('book.show', ['id' => $id]);
    }
}
