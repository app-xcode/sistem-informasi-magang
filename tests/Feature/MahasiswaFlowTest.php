<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\Instansi;
use App\Models\Laporan;
use App\Models\Magang;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MahasiswaFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Mahasiswa $mahasiswa;
    private Dosen $dosen;
    private Instansi $instansi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::forceCreate([
            'name' => 'Mahasiswa Test',
            'email' => 'mahasiswa@test.local',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        $this->mahasiswa = Mahasiswa::create([
            'user_id' => $this->user->id,
            'nim' => 'MHS001',
            'nama' => 'Mahasiswa Test',
            'program_studi' => 'Teknik Informatika',
            'no_hp' => '08123456789',
            'alamat' => 'Kupang',
        ]);

        $dosenUser = User::forceCreate([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.local',
            'password' => Hash::make('password'),
            'role' => 'dosen',
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
            'penanggung_jawab' => 'PIC Test',
        ]);
    }

    public function test_mahasiswa_can_access_all_main_modules(): void
    {
        $this->actingAs($this->user);

        foreach ([
            route('mahasiswa.dashboard'),
            route('mahasiswa.profil'),
            route('mahasiswa.pengajuan'),
            route('mahasiswa.tempat-magang'),
            route('mahasiswa.status-magang'),
            route('mahasiswa.logbook'),
            route('mahasiswa.laporan'),
        ] as $url) {
            $this->get($url)->assertSuccessful();
        }
    }

    public function test_mahasiswa_can_update_profile_and_password(): void
    {
        $this->actingAs($this->user);

        $this->patch(route('mahasiswa.profil.update'), [
            'name' => 'Mahasiswa Updated',
            'email' => 'updated@test.local',
            'nim' => 'MHS999',
            'program_studi' => 'Sistem Informasi',
            'no_hp' => '08999999999',
            'alamat' => 'Oesapa',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Mahasiswa Updated',
            'email' => 'updated@test.local',
        ]);

        $this->assertDatabaseHas('mahasiswa', [
            'id' => $this->mahasiswa->id,
            'nim' => 'MHS999',
            'program_studi' => 'Sistem Informasi',
        ]);

        $this->patch(route('mahasiswa.profil.password'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect();

        $this->assertTrue(Hash::check('new-password', $this->user->fresh()->password));
    }

    public function test_mahasiswa_can_submit_and_view_internship_application(): void
    {
        $this->actingAs($this->user);

        $this->post(route('mahasiswa.pengajuan.store'), [
            'instansi_id' => $this->instansi->id,
            'dosen_id' => $this->dosen->id,
            'judul_magang' => 'Pengembangan Sistem Informasi',
            'tanggal_mulai' => now()->addDays(7)->toDateString(),
            'tanggal_selesai' => now()->addDays(37)->toDateString(),
            'keterangan' => 'Pengajuan magang test.',
        ])->assertRedirect(route('mahasiswa.pengajuan'));

        $this->assertDatabaseHas('magang', [
            'mahasiswa_id' => $this->mahasiswa->id,
            'instansi_id' => $this->instansi->id,
            'status_pengajuan' => 'diajukan',
            'status_magang' => 'belum_mulai',
        ]);

        $this->get(route('mahasiswa.pengajuan'))
            ->assertSuccessful()
            ->assertSee('Pengembangan Sistem Informasi')
            ->assertSee('Menunggu');
    }

    public function test_mahasiswa_cannot_create_second_active_application(): void
    {
        $this->actingAs($this->user);

        Magang::create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'dosen_id' => $this->dosen->id,
            'instansi_id' => $this->instansi->id,
            'judul_magang' => 'Magang Aktif',
            'tanggal_pengajuan' => now()->toDateString(),
            'tanggal_mulai' => now()->addDays(2)->toDateString(),
            'tanggal_selesai' => now()->addDays(32)->toDateString(),
            'status_pengajuan' => 'disetujui',
            'status_magang' => 'belum_mulai',
        ]);

        $this->get(route('mahasiswa.pengajuan.create'))
            ->assertRedirect(route('mahasiswa.status-magang'));

        $this->post(route('mahasiswa.pengajuan.store'), [
            'instansi_id' => $this->instansi->id,
            'judul_magang' => 'Pengajuan Kedua',
        ])->assertRedirect(route('mahasiswa.pengajuan'))
          ->assertSessionHas('error');
    }

    public function test_mahasiswa_can_browse_places_and_status(): void
    {
        $this->actingAs($this->user);

        $this->get(route('mahasiswa.tempat-magang', ['q' => 'PT Test']))
            ->assertSuccessful()
            ->assertSee('PT Test Magang');

        Magang::create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'dosen_id' => $this->dosen->id,
            'instansi_id' => $this->instansi->id,
            'judul_magang' => 'Magang Berlangsung',
            'tanggal_pengajuan' => now()->subDays(10)->toDateString(),
            'tanggal_mulai' => now()->subDays(3)->toDateString(),
            'tanggal_selesai' => now()->addDays(27)->toDateString(),
            'status_pengajuan' => 'disetujui',
            'status_magang' => 'berlangsung',
        ]);

        $this->get(route('mahasiswa.status-magang'))
            ->assertSuccessful()
            ->assertSee('Magang Berlangsung')
            ->assertSee('PT Test Magang');
    }

    public function test_mahasiswa_can_upload_download_and_delete_report(): void
    {
        Storage::fake('public');
        $this->actingAs($this->user);

        $magang = Magang::create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'dosen_id' => $this->dosen->id,
            'instansi_id' => $this->instansi->id,
            'judul_magang' => 'Magang Selesai',
            'tanggal_pengajuan' => now()->subDays(40)->toDateString(),
            'tanggal_mulai' => now()->subDays(35)->toDateString(),
            'tanggal_selesai' => now()->subDays(5)->toDateString(),
            'status_pengajuan' => 'disetujui',
            'status_magang' => 'selesai',
        ]);

        $file = UploadedFile::fake()->create('laporan-akhir.pdf', 100, 'application/pdf');

        $this->post(route('mahasiswa.laporan.store'), ['file' => $file])
            ->assertRedirect();

        $laporan = Laporan::firstOrFail();

        Storage::disk('public')->assertExists($laporan->file_path);

        $this->get(route('mahasiswa.laporan'))
            ->assertSuccessful()
            ->assertSee('laporan-akhir.pdf')
            ->assertSee('Belum Validasi');

        $this->get(route('mahasiswa.laporan.download', $laporan))
            ->assertSuccessful();

        $this->delete(route('mahasiswa.laporan.destroy', $laporan))
            ->assertRedirect();

        $this->assertDatabaseMissing('laporan', ['id' => $laporan->id]);
    }

    public function test_mahasiswa_cannot_access_other_student_data(): void
    {
        $otherUser = User::forceCreate([
            'name' => 'Other Student',
            'email' => 'other@test.local',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        $other = Mahasiswa::create([
            'user_id' => $otherUser->id,
            'nim' => 'OTHER001',
            'nama' => 'Other Student',
            'program_studi' => 'Teknik Informatika',
        ]);

        $magang = Magang::create([
            'mahasiswa_id' => $other->id,
            'instansi_id' => $this->instansi->id,
            'judul_magang' => 'Data Mahasiswa Lain',
            'tanggal_pengajuan' => now()->toDateString(),
            'status_pengajuan' => 'diajukan',
            'status_magang' => 'belum_mulai',
        ]);

        $this->actingAs($this->user);

        $this->get(route('mahasiswa.laporan.download', Laporan::create([
            'magang_id' => $magang->id,
            'nama_file' => 'other.pdf',
            'file_path' => 'laporan/other.pdf',
            'tanggal_upload' => now(),
            'status' => 'belum_validasi',
        ])))->assertForbidden();
    }

    public function test_other_roles_cannot_access_mahasiswa_modules(): void
    {
        $admin = User::forceCreate([
            'name' => 'Admin',
            'email' => 'admin@test.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('mahasiswa.dashboard'))
            ->assertForbidden();
    }
}
