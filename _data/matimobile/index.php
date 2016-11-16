<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="name" content="Matiboux" />
	<meta name="description" content="" />
	<meta name="author" content="Matiboux" />
	
	<link rel="stylesheet" type="text/css" href="http://dev6.natrox.net/cdn/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="http://dev6.natrox.net/cdn/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="http://dev6.natrox.net/cdn/css/messages.css" />
	<link rel="stylesheet" type="text/css" href="style.css" />
	<link rel="stylesheet" type="text/css" href="mobile.css" />
	
	<title>Mobile - Matiboux</title>
</head>
<body>
	
	<header class="navbar navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="#" class="navbar-brand">Mobile Emulation</a>
			</div>
			
			<nav class="collapse navbar-collapse bs-navbar-collapse">
				<ul class="nav navbar-nav">
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="http://matiboux.com/"><i class="fa fa-long-arrow-right fa-fw"></i> Matiboux</a>
					</li>
				</ul>
			</nav>
		</div>
	</header>
	
	<div class="messages">
		<div class="container">
			<div class="loading-icon">
				<i class="fa fa-refresh fa-spin fa-fw"></i>
				Chargement...
			</div>
		</div>
	</div>
	
	<div class="mobile">
		<div class="container-fluid"></div>
	</div>
	
	<div class="main settings">
		<div class="container-fluid">
			<div class="header">
				<h1>Global Settings</h1>
				<p class="text-danger">
					Does nothing for the moment. <br />
					Inputs have been set to defaults values.
				</p>
			</div>
			<div class="content">
				<form action="#" class="form form-horizontal settings-menu">
					<div class="form-group">
						<label class="col-sm-2 control-label">Connect</label>
						<div class="col-sm-10">
							<div class="checkbox disabled">
								<label><input type="checkbox" id="useMatibouxApps" disabled /> Use Matiboux Apps and services</label> <br />
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Identité</label>
						<div class="col-sm-10">
							<div class="input-group">
								<div class="input-group-addon">Username: </div>
								<input type="number" class="form-control" id="username" placeholder="" disabled />
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Batterie</label>
						<div class="col-sm-10">
							<div class="checkbox disabled">
								<label><input type="checkbox" id="randomBatteryLevel" disabled /> Changement aléatoire du niveau de batterie</label> <br />
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<div class="input-group">
								<div class="input-group-addon">Update Interval: </div>
								<input type="number" class="form-control" id="updateBatteryLevelDelay" placeholder="" value="12000" disabled />
								<div class="input-group-addon">ms</div>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Date</label>
						<div class="col-sm-10">
							<div class="checkbox disabled">
								<label><input type="checkbox" id="updateTime" checked disabled /> Mise à jour de l'heure</label> <br />
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<div class="input-group">
								<div class="input-group-addon">Update Interval: </div>
								<input type="number" class="form-control" id="updateTimeDelay" placeholder="" value="1000" disabled />
								<div class="input-group-addon">ms</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="main">
		<div class="container-fluid">
			<div class="footer">
				<div class="copyright">
					<i class="fa fa-copyright"></i>
					<a href="http://matiboux.com/">Matiboux</a>
					- Tous droits réservés.
				</div>
			</div>
		</div>
	</div>
	
	<script src="http://dev6.natrox.net/cdn/js/jquery-2.1.4.min.js"></script>
	<script src="http://dev6.natrox.net/cdn/js/bootstrap.min.js"></script>
	<script src="mobileCore.js"></script>
	<script src="mobileScript.js"></script>
</body>
</html>