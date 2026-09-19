<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\Instansi;
use App\Models\Laporan;
use App\Models\LogKegiatan;
use App\Models\Magang;
use App\Models\Mahasiswa;
use App\Models\Penilaian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DosenFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Dosen $dosen;
    private Mahasiswa $mahasiswa;
    private Instansi $instansi;
    private Magang $magang;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::forceCreate(['name'=>'Dosen Test','email'=>'dosen@test.local','password'=>Hash::make('password'),'role'=>'dosen']);
        $this->dosen = Dosen::create(['user_id'=>$this->user->id,'nidn'=>'D001','nama'=>'Dosen Test','no_hp'=>'08123456789','alamat'=>'Kupang']);

        $studentUser=User::forceCreate(['name'=>'Mahasiswa Test','email'=>'mahasiswa@test.local','password'=>Hash::make('password'),'role'=>'mahasiswa']);
        $this->mahasiswa=Mahasiswa::create(['user_id'=>$studentUser->id,'nim'=>'MHS001','nama'=>'Mahasiswa Test','program_studi'=>'Teknik Informatika']);

        $this->instansi=Instansi::create(['nama_instansi'=>'PT Test Magang','alamat'=>'Kupang','no_telp'=>'0800000000','email'=>'pt@test.local','penanggung_jawab'=>'PIC']);

        $this->magang=Magang::create([
            'mahasiswa_id'=>$this->mahasiswa->id,'dosen_id'=>$this->dosen->id,'instansi_id'=>$this->instansi->id,
            'judul_magang'=>'Sistem Informasi Magang','tanggal_pengajuan'=>now()->subDays(20)->toDateString(),
            'tanggal_mulai'=>now()->subDays(10)->toDateString(),'tanggal_selesai'=>now()->addDays(20)->toDateString(),
            'status_pengajuan'=>'disetujui','status_magang'=>'berlangsung',
        ]);
    }

    public function test_dosen_can_access_all_main_modules(): void
    {
        $this->actingAs($this->user);
        foreach([
            route('dosen.dashboard'),route('dosen.profil'),route('dosen.mahasiswa-bimbingan'),
            route('dosen.pengajuan'),route('dosen.monitoring'),route('dosen.logbook'),
            route('dosen.penilaian'),route('dosen.laporan'),
        ] as $url) $this->get($url)->assertSuccessful();
    }

    public function test_dosen_can_update_profile_and_password(): void
    {
        $this->actingAs($this->user);
        $this->patch(route('dosen.profil.update'),[
            'nama'=>'Dosen Updated','nidn'=>'D009','email'=>'dosen-updated@test.local','no_hp'=>'08999999999','alamat'=>'Oesapa'
        ])->assertRedirect();

        $this->assertDatabaseHas('users',['id'=>$this->user->id,'name'=>'Dosen Updated','email'=>'dosen-updated@test.local']);
        $this->assertDatabaseHas('dosen',['id'=>$this->dosen->id,'nama'=>'Dosen Updated','nidn'=>'D009']);

        $this->patch(route('dosen.profil.password'),['current_password'=>'password','password'=>'new-password','password_confirmation'=>'new-password'])->assertRedirect();
        $this->assertTrue(Hash::check('new-password',$this->user->fresh()->password));
    }

    public function test_dosen_can_view_only_own_bimbingan(): void
    {
        $this->actingAs($this->user);
        $this->get(route('dosen.mahasiswa-bimbingan'))->assertSuccessful()->assertSee('Mahasiswa Test')->assertSee('Sistem Informasi Magang');
        $this->get(route('dosen.mahasiswa-bimbingan.show',$this->magang))->assertSuccessful()->assertSee('PT Test Magang');

        $otherUser=User::forceCreate(['name'=>'Dosen Other','email'=>'other-dosen@test.local','password'=>Hash::make('password'),'role'=>'dosen']);
        $other=Dosen::create(['user_id'=>$otherUser->id,'nidn'=>'D002','nama'=>'Dosen Other']);
        $otherMagang=$this->magang->replicate(); $otherMagang->dosen_id=$other->id; $otherMagang->save();

        $this->get(route('dosen.mahasiswa-bimbingan.show',$otherMagang))->assertForbidden();
    }

    public function test_dosen_can_validate_logbook_and_view_evidence(): void
    {
        Storage::fake('public'); $this->actingAs($this->user);
        $file=UploadedFile::fake()->create('bukti.pdf',100,'application/pdf');
        $path=$file->store('logbook','public');
        $log=LogKegiatan::create(['magang_id'=>$this->magang->id,'tanggal'=>now()->toDateString(),'judul_kegiatan'=>'Implementasi','deskripsi'=>'Implementasi modul','bukti_kegiatan'=>$path,'status_validasi'=>'menunggu']);

        $this->get(route('dosen.logbook.bukti',$log))->assertSuccessful();
        $this->patch(route('dosen.logbook.validate',$log),['status_validasi'=>'ditolak'])->assertSessionHasErrors('catatan_dosen');
        $this->patch(route('dosen.logbook.validate',$log),['status_validasi'=>'disetujui'])->assertRedirect();
        $this->assertDatabaseHas('log_kegiatan',['id'=>$log->id,'status_validasi'=>'disetujui']);
    }

    public function test_dosen_can_validate_report(): void
    {
        $this->actingAs($this->user);
        $laporan=Laporan::create(['magang_id'=>$this->magang->id,'nama_file'=>'laporan.pdf','file_path'=>'laporan/laporan.pdf','tanggal_upload'=>now(),'status'=>'belum_validasi']);
        $this->patch(route('dosen.laporan.validate',$laporan),['status'=>'ditolak'])->assertSessionHasErrors('catatan_dosen');
        $this->patch(route('dosen.laporan.validate',$laporan),['status'=>'disetujui'])->assertRedirect();
        $this->assertDatabaseHas('laporan',['id'=>$laporan->id,'status'=>'disetujui']);
    }

    public function test_dosen_can_create_and_update_assessment(): void
    {
        $this->actingAs($this->user);
        $payload=['kedisiplinan'=>80,'tanggung_jawab'=>90,'kerja_sama'=>85,'kemampuan_teknis'=>95,'sikap'=>90,'catatan'=>'Baik'];
        $this->post(route('dosen.penilaian.store',$this->magang),$payload)->assertRedirect();
        $this->assertDatabaseHas('penilaian',['magang_id'=>$this->magang->id,'dosen_id'=>$this->dosen->id,'nilai_akhir'=>88]);
        $this->get(route('dosen.penilaian.edit',$this->magang))->assertSuccessful()->assertSee('Baik');
        $payload['kemampuan_teknis']=100;
        $this->post(route('dosen.penilaian.store',$this->magang),$payload)->assertRedirect();
        $this->assertSame(1,Penilaian::where('magang_id',$this->magang->id)->count());
    }

    public function test_other_roles_cannot_access_dosen_modules(): void
    {
        $student=User::forceCreate(['name'=>'Student','email'=>'student2@test.local','password'=>Hash::make('password'),'role'=>'mahasiswa']);
        $this->actingAs($student)->get(route('dosen.dashboard'))->assertForbidden();
    }
}
