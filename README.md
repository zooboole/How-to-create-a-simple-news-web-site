#Create a simple news site with PHP and MySQL using PDO


[Introduction](#introduction)

[What will you need?](#what-will-you-need)

[Structure of the site](#structure-of-the-site)

[Database model and structure of tables](#database-model-and-structure-of-tables)

[Files & folders structure](#files-folders-structure)

[Files content (codes)](#files-content)

[Room for improvement](#room-for-improvement)

[Conclusion](#conclusion)

<a name="introduction" class="anchor" href="#introduction"></a>
[Introduction](#introduction)
--------------------

This tutorial is an update of the existing tutorial on how to create a very simple news site using php and mysql. The aim of this tutorial is to help you understand some basic concepts when using php and mysql. It's also a way for beginner to learn how to put together a full project using dynamic programming concepts and databases.

[View the demo here](http://phpocean.com/demo/create-a-simple-news-site-with-php-and-mysql-with-pdo/index.php)


<a name="what-will-you-need" class="anchor" href="#what-will-you-need"></a>
[What will you need ?](#what-will-you-need)
--------------------
For this project you will simply need a web server with a database to run the site. By web server I mean you should have Apache Server, MySql Server, and PHP. For those who are on windows you can easily install [WAMP](http://www.wampserver.com/en/), or [XAMP](https://www.apachefriends.org/index.html). 

And those on Linux Systems can use LAMP or [MAMP](http://www.mamp.info/en/) for Mac OS users. Another way to have all this is to have a virtual web server. You can install one yourself using VMware or Oracle VM VirtualBox. For those who are advanced in web servers stuffs, I believe [Vagrant](https://www.vagrantup.com/) will be the best for you.

At the end of the day, what matters is for you to have a running server that has php and a database that you can connect to.

Since PHP 5.5.0, the mysql extension is deprecated I will be using PDO. So if you don't have PDO extension activated you can do it now. Or if could also use Mysqli instead. I have decided to use PDO to make the tutorial more universal because in the first version of it, many people had problems with mysql and were encounters a lot of errors.




<a name="structure-of-the-site" class="anchor" href="#structure-of-the-site"></a>
[Structure of the site](#structure-of-the-site)
--------------------

Here I will be creating a very simple website that will display a list of our  recent news posts ordered by date.

For each post we'll be displaying its title, short description, the date of publication and the author's name. The title will be a URL that links us to another page that will display the full content of the news(See image bellow).

![list news][1]

On the page displays the full artile, we'll also  list other articles so that the user can easily read another news without going page to the home page. We will also provide a link to go back to the home page.

<a name="database-model-and-structure-of-tables" class="anchor" href="#database-model-and-structure-of-tables"></a>
[Database model and structure of tables](#database-model-and-structure-of-tables)
--------------------

Create a new database and name it **news** or something else. We will at this level have only one table in our database. The table will contain all our news. I named the table **info_news**; you can name anything you want what matters is the structures.

 So create your database and create following table in it. 

- Table structure for table **info_news**

>     CREATE TABLE IF NOT EXISTS 'info_news' (
>       'news_id' int(11) NOT NULL AUTO_INCREMENT,
>       'news_title' varchar(255) NOT NULL,
>       'news_short_description' text NOT NULL,
>       'news_full_content' text NOT NULL,
>       'news_author' varchar(120) NOT NULL,
>       'news_published_on' int(46) NOT NULL,
>       PRIMARY KEY ('news_id')
>     ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

This is the table's model:

![Table design][2]

You may be wondering how I got the structure of this table. The [site structure](#structure-of-the-site) helped me understand different information I need on each news article

<a name="files-folders-structure" class="anchor" href="#files-folders-structure"></a>
[Files & folders structure](#files-folders-structure)
--------------------

For this project I will be using two main files: **index.php** and **read-news.php**.

Since I want to make the project as simple as possible just to show you the concept, I won't be using a [PHP framework](http://phpocean.com/tutorials/back-end/php-frameworking-introduction-part-1/9) or adopt an [MVC](http://phpocean.com/tutorials/back-end/php-frameworking-introduction-part-1/9) design pattern.

But, I want to keep my code clean; so I will be using a different file that will contain my database connection (**dbconnect.php**) and another file(**functions.php**) that will contain some set of functions we will create and use them in the project.

At the end, our folder structure should be like follow:

> - News/
>  - config/
>        - dbconnect.php
>  - includes/
>        - functions.php
>  - design/
>        - style.css
> - index.php
> - read-news.php

Check out my own folder bellow:
![News project folder][3]


<a name="files-content" class="anchor" href="#files-content"></a>
[Files content (codes)](#files-content)
--------------------

To start, we'll require the **db.connect.php** in **functions.php** because we will the database instance in our functions. Then in **index.php** and **read-news.php** we'll require  the **functions.php** because we will need those functions in them.

- File **config/dbconnect.php**
```
  <?php
 	    $pdo = null;
    	    function connect_to_db()
    	    {
   		    $dbengine   = 'mysql';
    		    $dbhost     = 'localhost';
   		    $dbuser     = 'root';
    		    $dbpassword = '';
    		    $dbname     = 'news';
   
   		    try{
    			    $pdo = new PDO("".$dbengine.":host=$dbhost; dbname=$dbname", $dbuser,$dbpassword);
    			    return $pdo;
    		    }  
    		    catch (PDOException $e){
    			    $e->getMessage();
    		    }
    	    }
```


- File **includes/functions.php**
This file contains all function we will need in the project. The number of functions may increase as the project grows.

```
    <?php 
    	require __DIR__.'/../config/dbconnect.php'; 
    
    	function fetchNews( $conn )
    	{
    
    		$request = $conn->prepare(" SELECT news_id, news_title, news_short_description, news_author, news_published_on FROM info_news ORDER BY news_published_on DESC ");
    		return $request->execute() ? $request->fetchAll() : false; 
    	}
    
    
    	function getAnArticle( $id_article, $conn )
    	{
    
    		$request =  $conn->prepare(" SELECT news_id,  news_title, news_full_content, news_author, news_published_on FROM info_news  WHERE news_id = ? ");
    		return $request->execute(array($id_article)) ? $request->fetchAll() : false; 
    	}
    
    
    	function getOtherArticles( $differ_id, $conn )
    	{
    		$request =  $conn->prepare(" SELECT news_id,  news_title, news_short_description, news_full_content, news_author, news_published_on FROM info_news  WHERE news_id != ? ");
    		return $request->execute(array($differ_id)) ? $request->fetchAll() : false; 
    	}
```





- File **index.php** without PHP

```
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
    		</div>
    
    		<div class="news-box">
    
    			<div class="news">
    				<h2><a href="read-news.php?newsid=1">First news title here</a></h2>
    				<p> This news short description will be displayed at this particular place. This news short description will be displayed at this particular place.</p>
    				<span>published on Jan, 12th 2015 by zooboole</span>
    			</div>
    
    			<div class="news">
    				<h2><a href="read-news.php?newsid=2">Second news title here</a></h2>
    				<p>This news short description will be displayed at this particular place. This news short description will be displayed at this particular place.</p>
    				<span>published on Jan, 12th 2015 by zooboole</span>
    			</div>
    
    			<div class="news">
    				<h2><a href="read-news.php?newsid=3">Thirst news title here</a></h2>
    				<p>This news short description will be displayed at this particular place. This news short description will be displayed at this particular place.</p>
    				<span>published on Jan, 12th 2015 by zooboole</span>
    			</div>
    
    			<div class="news">
    				<h2><a href="read-news.php?newsid=4">Fourth news title here</a></h2>
    				<p>This news short description will be displayed at this particular place. This news short description will be displayed at this particular place.</p>
    				<span>published on Jan, 12th 2015 by zooboole</span>
    			</div>
    
    		</div>
    
    		<div class="footer">
    			phpocean.com © <?= date("Y") ?> - all rights reserved.
    		</div>
    
    	</div>
    </body>
    </html>
```

>Note: Each news has a specific URL that links it to the **read-news.php** page like this:

```
<a hred="read-news.php?newsid=x">News title</a>
```

where *x* is a number

The *x* represent the unique id of that particular article. So the *read-news.php?newsid=x* tells the read-news.php page to display a news that has the id *x*.

Now in this file we want the news to be fetched and displayed from the database dynamically. Let call the function **fetchNews()** . 
To do that let's replace every thing in 
```
    <div class="news"> 
    ... 
    </div> 
```

by the following:
```
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
```




- File **read-news.php** 

```

    <?php require __DIR__.'../includes/functions.php' ?>
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
    			<a href="index.php">return to home page</a>
    		</div>
    
    		<div class="news-box">
    
    			<div class="news">
    				<?php
    					// get the database handler
    					$dbh = connect_to_db(); // function created in dbconnect, remember?
    
    					$id_article = (int)$_GET['newsid'];
    
    					if ( !empty($id_article) && $id_article > 0) {
    						// Fecth news
    						$article = getAnArticle( $id_article, $dbh );
    						$article = $article[0];
    					}else{
    						$article = false;
    						echo "<strong>Wrong article!</strong>";
    					}
    
    					$other_articles = getOtherArticles( $id_article, $dbh );
    
    				?>
    
    				<?php if ( $article && !empty($article) ) :?>
    
    				<h2><?= stripslashes($article->news_title) ?></h2>
    				<span>published on <?= date("M, jS  Y, H:i", $article->news_published_on) ?> by <?= stripslashes($article->news_author) ?></span>
    				<div>
    					<?= stripslashes($article->news_full_content) ?>
    				</div>
    			<?php else:?>
    
    				<?php endif?>
    			</div>
    
    			<hr>
    			<h1>Other articles</h1>
    			<div class="similar-posts">
    				<?php if ( $other_articles && !empty($other_articles) ) :?>
    
    				<?php foreach ($other_articles as $key => $article) :?>
    				<h2><a href="read-news.php?newsid=<?= $article->news_id ?>"><?= stripslashes($article->news_title) ?></a></h2>
    				<p><?= stripslashes($article->news_short_description) ?></p>
    				<span>published on <?= date("M, jS  Y, H:i", $article->news_published_on) ?> by <?= stripslashes($article->news_author) ?></span>
    				<?php endforeach?>
    
    				<?php endif?>
    
    			</div>
    
    		</div>
    
    		<div class="footer">
    			phpocean.com © <?= date("Y") ?> - all rights reserved.
    		</div>
    
    	</div>
    </body>
    </html>
```

- The file **design/style.css**

```
     html, body
    {
    	font-family: verdana;
    	font-size: 16px;
    	font-size: 100%;
    	font-size: 1em;
    
    	height: 100%;
    	width: 100%;
    	margin: 0;
    	padding: 0;
    
    	background-color: #4DDEDF;
    }
    
    *
    {
    	box-sizing: border-box;
    }
    
    a{
    	text-decoration: none;
    	color: #4DDED0;
    }
    
    .welcome
    {
    	width: 800px;
    	margin: 2em auto;
    	padding: 10px 30px;
    	background-color: #ffffff;
    }
    
    .welcome a
    {
    	display: inline-block;
    	width: 200px;
    	border: 2px solid #0DDED0;
    	padding: 0.5em;
    	text-align: center;
    }
    
    .welcome h1
    {
    	margin: 0;
    	color: #555;
    }
    
    .news-box
    {
    
    	width: 800px;
    	margin: 0.5em auto;
    	padding: 30px;
    
    	background-color: #ffffff;
    }
    
    .news-box h2
    {
    	font-size: 1.3em;
    	padding: 0;
    	margin-bottom: 0;
    
    	color: #e45;
    }
    
    .news-box p
    {
    	font-size: 12px;
    	padding: 0;
    	margin-bottom: 0.3em;
    
    	color: #555;
    }
    
    .news-box span
    {
    	font-size: 10px;
    	color: #aaa;
    }
    
    .footer
    {
    	font-size: 10px;
    	color: #333;
    	text-align: center;
    
    	width: 800px;
    	margin: 2em auto;
    	padding: 10px 30px;
    }
```

<a name="room-for-improvement" class="anchor" href="#room-for-improvement"></a>
[Room for improvement](#room-of-improvement)
--------------------

Indeed, this is a very basic way of making a news website. It doesn't have any professional aspect like serious news sites do. But, we should know that this is a great basement to start with. The point here is mostly to show you how to retrieve data from a database and display it.

So, to make this tutorial complete, these are some functionalities one could add:

- an admin panel to manage news (add, edit, delete,etc),
- categorize your news,
- inhence the design,
- add comments system under each article we are reading,
- news scheduling
- etc.


There is lot one can do to make such system complete. It's left with you to decide of what to add or how you can use it for other goals.





<a name="conclusion" class="anchor" href="#conclusion"></a>
[Conclusion](#conclusion)
--------------------


Voila. We are at the end of this little tutorial. We have created a simple news system that displays a list of articles and when we click on an article's title we are taken to a reading page that uses the article's id to retrieve dynamically the whole content of that article.

It's a very simple system, but with it you can improve your skills in PHP/MYSQL applications. It was also an opportunity to introduce a bit how to use PDO.

So, if you have any question or you are meeting some errors, just comment down here. Check out the [demo here](http://phpocean.com/demo/create-a-simple-news-site-with-php-and-mysql-with-pdo/index.php), and you can also [download the zip](http://phpocean.com/tutorials/source-codes/1effdde64f6cff9aecb35e705fbdcc85) file of the source code with comments


  [1]: http://phpocean.com/assets/images/news1.png
  [2]: http://phpocean.com/assets/images/tablemodel.png
  [3]: http://phpocean.com/assets/images/news-folders.png
