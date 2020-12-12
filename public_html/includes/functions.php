<?php
include "db_connect.php";

function get_all_genres(){
    global $mysqli;
    return $mysqli->query("SELECT * FROM genres ORDER BY name ASC");
}

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

function get_credit_cards($user_id)
{
    global $mysqli;
    $card_query = $mysqli->prepare(
        "SELECT CC.id AS id, SUBSTRING(CC.number, 12, 4) AS last_digits, CC.expiration AS expiration
            FROM credit_cards CC
            WHERE CC.user_id = ?"
    );
    $card_query->bind_param("i", $user_id);
    $card_query->execute();
    return $card_query->get_result();
}

function get_credit_card($card_id){
    global $mysqli;
    $card_query = $mysqli->prepare(
        "SELECT CC.id AS id, SUBSTRING(CC.number, 12, 4) AS last_digits, CC.expiration AS expiration
            FROM credit_cards CC
            WHERE CC.id = ?"
    );
    $card_query->bind_param("i", $card_id);
    $card_query->execute();
    return $card_query->get_result();
}

function get_order($order_id, $user_id)
{
    global $mysqli;
    $order_query = $mysqli->prepare("
        SELECT O.id AS id, UNIX_TIMESTAMP(O.time) AS time, O.price AS price, SUBSTRING(CC.number, 12, 4) AS cc_last_digits
        FROM orders O
            INNER JOIN credit_cards CC ON (O.credit_card_id = CC.id)
        WHERE O.id=? AND O.user_id=?
    ");
    $order_query->bind_param("ii", $order_id, $user_id);
    $order_query->execute();
    return $order_query->get_result();
}

function get_ebooks_from_order($order_id)
{
    global $mysqli;
    $order_ebook_query = $mysqli->prepare("
        SELECT E.id AS id, E.title AS title, E.author AS author, OE.price AS price
        FROM ebooks E 
            INNER JOIN order_ebook OE ON (E.id = OE.ebook_id)
        WHERE OE.order_id=?
    ");
    $order_ebook_query->bind_param("i", $order_id);
    $order_ebook_query->execute();
    return $order_ebook_query->get_result();
}
