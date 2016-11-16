<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Remerciements - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1>Remerciements</h1>
		<p>
			Ceux qui me soutiennent, qui m'aident ou m'ont aidé
		</p>
	</div>
</div>

<div class="main">
	<div class="container">
		<div class="carousel">
			<?php foreach([
				array(
					'message' => 'J\'aime mon Mati',
					'author' => 'Eliott'
				),
				array(
					'message' => 'Salut salut, c\'est mes beaux projets',
					'author' => 'Matiboux'
				),
				array(
					'message' => 'Vivement Gaming-Actu 2 !',
					'author' => 'Dav'
				)
			] as $eachElement) { ?>
				<div>
					<i class="fa fa-quote-left fa-3x fa-pull-left fa-border" aria-hidden="true"></i>
					<p class="message"><?php echo $eachElement['message']; ?></p>
					<footer>- <?php echo $eachElement['author']; ?></footer>
				</div>
			<?php } ?>
		</div>
	</div> <hr />
	<div class="container">
		<h2>Vous voulez m'aider ? Faire un don ?</h2>
		<p>
			Mes informations de contact sont disponibles sur la page <span class="text-muted">A propos</span> <br />
			Si vous souhaitez faire un don, vous pouvez utiliser l'une des solutions suivantes : <br />
			<a href="https://paypal.me/Matiboux/2" class="btn btn-primary btn-sm">
				<i class="fa fa-paypal fa-fw"></i>
				Faire un don sur mon profil paypal.me
			</a>
			<a href="https://patreon.com/Matiboux" class="btn btn-primary btn-sm">
				<i class="fa fa- fa-fw"></i>
				Proposer un don mensuel sur Patreon
			</a>
		</p>
	</div> <hr />
	<div class="container">
		<p><i class="fa fa-sort-numeric-desc fa-fw"></i> Les plus récent sont notés en haut</p>
		
		<h2>Remerciements Spéciaux</h2>
		<p>
			
		</p>
		
		<h2>Dons</h2>
		<p>
			
		</p>
	</div>
</div>

<?php include 'footer.php'; ?>

<script>
var carouselCount = ($('.carousel').find('div')).length;
var currentCarouselId = Math.floor(Math.random() * carouselCount);
var speed = 6400;

function carousel() {
	$('.carousel').find('div').hide();
	console.log('all hide');
	$($('.carousel').find('div')[currentCarouselId]).fadeIn();
	console.log(currentCarouselId + ' fade in');
	
	// if(currentCarouselId < (carouselCount - 1))
		// currentCarouselId++;
	// else
		// currentCarouselId = 0
	
	var previousID = currentCarouselId;
	while(currentCarouselId == previousID && carouselCount > 1) {
		currentCarouselId = Math.floor(Math.random() * carouselCount);
		console.log('change id from ' + previousID + ' to ' + currentCarouselId)
	}
	
	setTimeout(carousel, speed);
}

$(document).ready(function() {
	carousel();
});
</script>

</body>
</html>