<?php 
function validate_card_number($num){
    if (strlen($num) != 16){
        return "Card number must have 16 digits";
    } elseif (!is_numeric($num)){
        return "Card number must be composed of digits";
    } else {
        return true;
    }
}

function validate_card_expiration($exp){
    if (strlen($exp) != 5){
        return "Card expiration must be in the format MM/YY";
    } elseif (!is_numeric(substr($exp, 0, 2))){
        return "Card expiration must be in the format MM/YY";
    } 
    $month = intval(substr($exp, 0, 2));
    $curr_month = intval(date("m"));
    if ($month < 1 || $month > 12){
        return "Month must be within 01 and 12";
    } elseif (!is_numeric(substr($exp, 3, 2))){
        return "Card expiration must be in the format MM/YY";
    }
    
    if ($exp[2] != '/')
        return "Card expiration must be in the format MM/YY";

    $year = intval(substr($exp, 3, 2)) + 2000;
    $curr_year = intval(date("Y"));
    if ($year < $curr_year){
        return "Expiration is before current date";
    } elseif ($year == $curr_year && $month < $curr_month){
        return "Expiration is before current date";
    }

    return true;   
}

function validate_card_cvv($exp){
    if (strlen($exp) != 3){
        return "Card CVV must contain 3 digits";
    } elseif (!is_numeric($exp)){
        return "Card CVV must contain 3 digits";
    } else {
        return true;
    }
}
?>