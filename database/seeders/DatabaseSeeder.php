<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════════
        //  UTILISATEURS
        // ══════════════════════════════════════════════
        $admin = User::create([
            'name'               => 'Directeur Koffi',
            'email'              => 'admin@school.com',
            'password'           => Hash::make('password'),
            'role'               => 'admin',
            'email_verified_at'  => now(),
        ]);

        $teachers = [
            User::create(['name' => 'Prof. Mensah Kofi',      'email' => 'mensah@school.com',   'password' => Hash::make('password'), 'role' => 'teacher', 'email_verified_at' => now()]),
            User::create(['name' => 'Prof. Amoussou Claire',  'email' => 'amoussou@school.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'email_verified_at' => now()]),
            User::create(['name' => 'Prof. Dossou Jean-Marc', 'email' => 'dossou@school.com',   'password' => Hash::make('password'), 'role' => 'teacher', 'email_verified_at' => now()]),
            User::create(['name' => 'Prof. Agbodjan Rosine',  'email' => 'agbodjan@school.com', 'password' => Hash::make('password'), 'role' => 'teacher', 'email_verified_at' => now()]),
            User::create(['name' => 'Prof. Sossou Patrick',   'email' => 'sossou@school.com',   'password' => Hash::make('password'), 'role' => 'teacher', 'email_verified_at' => now()]),
        ];

        // ══════════════════════════════════════════════
        //  CLASSES
        // ══════════════════════════════════════════════
        $classesData = [
            ['name' => 'Terminale S1',  'level' => 'Terminale', 'capacity' => 42],
            ['name' => 'Terminale S2',  'level' => 'Terminale', 'capacity' => 38],
            ['name' => 'Première S1',   'level' => 'Première',  'capacity' => 45],
            ['name' => 'Seconde A',     'level' => 'Seconde',   'capacity' => 50],
        ];

        $classes = [];
        foreach ($classesData as $cd) {
            $classes[] = SchoolClass::create(array_merge($cd, ['academic_year' => '2024-2025']));
        }

        // ══════════════════════════════════════════════
        //  MATIÈRES par classe
        // ══════════════════════════════════════════════
        $subjectsTemplate = [
            ['name' => 'Mathématiques',      'code' => 'MATH', 'coefficient' => 5, 'teacher_idx' => 0],
            ['name' => 'Physique-Chimie',    'code' => 'PHY',  'coefficient' => 4, 'teacher_idx' => 0],
            ['name' => 'Sciences de la Vie', 'code' => 'SVT',  'coefficient' => 3, 'teacher_idx' => 4],
            ['name' => 'Français',           'code' => 'FR',   'coefficient' => 4, 'teacher_idx' => 1],
            ['name' => 'Anglais',            'code' => 'ANG',  'coefficient' => 3, 'teacher_idx' => 2],
            ['name' => 'Histoire-Géographie','code' => 'HG',   'coefficient' => 3, 'teacher_idx' => 3],
            ['name' => 'Informatique',       'code' => 'INFO', 'coefficient' => 2, 'teacher_idx' => 0],
            ['name' => 'Éducation Civique',  'code' => 'EC',   'coefficient' => 1, 'teacher_idx' => 3],
        ];

        $allSubjects = []; // [class_id => [Subject, ...]]
        foreach ($classes as $class) {
            $classSubjects = [];
            foreach ($subjectsTemplate as $st) {
                $classSubjects[] = Subject::create([
                    'class_id'   => $class->id,
                    'teacher_id' => $teachers[$st['teacher_idx']]->id,
                    'name'       => $st['name'],
                    'code'       => $st['code'],
                    'coefficient'=> $st['coefficient'],
                ]);
            }
            $allSubjects[$class->id] = $classSubjects;
        }

        // ══════════════════════════════════════════════
        //  ÉLÈVES — prénoms & noms réalistes (Bénin / Afrique de l'Ouest)
        // ══════════════════════════════════════════════
        $firstNamesMale = [
            'Kossi','Dodji','Mawuli','Kofi','Yao','Koffi','Amédée','Rodrigue',
            'Gilles','Stanislas','Parfait','Fidèle','Hervé','Romain','Jocelin',
            'Armand','Clément','Thierry','Blaise','Séraphin','Barnabas','Ezéchiel',
            'Prudence','Célestin','Amos',
        ];
        $firstNamesFemale = [
            'Akossiwa','Kafui','Mawuena','Afi','Enyonam','Séverine','Christelle',
            'Mireille','Grâce','Espérance','Joëlle','Roseline','Nathalie','Claudine',
            'Victorine','Yvette','Léontine','Cécile','Adèle','Fidèle','Solange',
            'Aurélie','Andrée','Martine','Bénédicte',
        ];
        $lastNames = [
            'Agbossou','Amoussou','Atchadé','Dossou','Gnacadja','Houngbédji',
            'Kpossinou','Laleye','Medenou','Noukpo','Padonou','Quenum','Sossou',
            'Tchagninou','Togbé','Videgla','Zinsou','Mensah','Koffi','Adjovi',
            'Biaou','Chabi','Dansi','Faïnou','Gbédji',
        ];

        // Profils de performance pour des données réalistes et variées
        // excellent: notes entre 15-20, bon: 12-17, moyen: 9-14, faible: 5-11
        $profiles = [
            ['type' => 'excellent', 'count' => 4, 'min' => 15.0, 'max' => 19.5],
            ['type' => 'bon',       'count' => 8, 'min' => 12.0, 'max' => 17.0],
            ['type' => 'moyen',     'count' => 8, 'min' => 9.0,  'max' => 14.5],
            ['type' => 'faible',    'count' => 5, 'min' => 5.0,  'max' => 11.0],
        ];

        $studentCounter = 1;
        $phones = ['97','96','95','94','93','67','66','65','64'];

        foreach ($classes as $classIdx => $class) {
            $maleNames   = $this->shuffle($firstNamesMale);
            $femaleNames = $this->shuffle($firstNamesFemale);
            $lastNamesS  = $this->shuffle($lastNames);

            $studentList = [];
            $profileList = [];

            foreach ($profiles as $profile) {
                for ($i = 0; $i < $profile['count']; $i++) {
                    $profileList[] = $profile;
                }
            }
            shuffle($profileList);

            foreach ($profileList as $idx => $profile) {
                $gender = ($idx % 2 === 0) ? 'M' : 'F';
                $firstName = $gender === 'M'
                    ? ($maleNames[$idx % count($maleNames)])
                    : ($femaleNames[$idx % count($femaleNames)]);
                $lastName  = $lastNamesS[$idx % count($lastNamesS)];

                $year   = rand(2005, 2009);
                $month  = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
                $day    = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
                $phone1 = $phones[array_rand($phones)] . str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
                $phone2 = $phones[array_rand($phones)] . str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);

                $student = Student::create([
                    'class_id'            => $class->id,
                    'registration_number' => 'BJ-2024-' . str_pad($studentCounter, 5, '0', STR_PAD_LEFT),
                    'first_name'          => $firstName,
                    'last_name'           => $lastName,
                    'gender'              => $gender,
                    'birth_date'          => "$year-$month-$day",
                    'phone'               => '+229 ' . $phone1,
                    'parent_phone'        => '+229 ' . $phone2,
                    'address'             => $this->randomAddress(),
                    'is_active'           => true,
                ]);

                $studentList[] = ['student' => $student, 'profile' => $profile];
                $studentCounter++;
            }

            // ══════════════════════════════════════════════
            //  NOTES — réalistes selon profil
            // ══════════════════════════════════════════════
            $classSubjects = $allSubjects[$class->id];

            foreach ($studentList as $item) {
                $student = $item['student'];
                $profile = $item['profile'];

                foreach ($classSubjects as $subject) {
                    // Variation par matière : certains élèves sont forts en maths mais faibles en français
                    $subjectBonus = $this->subjectVariation($subject->code, $profile['type']);

                    // S1 — toutes les notes saisies
                    $gradeS1 = $this->realisticGrade($profile['min'] + $subjectBonus, $profile['max'] + $subjectBonus);
                    Grade::create([
                        'student_id'    => $student->id,
                        'subject_id'    => $subject->id,
                        'grade'         => $gradeS1,
                        'semester'      => 'S1',
                        'academic_year' => '2024-2025',
                        'recorded_by'   => $subject->teacher_id,
                    ]);

                    // S2 — 75% saisies (simule une année en cours)
                    if (rand(1, 100) <= 75) {
                        $gradeS2 = $this->realisticGrade($profile['min'] + $subjectBonus, $profile['max'] + $subjectBonus);
                        Grade::create([
                            'student_id'    => $student->id,
                            'subject_id'    => $subject->id,
                            'grade'         => $gradeS2,
                            'semester'      => 'S2',
                            'academic_year' => '2024-2025',
                            'recorded_by'   => $subject->teacher_id,
                        ]);
                    }
                }
            }
        }

        $totalStudents  = Student::count();
        $totalGrades    = Grade::count();
        $this->command->info('');
        $this->command->info('✅ Base de données peuplée avec succès !');
        $this->command->info("   👥 $totalStudents élèves répartis en " . count($classes) . " classes");
        $this->command->info("   📝 $totalGrades notes enregistrées");
        $this->command->info('');
        $this->command->info('   Identifiants :');
        $this->command->info('   admin@school.com / password');
        $this->command->info('   mensah@school.com / password');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────

    /**
     * Génère une note réaliste avec variation naturelle (pas juste random).
     */
    private function realisticGrade(float $min, float $max): float
    {
        $min = max(0, min(20, $min));
        $max = max(0, min(20, $max));
        if ($min > $max) [$min, $max] = [$max, $min];

        // Distribution en cloche : moyenne de 3 tirages
        $g = (rand((int)($min*10), (int)($max*10))
            + rand((int)($min*10), (int)($max*10))
            + rand((int)($min*10), (int)($max*10))) / 30;

        return round(min(20, max(0, $g)), 2);
    }

    /**
     * Bonus/malus selon la matière et le profil — rend les profils crédibles.
     */
    private function subjectVariation(string $code, string $type): float
    {
        // Les élèves "bon" en sciences peuvent être "moyen" en lettres etc.
        $variations = [
            'excellent' => ['MATH'=>0.5,  'PHY'=>0.5,  'SVT'=>0,    'FR'=>0,    'ANG'=>0,    'HG'=>0,    'INFO'=>1,   'EC'=>0.5],
            'bon'       => ['MATH'=>0,    'PHY'=>0,    'SVT'=>0.5,  'FR'=>0.5,  'ANG'=>-0.5, 'HG'=>0.5,  'INFO'=>0.5, 'EC'=>0],
            'moyen'     => ['MATH'=>-1,   'PHY'=>-0.5, 'SVT'=>0,    'FR'=>0.5,  'ANG'=>-1,   'HG'=>0,    'INFO'=>0,   'EC'=>1],
            'faible'    => ['MATH'=>-1.5, 'PHY'=>-1,   'SVT'=>-0.5, 'FR'=>0,    'ANG'=>-1.5, 'HG'=>0.5,  'INFO'=>-1,  'EC'=>1],
        ];

        return $variations[$type][$code] ?? 0;
    }

    private function randomAddress(): string
    {
        $quartiers = ['Cadjehoun','Akpakpa','Fidjrossè','Gbègamey','Vèdoko','Aïbatin','Cotonou-Centre','Zogbohouè','Ganhi','Missèbo','Agla','Godomey'];
        $rues      = ['Rue des Palmiers','Avenue de la Marina','Bd Saint-Michel','Rue du Lac','Allée des Cocotiers','Rue de l\'Étoile','Bd de France'];
        return $rues[array_rand($rues)] . ', ' . $quartiers[array_rand($quartiers)] . ', Cotonou';
    }

    private function shuffle(array $arr): array
    {
        shuffle($arr);
        return $arr;
    }
}
