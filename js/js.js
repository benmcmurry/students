$(document).ready(function() {
    // fields = [];
    fields = '';
    // set year for copyright update
    var d = new Date();
    var n = d.getFullYear();
    $("span#year").text(n);

    $("#student_id").keypress(function(e) {
        if (e.which == 13) {
            e.preventDefault();
            getReport();
        }
    });
    $("a#get").on("click", function() {
        getReport();
    }); //event listener for report generation
});





function getReport(student_id) {
    student_id = $("#student_id").text();
    console.log("b" + student_id + "e");
    $.ajax({
        type: "POST",
        url: "report.php",
        data: { student_id: student_id }
    }).done(function(phpfile) {
        $("#student_data").html(phpfile);
    });

}