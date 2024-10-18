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
$(document).ready(function () {
  $("#login").validate({
    rules: {
      username: {
        required: true,
        minlength: 4,
      },
      password: {
        required: true,
        minlength: 5,
        strongPassword: true, // Sử dụng quy tắc kiểm tra mật khẩu mạnh
      },
    },
    messages: {
      username: {
        required: "Bạn chưa nhập vào tên đăng nhập",
        minlength: "Tên đăng nhập phải có ít nhất 4 ký tự",
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
