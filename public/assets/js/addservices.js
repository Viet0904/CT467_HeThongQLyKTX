$(document).ready(function () {
  $("#serviceForm").validate({
    rules: {
      serviceName: {
        required: true,
        minlength: 4,
      },
      servicePrice: {
        required: true,
        number: true, // Kiểm tra xem giá dịch vụ có phải là số
        min: 1, // Giá dịch vụ phải lớn hơn hoặc bằng 1
      },
    },
    messages: {
      serviceName: {
        required: "Bạn chưa nhập vào tên dịch vụ",
        minlength: "Tên dịch vụ phải có ít nhất 4 ký tự",
      },
      servicePrice: {
        required: "Bạn chưa nhập vào giá dịch vụ",
        number: "Giá dịch vụ phải là một số",
        min: "Giá dịch vụ phải lớn hơn hoặc bằng 1",
      },
    },
    errorElement: "div",
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      if (element.prop("type") === "checkbox") {
        error.insertAfter(element.siblings("label"));
      } else {
        error.insertAfter(element);
      }
    },
    highlight: function (element) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },
    unhighlight: function (element) {
      $(element).addClass("is-valid").removeClass("is-invalid");
    },
  });
});
