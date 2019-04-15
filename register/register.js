var xmlhttp = createXmlHttpRequestObject();

function createXmlHttpRequestObject() {
    var xmlhttp;
    try {
        xmlhttp = new XMLHttpRequest();
    } catch(e) {
        xmlhttp = false;
    }
    return xmlhttp
}

function process() {
    if(xmlhttp.readyState == 0 || xmlhttp.readyState == 4) {
        user = encodeURIComponent(document.getElementById('username').value);
        xmlhttp.open("GET", "register.php?user="+user,true);
        xmlhttp.onreadystatechange = handleServerResponse;
        xmlhttp.send(null);
    } else {
        setTimeout('process()', 1000);
    }
}

function handleServerResponse() {
    if(xmlhttp.readyState == 4) {
        if(xmlhttp.status == 200) {
            xmlResponse = xmlhttp.responseXML;
            xmlDocumentElement = xmlResponse.documentElement;
            message = xmlDocumentElement.firstChild.data;
            document.getElementById('underUser').innerHTML = message;
            setTimeout('process()', 1000);
        }
    }
}
setInterval(process, 1000);
