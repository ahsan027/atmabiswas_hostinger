document.addEventListener("DOMContentLoaded", function () {
  const currentYear = new Date().getFullYear();

  fetch("/backend/getBranchNumber.php").then(res => res.json()).then(data => {
    const counters = [
      { id: "number1", end: 1500,              duration: 7000 },
      { id: "number2", end: 100,  suffix: "K", duration: 5500 },
      { id: "number3", end: data.value,         duration: 4000 },
      { id: "number4", end: currentYear - 1994, duration: 4000 },
    ];

    function runCounters() {
      counters.forEach((counter) => {
        const el = document.getElementById(counter.id);
        if (!el || el.dataset.animated) return;
        el.dataset.animated = "1";
        let startTimestamp = null;
        const step = (timestamp) => {
          if (!startTimestamp) startTimestamp = timestamp;
          const progress = Math.min((timestamp - startTimestamp) / counter.duration, 1);
          el.innerText = Math.floor(progress * counter.end) + (counter.suffix || "");
          if (progress < 1) window.requestAnimationFrame(step);
        };
        window.requestAnimationFrame(step);
      });
    }

    const section = document.querySelector(".Numbercontainer");
    if (!section) return;

    if ("IntersectionObserver" in window) {
      const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
          runCounters();
          observer.disconnect();
        }
      }, { threshold: 0.2 });
      observer.observe(section);
    } else {
      runCounters();
    }
  }).catch(err => console.log(err));
});
