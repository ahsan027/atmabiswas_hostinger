document.querySelector(".menu-toggle").addEventListener("click", function () {
  document.querySelector(".bottom-row").classList.toggle("active");
});

document.addEventListener("DOMContentLoaded", function () {
  var currentYear = new Date().getFullYear();

  function startCounters(branchCount) {
    var counters = [
      { id: "number1", end: 1500,           duration: 1500 },
      { id: "number2", end: 100,            duration: 1500 },
      { id: "number3", end: branchCount,    duration: 1500 },
      { id: "number4", end: currentYear - 1994, duration: 1500 },
    ];

    counters.forEach(function (counter) {
      var el = document.getElementById(counter.id);
      if (!el) return;
      var startTs = null;
      function step(ts) {
        if (!startTs) startTs = ts;
        var progress = Math.min((ts - startTs) / counter.duration, 1);
        el.textContent = Math.floor(progress * counter.end);
        if (progress < 1) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
    });
  }

  function fetchAndStart() {
    fetch("/backend/getBranchNumber.php")
      .then(function (res) { return res.json(); })
      .then(function (data) { startCounters(data.value); })
      .catch(function () { startCounters(30); });
  }

  // Only start the animation when the counter section scrolls into view.
  // On mobile the slider (500px) fills the initial viewport, so counters are
  // below the fold — no point burning CPU before the user reaches them.
  var section = document.querySelector(".Numbercontainer");
  if (section && "IntersectionObserver" in window) {
    var observer = new IntersectionObserver(function (entries) {
      if (entries[0].isIntersecting) {
        observer.disconnect();
        fetchAndStart();
      }
    }, { threshold: 0.1 });
    observer.observe(section);
  } else {
    // Fallback for browsers without IntersectionObserver
    fetchAndStart();
  }
});
