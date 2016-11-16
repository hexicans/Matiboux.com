<?php
/** Load Oli Framework */
if(!defined('ABSPATH')) define('ABSPATH', dirname(__FILE__) . '/');
require_once ABSPATH . 'load.php';
?>

<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<title>Mail Service</title>

</head>
<body>

<?php if($_Oli->getUrlParam(2) == 'new-ticket' AND $_Oli->getUrlParam(3) == 'client' AND !empty($_Oli->getUrlParam(4))) { ?>
	<?php $params = unserialize(urldecode($_Oli->getUrlParam(4))); ?>
	<div class="main">
		<div class="container">
			<h1>Votre ticket a été créé !</h1>
			<p>
				Merci <b><?php echo $params['owner']; ?></b>, <br />
				votre ticket "<?php echo $params['title']; ?>" sera traité dès que possible. <br />
				Y accéder :
				<a href="<?php echo $_Oli->getSetting('url'); ?>support/tickets/view/<?php echo $params['ticketKey']; ?>">
					<b>#<?php echo $params['ticketKey']; ?></b>
				</a>
			</p> <hr />
			
			<h3>Restez attentif</h3>
			<p>
				<b>Tous avertis</b> <br />
				Les administrateurs ont également été notifiés de la création de votre ticket.
				Quand l'un d'entre eux vous répondra, vous serez averti par mail,
				et quand ce sera à votre tour de leur répondre, ils seront à nouveau notifiés d'une activité.
			</p>
			
			<h3>Confidentialité</h3>
			<p>
				<b>Aucunes archives</b> <br />
				Il est impossible de fermer un ticket, seulement de le supprimer, soit aucune trace des messages n'est conservée.
				De plus, les tickets sont automatiquement supprimés après 15 jours d'existance si le dernier message est vieux de plus de 4 jours. <br />
				<span class="text-danger">Néanmoins</span>, bien qu'aucune trace ne soit conservé, un suivi des mails est affectué.
				Ainsi, tout abus ou spam sera sanctionné d'une interdiction à la création de ticket ou encore d'une suspension de votre compte. 
			</p> <hr />
			
			<p>
				<i class="fa fa-copyright fa-fw"></i>
				<a href="<?php echo $_Oli->getShortcutLink('home'); ?>"><?php echo $_Oli->getSetting('owner'); ?></a>
				- Tous droits réservés
			</p>
		</div>
	</div>
<?php } else if($_Oli->getUrlParam(2) == 'new-ticket' AND $_Oli->getUrlParam(3) == 'admin' AND !empty($_Oli->getUrlParam(4))) { ?>
	<?php $params = unserialize(urldecode($_Oli->getUrlParam(4))); ?>
	<div class="main">
		<div class="container">
			<h1>Un nouveau ticket a été créé</h1>
			<p>
				Titre : <b><?php echo $params['title']; ?></b> <br />
				Client conserné : <b><?php echo $params['owner']; ?></b> <br />
				Y accéder :
				<a href="<?php echo $_Oli->getSetting('url'); ?>support/tickets/view/<?php echo $params['ticketKey']; ?>">
					<b>#<?php echo $params['ticketKey']; ?></b>
				</a> <br />
				<span class="text-muted">Vous devez être connecté en tant qu'administrateur</span>
			</p> <hr />
			
			<h3>Restez attentif</h3>
			<p>
				<b>Tous avertis</b> <br />
				Vous serez notifiés en cas de réponse de la part du client conserné.
			</p> <hr />
			
			<p>
				<i class="fa fa-copyright fa-fw"></i>
				<a href="<?php echo $_Oli->getShortcutLink('home'); ?>"><?php echo $_Oli->getSetting('owner'); ?></a>
				- Tous droits réservés
			</p>
		</div>
	</div>
<?php } else if($_Oli->getUrlParam(2) == 'ticket-anwser' AND $_Oli->getUrlParam(3) == 'client' AND !empty($_Oli->getUrlParam(4))) { ?>
	<?php $params = unserialize(urldecode($_Oli->getUrlParam(4))); ?>
	<div class="main">
		<div class="container">
			<h1>Un admin vous a répondu !</h1>
			<p>
				Cher <b><?php echo $params['owner']; ?></b>, <br />
				votre ticket "<?php echo $params['title']; ?>" vient de recevoir une réponse ! <br />
				Allez la lire dès maintenant :
				<a href="<?php echo $_Oli->getSetting('url'); ?>support/tickets/view/<?php echo $params['ticketKey']; ?>">
					<b>#<?php echo $params['ticketKey']; ?></b>
				</a>
			</p> <hr />
			
			<h3>Restez attentif</h3>
			<p>
				Tant que le ticket existe, vous continuerez d'être tenu au courant en cas de réponse.
			</p> <hr />
			
			<p>
				<i class="fa fa-copyright fa-fw"></i>
				<a href="<?php echo $_Oli->getShortcutLink('home'); ?>"><?php echo $_Oli->getSetting('owner'); ?></a>
				- Tous droits réservés
			</p>
		</div>
	</div>
<?php } else if($_Oli->getUrlParam(2) == 'ticket-anwser' AND $_Oli->getUrlParam(3) == 'admin' AND !empty($_Oli->getUrlParam(4))) { ?>
	<?php $params = unserialize(urldecode($_Oli->getUrlParam(4))); ?>
	<div class="main">
		<div class="container">
			<h1>Une réponse du client</h1>
			<p>
				Titre : <b><?php echo $params['title']; ?></b> <br />
				Client conserné : <b><?php echo $params['owner']; ?></b> <br />
				Accéder au ticket :
				<a href="<?php echo $_Oli->getSetting('url'); ?>support/tickets/view/<?php echo $params['ticketKey']; ?>">
					<b>#<?php echo $params['ticketKey']; ?></b>
				</a> <br />
				<span class="text-muted">Vous devez être connecté en tant qu'administrateur</span>
			</p> <hr />
			
			<h3>Restez attentif</h3>
			<p>
				<b>Tous avertis</b> <br />
				Tant que le ticket existe, vous serez notifiés en cas de réponse de la part du client conserné.
			</p> <hr />
			
			<p>
				<i class="fa fa-copyright fa-fw"></i>
				<a href="<?php echo $_Oli->getShortcutLink('home'); ?>"><?php echo $_Oli->getSetting('owner'); ?></a>
				- Tous droits réservés
			</p>
		</div>
	</div>
<?php } else { ?>
	<div class="main">
		<div class="container">
			<h3>Une erreur s'est produite</h3>
			<p>Ce mail aurait pu être important ! Signalez-le à un admin au plus vite.</p>
		</div>
	</div>
<?php } ?>

</body>
</html>