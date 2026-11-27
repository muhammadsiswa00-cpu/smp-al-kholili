import { turso } from './turso';
import fs from 'node:fs';
import path from 'node:path';

export interface SiteSettings {
  site_name: string;
  site_tagline: string;
  hero_title: string;
  hero_subtitle: string;
  hero_bg: string;
  logo: string;
  ppdb_url: string;
  ppdb_open: boolean;
  whatsapp_number: string;
  whatsapp_display: string;
  phone: string;
  email: string;
  alamat: string;
  jam_kerja: string;
  maps_embed: string;
  nama_kepala_sekolah?: string;
  kepala_sekolah_foto?: string;
  sambutan_kepala_sekolah?: string;
  [key: string]: any;
}

export interface Teacher {
  id: number | string;
  nama: string;
  nip?: string;
  password?: string;
  mapel: string;
  foto: string;
  jabatan?: string;
  pendidikan?: string;
  color?: string;
  urutan?: number;
  status?: string;
}

export interface GalleryItem {
  id: number | string;
  judul: string;
  kategori: string;
  kategori_label?: string;
  gambar?: string;
  foto?: string;
  deskripsi?: string;
  tanggal?: string;
  tgl_unggah?: string;
  icon?: string;
  gradient?: string;
}

export interface NewsItem {
  id: number | string;
  judul: string;
  slug?: string;
  konten?: string;
  ringkasan?: string;
  kategori?: string;
  gambar?: string;
  penulis?: string;
  tanggal?: string;
  tgl_posting?: string;
  dilihat?: number;
  icon?: string;
  badge?: string;
}

const defaultSettings: SiteSettings = {
  site_name: "SMP ISLAM AL-KHOLILI",
  site_tagline: "Kota Probolinggo • Berkarakter Qur'ani",
  hero_title: "Selamat Datang di SMP Islam Al-Kholili",
  hero_subtitle: "Mewujudkan generasi Muslim yang kokoh dalam akidah, unggul dalam prestasi akademik, santun dalam budi pekerti, serta terampil dalam penguasaan teknologi.",
  hero_bg: "assets/img/hero-bg.svg",
  logo: "assets/img/logo.png",
  ppdb_url: "/ppdb",
  ppdb_open: true,
  whatsapp_number: "6285234567890",
  whatsapp_display: "+62 852-3456-7890",
  phone: "(0335) 421xxx / 0852-3456-7890",
  email: "info@smpislam-alkholili.sch.id",
  alamat: "Jl. KUA Blok Masjid Baiturrohman RT03 RW04 Kel. Kedopok, Kec. Kedopok, Kota Probolinggo",
  jam_kerja: "Senin - Sabtu: 07.00 - 14.00 WIB",
  maps_embed: "https://maps.google.com/maps?q=Kedopok,+Kota+Probolinggo,+Jawa+Timur&t=&z=15&ie=UTF8&iwloc=&output=embed",
  nama_kepala_sekolah: "Muhammad Taufiqur Rohman, S.Pd,",
  kepala_sekolah_foto: "assets/uploads/kepsek_1794985994.png"
};

function readLocalJson<T>(filename: string, fallback: T): T {
  try {
    const filePath = path.resolve(process.cwd(), 'data', filename);
    if (fs.existsSync(filePath)) {
      const raw = fs.readFileSync(filePath, 'utf-8');
      return JSON.parse(raw) as T;
    }
  } catch (err) {
    console.warn(`[LocalData] Could not read data/${filename}:`, err);
  }
  return fallback;
}

export async function getSiteSettings(): Promise<SiteSettings> {
  if (turso) {
    try {
      const res = await turso.execute("SELECT * FROM pengaturan LIMIT 1");
      if (res.rows && res.rows.length > 0) {
        const row = res.rows[0];
        return {
          ...defaultSettings,
          site_name: String(row.site_name || defaultSettings.site_name),
          site_tagline: String(row.site_tagline || defaultSettings.site_tagline),
          hero_title: String(row.hero_title || defaultSettings.hero_title),
          hero_subtitle: String(row.hero_subtitle || defaultSettings.hero_subtitle),
          hero_bg: String(row.hero_bg || defaultSettings.hero_bg),
          logo: String(row.logo || defaultSettings.logo),
          ppdb_url: (row.ppdb_url && !String(row.ppdb_url).includes('ppdb.smpislam-alkholili.sch.id')) ? String(row.ppdb_url) : '/ppdb',
          whatsapp_number: String(row.whatsapp_number || defaultSettings.whatsapp_number),
          whatsapp_display: String(row.whatsapp_display || defaultSettings.whatsapp_display),
          phone: String(row.phone || defaultSettings.phone),
          email: String(row.email || defaultSettings.email),
          alamat: String(row.alamat || defaultSettings.alamat),
          nama_kepala_sekolah: String(row.nama_kepsek || defaultSettings.nama_kepala_sekolah),
          kepala_sekolah_foto: String(row.kepsek_foto || defaultSettings.kepala_sekolah_foto),
          sambutan_kepala_sekolah: row.sambutan_kepsek ? String(row.sambutan_kepsek) : undefined
        };
      }
    } catch (err) {
      console.warn('[Turso] Error query pengaturan, falling back to JSON:', err);
    }
  }
  const local = readLocalJson<Partial<SiteSettings>>('settings.json', {});
  return { ...defaultSettings, ...local };
}

