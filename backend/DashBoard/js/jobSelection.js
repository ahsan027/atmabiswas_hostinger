window.onload = function () {
  fetch("../get_job_position.php")
    .then((res) => res.json())
    .then((data) => {
      if (!Array.isArray(data)) return;
      const position = document.getElementById("jobPosition");
      data.forEach((element) => {
        const opt = document.createElement("option");
        opt.value = element;
        opt.textContent = element;
        position.appendChild(opt);
      });
    })
    .catch((err) => console.error("Failed to load job positions:", err));
};

const position = document.getElementById("jobPosition");

position.addEventListener("change", function () {
  const currentPosition = this.value;
  const jobCode = document.getElementById("jobcode");
  jobCode.value = "No Job position Selected yet...";

  if (currentPosition) {
    fetch(
      `../get_job_code.php?job_title=${encodeURIComponent(currentPosition)}`
    )
      .then((res) => res.json())
      .then((data) => {
        jobCode.value = data[0]["JobCode"];
      });
  }
});
