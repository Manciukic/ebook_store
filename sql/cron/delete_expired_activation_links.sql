DELETE FROM activation_links
WHERE expiration < NOW() OR EXISTS(
    SELECT *
    FROM users
    WHERE id=user_id AND activated
);
