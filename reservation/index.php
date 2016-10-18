<!DOCTYPE html>
<html lang="fr">
<head>
	<title data-i18n="Que Huong Chorale">Hợp Ca Quê Hương</title>
	<meta name="keywords" content="hopcaquehuong, Chorale, HopXuong, QueHuong, Paris, France, Phap">
	<meta name="description" content="Dàn Hợp Xướng Quê Hương trên đất Pháp">
	<meta name="author" content="Hop Ca Que Huong">
	<meta name="language" content="fr">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.4.0/animate.min.css" media="only screen and (min-width:768px)">
	<link rel="stylesheet" href="main.css">
	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">-->
</head>
<body>
<nav class="navbar navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<a style="padding:0px; text-decoration: none; color: darkslateblue; font-size: large; font-weight: bold" href="../">
				<img alt="Brand" src="../content/logo.svg" width="50" height="50">
				Ensemble Chorale Que-Huong
			</a>
		</div>
	</div>
</nav>
</div><div class="jumbotron" style="background:darkslateblue;color:whitesmoke">
	<div class="container"><h1>Notre plus grande concerte de l'année</h1>
		<h2>Reservez vos places maintenant, c'est gratuit.</h2>
		<ul>
			<li>Nous nous essayons de notre mieux afin de présenter au publique les plus grande d'oeuvres vietnamiens.
			<li>11 chansons classiques avec tout notre émotions sont compacté dans 1 CD.
			<li>300 CDs seront offerts sur place pour les premiers réservations.
		</ul>
	</div>
</div>

<div class="container">
	<section>
		<reservation-form></reservation-form>
	</section>
</div>

<footer>
	<div class="container" style="padding-top:100px; padding-bottom:20px">
		<div class="row">
			<div class="col-md-4 text-center">
				<span class="copyright">Copyright &copy; Que-Huong Chorale 2015</span>
			</div>
			<div class="col-md-4">
				<a href="https://www.facebook.com/hopcaquehuong?fref=ts"><img src="../content/footer_icon_facebook.png"  class="center-block text-centered"></a>
			</div>
			<div class="col-md-4">
				<div class='text-center'>
					<!-- <a class="github-button" href="https://github.com/duongphuhiep/hcqh/fork" data-style="mega" aria-label="Fork duongphuhiep/hcqh on GitHub">Fork</a> -->
					<a class="github-button" href="https://github.com/duongphuhiep/hcqh/issues" data-style="mega" aria-label="Issue duongphuhiep/hcqh on GitHub">Issue?</a>
					<script async defer id="github-bjs" src="https://buttons.github.io/buttons.js"></script>
				</div>
			</div>
		</div>
	</div>
</footer>

<language-selector></language-selector>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/riot/2.6.4/riot+compiler.min.js"></script>
<script src="seat.tag" type="riot/tag"></script>
<script src="reservation-form.tag" type="riot/tag"></script>
<script>
	riot.mount("*");
</script>

<!--<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-68626039-1', 'auto');
	ga('send', 'pageview');
</script>
-->
</body>
</html>
