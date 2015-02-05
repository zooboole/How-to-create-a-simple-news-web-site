<?php require __DIR__.'/includes/functions.php' ?>
<html>
<head>
	<title>Welcome to news channel</title>
	<link rel="stylesheet" type="text/css" href="design/style.css">
</head>
<body>
	<div class="container">

		<div class="welcome">
			<h1>Latest news</h1>
			<p>Welcome to the demo news site. <em>We never stop until you are aware.</em></p>
			<p> <a href="http://phpocean.com/tutorials/back-end/create-a-simple-news-site-with-php-and-mysql-using-pdo/11">Return to tutorial</a> </p>
		</div>
		<div class="add-top"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
      <!-- Header-Bottom-Banner  -->
     <ins class="adsbygoogle"
          style="display:inline-block;width:728px;height:90px"
          data-ad-client="ca-pub-5908879638690497"
          data-ad-slot="2658449769"></ins>
     <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
     </script></div>
		<div class="news-box">

			<div class="news">
				<?php
					// get the database handler
					$dbh = connect_to_db(); // function created in dbconnect, remember?
					// Fecth news
					$news = fetchNews($dbh);
				?>

				<?php if ( $news && !empty($news) ) :?>

				<?php foreach ($news as $key => $article) :?>
				<h2><a href="read-news.php?newsid=<?= $article->news_id ?>"><?= stripslashes($article->news_title) ?></a></h2>
				<p><?= stripslashes($article->news_short_description) ?></p>
				<span>published on <?= date("M, jS  Y, H:i", $article->news_published_on) ?> by <?= stripslashes($article->news_author) ?></span>
				<?php endforeach?>

				<?php endif?>
			</div>

		</div>

		<div class="footer">
			phpocean.com &copy; <?= date("Y") ?> - all rights reserved.
		</div>

	</div>
</body>
</html>