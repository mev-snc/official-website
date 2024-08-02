<?php

class dbManager{

        private $user = 'mev';
	private $pass = 'mev';
	private $db_name = 'mev';
	private $host = 'localhost';
	private $connection = NULL;


	function __construct() {
            $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->db_name);
	}

	function __destruct() { 
            $this->connection->close();
	}


        /*
        *   Transazioni
         */
        function start_transaction(){
            $sql = "START TRANSACTION;";
            $this->connection->query($sql);
        }

        function transaction_rollback(){
            $sql = "ROLLBACK;";
            $this->connection->query($sql);
        }

        function transaction_commit(){
            $sql = "COMMIT;";
            $this->connection->query($sql);
        }


        
        /*
        *
        *   Utenti
        *
        */
        function aggiungi_utente( $utente ){
            $utente['email'] = addslashes($utente['email']);
            $utente['username'] = addslashes($utente['username']);
            $utente['password'] = md5($utente['password']);
            $utente['nome'] = addslashes($utente['nome']);
            $utente['cognome'] = addslashes($utente['cognome']);
            $utente['codice_fiscale'] = addslashes($utente['codice_fiscale']);
            $utente['piva'] = addslashes($utente['piva']);
            $utente['indirizzo'] = addslashes($utente['indirizzo']);
            $utente['citta'] = addslashes($utente['citta']);
            $utente['cap'] = addslashes($utente['cap']);
            $utente['provincia'] = addslashes($utente['provincia']);
            $utente['ragione_sociale'] = addslashes($utente['ragione_sociale']);
            $utente['fax'] = addslashes($utente['fax']);
            $utente['domanda'] = addslashes($utente['domanda']);
            $utente['risposta'] = addslashes($utente['risposta']);
            $sql = "INSERT INTO `utenti` (`id_utente`, `email`, `username`, `password`, `nome`, `cognome`, `codice_fiscale`, `piva`, `indirizzo`, `citta`, `cap`, `provincia`, `ragione_sociale`, `fax`, `domanda`, `risposta`)" 
                  ."VALUES ( NULL, '{$utente['email']}', '{$utente['username']}', '{$utente['password']}', '{$utente['nome']}', '{$utente['cognome']}', '{$utente['codice_fiscale']}', '{$utente['piva']}', '{$utente['indirizzo']}', '{$utente['citta']}', '{$utente['cap']}', '{$utente['provincia']}', '{$utente['ragione_sociale']}',  '{$utente['fax']}',  '{$utente['domanda']}', '{$utente['risposta']}' );";
            if( $this->connection->query($sql)=== TRUE ){ 
                return $this->connection->insert_id;
            } else {
                return false;
            }
        }

        function elimina_utente( $id_utente ){
            $sql = "DELETE FROM utenti WHERE id_utente = $id_utente LIMIT 1";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            return false;
        }
        
        function get_utente( $id=NULL, $username=NULL, $password=NULL  ){
            $toReturn = false;
            if( !empty($id) ){
                $sql = "SELECT * FROM utenti WHERE id_utente = $id ";
            } elseif ( !empty($username) && !empty($password) ){
                $password = md5($password);
                $sql = "SELECT * FROM `utenti` WHERE `username` = '$username' AND `password` = '$password' LIMIT 1";
            }else{
                return $toReturn;
            }
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }


        function modifica_utente( $utente ){
            $utente['email'] = addslashes($utente['email']);
            $utente['username'] = addslashes($utente['username']);
            $utente['password'] = md5($utente['password']);
            $utente['nome'] = addslashes($utente['nome']);
            $utente['cognome'] = addslashes($utente['cognome']);
            $utente['codice_fiscale'] = addslashes($utente['codice_fiscale']);
            $utente['piva'] = addslashes($utente['piva']);
            $utente['indirizzo'] = addslashes($utente['indirizzo']);
            $utente['citta'] = addslashes($utente['citta']);
            $utente['cap'] = addslashes($utente['cap']);
            $utente['provincia'] = addslashes($utente['provincia']);
            $utente['ragione_sociale'] = addslashes($utente['ragione_sociale']);
            $utente['fax'] = addslashes($utente['fax']);
            $utente['domanda'] = addslashes($utente['domanda']);
            $utente['risposta'] = addslashes($utente['risposta']);
            $sql = "UPDATE `utenti` SET `email` = '{$utente['email']}',"
                                     ." `nome` = '{$utente['nome']}', "
                                     ." `cognome` = '{$utente['cognome']}',"
                                     ." `codice_fiscale` = '{$utente['codice_fiscale']}',"
                                     ." `piva` = '{$utente['piva']}',"
                                     ." `indirizzo` = '{$utente['indirizzo']}', "
                                     ." `citta` = '{$utente['citta']}',"
                                     ." `cap` = '{$utente['cap']}',"
                                     ." `provincia` = '{$utente['provincia']}',"
                                     ." `ragione_sociale` = '{$utente['ragione_sociale']}',"
                                     ." `fax` = '{$utente['fax']}',"
                                     ." `domanda` = '{$utente['domanda']}',"
                                     ." `risposta` = '{$utente['risposta']}' "
                       ." WHERE `utenti`.`id_utente` = {$utente['id_utente']} LIMIT 1;";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }
        
        function modifica_pwd_utente($utente, $password){
            $utente['password'] = md5($password);
            $sql = "UPDATE `utenti` SET `password` = '{$utente['password']}' "
                       ." WHERE `utenti`.`id_utente` = {$utente['id_utente']} LIMIT 1;";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }

        function lista_utenti( $campo=NULL, $valore=NULL, $inizio=NULL, $lunghezza=NULL ){
            $toReturn=NULL;
            $indice=0;
            $sql = "SELECT * FROM utenti WHERE ";
            if(!empty($campo) && !empty($valore)){
                $sql.= " $campo = '$valore' ";
            }else{
                $sql.= ' 1 ';
            }
            if( isset($inizio) && !empty($lunghezza)){
                $sql .= " LIMIT $inizio, $lunghezza ";
            }
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        function get_num_utenti(){
            $sql = "SELECT * FROM utenti";
            if( $risultato = $this->connection->query($sql)  ){
                return $this->connection->affected_rows ;
            }
            return 0;
        }
        
        
        /*
        *
        *   Indirizzi spedizione
        *
        */
        function aggiungi_indirizzo( $indirizzo ){
            $indirizzo['nome'] = addslashes($indirizzo['nome']);
            $indirizzo['cognome'] = addslashes($indirizzo['cognome']);
            $indirizzo['indirizzo'] = addslashes($indirizzo['indirizzo']);
            $indirizzo['citta'] = addslashes($indirizzo['citta']);
            $indirizzo['cap'] = addslashes($indirizzo['cap']);
            $indirizzo['provincia'] = addslashes($indirizzo['provincia']);
            $indirizzo['ragione_sociale'] = addslashes($indirizzo['ragione_sociale']);
            $sql = "INSERT INTO `indirizzi_spedizione` (`id_indirizzo`, `id_utente`, `nome`, `cognome`, `indirizzo`, `citta`, `cap`, `provincia`, `ragione_sociale`) VALUES (NULL, '{$indirizzo['id_utente']}', '{$indirizzo['nome']}', '{$indirizzo['cognome']}', '{$indirizzo['indirizzo']}', '{$indirizzo['citta']}', '{$indirizzo['cap']}', '{$indirizzo['provincia']}', '{$indirizzo['ragione_sociale']}');";
            if( $this->connection->query($sql)=== TRUE ){ 
                return $this->connection->insert_id;
            } else {
                return false;
            }
        }
        
        function lista_indirizzi_by_utente( $utente ){
            $toReturn=NULL;
            $indice=0;
            $sql = "SELECT * FROM `indirizzi_spedizione` WHERE `id_utente` = {$utente['id_utente']} ";
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        
        function unset_indirizzi_predefiniti( $utente ){
            $sql = "UPDATE `indirizzi_spedizione` SET `predefinito` = '0' WHERE id_utente = {$utente['id_utente']} ";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }
        
        
        function set_indirizzo_predefinito( $id_indirizzo ){
            $sql = "UPDATE `indirizzi_spedizione` SET `predefinito` = '1' WHERE id_indirizzo = $id_indirizzo ";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }
        
        function get_indirizzo_predefinito( $utente ){
            $toReturn = false;
            if(empty($utente)){
                return $toReturn;
            }
            $sql = "SELECT * FROM indirizzi_spedizione WHERE id_utente = {$utente['id_utente']} AND predefinito=1";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function get_indirizzo( $id_indirizzo_spedizione ){
            $toReturn = false;
            $sql = "SELECT * FROM indirizzi_spedizione WHERE id_indirizzo = $id_indirizzo_spedizione ";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function elimina_indirizzo( $id_indirizzo ){
            $sql = "DELETE FROM indirizzi_spedizione WHERE id_indirizzo = $id_indirizzo LIMIT 1";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            return false;
        }
        
        
        /*
        *
        *   Categorie
        *
        */
        function aggiungi_categoria( $categoria ){
            $categoria['nome'] = addslashes($categoria['nome']);
            $categoria['descrizione'] = addslashes($categoria['descrizione']);
            if( !$categoria['id_categoria_padre']) $categoria['id_categoria_padre'] = 'NULL';
            $sql = "INSERT INTO `categorie` (`id_categoria`, `nome`, `descrizione`, `id_categoria_padre`) VALUES (NULL, '{$categoria['nome']}', '{$categoria['descrizione']}', {$categoria['id_categoria_padre']});";
            if( $this->connection->query($sql)=== TRUE ){ 
                return $this->connection->insert_id;
            } else {
                return false;
            }
        }
        
        function lista_categorie( $id_categoria_padre=0 ){
            $sql = "SELECT * FROM `categorie` WHERE `id_categoria_padre` ";
            if( !$id_categoria_padre ){
               $sql.=" IS NULL";
            }else{
                $sql.= " = $id_categoria_padre";
            }
            $toReturn=array();
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
            
        }
        
        function get_categoria( $id_categoria ){
            $toReturn = false;
            if(empty($id_categoria)){
                return $toReturn;
            }
            $sql = "SELECT * FROM categorie WHERE id_categoria= $id_categoria LIMIT 1";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function elimina_categoria( $id_categoria ){
            $sql = "DELETE FROM categorie WHERE id_categoria = $id_categoria LIMIT 1";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            return false;
        }
        
        function modifica_categoria( $categoria ){
            $categoria['nome'] = addslashes($categoria['nome']);
            $categoria['descrizione'] = addslashes($categoria['descrizione']);
            if( !$categoria['id_categoria_padre']) $categoria['id_categoria_padre'] = 'NULL';
            $sql = "UPDATE `categorie` SET `nome` = '{$categoria['nome']}', 
                                            `descrizione` = '{$categoria['descrizione']}', 
                                            `id_categoria_padre` = {$categoria['id_categoria_padre']} 
                    WHERE `id_categoria` = {$categoria['id_categoria']} LIMIT 1;";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }
        
        
        /*
         * 
         *  Aliquote IVA
         * 
         */
        function aggiungi_aliquota_iva( $aliquota_iva ){
            $aliquota_iva['nome'] = addslashes($aliquota_iva['nome']);
            $sql = "INSERT INTO `aliquote_iva` (`id_aliquota_iva`, `nome`, `aliquota`) VALUES (NULL, '{$aliquota_iva['nome']}', '{$aliquota_iva['aliquota']}');";
            if( $this->connection->query($sql)=== TRUE ){ 
                return $this->connection->insert_id;
            } else {
                return false;
            }
        }
        
        function lista_aliquote_iva( ){
            $sql = "SELECT * FROM `aliquote_iva` ";
            $toReturn=array();
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
            
        }
        
        function get_aliquota_iva( $id_aliquota ){
            $toReturn = false;
            if(empty($id_aliquota)){
                return $toReturn;
            }
            $sql = "SELECT * FROM aliquote_iva WHERE id_aliquota_iva = $id_aliquota LIMIT 1";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function elimina_aliquota_iva( $id_aliquota ){
            $sql = "DELETE FROM aliquote_iva WHERE id_aliquota_iva = $id_aliquota LIMIT 1";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            return false;
        }
        
        function modifica_aliquota_iva( $aliquota_iva ){
            $aliquota_iva['nome'] = addslashes($aliquota_iva['nome']);
            $sql = "UPDATE `aliquote_iva` SET `nome` = '{$aliquota_iva['nome']}', 
                                              `aliquota` = '{$aliquota_iva['aliquota']}' 
                    WHERE `id_aliquota_iva` = {$aliquota_iva['id_aliquota_iva']} LIMIT 1;";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }
        
        
        
        /*
         * 
         *  Prodotti
         * 
         */
        function aggiungi_prodotto( $prodotto ){
            //$prodotto['codice'] = addslashes($prodotto['codice']);
            $prodotto['nome'] = addslashes($prodotto['nome']);
            $prodotto['descrizione_breve'] = addslashes($prodotto['descrizione_breve']);
            $prodotto['descrizione'] = addslashes($prodotto['descrizione']);
            $prodotto['tempo_disponibilita'] = addslashes($prodotto['tempo_disponibilita']);
            $prodotto['tempo_note'] = addslashes($prodotto['note']);
            $sql = "INSERT INTO `prodotti` (`id_prodotto`, `nome`, `id_categoria`, `prezzo_netto`, `id_aliquota_iva`, `descrizione_breve`, `descrizione`, `tipo_quantita`, `quantita_select_inizio`, `quantita_select_fine`, `quantita_select_incremento`, `tempo_disponibilita`, `visualizzazione_qta`, `promo`, `sconto_promo`, `peso`, `note`) "
                  ." VALUES (NULL, '{$prodotto['nome']}', '{$prodotto['categoria']}', '{$prodotto['prezzo_netto']}', '{$prodotto['aliquota_iva']}', '{$prodotto['descrizione_breve']}', '{$prodotto['descrizione']}', '{$prodotto['tipo_quantita']}', '{$prodotto['quantita_select_inizio']}', '{$prodotto['quantita_select_fine']}', '{$prodotto['quantita_select_incremento']}', '{$prodotto['tempo_disponibilita']}', '{$prodotto['visualizzazione_qta']}', '{$prodotto['promo']}', '{$prodotto['sconto_promo']}', '{$prodotto['peso']}', '{$prodotto['note']}');";
            if( $this->connection->query($sql)=== TRUE ){ 
                return $this->connection->insert_id;
            } else {
                return false;
            }
        }
        
        function modifica_prodotto( $prodotto ){
            //$prodotto['codice'] = addslashes($prodotto['codice']);
            $prodotto['nome'] = addslashes($prodotto['nome']);
            $prodotto['descrizione_breve'] = addslashes($prodotto['descrizione_breve']);
            $prodotto['descrizione'] = addslashes($prodotto['descrizione']);
            $prodotto['tempo_disponibilita'] = addslashes($prodotto['tempo_disponibilita']);
            $prodotto['note'] = addslashes($prodotto['note']);
            $sql = "UPDATE `prodotti` SET `nome` = '{$prodotto['nome']}', 
                                          `id_categoria` = '{$prodotto['id_categoria']}', 
                                          `prezzo_netto` = '{$prodotto['prezzo_netto']}', 
                                          `id_aliquota_iva` = '{$prodotto['id_aliquota_iva']}', 
                                          `descrizione_breve` = '{$prodotto['descrizione_breve']}', 
                                          `descrizione` = '{$prodotto['descrizione']}', 
                                          `tipo_quantita` = '{$prodotto['tipo_quantita']}', 
                                          `quantita_select_inizio` = '{$prodotto['quantita_select_inizio']}', 
                                          `quantita_select_fine` = '{$prodotto['quantita_select_fine']}', 
                                          `quantita_select_incremento` = '{$prodotto['quantita_select_incremento']}',
                                          `tempo_disponibilita` = '{$prodotto['tempo_disponibilita']}', 
                                          `visualizzazione_qta` = '{$prodotto['visualizzazione_qta']}', 
                                          `iva_inclusa` = '{$prodotto['iva_inclusa']}', 
                                          `promo` = '{$prodotto['promo']}', 
                                          `sconto_promo` = '{$prodotto['sconto_promo']}', 
                                          `peso` = '{$prodotto['peso']}', 
                                          `note` = '{$prodotto['note']}'
                  WHERE `id_prodotto` = '{$prodotto['id_prodotto']}' LIMIT 1;";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
            return true;
        }
        
        function lista_prodotti( $txt_cerca=NULL, $start=0, $limit=0 ){
            $sql = "SELECT * FROM `prodotti` ";
            if( !empty($txt_cerca) ){
                $sql .= " WHERE (nome LIKE '%$txt_cerca%') OR (descrizione_breve LIKE '%$txt_cerca%') OR (descrizione LIKE '%$txt_cerca%') ";
            }
            if( !empty($limit) ){
                $sql .= " LIMIT $start,$limit ";
            }
            $toReturn=NULL;
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
            
        }
        
        function get_prodotto( $id_prodotto ){
            $toReturn = false;
            if(empty($id_prodotto)){
                return $toReturn;
            }
            $sql = "SELECT * FROM prodotti WHERE id_prodotto = $id_prodotto LIMIT 1";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function elimina_prodotto( $id_prodotto ){
            $sql = "DELETE FROM prodotti WHERE id_prodotto = $id_prodotto LIMIT 1";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            return false;
        }
        
        function lista_prodotti_by_categoria( $id_categoria, $txt_cerca=NULL, $start=0, $limit=0 ){
            $sql = "SELECT * FROM `prodotti` WHERE id_categoria= $id_categoria";
            if( !empty($txt_cerca) ){
                $sql .= " AND ((nome LIKE '%$txt_cerca%') OR (descrizione_breve LIKE '%$txt_cerca%') OR (descrizione LIKE '%$txt_cerca%')) ";
            }
            if( !empty($limit) ){
                $sql .= " LIMIT $start,$limit ";
            }
            $toReturn=NULL;
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        
        /*
         * 
         *  Attributi
         * 
         */
        function aggiungi_attributo( $attributo ){
            $attributo['nome'] = addslashes($attributo['nome']);
            $sql = "INSERT INTO `attributi` (`id_attributo`, `id_prodotto`, `nome`) VALUES (NULL, '{$attributo['id_prodotto']}', '{$attributo['nome']}');";
            if( $this->connection->query($sql)=== TRUE ){ 
                return $this->connection->insert_id;
            } else {
                return false;
            }
        }
        
        function modifica_attributo( $attributo ){
            $attributo['nome'] = addslashes($attributo['nome']);
            $sql = "UPDATE `attributi` SET `nome` = '{$attributo['nome']}' WHERE `id_attributo` = {$attributo['id_attributo']} LIMIT 1;";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }
        
        function lista_attributi_by_prodotto( $id_prodotto ){
            $sql = "SELECT * FROM `attributi` WHERE id_prodotto= $id_prodotto";
            $toReturn=array();
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        function get_attributo( $id_attributo ){
            $toReturn = false;
            if(empty($id_attributo)){
                return $toReturn;
            }
            $sql = "SELECT * FROM attributi WHERE id_attributo = $id_attributo LIMIT 1";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function elimina_attributo( $id_attributo ){
            if( $this->verifica_attributo_in_magazzino($id_attributo) ){
                return false;
            }
            $sql = "DELETE FROM attributi WHERE id_attributo = $id_attributo LIMIT 1";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            return false;
        }
        
        
        
        /*
         * 
         *  Proprieta
         * 
         */
        function aggiungi_proprieta( $proprieta ){
            $proprieta['valore'] = addslashes($proprieta['valore']);
            $sql = "INSERT INTO `proprieta` (`id_proprieta`, `id_attributo`, `valore`, `variazione_prezzo`) VALUES (NULL, '{$proprieta['id_attributo']}', '{$proprieta['valore']}', '{$proprieta['variazione_prezzo']}');";
            if( $this->connection->query($sql)=== TRUE ){ 
                return $this->connection->insert_id;
            } else {
                return false;
            }
        }
        
        function modifica_proprieta( $proprieta ){
            $proprieta['valore'] = addslashes($proprieta['valore']);
            $sql = "UPDATE `proprieta` SET `valore` = '{$proprieta['valore']}', `variazione_prezzo` = '{$proprieta['variazione_prezzo']}' WHERE `id_proprieta` = {$proprieta['id_proprieta']} LIMIT 1;";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }
        
        function lista_proprieta_by_attributo( $id_attributo ){
            $sql = "SELECT * FROM `proprieta` WHERE id_attributo = $id_attributo";
            $toReturn=array();
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        function get_proprieta( $id_proprieta ){
            $toReturn = false;
            if(empty($id_proprieta)){
                return $toReturn;
            }
            $sql = "SELECT * FROM proprieta WHERE id_proprieta = $id_proprieta LIMIT 1";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function elimina_proprieta( $id_proprieta ){
            if( $this->verifica_proprieta_in_magazzino($id_proprieta) ){
                return false;
            }
            $sql = "DELETE FROM proprieta WHERE id_proprieta = $id_proprieta LIMIT 1";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            return false;
        }
        
        
        
        /*
         * 
         * Amministratore
         * 
         */
        
        function verifica_pwd_admin($pwd){
            $pwd = md5($pwd);
            $sql = "SELECT * FROM `admin_pwd` WHERE `pwd`= '$pwd' " ;
            if ( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                return true;
            }
            return false;
        }
        
        function modifica_pwd_admin($pwd){
            $pwd = md5($pwd);
            $sql = "UPDATE `admin_pwd` SET `pwd` = '$pwd' WHERE 1 ";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }
        
        
        
        /*
         * 
         * Ordini
         * 
         */
        
        function aggiungi_ordine( $ordine ){
            $ordine['indirizzo_spedizione'] = addslashes($ordine['indirizzo_spedizione']);
            $sql = "INSERT INTO `ordini` (`id_ordine`, `id_utente`, `data`, `codice_spedizione`, `indirizzo_spedizione`, `costo_spedizione`, `codice_pagamento`, `costo_pagamento`, `indirizzo_ip`, `stato_ordine` ) "
                ." VALUES (NULL, '{$ordine['id_utente']}', '{$ordine['data']}', '{$ordine['codice_spedizione']}', '{$ordine['indirizzo_spedizione']}', '{$ordine['costo_spedizione']}', '{$ordine['codice_pagamento']}', '{$ordine['costo_pagamento']}', '{$ordine['indirizzo_ip']}', '{$ordine['stato_ordine']}');";
            if( $this->connection->query($sql)=== TRUE ){ 
                return $this->connection->insert_id;
            } else {
                return false;
            }
        }
        
        function lista_ordini( $start=0, $limit=0){
            $sql = "SELECT * FROM `ordini` WHERE 1 ORDER BY id_ordine DESC ";
            if( !empty($limit) ){
                $sql .= " LIMIT $start,$limit ";
            }
            $toReturn=NULL;
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        function lista_ordini_by_utente($id_utente, $start=0, $limit=0){
            $sql = "SELECT * FROM `ordini` WHERE id_utente = $id_utente ORDER BY id_ordine DESC ";
            if( !empty($limit) ){
                $sql .= " LIMIT $start,$limit ";
            }
            $toReturn=NULL;
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        function get_ordine( $id_ordine ){
            $toReturn = false;
            if(empty($id_ordine)){
                return $toReturn;
            }
            $sql = "SELECT * FROM ordini WHERE id_ordine = $id_ordine LIMIT 1";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function modifica_ordine( $ordine ){
            $ordine['indirizzo_spedizione'] = addslashes($ordine['indirizzo_spedizione']);
            $ordine['data_pagamento'] = (!empty($ordine['data_pagamento'])) ? '\''.addslashes($ordine['data_pagamento']).'\'' : 'NULL';
            $ordine['data_spedizione'] = (!empty($ordine['data_spedizione'])) ? '\''.addslashes($ordine['data_spedizione']).'\'' : 'NULL';
            $ordine['traking_number'] = addslashes($ordine['traking_number']);
            $ordine['note'] = addslashes($ordine['note']);
            $ordine['note_admin'] = addslashes($ordine['note_admin']);
            $sql = "UPDATE `ordini` SET `data` = '{$ordine['data']}', 
                                        `codice_spedizione` = '{$ordine['codice_spedizione']}', 
                                        `indirizzo_spedizione` = '{$ordine['indirizzo_spedizione']}', 
                                        `costo_spedizione` = '{$ordine['costo_spedizione']}', 
                                        `codice_pagamento` = '{$ordine['codice_pagamento']}', 
                                        `costo_pagamento` = '{$ordine['costo_pagamento']}', 
                                        `indirizzo_ip` = '{$ordine['indirizzo_ip']}',
                                        `note` = '{$ordine['note']}',
                                        `note_admin` = '{$ordine['note_admin']}',
                                        `data_pagamento` = {$ordine['data_pagamento']},
                                        `data_spedizione` = {$ordine['data_spedizione']},
                                        `traking_number` = '{$ordine['traking_number']}',
                                        `stato_ordine` = '{$ordine['stato_ordine']}' 
                    WHERE `id_ordine` = {$ordine['id_ordine']} LIMIT 1;";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }
        
        function elimina_ordine( $id_ordine ){
            $sql = "DELETE FROM ordini WHERE id_ordine = $id_ordine LIMIT 1";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            return false;
        }
        
        /*
         * 
         * Ordini Items
         * 
         */
        function aggiungi_item_ordine( $id_ordine, $item ){
            foreach ($item as $key => $value) {
                $item[$key] = addslashes($item[$key]);
            }
            $sql = "INSERT INTO `ordini_items` (`id_ordine_item`, `id_ordine`, `id_prodotto`, `nome_prodotto`, `descrizione_breve`, `attributi`, `prezzo_unitario`, `aliquota_iva`, `qta`, `prezzo_totale`, `peso`) "
                ." VALUES (NULL, '$id_ordine', '{$item['id_prodotto']}', '{$item['nome_prodotto']}', '{$item['descrizione_breve']}', '{$item['attributi']}', '{$item['prezzo_unitario']}', '{$item['aliquota_iva']}', '{$item['qta']}', '{$item['prezzo_totale']}', '{$item['peso']}');";
            if( $this->connection->query($sql)=== TRUE ){ 
                return $this->connection->insert_id;
            } else {
                return false;
            }
        }
        
        function get_item( $id_item ){
            $toReturn = false;
            if(empty($id_item)){
                return $toReturn;
            }
            $sql = "SELECT * FROM `ordini_items` WHERE id_ordine_item = $id_item LIMIT 1";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function lista_item_ordine($id_ordine){
            $sql = "SELECT * FROM `ordini_items` WHERE id_ordine = $id_ordine ";
            $toReturn=NULL;
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        function modifica_item_ordine( $item ){
            foreach ($item as $key => $value) {
                $item[$key] = addslashes($item[$key]);
            }
            $sql = "UPDATE `ordini_items` SET `id_prodotto` = '{$item['id_prodotto']}', 
                                              `nome_prodotto` = '{$item['nome_prodotto']}', 
                                              `descrizione_breve` = '{$item['descrizione_breve']}', 
                                              `attributi` = '{$item['attributi']}', 
                                              `prezzo_unitario` = '{$item['prezzo_unitario']}', 
                                              `aliquota_iva` = '{$item['aliquota_iva']}', 
                                              `qta` = '{$item['qta']}', 
                                              `prezzo_totale` = '{$item['prezzo_totale']}', 
                                              `attivo` = '{$item['attivo']}' 
                          WHERE `id_ordine_item` = '{$item['id_ordine_item']}' LIMIT 1;";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }
        
        
        /*
         * Costo Corriere Exp
         * 
         */
        
        
        function get_costo_corriere_exp($provincia, $da_peso, $a_peso){
            $toReturn = false;
            $sql = "SELECT * FROM `costo_corriere_exp` WHERE provincia = '$provincia' AND da_peso = $da_peso AND a_peso = $a_peso LIMIT 1";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        private function aggiungi_costo_corriere_exp( $costo_corriere_exp ){
            $sql = "INSERT INTO `costo_corriere_exp` (`id_costo_corriere_exp`, `provincia`, `da_peso`, `a_peso`, `costo`) VALUES 
                (NULL, '{$costo_corriere_exp['provincia']}', '{$costo_corriere_exp['da_peso']}', '{$costo_corriere_exp['a_peso']}', '{$costo_corriere_exp['costo']}');";
            if( $this->connection->query($sql)=== TRUE ){ 
                return $this->connection->insert_id;
            } else {
                return false;
            }
        }
        
        function modifica_costo_corriere_exp( $costo_corriere_exp ){
            $costo_corriere_exp['provincia'] = strtoupper($costo_corriere_exp['provincia']);
            foreach ($costo_corriere_exp as $key => $value) {
                $costo_corriere_exp[$key] = addslashes($costo_corriere_exp[$key]);
            }
            $sql = "UPDATE `costo_corriere_exp` SET `da_peso` = '{$costo_corriere_exp['da_peso']}',
                                                    `a_peso` = '{$costo_corriere_exp['a_peso']}',
                                                    `costo` = '{$costo_corriere_exp['costo']}' 
                    WHERE `provincia` = '{$costo_corriere_exp['provincia']}' 
                          AND `da_peso` = '{$costo_corriere_exp['da_peso']}'
                          AND `a_peso` = '{$costo_corriere_exp['a_peso']}' LIMIT 1;";
            if($this->get_costo_corriere_exp($costo_corriere_exp['provincia'], $costo_corriere_exp['da_peso'], $costo_corriere_exp['a_peso'])){
                if( $this->connection->query($sql) === TRUE ){
                    return true; 
                }else{
                    return false;
                }
            }else{
                return $this->aggiungi_costo_corriere_exp( $costo_corriere_exp );
            }
        }
        
        function lista_costi_corriere_exp(){
            $sql = "SELECT * FROM `costo_corriere_exp` ";
            $toReturn=array();
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        function elimina_costo_corriere_exp( $provincia, $da_peso, $a_peso ){
            $sql = "DELETE FROM `costo_corriere_exp` WHERE provincia = '$provincia' AND da_peso='$da_peso' AND a_peso='$a_peso' LIMIT 1";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            return false;
        }
        
        function get_calcolo_costo_corriere_espresso($provincia='TUTTE', $peso){
            $sql = "SELECT sum(costo) as costo FROM `costo_corriere_exp` WHERE ((provincia = 'TUTTE') OR (provincia='$provincia')) AND ($peso>da_peso) AND ($peso<=a_peso) ";
            //echo $sql;
            $toReturn=array();
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        
        /*
         * Fornitori
         * 
         */
        function aggiungi_fornitore( $fornitore ){
            foreach ($fornitore as $key => $value) {
                $fornitore[$key] = addslashes($fornitore[$key]);
            }
            $sql = "INSERT INTO `fornitori` (`id_fornitore`, `denominazione`, `piva`, `provincia`, `citta`, `email`, `telefono`, `fax`, `nome_banca`, `iban`) VALUES "
                    ." (NULL, '{$fornitore['denominazione']}', '{$fornitore['piva']}', '{$fornitore['provincia']}', '{$fornitore['citta']}', '{$fornitore['email']}', '{$fornitore['telefono']}', '{$fornitore['fax']}', '{$fornitore['nome_banca']}', '{$fornitore['iban']}');";
            if( $this->connection->query($sql)=== TRUE ){ 
                return $this->connection->insert_id;
            } else {
                return false;
            }
        }

        
        function lista_fornitori( $txt_cerca=NULL, $start=0, $limit=0 ){
            $sql = "SELECT * FROM `fornitori` ";
            if( !empty($txt_cerca) ){
                $sql .= " WHERE (denominazione LIKE '%$txt_cerca%') OR (id_fornitore = '$txt_cerca') ";
            }
            if( !empty($limit) ){
                $sql .= " LIMIT $start,$limit ";
            }
            $toReturn=NULL;
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        function get_fornitore( $id_fornitore ){
            $toReturn = false;
            if(empty($id_fornitore)){
                return $toReturn;
            }
            $sql = "SELECT * FROM fornitori WHERE id_fornitore = $id_fornitore LIMIT 1";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        
        function elimina_fornitore( $id_fornitore ){
            $sql = "DELETE FROM fornitori WHERE id_fornitore = $id_fornitore LIMIT 1";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            return false;
        }
        
        function modifica_fornitore( $fornitore ){
            foreach ($fornitore as $key => $value) {
                $fornitore[$key] = addslashes($fornitore[$key]);
            }
            $sql = "UPDATE `fornitori` SET `denominazione` = '{$fornitore['denominazione']}', 
                                            `piva` = '{$fornitore['piva']}', 
                                            `provincia` = '{$fornitore['provincia']}', 
                                            `citta` = '{$fornitore['citta']}', 
                                            `email` = '{$fornitore['email']}', 
                                            `telefono` = '{$fornitore['telefono']}', 
                                            `fax` = '{$fornitore['fax']}', 
                                            `nome_banca` = '{$fornitore['nome_banca']}', 
                                            `iban` = '{$fornitore['iban']}' 
                    WHERE `id_fornitore` = {$fornitore['id_fornitore']} LIMIT 1;";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }
        
        
        /*
         * Marchi
         * 
         */
        function aggiungi_marchio( $marchio ){
            foreach ($marchio as $key => $value) {
                $marchio[$key] = addslashes($marchio[$key]);
            }
            $sql = "INSERT INTO `marchi` (`id_marchio`, `nome`) VALUES "
                    ." (NULL, '{$marchio['nome']}' );";
            if( $this->connection->query($sql)=== TRUE ){ 
                return $this->connection->insert_id;
            } else {
                return false;
            }
        }

        
        function lista_marchi( $txt_cerca=NULL, $start=0, $limit=0 ){
            $sql = "SELECT * FROM `marchi` ";
            if( !empty($txt_cerca) ){
                $sql .= " WHERE (nome LIKE '%$txt_cerca%') ";
            }
            if( !empty($limit) ){
                $sql .= " LIMIT $start,$limit ";
            }
            $toReturn=NULL;
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        function get_marchio( $id_marchio ){
            $toReturn = false;
            if(empty($id_marchio)){
                return $toReturn;
            }
            $sql = "SELECT * FROM marchi WHERE id_marchio = $id_marchio LIMIT 1";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function elimina_marchio( $id_marchio ){
            $sql = "DELETE FROM marchi WHERE id_marchio = $id_marchio LIMIT 1";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            return false;
        }
        
        function modifica_marchio( $marchio ){
            foreach ($marchio as $key => $value) {
                $marchio[$key] = addslashes($marchio[$key]);
            }
            $sql = "UPDATE `marchi` SET `nome` = '{$marchio['nome']}' 
                    WHERE `id_marchio` = {$marchio['id_marchio']} LIMIT 1;";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }
        
        
        /*
         * Associazione Marchi -> Fornitori
         * 
         */
        
        function aggiungi_assoc_marchio_fornitore($assoc_marchio_fornitore) {
            $sql = "INSERT INTO `assoc_marchi_fornitori` (`id_marchio`, `id_fornitore`) VALUES "
                    ." ('{$assoc_marchio_fornitore['id_marchio']}', '{$assoc_marchio_fornitore['id_fornitore']}');";
            if( $this->connection->query($sql)=== TRUE ){ 
                return true;
            } else {
                return false;
            }
        }
        
        function lista_assoc_marchi_fornitori( $id_fornitore, $id_marchio=NULL ){
            $sql = "SELECT * FROM `assoc_marchi_fornitori` WHERE ";
            if( !is_null($id_fornitore) ){ 
                $sql .= "id_fornitore = $id_fornitore"; 
            }  elseif ( !is_null ($id_marchio) ) {
                $sql .= "id_marchio = $id_marchio"; 
            }
            $toReturn=NULL;
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        function get_assoc_marchio_fornitore($id_marchio, $id_fornitore) {
            $sql="SELECT * FROM `assoc_marchi_fornitori` WHERE id_marchio=$id_marchio AND id_fornitore=$id_fornitore";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function elimina_assoc_marchio_fornitore($id_marchio, $id_fornitore) {
            $sql="DELETE FROM assoc_marchi_fornitori WHERE id_marchio=$id_marchio AND id_fornitore=$id_fornitore LIMIT 1";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            return false;
        }
        
        
        /*
         * 
         * News
         * 
         */
        function aggiungi_news($news) {
            foreach ($news as $key => $value) {
                $news[$key] = addslashes($news[$key]);
            }
            $sql = "INSERT INTO `news` (`id_news`, `titolo`, `descrizione`) "
                 ."VALUES (NULL, '{$news['titolo']}', '{$news['descrizione']}');";
            if( $this->connection->query($sql)=== TRUE ){ 
                return $this->connection->insert_id;
            } else {
                return false;
            }
        }
        
        function lista_news( $inizio=NULL, $lunghezza=NULL ){
            $sql = "SELECT * FROM `news` WHERE 1 ORDER BY id_news DESC";
            if( isset($inizio) && !empty($lunghezza)){
                $sql .= " LIMIT $inizio, $lunghezza ";
            }
            $toReturn=array();
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        function get_news( $id_news ){
            $toReturn = false;
            if(empty($id_news)){
                return $toReturn;
            }
            $sql = "SELECT * FROM news WHERE id_news = $id_news LIMIT 1";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function modifica_news( $news ){
            $news['titolo'] = addslashes($news['titolo']);
            $news['descrizione'] = addslashes($news['descrizione']);
            $sql = "UPDATE `news` SET `titolo` = '{$news['titolo']}', 
                                            `descrizione` = '{$news['descrizione']}'
                    WHERE `id_news` = {$news['id_news']} LIMIT 1;";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }
        
        function elimina_news( $id_news ){
            $sql = "DELETE FROM news WHERE id_news = $id_news LIMIT 1";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            return false;
        }
        
        
        /*
         * 
         * Fatture
         * 
         */
        function aggiungi_fattura($fattura) {
            foreach ($fattura as $key => $value) {
                $fattura[$key] = addslashes($fattura[$key]);
            }
            $sql = "INSERT INTO `fatture` (`num_fattura`, `anno`, `data`, `intestazione`, `id_ordine`)
                    VALUES ( 
                    (SELECT IFNULL(MAX(`num_fattura`), 0) +1 FROM fatture AS F WHERE anno={$fattura['anno']} ), 
                    '{$fattura['anno']}', 
                    '{$fattura['data']}', 
                    '{$fattura['intestazione']}', 
                    '{$fattura['id_ordine']}');";
            if( $this->connection->query($sql)=== TRUE ){ 
                return true;
            } else {
                return false;
            }
        }
        
        function get_fattura( $num_fattura, $anno ){
            $toReturn = false;
            if(empty($num_fattura) || empty($anno)){
                return $toReturn;
            }
            $sql = "SELECT * FROM fatture WHERE num_fattura = $num_fattura AND anno=$anno LIMIT 1";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function get_fattura_by_ordine( $id_ordine ){
            $toReturn = false;
            if(empty($id_ordine)){
                return $toReturn;
            }
            $sql = "SELECT * FROM fatture WHERE id_ordine = $id_ordine LIMIT 1";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function lista_fatture(){
            $sql = "SELECT * FROM `fatture` WHERE 1 ";
            $toReturn=array();
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        function modifica_fattura($old_num_fattura, $fattura){
            $sql = "UPDATE `fatture` SET `num_fattura` = '{$fattura['num_fattura']}',
                                         data = '{$fattura['data']}'
                    WHERE `num_fattura` = $old_num_fattura AND `anno` = '{$fattura['anno']}' LIMIT 1;";
            if( $this->connection->query($sql) === TRUE ){
                $this->pulisci_magazzino();
                return true;
            }
            else{
                return false;
            }
        }




        /*
         * 
         * Magazzino
         * 
         */
        
        function aggiungi_a_magazzino($magazzino){
            $magazzino['attributi'] = (empty($magazzino['attributi'])) ? '' : implode('|', $magazzino['attributi']);
            $sql = "INSERT INTO `magazzino` (`id_prodotto`, `attributi`, `qta`) 
                    VALUES ('{$magazzino['id_prodotto']}', '{$magazzino['attributi']}', '{$magazzino['qta']}');";
            if( $this->connection->query($sql)=== TRUE ){ 
                $this->pulisci_magazzino();
                return true;
            } else {
                if( ($this->connection->errno == 1062) && $this->modifica_magazzino($magazzino) ){  //chiave primaria già esistente
                    return true;
                }
                return false;
            }
        }
        
        function lista_magazzino_by_prodotto( $id_prodotto ){
            $sql = "SELECT * FROM `magazzino` WHERE id_prodotto = $id_prodotto ";
            $toReturn=array();
            $indice=0;
            if( ($risultato = $this->connection->query($sql)) && ($this->connection->affected_rows>0) ){
                while ( $riga = mysqli_fetch_assoc($risultato) ){
                    $riga['attributi'] = (empty($riga['attributi'])) ? array() : explode('|', $riga['attributi']);
                    $toReturn[$indice]=$riga;
                    $indice++;
                }
            }
            return $toReturn;
        }
        
        function modifica_magazzino($magazzino){
            if ( is_array($magazzino['attributi']) ){
                $magazzino['attributi'] = (empty($magazzino['attributi'])) ? '' : implode('|', $magazzino['attributi']);
            }
            $sql = "UPDATE `magazzino` SET `qta` = `qta` + '{$magazzino['qta']}' 
                    WHERE `id_prodotto` = {$magazzino['id_prodotto']} AND `attributi` = '{$magazzino['attributi']}' LIMIT 1;";
            if( $this->connection->query($sql) === TRUE ){
                $this->pulisci_magazzino();
                return true;
            }
            else{
                return false;
            }
        }
        
        function pulisci_magazzino(){
            $sql="DELETE FROM magazzino WHERE qta <= 0";
            if( $this->connection->query($sql) === TRUE ){
                return true;
            }
            else{
                return false;
            }
        }
        
        function get_magazzino($id_prodotto, $proprieta){
            if ( is_array($proprieta) ){
                $proprieta = (empty($proprieta)) ? '' : implode('|', $proprieta);
            }
            $toReturn = array();
            $sql = "SELECT * FROM `magazzino` WHERE `id_prodotto` = $id_prodotto AND `attributi` = '$proprieta' LIMIT 1 ";
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $riga = mysqli_fetch_array($result);
                $toReturn=$riga;
            }
            return $toReturn;
        }
        
        function verifica_proprieta_in_magazzino($id_proprieta){
            $sql = "SELECT * FROM `magazzino` WHERE `attributi` LIKE '%$id_proprieta%'  ";
            $toReturn = false;
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $toReturn = true;
            }
            return $toReturn;
        }
        
        function verifica_attributo_in_magazzino($id_attributo){
            $lista_proprieta = $this->lista_proprieta_by_attributo($id_attributo);
            if( empty($lista_proprieta) ){ return false; }
            $tmp = array();
            foreach ($lista_proprieta as $proprieta) {
                $tmp[] = " (attributi LIKE '%{$proprieta['id_proprieta']}%') ";
            }
            $where = implode(' OR ', $tmp);
            unset ($tmp);
            $sql = "SELECT * FROM `magazzino` WHERE $where  ";
            $toReturn = false;
            if( ($result = $this->connection->query($sql)) && ( $this->connection->affected_rows>0 ) ){
                $toReturn = true;
            }
            return $toReturn;
        }
}


?>

