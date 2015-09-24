<?php
class Database{
    const CONN_SERVER	= "localhost";
    const CONN_DATABASE	= "database";
    const CONN_USER   	= "root";
    const CONN_PASS     = "";
    ########################################
    public $conn;

    public function __construct(){
        $this->conn = mysqli_connect(self::CONN_SERVER,self::CONN_USER,self::CONN_PASS);
        if (!$this->conn){
            echo 'Error: '.mysqli_error();
//            Redirect to error page
//            header('Location: errorMysql.php');
            exit;
        }
        $this->setDataBase();
    }


    ### PRIVATE FUNCTIONS ###
//    DEFINE DATABASE
    private function setDataBase(){
        mysqli_select_db($this->conn, self::CONN_DATABASE);
        mysqli_set_charset($this->conn, 'utf8');
    }

//    EXECUTE COMMAND SQL
    private function execute($sql, $debug = false){
        $this->setDataBase();
        if (!$debug){
            return mysqli_query($this->conn, $sql);
        } else {
            echo $sql; exit;
        }
    }

    ### PROTECTED FUNCTIONS ###

//    TRANSFORM ARRAY TO QUERY IN WHERE COMMAND
    protected function prepareWhere($array){
        $where = null;
        foreach($array as $key => $value){
            $where .= $key." = '".$this->escape(trim($value))."' AND ";
        }
        $where = substr($where, 0, -5);
        return $where;
    }

//    ESCAPE STRINGS
    protected function escape($value){
        return mysqli_real_escape_string($this->conn, $value);
    }

    ### PUBLIC FUNCTIONS ###

//    USE A PRESONAL QUERY
    public function query($sql, $debug = false){
        return $this->execute($sql, $debug);
    }

//    USE SELECT COMMAND
    public function select($tabela, $where = null, $debug = false){
        $sql = 'SELECT * FROM '.$tabela.' '.$where;
        return $this->execute($sql, $debug);
    }

//    USE INSERT COMMAND
    public function insert($tabela, $keys_values, $debug = false){
        foreach($keys_values as $key => $value){
            $arr_keys[] = $key;
            if ($value != ''){
                $arr_values[] = "'".$this->escape(trim($value))."'";
            }  else {
                $arr_values[] = " NULL ";
            }
        }

        $keys = implode(', ', $arr_keys);
        $values = implode(', ', $arr_values);

        $sql = 'INSERT INTO '.$tabela.' ('.$keys.') VALUES ('.$values.')';
        return $this->execute($sql, $debug);
    }

//    RETURN LAST INSERTED ID
    public function lastInsertId(){
        if($this->conn){
            return mysqli_insert_id($this->conn);
        }
        return 0;
    }

//    USE UPDATE COMMAND
    public function update($tabela, $keys_values, $where, $debug = false){
        $insert = NULL;
        foreach($keys_values as $key => $value){
            if ($value != ''){
                $insert .= $key." = '".$this->escape(trim($value))."', ";
            }  else {
                $insert .= $key." = NULL, ";
            }
        }
        $insert = substr($insert,0,-2);
        $sql = 'UPDATE '.$tabela.' SET '.$insert.' WHERE '.$this->prepareWhere($where);
        return $this->execute($sql, $debug);
    }

//    USE DELETE COMMAND
    public function delete($tabela, $keys_values, $debug = false){
        $sql = 'DELETE FROM '.$tabela.' WHERE '.$this->prepareWhere($keys_values);
        return $this->execute($sql, $debug);
    }

//    LOOK FOR SPECIFY LINE
    public function changeLine($result, $line){
        mysqli_data_seek($result, $line);
    }

//    USE FELL ARRAY IN RESULT TO LOOK QUERY LINE BY LINE
    public function feelArray($result){
        return mysqli_fetch_assoc($result);
    }

//    RETURN NUM OF ROWS RETURNED BY QUERY
    public function numRows($result, $number = false){
        if (!$number){
            if (mysqli_num_rows($result) > 0){
                return true;
            } else {
                return false;
            }
        } else {
            return mysqli_num_rows($result);
        }
    }

//    FREE RESULT
    public function freeResult($result){
        mysqli_free_result($result);
    }

//    CLOSE CONNECTION
    public function closeConnection(){
        mysqli_close($this->conn);
    }
}
?>