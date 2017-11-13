CREATE TABLE Utilisateur
(
	idUtilisateur int primary key not null,
	login varchar(25),
	pwd varchar(25)
);

CREATE TABLE Joueur
(
	idJoueur int primary key not null,
	idUtilisateur int not null,
	score int,
	foreign key (idUtilisateur) references Utilisateur(idUtilisateur)
);

CREATE TABLE Carte
(
	idCarte int primary key not null,
	nom varchar(10),
	rang int,
	url_illus varchar(200),
	effet varchar(25)
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
	idPioche int primary key not null
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
	idPartie int primary key not null
);

CREATE TABLE Manche
(
	idManche int primary key not null,
	idPartie int not null,
	idPioche int not null,
	foreign key(idPioche) references Pioche(idPioche),
	foreign key(idPartie) references Partie(idPartie)
);

CREATE TABLE Defausse
(
	idDefausse int primary key not null,
	idJoueur int,
	idManche int not null,
	foreign key (idJoueur) references Joueur(idJoueur),
	foreign key (idManche) references Manche(idManche)
);

CREATE TABLE EstPlacee
(
	idDefausse int not null,
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