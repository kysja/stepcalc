document.getElementById("platforms").addEventListener("click", function() {
    var platforms = document.getElementById("platforms").checked;
    if (platforms) {
        document.getElementById("divPlatformsQty").style.display = "block";
    } else {
        document.getElementById("divPlatformsQty").style.display = "none";
        document.getElementById("platforms_4").value = 0;
        document.getElementById("platforms_6").value = 0;
        document.getElementById("platforms_8").value = 0;
    }
});

function plaformsNum(id, oper) {
    var num = parseInt(document.getElementById(id).value);
    if (oper == "add") {
        num++;
    } else if (oper == "sub") {
        if (num > 0)
            num--;
    }
    document.getElementById(id).value = num;
}


function testdata() {
    document.getElementsByName("rise_ft")[0].value = '20';
    document.getElementsByName("rise_in")[0].value = '2';
    document.getElementsByName("run_ft")[0].value = '47';
    document.getElementsByName("run_in")[0].value = '5';
    document.getElementById("platforms").checked = true;
    document.getElementById("divPlatformsQty").style.display = "block";
    document.getElementById("railings_both_sides").checked = true;
    document.getElementById("step_color_beige").checked = true;
    document.getElementById("platforms_4").value = 2;
    document.getElementById("platforms_6").value = 0;
    document.getElementById("platforms_8").value = 1;

}