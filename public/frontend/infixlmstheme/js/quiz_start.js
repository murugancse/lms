let runner;
let totalQus = $('.quiz_assign').val();
startTimer();

//start timer
function startTimer() {
    var presentTime = document.getElementById('timer').innerHTML;
    var timeArray = presentTime.split(/[:]+/);
    var m = timeArray[0];
    var s = checkSecond((timeArray[1] - 1));
    if (s == 59) {
        m = m - 1
    }
    if ((m + '').length == 1) {
        m = '0' + m;
    }
    if (m < 0) {
        $('.submitBtn').trigger('click');
        // submitQuiz();
        // m = '59';
    } else {
        document.getElementById('timer').innerHTML = m + ":" + s;
        runner = setTimeout(startTimer, 1000);
    }

}

//check secound
function checkSecond(sec) {
    if (sec < 10 && sec >= 0) {
        sec = "0" + sec;
    }
    // add zero in front of numbers < 10
    if (sec < 0) {
        sec = "59";
    }

    return sec;
}


$(".skip").click(function (e) {
    e.preventDefault();
    $('.nav-pills .active').parent().next('li').find('a').trigger('click');
});

$(".next").click(function (e) {
    e.preventDefault();
    var qtype = $(".tab-pane:visible").find(".qtype").val();
    var keyid =$(".tab-pane:visible").find(".qtype").attr('id').replace(/qtype/, '');
    //alert(keyid);

    if(qtype=='M' || qtype=='MM' || qtype=='T'){
        let ans = $(".tab-pane:visible").find('.quizAns:checked').val();

        if (ans == "undefined" || ans == "" || ans == null) {
            toastr.error('Please select a option', 'Error Alert', {
                timeOut: 2000
            });
            return false;
        } else {
            var date = new Date();
            var convertedDate = convertTZ(date, "Asia/Singapore");
            var qtime = moment(convertedDate).format('YYYY-MM-DD HH:mm:ss');
           // $("#question_start_time_"+(next_question-1)).val(qtime);

            $("#question_end_time_"+(keyid+1)).val(qtime);
            //alert($("#question_end_time_"+(keyid+1)).val());
            $('.nav-pills .active').parent().next('li').find('a').trigger('click');

        }
    }else{
        var answer = $(".tab-pane:visible").find(".answer").val();
        if(answer==''){
            toastr.error('Please fill the answer', 'Error Alert', {
                timeOut: 2000
            });
            return false;
        } else {
            $('.nav-pills .active').parent().next('li').find('a').trigger('click');

        }
    }
    

});

function convertTZ(date, tzString) {
    return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: tzString}));   
}
