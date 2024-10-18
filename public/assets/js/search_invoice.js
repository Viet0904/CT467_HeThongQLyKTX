$(document).ready(function () {
  $("#customerForm").validate({
    rules: {
      searchinvoice: {
        required: true,
        number: true,
      },
    },
    messages: {
      searchinvoice: {
        required: "Bạn chưa nhập mã hóa đơn",
        number: "Mã hóa đơn phải là số",
      },
    },
    errorElement: "div",
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      error.insertAfter(element);
    },
    highlight: function (element) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },
    unhighlight: function (element) {
      $(element).addClass("is-valid").removeClass("is-invalid");
    },
  });
});
