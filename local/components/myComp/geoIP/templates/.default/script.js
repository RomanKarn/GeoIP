
$(document).ready(function () {
    $("#formSend").on("submit", function () {
        $.ajax({
            url: '/local/components/myComp/geoIP/ajax.php',
            method: 'post',
            dataType: 'json',
            data: { IP: document.getElementById("ip").value,
                ID_HL_BLOC: document.getElementById("id_hl_bloc").value
             },
            success: function (data) {
                if(data["error"])
                {
                    $("#error").removeClass('d-none');
                    $("#error").text(data["data"]);
                }
                else
                {
                    $("#error").addClass('d-none');
                    $("#tabl").removeClass('d-none');
                    $("#contry").text(data["data"]["UF_CONTRI"]);
                    $("#suty").text(data["data"]["UF_SITY"]);
                    $("#cord_x").text(data["data"]["UF_POSITION_X"]);
                    $("#cord_y").text(data["data"]["UF_POSITION_Y"]);
                }
                console.log(data);
            }
        });
        return false;
    });
});

