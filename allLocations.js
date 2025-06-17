window.onload = function () {
  getData();
};
function getData() {
  axios
    .get("locations.json")
    .then((response) => {
      const container = document.getElementById("storeId");

      response.data.forEach((element, i) => {
        const link = document.createElement("a");

        link.href = "#";
        link.className = "list-group-item list-group-item-action";
        link.setAttribute("data-lat", element.latitude);
        link.setAttribute("data-lng", element.longitude);

        link.innerHTML = `<small>Branch code: ${element.code}</small><br>
        <h5 class="mb-1">${element.branch_name}</h5>
                            <p class="mb-1">Address: ${element.address}</p>
                            <small>Phone: ${element.mobile}</small><br>
                            <small>Division: ${element.division}, District: ${element.district}</small>`;
        link.addEventListener("click", (e) => {
          e.preventDefault();
          moveToLocation(element.latitude, element.longitude);
        });
        console.log(element);
        container.appendChild(link);
      });
    })
    .catch((e) => console.log(e));
}
