<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function PHPUnit\Framework\isFalse;

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
                'question' => 'Apa yang dimaksud dengan algoritma pemrograman?',
                'options' => [
                    ['text' => 'Kumpulan data yang disimpan dalam memori komputer', 'correct' => false],
                    ['text' => 'Langkah-langkah logis dan sistematis untuk menyelesaikan suatu masalah', 'correct' => true],
                    ['text' => 'Bahasa pemrograman yang digunakan untuk membuat program komputer', 'correct' => false],
                    ['text' => 'Perangkat keras yang digunakan untuk menjalankan program', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa kepanjangan dari IDE yang sering digunakan dalam pemrograman?',
                'options' => [
                    ['text' => 'Internet Development Extension', 'correct' => false],
                    ['text' => 'Integrated Development Environment', 'correct' => true],
                    ['text' => 'Internal Data Evaluation', 'correct' => false],
                    ['text' => 'Interactivce Design Element', 'correct' => false],
                ]
            ],
            [
                'question' => 'Manakah dari pilihan berikut yang termasuk contoh IDE?',
                'options' => [
                    ['text' => 'Canva', 'correct' => false],
                    ['text' => 'Visual Studio Code', 'correct' => true],
                    ['text' => 'Internal Data Evaluation', 'correct' => false],
                    ['text' => 'Interactive Design Element', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa yang dimaksud dengan syntax error dalam pemrograman?',
                'options' => [
                    ['text' => 'Kesalahan dalam logika program', 'correct' => false],
                    ['text' => 'Kesalahan saat menjalankan program karena koneksi internet', 'correct' => false],
                    ['text' => 'Kesalahan penulisan aturan kode dalam bahasa pemrograman', 'correct' => true],
                    ['text' => 'Kesalahan dalam mengatur desain UI aplikasi', 'correct' => false],
                ]
            ],
            [
                'question' => 'Ketika mengalami error dalam kode dan ingin mencari solusi di internet, platform yang paling sering digunakan oleh programmer adalah:',
                'options' => [
                    ['text' => 'Behance', 'correct' => false],
                    ['text' => 'Stack Overflow', 'correct' => true],
                    ['text' => 'Pinterest', 'correct' => false],
                    ['text' => 'Canva', 'correct' => false],
                ]
            ],
            [
                'question' => 'Repository terbuka untuk menyimpan, mengelola, dan berbagi kode program secara kolaboratif disebut:',
                'options' => [
                    ['text' => 'Microsoft Word', 'correct' => false],
                    ['text' => 'Google Docs', 'correct' => false],
                    ['text' => 'GitHub', 'correct' => true],
                    ['text' => 'Excel', 'correct' => false],
                ]
            ],
            [
                'question' => 'Istilah “framework” dalam pemrograman merujuk pada:',
                'options' => [
                    ['text' => 'Template desain UI', 'correct' => false],
                    ['text' => 'Struktur dasar dan alat bantu untuk membangun aplikasi', 'correct' => true],
                    ['text' => 'Software editing video', 'correct' => false],
                    ['text' => 'Kumpulan foto dalam galeri', 'correct' => false],
                ]
            ],
            [
                'question' => 'Salah satu bahasa pemrograman yang sering digunakan untuk pengembangan website adalah:',
                'options' => [
                    ['text' => 'Python', 'correct' => false],
                    ['text' => 'Java', 'correct' => false],
                    ['text' => 'JavaScript', 'correct' => true],
                    ['text' => 'C++', 'correct' => false],
                ]
            ],
            [
                'question' => 'Salah satu website yang menyediakan dokumentasi resmi HTML, CSS, dan JavaScript adalah:',
                'options' => [
                    ['text' => 'W3Schools', 'correct' => true],
                    ['text' => 'Canva', 'correct' => false],
                    ['text' => 'Udemy', 'correct' => false],
                    ['text' => 'Pinterest', 'correct' => false],
                ]
            ],
            [
                'question' => 'Ketika membuat aplikasi mobile menggunakan Android Studio, bahasa yang paling umum digunakan adalah:',
                'options' => [
                    ['text' => 'Java atau Kotlin', 'correct' => true],
                    ['text' => 'Python', 'correct' => false],
                    ['text' => 'PHP', 'correct' => false],
                    ['text' => 'HTML', 'correct' => false],
                ]
            ],
            [
                'question' => 'Bahasa pemrograman yang banyak digunakan dalam pengembangan Machine Learning adalah:',
                'options' => [
                    ['text' => 'C', 'correct' => false],
                    ['text' => 'Python', 'correct' => true],
                    ['text' => 'PHP', 'correct' => false],
                    ['text' => 'Kotlin', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa yang dimaksud dengan debugging dalam dunia programming?',
                'options' => [
                    ['text' => 'Proses menambahkan fitur baru', 'correct' => false],
                    ['text' => 'Proses mempercantik tampilan aplikasi', 'correct' => false],
                    ['text' => 'Proses mencari dan memperbaiki kesalahan dalam kode', 'correct' => true],
                    ['text' => 'Proses mengatur database relasional', 'correct' => false],
                ]
            ],
            [
                'question' => 'Website GeeksforGeeks biasa digunakan oleh programmer untuk:',
                'options' => [
                    ['text' => 'Membuat desain presentasi', 'correct' => false],
                    ['text' => 'Mengedit foto', 'correct' => false],
                    ['text' => 'Belajar konsep dan dasar pemrograman dan struktur data', 'correct' => true],
                    ['text' => 'Menyimpan file multimedia', 'correct' => false],
                ]
            ],
            [
                'question' => 'Dalam pengembangan software, database digunakan untuk:',
                'options' => [
                    ['text' => 'Mengedit tampilan website', 'correct' => true],
                    ['text' => 'Menyimpan dan mengelola data', 'correct' => false],
                    ['text' => 'Membuat animasi', 'correct' => false],
                    ['text' => 'Menyimpan file musik', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa yang dimaksud dengan API (Application Programming Interface)?s',
                'options' => [
                    ['text' => 'Aplikasi chatting online', 'correct' => false],
                    ['text' => 'Alat untuk menyimpan gambar', 'correct' => false],
                    ['text' => 'Antarmuka yang memungkinkan aplikasi saling berkomunikasi', 'correct' => true],
                    ['text' => 'Desain tamplan user interface', 'correct' => false],
                ]
            ],
            [
                'question' => 'Manakah dari berikut ini yang termasuk soft skill penting dalam berorganisasi?',
                'options' => [
                    ['text' => 'Kemampuan menghafal struktur organisasi', 'correct' => false],
                    ['text' => 'Kemampuan bekerja sama dan komunikasi yang baik dengan tim', 'correct' => true],
                    ['text' => 'Kemampuan mengatur perangkat keras untuk presentasi', 'correct' => false],
                    ['text' => 'Kemampuan menggunakan aplikasi edit video', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa yang menunjukkan bahwa seseorang memiliki kemampuan komunikasi yang baik dalam organisasi?',
                'options' => [
                    ['text' => 'Hanya berbicara jika diminta oleh atasan', 'correct' => false],
                    ['text' => 'Mendominasi diskusi dan tidak memberi ruang orang lain berbicara', 'correct' => false],
                    ['text' => 'Mampu menyampaikan ide dengan jelas serta mendengarkan pendapat orang lain', 'correct' => true],
                    ['text' => 'Menghindari diskusi kelompok dan memilih bekerja sendiri', 'correct' => false],
                ]
            ],
            [
                'question' => 'Salah satu tantangan terbesar dalam mengelola tim dalam organisasi adalah…',
                'options' => [
                    ['text' => 'Kurangnya komunikasi yang efektif', 'correct' => true],
                    ['text' => 'Terlalu banyak anggaran untuk kegiatan', 'correct' => false],
                    ['text' => 'Tidak adanya peraturan dalam organisasi', 'correct' => false],
                    ['text' => 'Tidak ada perbedaan pendapat dalam tim', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa yang sebaiknya dilakukan saat terjadi konflik antar anggota dalam suatu divisi?',
                'options' => [
                    ['text' => 'Mengabaikan konflik agar tidak membuang waktu', 'correct' => false],
                    ['text' => 'Menegur anggota yang dirasa salah tanpa mencari tahu akar permasalahan', 'correct' => false],
                    ['text' => 'Mendengarkan semua pihak dan mencari solusi bersama', 'correct' => true],
                    ['text' => 'Meminta ketua langsung mengambil keputusan tanpa mendiskusikan dengan tim', 'correct' => false],
                ]
            ],
            [
                'question' => 'Mengapa penting untuk memiliki kemampuan manajemen waktu dalam organisasi?',
                'options' => [
                    ['text' => 'Agar bisa menunda pekerjaan dan tetap terlihat sibuk', 'correct' => false],
                    ['text' => 'Supaya dapat menyelesaikan tanggung jawab secara efektif dan tidak terburu-buru', 'correct' => true],
                    ['text' => 'Agar bisa bekerja lebih lama daripada anggota lain', 'correct' => false],
                    ['text' => 'Untuk menghindari tugas-tugas yang sulit', 'correct' => false],
                ]
            ],
            [
                'question' => 'Salah satu ciri anggota organisasi yang memiliki inisiatif tinggi adalah...',
                'options' => [
                    ['text' => 'Menunggu instruksi tanpa melakukan apa pun', 'correct' => false],
                    ['text' => 'Melakukan pekerjaan hanya jika diminta', 'correct' => false],
                    ['text' => 'Bertindak cepat, menawarkan bantuan, dan mencari solusi saat ada masalah', 'correct' => true],
                    ['text' => 'Menyerahkan semua pekerjaan kepada ketua', 'correct' => false],
                ]
            ],
            [
                'question' => 'Dalam sebuah organisasi, pemimpin yang baik seharusnya...',
                'options' => [
                    ['text' => 'Hanya fokus pada hasil tanpa peduli proses', 'correct' => false],
                    ['text' => 'Bersikap otoriter agar semua berjalan cepat', 'correct' => false],
                    ['text' => 'Mampu mendengarkan, memberi motivasi, dan membangun semangat tim', 'correct' => true],
                    ['text' => 'Menyelesaikan semua tugas sendirian tanpa melibatkan anggota', 'correct' => false],
                ]
            ],
            [
                'question' => 'Salah satu peran penting seorang pemimpin dalam tim adalah...',
                'options' => [
                    ['text' => 'Memberi hukuman jika anggota tidak aktif', 'correct' => false],
                    ['text' => 'Menjadi pusat perhatian dalam setiap kegiatan', 'correct' => false],
                    ['text' => 'Mendorong kolaborasi, mengarahkan tujuan tim, dan menjaga keharmonisan kelompok', 'correct' => true],
                    ['text' => 'Mengatur semua hal sendiri agar tidak merepotkan anggota lain', 'correct' => false],
                ]
            ],
            [
                'question' => 'Setelah beberapa bulan bergabung dengan organisasi, Anda merasa kurang berkembang. Apa yang sebaiknya dilakukan?',
                'options' => [
                    ['text' => 'Berdiskusi dengan senior atau mentor untuk mencari cara meningkatkan keterampilan', 'correct' => true],
                    ['text' => 'Keluar dari organisasi tanpa mencari solusi', 'correct' => false],
                    ['text' => 'Mengeluh kepada anggota lain tanpa berusaha memperbaiki diri', 'correct' => false],
                    ['text' => 'Menunggu perubahan terjadi tanpa melakukan apa-apa', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa sebaiknya yang dilakukan jika ingin bergabung ke dalam organisasi, namun tidak memiliki banyak pengalaman?',
                'options' => [
                    ['text' => 'Mencari pengalaman terlebih dahulu dan menunda untuk memasuki organisasi tersebut', 'correct' => false],
                    ['text' => 'Menyerah dan tidak mencoba karena merasa tidak cukup baik', 'correct' => false],
                    ['text' => 'Menghindari organisasi dan berfokus pada kegiatan individu', 'correct' => false],
                    ['text' => 'Mengakui bahwa anda pemula dan menunjukkan kemauan untuk belajar', 'correct' => true],
                ]
            ],
            [
                'question' => 'Apa alasan yang paling tepat untuk bergabung dalam sebuah organisasi?',
                'options' => [
                    ['text' => 'Karena semua teman ikut, jadi ikut saja tanpa alasan yang jelas', 'correct' => false],
                    ['text' => 'Untuk mengembangkan diri, membangun relasi, dan mendapatkan pengalaman', 'correct' => true],
                    ['text' => 'Agar terlihat lebih keren di depan orang lain', 'correct' => false],
                    ['text' => 'Supaya bisa menghindari tugas akademik atau pekerjaan lain', 'correct' => false],
                ]
            ],
            [
                'question' => 'Saat menghadiri wawancara untuk masuk ke sebuah organisasi, apa yang sebaiknya di lakukan?',
                'options' => [
                    ['text' => 'Datang dengan pakaian rapi dan menjawab pertanyaan dengan jujur', 'correct' => true],
                    ['text' => 'Tidak perlu mempersiapkan diri karena wawancara tidak penting', 'correct' => false],
                    ['text' => 'Datang terlambat karena berpikir semua orang akan menunggu Anda', 'correct' => false],
                    ['text' => 'Menjawab pertanyaan dengan asal-asalan karena ingin cepat selesai', 'correct' => false],
                ]
            ],
            [
                'question' => 'Jika diterima dalam sebuah organisasi, sikap apa yang harus ditunjukkan sebagai anggota baru?',
                'options' => [
                    ['text' => 'Bersikap pasif dan menunggu perintah tanpa inisiatif', 'correct' => false],
                    ['text' => 'Menunjukkan sikap antusias, aktif, dan mau belajar dari anggota lama', 'correct' => true],
                    ['text' => 'Memaksakan pendapat sendiri dan tidak mau mendengarkan orang lain', 'correct' => false],
                    ['text' => 'Menganggap enteng tugas dan hanya hadir jika ada acara seru', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa yang harus dilakukan, jika sudah diterima di sebuah organisasi, namun sulit beradaptasi?',
                'options' => [
                    ['text' => 'Menutup diri dan menghindari interaksi dengan anggota lain', 'correct' => false],
                    ['text' => 'Keluar dari organisasi tanpa mencoba beradaptasi', 'correct' => false],
                    ['text' => 'Mencoba mengenal lebih banyak orang dan memahami budaya organisasi', 'correct' => true],
                    ['text' => 'Menunggu orang lain yang harus mendekati Anda lebih dulu', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa langkah terbaik yang bisa dilakukan, jika setelah bergabung dalam organisasi dan mendapatkan tugas pertama yang cukup sulit?',
                'options' => [
                    ['text' => 'Mengabaikan tugas tersebut karena merasa belum siap', 'correct' => false],
                    ['text' => 'Menyerah dan meminta orang lain untuk mengerjakan tugas Anda', 'correct' => false],
                    ['text' => 'Meminta bantuan atau arahan dari senior dan belajar dari pengalaman', 'correct' => true],
                    ['text' => 'Mengeluh kepada anggota lain tanpa mencari solusi', 'correct' => false],
                ]
            ],

        ];

        $this->createQuestionsWithOptions($programmingDivision->id, $programmingQuestions);

        $multimediaDivision = Division::where('code', 'multimedia')->first();

        $multimediaQuestions = [
            [
                'question' => 'Apa hal pertama yang sebaiknya dilakukan sebelum mulai membuat desain?',
                'options' => [
                    ['text' => 'Tambah efek', 'correct' => false],
                    ['text' => 'Mencari referensi dan menentukan tujuan desain', 'correct' => true],
                    ['text' => 'Langsung buka software', 'correct' => false],
                    ['text' => 'Render terlebih dahulu', 'correct' => false],
                ]
            ],
            [
                'question' => 'Prinsip desain yang membuat komposisi visual terlihat seimbang dan tidak berat sebelah disebut:',
                'options' => [
                    ['text' => 'Simplicity', 'correct' => false],
                    ['text' => 'Balance', 'correct' => true],
                    ['text' => 'Animation', 'correct' => false],
                    ['text' => 'Typography', 'correct' => false],
                ]
            ],
            [
                'question' => 'Prinsip desain yang membantu user memfokuskan perhatian pada elemen penting disebut:',
                'options' => [
                    ['text' => 'Balance', 'correct' => false],
                    ['text' => 'Emphasis', 'correct' => true],
                    ['text' => 'Harmony', 'correct' => false],
                    ['text' => 'Layout', 'correct' => false],
                ]
            ],
            [
                'question' => 'Dalam dunia desain, kontras digunakan untuk:',
                'options' => [
                    ['text' => 'Menyeimbangkan warna', 'correct' => false],
                    ['text' => 'Menunjukkan perbedaan visual antar elemen', 'correct' => true],
                    ['text' => 'Menyimpan hasil desain', 'correct' => false],
                    ['text' => 'Menambah transisi', 'correct' => false],
                ]
            ],
            [
                'question' => 'Dalam menentukan warna desain, penting untuk memperhatikan color harmony, karena:',
                'options' => [
                    ['text' => 'Bisa membuat file jadi lebih besar', 'correct' => false],
                    ['text' => 'Membuat desain terlihat konsisten dan enak dipandang', 'correct' => true],
                    ['text' => 'Mengindari error saat rendering', 'correct' => false],
                    ['text' => 'Menambah transisi otomatis', 'correct' => false],
                ]
            ],
            [
                'question' => 'Jika ingin mencari inspirasi kombinasi warna untuk desain, bisa menggunakan:',
                'options' => [
                    ['text' => 'Coolors', 'correct' => true],
                    ['text' => 'DaVinci Resolve', 'correct' => false],
                    ['text' => 'ZBrush', 'correct' => false],
                    ['text' => 'Pinterest', 'correct' => false],
                ]
            ],
            [
                'question' => 'Untuk membuat desain grafis sederhana dengan drag and drop secara cepat, tools yang cocok digunakan adalah',
                'options' => [
                    ['text' => 'Canva', 'correct' => true],
                    ['text' => 'Blender', 'correct' => false],
                    ['text' => 'ZBrush', 'correct' => false],
                    ['text' => 'Figma', 'correct' => false],
                ]
            ],
            [
                'question' => 'Website yang bisa digunakan untuk mencari aset desain gratis seperti gambar, ilustrasi, dan ikon adalah:',
                'options' => [
                    ['text' => 'Freepik', 'correct' => true],
                    ['text' => 'Sketch', 'correct' => false],
                    ['text' => 'Final Cut Pro', 'correct' => false],
                    ['text' => 'Canva', 'correct' => false],
                ]
            ],
            [
                'question' => 'Dalam 3D design, istilah mesh merujuk pada:',
                'options' => [
                    ['text' => 'Background video', 'correct' => false],
                    ['text' => 'Susunan titik dan garis yang membentuk objek 3D', 'correct' => true],
                    ['text' => 'Warna dasar objek', 'correct' => false],
                    ['text' => 'Audio dalam video', 'correct' => false],
                ]
            ],
            [
                'question' => 'Jika ingin membuat desain vektor seperti logo dan ikon, tools yang paling tepat digunakan adalah:',
                'options' => [
                    ['text' => 'Adobe Illustrator', 'correct' => true],
                    ['text' => 'Adobe XD', 'correct' => false],
                    ['text' => 'CapCut', 'correct' => false]

                ]
            ],
            [
                'question' => 'Software yang umum digunakan untuk mendesain antarmuka (UI) aplikasi adalah:',
                'options' => [
                    ['text' => 'Figma', 'correct' => true],
                    ['text' => 'Blender', 'correct' => false],
                    ['text' => 'After Effects', 'correct' => false],
                    ['text' => 'AutoCAD', 'correct' => false],
                ]
            ],
            [
                'question' => 'Elemen penting dalam desain antarmuka aplikasi adalah:',
                'options' => [
                    ['text' => 'Musik latar', 'correct' => false],
                    ['text' => 'Navigasi dan konsistensi layout', 'correct' => true],
                    ['text' => 'Animasi 3D', 'correct' => false],
                    ['text' => 'Ukuran file', 'correct' => false],
                ]
            ],
            [
                'question' => 'Software yang sering digunakan untuk proses editing video profesional adalah:',
                'options' => [
                    ['text' => 'Microsoft Excel', 'correct' => false],
                    ['text' => 'Adobe Premiere Pro', 'correct' => true],
                    ['text' => 'Figma', 'correct' => false],
                    ['text' => 'Inkscape', 'correct' => false],
                ]
            ],
            [
                'question' => 'Dalam video editing, istilah cutting artinya:',
                'options' => [
                    ['text' => 'Mengatur resolusi video', 'correct' => false],
                    ['text' => 'Memotong bagian klip video', 'correct' => true],
                    ['text' => 'Menambahkan efek slow motion', 'correct' => false],
                    ['text' => 'Mengatur warna', 'correct' => false],
                ]
            ],
            [
                'question' => 'Jika kamu ingin membuat motion graphic untuk video, software yang paling tepat digunakan adalah:',
                'options' => [
                    ['text' => 'Adobe After Effects', 'correct' => true],
                    ['text' => 'Figma', 'correct' => false],
                    ['text' => 'InDesign', 'correct' => false],
                    ['text' => 'SketchUp', 'correct' => false],
                ]
            ],
            [
                'question' => 'Manakah dari berikut ini yang termasuk soft skill penting dalam berorganisasi?',
                'options' => [
                    ['text' => 'Kemampuan menghafal struktur organisasi', 'correct' => false],
                    ['text' => 'Kemampuan bekerja sama dan komunikasi yang baik dengan tim', 'correct' => true],
                    ['text' => 'Kemampuan mengatur perangkat keras untuk presentasi', 'correct' => false],
                    ['text' => 'Kemampuan menggunakan aplikasi edit video', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa yang menunjukkan bahwa seseorang memiliki kemampuan komunikasi yang baik dalam organisasi?',
                'options' => [
                    ['text' => 'Hanya berbicara jika diminta oleh atasan', 'correct' => false],
                    ['text' => 'Mendominasi diskusi dan tidak memberi ruang orang lain berbicara', 'correct' => false],
                    ['text' => 'Mampu menyampaikan ide dengan jelas serta mendengarkan pendapat orang lain', 'correct' => true],
                    ['text' => 'Menghindari diskusi kelompok dan memilih bekerja sendiri', 'correct' => false],
                ]
            ],
            [
                'question' => 'Salah satu tantangan terbesar dalam mengelola tim dalam organisasi adalah…',
                'options' => [
                    ['text' => 'Kurangnya komunikasi yang efektif', 'correct' => true],
                    ['text' => 'Terlalu banyak anggaran untuk kegiatan', 'correct' => false],
                    ['text' => 'Tidak adanya peraturan dalam organisasi', 'correct' => false],
                    ['text' => 'Tidak ada perbedaan pendapat dalam tim', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa yang sebaiknya dilakukan saat terjadi konflik antar anggota dalam suatu divisi?',
                'options' => [
                    ['text' => 'Mengabaikan konflik agar tidak membuang waktu', 'correct' => false],
                    ['text' => 'Menegur anggota yang dirasa salah tanpa mencari tahu akar permasalahan', 'correct' => false],
                    ['text' => 'Mendengarkan semua pihak dan mencari solusi bersama', 'correct' => true],
                    ['text' => 'Meminta ketua langsung mengambil keputusan tanpa mendiskusikan dengan tim', 'correct' => false],
                ]
            ],
            [
                'question' => 'Mengapa penting untuk memiliki kemampuan manajemen waktu dalam organisasi?',
                'options' => [
                    ['text' => 'Agar bisa menunda pekerjaan dan tetap terlihat sibuk', 'correct' => false],
                    ['text' => 'Supaya dapat menyelesaikan tanggung jawab secara efektif dan tidak terburu-buru', 'correct' => true],
                    ['text' => 'Agar bisa bekerja lebih lama daripada anggota lain', 'correct' => false],
                    ['text' => 'Untuk menghindari tugas-tugas yang sulit', 'correct' => false],
                ]
            ],
            [
                'question' => 'Salah satu ciri anggota organisasi yang memiliki inisiatif tinggi adalah...',
                'options' => [
                    ['text' => 'Menunggu instruksi tanpa melakukan apa pun', 'correct' => false],
                    ['text' => 'Melakukan pekerjaan hanya jika diminta', 'correct' => false],
                    ['text' => 'Bertindak cepat, menawarkan bantuan, dan mencari solusi saat ada masalah', 'correct' => true],
                    ['text' => 'Menyerahkan semua pekerjaan kepada ketua', 'correct' => false],
                ]
            ],
            [
                'question' => 'Dalam sebuah organisasi, pemimpin yang baik seharusnya...',
                'options' => [
                    ['text' => 'Hanya fokus pada hasil tanpa peduli proses', 'correct' => false],
                    ['text' => 'Bersikap otoriter agar semua berjalan cepat', 'correct' => false],
                    ['text' => 'Mampu mendengarkan, memberi motivasi, dan membangun semangat tim', 'correct' => true],
                    ['text' => 'Menyelesaikan semua tugas sendirian tanpa melibatkan anggota', 'correct' => false],
                ]
            ],
            [
                'question' => 'Salah satu peran penting seorang pemimpin dalam tim adalah...',
                'options' => [
                    ['text' => 'Memberi hukuman jika anggota tidak aktif', 'correct' => false],
                    ['text' => 'Menjadi pusat perhatian dalam setiap kegiatan', 'correct' => false],
                    ['text' => 'Mendorong kolaborasi, mengarahkan tujuan tim, dan menjaga keharmonisan kelompok', 'correct' => true],
                    ['text' => 'Mengatur semua hal sendiri agar tidak merepotkan anggota lain', 'correct' => false],
                ]
            ],
            [
                'question' => 'Setelah beberapa bulan bergabung dengan organisasi, Anda merasa kurang berkembang. Apa yang sebaiknya dilakukan?',
                'options' => [
                    ['text' => 'Berdiskusi dengan senior atau mentor untuk mencari cara meningkatkan keterampilan', 'correct' => true],
                    ['text' => 'Keluar dari organisasi tanpa mencari solusi', 'correct' => false],
                    ['text' => 'Mengeluh kepada anggota lain tanpa berusaha memperbaiki diri', 'correct' => false],
                    ['text' => 'Menunggu perubahan terjadi tanpa melakukan apa-apa', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa sebaiknya yang dilakukan jika ingin bergabung ke dalam organisasi, namun tidak memiliki banyak pengalaman?',
                'options' => [
                    ['text' => 'Mencari pengalaman terlebih dahulu dan menunda untuk memasuki organisasi tersebut', 'correct' => false],
                    ['text' => 'Menyerah dan tidak mencoba karena merasa tidak cukup baik', 'correct' => false],
                    ['text' => 'Menghindari organisasi dan berfokus pada kegiatan individu', 'correct' => false],
                    ['text' => 'Mengakui bahwa anda pemula dan menunjukkan kemauan untuk belajar', 'correct' => true],
                ]
            ],
            [
                'question' => 'Apa alasan yang paling tepat untuk bergabung dalam sebuah organisasi?',
                'options' => [
                    ['text' => 'Karena semua teman ikut, jadi ikut saja tanpa alasan yang jelas', 'correct' => false],
                    ['text' => 'Untuk mengembangkan diri, membangun relasi, dan mendapatkan pengalaman', 'correct' => true],
                    ['text' => 'Agar terlihat lebih keren di depan orang lain', 'correct' => false],
                    ['text' => 'Supaya bisa menghindari tugas akademik atau pekerjaan lain', 'correct' => false],
                ]
            ],
            [
                'question' => 'Saat menghadiri wawancara untuk masuk ke sebuah organisasi, apa yang sebaiknya di lakukan?',
                'options' => [
                    ['text' => 'Datang dengan pakaian rapi dan menjawab pertanyaan dengan jujur', 'correct' => true],
                    ['text' => 'Tidak perlu mempersiapkan diri karena wawancara tidak penting', 'correct' => false],
                    ['text' => 'Datang terlambat karena berpikir semua orang akan menunggu Anda', 'correct' => false],
                    ['text' => 'Menjawab pertanyaan dengan asal-asalan karena ingin cepat selesai', 'correct' => false],
                ]
            ],
            [
                'question' => 'Jika diterima dalam sebuah organisasi, sikap apa yang harus ditunjukkan sebagai anggota baru?',
                'options' => [
                    ['text' => 'Bersikap pasif dan menunggu perintah tanpa inisiatif', 'correct' => false],
                    ['text' => 'Menunjukkan sikap antusias, aktif, dan mau belajar dari anggota lama', 'correct' => true],
                    ['text' => 'Memaksakan pendapat sendiri dan tidak mau mendengarkan orang lain', 'correct' => false],
                    ['text' => 'Menganggap enteng tugas dan hanya hadir jika ada acara seru', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa yang harus dilakukan, jika sudah diterima di sebuah organisasi, namun sulit beradaptasi?',
                'options' => [
                    ['text' => 'Menutup diri dan menghindari interaksi dengan anggota lain', 'correct' => false],
                    ['text' => 'Keluar dari organisasi tanpa mencoba beradaptasi', 'correct' => false],
                    ['text' => 'Mencoba mengenal lebih banyak orang dan memahami budaya organisasi', 'correct' => true],
                    ['text' => 'Menunggu orang lain yang harus mendekati Anda lebih dulu', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa langkah terbaik yang bisa dilakukan, jika setelah bergabung dalam organisasi dan mendapatkan tugas pertama yang cukup sulit?',
                'options' => [
                    ['text' => 'Mengabaikan tugas tersebut karena merasa belum siap', 'correct' => false],
                    ['text' => 'Menyerah dan meminta orang lain untuk mengerjakan tugas Anda', 'correct' => false],
                    ['text' => 'Meminta bantuan atau arahan dari senior dan belajar dari pengalaman', 'correct' => true],
                    ['text' => 'Mengeluh kepada anggota lain tanpa mencari solusi', 'correct' => false],
                ]
            ],
        ];

        $this->createQuestionsWithOptions($multimediaDivision->id, $multimediaQuestions);

        $skjDivision = Division::where('code', 'skj')->first();
        $skjQuestions = [
            [
                'question' => 'Apa yang biasanya dilakukan ketika koneksi Wi-Fi tiba-tiba lambat?',
                'options' => [
                    ['text' => 'Menyalakan komputer ulang', 'correct' => false],
                    ['text' => 'Mematikan firewall', 'correct' => false],
                    ['text' => 'Mengecek jaringan dan router', 'correct' => true],
                    ['text' => 'Mengganti background laptop', 'correct' => false],
                ]
            ],
            [
                'question' => 'Perangkat apa yang biasa digunakan untuk mengakses internet di rumah?',
                'options' => [
                    ['text' => 'Switch', 'correct' => false],
                    ['text' => 'Printer', 'correct' => false],
                    ['text' => 'Modem', 'correct' => true],
                    ['text' => 'Server', 'correct' => false],
                ]
            ],
            [
                'question' => 'Kabel jaringan yang sering digunakan untuk koneksi LAN adalah:',
                'options' => [
                    ['text' => 'Kabel HDMI', 'correct' => false],
                    ['text' => 'Kabel USB', 'correct' => false],
                    ['text' => 'Kabel VGA', 'correct' => false],
                    ['text' => 'Kabel UTP', 'correct' => true],
                ]
            ],
            [
                'question' => 'Apa yang bisa terjadi jika jaringan tidak memiliki keamanan yang baik?',
                'options' => [
                    ['text' => 'Internet lebih cepat', 'correct' => false],
                    ['text' => 'Data bisa dicuri orang', 'correct' => true],
                    ['text' => 'Semua perangkat akan mati', 'correct' => false],
                    ['text' => 'Printer jadi error', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa tujuan utama dari membuat password yang kuat pada jaringan Wi-Fi?',
                'options' => [
                    ['text' => 'Biar keren', 'correct' => false],
                    ['text' => 'Biar bisa digunakan banyak orang', 'correct' => false],
                    ['text' => 'Untuk melindungi jaringan dari pengguna tidak sah', 'correct' => true],
                    ['text' => 'Agar internet lebih murah', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa kepanjangan dari LAN dalam jaringan komputer?',
                'options' => [
                    ['text' => 'Local Access Network', 'correct' => false],
                    ['text' => 'Local Area Network', 'correct' => true],
                    ['text' => 'Large Area Network', 'correct' => false],
                    ['text' => 'Long Area Network', 'correct' => false],
                ]
            ],
            [
                'question' => 'Perangkat jaringan yang berfungsi menghubungkan beberapa perangkat dalam satu jaringan lokal disebut:',
                'options' => [
                    ['text' => 'Router', 'correct' => false],
                    ['text' => 'Switch', 'correct' => true],
                    ['text' => 'Modem', 'correct' => false],
                    ['text' => 'Bandwith', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa fungsi utama dari sebuah router dalam jaringan komputer?',
                'options' => [
                    ['text' => 'Mengatur kecepatan internet', 'correct' => false],
                    ['text' => 'Menghubungkan komputer ke printer', 'correct' => false],
                    ['text' => 'Menghubungkan beberapa jaringan berbeda', 'correct' => true],
                    ['text' => 'Mendeteksi virus jaringan', 'correct' => false],
                ]
            ],
            [
                'question' => 'IP Address digunakan untuk:',
                'options' => [
                    ['text' => 'Menyimpan file di server', 'correct' => false],
                    ['text' => 'Mengidentifikasi perangkat dalam jaringan', 'correct' => true],
                    ['text' => 'Mendeteksi virus dalam komputer', 'correct' => false],
                    ['text' => 'Mengakses jaringan Wi-Fi', 'correct' => false],
                ]
            ],
            [
                'question' => 'Protokol yang digunakan untuk mengirim email adalah:',
                'options' => [
                    ['text' => 'HTTP', 'correct' => false],
                    ['text' => 'FTP', 'correct' => false],
                    ['text' => 'SMTP', 'correct' => true],
                    ['text' => 'DHCP', 'correct' => false],
                ]
            ],
            [
                'question' => 'Teknologi jaringan nirkabel yang umum digunakan dalam rumah dan kantor disebut:',
                'options' => [
                    ['text' => 'Bluetooth', 'correct' => false],
                    ['text' => 'Ethernet', 'correct' => false],
                    ['text' => 'Wi-Fi', 'correct' => true],
                    ['text' => 'NFC', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa fungsi dari DNS dalam sistem jaringan?',
                'options' => [
                    ['text' => 'Mengatur IP address otomatis', 'correct' => false],
                    ['text' => 'Mengubah nama domain menjadi IP address', 'correct' => true],
                    ['text' => 'Menyaring spam email', 'correct' => false],
                    ['text' => 'Menghubungkan dua perangkat Bluetooth', 'correct' => false],
                ]
            ],
            [
                'question' => 'Jaringan dengan jangkauan luas seperti antar kota atau negara disebut:',
                'options' => [
                    ['text' => 'LAN', 'correct' => false],
                    ['text' => 'PAN', 'correct' => false],
                    ['text' => 'MAN', 'correct' => false],
                    ['text' => 'WAN', 'correct' => true],
                ]
            ],
            [
                'question' => 'Protokol yang digunakan untuk mentransfer file antar komputer dalam jaringan adalah:',
                'options' => [
                    ['text' => 'FTP', 'correct' => true],
                    ['text' => 'SMTP', 'correct' => false],
                    ['text' => 'HTTP', 'correct' => false],
                    ['text' => 'DNS', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa fungsi utama dari firewall dalam jaringan komputer?',
                'options' => [
                    ['text' => 'Mendeteksi perangkat baru', 'correct' => false],
                    ['text' => 'Mempercepat internet', 'correct' => false],
                    ['text' => 'Melindungi jaringan dari serangan luar', 'correct' => true],
                    ['text' => 'Mencadangkan file', 'correct' => false],
                ]
            ],
            [
                'question' => 'Manakah dari berikut ini yang termasuk soft skill penting dalam berorganisasi?',
                'options' => [
                    ['text' => 'Kemampuan menghafal struktur organisasi', 'correct' => false],
                    ['text' => 'Kemampuan bekerja sama dan komunikasi yang baik dengan tim', 'correct' => true],
                    ['text' => 'Kemampuan mengatur perangkat keras untuk presentasi', 'correct' => false],
                    ['text' => 'Kemampuan menggunakan aplikasi edit video', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa yang menunjukkan bahwa seseorang memiliki kemampuan komunikasi yang baik dalam organisasi?',
                'options' => [
                    ['text' => 'Hanya berbicara jika diminta oleh atasan', 'correct' => false],
                    ['text' => 'Mendominasi diskusi dan tidak memberi ruang orang lain berbicara', 'correct' => false],
                    ['text' => 'Mampu menyampaikan ide dengan jelas serta mendengarkan pendapat orang lain', 'correct' => true],
                    ['text' => 'Menghindari diskusi kelompok dan memilih bekerja sendiri', 'correct' => false],
                ]
            ],
            [
                'question' => 'Salah satu tantangan terbesar dalam mengelola tim dalam organisasi adalah…',
                'options' => [
                    ['text' => 'Kurangnya komunikasi yang efektif', 'correct' => true],
                    ['text' => 'Terlalu banyak anggaran untuk kegiatan', 'correct' => false],
                    ['text' => 'Tidak adanya peraturan dalam organisasi', 'correct' => false],
                    ['text' => 'Tidak ada perbedaan pendapat dalam tim', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa yang sebaiknya dilakukan saat terjadi konflik antar anggota dalam suatu divisi?',
                'options' => [
                    ['text' => 'Mengabaikan konflik agar tidak membuang waktu', 'correct' => false],
                    ['text' => 'Menegur anggota yang dirasa salah tanpa mencari tahu akar permasalahan', 'correct' => false],
                    ['text' => 'Mendengarkan semua pihak dan mencari solusi bersama', 'correct' => true],
                    ['text' => 'Meminta ketua langsung mengambil keputusan tanpa mendiskusikan dengan tim', 'correct' => false],
                ]
            ],
            [
                'question' => 'Mengapa penting untuk memiliki kemampuan manajemen waktu dalam organisasi?',
                'options' => [
                    ['text' => 'Agar bisa menunda pekerjaan dan tetap terlihat sibuk', 'correct' => false],
                    ['text' => 'Supaya dapat menyelesaikan tanggung jawab secara efektif dan tidak terburu-buru', 'correct' => true],
                    ['text' => 'Agar bisa bekerja lebih lama daripada anggota lain', 'correct' => false],
                    ['text' => 'Untuk menghindari tugas-tugas yang sulit', 'correct' => false],
                ]
            ],
            [
                'question' => 'Salah satu ciri anggota organisasi yang memiliki inisiatif tinggi adalah...',
                'options' => [
                    ['text' => 'Menunggu instruksi tanpa melakukan apa pun', 'correct' => false],
                    ['text' => 'Melakukan pekerjaan hanya jika diminta', 'correct' => false],
                    ['text' => 'Bertindak cepat, menawarkan bantuan, dan mencari solusi saat ada masalah', 'correct' => true],
                    ['text' => 'Menyerahkan semua pekerjaan kepada ketua', 'correct' => false],
                ]
            ],
            [
                'question' => 'Dalam sebuah organisasi, pemimpin yang baik seharusnya...',
                'options' => [
                    ['text' => 'Hanya fokus pada hasil tanpa peduli proses', 'correct' => false],
                    ['text' => 'Bersikap otoriter agar semua berjalan cepat', 'correct' => false],
                    ['text' => 'Mampu mendengarkan, memberi motivasi, dan membangun semangat tim', 'correct' => true],
                    ['text' => 'Menyelesaikan semua tugas sendirian tanpa melibatkan anggota', 'correct' => false],
                ]
            ],
            [
                'question' => 'Salah satu peran penting seorang pemimpin dalam tim adalah...',
                'options' => [
                    ['text' => 'Memberi hukuman jika anggota tidak aktif', 'correct' => false],
                    ['text' => 'Menjadi pusat perhatian dalam setiap kegiatan', 'correct' => false],
                    ['text' => 'Mendorong kolaborasi, mengarahkan tujuan tim, dan menjaga keharmonisan kelompok', 'correct' => true],
                    ['text' => 'Mengatur semua hal sendiri agar tidak merepotkan anggota lain', 'correct' => false],
                ]
            ],
            [
                'question' => 'Setelah beberapa bulan bergabung dengan organisasi, Anda merasa kurang berkembang. Apa yang sebaiknya dilakukan?',
                'options' => [
                    ['text' => 'Berdiskusi dengan senior atau mentor untuk mencari cara meningkatkan keterampilan', 'correct' => true],
                    ['text' => 'Keluar dari organisasi tanpa mencari solusi', 'correct' => false],
                    ['text' => 'Mengeluh kepada anggota lain tanpa berusaha memperbaiki diri', 'correct' => false],
                    ['text' => 'Menunggu perubahan terjadi tanpa melakukan apa-apa', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa sebaiknya yang dilakukan jika ingin bergabung ke dalam organisasi, namun tidak memiliki banyak pengalaman?',
                'options' => [
                    ['text' => 'Mencari pengalaman terlebih dahulu dan menunda untuk memasuki organisasi tersebut', 'correct' => false],
                    ['text' => 'Menyerah dan tidak mencoba karena merasa tidak cukup baik', 'correct' => false],
                    ['text' => 'Menghindari organisasi dan berfokus pada kegiatan individu', 'correct' => false],
                    ['text' => 'Mengakui bahwa anda pemula dan menunjukkan kemauan untuk belajar', 'correct' => true],
                ]
            ],
            [
                'question' => 'Apa alasan yang paling tepat untuk bergabung dalam sebuah organisasi?',
                'options' => [
                    ['text' => 'Karena semua teman ikut, jadi ikut saja tanpa alasan yang jelas', 'correct' => false],
                    ['text' => 'Untuk mengembangkan diri, membangun relasi, dan mendapatkan pengalaman', 'correct' => true],
                    ['text' => 'Agar terlihat lebih keren di depan orang lain', 'correct' => false],
                    ['text' => 'Supaya bisa menghindari tugas akademik atau pekerjaan lain', 'correct' => false],
                ]
            ],
            [
                'question' => 'Saat menghadiri wawancara untuk masuk ke sebuah organisasi, apa yang sebaiknya di lakukan?',
                'options' => [
                    ['text' => 'Datang dengan pakaian rapi dan menjawab pertanyaan dengan jujur', 'correct' => true],
                    ['text' => 'Tidak perlu mempersiapkan diri karena wawancara tidak penting', 'correct' => false],
                    ['text' => 'Datang terlambat karena berpikir semua orang akan menunggu Anda', 'correct' => false],
                    ['text' => 'Menjawab pertanyaan dengan asal-asalan karena ingin cepat selesai', 'correct' => false],
                ]
            ],
            [
                'question' => 'Jika diterima dalam sebuah organisasi, sikap apa yang harus ditunjukkan sebagai anggota baru?',
                'options' => [
                    ['text' => 'Bersikap pasif dan menunggu perintah tanpa inisiatif', 'correct' => false],
                    ['text' => 'Menunjukkan sikap antusias, aktif, dan mau belajar dari anggota lama', 'correct' => true],
                    ['text' => 'Memaksakan pendapat sendiri dan tidak mau mendengarkan orang lain', 'correct' => false],
                    ['text' => 'Menganggap enteng tugas dan hanya hadir jika ada acara seru', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa yang harus dilakukan, jika sudah diterima di sebuah organisasi, namun sulit beradaptasi?',
                'options' => [
                    ['text' => 'Menutup diri dan menghindari interaksi dengan anggota lain', 'correct' => false],
                    ['text' => 'Keluar dari organisasi tanpa mencoba beradaptasi', 'correct' => false],
                    ['text' => 'Mencoba mengenal lebih banyak orang dan memahami budaya organisasi', 'correct' => true],
                    ['text' => 'Menunggu orang lain yang harus mendekati Anda lebih dulu', 'correct' => false],
                ]
            ],
            [
                'question' => 'Apa langkah terbaik yang bisa dilakukan, jika setelah bergabung dalam organisasi dan mendapatkan tugas pertama yang cukup sulit?',
                'options' => [
                    ['text' => 'Mengabaikan tugas tersebut karena merasa belum siap', 'correct' => false],
                    ['text' => 'Menyerah dan meminta orang lain untuk mengerjakan tugas Anda', 'correct' => false],
                    ['text' => 'Meminta bantuan atau arahan dari senior dan belajar dari pengalaman', 'correct' => true],
                    ['text' => 'Mengeluh kepada anggota lain tanpa mencari solusi', 'correct' => false],
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
