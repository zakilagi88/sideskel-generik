
CREATE TABLE apbdes_kategoris (
  id bigint unsigned AUTO_INCREMENT,
  nama_kategori varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id)
);

-- kelurahan.apbdes_subkategoris
CREATE TABLE apbdes_subkategoris (
  id bigint unsigned AUTO_INCREMENT,
  kategori_id bigint unsigned,
  nama_subkategori varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id)
);
-- kelurahan.apbdes
CREATE TABLE apbdes (
  id bigint unsigned AUTO_INCREMENT,
  tahun year ,
  komponen varchar(255)  ,
  kategori_id bigint unsigned ,
  subkategori_id bigint unsigned ,
  nilai decimal(15,2) ,
  realisasi decimal(15,2) ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id),
);

-- kelurahan.apbdes_kategoris


-- kelurahan.audits

-- kelurahan.authentication_log
CREATE TABLE authentication_log (
  id bigint unsigned AUTO_INCREMENT,
  authenticatable_type varchar(255)  ,
  authenticatable_id bigint unsigned ,
  ip_address varchar(45)  ,
  user_agent text ,
  login_at timestamp  ,
  login_successful tinyint(1)  DEFAULT '0',
  logout_at timestamp  ,
  cleared_by_user tinyint(1)  DEFAULT '0',
  location json ,
  PRIMARY KEY (id),
);

-- kelurahan.bantuanables
CREATE TABLE bantuanables (
  bantuan_id bigint unsigned ,
  bantuanable_id varchar(255)  ,
  bantuanable_type varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  UNIQUE KEY (bantuan_id,bantuanable_id,bantuanable_type),
);

-- kelurahan.bantuans
CREATE TABLE bantuans (
  bantuan_id bigint unsigned AUTO_INCREMENT,
  bantuan_program varchar(255)  ,
  bantuan_sasaran varchar(255)  ,
  bantuan_keterangan longtext  ,
  bantuan_tgl_mulai date ,
  bantuan_tgl_selesai date ,
  bantuan_status tinyint(1)  DEFAULT '0',
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (bantuan_id)
);

-- kelurahan.berita
CREATE TABLE berita (
  berita_id bigint unsigned AUTO_INCREMENT,
  user_id bigint unsigned ,
  kategori_berita_id bigint unsigned ,
  title varchar(255)  ,
  slug varchar(255)  ,
  gambar varchar(255)  ,
  body longtext ,
  meta_description varchar(255)  ,
  meta_tags varchar(255)  ,
  scheduled_for datetime ,
  published_at datetime ,
  status varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (berita_id),
);

-- kelurahan.desa_kelurahan
CREATE TABLE desa_kelurahan (
  deskel_id varchar(10)  ,
  deskel_nama varchar(255)  ,
  kec_id varchar(6)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (deskel_id),
);

-- kelurahan.deskel_profils
CREATE TABLE deskel_profils (
  id bigint unsigned AUTO_INCREMENT,
  deskel_id varchar(10)  ,
  struktur varchar(255)  ,
  alamat varchar(255)  ,
  kodepos varchar(255)  ,
  luaswilayah double ,
  jmlh_pdd int ,
  bts_utara varchar(255)  ,
  bts_timur varchar(255)  ,
  bts_selatan varchar(255)  ,
  bts_barat varchar(255)  ,
  visi longtext ,
  misi longtext ,
  sejarah longtext ,
  gambar varchar(255)  ,
  logo varchar(255)  ,
  telepon varchar(255)  ,
  email varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id),
);

-- kelurahan.dusun
CREATE TABLE dusun (
  dusun_id bigint unsigned AUTO_INCREMENT,
  dusun_nama varchar(255)  ,
  deskel_id varchar(10)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (dusun_id),
);

-- kelurahan.kab_kota
CREATE TABLE kab_kota (
  kabkota_id varchar(4)  ,
  prov_id varchar(2)  ,
  kabkota_nama varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (kabkota_id),
);

