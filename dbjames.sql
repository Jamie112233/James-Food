-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2024 at 09:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbjames`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `advertisement_id` int(11) NOT NULL,
  `client` varchar(512) NOT NULL,
  `url` text NOT NULL,
  `image` varchar(512) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(256) NOT NULL,
  `slug_url` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `slug_url`) VALUES
(4, 'Steaks', 'steaks'),
(5, 'Sushis', 'sushis'),
(8, 'Noodles', 'noodles');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `content_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(512) NOT NULL,
  `slug_url` varchar(512) NOT NULL,
  `short_description` text NOT NULL,
  `description` text NOT NULL,
  `image` varchar(512) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`content_id`, `category_id`, `title`, `slug_url`, `short_description`, `description`, `image`, `active`, `created_at`, `updated_at`) VALUES
(1, 5, 'Spicy Tuna Roll', 'spicy-tuna-roll', 'Spicy Tuna Roll. In restaurants, spicy tuna sushi rolls usually involve raw, sushi-grade fish. But if raw fish is out of reach, this recipe uses humble canned tuna', '<h2>Spicy Tuna Roll</h2>\r\n\r\n<p>In restaurants, spicy tuna sushi rolls usually involve raw, sushi-grade fish. But if raw fish is out of reach, this recipe uses humble canned&nbsp;<a href=\"https://www.delish.com/cooking/g57/tuna-recipes/\" target=\"_blank\">tuna</a>. That&rsquo;s not to say you can&rsquo;t use the basic skeleton of this recipe for raw tuna if you&nbsp;<em>can</em>&nbsp;find it&mdash;just substitute in sushi-grade fish, finely chopped, and proceed onward!</p>\r\n\r\n<p>Get the&nbsp;<strong><a href=\"https://www.delish.com/cooking/recipe-ideas/a39996016/spicy-tuna-roll-recipe/\" target=\"_blank\">Spicy Tuna Roll recipe</a></strong>.</p>\r\n\r\n<p><img alt=\"spicy tuna roll with scallions and sesame on top\" src=\"https://hips.hearstapps.com/hmg-prod/images/spicy-tuna-roll-5-1652806800.jpg?crop=0.842xw:1.00xh;0,0&amp;resize=980:*\" style=\"height:449px; width:300px\" /></p>\r\n', 'download (7).jpeg', 1, '2024-03-21 17:15:51', '2024-08-07 06:19:53'),
(2, 5, 'California Roll', 'california-roll', 'California Roll or California maki is an uramaki (inside-out makizushi roll) containing imitation crab (or rarely real crab), avocado, and cucumber.', '<h2>California Roll</h2>\r\n\r\n<p>Just getting into sushi? California rolls are the perfect place to start. No raw fish is required&mdash;just imitation crab, which is typical for this type of roll. All you need is a&nbsp;<a href=\"https://www.amazon.com/BambooMN-Natural-Rolling-Spreader-Utensils/dp/B074SVD5WG/ref=sr_1_5?ots=1&amp;keywords=sushi+mat&amp;qid=1580244668&amp;sr=8-5&amp;linkCode=ogi&amp;tag=delish_auto-append-20&amp;ascsubtag=%5Bartid%7C1782.r.30502281%5Bsrc%7C%5Bch%7C%5Blt%7C\" target=\"_blank\">sushi mat</a>&nbsp;and you&#39;re ready to roll!&nbsp;</p>\r\n\r\n<p>California roll (加州巻き,カリフォルニアロール, kariforunia rōru) or California maki is an uramaki (inside-out makizushi roll) containing imitation crab (or rarely real crab), avocado, and cucumber. Sometimes crab salad is substituted for the crab stick, and often the outer layer of rice is sprinkled with toasted sesame seeds or roe (such as tobiko from flying fish).</p>\r\n\r\n<p>As one of the most popular styles of sushi in Canada and the United States, the California roll has been influential in sushi&#39;s global popularity, and in inspiring sushi chefs around the world to create non-traditional fusion cuisine.[2]</p>\r\n\r\n<p>Get the&nbsp;<strong><a href=\"https://www.delish.com/cooking/recipe-ideas/a30502281/california-roll-sushi-recipe/\" target=\"_blank\">California Roll recipe</a></strong>.</p>\r\n\r\n<p><img alt=\"california rolls delishcom\" src=\"https://hips.hearstapps.com/hmg-prod/images/20200108-seo-california-roll-delish-ehg-9123-jpg-1579900537.jpg?crop=0.787xw:0.843xh;0.0850xw,0.0134xh&amp;resize=980:*\" style=\"height:450px; width:300px\" /></p>\r\n', 'download (8).jpeg', 1, '2024-03-21 17:33:00', '2024-08-07 06:22:05'),
(5, 5, 'Dragon Roll', 'dragon-roll', 'Dragon Roll. Uramaki arguably reaches a peak of extravagance with the dragon roll, with its gorgeous &#34;scales&#34; of thinly sliced mango and avocado and drizzle of spicy sriracha mayo.', '<h2>Dragon Roll</h2>\r\n\r\n<p><em>Uramaki</em>&nbsp;arguably reaches a peak of extravagance with the dragon roll, with its gorgeous &quot;scales&quot; of thinly sliced mango and avocado and drizzle of&nbsp;<a href=\"https://www.delish.com/cooking/recipe-ideas/a39531023/spicy-mayo-recipe/\" target=\"_blank\">spicy sriracha mayo</a>. The roll is wrapped in classic vinegar-seasoned&nbsp;<a href=\"https://www.delish.com/cooking/recipe-ideas/a34151057/sushi-rice-recipe/\" target=\"_blank\">rice</a>&nbsp;and filled with crispy shrimp&nbsp;<a href=\"https://www.delish.com/cooking/recipe-ideas/a31750708/tempura-batter-recipe/\" target=\"_blank\">tempura</a>, a mix of buttery avocado, and crunchy cucumber.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><img alt=\"dragon roll sushi\" src=\"https://hips.hearstapps.com/hmg-prod/images/dragon-roll3-1655475756.jpg?crop=0.889xw:1.00xh;0.0255xw,0&amp;resize=980:*\" style=\"height:525px; width:350px\" /></p>\r\n', 'download (9).jpeg', 1, '2024-03-22 00:48:10', '2024-08-07 06:23:34'),
(7, 8, 'Hokkien noodles', 'hokkien-noodles', 'Hokkien noodles. I know that I’ve repeatedly bleated about how you can throw “any vegetables you want!”', '<p>My everyday Hokkien Noodles recipe. A great sauce. Strips of chicken. Lots of vegetables. 6 minute cook. Dinner in 20 minutes flat!</p>\r\n\r\n<p><img alt=\"Close up photo of Hokkien noodles with chicken\" src=\"https://www.recipetineats.com/tachyon/2024/08/Hokkien-noodles-with-chicken_2.jpg\" style=\"height:500px; width:400px\" /></p>\r\n\r\n<h2>Hokkien noodles</h2>\r\n\r\n<p>I know that I&rsquo;ve repeatedly bleated about how you can throw &ldquo;any vegetables you want!&rdquo; into&nbsp;<a href=\"https://www.recipetineats.com/category/noodles/\">stir fried noodles</a>. But if speed is of the essence, and you want a good amount of vegetables in your dinner, you do actually need to think about what vegetables are quick to chop, quick to cook, won&rsquo;t make your stir fry watery, and you know you can get at any grocery store.</p>\r\n\r\n<p>No pre-cooking required (like broccoli). Doesn&rsquo;t take 5 minutes to soften (like mushrooms). And not one of those vegetables you can&rsquo;t always find (like Asian greens).</p>\r\n', 'download (11).jpeg', 1, '2024-03-27 07:53:05', '2024-08-07 05:59:57'),
(8, 8, 'Garlic noodles', 'garlic-noodles', 'Garlic noodles. This is yet another example of an excellent fusion Asian dish that brings together Asian and Western ingredients to create something incredibly tasty', '<p>These extraordinarily delicious, yet simple Garlic Noodles are a fusion Asian dish made famous by&nbsp;<a href=\"http://www.thanhlongsf.com/\" target=\"_blank\">Thanh Long restaurant</a>&nbsp;in San Francisco, via&nbsp;<a href=\"https://www.youtube.com/watch?v=wK9OHVxB_Z8\" target=\"_blank\">Kenji Lopez-Alt</a>. Top with a fried egg and vegetables for a quick meal. Also makes an excellent Asian side dish for &ldquo;anything&rdquo;!</p>\r\n\r\n<p><img alt=\"Bowl of Garlic Noodles with fried egg and broccolini\" src=\"https://www.recipetineats.com/tachyon/2023/09/Garlic-noodles-with-egg-close-up.jpg\" style=\"height:500px; width:400px\" /></p>\r\n\r\n<h2>Garlic noodles</h2>\r\n\r\n<p>This is yet another example of an excellent fusion Asian dish that brings together Asian and Western ingredients to create something incredibly tasty. Big garlic flavours &ndash; with a secret ingredient:&nbsp;<strong>parmesan.&nbsp;</strong>Yes, you read that right! Parmesan. Mixed with Asian sauces (oyster, fish sauce and Maggi seasoning or soy sauce) and a stack of garlic, it adds a punch of savoury flavour when it melts into the sauce. And it goes amazingly well with the Asian flavours!</p>\r\n', 'download (12).jpeg', 1, '2024-03-27 07:54:54', '2024-08-07 06:05:19'),
(9, 8, 'Beef chow mein', 'beef-chow-mein', 'Beef chow mein. Here’s something new to try with that packet of beef mince you throw into your shopping cart every week!', '<p>Here&rsquo;s a great beef mince recipe for you that&rsquo;s quick to make, economical and full of hidden vegetables so it&rsquo;s a complete meal &ndash; Beef Chow Mein! It&rsquo;s the&nbsp;beef version of everybody&rsquo;s favourite&nbsp;<a href=\"https://www.recipetineats.com/chicken-chow-mein/\">Chicken Chow Mein</a>, made with the convenience of ground beef.</p>\r\n\r\n<p><img alt=\"Bowl of Beef chow mein ready to be eaten\" src=\"https://www.recipetineats.com/tachyon/2023/07/Beef-chow-mein_5.jpg\" style=\"height:500px; width:400px\" /></p>\r\n\r\n<h2>Beef chow mein</h2>\r\n\r\n<p>Here&rsquo;s something new to try with that packet of beef mince you throw into your shopping cart every week! The beef is stir fried with chow mein sauce until it&rsquo;s beautifully caramelised then tossed in a tangle of noodles and vegetables.</p>\r\n\r\n<p>A neat trick in today&rsquo;s recipe is to scramble up an egg with the beef. It makes the beef bits stick to the noodles better, with the added bonus of upping the protein.</p>\r\n', 'download (13).jpeg', 1, '2024-03-27 07:58:06', '2024-08-07 06:04:31'),
(10, 4, 'Surf and Turf', 'surf-and-turf', 'Celebrate both the land and the sea with this impressive steak and shrimp dinner. It&#39;s part seared filet mignon and part creamy, lemony shrimp—both of which can be served over mashed potatoes.', '<h2>Surf and Turf</h2>\r\n\r\n<p>Celebrate both the land and the sea with this impressive steak and shrimp dinner. It&#39;s part seared filet mignon and part creamy, lemony shrimp&mdash;both of which can be served over mashed potatoes.</p>\r\n\r\n<p><img alt=\"surf and turf\" src=\"https://hips.hearstapps.com/hmg-prod/images/steak-dinner-recipes-surf-and-turf-660b0096c640a.jpeg?crop=0.8xw:1xh;center,top&amp;resize=980:*\" style=\"height:438px; width:350px\" /></p>\r\n\r\n<p>What&#39;s more extravagant and luxurious than&nbsp;a really good surf and turf meal? This delightful pairing of land and sea features a perfectly seared&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/recipes/a34226596/bacon-wrapped-filets-with-cowboy-butter/\">filet mignon</a>&nbsp;alongside succulent&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/meals-menus/g32880437/shrimp-recipes/\">shrimp</a>&nbsp;swimming in a creamy, lemony sauce. This&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/meals-menus/g35191871/steak-dinner-recipes/\">steak dinner</a>&nbsp;would make a truly special&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/meals-menus/g32084340/family-meal-ideas/\">family supper</a>&nbsp;for Christmas Eve or New Year&#39;s Eve, or a&nbsp;dinner party main dish anytime of year. It can even be cut in half for an intimate&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/meals-menus/g35098731/romantic-valentines-day-dinners/\">Valentine&rsquo;s Day dinner</a>! Play up the steakhouse theme and serve this surf and turf meal with&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/recipes/a12083/delicious-creamy-mashed-potatoes/\">creamy mashed potatoes</a>,&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/recipes/a38985039/sauteed-asparagus-recipe/\">sauteed asparagus</a>, and&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/recipes/a38816776/chocolate-lava-cake-recipe/\">chocolate lava cake</a>!</p>\r\n', 'download.jpeg', 1, '2024-03-27 08:16:07', '2024-08-07 06:30:28'),
(11, 4, 'Philly Cheesesteak Sliders', 'philly-cheesesteak-sliders', 'Cheez whiz lovers, these Philly cheesesteak sliders are for you! Anybody that turns their nose up to &#39;whiz on a sandwich will find their lives', '<h2>Philly Cheesesteak Sliders</h2>\r\n\r\n<p>What&#39;s the trick to making the best Philly cheesesteak? Stick your ribeye in the freezer! It&#39;ll make it so much easier to slice. Once it&#39;s cut as thinly as possible into shavings, the steak is seared in a skillet with chopped onions for an extra layer of flavor.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><img alt=\"philly cheesesteak sliders\" src=\"https://hips.hearstapps.com/hmg-prod/images/steak-dinner-recipes-philly-cheesesteak-sliders-660b015ed45ec.jpeg?crop=0.8xw:1xh;center,top&amp;resize=980:*\" style=\"height:438px; width:350px\" /></p>\r\n\r\n<p>Cheez whiz lovers, these Philly cheesesteak sliders are for you! Anybody that turns their nose up to &#39;whiz on a sandwich&nbsp;will find their lives transformed when they take a bite of a slider stacked with griddled&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/recipes/a9744/pan-fried-ribeye-steak-heaven-in-a-skillet/\">ribeye</a>, golden onions, and plenty of&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/recipes/a46000903/homemade-cheez-whiz-recipe/\">homemade&nbsp;Cheez Whiz</a>. These party&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/meals-menus/g43962850/sandwich-ideas/\">sandwiches</a>&nbsp;follow the preferences of Philly purists by keeping things simple. Leave the lettuce, tomato, and mayonnaise in the refrigerator and get ready to bite into Philly&rsquo;s new favorite slider!</p>\r\n\r\n<p><strong>What goes into Philly cheesesteak sliders?</strong></p>\r\n\r\n<p>Bread: These pull-apart sliders were made to closely resemble a classic Philly cheesesteak. But instead of a crusty hoagie roll softened over steaming griddled meat, here, un-toasted dinner rolls get to soak up any residual beef and onion juices while the sliders bake. They are also brushed with a garlicky Worcestershire butter for even more flat-top flavor.</p>\r\n\r\n<p>&nbsp;</p>\r\n', 'download (1).jpeg', 1, '2024-03-27 08:25:25', '2024-08-07 06:32:45'),
(12, 4, 'Salisbury Steak', 'salisbury-steak', 'From casseroles to burgers to meatballs, there’s no denying that ground beef is the modern family’s favorite protein. But look back in time, and it’s clear to see that ground beef has always been in style!', '<h2>Salisbury Steak</h2>\r\n\r\n<p>While it&#39;s not technically a steak, this old-school dish made with ground beef is meant to mimic the look of an elegant steak&mdash;mushroom gravy and all! It&#39;s extremely comforting, especially when served with whipped mashed potatoes and traditional peas.</p>\r\n\r\n<p><img alt=\"the pioneer woman\'s salisbury steak recipe\" src=\"https://hips.hearstapps.com/hmg-prod/images/salisbury-steak-001-preview-64b713e8b1207.jpg?crop=1xw:1xh;center,top&amp;resize=640:*\" style=\"height:350px; width:350px\" /></p>\r\n\r\n<p>From&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/meals-menus/g37358432/ground-beef-casserole-recipes/\">casseroles</a>&nbsp;to&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/meals-menus/g40104401/burger-recipes/\">burgers</a>&nbsp;to&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/meals-menus/g41316128/meatball-recipes/\">meatballs</a>, there&rsquo;s no denying that&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/meals-menus/g32174716/ground-beef-recipes/\">ground beef</a>&nbsp;is the modern family&rsquo;s favorite protein. But look back in time, and it&rsquo;s clear to see that ground beef has always been in style! The perfect example? Salisbury steak. This vintage&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/meals-menus/g32933285/comfort-food-recipes/\">comfort food</a>&nbsp;is sure to warm up the dinner table today like it did in years long past. And with the whole meal being cooked in one pan, it&rsquo;s no wonder why it&#39;s a family favorite!</p>\r\n\r\n<p><strong>Where did the Salisbury steak come from?</strong></p>\r\n\r\n<p>Created by James H. Salisbury in 1897, the Salisbury steak was originally designed to be a health food! Salisbury was a physician and chemist who advocated for a meat-centered diet to promote gut health. And while some of Salisbury&rsquo;s theories didn&rsquo;t quite stand the test of time, there&rsquo;s no denying that Salisbury steak surely made its impact.</p>\r\n', 'download (2).jpeg', 1, '2024-03-27 08:26:48', '2024-08-07 06:36:13'),
(13, 4, 'Chile-Lime Steak Salad', 'chile-lime-steak-salad', 'For the steak: Whisk the fish sauce, soy sauce, brown sugar and pepper in a small bowl until the sugar is dissolved.', '<h2>Chile-Lime Steak Salad</h2>\r\n\r\n<p>When the weather is hot, sometimes all you want is a crisp, refreshing salad&mdash;topped with steak, of course. Here, the steaks are marinaded in a mixture of fish sauce, soy sauce, brown sugar and pepper for extra flavor.</p>\r\n\r\n<p><img alt=\"chile lime steak salad\" src=\"https://hips.hearstapps.com/hmg-prod/images/steak-dinner-recipes-chile-lime-steak-salad-660b039d28fbc.jpeg?crop=0.805xw:1.00xh;0.105xw,0&amp;resize=980:*\" /></p>\r\n\r\n<p>For the steak: Whisk the fish sauce, soy sauce, brown sugar and pepper in a small bowl until the sugar is dissolved. Prick the steak all over with a fork and put in a resealable plastic bag. Add the fish sauce mixture, seal the bag and massage the marinade into the meat for a few minutes. Let marinate 15 minutes.</p>\r\n\r\n<p>Meanwhile, for the dressing: Whisk the lime juice, fish sauce, brown sugar, chile and garlic in a small bowl until the sugar is dissolved.</p>\r\n', 'download (4).jpeg', 1, '2024-03-27 08:28:15', '2024-08-07 06:40:01'),
(14, 4, 'Cowboy Steak', 'cowboy-steak', 'What&#39;s a cowboy steak? The thick-cut, bone-in ribeye is known for its butchering technique known as “frenching,&#34; in which some of the bone is left exposed.', '<h2>Cowboy Steak</h2>\r\n\r\n<p>What&#39;s a cowboy steak? The thick-cut, bone-in ribeye is known for its butchering technique known as &ldquo;frenching,&quot; in which some of the bone is left exposed. If that weren&#39;t impressive enough, there&#39;s also a ranch-flavored butter melted over the top!</p>\r\n\r\n<p><img alt=\"steak dinner recipes cowboy steak\" src=\"https://hips.hearstapps.com/hmg-prod/images/steak-dinner-recipes-cowboy-steak-6441597c7988c.jpeg?crop=0.403xw:1.00xh;0.344xw,0&amp;resize=980:*\" style=\"height:434px; width:350px\" /></p>\r\n\r\n<p>Treat someone special to a cowboy steak one summer&rsquo;s evening! If this cut of beef sounds new and unfamiliar, it&rsquo;s actually a type of steak that you&rsquo;ve probably cooked before. A cowboy steak is an extra-thick-cut, bone-in ribeye steak with some of the bone left exposed. The bone is carefully cleaned, a technique called &ldquo;frenching,&quot; for an impressive presentation. This&nbsp;<a href=\"https://www.thepioneerwoman.com/ree-drummond-life/a35462575/ree-drummond-date-night-dinner-menu/\">special occasion dish</a>&nbsp;is perfect for a&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/meals-menus/g32174441/fourth-of-july-menu-ideas/\">Fourth of July menu,</a>&nbsp;or an over-the-top&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/meals-menus/g35589850/mothers-day-dinner-ideas/\">Mother&rsquo;s Day</a>&nbsp;or&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/meals-menus/g36109352/fathers-day-dinner-recipes/\">Father&rsquo;s Day</a>&nbsp;dinner.&nbsp;</p>\r\n\r\n<p><strong>What is the best way to cook a cowboy steak?&nbsp;</strong></p>\r\n\r\n<p>A cowboy steak is one of the&nbsp;<a href=\"https://www.thepioneerwoman.com/food-cooking/cooking-tips-tutorials/g38919544/best-steaks-for-grilling/\">best steaks for grilling</a>! Because this cut needs both a hot sear and some time to roast, it spends a good amount of time in the grill&rsquo;s smoky haven to pick up lots of flavor. (A charcoal grill is ideal, but a gas grill works just as well).&nbsp;For cooking indoors, a cast-iron pan is best. A 12-inch cast-iron skillet, however, will fit just one of these steaks and you&#39;ll need to&nbsp;carefully wipe the skillet out in between steaks. You could also&nbsp;heat two skillets side-by-side to cook both at the same time or simply cut the recipe in half. Because this steak is well-marbled, it will render a lot of fat and could create some smoke inside. Spoon excess fat out of the skillet and into a heatproof bowl if too much accumulates, and be sure to keep the hood vent over the stove&nbsp;turned on!</p>\r\n', 'download (5).jpeg', 1, '2024-03-27 08:30:10', '2024-08-07 06:43:06');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(512) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(512) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `username`, `password`, `created_at`, `updated_at`, `role_id`) VALUES
(1, 'Administrator', 'admin', '$2y$10$n633dy1ZabfaSaz6.tACrurMSIcF8J9/tceCK3PaecgDUdsOynE2W', '2024-03-21 05:04:04', '2024-08-07 07:16:50', 1),
(3, 'James Chibuike', 'james@gmail.com', '$2y$10$n633dy1ZabfaSaz6.tACrurMSIcF8J9/tceCK3PaecgDUdsOynE2W', '2024-03-28 04:50:14', '2024-08-07 07:17:16', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`advertisement_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `content_id` (`content_id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`content_id`),
  ADD KEY `menu_id` (`category_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `advertisement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `content_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `contents` (`content_id`);

--
-- Constraints for table `contents`
--
ALTER TABLE `contents`
  ADD CONSTRAINT `contents_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
