<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'Qatar vs Jordan',
                'event_type' => 'football',
                'short_description' => 'World Cup qualifying showdown under the Lusail lights.',
                'description' => "Qatar host Jordan in a decisive qualifying fixture at Lusail Stadium. Gates open three hours before kick-off with Fan Zone activations across the concourse.\n\nFan ID holders get priority lane access at Gate C.",
                'venue' => 'Lusail Stadium, Lusail',
                'start_at' => '2026-11-14 19:00:00',
                'end_at' => '2026-11-14 21:30:00',
                'image' => '/images/events/qatar-vs-jordan.jpg',
                'accent' => '#8A1538',
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'title' => 'NBA Qatar Games: Heat vs Cavaliers',
                'event_type' => 'basketball',
                'short_description' => 'The NBA returns to Doha for a preseason double-header.',
                'description' => "Miami Heat face the Cleveland Cavaliers in the first of two NBA Qatar Games fixtures. Courtside entertainment, mascot appearances and a fan meet-and-greet zone open from midday.",
                'venue' => 'Lusail Multipurpose Hall, Lusail',
                'start_at' => '2026-10-02 18:00:00',
                'end_at' => '2026-10-02 20:00:00',
                'image' => '/images/events/nba-qatar-games.jpg',
                'accent' => '#1B3668',
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'title' => 'RTQ+ Fan Festival — Corniche Nights',
                'event_type' => 'festival',
                'short_description' => 'Live music, food trucks and big-screen match viewing on the Corniche.',
                'description' => "A free, family-friendly fan festival along the Doha Corniche with live performances, street food and a big screen simulcast of the day's fixtures.",
                'venue' => 'Doha Corniche',
                'start_at' => '2026-11-13 16:00:00',
                'end_at' => '2026-11-13 23:00:00',
                'image' => '/images/events/fan-festival.jpg',
                'accent' => '#E75300',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'RTQ+ Esports Showdown',
                'event_type' => 'esports',
                'short_description' => 'Regional qualifiers for the RTQ+ Road to Qatar esports cup.',
                'description' => "Top regional teams compete for a place in the RTQ+ Esports Cup final. Free entry for Fan ID holders, with an on-site RTQ+ points booth.",
                'venue' => 'Qatar National Convention Centre, Doha',
                'start_at' => '2026-09-24 15:00:00',
                'end_at' => '2026-09-24 21:00:00',
                'image' => '/images/events/esports-showdown.jpg',
                'accent' => '#622066',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Amir Cup Equestrian Show',
                'event_type' => 'equestrian',
                'short_description' => 'Show jumping and endurance heats at Al Shaqab.',
                'description' => "The Amir Cup equestrian show returns to Al Shaqab with show jumping heats through the afternoon. Photography access details published closer to the date.",
                'venue' => 'Al Shaqab, Al Rayyan',
                'start_at' => '2026-12-05 09:00:00',
                'end_at' => '2026-12-05 17:00:00',
                'image' => null,
                'accent' => '#00837B',
                'status' => 'published',
                'is_featured' => false,
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(
                ['slug' => Str::slug($event['title'])],
                $event + ['slug' => Str::slug($event['title'])]
            );
        }
    }
}