export async function getTeachers(): Promise<Teacher[]> {
  if (turso) {
    try {
      const res = await turso.execute("SELECT * FROM guru ORDER BY urutan ASC, id ASC");
      if (res.rows && res.rows.length > 0) {
        return res.rows.map(r => ({
          id: Number(r.id),
          nama: String(r.nama),
          nip: r.nip ? String(r.nip) : undefined,
          password: r.password ? String(r.password) : undefined,
          jabatan: r.jabatan ? String(r.jabatan) : 'Guru',
          mapel: String(r.mapel || '-'),
          pendidikan: r.pendidikan ? String(r.pendidikan) : '-',
          foto: String(r.foto || 'assets/img/teachers/male-1.svg'),
          urutan: r.urutan ? Number(r.urutan) : 0
        }));
      }
    } catch (err) {
      console.warn('[Turso] Error query guru, falling back to JSON:', err);
    }
  }
  return readLocalJson<Teacher[]>('teachers.json', [
    { id: 1, nama: "Muhammad Taufiqur Rohman, S.Pd,", jabatan: "Kepala Sekolah", mapel: "Manajemen Pendidikan", foto: "assets/uploads/kepsek_1794985994.png" }
  ]);
}

export async function getGallery(): Promise<GalleryItem[]> {
  if (turso) {
    try {
      const res = await turso.execute("SELECT id, judul, kategori, gambar, deskripsi FROM galeri ORDER BY id ASC");
      if (res.rows && res.rows.length > 0) {
        return res.rows.map(r => ({
          id: Number(r.id),
          judul: String(r.judul),
          kategori: String(r.kategori || 'Kegiatan'),
          kategori_label: String(r.kategori === 'ibadah' ? 'Keagamaan & Ibadah' : (r.kategori === 'tahfidz' ? 'Tahfidz Qur\'an' : (r.kategori === 'belajar' ? 'Belajar & Kelas' : (r.kategori === 'ekskul' ? 'Ekstrakurikuler' : 'Fasilitas')))),
          gambar: String(r.gambar || ''),
          foto: String(r.gambar || ''),
          deskripsi: r.deskripsi ? String(r.deskripsi) : undefined,
          icon: String(r.kategori === 'ibadah' ? 'fa-mosque' : (r.kategori === 'tahfidz' ? 'fa-book-quran' : (r.kategori === 'belajar' ? 'fa-flask' : 'fa-drum')))
        }));
      }
    } catch (err) {
      console.warn('[Turso] Error query galeri, falling back to JSON:', err);
    }
  }
  return readLocalJson<GalleryItem[]>('gallery.json', [
    { id: 1, judul: "Sholat Berjamaah di Masjid Baiturrohman", kategori: "ibadah", gambar: "assets/uploads/hero_bg_1795050753.jpeg" }
  ]);
}

