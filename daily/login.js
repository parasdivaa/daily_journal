document.getElementById("loginForm").addEventListener("submit", function(e) {
  e.preventDefault();

  // username & password yang diizinkan
  const validUser = "danny";
  const validPass = "admin";

  // ambil input user
  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;

  // validasi login
  if (username === validUser && password === validPass) {
    alert("Login berhasil!");
    localStorage.setItem("login", "true");
    window.location.href = "index.html"; // masuk ke web utama
  } else {
    document.getElementById("errorMsg").innerText =
      "Username atau password salah!";
  }
});
