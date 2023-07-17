# ************************************************************
# Sequel Ace SQL dump
# Version 20046
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: 127.0.0.1 (MySQL 5.5.5-10.4.27-MariaDB)
# Database: uas_oji
# Generation Time: 2023-06-22 01:37:49 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table customer
# ------------------------------------------------------------

CREATE TABLE `customer` (
  `id_cust` int(11) NOT NULL AUTO_INCREMENT,
  `nama_cust` varchar(50) NOT NULL,
  `no_ktp` int(100) NOT NULL,
  `hp` int(15) NOT NULL,
  PRIMARY KEY (`id_cust`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;

INSERT INTO `customer` (`id_cust`, `nama_cust`, `no_ktp`, `hp`)
VALUES
	(2,'rijal',123,456);

/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table kamar
# ------------------------------------------------------------

CREATE TABLE `kamar` (
  `id_kamar` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_kamar` varchar(20) NOT NULL,
  `kategori` varchar(10) NOT NULL,
  PRIMARY KEY (`id_kamar`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

LOCK TABLES `kamar` WRITE;
/*!40000 ALTER TABLE `kamar` DISABLE KEYS */;

INSERT INTO `kamar` (`id_kamar`, `jenis_kamar`, `kategori`)
VALUES
	(31,'Superior','Superior'),
	(32,'Deluxe','Deluxe');

/*!40000 ALTER TABLE `kamar` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pemesanan
# ------------------------------------------------------------

CREATE TABLE `pemesanan` (
  `id_pesan` int(11) NOT NULL AUTO_INCREMENT,
  `id_cust` int(11) NOT NULL,
  `id_kamar` int(11) NOT NULL,
  `tgl_checkin` date NOT NULL,
  `tgl_checkout` date NOT NULL,
  `lama_inap` int(11) NOT NULL,
  `total_bayar` int(11) NOT NULL,
  PRIMARY KEY (`id_pesan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

LOCK TABLES `pemesanan` WRITE;
/*!40000 ALTER TABLE `pemesanan` DISABLE KEYS */;

INSERT INTO `pemesanan` (`id_pesan`, `id_cust`, `id_kamar`, `tgl_checkin`, `tgl_checkout`, `lama_inap`, `total_bayar`)
VALUES
	(2,2,31,'2023-06-21','2023-06-20',1,850);

/*!40000 ALTER TABLE `pemesanan` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