export async function getNews(): Promise<NewsItem[]> {
  if (turso) {
    try {
      const res = await turso.execute("SELECT id, judul, kategori, tanggal, penulis, gambar, ringkasan FROM berita ORDER BY id DESC");
      if (res.rows && res.rows.length > 0) {
        return res.rows.map(r => ({
          id: Number(r.id),
          judul: String(r.judul),
          kategori: String(r.kategori || 'Berita'),
          tanggal: String(r.tanggal || ''),
          penulis: r.penulis ? String(r.penulis) : 'Humas Sekolah',
          gambar: String(r.gambar || ''),
          ringkasan: r.ringkasan ? String(r.ringkasan) : '',
          konten: r.ringkasan ? String(r.ringkasan) : '',
          icon: String(r.kategori && r.kategori.includes('PPDB') ? 'fa-bullhorn' : (r.kategori && r.kategori.includes('Tahfidz') ? 'fa-book-quran' : 'fa-newspaper')),
          badge: String(r.kategori && r.kategori.includes('PPDB') ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800')
        }));
      }
    } catch (err) {
      console.warn('[Turso] Error query berita, falling back to JSON:', err);
    }
  }
  return readLocalJson<NewsItem[]>('news.json', [
    {
      id: 1,
      judul: "Sosialisasi Pembukaan PPDB Tahun Ajaran 2026/2027 di SMP Islam Al-Kholili",
      ringkasan: "Panitia Penerimaan Peserta Didik Baru (PPDB) resmi membuka pendaftaran gelombang pertama.",
      penulis: "Humas Sekolah",
      tanggal: "15 November 2026"
    }
  ]);
}

export interface PpdbItem {
  id: string | number;
  nama: string;
  nama_lengkap?: string;
  jenis_kelamin?: string;
  nisn?: string;
  tempat_lahir?: string;
  tanggal_lahir?: string;
  alamat_lengkap?: string;
  email?: string;
  asal_sekolah?: string;
  alamat_sekolah_asal?: string;
  jalur?: string;
  no_wa?: string;
  waktu?: string;
  status?: string;
  nama_ayah?: string;
  tempat_lahir_ayah?: string;
  tanggal_lahir_ayah?: string;
  pekerjaan_ayah?: string;
  pendidikan_ayah?: string;
  telepon_ayah?: string;
  nama_ibu?: string;
  tempat_lahir_ibu?: string;
  tanggal_lahir_ibu?: string;
  pekerjaan_ibu?: string;
  pendidikan_ibu?: string;
  telepon_ibu?: string;
  berkas_akta?: string;
  berkas_ktp?: string;
  berkas_foto?: string;
  berkas_rapor?: string;
  berkas_skl?: string;
  catatan?: string;
}

export interface StudentItem {
  id: number;
  nis: string;
  nisn: string;
  nama: string;
  jenis_kelamin: 'L' | 'P';
  kelas: string;
  tempat_lahir?: string;
  tanggal_lahir?: string;
  alamat?: string;
  nama_ortu?: string;
  telepon_ortu?: string;
  status: 'Aktif' | 'Lulus' | 'Pindah';
}

export interface StudentAttendanceItem {
  id: string;
  tanggal: string;
  kelas: string;
  total_siswa: number;
  hadir: number;
  sakit: number;
  izin: number;
  alpa: number;
  records: Array<{
    student_id: number;
    nama: string;
    status: 'H' | 'S' | 'I' | 'A';
    keterangan?: string;
  }>;
  created_at?: string;
}

export interface TeacherAttendanceItem {
  id: string;
  tanggal: string;
  total_guru: number;
  hadir: number;
  sakit: number;
  izin: number;
  dinas_luar: number;
  records: Array<{
    guru_id: number | string;
    nama: string;
    status: 'Hadir' | 'Terlambat' | 'Sakit' | 'Izin' | 'Dinas Luar';
    jam_masuk?: string;
    jam_pulang?: string;
    keterangan?: string;
  }>;
  created_at?: string;
}

export async function getPpdbList(): Promise<PpdbItem[]> {
  if (turso) {
    try {
      const res = await turso.execute("SELECT * FROM ppdb ORDER BY id DESC");
      if (res.rows && res.rows.length > 0) {
        return res.rows.map(r => ({
          id: String(r.id || r.no_pendaftaran),
          nama: String(r.nama_lengkap || r.nama),
          nama_lengkap: String(r.nama_lengkap || r.nama),
          jenis_kelamin: r.jenis_kelamin ? String(r.jenis_kelamin) : 'Laki-laki',
          nisn: r.nisn ? String(r.nisn) : '-',
          tempat_lahir: r.tempat_lahir ? String(r.tempat_lahir) : '',
          tanggal_lahir: r.tanggal_lahir ? String(r.tanggal_lahir) : '',
          alamat_lengkap: r.alamat_lengkap || r.alamat ? String(r.alamat_lengkap || r.alamat) : '',
          email: r.email ? String(r.email) : '',
          asal_sekolah: r.asal_sekolah ? String(r.asal_sekolah) : '-',
          alamat_sekolah_asal: r.alamat_sekolah_asal ? String(r.alamat_sekolah_asal) : '',
          jalur: r.jalur ? String(r.jalur) : 'Reguler',
          no_wa: r.no_wa || r.no_whatsapp ? String(r.no_wa || r.no_whatsapp) : '-',
          waktu: r.created_at || r.waktu ? String(r.created_at || r.waktu) : '-',
          status: r.status ? String(r.status) : 'Menunggu',
          nama_ayah: r.nama_ayah ? String(r.nama_ayah) : '',
          tempat_lahir_ayah: r.tempat_lahir_ayah ? String(r.tempat_lahir_ayah) : '',
          tanggal_lahir_ayah: r.tanggal_lahir_ayah ? String(r.tanggal_lahir_ayah) : '',
          pekerjaan_ayah: r.pekerjaan_ayah ? String(r.pekerjaan_ayah) : '',
          pendidikan_ayah: r.pendidikan_ayah ? String(r.pendidikan_ayah) : '',
          telepon_ayah: r.telepon_ayah ? String(r.telepon_ayah) : '',
          nama_ibu: r.nama_ibu ? String(r.nama_ibu) : '',
          tempat_lahir_ibu: r.tempat_lahir_ibu ? String(r.tempat_lahir_ibu) : '',
          tanggal_lahir_ibu: r.tanggal_lahir_ibu ? String(r.tanggal_lahir_ibu) : '',
          pekerjaan_ibu: r.pekerjaan_ibu ? String(r.pekerjaan_ibu) : '',
          pendidikan_ibu: r.pendidikan_ibu ? String(r.pendidikan_ibu) : '',
          telepon_ibu: r.telepon_ibu ? String(r.telepon_ibu) : '',
          berkas_akta: r.berkas_akta ? String(r.berkas_akta) : '',
          berkas_ktp: r.berkas_ktp ? String(r.berkas_ktp) : '',
          berkas_foto: r.berkas_foto ? String(r.berkas_foto) : '',
          berkas_rapor: r.berkas_rapor ? String(r.berkas_rapor) : '',
          berkas_skl: r.berkas_skl ? String(r.berkas_skl) : '',
          catatan: r.catatan ? String(r.catatan) : ''
        }));
      }
    } catch (err) {
      console.warn('[Turso] Error query ppdb, falling back to JSON:', err);
    }
  }
  return readLocalJson<PpdbItem[]>('ppdb_submissions.json', []);
}

export async function getStudents(): Promise<StudentItem[]> {
  if (turso) {
    try {
      const res = await turso.execute("SELECT * FROM siswa ORDER BY kelas ASC, nama ASC");
      if (res.rows && res.rows.length > 0) {
        return res.rows.map(r => ({
          id: Number(r.id),
          nis: String(r.nis || ''),
          nisn: String(r.nisn || ''),
          nama: String(r.nama || r.nama_lengkap),
          jenis_kelamin: String(r.jenis_kelamin || 'L') as 'L' | 'P',
          kelas: String(r.kelas || 'VII-A'),
          tempat_lahir: r.tempat_lahir ? String(r.tempat_lahir) : '',
          tanggal_lahir: r.tanggal_lahir ? String(r.tanggal_lahir) : '',
          alamat: r.alamat ? String(r.alamat) : '',
          nama_ortu: r.nama_ortu ? String(r.nama_ortu) : '',
          telepon_ortu: r.telepon_ortu ? String(r.telepon_ortu) : '',
          status: (r.status ? String(r.status) : 'Aktif') as 'Aktif' | 'Lulus' | 'Pindah'
        }));
      }
    } catch (err) {
      console.warn('[Turso] Error query siswa, falling back to JSON:', err);
    }
  }
  return readLocalJson<StudentItem[]>('students.json', []);
}

export async function getStudentAttendance(): Promise<StudentAttendanceItem[]> {
  if (turso) {
    try {
      const res = await turso.execute("SELECT * FROM absen_siswa ORDER BY tanggal DESC");
      if (res.rows && res.rows.length > 0) {
        return res.rows.map(r => ({
          id: String(r.id),
          tanggal: String(r.tanggal),
          kelas: String(r.kelas),
          total_siswa: Number(r.total_siswa || 0),
          hadir: Number(r.hadir || 0),
          sakit: Number(r.sakit || 0),
          izin: Number(r.izin || 0),
          alpa: Number(r.alpa || 0),
          records: r.records ? JSON.parse(String(r.records)) : [],
          created_at: r.created_at ? String(r.created_at) : ''
        }));
      }
    } catch (err) {
      console.warn('[Turso] Error query absen_siswa, falling back to JSON:', err);
    }
  }
  return readLocalJson<StudentAttendanceItem[]>('attendance_students.json', []);
}

export async function getTeacherAttendance(): Promise<TeacherAttendanceItem[]> {
  if (turso) {
    try {
      const res = await turso.execute("SELECT * FROM absen_guru ORDER BY tanggal DESC");
      if (res.rows && res.rows.length > 0) {
        return res.rows.map(r => ({
          id: String(r.id),
          tanggal: String(r.tanggal),
          total_guru: Number(r.total_guru || 0),
          hadir: Number(r.hadir || 0),
          sakit: Number(r.sakit || 0),
          izin: Number(r.izin || 0),
          dinas_luar: Number(r.dinas_luar || 0),
          records: r.records ? JSON.parse(String(r.records)) : [],
          created_at: r.created_at ? String(r.created_at) : ''
        }));
      }
    } catch (err) {
      console.warn('[Turso] Error query absen_guru, falling back to JSON:', err);
    }
  }
  return readLocalJson<TeacherAttendanceItem[]>('attendance_teachers.json', []);
}
