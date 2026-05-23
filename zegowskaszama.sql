-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 23, 2026 at 08:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zegowskaszama`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `Imie` varchar(35) NOT NULL,
  `Nazwisko` varchar(35) NOT NULL,
  `Login` varchar(35) NOT NULL,
  `Hasło` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `koszyk`
--

CREATE TABLE `koszyk` (
  `id` int(11) NOT NULL,
  `id_produktu` int(11) NOT NULL,
  `id_uzytkownika` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produkty`
--

CREATE TABLE `produkty` (
  `id` int(11) NOT NULL,
  `Nazwa` varchar(100) NOT NULL,
  `Cena` float NOT NULL,
  `Kategoria` varchar(35) NOT NULL,
  `opis` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produkty`
--

INSERT INTO `produkty` (`id`, `Nazwa`, `Cena`, `Kategoria`, `opis`) VALUES
(1, 'Duble dog', 8.5, 'jedzenie', 'Podwójna porcja parówki w chrupiącej bułce z Twoimi ulubionymi sosami.'),
(2, 'Buła gołosza', 6.7, 'jedzenie', 'Sytna, tradycyjna buła wypchana po brzegi klasycznymi dodatkami.'),
(3, 'Hot-dog klasyczny', 6, 'jedzenie', 'Klasyka gatunku – idealnie podgrzana parówka, chrupiąca bułka i sosy.'),
(4, 'Hot-dog XXL', 9.5, 'jedzenie', 'Wersja dla naprawdę głodnych. Większa bułka, większa kiełbaska, więcej szamy.'),
(5, 'Zapiekanka mini', 7.5, 'jedzenie', 'Klasyczna zapiekanka z pieczarkami i ciągnącym się serem – idealna na mniejszy głód.'),
(6, 'Zapiekanka duża', 10, 'jedzenie', 'Długa, chrupiąca zapiekanka z bogatym farszem pieczarkowym i solidną porcją sera.'),
(7, 'Tost z serem', 5, 'jedzenie', 'Chrupiący, złocisty tost z mnóstwem ciągnącego się sera w środku.'),
(8, 'Tost z szynką i serem', 6.5, 'jedzenie', 'Klasyczne połączenie ciągnącego się sera i soczystej szynki w chrupiącym pieczywie.'),
(9, 'Kanapka z szynką', 6.5, 'jedzenie', 'Świeża bułka z soczystą szynką, masłem i chrupiącymi warzywami.'),
(10, 'Kanapka z serem', 6, 'jedzenie', 'Tradycyjna, świeża kanapka z żółtym serem i świeżymi dodatkami.'),
(11, 'Kanapka z kurczakiem', 7.5, 'jedzenie', 'Pożywna kanapka z pieczonym kurczakiem, wyrazistym sosem i warzywami.'),
(12, 'Sałatka makaronowa', 8, 'jedzenie', 'Lekka, a zarazem sycąca sałatka na bazie makaronu ze świeżymi dodatkami.'),
(13, 'Drożdżówka z serem', 4, 'przekąski', 'Słodka, puszysta drożdżówka z tradycyjnym twarogowym nadzieniem.'),
(14, 'Drożdżówka z czekoladą', 4.5, 'przekąski', 'Pyszna drożdżówka z bogatym, mocno czekoladowym wnętrzem.'),
(15, 'Drożdżówka z budyniem', 4.5, 'przekąski', 'Delikatna drożdżówka wypełniona po brzegi aksamitnym budyniem.'),
(16, 'Pączek z marmoladą', 3.5, 'przekąski', 'Tradycyjny, puszysty pączek z owocową marmoladą, posypany cukrem pudrem.'),
(17, 'Pączek z czekoladą', 4, 'przekąski', 'Rozpływający się w ustach pączek z gęstym kremem czekoladowym.'),
(18, 'Rogal maślany', 3, 'przekąski', 'Maślany, miękki i delikatny rogal – idealny do porannej kawy lub mleka.'),
(19, 'Baton czekoladowy', 4, 'przekąski', 'Szybki zastrzyk energii w postaci Twojego ulubionego batona czekoladowego.'),
(20, 'Wafel Prince Polo', 3.5, 'przekąski', 'Kultowy, chrupiący wafel kakaowy w czekoladzie.'),
(21, 'Kinder Bueno', 5.5, 'przekąski', 'Lekki wafel z delikatnym nadzieniem z orzechów laskowych oblany mleczną czekoladą.'),
(22, 'Chipsy małe', 5.5, 'przekąski', 'Chrupiące chipsy ziemniaczane w sam raz na małą przekąskę między lekcjami.'),
(23, 'Chipsy duże', 8, 'przekąski', 'Duża paczka chrupiących chipsów – idealna do podzielenia się ze znajomymi.'),
(24, 'Paluszki solone', 3, 'przekąski', 'Klasyczne, chrupiące paluszki z solą – idealna przekąska do chrupania w przerwie.'),
(25, 'Krakersy', 4.5, 'przekąski', 'Kruche, lekko słone krakersy, które sprawdzą się w każdej sytuacji.'),
(26, 'Orzeszki ziemne', 5, 'przekąski', 'Chrupiące, idealnie upieczone i delikatnie solone orzeszki ziemne.'),
(27, 'Żelki', 4.5, 'przekąski', 'Kolorowe, owocowe żelki – idealna słodka chwila przyjemności.'),
(28, 'Guma do żucia', 2.5, 'przekąski', 'Mocno odświeżająca guma do żucia, która natychmiast przywraca świeżość.'),
(29, 'Woda mineralna 0.5L', 3, 'napoje', 'Czysta, orzeźwiająca woda mineralna – idealna na ugaszenie pragnienia.'),
(30, 'Woda smakowa 0.5L', 3.5, 'napoje', 'Delikatnie słodka woda z nutą owocowego smaku, doskonale orzeźwia.'),
(31, 'Sok owocowy 0.33L', 3.5, 'napoje', 'Pyszny, naturalnie owocowy sok w poręcznej butelce na wynos.'),
(32, 'Sok owocowy 1L', 6.5, 'napoje', 'Duża butelka pełna owocowego smaku, idealna do dłuższego gaszenia pragnienia.'),
(33, 'Napój gazowany 0.5L', 5, 'napoje', 'Twój ulubiony, mocno bąbelkowy napój gazowany, który stawia na nogi.'),
(34, 'Napój gazowany 1L', 7.5, 'napoje', 'Duża porcja gazowanego orzeźwienia – idealna na większe pragnienie.'),
(35, 'Ice tea 0.5L', 4.5, 'napoje', 'Mrożona herbata o smaku owocowym, która doskonale chłodzi w ciepłe dni.'),
(36, 'Kawa mrożona', 6.5, 'napoje', 'Aksamitna, mocno schłodzona kawa z mlekiem – idealny miks energii i ochłody.'),
(37, 'Kawa czarna', 5, 'napoje', 'Klasyczna, aromatyczna czarna kawa ze świeżo mielonych ziaren, która daje kopa.'),
(38, 'Kawa z mlekiem', 5.5, 'napoje', 'Delikatna, zbalansowana kawa z dodatkiem puszystego mleka.'),
(39, 'Herbata ciepła', 3, 'napoje', 'Rozgrzewająca, klasyczna herbata, idealna na każdą porę dnia.'),
(40, 'Czekolada na gorąco', 6, 'napoje', 'Gęsta, słodka i aksamitna czekolada na gorąco – czysta przyjemność w kubku.');

-- --------------------------------------------------------

--
-- Table structure for table `użytkownicy`
--

CREATE TABLE `użytkownicy` (
  `id` int(10) UNSIGNED NOT NULL,
  `Imie` varchar(35) NOT NULL,
  `Nazwisko` varchar(35) NOT NULL,
  `Email` varchar(35) NOT NULL,
  `Haslo` varchar(67) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `użytkownicy`
--

INSERT INTO `użytkownicy` (`id`, `Imie`, `Nazwisko`, `Email`, `Haslo`) VALUES
(1, 'Kamien', 'Urwal', 'kamy@gmail.com', '$2y$10$TG7NUX8lC2zp5ZLRMgyETOlnToln3gVnhGddyLKevV7p7IygbV76O');

-- --------------------------------------------------------

--
-- Table structure for table `zamówienia`
--

CREATE TABLE `zamówienia` (
  `id` int(11) NOT NULL,
  `id_użytkownika` int(11) UNSIGNED NOT NULL,
  `id_produktu` int(11) NOT NULL,
  `indeks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Hasło` (`Hasło`);

--
-- Indexes for table `koszyk`
--
ALTER TABLE `koszyk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_produktu` (`id_produktu`,`id_uzytkownika`),
  ADD KEY `id_uzytkownika` (`id_uzytkownika`);

--
-- Indexes for table `produkty`
--
ALTER TABLE `produkty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `użytkownicy`
--
ALTER TABLE `użytkownicy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Hasło` (`Haslo`(25)),
  ADD KEY `id` (`id`);

--
-- Indexes for table `zamówienia`
--
ALTER TABLE `zamówienia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_użytkownika` (`id_użytkownika`),
  ADD KEY `id_produktu` (`id_produktu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `koszyk`
--
ALTER TABLE `koszyk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produkty`
--
ALTER TABLE `produkty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `użytkownicy`
--
ALTER TABLE `użytkownicy`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `zamówienia`
--
ALTER TABLE `zamówienia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `koszyk`
--
ALTER TABLE `koszyk`
  ADD CONSTRAINT `koszyk_ibfk_1` FOREIGN KEY (`id_produktu`) REFERENCES `produkty` (`id`),
  ADD CONSTRAINT `koszyk_ibfk_2` FOREIGN KEY (`id_uzytkownika`) REFERENCES `użytkownicy` (`id`);

--
-- Constraints for table `zamówienia`
--
ALTER TABLE `zamówienia`
  ADD CONSTRAINT `zamówienia_ibfk_1` FOREIGN KEY (`id_produktu`) REFERENCES `produkty` (`id`),
  ADD CONSTRAINT `zamówienia_ibfk_2` FOREIGN KEY (`id_użytkownika`) REFERENCES `użytkownicy` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
