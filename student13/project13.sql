-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2014 at 07:30 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `project13`
--

-- --------------------------------------------------------

--
-- Table structure for table `tpm_acoustic`
--

CREATE TABLE IF NOT EXISTS `tpm_acoustic` (
  `id` varchar(20) NOT NULL,
  `manu_name` varchar(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  `model` varchar(50) NOT NULL,
  `color` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `thumb_desc` varchar(70) NOT NULL,
  `descript` varchar(2000) NOT NULL,
  `image` varchar(30) NOT NULL,
  `image_large` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tpm_acoustic`
--

INSERT INTO `tpm_acoustic` (`id`, `manu_name`, `type`, `model`, `color`, `price`, `thumb_desc`, `descript`, `image`, `image_large`, `quantity`) VALUES
('GBS7621', 'Gibson', '6 String', 'Hummingbird Pro', 'Sunburst', '1499.99', 'A high quality Gibson acoustic', 'A true legend of acoustic instruments, the Gibson Hummingbird Pro acoustic-electric guitar was first introduced in 1960 as Gibson''s earliest square-shoulder dreadnought guitar. Superb for all styles of playing, with a capacity for rich, first-position folk chords and intricate solo playing, the Gibson Hummingbird quickly became an iconic acoustic line, and a favorite among legends like Gram Parsons and Keith Richards. The Hummingbird has been one of Gibson Acoustic''s most enduring guitar line. Includes case.', 'acGibH.jpg', 'lgHumm.png', 999),
('MTN1445', 'Martin', '6 string', '15 Series 00015M', 'Dark Mahogany Stain', '1249.00', 'A high quality Martin acoustic', 'The Martin 00015M offers a perfect blend of vintage features and modern technology to create an acoustic guitar of exceptional tone, playability and classic Martin good looks. Back, sides and top are bookmatched solid genuine mahogany with A-frame Sitka Spruce X-bracing and a traditional maple bridge-plate. The 14-fret neck, also genuine mahogany, is joined with an East Indian rosewood fingerboard and headplate. The traditional rosewood belly-bridge is fitted with a bone saddle, and the nut is fashioned from bone as well. ', 'acMtn15.jpg', 'lgMtn15.png', 999),
('MTN7854', 'Martin', '6 String', 'Custom D Classic', 'Natural', '899.99', 'High quality steel string', 'This Martin Custom D Classic Acoustic Guitar is the real deal and a real steal. Crafted with a solid Sitka spruce top and solid mahogany back and sides, the mahogany Custom D guitar also has a tortoise binding, modified low-oval mahogany neck with a mortise-and-tenon joint and adjustable truss rod. Solid Indian rosewood fingerboard and bridge, and inlaid single-ring rosette complete this Martin guitar. The mahogany body on the Custom D guitar is much lighter in weight than rosewood, koa, or maple and yields a surprisingly strong, loud sound with an emphasis on clear, bright, airy trebles. Quantities of this custom Martin acoustic guitar are limited. Includes case.', 'acMtnD.jpg', 'lgMtnD.png', 999),
('PRS1244', 'PRS', '6 String', 'SE Angelus Custom', 'Natural', '859.99', 'High quality, custom model', 'The PRS SE Angelus Custom model is made with high-quality components, including a solid back and solid spruce top, a bone nut and saddle, and PRS SE designed tuners. This model comes with the PRS pickup system, highlighting the instrument''s rich, resonant, and responsive tone. The PRS pickup system is an under-saddle design with three bands of EQ and an anti-feedback loop. The pickup uses one 9-volt battery for power.', 'acPRS.jpg', 'lgPRS.png', 999),
('SEA7415', 'Seagull', '12 String', 'Coastline Series S12', 'Natural Semi-gloss', '629.00', 'A rich, full sounding acoustic', 'The Seagull Coastline S12 is a 12-String acoustic-electric dreadnought guitar with rich, full sound that only a quality 12-string guitar can produce. With a warm mid-range tone and of course, the award winning craftsmanship and value for which Seagull is known, the S12 features an ultra thin semi-gloss finish, a select pressure tested solid cedar top, and built-in Godin QI electronics that let you hit the stage fully loaded for any venue.', 'acSea.jpg', 'lgSea.png', 999),
('TLR4568', 'Taylor', '6 String', '214ce Rosewood', 'Natural', '999.99', 'Acoustic-Electric with a fine gloss finish', 'Taylor''s most popular and versatile body shape, the mid-size Grand Auditorium arrived in 1994 bearing refined proportions that fell between a Dreadnought and Grand Concert. While the bigger Dreadnought was traditionally considered a flatpicker''s guitar and the smaller Grand Concert catered to finger-stylists, the GA was designed to deliver on both fronts. The shape produced an original acoustic voice that was big enough to handle medium-strength picking and strumming, yet with impressive balance across the tonal spectrum, especially in the midrange, producing clear, well-defined notes that suited both strumming and fingerstyle playing. The GA''s overall presence tracks well with other instruments both in a studio mix and on stage, and singer-songwriters have embraced its utility both for composing and traveling with one guitar. Many people want a single guitar that can cover a variety of styles, which is why the GA continues to be Taylor''s bestselling shape. If you want a great all-purpose guitar, the multi-dimensional GA won''t let you down.', 'acTay.jpg', 'lgTay.png', 999);

-- --------------------------------------------------------

--
-- Table structure for table `tpm_basses`
--

CREATE TABLE IF NOT EXISTS `tpm_basses` (
  `id` varchar(20) NOT NULL,
  `manu_name` varchar(50) NOT NULL,
  `type` varchar(30) NOT NULL,
  `model` varchar(50) NOT NULL,
  `color` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `thumb_descript` varchar(100) NOT NULL,
  `descript` varchar(2000) NOT NULL,
  `image` varchar(30) NOT NULL,
  `image_large` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bass_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tpm_basses`
--

INSERT INTO `tpm_basses` (`id`, `manu_name`, `type`, `model`, `color`, `price`, `thumb_descript`, `descript`, `image`, `image_large`, `quantity`) VALUES
('EB4117', 'Music Man', '4 String', 'StingRay', 'Sunburst', '1050.00', 'A classic StingRay bass guitar', 'Few 4-string electric basses are as flat-out cool or have as much powerful tone as the MusicMan StingRay. Introduced in 1976, the StingRay was the first bass to feature active electronics and advancements such as a 6-bolt neck joint, contoured body, superior neck truss-rod system, and rock-solid bridge, all of which keep it on top of the pack. The StingRay is constructed with a select hardwood body, maple neck, Schaller BM tuners, 3-band active EQ, and MusicMan humbucking pickup.', 'baSting.jpg', 'lgSting.png', 998),
('EPH9874', 'Epiphone', '4 String', 'Allen Woody', 'Cherry', '399.99', 'An excellent Epiphone bass guitar', 'The Epiphone Allen Woody Limited Edition Bass is easy to get around on. Woody, who passed away in August 2000, was an avid collector of vintage Epiphone basses, and this signature model combines the features that Woody found best for playing in his aggressive, rumbling style. It has a semi-hollow, single-cutaway, archtop body similar to that of Epiphone''s Kat series but without f-holes. The neck is a 30" short scale for easier play. Mini-humbucking pickups in the neck and middle position deliver a warm tone. Wine red finish with gold hardware. Case sold separately.', 'baEpi.jpg', 'lgEpi.png', 999),
('FDR4136', 'Fender', '4 String', 'American Deluxe Jazz', 'Black', '999.99', 'A high quality Jazz Bass from Fender', 'The Fender American Deluxe Jazz Bass delivers the modern features that bassists demand in a sleek, familiar electric bass. An updated preamp circuit puts out deeply exhilarating active and passive tone. Powerful-yet-quiet N3 Noiseless pickups present more clean headroom, extended EQ range, and plenty of sublime tonal options, thanks to the active/passive toggle switch and passive tone control. The classic Fender Jazz Bass block fretboard inlays and binding give the Deluxe Jazz Bass extra touches of class.', 'baFenJz.jpg', 'lgFenJz.png', 999),
('FDR4152', 'Fender', '4 String', 'American Precision', '3-Color Sunburst', '999.99', 'Classic Fender Precision Bass', 'Fender introduced the American Standard Precision Bass guitar in 1951, and its thick sound and indestructible build changed popular music. Today''s American Standard Precision Bass is the new version of one of the greatest instruments of bass heroes past and present; the modern interpretation of Fender''s classic instrument refined for modern players.<br><br>\r\n\r\nThe American Standard Precision Bass has a high-mass vintage bridge, Fender Custom Shop ''60s Precision Bass split single-coil pickup, thinner finish undercoat that lets the body breathe and improves resonance, improved Fender tuners that are 30 percent lighter while keeping the classic look, richer and deeper neck tint for a more elegant appearance, great-looking gloss rosewood fingerboard, satin neck back for smooth playability and Fender Tolex case.', 'baFenP.jpg', 'lgFenP.png', 999),
('FDR7855', 'Fender', '4 String', 'Pawn Shop Mustang', 'Red', '799.99', 'A classic Fender Mustang bass', 'Players continue to praise the Pawn Shop Series for evoking the more eccentric Fender creations of the mid-''60s to mid-''70s while delivering thoroughly modern sound and quality with a wealth of various Fender elements. New for 2013, the Pawn Shop Series introduces yet another pleasingly unconventional assortment of "guitars that never were but should have been," in which new models take the stage and long-vanished classics return in modern form.<br><br>\r\n\r\nWith its racing stripe and short scale (30"), the new Pawn Shop Mustang Bass authentically evokes the original "competition" Mustang Bass of the early 1970s. This time, though, you get the huge bass sound of a single humbucking pickup (the original had a single-coil pickup) and several classic Fender finish options.', 'baFenPwn.jpg', 'lgFenPwn.png', 999),
('STN4512', 'Steinberger', '4 String', 'XT-2DB', 'Black', '449.00', 'A fine quality Steinberger', 'The Spirit XT-2DB 4-string bass guitar is a breakthrough for contemporary bassists. It features the patented Steinberger DB-Tuner, which extends the range of the E-string from Eb to B. This electric bass also features a 3-piece, hard maple thru-neck construction with the patented Steinberger Double-Ball Tuning System that provides rock-solid tuning, stability, and tone. At only 38.50" in length, it is among the most travel-friendly and convenient full-size, professional basses on the market.', 'baStn.jpg', 'lgStn.png', 999);

-- --------------------------------------------------------

--
-- Table structure for table `tpm_customers`
--

CREATE TABLE IF NOT EXISTS `tpm_customers` (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `credit_card` varchar(30) NOT NULL,
  `credit_card_num` varchar(19) NOT NULL,
  `security_code` varchar(3) NOT NULL,
  `address` varchar(50) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(2) NOT NULL,
  `zip_code` int(5) NOT NULL,
  `phone_number` varchar(10) NOT NULL,
  PRIMARY KEY (`cust_id`),
  UNIQUE KEY `cust_id` (`cust_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `tpm_customers`
--

INSERT INTO `tpm_customers` (`cust_id`, `username`, `password`, `first_name`, `last_name`, `credit_card`, `credit_card_num`, `security_code`, `address`, `city`, `state`, `zip_code`, `phone_number`) VALUES
(25, 'tpmetiv1@svsu.edu', 'ae42760ef71e07cdc78c21849b44551816bda917', 'Tyler', 'Metiva', 'MasterCard', '5555555555555555', '123', '5555 Road St.', 'Saginaw', 'MI', 48704, '9895555555');

-- --------------------------------------------------------

--
-- Table structure for table `tpm_guitars`
--

CREATE TABLE IF NOT EXISTS `tpm_guitars` (
  `id` varchar(20) NOT NULL,
  `manu_name` varchar(50) NOT NULL,
  `type` varchar(30) NOT NULL,
  `model` varchar(50) NOT NULL,
  `color` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `thumb_descript` varchar(100) NOT NULL,
  `descript` varchar(2000) NOT NULL,
  `image` varchar(30) NOT NULL,
  `image_large` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guitar_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tpm_guitars`
--

INSERT INTO `tpm_guitars` (`id`, `manu_name`, `type`, `model`, `color`, `price`, `thumb_descript`, `descript`, `image`, `image_large`, `quantity`) VALUES
('EPH4162', 'Epiphone', '6 String / 12 String', 'G-1275 Double Neck', 'Cherry', '999.99', 'A reissue of the classic', 'The original "1235" doubleneck made at the legendary Kalamazoo, Michigan factory was introduced in 1963 and quickly become one of the most sought-after guitars in rock. Elvis Presley, bluesman Earl Hooker, Pete Townshend of The Who and John McLaughlin are just a few who caught on quickly to the "1235" and found it a stunning way to make an entrance on stage and on record. But the "1235" is primarily recognized as the guitar Jimmy Page used in countless Led Zeppelin concerts and recording sessions, most notably on the classic "Stairway to Heaven." A vintage "1235" or "SG double neck" is hardly ever seen in the wild and has been known primarily a custom-made guitar mostly seen in the hands of rock''s elite. Until now.', 'dneck.jpg', 'lgDneck.png', 999),
('EPH5974', 'Epiphone', '6 String', 'ES-175', 'Natural', '899.99', 'A faithful reissue of the Gibson ES-175', 'Epiphone is proud to present the Epiphone ES-175 Premium, a faithful new reissue of what many consider the most famous electric archtop in popular music, heard on classics from jazz to rock in the hands of pioneers like Joe Pass, Scotty Moore, and Wes Montgomery. Epiphone''s long history of innovation began with designing archtop guitars. And now, Epiphone steps up its game again with the new ES-175 Premium featuring Gibson USA ''57 Classic pickups and a vintage-inspired "aged" lacquer finish that will make you think you''re playing a 50''s original but at a price anyone can afford.', 'ES175.jpg', 'lgES175.png', 999),
('FNDR4412', 'Fender', '6 String', 'American Stratocaster', 'Sunburst', '1029.99', 'The classic Fender Stratocaster', 'The Fender American Deluxe Stratocaster Ash has the distinctive grain pattern and spanking tone of an ash body, with all the cool specs of the American Deluxe Stratocaster. The compound radius fingerboard allows effortless string bending anywhere along the neck. N3 Noiseless pickups provide improved Stratocaster tones for sparkling bell-like chime with no hum, and reconfigured S-1 switching offers even more distinctive tonal options. Other features include staggered locking tuners, two-point synchronized American Deluxe tremolo bridge with pop-in arm, and beveled neck heel.', 'strat.jpg', 'lgStrat.png', 999),
('GBS4123', 'Gibson', '6 String', 'Les Paul Traditional', 'Sunburst', '1999.99', 'Gibson quality at an affordable price', 'The Gibson Les Paul Traditional 2014 Electric Guitar celebrates Gibson''s 120th Anniversary in style. It has a mahogany body without weight relief and an AA figured maple top. The mahogany neck has a late ''50s thickness. The bound rosewood fingerboard has trapezoid inlays and sports a 120th Anniversary banner at the 12th fret. Other key features include ''59 Tribute Humbuckers with orange drop capacitors, max grip speed knobs, TonePros vintage-style tuners, Graph Tech nut and chrome tune-o-matic bridge.', 'lespaul.jpg', 'lgLesPaul.png', 999),
('GBS4567', 'Gibson', '6 String', 'SG Standard', 'Cherry', '1899.99', 'A classic Gibson SG', 'The Gibson SG Standard 2014 Electric Guitar celebrates Gibson''s 120th Anniversary in style. It has a mahogany body in a variety of colors with a slim, SG profile neck. The rosewood fingerboard has undercut frets over the binding for extra playing surface and sports a 120th Anniversary banner at the 12th fret. Other key features include 57 classic pickups with coil-splitting, max grip speed knobs, the Min-ETune system, Graph Tech nut and chrome tune-o-matic bridge. Includes hardshell case.', 'sg.jpg', 'lgSg.png', 999),
('IBZ7789', 'Ibanez', '6 String', 'RG 655 Prestige ', 'Cobalt Blue', '1199.99', 'An excellent lightweight electric', 'The Ibanez RG655 Prestige is a lightweight electric guitar that features a basswood body and the incredible Ibanez Super Wizard 5-piece neck for fast and effortless shredding. The 24-fret rosewood fingerboard is dressed with dot inlays and has an ultraflat 16.92" radius that makes two-hand tapping effortless.<br><br>\r\n\r\nThe RG655 guitar''s Ibanez Edge bridge features stud lock function, it offers tight tone, and good tuning stability allowing you to perform the most radical of trem effects with flawless tuning accuracy.<br><br>\r\n\r\n\r\nIbanez includes a hardshell case with the RG655 Prestige electric. ', 'prstg.jpg', 'lgPrstg.png', 999);

-- --------------------------------------------------------

--
-- Table structure for table `tpm_shopping_cart`
--

CREATE TABLE IF NOT EXISTS `tpm_shopping_cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` int(11) NOT NULL,
  `item_id` varchar(20) NOT NULL,
  `item_brand` varchar(50) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `item_color` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
