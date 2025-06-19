document.getElementById("loginForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const username = document.getElementById("username").value.trim();
  const password = document.getElementById("password").value.trim();
  const errorMessage = document.getElementById("error-message");

  if (!username || !password) {
    errorMessage.textContent = "Please fill up the required fields.";
  } else {
    errorMessage.textContent = "";
    // Simulate successful login
    alert("Login successful!");
    // Optionally redirect to another page:
    // window.location.href = "dashboard.html";
  }
});
