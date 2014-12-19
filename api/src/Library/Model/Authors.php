<?php
namespace Library\Model;

class Authors {
	public function getAll() {
		$sql = "SELECT * FROM authors";
		$query = \Library\Db::getInstance() -> prepare($sql);
		$query -> execute();
		return $query -> fetchAll(\PDO::FETCH_ASSOC);
	}

	public function add($data) {
		$query = \Library\Db::getInstance() -> prepare("INSERT INTO authors (
					name,
					address
				) VALUES (
					:name,
					:address
				)");

		$data = array(':name' => $data['name'], ':address' => $data['address']);

		return $query -> execute($data);
	}

	public function getAuthor($id) {
		$sql = "SELECT * FROM authors WHERE id = :id LIMIT 1";
		$query = \Library\Db::getInstance() -> prepare($sql);

		$values = array(':id' => $id);
		$query -> execute($values);

		return $query -> fetch(\PDO::FETCH_ASSOC);
	}

	public function update($data) {
		$query = \Library\Db::getInstance() -> prepare("UPDATE authors 
	            SET 
	                name = :name, 
					address = :address
	            WHERE
	                id = :id");

		$data = array(':id' => $data['id'], ':name' => $data['name'], ':address' => $data['address']);

		return $query -> execute($data);
	}

	public function delete($id) {
		$result = $this -> getAuthor($id);
		if ($result != NULL) {

			$query = \Library\Db::getInstance() -> prepare("DELETE FROM authors
					WHERE
				id = :id");

			$data = array(':id' => $id, );

			return $query -> execute($data);
		} else {
			return false;
		}
	}

	public function findByName($name) {
		$sql = "SELECT * FROM authors WHERE name LIKE %:name%";
		$query = \Library\Db::getInstance() -> prepare($sql);
		$data = array(':name' => $name);
		$query -> execute($data);
		return $query -> fetchAll(\PDO::FETCH_ASSOC);
	}

	public function findByAddress($address) {
		$sql = "SELECT * FROM authors WHERE address LIKE %:address%";
		$query = \Library\Db::getInstance() -> prepare($sql);
		$data = array(':address' => $address);
		$query -> execute($data);
		return $query -> fetchAll(\PDO::FETCH_ASSOC);
	}

}
?>