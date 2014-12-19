<?php
namespace Library\Model;

class Books {
	public function getAll() {
		$sql = "SELECT *.books,publisher.name,author.name 
				FROM books
				INNER JOIN author
				ON books.author_id = author.id
				INNER JOIN publisher
				ON books.publisher_id = publisher.id";
		$query = \Library\Db::getInstance() -> prepare($sql);
		$query -> execute();
		return $query -> fetchAll(\PDO::FETCH_ASSOC);
	}

	public function add($data) {
		$query = \Library\Db::getInstance() -> prepare("INSERT INTO books (
					title, 
					description,
					author_id, 
					publisher_id, 
					year, 
					isbn 
				) VALUES (
					:title, 
					:description,
					:author_id, 
					:publisher_id, 
					:year, 
					:isbn 
				)");

		$data = array(':title' => $data['title'], ':description' => $data['description'], ':author_id,' => $data['author_id'], ':publisher_id' => $data['publisher_id'], ':year' => $data['year'], ':isbn' => $data['isbn']);

		return $query -> execute($data);
	}

	public function getBook($id) {
		$sql = "SELECT *.books,publisher.name,author.name 
				FROM books
				INNER JOIN author
				ON books.author_id = author.id
				INNER JOIN publisher
				ON books.publisher_id = publisher.id
				WHERE id = :id LIMIT 1";
		$query = \Library\Db::getInstance() -> prepare($sql);

		$values = array(':id' => $id);
		$query -> execute($values);

		return $query -> fetch(\PDO::FETCH_ASSOC);
	}

	public function update($data) {
		$query = \Library\Db::getInstance() -> prepare("UPDATE mesin 
	            SET 
	                title = :title, 
					description = :description,
					author_id = :author_id, 
					publisher_id = :publisher_id, 
					year = :year, 
					isbn = :isbn
	            WHERE
	                id = :id");

		$data = array(':id' => $data['id'], ':title' => $data['title'], ':description' => $data['description'], ':author_id,' => $data['author_id'], ':publisher_id' => $data['publisher_id'], ':year' => $data['year'], ':isbn' => $data['isbn']);

		return $query -> execute($data);
	}

	public function delete($id) {
		$result = $this -> getBook($id);
		if ($result != NULL) {

			$query = \Library\Db::getInstance() -> prepare("DELETE FROM books
					WHERE
				id = :id");

			$data = array(':id' => $id, );

			return $query -> execute($data);
		} else {
			return false;
		}
	}

	public function findByTitle($title) {
		$sql = "SELECT * FROM books WHERE title LIKE %:title%";
		$query = \Library\Db::getInstance() -> prepare($sql);
		$data = array(':title' => $title, );
		$query -> execute($data);
		return $query -> fetchAll(\PDO::FETCH_ASSOC);
	}

	public function findByAuthor($author) {
		$sql = "SELECT *.books,author.name 
	    	FROM books,author 
	    	WHERE 
	    		books.author_id = author.id AND
	    		author.name LIKE %:author%";
		$query = \Library\Db::getInstance() -> prepare($sql);
		$data = array(':author' => $author, );
		$query -> execute($data);
		return $query -> fetchAll(\PDO::FETCH_ASSOC);
	}

	public function findByPublisher($publisher) {
		$sql = "SELECT *.books,publisher.name 
	    	FROM books,publisher 
	    	WHERE 
	    		books.publisher_id = publisher.id AND
	    		publisher.name LIKE %:publisher%";
		$query = \Library\Db::getInstance() -> prepare($sql);
		$data = array(':publisher' => $publisher, );
		$query -> execute($data);
		return $query -> fetchAll(\PDO::FETCH_ASSOC);
	}

	public function findByISBN($isbn) {
		$sql = "SELECT * FROM books WHERE ISBN = :ISBN";
		$query = \Library\Db::getInstance() -> prepare($sql);
		$data = array(':ISBN' => $isbn, );
		$query -> execute($data);
		return $query -> fetchAll(\PDO::FETCH_ASSOC);
	}

}
?>