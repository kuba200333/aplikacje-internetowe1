-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sty 20, 2026 at 09:52 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

-- SQLite-compatible schema and data for Plusflix
PRAGMA foreign_keys = ON;
BEGIN TRANSACTION;

-- admins
CREATE TABLE admins (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  login TEXT NOT NULL UNIQUE,
  password_hash TEXT NOT NULL
);

INSERT INTO admins (id, login, password_hash) VALUES
(1, 'admin', '$2y$10$PrzykladowyHashHaslaAdmina123');

-- categories
CREATE TABLE categories (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL UNIQUE
);

INSERT INTO categories (id, name) VALUES
(1, 'Akcja'),
(3, 'Dramat'),
(4, 'Horror'),
(2, 'Komedia'),
(5, 'Sci-Fi');

-- movies
CREATE TABLE movies (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title TEXT NOT NULL,
  description TEXT,
  year INTEGER NOT NULL,
  duration INTEGER NOT NULL,
  image_path TEXT,
  cat_id INTEGER,
  FOREIGN KEY (cat_id) REFERENCES categories(id) ON DELETE SET NULL
);

INSERT INTO movies (id, title, description, year, duration, image_path, cat_id) VALUES
(1, 'Gwiezdne wojny: Część I - Mroczne widmo', 'Dwaj rycerze Jedi wyruszają z misją ocalenia planety Naboo przed inwazją wojsk Federacji Handlowej. Trafiają na pustynny glob, gdzie pomaga im mały Anakin Skywalker.\n', 1999, 166, 'images/star_wars1.jpg', 5),
(2, 'Piła', 'Dwóch mężczyzn budzi się przykutych do zardzewiałej rury w piwnicy. Zdają sobie sprawę, że są uczestnikami krwawej gry szaleńca.\n', 2004, 127, 'images/piła.jpg', 1),
(3, 'Shrek', 'Zielony ogr wyrusza na ratunek księżniczce.', 2001, 90, 'images/shrek.jpg', 2),
(6, 'Matrix', 'Haker komputerowy Neo odkrywa szokującą prawdę o świecie, w którym żyje.', 1999, 136, 'images/matrix.jpg', 5),
(7, 'Skazani na Shawshank', 'Niesłusznie skazany bankier stara się przetrwać w brutalnym świecie więziennym.', 1994, 142, 'images/shawshank.jpg', 3),
(8, 'Mroczny Rycerz', 'Batman stawia czoła psychopatycznemu przestępcy znanemu jako Joker.', 2008, 152, 'images/dark_knight.jpg', 4),
(9, 'Incepcja', 'Złodziej kradnie sekrety z podświadomości śpiących ludzi.', 2010, 148, 'images/inception.jpg', 5),
(10, 'Król Lew', 'Młody lew Simba musi odzyskać tron po tragicznej śmierci ojca.', 1994, 88, 'images/lion_king.jpg', 2),
(11, 'Pulp Fiction', 'Przemoc i odkupienie w historiach kilku przestępców z Los Angeles.', 1994, 154, 'images/pulp_fiction.jpg', 4),
(12, 'Forrest Gump', 'Historia człowieka o niskim ilorazie inteligencji, który staje się bohaterem.', 1994, 142, 'images/forrest_gump.jpg', 3),
(13, 'Obecność', 'Paranormalni badacze pomagają rodzinie terroryzowanej przez mroczną obecność.', 2013, 112, 'images/conjuring.jpg', 1),
(14, 'Gladiator', 'Rzymski generał szuka zemsty po tym, jak został zdradzony przez cesarza.', 2000, 155, 'images/gladiator.jpg', 4),
(15, 'Epoka Lodowcowa', 'Mamut, leniwiec i tygrys szablozęby wyruszają w podróż, by oddać ludzkie dziecko.', 2002, 81, 'images/ice_age.jpg', 2),
(16, 'Titanic', 'Romantyczna historia miłosna na tle dziewiczego rejsu "niezatapialnego" statku.', 1997, 195, 'images/titanic.jpg', 3),
(17, 'Avatar', 'Były żołnierz trafia na planetę Pandora i staje w obronie jej mieszkańców.', 2009, 162, 'images/avatar.jpg', 5),
(18, 'To', 'Grupa dzieci musi stawić czoła przerażającemu klaunowi w małym miasteczku.', 2017, 135, 'images/it.jpg', 1),
(19, 'Kac Vegas', 'Wieczór kawalerski w Las Vegas wymyka się spod kontroli.', 2009, 100, 'images/hangover.jpg', 2),
(20, 'Interstellar', 'Grupa odkrywców wyrusza w podróż przez tunel czasoprzestrzenny.', 2014, 169, 'images/interstellar.jpg', 5),
(21, 'John Wick', 'Emerytowany płatny zabójca wraca do gry, by pomścić swojego psa.', 2014, 101, 'images/john_wick.jpg', 4),
(22, 'Spider-Man: No Way Home', 'Peter Parker prosi Doktora Strange’a o pomoc, co prowadzi do chaosu w multiwersum.', 2021, 148, 'images/spiderman.jpg', 4);

