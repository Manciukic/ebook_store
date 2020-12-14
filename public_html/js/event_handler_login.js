var form=document.form_login;
function send_log(){
    for(var i=0;i < form.elements.length;i++)
    {
        if(form.elements[i].className=="loginInput" && form.elements[i].value=="")
        {
            document.getElementById("emtpyFieldsLog").style.display="inline";
            return false;
        }
    }
    document.getElementById("emtpyFieldsLog").style.display="none";
    return true;
}
form.onsubmit=new Function("return send_log()");








