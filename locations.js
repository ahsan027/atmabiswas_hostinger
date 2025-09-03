document.addEventListener("DOMContentLoaded", function () {
  // Division selection functionality
  const divisionSelect = document.getElementById("divisionSelect");
  if (divisionSelect) {
    divisionSelect.addEventListener("change", function () {
      const division = this.value;
      if (!division) return;

      const xhr = new XMLHttpRequest();
      xhr.open("POST", "Action/filter.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onload = function () {
        if (xhr.status === 200) {
          const tablebody = document.getElementById("table-body");
          if (!tablebody) return;

          try {
            const obj = JSON.parse(xhr.responseText);
            tablebody.innerHTML = "";

            if (obj.length === 0) {
              const row = document.createElement("tr");
              row.innerHTML = `<td colspan="4" style="text-align:center; padding: 20px; color: #666;">No branches found for this division</td>`;
              tablebody.appendChild(row);
              return;
            }

            obj.forEach((element) => {
              const row = document.createElement("tr");
              row.innerHTML = `
                <td data-label="Branch Name">${element.branchName || "N/A"}</td>
                <td data-label="Branch Location">${
                  element.branchLoc || "N/A"
                }</td>
                <td data-label="District">${element.dist || "N/A"}</td>
                <td data-label="Division">${element.division || "N/A"}</td>
              `;
              tablebody.appendChild(row);
            });
          } catch (error) {
            console.error("Error parsing response:", error);
            tablebody.innerHTML = `<tr><td colspan="4" style="text-align:center; color: #ff0000;">Error loading data</td></tr>`;
          }
        } else {
          console.error("Request failed with status:", xhr.status);
        }
      };

      xhr.onerror = function () {
        console.error("Request failed");
        const tablebody = document.getElementById("table-body");
        if (tablebody) {
          tablebody.innerHTML = `<tr><td colspan="4" style="text-align:center; color: #ff0000;">Network error</td></tr>`;
        }
      };

      xhr.send("division=" + encodeURIComponent(division));
    });
  }

  // Toggle button functionality for contact card
  const toggleBtn = document.getElementById("toggle-btn");
  const contactCard = document.getElementById("contactCard");

  if (toggleBtn && contactCard) {
    toggleBtn.addEventListener("click", () => {
      if (contactCard.classList.contains("active")) {
        contactCard.classList.remove("active");
      } else {
        contactCard.classList.add("active");
      }
    });
  }

  // Filter button functionality
  const filterbtn = document.getElementById("filterbutton");
  const filterField = document.getElementById("filterbars");

  if (filterbtn && filterField) {
    filterbtn.addEventListener("click", () => {
      filterField.classList.toggle("active");
    });
  }

  // Regional offices functionality
  const newcard = document.getElementById("newcard");
  const newbody = document.getElementById("reg");
  const newtablebody = document.getElementById("newtable");

  if (newcard && newbody && newtablebody) {
    newcard.addEventListener("click", () => {
      newbody.classList.toggle("active");

      // Clear table when closing
      if (!newbody.classList.contains("active")) {
        newtablebody.innerHTML = "";
        return;
      }

      // Load regional data
      loadRegionalData();
    });
  }

  // Function to load regional data
  function loadRegionalData() {
    if (!newtablebody) return;

    // Show loading state
    newtablebody.innerHTML = `<tr><td colspan="4" style="text-align:center; padding: 20px; color: #666;">Loading regional offices...</td></tr>`;

    axios
      .get("regional.json")
      .then((response) => {
        const object = response.data;
        newtablebody.innerHTML = "";

        if (!Array.isArray(object) || object.length === 0) {
          newtablebody.innerHTML = `<tr><td colspan="4" style="text-align:center; padding: 20px; color: #666;">No regional offices found</td></tr>`;
          return;
        }

        object.forEach((element) => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td data-label="Branch Name">${element.region || "N/A"}</td>
            <td data-label="Branch Location">${element.address || "N/A"}</td>
            <td data-label="Division">${element.designation || "N/A"}</td>
            <td data-label="District">${element.mobile || "N/A"}</td>
          `;
          newtablebody.appendChild(row);
        });
      })
      .catch((error) => {
        console.error("Error loading regional data:", error);
        newtablebody.innerHTML = `<tr><td colspan="4" style="text-align:center; color: #ff0000;">Error loading regional offices</td></tr>`;
      });
  }

  // Add keyboard navigation support
  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      // Close all open sections when Escape is pressed
      if (contactCard && contactCard.classList.contains("active")) {
        contactCard.classList.remove("active");
      }
      if (filterField && filterField.classList.contains("active")) {
        filterField.classList.remove("active");
      }
      if (newbody && newbody.classList.contains("active")) {
        newbody.classList.remove("active");
        if (newtablebody) newtablebody.innerHTML = "";
      }
    }
  });

  // Add focus management for better accessibility
  const toggleButtons = document.querySelectorAll(".toggle-btn");
  toggleButtons.forEach((button) => {
    button.addEventListener("focus", function () {
      this.style.outline = "2px solid #0073e6";
      this.style.outlineOffset = "2px";
    });

    button.addEventListener("blur", function () {
      this.style.outline = "none";
    });
  });

  // Add smooth scrolling for better UX
  const smoothScrollToElement = (element) => {
    if (element) {
      element.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  };

  // Enhance toggle buttons with smooth scrolling
  if (toggleBtn && contactCard) {
    toggleBtn.addEventListener("click", () => {
      if (!contactCard.classList.contains("active")) {
        setTimeout(() => smoothScrollToElement(contactCard), 100);
      }
    });
  }

  if (filterbtn && filterField) {
    filterbtn.addEventListener("click", () => {
      if (!filterField.classList.contains("active")) {
        setTimeout(() => smoothScrollToElement(filterField), 100);
      }
    });
  }

  if (newcard && newbody) {
    newcard.addEventListener("click", () => {
      if (!newbody.classList.contains("active")) {
        setTimeout(() => smoothScrollToElement(newbody), 100);
      }
    });
  }
});
