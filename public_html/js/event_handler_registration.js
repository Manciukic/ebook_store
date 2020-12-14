

function checkPasswordSecurity()
{

    var form=document.form_registration;
    if(form.password.value.length==0)
        form.passwordCounter.style="visibility:hidden";

    else if(form.password.value.length<5)
    {
        form.passwordCounter.value="very weak";
        form.passwordCounter.setAttribute('style','visibility:visible;');
    }
    else if(form.password.value.length<7)
    {
        form.passwordCounter.value="weak";
        form.passwordCounter.setAttribute('style','visibility:visible;');
        form.passwordCounter.style.color="#f5e413";
    }
    else{
        form.passwordCounter.value="strong";
        form.passwordCounter.setAttribute('style','visibility:visible;');
        form.passwordCounter.style.color="#9ef513";
    }

}



function registrate(){

    var form=document.form_registration;
    for(var i=0;i < form.elements.length;i++)
    {
        if(form.elements[i].name!="customedQuestion" && form.elements[i].name!="defaultQuestion"){
            if(form.elements[i].className=="registrationInput" && (form.elements[i].value==""))			//Verify wether the fields are empty or not
            {
                document.getElementById("regControl").textContent="Fill all the fields!";
                document.getElementById("regControl").style.display="inline";
                return false;
            }
        }
    }
    for(var j=0;j < form.elements.length;j++)	///verify wether the patterns are met or not
    {
            if(form.elements[j].className=="error")
            {
                document.getElementById("regControl").textContent="Insert valid inputs!";
                document.getElementById("regControl").style.display="inline";
                return false;

            }

    }

    var selectedDefaultQuestion=form.defaultQuestion;
    if(selectedDefaultQuestion.options[selectedDefaultQuestion.selectedIndex].value==="" && form.customedQuestion.value==""){

        document.getElementById("regControl").textContent="Select a secret question!";
        document.getElementById("regControl").style.display="inline";
        return false
    }
    if(selectedDefaultQuestion.options[selectedDefaultQuestion.selectedIndex].value!="" && form.customedQuestion.value!=""){

        document.getElementById("regControl").textContent="Select just one secret question!";
        document.getElementById("regControl").style.display="inline";
        return false
    }

    document.getElementById("regControl").style.display="none";
    return true;
}

function invalidHandler(field){

    var form=document.form_registration;
    if(field.name=="repassword")			//Check if password and repassword match
    {
        if (form.password.value != form.repassword.value)
        {
            form.repassword.className = "registrationInput";
            form.controlRepassword.setAttribute('style','visibility:visible;');
            form.controlRepassword.value="The passwords don't match!";
            return;
        }

    }
    if (field.validity.patternMismatch || field.validity.typeMismatch)
    {
        field.className = "error";
        document.getElementById("control_"+field.name).setAttribute('style','visibility:visible;');
        document.getElementById("control_"+field.name).value="Invalid input";
        return;
    }
    field.className = "registrationInput";
    document.getElementById("control_"+field.name).setAttribute('style','visibility:hidden;');

}

function initializeRegistrationHandler()
{
    var form=document.form_registration;

    for(var i=0;i <form.elements.length;i++)
    {
        if(form.elements[i].className=="registrationInput")
        {
            form.elements[i].onblur=new Function("invalidHandler(this);");
        }
    }
    form.password.onkeyup=new Function("checkPasswordSecurity();");
    form.onsubmit = new Function("return registrate()");

}
initializeRegistrationHandler();



