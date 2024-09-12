-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.28-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.4.0.6659
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for e_journal
CREATE DATABASE IF NOT EXISTS `e_journal` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `e_journal`;

-- Dumping structure for table e_journal.classes
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `school_id` (`school_id`),
  CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table e_journal.classes: ~15 rows (approximately)
INSERT INTO `classes` (`id`, `name`, `school_id`) VALUES
	(1, '1 Клас', 1),
	(2, '2 Клас', 1),
	(3, '11 Клас', 2),
	(4, '12 Клас', 2),
	(5, '5 клас', 1),
	(6, '8 клас', 2),
	(7, '1 клас', 3),
	(8, '2 клас', 3),
	(9, '3 клас', 3),
	(10, '6 клас', 4),
	(11, '7 клас', 4),
	(12, '8 клас', 4),
	(13, '9 клас', 5),
	(14, '10 клас', 5),
	(15, '11 клас', 5);

-- Dumping structure for table e_journal.schools
CREATE TABLE IF NOT EXISTS `schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table e_journal.schools: ~5 rows (approximately)
INSERT INTO `schools` (`id`, `name`) VALUES
	(1, 'УКТЦ'),
	(2, 'ГПЧЕ'),
	(3, 'Св. Св. Кирил и Методий'),
	(4, '1-во Софийско'),
	(5, '7-мо Пловдивско');

-- Dumping structure for table e_journal.students
CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `bel` text DEFAULT NULL,
  `maths` text DEFAULT NULL,
  `english` text DEFAULT NULL,
  `classes_skipped_bel` int(11) DEFAULT NULL,
  `classes_skipped_maths` int(11) DEFAULT NULL,
  `classes_skipped_english` int(11) DEFAULT NULL,
  `class_id` int(11) NOT NULL,
  KEY `class_id` (`class_id`),
  CONSTRAINT `students_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table e_journal.students: ~150 rows (approximately)
