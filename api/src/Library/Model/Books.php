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
		$sql = "SELECT *.books,publishers.name,authors.name 
				FROM books
				INNER JOIN authors
				ON books.author_id = authors.id
				INNER JOIN publishers
				ON books.publisher_id = publishers.id
				WHERE id = :id LIMIT 1";
		$query = \Library\Db::getInstance() -> prepare($sql);

		$values = array(':id' => $id);
		$query -> execute($values);

		return $query -> fetch(\PDO::FETCH_ASSOC);
	}

	public function update($data) {
		$query = \Library\Db::getInstance() -> prepare("UPDATE books 
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
		$data = array(':title' => $title);
		$query -> execute($data);
		return $query -> fetchAll(\PDO::FETCH_ASSOC);
	}

	public function findByAuthor($author) {
		$sql = "SELECT *.books,authors.name 
	    	FROM books,authors 
	    	WHERE 
	    		books.author_id = authors.id AND
	    		authors.name LIKE %:author%";
		$query = \Library\Db::getInstance() -> prepare($sql);
		$data = array(':author' => $author);
		$query -> execute($data);
		return $query -> fetchAll(\PDO::FETCH_ASSOC);
	}

	public function findByPublisher($publisher) {
		$sql = "SELECT *.books,publishers.name 
	    	FROM books,publishers 
	    	WHERE 
	    		books.publisher_id = publishers.id AND
	    		publishers.name LIKE %:publisher%";
		$query = \Library\Db::getInstance() -> prepare($sql);
		$data = array(':publisher' => $publisher);
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