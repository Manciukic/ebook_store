function validate(field) {
    var control_field = document.getElementById("control_" + field.name);

    if (field.validity.patternMismatch || field.validity.typeMismatch) {
        field.classList.add("error");
        control_field.classList.remove('hidden');
        control_field.textContent = "Invalid password";
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