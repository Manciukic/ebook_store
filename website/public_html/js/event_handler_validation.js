function validate(field) {
    var control_field = document.getElementById("control_" + field.name);

    if (field.validity.patternMismatch || field.validity.typeMismatch) {
        field.classList.add("error");
        control_field.classList.remove('hidden');
        control_field.textContent = "Invalid input";
    } else {
        field.classList.remove("error");
        control_field.classList.add('hidden');
    }
}

function check_match(repeated_field, primary_field_id) {
    var primary_field = document.getElementById(primary_field_id);
    var control_repeated_field = document.getElementById("control_" + repeated_field.name);
    if (repeated_field.value != primary_field.value) {
        control_repeated_field.classList.remove('hidden')
        control_repeated_field.textContent = "The passwords don't match!";
    } else {
        control_repeated_field.classList.add('hidden')
    }
}

function update_security(pw_field, security_field_id){
    var security_field = document.getElementById(security_field_id);
    var pw_len = pw_field.value.length;

    if(pw_len == 0){
        security_field.classList.add('hidden');
        return;
    } else {
        security_field.classList.remove("pw-very-weak");
        security_field.classList.remove("pw-weak");
        security_field.classList.remove("pw-strong");
    }
    
    if(pw_len < 5 ){
        security_field.textContent = "very weak";
        security_field.classList.add('pw-very-weak')
    } else if (pw_len < 7){
        security_field.textContent = "weak";
        security_field.classList.add('pw-weak')
    } else {
        security_field.textContent="strong";
        security_field.classList.add('pw-strong')
    }
    security_field.classList.remove('hidden');
}