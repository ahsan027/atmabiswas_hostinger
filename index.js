document.querySelector(".menu-toggle").addEventListener("click", function () {
  document.querySelector(".bottom-row").classList.toggle("active");
});

document.addEventListener("DOMContentLoaded", function () {
  const currentYear = new Date().getFullYear();
 

  fetch("/backend/getBranchNumber.php").then(res=>res.json()).then(data=>{
   
    const counters = [
    { id: "number1", end: 1500, duration: 7000 },
    { id: "number2", end: "100", duration: 5500 },
    { id: "number3", end: data.value, duration: 4000 },
    { id: "number4", end: currentYear - 1994, duration: 4000 },
  ];

  counters.forEach((counter) => {
    let startTimestamp = null;
    const step = (timestamp) => {
      if (!startTimestamp) startTimestamp = timestamp;
      const progress = Math.min(
        (timestamp - startTimestamp) / counter.duration,
        1
      );
      document.getElementById(counter.id).innerText = Math.floor(
        progress * counter.end
      );
      if (progress < 1) {
        window.requestAnimationFrame(step);
      }
    };
    window.requestAnimationFrame(step);
  });
  }).catch(err=>console.log(err))
  
});