-- platforms
CREATE TABLE platforms (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  logo_path TEXT,
  url TEXT
);

INSERT INTO platforms (id, name, logo_path, url) VALUES
(1, 'Netflix', 'logos/netflix.png', 'https://netflix.com'),
(2, 'Disney+', 'logos/disney.png', 'https://disneyplus.com'),
(3, 'HBO Max', 'logos/hbo.png', 'https://hbomax.com'),
(4, 'Prime Video', 'logos/prime.png', 'https://primevideo.com');

-- movie_platform
CREATE TABLE movie_platform (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  movie_id INTEGER NOT NULL,
  platform_id INTEGER NOT NULL,
  details TEXT,
  FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
  FOREIGN KEY (platform_id) REFERENCES platforms(id) ON DELETE CASCADE
);

INSERT INTO movie_platform (id, movie_id, platform_id, details) VALUES
(1, 1, 2, '4K HDR'),
(2, 2, 1, 'Lektor PL'),
(3, 2, 4, 'HD'),
(4, 3, 1, 'Dubbing PL'),
(5, 3, 3, 'HD'),
(6, 3, 4, 'Dubbing PL'),
(7, 6, 3, '4K Dolby Vision'),
(8, 6, 1, 'HD'),
(9, 7, 1, '4K Remastered'),
(10, 8, 3, 'IMAX Enhanced'),
(11, 9, 1, 'HD'),
(12, 9, 3, '4K'),
(13, 9, 4, NULL),
(14, 10, 2, 'Dubbing PL'),
(15, 11, 1, 'Napisy PL'),
(16, 11, 4, 'HD'),
(17, 12, 1, 'HD'),
(18, 12, 4, 'Lektor PL'),
(19, 13, 3, '4K'),
(20, 13, 1, NULL),
(21, 14, 1, '4K'),
(22, 14, 4, 'Extended Cut'),
(23, 15, 2, 'Dubbing PL'),
(24, 16, 2, '4K HDR'),
(25, 16, 4, 'HD'),
(26, 17, 2, '4K Dolby Atmos'),
(27, 18, 3, '4K HDR'),
(28, 19, 1, 'Lektor PL'),
(29, 19, 3, 'HD'),
(30, 20, 1, '4K'),
(31, 20, 2, 'HD'),
(32, 20, 3, 'IMAX Enhanced'),
(33, 20, 4, '4K HDR'),
(34, 21, 1, 'Lektor PL'),
(35, 21, 4, '4K'),
(36, 21, 3, NULL),
(37, 22, 1, '4K'),
(38, 22, 3, 'Wersja rozszerzona');

-- comments
CREATE TABLE comments (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  movie_id INTEGER NOT NULL,
  nick TEXT NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  cookie_id TEXT NOT NULL,
  FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
);