INSERT INTO `students` (`id`, `name`, `email`, `password`, `bel`, `maths`, `english`, `classes_skipped_bel`, `classes_skipped_maths`, `classes_skipped_english`, `class_id`) VALUES
	(1, 'Андрей Иванов', 'andrei.ivanov@email.com', 'andrei123', '2 6', '3 4 5 2 2 2 2', '4 5 5 5 4 4 6 2', 4, 2, 0, 1),
	(2, 'Борислава Петрова', 'borislava.petrova@email.com', 'borislava123', '6 4 5 3', '4 3 2', '4 2 5', 0, 0, 0, 1),
	(3, 'Веселин Георгиев', 'veselin.georgiev@email.com', 'veselin123', '4 3 2 4 4 4 4', '5 3 2 2 2 2', '5 3 4 4', 1, 4, 1, 1),
	(4, 'Гергана Димитрова', 'gergana.dimitrova@email.com', 'gergana123', '5 2 3', '2 3 4', '2 3 5', 0, 0, 0, 1),
	(5, 'Даниел Митев', 'daniel.mitev@email.com', 'daniel123', '2 6', '5', '6 6 6 4', 7, 3, 7, 1),
	(6, 'Елена Тодорова', 'elena.todorova@email.com', 'elena123', '4 4 4 3 2 4 6 4 2 2', '3 3 3 2 6 5 2 6 2 2', '2 2 2 5 6 6 2', 1, 0, 5, 1),
	(7, 'Живко Киров', 'zhivko.kirov@email.com', 'jivko123', '6 6 6 2', '4 2 4', '6 4 2', 3, 2, 4, 1),
	(8, 'Зорница Стойчева', 'zornica.stoicheva@email.com', 'zornitsa123', '2 3 5', '6 4 2', '2 3 4', 0, 0, 0, 1),
	(9, 'Иван Кръстев', 'ivan.krastev@email.com', 'ivan123', '4 3 6', '3 5 4', '6 2 4', 0, 0, 0, 1),
	(10, 'Йоана Маркова', 'yoana.markova@email.com', 'ioana123', '2 5 4', '6 5 3', '2 6 5', 0, 0, 0, 1),
	(1, 'Катерина Николова', 'katerina.nikolova@email.com', 'katerina123', '2 3 4 6', '4 5 3', '3 3', 0, 0, 0, 2),
	(2, 'Любомир Минчев', 'lyubomir.minchev@email.com', 'lubomir123', '4 6 6', '5 2 3', '5 3 5', 0, 0, 0, 2),
	(3, 'Деница Георгиева', 'denitsa.georgieva@email.com', 'denitsa123', '6 2 3', '6', '6', 3, 0, 0, 2),
	(4, 'Николай Иванов', 'nikolay.ivanov@email.com', 'nikolai123', '4 2 5', '2 6 3', '2 3 6', 0, 0, 0, 2),
	(5, 'Олга Димитрова', 'olga.dimitrova@email.com', 'olga123', '3 4 6 2 2 2 3', '4 3 2 4 4 4 4', '2', 6, 0, 0, 2),
	(6, 'Павел Стефанов', 'pavel.stefanov@email.com', 'pavel123', '2 6 5', '4 3 6', '4 2 5', 0, 0, 0, 2),
	(7, 'Радослава Петрова', 'radoslava.petrova@email.com', 'radoslava123', '4 2 6', '2 4 5', '2 5 6', 0, 0, 0, 2),
	(8, 'Симеон Василев', 'simeon.vasilev@email.com', 'simeon123', '3 5 6', '2 4 6', '2 6 4', 0, 0, 0, 2),
	(9, 'Теодора Митева', 'teodora.miteva@email.com', 'teodora123', '5 4 3', '6 6 5', '6 3 2', 0, 0, 0, 2),
	(10, 'Умберто Андонов', 'umberto.andonov@email.com', 'umberto123', '6 4 5', '5 2 6', '5 6 3', 0, 0, 0, 2),
	(1, 'Димитър Иванов', 'dimitar.ivanov@email.com', 'dimitar123', '4 5 3', '6 5 6', '5 4 6', 0, 0, 0, 3),
	(2, 'Ангела Николова', 'angela.nikolova@email.com', 'angela123', '5 4 5', '4 6 5', '6 5 4', 0, 0, 0, 3),
	(3, 'Петя Станчева', 'petya.stancheva@email.com', 'petya123', '6 6 6', '4 4 5', '6 6 6', 0, 0, 0, 3),
	(4, 'Георги Маринов', 'georgi.marinov@email.com', 'georgi123', '5 6 5', '6 6 6', '5 6 6', 0, 0, 0, 3),
	(5, 'Мария Георгиева', 'maria.georgieva@email.com', 'maria123', '6 5 6', '6 4 6', '4 5 6', 0, 0, 0, 3),
	(6, 'Станимир Тодоров', 'stanimir.todorov@email.com', 'stanimir123', '4 4 4', '5 6 4', '4 4 5', 0, 0, 0, 3),
	(7, 'Александър Петков', 'alexander.petkov@email.com', 'alexander123', '5 6 5', '6 6 6', '5 5 6', 0, 0, 0, 3),
	(8, 'Виктория Славчева', 'viktoria.slavcheva@email.com', 'viktoria123', '6 6 6', '6 6 6', '6 6 6', 0, 0, 0, 3),
	(9, 'Даниел Стефанов', 'daniel.stefanov@email.com', 'daniel123', '4 5 4', '5 6 4', '4 4 5', 0, 0, 0, 3),
	(10, 'Кристина Иванова', 'kristina.ivanova@email.com', 'kristina123', '5 6 5', '6 6 6', '6 6 6', 0, 0, 0, 3),
	(1, 'Иван Петров', 'ivan.petrov@email.com', 'ivan123', '4 5 6', '6 6 6', '5 6 5', 0, 0, 0, 4),
	(2, 'Мира Георгиева', 'mira.georgieva@email.com', 'mira123', '6 5 6', '5 6 5', '6 6 6', 0, 0, 0, 4),
	(3, 'Андрей Стоянов', 'andrei.stoyanov@email.com', 'andrei123', '4 4 6', '6 5 6', '4 6 5', 0, 0, 0, 4),
	(4, 'Гергана Иванова', 'gergana.ivanova@email.com', 'gergana123', '5 5 6', '5 5 5', '6 6 5', 0, 0, 0, 4),
	(5, 'Светослав Василев', 'svetoslav.vasilev@email.com', 'svetoslav123', '5 6 5', '6 6 5', '5 5 5', 0, 0, 0, 4),
	(6, 'Виктор Митев', 'viktor.mitev@email.com', 'viktor123', '4 4 4', '4 4 4', '4 4 4', 0, 0, 0, 4),
	(7, 'Милена Костадинова', 'milena.kostadinova@email.com', 'milena123', '6 6 6', '6 6 6', '6 6 6', 0, 0, 0, 4),
	(8, 'Георги Тодоров', 'georgi.todorov@email.com', 'georgi123', '4 5 4', '5 6 5', '4 5 6', 0, 0, 0, 4),
	(9, 'Христина Маринова', 'hristina.marinova@email.com', 'hristina123', '5 6 5', '5 6 5', '6 5 6', 0, 0, 0, 4),
	(10, 'Стефан Колев', 'stefan.kolev@email.com', 'stefan123', '5 5 5', '5 5 5', '5 5 5', 0, 0, 0, 4),
	(1, 'Петър Димитров', 'petar.dimitrov@email.com', 'petar123', '5 5 5', '6 6 6 2', '5 5 5', 0, 0, 0, 5),
	(2, 'Ивайло Костов', 'ivaylo.kostov@email.com', 'ivaylo123', '4 6 5', '5 4 6', '6 5 6', 0, 0, 0, 5),
	(3, 'Маргарита Георгиева', 'margarita.georgieva@email.com', 'margarita123', '6 6 6 2', '5 6 5', '6 6 6 2', 0, 0, 0, 5),
	(4, 'Даниел Николов', 'daniel.nikolov@email.com', 'daniel123', '4 5 5', '6 6 2', '5 5 5', 0, 0, 0, 5),
	(5, 'Василена Иванова', 'vasilena.ivanova@email.com', 'vasilena123', '5 6 5', '5 5 5', '6 5 6', 0, 0, 0, 5),
	(6, 'Румен Петров', 'rumen.petrov@email.com', 'rumen123', '5 5 5', '5 6 5', '5 5 5', 0, 0, 0, 5),
	(7, 'Стефания Георгиева', 'stefania.georgieva@email.com', 'stefania123', '4 4 4', '4 4 4', '4 4 4', 0, 0, 0, 5),
	(8, 'Георги Димитров', 'georgi.dimitrov@email.com', 'georgi123', '', '6 6 6 2', '5 6 5', 0, 0, 0, 5),
	(9, 'Ивелина Иванова', 'ivelina.ivanova@email.com', 'ivelina123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 5),
	(10, 'Кристина Стоянова', 'kristina.stoyanova@email.com', 'kristina123', '5 6 5', '6 5 6', '5 6 5', 0, 0, 0, 5),
	(1, 'Мария Иванова', 'maria.ivanova@email.com', 'maria123', '5 6 5', '6 5 6', '5 6 5', 0, 5, 0, 6),
	(2, 'Иван Димитров', 'ivan.dimitrov@email.com', 'ivan123', '6 6 6', '5 6 5', '6 5 6', 0, 0, 0, 6),
	(3, 'Николай Георгиев', 'nikolay.georgiev@email.com', 'nikolay123', '5 5 5', '5 6 5', '5 5 5', 0, 0, 0, 6),
	(4, 'Валерия Петрова', 'valeriya.petrova@email.com', 'valeriya123', '6 5 6', '6 6 6', '5 6 5', 0, 0, 0, 6),
	(5, 'Симона Костова', 'simona.kostova@email.com', 'simona123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 6),
	(6, 'Станимир Георгиев', 'stanimir.georgiev@email.com', 'stanimir123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 6),
	(7, 'Виктор Николов', 'viktor.nikolov@email.com', 'viktor123', '4 4 4', '4 4 4', '4 4 4', 0, 0, 0, 6),
	(8, 'Теодора Иванова', 'teodora.ivanova@email.com', 'teodora123', '5 6 5', '5 5 5', '6 5 6', 0, 0, 0, 6),
	(9, 'Димитър Костов', 'dimitar.kostov@email.com', 'dimitar123', '6 6 6', '6 5 6', '5 6 5', 0, 0, 0, 6),
	(10, 'Гергана Димитрова', 'gergana.dimitrova@email.com', 'gergana123', '5 6 5', '6 5 6', '5 6 5', 0, 0, 0, 6),
	(1, 'Стела Иванова', 'stela.ivanova@email.com', 'stela123', '6 6 6', '5 6 5', '6 5 6', 0, 0, 0, 7),
	(2, 'Илиян Димитров', 'iliyan.dimitrov@email.com', 'iliyan123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 7),
	(3, 'Анна Георгиева', 'anna.georgieva@email.com', 'anna123', '5 6 5', '5 5 5', '6 5 6', 0, 0, 0, 7),
	(4, 'Кристиян Петров', 'kristiyan.petrov@email.com', 'kristiyan123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 7),
	(5, 'Мартин Костов', 'martin.kostov@email.com', 'martin123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 7),
	(6, 'Радина Георгиева', 'radina.georgieva@email.com', 'radina123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 7),
	(7, 'Калоян Николов', 'kaloqan.nikolov@email.com', 'kaloqan123', '6 5 6', '6 5 6', '5 6 5', 0, 0, 0, 7),
	(8, 'Мая Иванова', 'maya.ivanova@email.com', 'maya123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 7),
	(9, 'Христина Димитрова', 'hristina.dimitrova@email.com', 'hristina123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 7),
	(10, 'Милена Георгиева', 'milena.georgieva@email.com', 'milena123', '5 6 5', '5 6 5', '6 5 6', 0, 0, 0, 7),
	(1, 'Даниел Иванов', 'daniel.ivanov@email.com', 'daniel123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 8),
	(2, 'Андреа Димитрова', 'andrea.dimitrova@email.com', 'andrea123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 8),
	(3, 'Ивайло Георгиев', 'ivaylo.georgiev@email.com', 'ivaylo123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 8),
	(4, 'Валентин Петров', 'valentin.petrov@email.com', 'valentin123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 8),
	(5, 'Милан Костов', 'milan.kostov@email.com', 'milan123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 8),
	(6, 'Даниела Георгиева', 'daniela.georgieva@email.com', 'daniela123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 8),
	(7, 'Виктория Николова', 'viktoriya.nikolova@email.com', 'viktoriya123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 8),
	(8, 'Антон Иванов', 'anton.ivanov@email.com', 'anton123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 8),
	(9, 'Даниел Димитров', 'daniel.dimitrov@email.com', 'daniel123', '6 5 6', '6 5 6', '6 5 6', 0, 0, 0, 8),
	(10, 'Любомир Костадинов', 'lyubomir.kostadinov@email.com', 'lyubomir123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 8),
	(11, 'Магдалена Иванова', 'magdalena.ivanova@email.com', 'magdalena123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 8),
	(1, 'Христина Димитрова', 'hristina.dimitrova@email.com', 'hristina123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 9),
	(2, 'Милена Георгиева', 'milena.georgieva@email.com', 'milena123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 9),
	(3, 'Даниел Иванов', 'daniel.ivanov@email.com', 'daniel123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 9),
	(4, 'Андреа Димитрова', 'andrea.dimitrova@email.com', 'andrea123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 9),
	(5, 'Ивайло Георгиев', 'ivaylo.georgiev@email.com', 'ivaylo123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 9),
	(6, 'Валентин Петров', 'valentin.petrov@email.com', 'valentin123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 9),
	(7, 'Милан Костов', 'milan.kostov@email.com', 'milan123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 9),
	(8, 'Даниела Георгиева', 'daniela.georgieva@email.com', 'daniela123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 9),
	(9, 'Виктория Николова', 'viktoriya.nikolova@email.com', 'viktoriya123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 9),
	(10, 'Антон Иванов', 'anton.ivanov@email.com', 'anton123', '6 5 6', '2 5 3', '6 3 2', 0, 0, 0, 9),
	(1, 'Симона Иванова', 'simona.ivanova@email.com', 'simona123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 10),
	(2, 'Георги Христов', 'georgi.hristov@email.com', 'georgi123', '5 6 5 2', '6 6 6', '5 6 5', 0, 0, 0, 10),
	(3, 'Симеон Костов', 'simeon.kostov@email.com', 'simeon123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 10),
	(4, 'Мартин Иванов', 'martin.ivanov@email.com', 'martin123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 10),
	(5, 'Кристиан Димитров', 'kristian.dimitrov@email.com', 'kristian123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 10),
	(6, 'Милена Илиева', 'milena.ilieva@email.com', 'milena123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 10),
	(7, 'Даниел Георгиев', 'daniel.georgiev@email.com', 'daniel123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 10),
	(8, 'Михаела Петрова', 'mihalea.petrova@email.com', 'mihalea123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 10),
	(9, 'Николай Иванов', 'nikolay.ivanov@email.com', 'nikolay123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 10),
	(10, 'Катерина Георгиева', 'katerina.georgieva@email.com', 'katerina123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 10),
	(1, 'Георги Иванов', 'georgi.ivanov@email.com', 'georgi123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 11),
	(2, 'Антония Димитрова', 'antoniya.dimitrova@email.com', 'antoniya123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 11),
	(3, 'Светла Георгиева', 'svetla.georgieva@email.com', 'svetla123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 11),
	(4, 'Петър Илиев', 'petar.iliev@email.com', 'petar123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 11),
	(5, 'Димитър Георгиев', 'dimitar.georgiev@email.com', 'dimitar123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 11),
	(6, 'Красимир Иванов', 'krasimir.ivanov@email.com', 'krasimir123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 11),
	(7, 'Деница Димитрова', 'denitza.dimitrova@email.com', 'denitza123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 11),
	(8, 'Теодора Георгиева', 'teodora.georgieva@email.com', 'teodora123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 11),
	(9, 'Ивайло Илиев', 'ivaylo.iliev@email.com', 'ivaylo123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 11),
	(1, 'Кристина Иванова', 'kristina.ivanova@email.com', 'kristina123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 12),
	(2, 'Георги Йорданов', 'georgi.yordanov@email.com', 'georgi123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 12),
	(3, 'Даниел Стефанов', 'daniel.stefanov@email.com', 'daniel123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 12),
	(4, 'Иванка Петкова', 'ivanka.petkova@email.com', 'ivanka123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 12),
	(5, 'Теодор Димитров', 'teodor.dimitrov@email.com', 'teodor123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 12),
	(6, 'Даниел Иванов', 'daniel.ivanov@email.com', 'daniel123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 12),
	(7, 'Светла Георгиева', 'svetla.georgieva@email.com', 'svetla123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 12),
	(8, 'Марин Тодоров', 'marin.todorov@email.com', 'marin123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 12),
	(9, 'Ангел Петров', 'angel.petrov@email.com', 'angel123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 12),
	(10, 'Милка Василева', 'milka.vasileva@email.com', 'milka123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 12),
	(1, 'Димитър Иванов', 'dimitar.ivanov@email.com', 'dimitar123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 13),
	(2, 'Елена Димитрова', 'elena.dimitrova@email.com', 'elena123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 13),
	(3, 'Николай Христов', 'nikolay.hristov@email.com', 'nikolay123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 13),
	(4, 'Станимир Георгиев', 'stanimir.georgiev@email.com', 'stanimir123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 13),
	(5, 'Радослава Иванова', 'radoslava.ivanova@email.com', 'radoslava123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 13),
	(6, 'Георги Колев', 'georgi.kolev@email.com', 'georgi123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 13),
	(7, 'Мила Георгиева', 'mila.georgieva@email.com', 'mila123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 13),
	(8, 'Димитър Йорданов', 'dimitar.yordanov@email.com', 'dimitar123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 13),
	(9, 'Йорданка Димитрова', 'yordanka.dimitrova@email.com', 'yordanka123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 13),
	(10, 'Петър Маринов', 'petar.marinov@email.com', 'petar123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 13),
	(1, 'Валентина Петкова', 'valentina.petkova@email.com', 'valentina123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 14),
	(2, 'Румен Иванов', 'rumen.ivanov@email.com', 'rumen123', '5 5 5', '6 5 6', '5 5 5', 0, 0, 0, 14),
	(3, 'Георги Петров', 'georgi.petrov@email.com', 'georgi123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 14),
	(4, 'Мария Георгиева', 'maria.georgieva@email.com', 'maria123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 14),
	(5, 'Николай Иванов', 'nikolay.ivanov@email.com', 'nikolay123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 14),
	(6, 'Иванка Колева', 'ivanka.koleva@email.com', 'ivanka123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 14),
	(7, 'Десислава Георгиева', 'desislava.georgieva@email.com', 'desislava123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 14),
	(8, 'Светослав Петров', 'svetoslav.petrov@email.com', 'svetoslav123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 14),
	(9, 'Петър Георгиев', 'petar.georgiev@email.com', 'petar123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 14),
	(10, 'Иво Димитров', 'ivo.dimitrov@email.com', 'ivo123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 14),
	(1, 'Женя Кирова', 'jenya.kirova@email.com', 'jenya123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 15),
	(2, 'Марин Петков', 'marin.petkov@email.com', 'marin123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 15),
	(3, 'Красимир Томов', 'krasimir.tomov@email.com', 'krasimir123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 15),
	(4, 'Димитър Петров', 'dimitar.petrov@email.com', 'dimitar123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 15),
	(5, 'Мартин Митев', 'martin.mitev@email.com', 'martin123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 15),
	(6, 'Тодор Тодоров', 'todor.todorov@email.com', 'todor123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 15),
	(7, 'Мариян Маринов', 'mariyan.marinov@email.com', 'mariyan123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 15),
	(8, 'Иво Георгиев', 'ivo.georgiev@email.com', 'ivo123', '6 6 6', '5 5 5', '6 5 6', 0, 0, 0, 15),
	(9, 'Нина Йорданова', 'nina.yordanova@email.com', 'nina123', '5 6 5', '6 6 6', '5 6 5', 0, 0, 0, 15),
	(10, 'Стефка Иванова', 'stefka.ivanova@email.com', 'stefka123', '6 5 6', '5 6 5', '6 5 6', 0, 0, 0, 15);

-- Dumping structure for table e_journal.teachers
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `school_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `school_id` (`school_id`),
  CONSTRAINT `school` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table e_journal.teachers: ~4 rows (approximately)
INSERT INTO `teachers` (`id`, `name`, `email`, `password`, `school_id`) VALUES
	(1, 'Мария Пламенова', 'mariaplamenova@t.com', 'maria123', 1),
	(2, 'Панайот Пипков', 'panaiot@t.com', 'panaiot123', 2),
	(3, 'Ангел Петров', 'angelcho@t.com', 'angel123', 3),
	(4, 'Петър Георгиев', 'petar@t.com', 'petar123', 4),
	(5, 'Мани Маас', 'mani@t.com', 'mani123', 5);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
