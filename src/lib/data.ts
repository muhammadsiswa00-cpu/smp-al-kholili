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
  ppdb_url: "https://ppdb.smpislam-alkholili.sch.id",
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
          ppdb_url: String(row.ppdb_url || defaultSettings.ppdb_url),
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
      const res = await turso.execute("SELECT id, nama, jabatan, mapel, pendidikan, foto, urutan FROM guru ORDER BY urutan ASC, id ASC");
      if (res.rows && res.rows.length > 0) {
        return res.rows.map(r => ({
          id: Number(r.id),
          nama: String(r.nama),
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
