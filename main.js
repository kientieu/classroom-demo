function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

function logup_on_change(val){
    let user_box = document.getElementById("user");
    user_box.value = val.split('@')[0];
}

function del_class(classId, className) {
    document.getElementById('classNameView').innerHTML = className;
    document.getElementById('classIdPost').value = classId;
}

function edit_permission(username) {
    document.getElementById('usernameView').innerHTML = username;
    document.getElementById('usernameEdit').value = username;
}

function del_user(username) {
    document.getElementById('usernameView').innerHTML = username;
    document.getElementById('usernameDel').value = username;
}

function get_data(className) {
    console.log(className);
}

//add_class.php
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

//list_class.php
$(document).ready(function () {
    $(".delete").click(function () {

        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
});

//list_user.php
$(document).ready(function () {
    $(".edit_permission").click(function () {

        $('#re_permission').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
});

//student_view.php
$(document).ready(function () {
    $(".add_student").click(function () {

        $('#add_student').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
});