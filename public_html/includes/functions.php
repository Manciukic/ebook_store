<?php
include "db_connect.php";

function get_by_genre($genre)
{
    global $mysqli;
    $query = $mysqli->prepare(
        "SELECT B.id, B.title, B.author, B.price
                FROM ebooks B INNER JOIN ebook_genre EG ON B.id = EG.ebook_id
                WHERE EG.genre_id = ? "
    );
    $query->bind_param("i", $genre);
    $query->execute();
    return $query->get_result();
}

function get_genre_name($id)
{
    global $mysqli;
    $genre_query = $mysqli->prepare(
        "SELECT G.name
                FROM genres G 
                WHERE G.id = ?"
    );
    $genre_query->bind_param("i", $id);
    $genre_query->execute();
    $genre_result = $genre_query->get_result();
    return $genre_result ? $genre_result->fetch_array() : false; //TODO
}

function get_book($id)
{
    global $mysqli;
    $book_query = $mysqli->prepare(
        "SELECT *
                FROM ebooks B
                WHERE B.id = ? "
    );
    $book_query->bind_param("i", $id);
    $book_query->execute();
    $book_result = $book_query->get_result();
    return $book_result ? $book_result->fetch_array() : false;
}

function get_book_genres($book_id)
{
    global $mysqli;
    $genre_query = $mysqli->prepare(
        "SELECT G.id, G.name
                FROM ebook_genre EG INNER JOIN genres G
                ON EG.genre_id = G.id 
                WHERE EG.ebook_id = ?"
    );
    $genre_query->bind_param("i", $book_id);
    $genre_query->execute();
    return $genre_query->get_result();
}

function get_books($book_ids)
{
    global $mysqli;
    $nbooks =  count($book_ids);
    $book_ids_query = implode(',', array_fill(0, $nbooks, '?'));

    if ($nbooks > 0) {
        $stmt = $mysqli->prepare("
            SELECT *
            FROM ebooks
            WHERE id IN (" . $book_ids_query . ")
        ");
        $stmt->bind_param(str_repeat('i', $nbooks), ...$book_ids);
        $stmt->execute();
        $cart = $stmt->get_result();
    } else {
        return false;
    }
    return $cart;
}
