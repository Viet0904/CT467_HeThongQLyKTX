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
$.validator.addMethod(
  "phoneNumber",
  function (value, element) {
    // Kiểm tra xem số điện thoại phải chứa chính xác 10 số
    return this.optional(element) || /^\d{10}$/.test(value);
  },
  "Số điện thoại phải chứa chính xác 10 số"
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
  $("#admin_profile").validate({
    rules: {
      contactName: {
        required: true,
      },
      username: {
        required: true,
        minlength: 4,
      },
      mobile: {
        required: true,
        phoneNumber: true, // Sử dụng quy tắc kiểm tra số điện thoại
      },
      email: {
        required: true,
        email: true, // Kiểm tra email hợp lệ
      },
    },
    messages: {
      contactName: {
        required: "Bạn chưa nhập tên liên hệ",
      },
      username: {
        required: "Bạn chưa nhập vào tên đăng nhập",
        minlength: "Tên đăng nhập phải có ít nhất 4 ký tự",
      },
      mobile: {
        required: "Bạn chưa nhập số điện thoại",
        phoneNumber: "Số điện thoại phải chứa chính xác 10 số",
      },
      email: {
        required: "Bạn chưa nhập địa chỉ email",
        email: "Địa chỉ email không hợp lệ",
      },
      password: {
        required: "Bạn chưa nhập mật khẩu",
        minlength: "Mật khẩu phải có ít nhất 5 ký tự",
        strongPassword:
          "Mật khẩu phải chứa ít nhất một chữ hoa, một chữ thường, một số và một ký tự đặt biệt",
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
