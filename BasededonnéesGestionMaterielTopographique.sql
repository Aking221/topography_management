-- Script SQL pour la gestion du matériel topographique avec améliorations et journalisation complète

-- Table: utilisateurs
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    nom_complet VARCHAR(100) NOT NULL,
    telephone VARCHAR(50),
    groupe VARCHAR(50),
    privilege VARCHAR(50),
    code_authent VARCHAR(15),
    created_at DATETIME,
    updated_at DATETIME
);

-- Table: fournisseurs
CREATE TABLE fournisseurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fournisseur VARCHAR(100) NOT NULL,
    contact VARCHAR(100),
    code VARCHAR(50),
    active INT DEFAULT 1,
    observation TEXT
);

-- Table: chantiers
CREATE TABLE chantiers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(15) NOT NULL,
    chantier VARCHAR(255) NOT NULL,
    id_pays INT,
    contact VARCHAR(50),
    creer_par VARCHAR(50),
    FOREIGN KEY (id_pays) REFERENCES pays(id)
);

-- Table: pays
CREATE TABLE pays (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pays VARCHAR(50) NOT NULL,
    creer_par VARCHAR(50)
);

-- Table: materiel_topo
CREATE TABLE materiel_topo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_famille_topo INT,
    code VARCHAR(25) NOT NULL,
    description VARCHAR(255),
    marque VARCHAR(200),
    num_serie VARCHAR(100),
    cout_acquisition FLOAT,
    date_acquisition DATE,
    id_fournisseur INT,
    num_bv_fournisseur VARCHAR(100),
    cout_achat FLOAT,
    fiche_mise_service DATE,
    id_chantier INT,
    date_defection DATE,
    observation VARCHAR(255),
    FOREIGN KEY (id_famille_topo) REFERENCES familles_topo(id),
    FOREIGN KEY (id_fournisseur) REFERENCES fournisseurs(id),
    FOREIGN KEY (id_chantier) REFERENCES chantiers(id)
);

-- Table: familles_topo
CREATE TABLE familles_topo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    materiel VARCHAR(250),
    abv VARCHAR(8),
    active INT DEFAULT 1,
    observation VARCHAR(255)
);

-- Table: interventions
CREATE TABLE interventions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_intervention VARCHAR(25),
    id_materiel_topo INT,
    date_intervention DATE,
    intervenant VARCHAR(50),
    sous_traitant VARCHAR(50),
    nature_intervention VARCHAR(50),
    reference VARCHAR(255),
    tolerance INT,
    duree_validite INT,
    date_fin_validite DATE,
    cout FLOAT,
    observation TEXT,
    FOREIGN KEY (id_materiel_topo) REFERENCES materiel_topo(id)
);

-- Table: transfert_materiel
CREATE TABLE transfert_materiel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_materiel_topo INT,
    date_transfert DATE,
    id_provenance INT,
    id_destination INT,
    num_bt INT,
    bon_transfert VARCHAR(255),
    receptionner INT,
    date_reception DATE,
    cout FLOAT,
    creer_par VARCHAR(50),
    observation TEXT,
    FOREIGN KEY (id_materiel_topo) REFERENCES materiel_topo(id),
    FOREIGN KEY (id_provenance) REFERENCES chantiers(id),
    FOREIGN KEY (id_destination) REFERENCES chantiers(id),
    FOREIGN KEY (receptionner) REFERENCES utilisateurs(id)
);

-- Table: reforme_materiel
CREATE TABLE reforme_materiel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_materiel_topo INT,
    date_reforme DATE,
    raison VARCHAR(255),
    id_destination VARCHAR(50),
    observation TEXT,
    FOREIGN KEY (id_materiel_topo) REFERENCES materiel_topo(id)
);

-- Table: journal
CREATE TABLE journal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module VARCHAR(100),
    type_action VARCHAR(50),
    id_utilisateur INT,
    effective_par VARCHAR(100),
    date_action DATETIME,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id)
);

-- Indexes
CREATE INDEX idx_materiel_topo_code ON materiel_topo(code);
CREATE INDEX idx_interventions_type ON interventions(type_intervention);
CREATE INDEX idx_transfert_materiel_date ON transfert_materiel(date_transfert);

