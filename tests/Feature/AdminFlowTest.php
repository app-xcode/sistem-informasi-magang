<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\Instansi;
use App\Models\Laporan;
use App\Models\LogKegiatan;
use App\Models\Magang;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Mahasiswa $mahasiswa;
    private Dosen $dosen;
    private Instansi $instansi;
    private Magang $magang;
    private LogKegiatan $logbook;
    private Laporan $laporan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::forceCreate([
            'name' => 'Admin Test',
            'email' => 'admin@test.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $studentUser = User::forceCreate([
            'name' => 'Mahasiswa Test',
            'email' => 'mahasiswa@test.local',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        $dosenUser = User::forceCreate([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.local',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        $this->mahasiswa = Mahasiswa::create([
            'user_id' => $studentUser->id,
            'nim' => 'TEST001',
            'nama' => 'Mahasiswa Test',
            'program_studi' => 'Teknik Informatika',
        ]);

        $this->dosen = Dosen::create([
            'user_id' => $dosenUser->id,
            'nidn' => 'D001',
            'nama' => 'Dosen Test',
        ]);

        $this->instansi = Instansi::create([
            'nama_instansi' => 'PT Test Magang',
            'alamat' => 'Kupang',
            'no_telp' => '0800000000',
            'email' => 'pt@test.local',
            'penanggung_jawab' => 'Penanggung Jawab',
        ]);

        $this->magang = Magang::create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'dosen_id' => $this->dosen->id,
            'instansi_id' => $this->instansi->id,
            'judul_magang' => 'Sistem Informasi Test',
            'tanggal_pengajuan' => now()->toDateString(),
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(25)->toDateString(),
            'status_pengajuan' => 'disetujui',
            'status_magang' => 'berlangsung',
        ]);

        $this->logbook = LogKegiatan::create([
            'magang_id' => $this->magang->id,
            'tanggal' => now()->toDateString(),
            'judul_kegiatan' => 'Mengerjakan fitur dashboard',
            'deskripsi' => 'Pengujian fitur dashboard admin.',
            'status_validasi' => 'menunggu',
        ]);

        $this->laporan = Laporan::create([
            'magang_id' => $this->magang->id,
            'nama_file' => 'laporan-test.pdf',
            'file_path' => 'laporan/laporan-test.pdf',
            'tanggal_upload' => now(),
            'status' => 'belum_validasi',
        ]);
    }

    public function test_admin_can_access_all_main_admin_modules(): void
    {
        $this->actingAs($this->admin);

        $routes = [
            route('admin.dashboard'),
            route('admin.mahasiswa.index'),
            route('admin.dosen.index'),
            route('admin.perusahaan.index'),
            route('admin.pengajuan.index'),
            route('admin.magang.index'),
            route('admin.monitoring.index'),
            route('admin.laporan.index'),
            route('admin.pengaturan.index'),
        ];

        foreach ($routes as $url) {
            $this->get($url)->assertSuccessful();
        }
    }

    public function test_admin_dashboard_uses_real_database_data(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.dashboard'));

        $response
            ->assertSuccessful()
            ->assertSee('Mahasiswa Test')
            ->assertSee('PT Test Magang')
            ->assertSee('Dashboard Admin')
            ->assertSee('Pengajuan')
            ->assertSee('Logbook')
            ->assertSee('Laporan');
    }

    public function test_admin_can_view_monitoring_and_logbook_progress_data(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('admin.monitoring.index'))
            ->assertSuccessful()
            ->assertSee('Mahasiswa Test')
            ->assertSee('Dosen Test')
            ->assertSee('PT Test Magang')
            ->assertSee('Berlangsung')
            ->assertSee('1 logbook');
    }

    public function test_admin_can_view_and_download_reports(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put($this->laporan->file_path, 'PDF TEST CONTENT');

        $this->actingAs($this->admin);

        $this->get(route('admin.laporan.index'))
            ->assertSuccessful()
            ->assertSee('laporan-test.pdf')
            ->assertSee('Belum Validasi');

        $this->get(route('admin.laporan.download', $this->laporan))
            ->assertSuccessful();
    }

    public function test_admin_can_update_magang_status(): void
    {
        $this->actingAs($this->admin);

        $this->patch(route('admin.magang.status', $this->magang), [
            'status_magang' => 'selesai',
        ])
            ->assertRedirect(route('admin.magang.show', $this->magang));

        $this->assertDatabaseHas('magang', [
            'id' => $this->magang->id,
            'status_magang' => 'selesai',
        ]);
    }

    public function test_admin_can_update_profile_and_password(): void
    {
        $this->actingAs($this->admin);

        $this->patch(route('admin.pengaturan.profile'), [
            'name' => 'Admin Updated',
            'email' => 'admin.updated@test.local',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'name' => 'Admin Updated',
            'email' => 'admin.updated@test.local',
        ]);

        $this->patch(route('admin.pengaturan.password'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect();

        $this->assertTrue(Hash::check('new-password', $this->admin->fresh()->password));
    }

    public function test_non_admin_cannot_access_admin_modules(): void
    {
        $student = User::where('email', 'mahasiswa@test.local')->firstOrFail();

        $this->actingAs($student)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_listing_supports_search_filter_pagination_and_empty_state(): void
    {
        $this->actingAs($this->admin);

        for ($i = 1; $i <= 10; $i++) {
            Magang::create([
                'mahasiswa_id' => $this->mahasiswa->id,
                'dosen_id' => $this->dosen->id,
                'instansi_id' => $this->instansi->id,
                'judul_magang' => "Pengajuan Tambahan {$i}",
                'tanggal_pengajuan' => now()->subDays($i)->toDateString(),
                'status_pengajuan' => 'diajukan',
            ]);
        }

        $this->get(route('admin.pengajuan.index', ['status' => 'diajukan']))
            ->assertSuccessful()
            ->assertSee('Pengajuan Tambahan 10')
            ->assertSee('Pengajuan Tambahan 1');

        $this->get(route('admin.pengajuan.index', ['q' => 'Pengajuan Tambahan 5']))
            ->assertSuccessful()
            ->assertSee('Pengajuan Tambahan 5');

        $this->get(route('admin.pengajuan.index', ['q' => 'Tidak Ada Data XYZ']))
            ->assertSuccessful()
            ->assertSee('Belum ada pengajuan magang.');

        $this->get(route('admin.pengajuan.index', ['page' => 2]))
            ->assertSuccessful()
            ->assertSee('Pengajuan Tambahan 10');
    }

    public function test_admin_rejects_invalid_magang_relations_and_missing_rejection_reason(): void
    {
        $this->actingAs($this->admin);

        $this->from(route('admin.pengajuan.create'))
            ->post(route('admin.pengajuan.store'), [
                'mahasiswa_id' => 999999,
                'dosen_id' => 999999,
                'instansi_id' => 999999,
                'judul_magang' => 'Invalid Relation',
                'tanggal_pengajuan' => now()->toDateString(),
                'status_pengajuan' => 'ditolak',
            ])
            ->assertRedirect(route('admin.pengajuan.create'))
            ->assertSessionHasErrors([
                'mahasiswa_id',
                'dosen_id',
                'instansi_id',
                'alasan_penolakan',
            ]);
    }

    public function test_admin_cannot_delete_magang_that_already_has_logbook_or_report(): void
    {
        $this->actingAs($this->admin);

        $this->delete(route('admin.pengajuan.destroy', $this->magang))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('magang', ['id' => $this->magang->id]);
    }

    public function test_admin_dashboard_and_modules_follow_approved_internship_flow(): void
    {
        $rejected = Magang::create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'dosen_id' => $this->dosen->id,
            'instansi_id' => $this->instansi->id,
            'judul_magang' => 'Magang Ditolak',
            'tanggal_pengajuan' => now()->toDateString(),
            'status_pengajuan' => 'ditolak',
            'alasan_penolakan' => 'Data tidak lengkap',
        ]);

        LogKegiatan::create([
            'magang_id' => $rejected->id,
            'tanggal' => now()->toDateString(),
            'judul_kegiatan' => 'Logbook Ditolak',
            'deskripsi' => 'Tidak boleh muncul pada monitoring approved.',
            'status_validasi' => 'menunggu',
        ]);

        $rejectedReport = Laporan::create([
            'magang_id' => $rejected->id,
            'nama_file' => 'laporan-ditolak.pdf',
            'file_path' => 'laporan/laporan-ditolak.pdf',
            'tanggal_upload' => now(),
            'status' => 'belum_validasi',
        ]);

        $this->get(route('admin.dashboard'))
            ->assertSuccessful()
            ->assertSee('Mengerjakan fitur dashboard')
            ->assertDontSee('Logbook Ditolak')
            ->assertDontSee('laporan-ditolak.pdf');

        $this->get(route('admin.magang.index'))
            ->assertSee('Sistem Informasi Test')
            ->assertDontSee('Magang Ditolak');

        $this->get(route('admin.monitoring.index'))
            ->assertSee('Sistem Informasi Test')
            ->assertDontSee('Magang Ditolak');

        $this->get(route('admin.laporan.index'))
            ->assertSee('laporan-test.pdf')
            ->assertDontSee('laporan-ditolak.pdf');

        $this->get(route('admin.laporan.download', $rejectedReport))
            ->assertNotFound();
    }

    public function test_admin_report_download_returns_404_when_file_is_missing(): void
    {
        $this->actingAs($this->admin);

        Storage::fake('public');

        $this->get(route('admin.laporan.download', $this->laporan))
            ->assertNotFound();
    }

    public function test_admin_can_filter_monitoring_and_reports(): void
    {
        $approvedDone = Magang::create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'dosen_id' => $this->dosen->id,
            'instansi_id' => $this->instansi->id,
            'judul_magang' => 'Magang Selesai',
            'tanggal_pengajuan' => now()->subDays(30)->toDateString(),
            'tanggal_mulai' => now()->subDays(25)->toDateString(),
            'tanggal_selesai' => now()->subDays(5)->toDateString(),
            'status_pengajuan' => 'disetujui',
            'status_magang' => 'selesai',
        ]);

        $doneReport = Laporan::create([
            'magang_id' => $approvedDone->id,
            'nama_file' => 'laporan-selesai.pdf',
            'file_path' => 'laporan/laporan-selesai.pdf',
            'tanggal_upload' => now()->subDay(),
            'status' => 'disetujui',
        ]);

        $this->get(route('admin.monitoring.index', ['status' => 'selesai']))
            ->assertSuccessful()
            ->assertSee('Magang Selesai')
            ->assertDontSee('Sistem Informasi Test');

        $this->get(route('admin.laporan.index', ['status' => 'disetujui']))
            ->assertSuccessful()
            ->assertSee('laporan-selesai.pdf')
            ->assertDontSee('laporan-test.pdf');
    }

}
