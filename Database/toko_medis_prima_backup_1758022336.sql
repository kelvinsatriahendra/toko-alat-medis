

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO admin VALUES("1","admin","$2y$10$AIy0X1Ep6alaHDTofiChGeqq7k/d1Kc8vKQf1JZo0mKrzkkj6M626");



CREATE TABLE `cart` (
  `id_cart` int(11) NOT NULL AUTO_INCREMENT,
  `kode_customer` varchar(100) NOT NULL,
  `kode_produk` varchar(100) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  PRIMARY KEY (`id_cart`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO cart VALUES("16","C0003","P0002","Tensimeter Digital","1","350000");
INSERT INTO cart VALUES("17","C0003","P0003","Paket P3K Lengkap","2","150000");
INSERT INTO cart VALUES("28","C0007","P0003","Paket P3K Lengkap","5","150000");
INSERT INTO cart VALUES("29","C0007","P0001","Kursi Roda Standar","1","750000");
INSERT INTO cart VALUES("30","C0007","P0002","Tensimeter Digital","2","350000");



CREATE TABLE `customer` (
  `kode_customer` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `no_telp` varchar(100) NOT NULL,
  PRIMARY KEY (`kode_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO customer VALUES("C0001","Kinan Larisa","kinan.larisa@gmail.com","kinan","$2y$10$/UjGYbisTPJhr8MgmT37qOXo1o/HJn3dhafPoSYbOlSN1E7olHIb.","085789231098");
INSERT INTO customer VALUES("C0002","Suci Indahsari","indah.sarisuci@gmail.com","indah","$2y$10$47./qEeA/y3rNx3UkoKmkuxoAtmz4ebHSR0t0Bc.cFEEg7cK34M3C","088929431765");
INSERT INTO customer VALUES("C0003","Abdi Sinal","ab_sinal@gmail.com","abdi","$2y$10$6wHH.7rF1q3JtzKgAhNFy.4URchgJC8R.POT1osTAWmasDXTTO7ZG","082238776789");
INSERT INTO customer VALUES("C0006","hendra","hendramarta304@gmail.com","hendra marta","$2y$10$QFZB5qa45.BQv5ygF3T0buyOLGmnXPMXQdB0Nrk7m9NNl3r8YhCMu","082335791705");
INSERT INTO customer VALUES("C0007","kelvintut304@gmail.com","kelvintut304@gmail.com","kelvintut304@gmail.com","$2y$10$3twwdJBWVO1e6AaITQfJGOpOaE4eBWB7P8DPNU1vg0A42AEi3FAE2","082335791705");



CREATE TABLE `inventory` (
  `kode_item` varchar(100) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `qty` varchar(200) NOT NULL,
  `satuan` varchar(200) NOT NULL,
  `harga` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`kode_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO inventory VALUES("ITM001","Kasa Steril 10cm","90","Box","10000","2026-07-26");
INSERT INTO inventory VALUES("ITM002","Plester Roll","45","Roll","8000","2026-07-27");
INSERT INTO inventory VALUES("ITM003","Cairan Antiseptik 100ml","50","Botol","25000","2026-07-26");
INSERT INTO inventory VALUES("ITM004","Gunting Medis","30","Pcs","55000","2026-07-26");
INSERT INTO inventory VALUES("ITM005","Sarung Tangan Latex","100","Box","25000","2026-07-27");



CREATE TABLE `komponen_produk` (
  `kode_paket` varchar(100) NOT NULL,
  `kode_item` varchar(100) NOT NULL,
  `kode_produk` varchar(100) NOT NULL,
  `nama_produk` varchar(200) NOT NULL,
  `kebutuhan` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO komponen_produk VALUES("PKT001","ITM001","P0003","Paket P3K Lengkap","2");
INSERT INTO komponen_produk VALUES("PKT001","ITM002","P0003","Paket P3K Lengkap","1");
INSERT INTO komponen_produk VALUES("PKT001","ITM003","P0003","Paket P3K Lengkap","1");
INSERT INTO komponen_produk VALUES("PKT001","ITM004","P0003","Paket P3K Lengkap","1");



CREATE TABLE `pesanan` (
  `id_order` int(11) NOT NULL AUTO_INCREMENT,
  `invoice` varchar(200) NOT NULL,
  `email_pembeli` varchar(255) DEFAULT NULL,
  `kode_customer` varchar(200) NOT NULL,
  `kode_produk` varchar(200) NOT NULL,
  `nama_produk` varchar(200) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `status` varchar(200) NOT NULL,
  `tanggal` date NOT NULL,
  `provinsi` varchar(200) NOT NULL,
  `kota` varchar(200) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `kode_pos` varchar(200) NOT NULL,
  `terima` varchar(200) NOT NULL,
  `tolak` varchar(200) NOT NULL,
  `cek` int(11) NOT NULL,
  PRIMARY KEY (`id_order`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO pesanan VALUES("8","INV0001","","C0002","P0003","Paket P3K Lengkap","1","150000","Pesanan Baru","2020-07-27","Jawa Timur","Surabaya","Jl.Tanah Merah Indah 1","60129","2","1","1");
INSERT INTO pesanan VALUES("9","INV0002","","C0002","P0001","Kursi Roda Standar","1","750000","Pesanan Baru","2020-07-27","Jawa Barat","Bandung","Jl.Jati Nangor Blok C, 10","30712","0","0","1");
INSERT INTO pesanan VALUES("10","INV0003","","C0003","P0002","Tensimeter Digital","2","350000","0","2020-07-27","Jawa Tengah","Yogyakarta","Jl.Malioboro, Blok A 10D","30123","1","0","0");
INSERT INTO pesanan VALUES("11","INV0003","","C0003","P0003","Paket P3K Lengkap","1","150000","0","2020-07-27","Jawa Tengah","Yogyakarta","Jl.Malioboro, Blok A 10D","30123","1","0","0");
INSERT INTO pesanan VALUES("12","INV0003","","C0003","P0001","Kursi Roda Standar","1","750000","0","2020-07-27","Jawa Tengah","Yogyakarta","Jl.Malioboro, Blok A 10D","30123","1","0","0");
INSERT INTO pesanan VALUES("13","INV0004","","C0001","P0002","Tensimeter Digital","1","350000","0","2020-07-26","Jawa Timur","Sidoarjo","Jl.KH Syukur Blok C 18 A","50987","1","0","0");
INSERT INTO pesanan VALUES("14","INV0005","kelvintut304@gmail.com","C0007","P0001","Kursi Roda Standar","1","750000","Pesanan Baru","2025-09-15","surabaya","surabaya","apartmen gunawangsa manyar","70248","","","0");
INSERT INTO pesanan VALUES("15","INV0005","kelvintut304@gmail.com","C0007","P0003","Paket P3K Lengkap","4","150000","Pesanan Baru","2025-09-15","surabaya","surabaya","apartmen gunawangsa manyar","70248","","","0");
INSERT INTO pesanan VALUES("16","INV0006","kelvintut304@gmail.com","C0007","P0001","Kursi Roda Standar","1","750000","Pesanan Baru","2025-09-15","surabaya","surabaya","apartmen gunawangsa manyar","70248","","","0");
INSERT INTO pesanan VALUES("17","INV0007","kelvintut304@gmail.com","C0007","P0003","Paket P3K Lengkap","1","150000","Pesanan Baru","2025-09-15","surabaya","surabaya","apartmen gunawangsa manyar","70248","0","0","0");
INSERT INTO pesanan VALUES("18","INV0008","kelvintut304@gmail.com","C0007","P0001","Kursi Roda Standar","1","750000","Pesanan Baru","2025-09-15","surabaya","surabaya","apartmen gunawangsa manyar","70248","0","0","0");



CREATE TABLE `produk` (
  `kode_produk` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `image` text NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` int(11) NOT NULL,
  PRIMARY KEY (`kode_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO produk VALUES("P0001","Kursi Roda Standar","kursiroda.jpeg","Kursi roda standar untuk mobilitas pasien, bahan kuat dan ringan.","750000");
INSERT INTO produk VALUES("P0002","Tensimeter Digital","tensimeter.jpeg","				Alat pengukur tekanan darah digital otomatis dengan layar LCD yang jelas.			","350000");
INSERT INTO produk VALUES("P0003","Paket P3K Lengkap","p3klengkap.jpeg","Kotak pertolongan pertama pada kecelakaan (P3K) dengan isi lengkap untuk kebutuhan darurat.","150000");



CREATE TABLE `report_cancel` (
  `id_report_cancel` int(11) NOT NULL AUTO_INCREMENT,
  `id_order` varchar(100) NOT NULL,
  `kode_produk` varchar(100) NOT NULL,
  `jumlah` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id_report_cancel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `report_inventory` (
  `id_report_inv` int(11) NOT NULL AUTO_INCREMENT,
  `kode_item` varchar(100) NOT NULL,
  `nama_item` varchar(100) NOT NULL,
  `jml_stok_item` int(11) NOT NULL,
  `tanggal` varchar(11) NOT NULL,
  PRIMARY KEY (`id_report_inv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `report_omset` (
  `id_report_omset` int(11) NOT NULL AUTO_INCREMENT,
  `invoice` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `total_omset` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id_report_omset`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `report_penjualan` (
  `id_report_sell` int(11) NOT NULL AUTO_INCREMENT,
  `invoice` varchar(100) NOT NULL,
  `kode_produk` varchar(100) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `jumlah_terjual` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id_report_sell`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `report_pesanan` (
  `id_report_order` int(11) NOT NULL AUTO_INCREMENT,
  `invoice` varchar(100) NOT NULL,
  `kode_produk` varchar(100) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id_report_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `report_profit` (
  `id_report_profit` int(11) NOT NULL AUTO_INCREMENT,
  `kode_paket` varchar(100) NOT NULL,
  `invoice` varchar(100) NOT NULL,
  `kode_produk` varchar(100) NOT NULL,
  `jumlah` varchar(11) NOT NULL,
  `total_profit` varchar(11) NOT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id_report_profit`),
  UNIQUE KEY `kode_paket` (`kode_paket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