INSERT INTO comments (id, movie_id, nick, content, created_at, cookie_id) VALUES
(7, 1, 'kuba200333', 'polecam bardzo ten film ciekawa fabuła', '2026-01-09 21:15:50', 'usr_696160742332b9.25886350'),
(8, 6, 'Neo99', 'Absolutny klasyk, oglądam co rok i zawsze odkrywam coś nowego.', '2026-01-09 22:28:30', 'cookie_user_alpha'),
(9, 7, 'Kuba', 'Niesamowita historia o nadziei. Końcówka zawsze wzrusza.', '2026-01-09 22:28:30', 'cookie_user_alpha'),
(10, 20, 'Astronauta', 'Wizualnie majstersztyk, a muzyka Zimmera to inna liga.', '2026-01-09 22:28:30', 'cookie_user_alpha'),
(11, 3, 'Fiona', 'Humor dla dzieci i dorosłych. Osioł wymiata!', '2026-01-09 22:28:30', 'cookie_user_beta'),
(12, 15, 'Sid', 'Dobra bajka, ale Shrek lepszy.', '2026-01-09 22:28:30', 'cookie_user_beta'),
(13, 19, 'Alan', 'Najlepsza komedia jaką widziałem. Scena z tygrysem legendarna.', '2026-01-09 22:28:30', 'cookie_user_beta'),
(14, 2, 'HorrorFan', 'Ciekawy koncept, ale trochę za dużo brutalności jak dla mnie.', '2026-01-09 22:28:30', 'cookie_user_beta'),
(15, 8, 'Joker', 'Heath Ledger zagrał rolę życia. Najlepszy film o Batmanie.', '2026-01-09 22:28:30', 'cookie_user_gamma'),
(16, 21, 'BabaJaga', 'Prosta fabuła, ale sceny walki są po prostu genialne.', '2026-01-09 22:28:30', 'cookie_user_gamma'),
(17, 11, 'Vincent', 'Klimat, dialogi, muzyka. Tarantino to geniusz.', '2026-01-09 22:28:30', 'cookie_user_gamma'),
(18, 1, 'Krytyk', 'Najsłabsza część sagi. Jar Jar Binks psuje cały seans.', '2026-01-09 22:28:30', 'cookie_user_delta'),
(19, 16, 'Rose', 'Wielkie kino, choć trwa wieczność. Scena na dziobie statku kultowa.', '2026-01-09 22:28:30', 'cookie_user_delta'),
(20, 17, 'Jake', 'Piękny świat, ale historia to trochę kopia Pocahontas.', '2026-01-09 22:28:30', 'cookie_user_delta'),
(21, 1, 'Anakin', 'Wyścig podów to najlepsza scena w tym filmie!', '2026-01-09 22:29:40', 'u1'),
(22, 1, 'ObiWan', 'Mam złe przeczucia co do tego dzieciaka.', '2026-01-09 22:29:40', 'u2'),
(23, 1, 'QuiGon', 'Moc jest w nim silna, ale rada się boi.', '2026-01-09 22:29:40', 'u3'),
(24, 1, 'FanSW', 'Trochę za dużo polityki jak na Gwiezdne Wojny.', '2026-01-09 22:29:40', 'u4'),
(25, 2, 'Zagadka', 'Zagrajmy w grę... Świetny thriller z klimatem.', '2026-01-09 22:29:40', 'u1'),
(26, 2, 'Adam', 'Końcówka totalnie mnie zaskoczyła, nie wierzyłem własnym oczom.', '2026-01-09 22:29:40', 'u5'),
(27, 2, 'Zordon', 'Mocne kino, tylko dla ludzi o silnych nerwach.', '2026-01-09 22:29:40', 'u6'),
(28, 3, 'Osioł', 'A daleko jeszcze? Najlepsza bajka wszech czasów.', '2026-01-09 22:29:40', 'u1'),
(29, 3, 'Cebula', 'Ogry mają warstwy! Ten film to arcydzieło humoru.', '2026-01-09 22:29:40', 'u2'),
(30, 3, 'Smoczyca', 'Romantyczna historia w krzywym zwierciadle.', '2026-01-09 22:29:40', 'u3'),
(31, 6, 'Neo', 'Wybrałem czerwoną pigułkę i nie żałuję.', '2026-01-09 22:29:40', 'u1'),
(32, 6, 'Morfid', 'Ten film zmienił moje postrzeganie kina sci-fi.', '2026-01-09 22:29:40', 'u4'),
(33, 6, 'Trinity', 'Sceny walki i bullet-time to był wtedy kosmos.', '2026-01-09 22:29:40', 'u6'),
(34, 7, 'Red', 'Nadzieja to dobra rzecz. Film, który każdy musi zobaczyć.', '2026-01-09 22:29:40', 'u1'),
(35, 7, 'Andy', 'Cudowna lekcja cierpliwości i przyjaźni.', '2026-01-09 22:29:40', 'u2'),
(36, 7, 'Brooks', 'Bardzo wzruszający film, klasyka gatunku.', '2026-01-09 22:29:40', 'u5'),
(37, 8, 'Bruce', 'Batman to postać tragiczna, a ten film to pokazuje.', '2026-01-09 22:29:40', 'u1'),
(38, 8, 'Rachel', 'Mroczny, ciężki i genialnie zagrany przez Ledgera.', '2026-01-09 22:29:40', 'u2'),
(39, 8, 'Harvey', 'Albo umierasz jako bohater, albo żyjesz dość długo, by stać się złoczyńcą.', '2026-01-09 22:29:40', 'u3'),
(40, 9, 'Cobb', 'Film, o którym myślisz jeszcze tydzień po seansie.', '2026-01-09 22:29:40', 'u1'),
(41, 9, 'Mal', 'Czy to na pewno rzeczywistość? Nolan to wizjoner.', '2026-01-09 22:29:40', 'u2'),
(42, 9, 'Arthur', 'Muzyka i efekty wgniatają w fotel przy każdym seansie.', '2026-01-09 22:29:40', 'u5'),
(43, 10, 'Mufasa', 'Pamiętaj kim jesteś. Popłakałem się jak dziecko.', '2026-01-09 22:29:40', 'u1'),
(44, 10, 'Pumba', 'Hakuna Matata! Cudowny powrót do czasów dzieciństwa.', '2026-01-09 22:29:40', 'u3'),
(45, 10, 'Skaza', 'Najlepszy czarny charakter w historii Disneya.', '2026-01-09 22:29:40', 'u4'),
(46, 11, 'Jules', 'Znasz te dialogi na pamięć, a i tak bawią za każdym razem.', '2026-01-09 22:29:40', 'u1'),
(47, 11, 'Mia', 'Taniec Travolty i Umy Thurman to czysty kult.', '2026-01-09 22:29:40', 'u2'),
(48, 11, 'Butch', 'Tarantino w swojej absolutnie najwyższej formie.', '2026-01-09 22:29:40', 'u6'),
(49, 12, 'Jenny', 'Biegnij Forrest! Niesamowita podróż przez historię USA.', '2026-01-09 22:29:40', 'u2'),
(50, 12, 'Bubba', 'Życie jest jak pudełko czekoladek. Piękny i mądry film.', '2026-01-09 22:29:40', 'u4'),
(51, 12, 'Dan', 'Tom Hanks zagrał tu rolę swojego życia.', '2026-01-09 22:29:40', 'u5'),
(52, 13, 'Ed', 'Jeden z nielicznych horrorów, które naprawdę straszą.', '2026-01-09 22:29:40', 'u1'),
(53, 13, 'Lorraine', 'Klimat budowany napięciem, a nie tylko jumpscare’ami.', '2026-01-09 22:29:40', 'u2'),
(54, 13, 'Judy', 'Bałam się iść spać po tym filmie. Polecam!', '2026-01-09 22:29:40', 'u3'),
(55, 14, 'Maximus', 'Mój synu, pomszczę cię! Epickie kino historyczne.', '2026-01-09 22:29:40', 'u1'),
(56, 14, 'Commodus', 'Russel Crowe jest tu niesamowicie charyzmatyczny.', '2026-01-09 22:29:40', 'u4'),
(57, 14, 'Proximo', 'Sceny bitew i walk na arenie są zrealizowane perfekcyjnie.', '2026-01-09 22:29:40', 'u5'),
(58, 20, 'Cooper', 'Miłość to jedyna rzecz, która przekracza wymiary czasu i przestrzeni.', '2026-01-09 22:29:40', 'u1'),
(59, 20, 'Murph', 'Płakałem przy scenie z wiadomościami wideo. Coś pięknego.', '2026-01-09 22:29:40', 'u2'),
(60, 20, 'Tars', 'Naukowy majstersztyk połączony z ogromnymi emocjami.', '2026-01-09 22:29:40', 'u5'),
(61, 20, 'Brand', 'Ten film trzeba obejrzeć na jak największym ekranie.', '2026-01-09 22:29:40', 'u6');

