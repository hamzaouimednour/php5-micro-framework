/*
$(document).ready(function(){
    $.ajaxSetup({cache: false});
    var $container = $("#liveData");
    $container.load("../../redirect.js");
    setInterval(function(){
        $container.load('../../redirect.js');
    }, 5000);
});
*/
window.addEventListener('load', function(){
    var xhr = null;

    getXmlHttpRequestObject = function(){
        if(!xhr){               
            // Create a new XMLHttpRequest object 
            xhr = new XMLHttpRequest();
        }
        return xhr;
    };

    updateLiveData = function(){
        var now = new Date();
        // Date string is appended as a query with live data 
        // for not to use the cached version
        var url = '../../redirect.js?' + now.getTime();
        xhr = getXmlHttpRequestObject();
        xhr.onreadystatechange = evenHandler;
        // asynchronous requests
        xhr.open("GET", url, true);
        // Send the request over the network
        xhr.send(null);
    };

    updateLiveData();

    function evenHandler(){
        // Check response is ready or not
        if(xhr.readyState == 4 && xhr.status == 200){
            dataDiv = document.getElementById('liveData');
            // Set current data text
            dataDiv.innerHTML = xhr.responseText; //get only add data.
            // Update the live data every 10 sec
            setTimeout(updateLiveData(), 10000);
        }
    }
});