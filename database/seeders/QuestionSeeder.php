<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programmingDivision = Division::where('code', 'programming')->first();

        $programmingQuestions = [
            [
                'question' => 'Apa yang dimaksud dengan Pemrograman?',
                'options' => [
                    ['text' => 'Mengumpulkan data dan menyimpan dalam bentuk data struktur', 'correct' => false],
                    ['text' => 'Melakukan perubahan data secara real-time', 'correct' => false],
                    ['text' => 'Melatih algoritma dan struktur data', 'correct' => true],
                    ['text' => 'Mengembangkan program yang dapat berinteraksi dengan pengguna', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apakah JavaScript adalah bahasa pemrograman yang diturunkan dari Java?',
                'options' => [
                    ['text' => 'Ya', 'correct' => false],
                    ['text' => 'Tidak', 'correct' => true],
                ]
            ],
            [
                'question' => 'Mana berikut yang bukan bahasa Pemrograman?',
                'options' => [
                    ['text' => 'Java', 'correct' => false],
                    ['text' => 'Python', 'correct' => false],
                    ['text' => 'C++', 'correct' => false],
                    ['text' => 'HTML', 'correct' => true],
                ]
            ],
        ];

        $this->createQuestionsWithOptions($programmingDivision->id, $programmingQuestions);

        $multimediaDivision = Division::where('code', 'multimedia')->first();

        $multimediaQuestions = [
            [
                'question' => 'Software yang digunakan untuk editing foto adalah?',
                'options' => [
                    ['text' => 'Adobe Premiere Pro', 'correct' => false],
                    ['text' => 'Adobe Photoshop', 'correct' => true],
                    ['text' => 'Adobe After Effects', 'correct' => false],
                    ['text' => 'Adobe Audition', 'correct' => false],
                ]
            ],
            [
                'question' => 'Format file yang digunakan untuk gambar dengan transparansi adalah?',
                'options' => [
                    ['text' => 'JPG', 'correct' => false],
                    ['text' => 'BMP', 'correct' => false],
                    ['text' => 'PNG', 'correct' => true],
                    ['text' => 'GIF', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa yang dimaksud dengan UI/UX?',
                'options' => [
                    ['text' => 'User Interface/User Experience', 'correct' => true],
                    ['text' => 'User Integration/User Extension', 'correct' => false],
                    ['text' => 'Unified Interface/Unified Experience', 'correct' => false],
                    ['text' => 'Universal Interface/Universal Experience', 'correct' => false],
                ]
            ],
        ];

        $this->createQuestionsWithOptions($multimediaDivision->id, $multimediaQuestions);

        $skjDivision = Division::where('code', 'skj')->first();

        $skjQuestions = [
            [
                'question' => 'Dalam jaringan komputer, apa kepanjangan dari IP?',
                'options' => [
                    ['text' => 'Internet Protocol', 'correct' => true],
                    ['text' => 'Internet Port', 'correct' => false],
                    ['text' => 'Interface Protocol', 'correct' => false],
                    ['text' => 'Internal Process', 'correct' => false],
                ]
            ],
            [
                'question' => 'Perangkat yang berfungsi menghubungkan dua jaringan yang berbeda adalah?',
                'options' => [
                    ['text' => 'Hub', 'correct' => false],
                    ['text' => 'Switch', 'correct' => false],
                    ['text' => 'Router', 'correct' => true],
                    ['text' => 'Repeater', 'correct' => false],
                ]
            ],
            [
                'question' => 'Port yang digunakan untuk protokol HTTP adalah?',
                'options' => [
                    ['text' => '21', 'correct' => false],
                    ['text' => '22', 'correct' => false],
                    ['text' => '80', 'correct' => true],
                    ['text' => '443', 'correct' => false],
                ]
            ],
        ];

        $this->createQuestionsWithOptions($skjDivision->id, $skjQuestions);
    }

    /**
     * Create questions with options for a division
     *
     * @param int $divisionId
     * @param array $questionsData
     * @return void
     */
    private function createQuestionsWithOptions($divisionId, $questionsData)
    {
        foreach ($questionsData as $questionData) {
            $question = Question::create([
                'division_id' => $divisionId,
                'question_text' => $questionData['question'],
            ]);

            foreach ($questionData['options'] as $optionData) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['text'],
                    'is_correct' => $optionData['correct'],
                ]);
            }
        }
    }
}
