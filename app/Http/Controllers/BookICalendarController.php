<?php

namespace App\Http\Controllers;

use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Illuminate\Http\Request;

class BookICalendarController extends Controller
{
    public function export(Request $request, string $id)
    {
        $book = $request->user()->books()->findOrFail($id);
        $records = $book->records;

        $calendar = new Calendar;

        foreach ($records as $record) {
            $uid = new UniqueIdentifier("work-log/{$record->id}");
            $event = new Event($uid);
            $dateTime = new DateTime($record->created_at, true);

            $event->setOccurrence(new TimeSpan($dateTime, $dateTime))
                ->setSummary($record->content);
            $calendar->addEvent($event);
        }

        $componentFactory = new CalendarFactory;
        $calendarComponent = $componentFactory->createCalendar($calendar);

        return response()->streamDownload(function () use ($calendarComponent) {
            echo $calendarComponent;
        }, 'journal-export.ics');
    }
}
