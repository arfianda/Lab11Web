<?php
class Database
{
    protected $host;
    protected $user;
    protected $password;
    protected $db_name;
    protected $conn;

    public function __construct()
    {
        $this->getConfig();
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->db_name);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    private function getConfig()
    {
        include("config.php");
        $this->host = $config['host'];
        $this->user = $config['username'];
        $this->password = $config['password'];
        $this->db_name = $config['db_name'];
    }

    public function query($sql)
    {
        return $this->conn->query($sql);
    }

    public function get($table, $where = null)
    {
        $whereClause = "";
        if ($where) {
            $whereClause = " WHERE " . $where;
        }

        $sql = "SELECT * FROM " . $table . $whereClause;
        $result = $this->conn->query($sql);

        if ($result) {
            // Jika hasil lebih dari satu, kembalikan array, jika hanya satu, kembalikan asosiatif
            if ($result->num_rows > 1) {
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            } else {
                return $result->fetch_assoc();
            }
        }
        return false;
    }

    public function insert($table, $data)
    {
        if (is_array($data)) {
            $column = [];
            $value = [];
            foreach ($data as $key => $val) {
                $column[] = $key;
                $value[] = "'{$val}'";
            }
            $columns = implode(",", $column);
            $values = implode(",", $value);
        } else {
            return false;
        }

        $sql = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ")";
        $result = $this->conn->query($sql);

        if ($result == true) {
            return $result;
        } else {
            return false;
        }
    }

    public function update($table, $data, $where)
    {
        $update_value = [];
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $update_value[] = "$key='{$val}'";
            }
        } else {
            return false;
        }

        $update_value_str = implode(",", $update_value);

        $sql = "UPDATE " . $table . " SET " . $update_value_str . " WHERE " . $where;
        $result = $this->conn->query($sql);

        if ($result == true) {
            return true;
        } else {
            return false;
        }
    }
}
?>