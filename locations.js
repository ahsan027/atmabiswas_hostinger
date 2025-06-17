document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("divisionSelect")
    .addEventListener("change", function () {
      const division = this.value;

      const xhr = new XMLHttpRequest();
      xhr.open("POST", "Action/filter.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onload = function () {
        if (xhr.status === 200) {
          const tablebody = document.getElementById("table-body");
          const obj = JSON.parse(xhr.responseText);
          tablebody.innerHTML = "";

          if (obj.length === 0) {
            const row = document.createElement("tr");
            row.innerHTML = `<td colspan="4" style="text-align:center;">In Progress</td>`;
            tablebody.appendChild(row);
            return;
          }

          obj.forEach((element, idx) => {
            const row = document.createElement("tr");

            row.innerHTML = `<td data-label="Branch Name">${element.branchName}</td>
                <td data-label="Branch Location">${element.branchLoc}</td>
                <td data-label="District">${element.dist}</td>

                <td data-label="Division">${element.division}</td>
                `;
            tablebody.appendChild(row);
          });
        }
      };
      xhr.send("division=" + encodeURIComponent(division));
    });

  const toggleBtn = document.getElementById("toggle-btn");
  const contactCard = document.getElementById("contactCard");

  const filterbtn = document.getElementById("filterbutton");
  const filterField = document.getElementById("filterbars");

  // Ensure the elements are available before adding event listeners
  if (toggleBtn && contactCard) {
    toggleBtn.addEventListener("click", () => {
      if (contactCard.classList.contains("active")) {
        contactCard.style.opacity = "0";
        contactCard.style.transform = "translateY(20px)";
        setTimeout(() => {
          contactCard.classList.remove("active");
        }, 500); // Matches the transition duration
      } else {
        contactCard.classList.add("active");
        setTimeout(() => {
          contactCard.style.opacity = "1";
          contactCard.style.transform = "translateY(0)";
        }, 0);
      }
    });
  }

  if (filterbtn && filterField) {
    filterbtn.addEventListener("click", () => {
      if (filterField.classList.contains("active")) {
        filterField.style.opacity = "0";
        filterField.style.transform = "translateY(20px)";
        setTimeout(() => {
          filterField.classList.remove("active");
        }, 500); // Matches the transition duration
      } else {
        filterField.classList.add("active");
        setTimeout(() => {
          filterField.style.opacity = "1";
          filterField.style.transform = "translateY(0)";
        }, 0);
      }
    });
  }

  const newcard = document.getElementById("newcard");
  const newbody = document.getElementById("reg");
  const newtablebody = document.getElementById("newtable");

  newcard.addEventListener("click", () => {
    newbody.classList.toggle("active");

    // Check if the toggle is NOT active, then remove all child elements
    if (!newbody.classList.contains("active")) {
      newtablebody.innerHTML = "";
      return;
    }

    axios.get("regional.json").then((elem) => {
      const object = elem.data;
      console.log(object);

      object.forEach((element, idx) => {
        const row = document.createElement("tr");

        row.innerHTML = `<td data-label="Branch Name">${element.region}</td>
                <td data-label="Branch Location">${element.address}</td>
                <td data-label="Division">${element.designation}</td>
                <td data-label="District">${element.mobile}</td>
                `;
        newtablebody.appendChild(row);
      });
    });
  });
});
