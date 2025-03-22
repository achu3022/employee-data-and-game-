$(document).ready(function () {
    $("#emp_id").on("input", function () {
        let emp_id = $(this).val().trim();

        if (emp_id.length > 5) {
            $.post("fetch_employee.php", { emp_id: emp_id }, function (response) {
                if (response.status === "success") {
                    $("#name").text(response.name);
                    $("#phone").text(response.phone);
                    $("#department").text(response.department);
                    $("#user-info").show();
                    $("#next-btn").show();
                    $("#register-btn").hide();
                    $("#error-msg").hide();
                } else {
                    resetUI();
                    $("#error-msg").text("No user found!").show();
                    $("#register-btn").show(); // Show Register button if no user is found
                }
            }, "json");
        } else {
            resetUI();
        }
    });

    function resetUI() {
        $("#user-info").hide();
        $("#next-btn").hide();
        $("#error-msg").hide();
        $("#register-btn").hide();
    }

    $("#next-btn").click(function () {
        let emp_id = $("#emp_id").val().trim();

        if (emp_id.length > 5) {
            fetchIPAddress(emp_id);
        } else {
            alert("Please enter a valid Employee ID");
        }
    });
});

function fetchIPAddress(emp_id) {
    $.getJSON("https://api64.ipify.org?format=json", function (data) {
        let ip = data.ip;

        $.getJSON("get_mac.php", function (macData) {
            let mac = macData.mac;

            // Send data to PHP for validation & redirection
            $.post("check_player.php", { emp_id: emp_id, ip: ip, mac: mac }, function (response) {
                if (response.status === "results") {
                    window.location.href = "results.php?emp_id=" + emp_id; // Already played, show results
                } else if (response.status === "warning") {
                    window.location.href = "device_warning.php?emp_id=" + emp_id; // Device issue, show warning
                } else if (response.status === "quiz") {
                    window.location.href = "quiz.php?emp_id=" + emp_id; // New user, start quiz
                } else {
                    alert("Error: " + response.message);
                }
            }, "json");
        });
    });
}