-- ratings
CREATE TABLE ratings (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  movie_id INTEGER NOT NULL,
  rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 10),
  cookie_id TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
);

INSERT INTO ratings (id, movie_id, rating, cookie_id, created_at) VALUES
(6, 1, 9, 'usr_696160742332b9.25886350', '2026-01-09 21:15:50'),
(7, 6, 10, 'cookie_user_alpha', '2026-01-09 22:28:30'),
(8, 7, 9, 'cookie_user_alpha', '2026-01-09 22:28:30'),
(9, 20, 10, 'cookie_user_alpha', '2026-01-09 22:28:30'),
(10, 3, 8, 'cookie_user_beta', '2026-01-09 22:28:30'),
(11, 15, 7, 'cookie_user_beta', '2026-01-09 22:28:30'),
(12, 19, 9, 'cookie_user_beta', '2026-01-09 22:28:30'),
(13, 2, 6, 'cookie_user_beta', '2026-01-09 22:28:30'),
(14, 8, 10, 'cookie_user_gamma', '2026-01-09 22:28:30'),
(15, 21, 9, 'cookie_user_gamma', '2026-01-09 22:28:30'),
(16, 11, 10, 'cookie_user_gamma', '2026-01-09 22:28:30'),
(17, 1, 4, 'cookie_user_delta', '2026-01-09 22:28:30'),
(18, 16, 8, 'cookie_user_delta', '2026-01-09 22:28:30'),
(19, 17, 7, 'cookie_user_delta', '2026-01-09 22:28:30'),
(20, 1, 5, 'u1', '2026-01-09 22:29:40'),
(21, 1, 6, 'u2', '2026-01-09 22:29:40'),
(22, 1, 4, 'u3', '2026-01-09 22:29:40'),
(23, 1, 7, 'u4', '2026-01-09 22:29:40'),
(24, 2, 9, 'u1', '2026-01-09 22:29:40'),
(25, 2, 8, 'u5', '2026-01-09 22:29:40'),
(26, 2, 7, 'u6', '2026-01-09 22:29:40'),
(27, 2, 9, 'u2', '2026-01-09 22:29:40'),
(28, 3, 10, 'u1', '2026-01-09 22:29:40'),
(29, 3, 10, 'u2', '2026-01-09 22:29:40'),
(30, 3, 9, 'u3', '2026-01-09 22:29:40'),
(31, 3, 10, 'u5', '2026-01-09 22:29:40'),
(32, 6, 10, 'u1', '2026-01-09 22:29:40'),
(33, 6, 9, 'u4', '2026-01-09 22:29:40'),
(34, 6, 10, 'u6', '2026-01-09 22:29:40'),
(35, 6, 9, 'u3', '2026-01-09 22:29:40'),
(36, 7, 10, 'u1', '2026-01-09 22:29:40'),
(37, 7, 10, 'u2', '2026-01-09 22:29:40'),
(38, 7, 10, 'u5', '2026-01-09 22:29:40'),
(39, 7, 10, 'u6', '2026-01-09 22:29:40'),
(40, 8, 10, 'u1', '2026-01-09 22:29:40'),
(41, 8, 9, 'u2', '2026-01-09 22:29:40'),
(42, 8, 10, 'u3', '2026-01-09 22:29:40'),
(43, 8, 10, 'u4', '2026-01-09 22:29:40'),
(44, 9, 9, 'u1', '2026-01-09 22:29:40'),
(45, 9, 10, 'u2', '2026-01-09 22:29:40'),
(46, 9, 8, 'u5', '2026-01-09 22:29:40'),
(47, 9, 9, 'u6', '2026-01-09 22:29:40'),
(48, 10, 10, 'u1', '2026-01-09 22:29:40'),
(49, 10, 9, 'u3', '2026-01-09 22:29:40'),
(50, 10, 10, 'u4', '2026-01-09 22:29:40'),
(51, 10, 8, 'u5', '2026-01-09 22:29:40'),
(52, 11, 10, 'u1', '2026-01-09 22:29:40'),
(53, 11, 9, 'u2', '2026-01-09 22:29:40'),
(54, 11, 10, 'u6', '2026-01-09 22:29:40'),
(55, 11, 8, 'u3', '2026-01-09 22:29:40'),
(56, 12, 10, 'u2', '2026-01-09 22:29:40'),
(57, 12, 10, 'u4', '2026-01-09 22:29:40'),
(58, 12, 9, 'u5', '2026-01-09 22:29:40'),
(59, 12, 10, 'u1', '2026-01-09 22:29:40'),
(60, 13, 8, 'u1', '2026-01-09 22:29:40'),
(61, 13, 7, 'u2', '2026-01-09 22:29:40'),
(62, 13, 9, 'u3', '2026-01-09 22:29:40'),
(63, 13, 8, 'u6', '2026-01-09 22:29:40'),
(64, 14, 10, 'u1', '2026-01-09 22:29:40'),
(65, 14, 9, 'u4', '2026-01-09 22:29:40'),
(66, 14, 10, 'u5', '2026-01-09 22:29:40'),
(67, 14, 9, 'u2', '2026-01-09 22:29:40'),
(68, 15, 8, 'u1', '2026-01-09 22:29:40'),
(69, 15, 8, 'u3', '2026-01-09 22:29:40'),
(70, 15, 7, 'u4', '2026-01-09 22:29:40'),
(71, 15, 9, 'u6', '2026-01-09 22:29:40'),
(72, 16, 8, 'u1', '2026-01-09 22:29:40'),
(73, 16, 9, 'u2', '2026-01-09 22:29:40'),
(74, 16, 7, 'u5', '2026-01-09 22:29:40'),
(75, 16, 8, 'u6', '2026-01-09 22:29:40'),
(76, 17, 7, 'u1', '2026-01-09 22:29:40'),
(77, 17, 8, 'u2', '2026-01-09 22:29:40'),
(78, 17, 9, 'u3', '2026-01-09 22:29:40'),
(79, 17, 7, 'u4', '2026-01-09 22:29:40'),
(80, 18, 8, 'u1', '2026-01-09 22:29:40'),
(81, 18, 9, 'u5', '2026-01-09 22:29:40'),
(82, 18, 7, 'u6', '2026-01-09 22:29:40'),
(83, 18, 8, 'u2', '2026-01-09 22:29:40'),
(84, 19, 9, 'u1', '2026-01-09 22:29:40'),
(85, 19, 10, 'u2', '2026-01-09 22:29:40'),
(86, 19, 8, 'u3', '2026-01-09 22:29:40'),
(87, 19, 9, 'u4', '2026-01-09 22:29:40'),
(88, 20, 10, 'u1', '2026-01-09 22:29:40'),
(89, 20, 10, 'u2', '2026-01-09 22:29:40'),
(90, 20, 10, 'u5', '2026-01-09 22:29:40'),
(91, 20, 9, 'u6', '2026-01-09 22:29:40'),
(92, 21, 9, 'u1', '2026-01-09 22:29:40'),
(93, 21, 9, 'u2', '2026-01-09 22:29:40'),
(94, 21, 10, 'u3', '2026-01-09 22:29:40'),
(95, 21, 8, 'u4', '2026-01-09 22:29:40'),
(96, 22, 9, 'u1', '2026-01-09 22:29:40'),
(97, 22, 10, 'u2', '2026-01-09 22:29:40'),
(98, 22, 8, 'u5', '2026-01-09 22:29:40'),
(99, 22, 9, 'u6', '2026-01-09 22:29:40');

-- indexes
CREATE INDEX idx_comments_movie_id ON comments (movie_id);
CREATE INDEX idx_movies_cat_id ON movies (cat_id);
CREATE INDEX idx_movie_platform_movie_id ON movie_platform (movie_id);
CREATE INDEX idx_movie_platform_platform_id ON movie_platform (platform_id);
CREATE INDEX idx_ratings_movie_id ON ratings (movie_id);

COMMIT;
