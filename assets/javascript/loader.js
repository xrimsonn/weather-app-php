window.addEventListener("beforeunload", function() {
    const loader = document.getElementById("loader");
    loader.style.display = "flex";
  });

  document.addEventListener("DOMContentLoaded", function() {
    const loader = document.getElementById("loader");
    loader.style.display = "none";
  });