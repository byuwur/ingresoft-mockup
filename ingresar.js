const video = document.getElementById("videoWebcam");
const canvas = document.getElementById("canvasWebcam");
const placa = document.getElementById("placa");
const context = canvas.getContext("2d");

placa.value = "Presione el botón ANALIZAR.";

if (navigator.mediaDevices.getUserMedia)
  navigator.mediaDevices
    .getUserMedia({
      video: true,
    })
    .then(function (stream) {
      video.srcObject = stream;
    })
    .catch(function (err0r) {
      console.log("Algo salió mal...");
    });

function analyzeTextFromImage() {
  placa.value = "Analizando...";

  context.fillRect(0, 0, canvas.width, canvas.height);
  context.drawImage(video, 0, 0, canvas.width, canvas.height);

  Tesseract.recognize(canvas, "eng", {
    logger: (m) => console.log(m),
  }).then(({ data: { text } }) => {
    console.log(text);
    placa.value = text;
    placa.focus();
    verificarPlaca();
  });
}

function restoreResponse() {
  $("#verif-response").removeClass(
    "alert-info alert-danger alert-success alert-warning"
  ),
    $("#verif-response").html("");
}
function verificarPlaca() {
  restoreResponse(),
    $("#verif-response").addClass("alert-info"),
    $("#verif-response").html(
      '<strong>Está verificándose la existencia de la placa "' +
        $("#verif-prefijo").val() +
        $("#verif-factura").val() +
        '".</strong><hr><p>Espere un momento...</p>'
    ),
    $.ajax({
      url: "ingresar.php",
      type: "POST",
      dataType: "json",
      data: $("#verif-form").serialize(),
      success: function (a) {
        $.each(a, function (a, e) {
          restoreResponse(),
            e.error
              ? ($("#verif-response").addClass(e.status),
                $("#verif-response").html(e.message),
                $("#btn-ingreso-in").css({ display: "none", opacity: "0" }),
                $("#btn-ingreso-out").css({ display: "none", opacity: "0" }))
              : ($("#verif-response").addClass(e.status),
                $("#verif-response").html(e.message),
                $("#btn-ingreso-in").css({ display: "block", opacity: "1" }),
                $("#btn-ingreso-out").css({ display: "block", opacity: "1" }));
        });
      },
    });
}

$("#verif-form").change(function () {
  verificarPlaca();
}),
  $("#verif-form").keyup(function () {
    verificarPlaca();
  });
