-- Création des tables de référence

CREATE TABLE familles_topo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    materiel VARCHAR(250),
    abv VARCHAR(8),
    active TINYINT(1) DEFAULT 1,
    observation VARCHAR(255)
);

CREATE TABLE fournisseurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fournisseur VARCHAR(255),
    code VARCHAR(100),
    contact VARCHAR(255),
    active TINYINT(1) DEFAULT 1,
    creer_par VARCHAR(100),
    observation VARCHAR(255)
);

CREATE TABLE pays (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pays VARCHAR(50) NOT NULL,
    creer_par VARCHAR(50)
);

CREATE TABLE chantiers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(15) NOT NULL UNIQUE,
    chantier VARCHAR(255) NOT NULL,
    id_pays INT,
    contact VARCHAR(255),
    active TINYINT(1) DEFAULT 1,
    creer_par VARCHAR(50),
    observation VARCHAR(255),
    FOREIGN KEY (id_pays) REFERENCES pays(id)
);

-- Création de la table principale de gestion du matériel topographique

CREATE TABLE materiel_topo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_famille_topo INT,
    code VARCHAR(25) NOT NULL UNIQUE,
    description VARCHAR(200),
    marque VARCHAR(200),
    num_serie VARCHAR(100),
    date_acquisition DATE,
    cout_acquisition FLOAT,
    id_fournisseur INT,
    num_bc VARCHAR(100),
    fiche_bl VARCHAR(255),
    date_mise_service DATE,
    etat VARCHAR(25),
    id_chantier INT,
    date_affectation DATE,
    creer_par VARCHAR(50),
    observation VARCHAR(255),
    FOREIGN KEY (id_famille_topo) REFERENCES familles_topo(id),
    FOREIGN KEY (id_fournisseur) REFERENCES fournisseurs(id),
    FOREIGN KEY (id_chantier) REFERENCES chantiers(id)
);

-- Création des tables pour les mouvements, interventions et réformes

CREATE TABLE transfert_materiel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_materiel_topo INT,
    date_transfert DATE NOT NULL,
    id_provenance INT,
    id_destination INT,
    num_bt INT,
    bon_transfert VARCHAR(255),
    receptionner TINYINT(1),
    date_reception DATE,
    cout FLOAT,
    creer_par VARCHAR(50),
    observation TEXT,
    FOREIGN KEY (id_materiel_topo) REFERENCES materiel_topo(id),
    FOREIGN KEY (id_provenance) REFERENCES chantiers(id),
    FOREIGN KEY (id_destination) REFERENCES chantiers(id)
);

CREATE TABLE interventions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_intervention VARCHAR(25) NOT NULL,
    id_materiel_topo INT,
    date_intervention DATE NOT NULL,
    intervenant VARCHAR(50),
    sous_traitant VARCHAR(50),
    nature_intervention VARCHAR(50),
    reference VARCHAR(255),
    tolerance INT,
    duree_validite INT,
    date_fin_validite DATE,
    cout FLOAT,
    fiche VARCHAR(255),
    observation TEXT,
    FOREIGN KEY (id_materiel_topo) REFERENCES materiel_topo(id)
);

CREATE TABLE reforme_materiel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_materiel_topo INT,
    date_reforme DATE NOT NULL,
    raison VARCHAR(255),
    destination VARCHAR(255),
    observation TEXT,
    creer_par VARCHAR(50),
    FOREIGN KEY (id_materiel_topo) REFERENCES materiel_topo(id)
);

-- Création des tables pour les utilisateurs et le journal des actions

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    nom_complet VARCHAR(100) NOT NULL,
    telephone VARCHAR(50),
    groupe VARCHAR(50),
    privilege VARCHAR(15),
    code_chantier VARCHAR(15),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE journal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module VARCHAR(100),
    type_action VARCHAR(50),
    actions VARCHAR(255),
    effectue_par VARCHAR(100),
    date_action DATETIME NOT NULL
);

-- Création des tables pour les intervenants

CREATE TABLE intervenants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(250),
    code_intervenant VARCHAR(25),
    domaine_intervention VARCHAR(50),
    date_entree_service DATE,
    active TINYINT(1) DEFAULT 1,
    observation TEXT
);

-- Insertions initiales de données

INSERT INTO pays (pays, creer_par) VALUES 
('France', 'admin'), 
('Germany', 'admin'), 
('Spain', 'admin');

INSERT INTO familles_topo (materiel, abv, active, observation) VALUES
('Theodolite', 'THD', 1, 'Precision instrument for measuring angles'),
('Level', 'LVL', 1, 'Instrument used to determine the horizontal plane'),
('Total Station', 'TST', 1, 'Electronic/optical instrument for surveying');

INSERT INTO fournisseurs (fournisseur, code, contact, active, creer_par, observation) VALUES
('Supplier A', 'SUPA', 'contact@suppliera.com', 1, 'admin', 'Main supplier of surveying instruments'),
('Supplier B', 'SUPB', 'contact@supplierb.com', 1, 'admin', 'Secondary supplier of parts and accessories');

INSERT INTO chantiers (code, chantier, id_pays, contact, active, creer_par, observation) VALUES
('11-1604', 'ILE A MORPHIL', 1, '', 0, 'O Faye', ''),
('11-1604_B', 'BABA GARAGE - MECKHE - FASS BOYE', 1, '', 0, 'O Faye', ''),
('11-1604_C', 'DEMETH CAS CAS', 1, '', 0, 'O Faye', ''),
('11-1605', 'ROUTE DES NIAYES', 1, '', 0, 'O Faye', ''),
('11-1701', 'MOSQUEE CITE CSE', 1, '', 0, 'O Faye', ''),
('11-1702', 'NDIOUM DEMETH LOT 4', 1, '', 0, 'O Faye', ''),
('11-1703', 'MBEUBEUSS', 1, '', 0, 'O Faye', ''),
('11-1704', 'MBEUBEUSS LOT2', 1, '', 0, 'O Faye', ''),
('11-1705', 'ROUTE NATIONALE GOLERE THILOGNE', 1, '', 0, 'O Faye', ''),
('11-1706', 'OCEAN VIEW', 1, '', 0, 'O Faye', ''),
('11-1709', 'PROMOVILLES', 1, '', 0, 'O Faye', '');

-- Insertions pour les autres tables peuvent être ajoutées ici en suivant le même format
