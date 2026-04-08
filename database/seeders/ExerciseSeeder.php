<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exercise;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $exercises = [
            // Borstspieren
            ['name' => 'Bench Press', 'muscle_group' => 'chest', 'description' => 'Klassieke borstoefening met barbell op een platte bank.'],
            ['name' => 'Incline Dumbbell Press', 'muscle_group' => 'chest', 'description' => 'Schuine bankdrukken met dumbbells voor de bovenste borstspieren.'],
            ['name' => 'Cable Fly', 'muscle_group' => 'chest', 'description' => 'Kabeloefening voor isolatie van de borstspieren.'],
            ['name' => 'Push-Up', 'muscle_group' => 'chest', 'description' => 'Lichaamsgewicht borstdrukken.'],
            ['name' => 'Dips', 'muscle_group' => 'chest', 'description' => 'Triceps en borst oefening op de dipstangen.'],

            // Rug
            ['name' => 'Deadlift', 'muscle_group' => 'back', 'description' => 'Samengestelde oefening die de gehele rug en hamstrings traint.'],
            ['name' => 'Pull-Up', 'muscle_group' => 'back', 'description' => 'Lichaamsgewicht optrekken voor brede rug.'],
            ['name' => 'Barbell Row', 'muscle_group' => 'back', 'description' => 'Gebogen roeien met barbell voor de middeldeel rug.'],
            ['name' => 'Lat Pulldown', 'muscle_group' => 'back', 'description' => 'Kabeloefening om de lats te isoleren.'],
            ['name' => 'Seated Cable Row', 'muscle_group' => 'back', 'description' => 'Zittend roeien met kabel voor rug dikte.'],

            // Benen
            ['name' => 'Squat', 'muscle_group' => 'legs', 'description' => 'Koningin van alle beensoefeningen, traint quadriceps, billen en hamstrings.'],
            ['name' => 'Romanian Deadlift', 'muscle_group' => 'legs', 'description' => 'Hamstring gerichte deadlift variant.'],
            ['name' => 'Leg Press', 'muscle_group' => 'legs', 'description' => 'Beendrukken op de beenpersmachine.'],
            ['name' => 'Lunges', 'muscle_group' => 'legs', 'description' => 'Uitvalspassen voor quad en bil activatie.'],
            ['name' => 'Leg Curl', 'muscle_group' => 'legs', 'description' => 'Hamstring isolatie op de buikliggende curl machine.'],
            ['name' => 'Calf Raise', 'muscle_group' => 'calves', 'description' => 'Kuitspieren versterken met staande of zittende calf raises.'],

            // Schouders
            ['name' => 'Overhead Press', 'muscle_group' => 'shoulders', 'description' => 'Staand of zittend schouder drukken met barbell.'],
            ['name' => 'Lateral Raise', 'muscle_group' => 'shoulders', 'description' => 'Zijwaartse heffingen voor brede schouders.'],
            ['name' => 'Face Pull', 'muscle_group' => 'shoulders', 'description' => 'Kabeloefening voor achterste schouder en rotator cuff.'],
            ['name' => 'Arnold Press', 'muscle_group' => 'shoulders', 'description' => 'Roterende schouder drukken, bedacht door Arnold Schwarzenegger.'],

            // Biceps
            ['name' => 'Barbell Curl', 'muscle_group' => 'biceps', 'description' => 'Klassieke biceps oefening met barbell.'],
            ['name' => 'Hammer Curl', 'muscle_group' => 'biceps', 'description' => 'Neutrale grip curl voor biceps en onderarmen.'],
            ['name' => 'Incline Dumbbell Curl', 'muscle_group' => 'biceps', 'description' => 'Schuine bank curl voor maximale biceps rek.'],

            // Triceps
            ['name' => 'Tricep Pushdown', 'muscle_group' => 'triceps', 'description' => 'Kabel pushdown voor triceps isolatie.'],
            ['name' => 'Skull Crushers', 'muscle_group' => 'triceps', 'description' => 'Liggend barbell triceps extensie.'],
            ['name' => 'Close-Grip Bench Press', 'muscle_group' => 'triceps', 'description' => 'Smalle greep bankdrukken voor triceps.'],

            // Core / Buik
            ['name' => 'Plank', 'muscle_group' => 'core', 'description' => 'Statische core stabilisatie oefening.'],
            ['name' => 'Cable Crunch', 'muscle_group' => 'core', 'description' => 'Kabel buikspieroefening voor maximale rek en contractie.'],
            ['name' => 'Hanging Leg Raise', 'muscle_group' => 'core', 'description' => 'Hangend de benen optrekken voor onderbuik.'],

            // Cardio
            ['name' => 'Treadmill Run', 'muscle_group' => 'cardio', 'description' => 'Hardlopen op de loopband voor cardiovasculaire conditie.'],
            ['name' => 'Rowing Machine', 'muscle_group' => 'cardio', 'description' => 'Roeimachine voor full-body cardio conditie.'],
            ['name' => 'Jump Rope', 'muscle_group' => 'cardio', 'description' => 'Touwtje springen voor explosieve cardio.'],
        ];

        foreach ($exercises as $exercise) {
            Exercise::firstOrCreate(
                ['name' => $exercise['name']],
                $exercise
            );
        }
    }
}