-- kelurahan.kartu_keluarga
CREATE TABLE kartu_keluarga (
  kk_id varchar(16)  ,
  kk_alamat varchar(255)  ,
  deskel_id varchar(10)  ,
  dusun_id bigint unsigned ,
  rw_id bigint unsigned ,
  rt_id bigint unsigned ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (kk_id),
);

-- kelurahan.kategori_berita
CREATE TABLE kategori_berita (
  kategori_berita_id bigint unsigned AUTO_INCREMENT,
  name varchar(255)  ,
  slug varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (kategori_berita_id)
);

-- kelurahan.kategori_stuntings
CREATE TABLE kategori_stuntings (
  id bigint unsigned AUTO_INCREMENT,
  indeks varchar(255)  ,
  keterangan varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id)
);

-- kelurahan.kecamatan
CREATE TABLE kecamatan (
  kec_id varchar(6)  ,
  kec_nama varchar(255)  ,
  kabkota_id varchar(4)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (kec_id),
);

-- kelurahan.kelahirans
CREATE TABLE kelahirans (
  id bigint unsigned AUTO_INCREMENT,
  nik varchar(16)  ,
  anak_ke varchar(255)  ,
  tempat_lahir varchar(255)  ,
  jenis_lahir varchar(255)  ,
  penolong_lahir varchar(255)  ,
  berat_lahir varchar(255)  ,
  panjang_lahir varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id),
);

-- kelurahan.kematians
CREATE TABLE kematians (
  id bigint unsigned AUTO_INCREMENT,
  nik varchar(16)  ,
  waktu_kematian time ,
  tempat_kematian varchar(255)  ,
  penyebab_kematian varchar(255)  ,
  menerangkan_kematian varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id),
);

-- kelurahan.kepala_wilayah
CREATE TABLE kepala_wilayah (
  id bigint unsigned AUTO_INCREMENT,
  kepala_nik varchar(16)  ,
  kepala_type varchar(255)  ,
  kepala_id bigint unsigned ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id),
);

-- kelurahan.kepindahans
CREATE TABLE kepindahans (
  id bigint unsigned AUTO_INCREMENT,
  nik varchar(16)  ,
  tujuan_pindah varchar(255)  ,
  alamat_pindah varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id),
);

-- kelurahan.kesehatans
CREATE TABLE kesehatans (
  kes_id bigint unsigned AUTO_INCREMENT,
  kes_cacat_mental_fisik varchar(255)  ,
  kes_penyakit_menahun varchar(255)  ,
  kes_penyakit_lain varchar(255)  ,
  kes_akseptor_kb varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (kes_id)
);

-- kelurahan.kesehatan_anaks
CREATE TABLE kesehatan_anaks (
  id bigint unsigned AUTO_INCREMENT,
  kategori_id bigint unsigned ,
  subkategori_id bigint unsigned ,
  anak_id varchar(16)  ,
  ibu_id varchar(16)  ,
  berat_badan double(8,2) ,
  tinggi_badan double(8,2) ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id),
);

-- kelurahan.laporans
CREATE TABLE laporans (
  id bigint unsigned AUTO_INCREMENT,
  Perincian varchar(255)  ,
  Jumlah_Penduduk varchar(255)  ,
  Jumlah_Keluarga varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id)
);

-- kelurahan.pendatangs
CREATE TABLE pendatangs (
  id bigint unsigned AUTO_INCREMENT,
  nik varchar(16)  ,
  alamat_sebelumnya text ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id),
);