-- Triggers pour journaliser les actions
DELIMITER //
CREATE TRIGGER after_materiel_topo_insert
AFTER INSERT ON materiel_topo
FOR EACH ROW
BEGIN
    INSERT INTO journal (module, type_action, id_utilisateur, effective_par, date_action)
    VALUES ('materiel_topo', 'INSERT', NEW.id_fournisseur, NEW.creer_par, NOW());
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER after_materiel_topo_update
AFTER UPDATE ON materiel_topo
FOR EACH ROW
BEGIN
    INSERT INTO journal (module, type_action, id_utilisateur, effective_par, date_action)
    VALUES ('materiel_topo', 'UPDATE', NEW.id_fournisseur, NEW.creer_par, NOW());
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER after_materiel_topo_delete
AFTER DELETE ON materiel_topo
FOR EACH ROW
BEGIN
    INSERT INTO journal (module, type_action, id_utilisateur, effective_par, date_action)
    VALUES ('materiel_topo', 'DELETE', OLD.id_fournisseur, OLD.creer_par, NOW());
END;
//
DELIMITER ;

-- Procedure stockée pour ajouter un nouveau matériel
DELIMITER //
CREATE PROCEDURE add_materiel_topo (
    IN p_code VARCHAR(25),
    IN p_description VARCHAR(255),
    IN p_marque VARCHAR(200),
    IN p_num_serie VARCHAR(100),
    IN p_cout_acquisition FLOAT,
    IN p_date_acquisition DATE,
    IN p_id_fournisseur INT,
    IN p_num_bv_fournisseur VARCHAR(100),
    IN p_cout_achat FLOAT,
    IN p_fiche_mise_service DATE,
    IN p_id_chantier INT,
    IN p_date_defection DATE,
    IN p_observation VARCHAR(255),
    IN p_id_famille_topo INT
)
BEGIN
    INSERT INTO materiel_topo (code, description, marque, num_serie, cout_acquisition, date_acquisition, id_fournisseur, num_bv_fournisseur, cout_achat, fiche_mise_service, id_chantier, date_defection, observation, id_famille_topo)
    VALUES (p_code, p_description, p_marque, p_num_serie, p_cout_acquisition, p_date_acquisition, p_id_fournisseur, p_num_bv_fournisseur, p_cout_achat, p_fiche_mise_service, p_id_chantier, p_date_defection, p_observation, p_id_famille_topo);
END;
//
DELIMITER ;

-- Procedure stockée pour mettre à jour un matériel
DELIMITER //
CREATE PROCEDURE update_materiel_topo (
    IN p_id INT,
    IN p_code VARCHAR(25),
    IN p_description VARCHAR(255),
    IN p_marque VARCHAR(200),
    IN p_num_serie VARCHAR(100),
    IN p_cout_acquisition FLOAT,
    IN p_date_acquisition DATE,
    IN p_id_fournisseur INT,
    IN p_num_bv_fournisseur VARCHAR(100),
    IN p_cout_achat FLOAT,
    IN p_fiche_mise_service DATE,
    IN p_id_chantier INT,
    IN p_date_defection DATE,
    IN p_observation VARCHAR(255),
    IN p_id_famille_topo INT
)
BEGIN
    UPDATE materiel_topo
    SET code = p_code,
        description = p_description,
        marque = p_marque,
        num_serie = p_num_serie,
        cout_acquisition = p_cout_acquisition,
        date_acquisition = p_date_acquisition,
        id_fournisseur = p_id_fournisseur,
        num_bv_fournisseur = p_num_bv_fournisseur,
        cout_achat = p_cout_achat,
        fiche_mise_service = p_fiche_mise_service,
        id_chantier = p_id_chantier,
        date_defection = p_date_defection,
        observation = p_observation,
        id_famille_topo = p_id_famille_topo
    WHERE id = p_id;
END;
//
DELIMITER ;

-- Procedure stockée pour supprimer un matériel
DELIMITER //
CREATE PROCEDURE delete_materiel_topo (
    IN p_id INT
)
BEGIN
    DELETE FROM materiel_topo
    WHERE id = p_id;
END;
//
DELIMITER ;
