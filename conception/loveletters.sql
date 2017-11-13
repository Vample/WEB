CREATE TABLE Utilisateur
(
	idUtilisateur int primary key not null AUTO_INCREMENT,
	login varchar(25),
	pwd varchar(25)
);

CREATE TABLE Joueur
(
	idJoueur int primary key not null AUTO_INCREMENT,
	idUtilisateur int not null,
	score int,
	foreign key (idUtilisateur) references Utilisateur(idUtilisateur)
);

CREATE TABLE Carte
(
	idCarte int primary key not null AUTO_INCREMENT,
	nom varchar(10),
	rang int,
	url_illus varchar(200),
	effet varchar(200)
);

CREATE TABLE Possede
(
	idJoueur int not null,
	idCarte int not null,
	nbCartes int,
	primary key (idJoueur, idCarte),
	foreign key (idCarte) references Carte(idCarte),
	foreign key (idJoueur) references Joueur(idJoueur)
);

CREATE TABLE Pioche
(
	idPioche int primary key not null AUTO_INCREMENT
);

CREATE TABLE Comporte
(
	idPioche int not null,
	idCarte int not null,
	nbCartes int,
	primary key (idPioche, idCarte),
	foreign key (idPioche) references Pioche(idPioche),
	foreign key (idCarte) references Carte(idCarte)
);

CREATE TABLE Partie
(
	idPartie int primary key not null AUTO_INCREMENT
);

CREATE TABLE Manche
(
	idManche int primary key not null AUTO_INCREMENT,
	idPartie int not null,
	idPioche int not null,
	foreign key(idPioche) references Pioche(idPioche),
	foreign key(idPartie) references Partie(idPartie)
);

CREATE TABLE Defausse
(
	idDefausse int primary key not null AUTO_INCREMENT,
	idJoueur int,
	idManche int not null,
	foreign key (idJoueur) references Joueur(idJoueur),
	foreign key (idManche) references Manche(idManche)
);

CREATE TABLE EstPlacee
(
	idDefausse int not null AUTO_INCREMENT,
	idCarte int not null,
	nbCartes int not null,
	primary key (idDefausse, idCarte),
	foreign key (idDefausse) references Defausse(idDefausse),
	foreign key (idCarte) references Carte(idCarte)
);

CREATE TABLE Participe
(
	idJoueur int not null,
	idManche int not null,
	primary key(idJoueur, idManche),
	foreign key(idJoueur) references Joueur(idJoueur),
	foreign key(idManche) references Manche(idManche)
);

INSERT INTO Carte VALUES (1, 'Garde', 1, '', 'Choisissez un joueur et essayez de deviner la carte qu\'il a en main (sauf Garde) si vous tombez juste le joueur est éliminé de la manche.'),
			 (2, 'Prêtre', 2, '', 'Regardez la main d\'un autre joueur.'),
			 (3, 'Baron', 3, '', 'Comparez votre carte avec celle d\'un autre joueur, celui qui a la carte avec la plus faible valeur est éliminé de la manche.'),
			 (4, 'Servante', 4, '', 'Jusqu\'au prochain tour vous êtes protégé des effets des cartes des autres joueurs.'),
			 (5, 'Prince', 5, '', 'Choisissez un joueur (y compris vous), celui-ci défausse la carte qu\'il a en main pour en piocher une nouvelle.'),
			 (6, 'Roi', 6, '', 'Echangez votre main avec un autre joueur de votre choix.'),
			 (7, 'Comtesse', 7, '', 'Si vous avez cette carte en main en même temps que le Roi ou le Prince, alors vous devez défausser la carte de la Comtesse'),
			 (8, 'Princesse', 8, '', 'Si vous défaussez cette carte vous êtes éliminé de la manche');