$.validator.addMethod(
  "strongPassword",
  function (value, element) {
    // Kiểm tra xem mật khẩu chứa ít nhất một chữ hoa, một chữ thường, một số và một ký tự đặt biệt
    return (
      this.optional(element) ||
      /(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&*!])[A-Za-z\d@#$%^&*!]+/.test(
        value
      )
    );
  },
  "Mật khẩu phải có ít nhất một chữ hoa, một chữ thường, một số và một ký tự đặt biệt"
);
// Thêm quy tắc kiểm tra email hợp lệ
$.validator.addMethod(
  "validEmail",
  function (value, element) {
    // Sử dụng biểu thức chính quy để kiểm tra email
    return (
      this.optional(element) ||
      /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/.test(value)
    );
  },
  "Email không hợp lệ"
);
$(document).ready(function () {
  $("#login").validate({
    rules: {
      EmailInput: {
        required: true,
        email: true, // Sử dụng quy tắc kiểm tra email tích hợp trong jQuery Validation
      },
      password: {
        required: true,
        minlength: 5,
        strongPassword: true, // Sử dụng quy tắc kiểm tra mật khẩu mạnh
      },
    },
    messages: {
      EmailInput: {
        required: "Bạn chưa nhập vào email",
        email: "Email không hợp lệ",
      },
      password: {
        required: "Bạn chưa nhập vào mật khẩu",
        minlength: "Mật khẩu phải có ít nhất 5 ký tự",
        strongPassword:
          "Mật khẩu phải có ít nhất một chữ hoa, một chữ thường, một số và một ký tự đặt biệt",
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
