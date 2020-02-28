$(function () {

    $(".addUsers, .raport").hide();
    $(".forSelectedForm").hide();
    $(".forSelectedRaport, .forUserRaport").hide();
    $("#addUsersHeader").click(function () {
        $(".addUsers").toggle();
    });
    $("#raportHeader").click(function () {
        $(".raport").toggle();
    });
    $("#selectAll").prop('checked', false).click(batchUsersSelect);
    $(".userselector").prop('checked', false).click(checkUsersSelected);
    $("#addMoreRows").click(function () {
        $("#userTable").append(createManyUserRows($("#userTable tr").length - 1, 10));
    });
    $("#userTable").append(createManyUserRows(0, 15));
});

function checkUsersSelected() {
    if ($(".userselector:checked").length) {
        $("#selectAll").prop('checked', true);
        $(".forSelectedForm").show();
        $(".addUsersForm").hide();

        if ($(".userselector:checked").length > 1) {
            $("#rapwhere").prop('checked', true);
            $(".forSelectedRaport").show();
            $(".forUserRaport").hide();
        } else {
            $("#userRaport").val($(".userselector:checked")[0].id.substr(2));
            $("#rapuser").prop('checked', true);
            $(".forSelectedRaport").hide();
            $(".forUserRaport").show();
        }
    } else {
        $("#selectAll").prop('checked', false);
        $(".forSelectedForm").hide();
        $(".addUsersForm").show();

        if (true) {
            $("#rapclass").prop('checked', true);
            $(".forSelectedRaport").hide();
            $(".forUserRaport").hide();
        }
    }
    $("#where, #where2").val("");
    $(".userselector:checked").each(function (i, elem) {
        $("#where, #where2").val($("#where").val() + (i == 0 ? "" : " OR ") + "userCode=\"" + elem.id.substr(2) + "\"");
    })
}

function batchUsersSelect() {
    if ($("#selectAll:checked").length) {
        $(".userselector").prop('checked', true);
        $(".forSelectedForm").show();
        $(".addUsersForm").hide();

        if ($(".userselector:checked").length > 1) {
            $("#rapwhere").prop('checked', true);
            $(".forSelectedRaport").show();
            $(".forUserRaport").hide();
        } else {
            $("#user").val($(".userselector:checked")[0].id.substr(2));
            $("#rapuser").prop('checked', true);
            $(".forSelectedRaport").hide();
            $(".forUserRaport").show();
        }
    } else {
        $(".userselector").prop('checked', false);
        $(".forSelectedForm").hide();
        $(".addUsersForm").show();

        if (true) {
            $("#rapclass").prop('checked', true);
            $(".forSelectedRaport").hide();
            $(".forUserRaport").hide();
        }
    }
    $("#where, #where2").val("");
    $(".userselector:checked").each(function (i, elem) {
        $("#where, #where2").val($("#where").val() + (i == 0 ? "" : " OR ") + "userCode=\"" + elem.id.substr(2) + "\"");
    })
}

function createUserRow(n) {
    return "<tr><td><input name=\"userCode" + n + "\"></td><td><input name=\"Name" + n + "\"></td><td><input name=\"LastName" + n + "\"></td></tr>"
}

function createManyUserRows(n, count) {
    var result = "";
    for (var i = n; i < n + count; i++) {
        result += createUserRow(i);
    }
    return result;
}
