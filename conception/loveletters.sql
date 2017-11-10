CREATE TABLE Utilisateur
(
	idUtilisateur integer(10) primary key not null,
	login varchar(25),
	pwd varchar(25)
);

CREATE TABLE Joueur
(
	idJoueur integer(10) primary key not null,
	idUtilisateur integer(10) not null,
	score integer(10),
	foreign key (idUtilisateur) references Utilisateur(idUtilisateur)
);

CREATE TABLE Defausse
(
	idDefausse integer(10) primary key not null,
	idJoueur integer(10),
	idManche integer(10) not null,
	foreign key (idJoueur) references Joueur(idJoueur),
	foreign key (idManche) references Manche(idManche)
);

CREATE TABLE EstPlacee
(
	idDefausse integer(10) not null,
	idCarte integer(10) not null,
	nbCartes integer(10) not null,
	primary key (idDefausse, idCarte),
	foreign key (idDefausse) references Defausse(idDefausse),
	foreign key (idCarte) references Carte(idCarte)
);

CREATE TABLE Carte
(
	idCarte integer(10) primary key not null,
	nom varchar(10),
	rang integer(10),
	url_illus varchar(25),
	effet varchar(25)
);

CREATE TABLE Possede
(
	idJoueur integer(10) not null,
	idCarte integer(10) not null,
	nbCartes integer(10),
	primary key (idJoueur, idCarte),
	foreign key (idCarte) references Carte(idCarte),
	foreign key (idJoueur) references Joueur(idJoueur)
);

CREATE TABLE Pioche
(
	idPioche integer(10) primary key not null
);

CREATE TABLE Comporte
(
	idPioche integer(10) not null,
	idCarte integer(10) not null,
	nbCartes integer(10),
	primary key (idPioche, idCarte),
	foreign key (idPioche) references Pioche(idPioche),
	foreign key (idCarte) references Carte(idCarte)
);

CREATE TABLE Manche
(
	idManche integer(10) primary key not null,
	idPartie integer(10) not null,
	idPioche integer(10) not null,
	foreign key(idPioche) references Pioche(idPioche),
	foreign key(idPartie) references Partie(idPartie)
);

CREATE TABLE Partie
(
	idPartie integer(10) primary key not null
);

CREATE TABLE Participe
(
	idJoueur integer(10) not null,
	idManche integer(10) not null,
	primary key(idJoueur, idManche),
	foreign key(idJoueur) references Joueur(idJoueur),
	foreign key(idManche) references Manche(idManche)
);