-- kelurahan.penduduk
CREATE TABLE penduduk (
  nik varchar(16)  ,
  kk_id varchar(16)  ,
  foto varchar(255)  ,
  nama_lengkap varchar(255)  ,
  jenis_kelamin varchar(255)  ,
  tempat_lahir varchar(255)  ,
  tanggal_lahir date ,
  agama varchar(255)  ,
  pendidikan varchar(255)  ,
  pekerjaan varchar(255)  ,
  status_perkawinan varchar(255)  ,
  tgl_perkawinan date ,
  tgl_perceraian date ,
  kewarganegaraan varchar(255)   DEFAULT 'WNI',
  nama_ayah varchar(255)  ,
  nama_ibu varchar(255)  ,
  nik_ayah varchar(16)  ,
  nik_ibu varchar(16)  ,
  golongan_darah varchar(255)  ,
  etnis_suku varchar(255)  ,
  cacat varchar(255)  ,
  penyakit varchar(255)  ,
  akseptor_kb varchar(255)  ,
  status_penduduk varchar(255)   DEFAULT 'Tetap',
  status_dasar varchar(255)   DEFAULT 'HIDUP',
  status_pengajuan varchar(255)   DEFAULT 'BELUM DIVERIFIKASI',
  status_tempat_tinggal varchar(255)  ,
  alamat_sekarang varchar(255)  ,
  alamat_sebelumnya varchar(255)  ,
  alamatKK tinyint(1) DEFAULT '1',
  telepon varchar(255)  ,
  email varchar(255)  ,
  status_hubungan varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (nik),
);

-- kelurahan.peristiwas
CREATE TABLE peristiwas (
  id bigint unsigned AUTO_INCREMENT,
  nik varchar(16)  ,
  peristiwa_type varchar(255)  ,
  peristiwa_id bigint unsigned ,
  jenis_peristiwa varchar(255)  ,
  catatan_peristiwa varchar(255)  ,
  tanggal_peristiwa date ,
  tanggal_lapor date ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id),
);

-- kelurahan.permissions
CREATE TABLE permissions (
  id bigint unsigned AUTO_INCREMENT,
  name varchar(255)  ,
  guard_name varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id),
);

-- kelurahan.provinsi
CREATE TABLE provinsi (
  prov_id varchar(2)  ,
  prov_nama varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (prov_id)
);

-- kelurahan.roles
CREATE TABLE roles (
  id bigint unsigned AUTO_INCREMENT,
  name varchar(255)  ,
  guard_name varchar(255)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id),
  UNIQUE KEY (name,guard_name)
) ;

-- kelurahan.rukun_tetangga
CREATE TABLE rukun_tetangga (
  rt_id bigint unsigned AUTO_INCREMENT,
  rt_nama varchar(255)  ,
  rw_id bigint unsigned ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (rt_id),
);

-- kelurahan.rukun_warga
CREATE TABLE rukun_warga (
  rw_id bigint unsigned AUTO_INCREMENT,
  rw_nama varchar(255)  ,
  deskel_id varchar(10)  ,
  dusun_id bigint unsigned ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (rw_id),
);

-- kelurahan.subkategori_stuntings
CREATE TABLE subkategori_stuntings (
  id bigint unsigned AUTO_INCREMENT,
  subkategori_nama varchar(255)  ,
  subkategori_batas_bawah double(8,2) ,
  subkategori_batas_atas double(8,2) ,
  kategori_id bigint unsigned ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id),
);

-- kelurahan.taggables
CREATE TABLE taggables (
  tag_id bigint unsigned ,
  taggable_type varchar(255) ,
  taggable_id bigint unsigned ,
  UNIQUE KEY (tag_id,taggable_id,taggable_type),
);

-- kelurahan.tags
CREATE TABLE tags (
  id bigint unsigned AUTO_INCREMENT,
  name json ,
  slug json ,
  type varchar(255)  ,
  order_column int ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id)
);

-- kelurahan.users
CREATE TABLE users (
  id bigint unsigned AUTO_INCREMENT,
  nik varchar(16)  ,
  name varchar(255)  ,
  username varchar(255)  ,
  email varchar(255)  ,
  password varchar(255)  ,
  avatar_url varchar(255)  ,
  settings json ,
  wilayah_type varchar(255)  ,
  wilayah_id bigint unsigned ,
  email_verified_at timestamp  ,
  remember_token varchar(100)  ,
  created_at timestamp  ,
  updated_at timestamp  ,
  PRIMARY KEY (id),
  UNIQUE KEY (username),
  UNIQUE KEY (email),
);

