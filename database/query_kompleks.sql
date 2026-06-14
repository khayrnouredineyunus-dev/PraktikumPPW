-- ============================================================
--  query_kompleks.sql
--  MiniFut — Objek Database untuk UAS Basis Data
--  Jalankan file ini SETELAH minifut_db.sql sudah diimport
--
--  Isi file:
--  [1] Tabel bantu LogAktivitas (untuk Trigger)
--  [2] 3 VIEW
--  [3] 2 FUNCTION
--  [4] 2 PROCEDURE
--  [5] 3 TRIGGER
--  [6] Query Kompleks (termasuk yang digunakan di website)
--  [7] Demo/Uji  eew  
-- ============================================================

USE minifut_db;

-- ============================================================
-- [1] TABEL BANTU: LogAktivitas
-- Digunakan oleh Trigger untuk mencatat setiap perubahan data
-- ============================================================

CREATE TABLE IF NOT EXISTS `LogAktivitas` (
  `ID_LOG`       INT          NOT NULL AUTO_INCREMENT,
  `TABEL_ASAL`   VARCHAR(50)  NOT NULL COMMENT 'Nama tabel sumber perubahan',
  `AKSI`         VARCHAR(20)  NOT NULL COMMENT 'INSERT / UPDATE / DELETE',
  `ID_REFERENSI` VARCHAR(50)  NOT NULL COMMENT 'ID baris yang berubah',
  `KETERANGAN`   TEXT         DEFAULT NULL,
  `WAKTU`        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_LOG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- [2] VIEW (3 view)
-- ============================================================

-- VIEW 1: Ringkasan booking lengkap
-- Menggabungkan booking + pelanggan + jadwal + lapangan + pembayaran
DROP VIEW IF EXISTS `v_booking_lengkap`;
CREATE VIEW `v_booking_lengkap` AS
SELECT
    b.ID_BOOKING,
    b.TANGGAL_BOOKING,
    b.STATUS_BOOKING,
    b.TEAM_NAME,
    b.NOTES,
    p.U_NAMA             AS NAMA_PELANGGAN,
    p.U_EMAIL            AS EMAIL_PELANGGAN,
    p.U_NOTELP,
    l.NAMA_LAPANGAN,
    l.JENIS_LAPANGAN,
    l.HARGA_PER_JAM,
    j.TANGGAL            AS TANGGAL_MAIN,
    j.JAM_MULAI,
    j.JAM_SELESAI,
    j.STATUS_JADWAL,
    pm.STATUS_PEMBAYARAN,
    pm.METODE_PEMBAYARAN,
    pm.TANGGAL_BAYAR
FROM booking b
JOIN pelanggan    p  ON b.ID_PELANGGAN = p.ID_PELANGGAN
JOIN jadwal       j  ON b.ID_JADWAL    = j.ID_JADWAL
JOIN lapangan     l  ON j.ID_LAPANGAN  = l.ID_LAPANGAN
LEFT JOIN pembayaran pm ON b.ID_BOOKING = pm.ID_BOOKING;


-- VIEW 2: Statistik per pelanggan
-- Menampilkan jumlah booking, booking lunas, pending, batal, dan waktu booking terakhir
DROP VIEW IF EXISTS `v_statistik_pelanggan`;
CREATE VIEW `v_statistik_pelanggan` AS
SELECT
    p.ID_PELANGGAN,
    p.U_NAMA,
    p.U_EMAIL,
    p.U_NOTELP,
    COUNT(b.ID_BOOKING)                                        AS TOTAL_BOOKING,
    COUNT(CASE WHEN b.STATUS_BOOKING = 'LUNAS'   THEN 1 END)  AS BOOKING_LUNAS,
    COUNT(CASE WHEN b.STATUS_BOOKING = 'DP'      THEN 1 END)  AS BOOKING_DP,
    COUNT(CASE WHEN b.STATUS_BOOKING = 'PENDING' THEN 1 END)  AS BOOKING_PENDING,
    COUNT(CASE WHEN b.STATUS_BOOKING = 'BATAL'   THEN 1 END)  AS BOOKING_BATAL,
    MAX(b.TANGGAL_BOOKING)                                     AS BOOKING_TERAKHIR
FROM pelanggan p
LEFT JOIN booking b ON p.ID_PELANGGAN = b.ID_PELANGGAN
GROUP BY p.ID_PELANGGAN, p.U_NAMA, p.U_EMAIL, p.U_NOTELP;


-- VIEW 3: Ketersediaan lapangan hari ini
-- Menampilkan slot tersedia dan terisi per lapangan untuk tanggal hari ini
DROP VIEW IF EXISTS `v_ketersediaan_lapangan`;
CREATE VIEW `v_ketersediaan_lapangan` AS
SELECT
    l.ID_LAPANGAN,
    l.NAMA_LAPANGAN,
    l.JENIS_LAPANGAN,
    l.HARGA_PER_JAM,
    l.STATUS_LAPANGAN,
    COUNT(j.ID_JADWAL)                                        AS TOTAL_SLOT_HARI_INI,
    COUNT(CASE WHEN j.STATUS_JADWAL = 'YA'    THEN 1 END)    AS SLOT_TERSEDIA,
    COUNT(CASE WHEN j.STATUS_JADWAL = 'TIDAK' THEN 1 END)    AS SLOT_TERISI
FROM lapangan l
LEFT JOIN jadwal j ON l.ID_LAPANGAN = j.ID_LAPANGAN
                  AND j.TANGGAL = CURDATE()
GROUP BY l.ID_LAPANGAN, l.NAMA_LAPANGAN, l.JENIS_LAPANGAN,
         l.HARGA_PER_JAM, l.STATUS_LAPANGAN;


-- ============================================================
-- [3] FUNCTION (2 function)
-- ============================================================

DELIMITER $$

-- FUNCTION 1: Hitung total booking LUNAS milik satu pelanggan
-- Penggunaan: SELECT fn_total_booking_lunas(1);
DROP FUNCTION IF EXISTS `fn_total_booking_lunas`$$
CREATE FUNCTION `fn_total_booking_lunas`(p_id_pelanggan INT)
RETURNS INT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_total INT DEFAULT 0;
    SELECT COUNT(*)
    INTO   v_total
    FROM   booking
    WHERE  ID_PELANGGAN   = p_id_pelanggan
      AND  STATUS_BOOKING = 'LUNAS';
    RETURN v_total;
END$$


-- FUNCTION 2: Format angka menjadi string Rupiah
-- Penggunaan: SELECT fn_format_rupiah(1000000);  → 'Rp 1.000.000'
DROP FUNCTION IF EXISTS `fn_format_rupiah`$$
CREATE FUNCTION `fn_format_rupiah`(p_amount INT)
RETURNS VARCHAR(60)
NO SQL
DETERMINISTIC
BEGIN
    RETURN CONCAT('Rp ', FORMAT(p_amount, 0));
END$$

DELIMITER ;


-- ============================================================
-- [4] PROCEDURE (2 procedure)
-- ============================================================

DELIMITER $$

-- PROCEDURE 1: Laporan booking per lapangan dalam rentang tanggal
-- Penggunaan: CALL sp_laporan_booking('2026-01-01', '2026-12-31');
DROP PROCEDURE IF EXISTS `sp_laporan_booking`$$
CREATE PROCEDURE `sp_laporan_booking`(
    IN p_tgl_mulai DATE,
    IN p_tgl_akhir DATE
)
BEGIN
    SELECT
        l.ID_LAPANGAN,
        l.NAMA_LAPANGAN,
        l.JENIS_LAPANGAN,
        fn_format_rupiah(l.HARGA_PER_JAM)              AS HARGA_PER_JAM,
        COUNT(DISTINCT b.ID_BOOKING)                    AS TOTAL_BOOKING,
        COUNT(CASE WHEN b.STATUS_BOOKING = 'LUNAS'
                   THEN 1 END)                          AS LUNAS,
        COUNT(CASE WHEN b.STATUS_BOOKING IN ('DP','PENDING')
                   THEN 1 END)                          AS BELUM_LUNAS,
        COUNT(CASE WHEN b.STATUS_BOOKING = 'BATAL'
                   THEN 1 END)                          AS BATAL
    FROM lapangan l
    LEFT JOIN jadwal  j ON l.ID_LAPANGAN = j.ID_LAPANGAN
                       AND j.TANGGAL BETWEEN p_tgl_mulai AND p_tgl_akhir
    LEFT JOIN booking b ON j.ID_JADWAL   = b.ID_JADWAL
    GROUP BY l.ID_LAPANGAN, l.NAMA_LAPANGAN, l.JENIS_LAPANGAN, l.HARGA_PER_JAM
    ORDER BY TOTAL_BOOKING DESC;
END$$


-- PROCEDURE 2: Batalkan booking PENDING yang sudah melewati 24 jam
-- Penggunaan: CALL sp_batal_booking_kadaluarsa();
DROP PROCEDURE IF EXISTS `sp_batal_booking_kadaluarsa`$$
CREATE PROCEDURE `sp_batal_booking_kadaluarsa`()
BEGIN
    DECLARE v_jumlah INT DEFAULT 0;

    SELECT COUNT(*) INTO v_jumlah
    FROM   booking
    WHERE  STATUS_BOOKING = 'PENDING'
      AND  TANGGAL_BOOKING < NOW() - INTERVAL 24 HOUR;

    UPDATE booking
    SET    STATUS_BOOKING = 'BATAL'
    WHERE  STATUS_BOOKING = 'PENDING'
      AND  TANGGAL_BOOKING < NOW() - INTERVAL 24 HOUR;

    SELECT CONCAT(v_jumlah, ' booking PENDING dibatalkan karena melewati batas 24 jam.')
        AS HASIL_EKSEKUSI;
END$$

DELIMITER ;


-- ============================================================
-- [5] TRIGGER (3 trigger)
-- ============================================================

DELIMITER $$

-- TRIGGER 1: AFTER INSERT pada booking
-- Otomatis ubah STATUS_JADWAL = 'TIDAK' saat ada booking baru
DROP TRIGGER IF EXISTS `trg_booking_setelah_insert`$$
CREATE TRIGGER `trg_booking_setelah_insert`
AFTER INSERT ON `booking`
FOR EACH ROW
BEGIN
    -- Tandai slot jadwal sebagai sudah terisi
    UPDATE jadwal
    SET    STATUS_JADWAL = 'TIDAK'
    WHERE  ID_JADWAL = NEW.ID_JADWAL;

    -- Catat ke log aktivitas
    INSERT INTO LogAktivitas (TABEL_ASAL, AKSI, ID_REFERENSI, KETERANGAN)
    VALUES (
        'booking',
        'INSERT',
        NEW.ID_BOOKING,
        CONCAT(
            'Booking baru masuk. Pelanggan ID=', IFNULL(NEW.ID_PELANGGAN,'?'),
            ', Jadwal ID=', IFNULL(NEW.ID_JADWAL,'?'),
            ', Status=', IFNULL(NEW.STATUS_BOOKING,'?'),
            IFNULL(CONCAT(', Tim: ', NEW.TEAM_NAME), '')
        )
    );
END$$


-- TRIGGER 2: AFTER UPDATE pada booking
-- Jika booking dibatalkan → kembalikan slot jadwal menjadi 'YA' (tersedia)
-- Catat setiap perubahan status ke log
DROP TRIGGER IF EXISTS `trg_booking_setelah_update`$$
CREATE TRIGGER `trg_booking_setelah_update`
AFTER UPDATE ON `booking`
FOR EACH ROW
BEGIN
    -- Kembalikan jadwal ke tersedia jika booking dibatalkan
    IF NEW.STATUS_BOOKING = 'BATAL' AND OLD.STATUS_BOOKING <> 'BATAL' THEN
        UPDATE jadwal
        SET    STATUS_JADWAL = 'YA'
        WHERE  ID_JADWAL = NEW.ID_JADWAL;
    END IF;

    -- Catat perubahan status ke log
    IF NEW.STATUS_BOOKING <> OLD.STATUS_BOOKING THEN
        INSERT INTO LogAktivitas (TABEL_ASAL, AKSI, ID_REFERENSI, KETERANGAN)
        VALUES (
            'booking',
            'UPDATE',
            NEW.ID_BOOKING,
            CONCAT(
                'Status berubah: [', OLD.STATUS_BOOKING,
                '] → [', NEW.STATUS_BOOKING, ']'
            )
        );
    END IF;
END$$


-- TRIGGER 3: AFTER UPDATE pada pembayaran
-- Saat status pembayaran diverifikasi (LUNAS) → ubah STATUS_BOOKING menjadi LUNAS
DROP TRIGGER IF EXISTS `trg_pembayaran_setelah_update`$$
CREATE TRIGGER `trg_pembayaran_setelah_update`
AFTER UPDATE ON `pembayaran`
FOR EACH ROW
BEGIN
    IF NEW.STATUS_PEMBAYARAN = 'LUNAS' AND OLD.STATUS_PEMBAYARAN <> 'LUNAS' THEN
        UPDATE booking
        SET    STATUS_BOOKING = 'LUNAS'
        WHERE  ID_BOOKING = NEW.ID_BOOKING;

        INSERT INTO LogAktivitas (TABEL_ASAL, AKSI, ID_REFERENSI, KETERANGAN)
        VALUES (
            'pembayaran',
            'UPDATE',
            NEW.ID_BOOKING,
            CONCAT(
                'Pembayaran dikonfirmasi LUNAS. Metode: ',
                IFNULL(NEW.METODE_PEMBAYARAN, '-'),
                ', Tanggal: ', IFNULL(NEW.TANGGAL_BAYAR, '-')
            )
        );
    END IF;
END$$

DELIMITER ;


-- ============================================================
-- [6] QUERY KOMPLEKS
-- ============================================================

-- ════════════════════════════════════════════════════════════
-- A. QUERY YANG DIGUNAKAN DI WEBSITE (profile.php & admin)
-- ════════════════════════════════════════════════════════════

-- ── Web Query A1: Riwayat Booking Pelanggan (profile.php) ──
-- Query ini digunakan di halaman profil pelanggan untuk
-- menampilkan seluruh riwayat booking milik pelanggan yang login.
-- JOIN: booking → jadwal → lapangan → pembayaran (LEFT JOIN)
SELECT
    b.ID_BOOKING,
    b.TANGGAL_BOOKING,
    b.STATUS_BOOKING,
    l.NAMA_LAPANGAN,
    l.HARGA_PER_JAM,
    j.TANGGAL              AS TGL_MAIN,
    j.JAM_MULAI,
    pm.STATUS_PEMBAYARAN,
    pm.METODE_PEMBAYARAN
FROM booking b
JOIN jadwal      j  ON b.ID_JADWAL    = j.ID_JADWAL
JOIN lapangan    l  ON j.ID_LAPANGAN  = l.ID_LAPANGAN
LEFT JOIN pembayaran pm ON b.ID_BOOKING = pm.ID_BOOKING
WHERE b.ID_PELANGGAN = 1   -- ganti dengan ID pelanggan yang diinginkan
ORDER BY b.TANGGAL_BOOKING DESC;


-- ── Web Query A2: Dashboard Admin (admin/dashboard.php) ────
-- Menampilkan 8 booking terbaru beserta nama pelanggan & lapangan.
-- JOIN: booking → pelanggan, jadwal → lapangan
SELECT
    b.ID_BOOKING,
    p.U_NAMA,
    p.U_EMAIL,
    l.NAMA_LAPANGAN,
    j.TANGGAL,
    j.JAM_MULAI,
    b.STATUS_BOOKING,
    b.TANGGAL_BOOKING
FROM booking b
JOIN pelanggan p ON b.ID_PELANGGAN = p.ID_PELANGGAN
JOIN jadwal    j ON b.ID_JADWAL    = j.ID_JADWAL
JOIN lapangan  l ON j.ID_LAPANGAN  = l.ID_LAPANGAN
ORDER BY b.TANGGAL_BOOKING DESC
LIMIT 8;


-- ── Web Query A3: Manajemen Pembayaran (admin/pembayaran.php)
-- Menampilkan seluruh data pembayaran lengkap dengan informasi
-- pelanggan dan lapangan yang dipesan.
-- JOIN: pembayaran → booking → pelanggan → jadwal → lapangan
SELECT
    pm.ID_PEMBAYARAN,
    pm.ID_BOOKING,
    pm.METODE_PEMBAYARAN,
    pm.STATUS_PEMBAYARAN,
    pm.TANGGAL_BAYAR,
    p.U_NAMA               AS NAMA_PELANGGAN,
    p.U_EMAIL,
    l.NAMA_LAPANGAN,
    j.TANGGAL              AS TANGGAL_MAIN,
    j.JAM_MULAI,
    j.JAM_SELESAI
FROM pembayaran pm
JOIN booking   b  ON pm.ID_BOOKING   = b.ID_BOOKING
JOIN pelanggan p  ON b.ID_PELANGGAN  = p.ID_PELANGGAN
JOIN jadwal    j  ON b.ID_JADWAL     = j.ID_JADWAL
JOIN lapangan  l  ON j.ID_LAPANGAN   = l.ID_LAPANGAN
ORDER BY pm.TANGGAL_BAYAR DESC;


-- ════════════════════════════════════════════════════════════
-- B. QUERY TAMBAHAN (untuk syarat UAS Basis Data)
-- ════════════════════════════════════════════════════════════

-- ── Query B1: JOIN 5 Tabel — Laporan Booking Lengkap ───────
-- Menampilkan semua data booking dengan detail harga format Rupiah
SELECT
    b.ID_BOOKING,
    p.U_NAMA                                 AS Nama_Pelanggan,
    p.U_EMAIL                                AS Email,
    l.NAMA_LAPANGAN,
    fn_format_rupiah(l.HARGA_PER_JAM)        AS Harga_Per_Jam,
    j.TANGGAL                                AS Tanggal_Main,
    j.JAM_MULAI,
    j.JAM_SELESAI,
    b.STATUS_BOOKING,
    b.TEAM_NAME,
    pm.STATUS_PEMBAYARAN,
    pm.METODE_PEMBAYARAN,
    pm.TANGGAL_BAYAR
FROM booking b
JOIN pelanggan   p  ON b.ID_PELANGGAN = p.ID_PELANGGAN
JOIN jadwal      j  ON b.ID_JADWAL    = j.ID_JADWAL
JOIN lapangan    l  ON j.ID_LAPANGAN  = l.ID_LAPANGAN
LEFT JOIN pembayaran pm ON b.ID_BOOKING = pm.ID_BOOKING
ORDER BY b.TANGGAL_BOOKING DESC;


-- ── Query B2: Subquery — Pelanggan Paling Aktif ────────────
-- Pelanggan yang memiliki lebih dari 1 booking aktif,
-- dihitung menggunakan subquery di WHERE dan SELECT
SELECT
    p.ID_PELANGGAN,
    p.U_NAMA,
    p.U_EMAIL,
    fn_total_booking_lunas(p.ID_PELANGGAN)  AS TOTAL_LUNAS,
    (
        SELECT COUNT(*)
        FROM   booking b2
        WHERE  b2.ID_PELANGGAN  = p.ID_PELANGGAN
          AND  b2.STATUS_BOOKING <> 'BATAL'
    )                                        AS TOTAL_BOOKING_AKTIF
FROM pelanggan p
WHERE (
    SELECT COUNT(*)
    FROM   booking b3
    WHERE  b3.ID_PELANGGAN  = p.ID_PELANGGAN
      AND  b3.STATUS_BOOKING <> 'BATAL'
) > 1
ORDER BY TOTAL_BOOKING_AKTIF DESC;


-- ── Query B3: GROUP BY + HAVING — Lapangan Tersibuk ────────
-- Lapangan dengan lebih dari 2 booking aktif beserta statistiknya
SELECT
    l.NAMA_LAPANGAN,
    l.JENIS_LAPANGAN,
    fn_format_rupiah(l.HARGA_PER_JAM)  AS Harga_Per_Jam,
    COUNT(b.ID_BOOKING)                 AS TOTAL_BOOKING,
    COUNT(CASE WHEN b.STATUS_BOOKING = 'LUNAS'            THEN 1 END) AS LUNAS,
    COUNT(CASE WHEN b.STATUS_BOOKING IN ('DP','PENDING')  THEN 1 END) AS BELUM_LUNAS,
    MAX(j.TANGGAL)                      AS BOOKING_TERAKHIR
FROM lapangan l
JOIN jadwal  j ON l.ID_LAPANGAN = j.ID_LAPANGAN
JOIN booking b ON j.ID_JADWAL   = b.ID_JADWAL
WHERE b.STATUS_BOOKING <> 'BATAL'
GROUP BY l.ID_LAPANGAN, l.NAMA_LAPANGAN, l.JENIS_LAPANGAN, l.HARGA_PER_JAM
HAVING COUNT(b.ID_BOOKING) > 2
ORDER BY TOTAL_BOOKING DESC;


-- ── Query B4: UNION — Gabung Data Sistem Baru & Lama ───────
-- Menggabungkan data booking dari sistem baru (tabel booking)
-- dan sistem lama (tabel bookings_lama) dalam satu hasil query
SELECT
    b.ID_BOOKING      AS Kode_Booking,
    p.U_NAMA          AS Nama_Pelanggan,
    p.U_EMAIL         AS Email,
    l.NAMA_LAPANGAN   AS Lapangan,
    j.TANGGAL         AS Tanggal_Main,
    j.JAM_MULAI       AS Jam_Mulai,
    b.STATUS_BOOKING  AS Status,
    'SISTEM BARU'     AS Sumber
FROM booking b
JOIN pelanggan p ON b.ID_PELANGGAN = p.ID_PELANGGAN
JOIN jadwal    j ON b.ID_JADWAL    = j.ID_JADWAL
JOIN lapangan  l ON j.ID_LAPANGAN  = l.ID_LAPANGAN

UNION ALL

SELECT
    bl.booking_code          AS Kode_Booking,
    bl.customer_name         AS Nama_Pelanggan,
    bl.customer_email        AS Email,
    CONCAT('Field #', bl.field_id) AS Lapangan,
    bl.book_date             AS Tanggal_Main,
    bl.time_slots            AS Jam_Mulai,
    bl.pay_type              AS Status,
    'SISTEM LAMA'            AS Sumber
FROM bookings_lama bl
ORDER BY Tanggal_Main DESC;


-- ============================================================
-- [7] UJI / DEMO
-- ============================================================

-- Cek View 1
SELECT * FROM v_booking_lengkap;

-- Cek View 2
SELECT * FROM v_statistik_pelanggan ORDER BY TOTAL_BOOKING DESC;

-- Cek View 3
SELECT * FROM v_ketersediaan_lapangan;

-- Cek Function 1
SELECT fn_total_booking_lunas(1) AS Booking_Lunas_Pelanggan_1;

-- Cek Function 2
SELECT fn_format_rupiah(1000000) AS Contoh_Format;

-- Cek Procedure 1
CALL sp_laporan_booking('2026-01-01', '2026-12-31');

-- Cek Procedure 2
CALL sp_batal_booking_kadaluarsa();

-- Cek Log Trigger
SELECT * FROM LogAktivitas ORDER BY WAKTU DESC;

-- ============================================================
-- SELESAI — query_kompleks.sql
-- ============================================================
