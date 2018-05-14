$(function(){
    // var timerId = setInterval(function() {
    //     enableButton()
    // }, 1000);

    // function enableButton()
    // {
    //     var a = check();
    //     if (a) {
    //         $('button:submit').attr('disabled', true);
    //     } else {
    //         $('button:submit').attr('disabled', false);
    //     }
    // }

    function check()
    {
        var state = true;

	//$('div.field-offer-file1 div.file-caption-name').attr('title') && 
        if ($('div.field-offer-file2 div.file-caption-name').attr('title') && $('div.field-offer-file3 div.file-caption-name').attr('title')) {
            state = false;
        }

        return state;
    }
});